<?php
if( !defined("INPROCESS")){
	header("HTTP/1.0 403 Forbidden");
	die();
}
$pluginClass = new pluginClass();

class plugin {
	static $PluginInfo		= array();
	static $PluginStatus;
	static $PluginName;
	static $PluginSettings	= array();
	
	public function __construct($info, $filename) {
		self::$PluginInfo		= $info;
		self::$PluginName		= $filename;
		if($info['configFile'] != '') {
			self::$PluginSettings	= self::LoadConfig();
		} else {
			self::$PluginSettings	= array();
		}
		if($info['tableName'] != '') {
			$tables = explode(';', $info['tableName']);
			foreach($tables as $table) {
				if($table != '') define("T_".strtoupper($table)."","plg_".$table);
			}
		}
	}
	
	//Config-File laden
	public function LoadConfig() {
		$arr = array();
		$arr = simplexml_load_file(DIR_PLUGINS.'/'.self::$PluginName."/".self::$PluginInfo['configFile']);
		return $arr;
	}
	
	//Style-Files laden
	public function LoadStyle() {
		$ret		= null;
		$admin_path = null;
		if(strpos($_SERVER['REQUEST_URI'], '/admin/'))$admin_path = '../';
		
		
		if(self::$PluginInfo['styleFile'] != '') {
			$styleFiles = explode(';', self::$PluginInfo['styleFile']);
			foreach($styleFiles as $styleFile) {
				if($styleFile != '') {
					
					$ret .= '<link rel="stylesheet" href="'.$admin_path.'plugins/'.self::$PluginName.'/css/'.$styleFile.'">'."\r\n";
				}
			}
		}
		return $ret;
	}
	
	//Javascript Dateien laden
	public function LoadJS() {
		$ret = null;
		$admin_path = null;
		if(strpos($_SERVER['REQUEST_URI'], '/admin/'))$admin_path = '../';
		
		if(self::$PluginInfo['jsFile'] != '') {
			$jsFiles	= explode(';', self::$PluginInfo['jsFile']);
			foreach($jsFiles as $jsFile) {
				if($jsFile != '') {
					$ret .= '<script src="'.$admin_path.'plugins/'.self::$PluginName.'/js/'.$jsFile.'" type="text/javascript"></script>'."\r\n";
				}
			}
		}
		return $ret;
	}
	
	
	//Informationen des Plugins ermitteln
	static function getInfo() {
		$sere			= self::$PluginInfo;
		$sere['ACTIVE'] = self::getStatus();
		$sere['ID']		= self::getID();
		return $sere;
	}
	
	//Status ermitteln
	public static function getStatus() {
		global $db;
		$db->where("Name", self::$PluginName);
		$status = $db->getOne(T_PLUGIN, null, "Active");
		return $status['Active'];
	}
	
	//Status setzen
	public function setStatus($status) {
		global $db;
		$data = Array ("Active" => $status);
		$db->where("Name", self::$PluginName);
		if($db->update(T_PLUGIN, $data)) {
			return true;
		} else {
			return false;
		}
	}
	
	//ID des Plugins ermitteln
	public static function getID() {
		global $db;
		$db->where("Name", self::$PluginName);
		$id = $db->getOne(T_PLUGIN, "ID");
		return $id['ID'];
	}
	
	//Priorität des Plugins ermitteln
	public function getPrio() {
		global $db;
		$db->where("Name", self::$PluginName);
		$prio = $db->getOne(T_PLUGIN, "Prio");
		return $prio['Prio'];
	}
}



class pluginClass {
	static private	$plugins_active		= array();
	static private	$plugins_deactive	= array();
	static public	$plugins			= array();
	
	
	function __construct() {
		pluginClass::initialize();
	}
	
	static function initialize() {
		$list		= array();   
		$listDB		= array();
		global $mysqli;
		global $db;
		
		// Populate the list of directories to check against
		if ( ($directoryHandle = opendir(DIR_PLUGINS.'/')) == true ) {
			while (($file = readdir( $directoryHandle )) !== false) {
				// Make sure we're not dealing with a file or a link to the parent directory
				if( is_dir(DIR_PLUGINS.'/'.$file ) && ($file == '.' || $file == '..') !== true )
					array_push( $list, $file );
			}
		}
		
		$sql = 'SELECT Name FROM '.T_PLUGIN.'';
		$stmt = $mysqli->prepare($sql);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($Name);
		while($stmt->fetch()) {
			array_push($listDB,$Name);
		}
		
		foreach($listDB as $plugin) {
			if(!file_exists(DIR_PLUGINS.'/'.$plugin.'/'.$plugin.'.class.php')) {
				$db->where("Name", $plugin);
				$db->delete(T_PLUGIN);
			}
		}
		
		foreach($list as $plugin) {
			$db->where("Name", $plugin);
			if(!$db->get(T_PLUGIN, null, "Name")) {
				$data = Array ("Active" => false,
								"Name"	=> $plugin);
				$db->insert(T_PLUGIN, $data);
			}
		}

		if($result = $mysqli->query('SELECT ID, Name, Active FROM '.T_PLUGIN.' ORDER BY Prio ASC, ID ASC')) {
			while ($obj = $result->fetch_object()) {
				pluginClass::register($obj->ID, $obj->Name);
				if($obj->Active == true){
					pluginClass::register($obj->ID, $obj->Name, 'active');
				}
				else pluginClass::register($obj->ID, $obj->Name, 'deactive');
			}
		}
	}
	
	static function hook($checkpoint, $plg = null) {
		global $db;
		$hooks	= null;
		$ret	= null;
		$i		= null;
		
		foreach(pluginClass::$plugins_active as $plugin) {
			try {
				if (is_callable(array($plugin, $checkpoint))) {
					$hooks[$plugin]	= call_user_func(array($plugin, $checkpoint));
				}
			} catch(Exception $error) {
				$hooks[$plugin] = $error;
			}
		}
		
		if($plg != null) {
			$ret .= $hooks[$plg];
		} else if(count($hooks) > 0){
			foreach($hooks as $hook) {
				$ret .= $hook;
			}
		} else {
			$ret = '';
		}
		
		return $ret;
	}

	
	static function register($ID, $plugin, $type='') {
		global $config_fullpath;
		require_once(DIR_PLUGINS."/$plugin/$plugin.class.php" );
		
		if($type == 'active') {
			pluginClass::$plugins_active[$ID] = $plugin;
		} else if($type == 'deactive') {
			pluginClass::$plugins_deactive[$ID] = $plugin;
		} else {
			pluginClass::$plugins[$ID] = $plugin;
		}
	}
	
	static function install($pname) {
		$plg = new $pname;
		try {
			if (is_callable(array($pname, "Install"))) {
				return $plg->Install();
			}
		} catch(Exception $error) {
			return false;
		}
	}
	
	static function deinstall($pname) {
		$plg = new $pname;
		try {
			if (is_callable(array($pname, "DeInstall"))) {
				return $plg->DeInstall();
			}
		} catch(Exception $error) {
			return false;
		}
	}
	
}
?>