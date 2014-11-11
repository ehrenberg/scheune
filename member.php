<?php
include_once('include/db_connect.php');
include_once('include/functions.inc.php');
include_once('class/Template.class.php');
include_once('class/Plugin.class.php');

sec_session_start($mysqli);

if (!login_check($mysqli)) {
	header("Location:login.php");
}
refreshOnlineUsers($mysqli);

$sere		= array();
$UserID		= $_SESSION['user_id'];
$inhalt		= null;
$selected	= null;

/*
 *	STARTSEITE
 */
$Template	= new tpl("member.tpl");

if(isset($_GET['p'])) {
	$p = cleanInput($_GET['p']);
	switch($p) {
		case 1:$selected = 1;break;
		case 2:$selected = 2;break;
		default:$selected = 1;break;
	}
} else $selected = 1;


$arrayMenue = array(
	1 => array("url" => "member.php?p=1",		"text" => "Start"),
	2 => array("url" => "member.php?p=2",		"text" => "Shoutbox")
);

$sere		= array("menue" => createMenue($arrayMenue, $selected));

switch($selected) {
	case 1:
		$inhalt .= $Template->fill_tpl("start", $sere);
		$inhalt .= $pluginClass->hook("LoadMemberStart");
	break;
	case 2:
		$member_shoutbox	= new member_shoutbox();
		$pluginSettings		= $member_shoutbox->LoadConfig();
		$inhalt .= $Template->fill_tpl("start", $sere);
		if(isset($_POST['save'])) {
			$message = cleanInput($_POST['message']);
			if($member_shoutbox->saveMessage($message)) {
				$inhalt .= '<div class="alert-box success">Nachricht erfolgreich gesendet</div>';
			} else {
				$inhalt .= '<div class="alert-box notice">Du darfst nur alle '.$pluginSettings->MessageLimit.' Minuten eine Nachricht senden</div>';
			}
		}
		
		$inhalt .= $pluginClass->hook("LoadMemberShoutbox");
	break;
	default:
		$inhalt .= $Template->fill_tpl("start", $sere);
		$inhalt .= $pluginClass->hook("LoadMemberStart");
	break;
}
 



/*
 *	HAUPTGERÜST
 */
$Template	= new tpl("main.tpl");
$sere = array (
		"title"				=> "Der Schuppen",
		"inhalt"			=> $inhalt,
		"playerText"		=> $settings['playerText']
);

echo $Template->fill_tpl("start", $sere);

?>