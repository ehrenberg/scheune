<?php
class member_shoutbox extends plugin {
	static $pluginSettings = array();
	static $pluginHead;
	static $pluginName;
	static $tpl;
	
	static $pluginInfo = array(
			"name"				=> "Shoutbox",
			"description"		=> "Shoutbox",
			"authorName"		=> "Bastian Ehrenberg",
			"authorLink"		=> "http://www.radio-rockscheune.de",
			"version"			=> "0.0.1",
			"configFile"		=> "config.xml",
			"styleFile"			=> "style.css",
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
		
		$text = str_replace(":)", '<img src="'.$picpf.'/1.png" alt="Smilie" border="0" />', $text);
		$text = str_replace(":D", '<img src="'.$picpf.'/2.png" alt="Smilie" border="0" />', $text);
		$text = str_replace("8)", '<img src="'.$picpf.'/3.png" alt="Smilie" border="0" />', $text);
		$text = str_replace(":eek:", '<img src="'.$picpf.'/4.png" alt="Smilie" border="0" />', $text);
		$text = str_replace(":p", '<img src="'.$picpf.'/5.png" alt="Smilie" border="0" />', $text);
		$text = str_replace(":(", '<img src="'.$picpf.'/6.png" alt="Smilie" border="0" />', $text);
		$text = str_replace(":x", '<img src="'.$picpf.'/7.png" alt="Smilie" border="0" />', $text);
		$text = str_replace(":oX:", '<img src="'.$picpf.'/8.png" alt="Smilie" border="0" />', $text);
		$text = str_replace(":roll", '<img src="'.$picpf.'/9.png" alt="Smilie" border="0" />', $text);
		$text = str_replace(";)", '<img src="'.$picpf.'/10.png" alt="Smilie" border="0" />', $text);

		return $text;
	}
}
?>