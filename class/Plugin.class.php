<?php
$pluginClass = new pluginClass();
include_once(DIR_ROOT."/include/db_connect.php");
if( !defined("INPROCESS")){
	header("HTTP/1.0 403 Forbidden");
	die();
}

class plugin {
	static $PluginInfo = array();
	static $PluginStatus;
	static $PluginName;
	
	public function __construct($info, $filename) {
		self::$PluginInfo = $info;
		self::$PluginName = $filename;
	}
	
	//Informationen des Plugins ermitteln
	static function getInfo() {
		$sere			= self::$PluginInfo;
		$sere['ACTIVE'] = self::getStatus();
		$sere['ID']		= self::getID();
		return $sere;
	}
	
	//Status ermitteln
	public function getStatus() {
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
	public function getID() {
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
	static private $plugins_active		= array();
	static private $plugins_deactive	= array();
	static public $plugins				= array();
	
	
	function __construct() {
		pluginClass::initialize();
	}
	
	static function initialize(){
		$list		= array();   
		$listDB		= array();
		global $mysqli;
		global $db;
		
		// Populate the list of directories to check against
		if ( ($directoryHandle = opendir(DIR_ROOT.'/plugins/' )) == true ) {
			while (($file = readdir( $directoryHandle )) !== false) {
				// Make sure we're not dealing with a file or a link to the parent directory
				if( is_dir('../plugins/' . $file ) && ($file == '.' || $file == '..') !== true )
					array_push( $list, $file );
			}
		}
		
		$dbPlugins = $db->get(T_PLUGIN, null, "Name");
		
		foreach($dbPlugins as $plugin) {
			array_push($listDB,$plugin['Name']);
		}
		
		foreach($listDB as $plugin) {
			if(!file_exists(DIR_ROOT.'/plugins/'.$plugin.'/'.$plugin.'.class.php')) {
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

		if($result = $mysqli->query('SELECT ID, Name, Active FROM '.T_PLUGIN.' ORDER BY Prio ASC')) {
			while ($obj = $result->fetch_object()) {
				pluginClass::register($obj->ID, $obj->Name);
				if($obj->Active == true){
					pluginClass::register($obj->ID, $obj->Name, 'active');
				}
				else pluginClass::register($obj->ID, $obj->Name, 'deactive');
			}
		}
	}
	
	static function hook($checkpoint) {
		$hooks	= null;
		$ret	= null;
		$i		= null;
		global $db;
		
		foreach(pluginClass::$plugins_active as $plugin) {
			if (is_callable(array($plugin, $checkpoint))) {
				$hooks[]	= call_user_func(array($plugin, $checkpoint));
			}
		}
		
		if(count($hooks) > 0)ksort($hooks); //Nach Key Sortieren
		
		for($i = 0;$i < count($hooks);$i++)
			$ret .= $hooks[$i];
		
		return $ret;
	}

	
	static function register($ID, $plugin, $type='') {
		global $config_fullpath;
		require_once(DIR_ROOT."/plugins/$plugin/$plugin.class.php" );
		
		if($type == 'active') {
			pluginClass::$plugins_active[$ID] = $plugin;
		} else if($type == 'deactive') {
			pluginClass::$plugins_deactive[$ID] = $plugin;
		} else {
			pluginClass::$plugins[$ID] = $plugin;
		}
	}
}
?>