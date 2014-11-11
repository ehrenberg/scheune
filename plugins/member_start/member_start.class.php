<?php
include_once(DIR_ROOT.'/include/db_connect.php');
include_once(DIR_ROOT.'/include/functions.inc.php');
include_once(DIR_ROOT.'/class/Plugin.class.php');

class member_start extends plugin {
	static $PluginInfo = array(
			"name"				=> "Mitglieder - Startseite",
			"description"		=> "Die Startseite der Mitglieder",
			"authorName"		=> "Bastian Ehrenberg",
			"authorLink"		=> "http://www.radio-rockscheune.de",
			"version"			=> "0.0.1",
			"configFile"		=> "",
			"styleFile"			=> "",
			"jsFile"			=> ""
	);
	
	public function __construct() {
		parent::__construct(self::$PluginInfo, substr(basename(__FILE__), 0, -10));
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