<?php
include_once(DIR_ROOT.'/include/db_connect.php');
include_once(DIR_ROOT.'/include/functions.inc.php');
include_once(DIR_ROOT.'/class/Plugin.class.php');

class member_shoutbox extends plugin {
	
	public $pluginSettings;
	
	public static $PluginInfo = array(
			"name"				=> "Shoutbox",
			"description"		=> "Shoutbox",
			"authorName"		=> "Bastian Ehrenberg",
			"authorLink"		=> "http://www.radio-rockscheune.de",
			"version"			=> "0.0.1",
			"configFile"		=> "config.xml",
			"styleFile"			=> "style.css",
			"jsFile"			=> ""
	);
	
	public function __construct() {
		parent::__construct(self::$PluginInfo, substr(basename(__FILE__), 0, -10));
		$this->pluginSettings = $this->LoadConfig();
	}
	
	public function LoadConfig() {
		$arr = array();
		$arr = simplexml_load_file(dirname(__FILE__)."/".self::$PluginInfo['configFile']);
		return $arr;
	}

	static function LoadMemberShoutbox() {
		$ret	= null;
		$sql	= null;
		$UserID	= $_SESSION['user_id'];
		global $db;
		$ret .= '<style>'.file_get_contents(dirname(__FILE__)."/".self::$PluginInfo['styleFile']).'</style>';
		$ret .= '<div id="shoutbox">';
		$ret .= '<div id="title">Shoutbox</div>';
		$ret .= '<div id="messages">';
		$ret .= self::Messages();
		$ret .= '</div>';
		$ret .= '<div id="newmessage">
			<form method="POST">
			<textarea name="message"></textarea>
			<input type="submit" name="save" value="Senden" class="btn">
			</form>
		</div>';
		$ret .= '</div>';
		return $ret;
	}
	
	public function saveMessage($message) {
		global $db;
		$ret = null;
		$limit = self::MessageLimit();
		
		if($limit == 0) {
			$data = array("Message"	=> $message,
							"Time"	=> date('Y-m-d H:i:s',time()),
							"MemberID"	=> $_SESSION['user_id']);
			if($db->insert(T_SHOUTBOX, $data)) {
				$ret = true;
			}
		} else {
			$ret = false;
		}
		return $ret;
	}
	
	private function MessageLimit() {
		global $db;
		global $pluginSettings;
		$db->where("MemberID", $_SESSION['user_id']);
		$db->where("Time >= '".date('Y-m-d H:i:s',time()-($pluginSettings->MessageLimit*60))."'"); //In den letzten x Minuten
		$count = $db->getOne(T_SHOUTBOX, "COUNT(ID) AS CNT");
		
		return $count['CNT'];
	}
	
	private function Messages() {
		global $db;
		$ret = null;
		$db->orderBy("Time", "DESC");
		$messages = $db->get(T_SHOUTBOX, null, "MemberID, Message, Time");
		
		foreach($messages as $message) {
			$ret .= '<p><span class="user">'.get_Username($message['MemberID']).'</span><span class="time">'.date('d.m.Y H:i', strtotime($message['Time'])).' Uhr</span>'.$message['Message'].'</p>';
		}
		$ret = self::shout_smilies($ret);
		return $ret;
	}
	
	private function shout_smilies($text) {
		global $pluginSettings;
		$picpf = $pluginSettings->SmiliePath;
		
		$text = str_replace(":)", '<img src="'.$picpf.'/1.gif" alt="Smilie" border="0" />', $text);
		$text = str_replace(":D", '<img src="'.$picpf.'/2.gif" alt="Smilie" border="0" />', $text);
		$text = str_replace("8)", '<img src="'.$picpf.'/3.gif" alt="Smilie" border="0" />', $text);
		$text = str_replace(":eek:", '<img src="'.$picpf.'/4.gif" alt="Smilie" border="0" />', $text);
		$text = str_replace(":p", '<img src="'.$picpf.'/5.gif" alt="Smilie" border="0" />', $text);
		$text = str_replace(":(", '<img src="'.$picpf.'/6.gif" alt="Smilie" border="0" />', $text);
		$text = str_replace(":x", '<img src="'.$picpf.'/7.gif" alt="Smilie" border="0" />', $text);
		$text = str_replace(":oX:", '<img src="'.$picpf.'/8.gif" alt="Smilie" border="0" />', $text);
		$text = str_replace(":roll", '<img src="'.$picpf.'/9.gif" alt="Smilie" border="0" />', $text);
		$text = str_replace(";)", '<img src="'.$picpf.'/10.gif" alt="Smilie" border="0" />', $text);

		return $text;
	}
}
?>