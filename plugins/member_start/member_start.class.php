<?php
class member_start extends plugin {
	static $pluginSettings = array();
	static $pluginHead;
	static $pluginName;
	static $tpl;
	
	static $pluginInfo = array(
			"name"				=> "Mitglieder - Startseite",
			"description"		=> "Die Startseite der Mitglieder",
			"authorName"		=> "Bastian Ehrenberg",
			"authorLink"		=> "http://www.radio-rockscheune.de",
			"version"			=> "0.0.1",
			"configFile"		=> "",
			"styleFile"			=> "",
			"jsFile"			=> "",
			"templateFile"		=> "",
			"tableName"			=> ""
	);
	
	public function __construct() {
		parent::__construct(self::$pluginInfo, substr(basename(__FILE__), 0, -10));
		self::$pluginSettings	= parent::$PluginSettings;
		self::$pluginName		= parent::$PluginName;
		if(self::$pluginInfo['templateFile'] != '')self::$tpl = new tpl(self::$pluginInfo['templateFile'], parent::$PluginName);
		
		self::$pluginHead		= parent::LoadStyle();
		self::$pluginHead		.= parent::LoadJS();
	}
	public static function LoadToHead() {
		$ret = self::$pluginHead;
		return $ret;
	}
	static function LoadToAdminNav() {
		return '';
	}
	
	static function LoadToStartPage() {
		$html = '';
		return $html;
	}

	static function LoadMemberStart() {
		$ret	= null;
		$sql	= null;
		$UserID	= $_SESSION['user_id'];
		global $db;
		
		$ret .= 'Moinsens '.get_UserName($_SESSION['user_id']).'!<br/>';
		
		return $ret;
	}
}
?>