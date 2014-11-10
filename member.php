<?php
include_once('include/db_connect.php');
include_once('include/functions.inc.php');
include_once('class/Template.class.php');

sec_session_start($mysqli);

if (!login_check($mysqli)) {
	notLoggedIn();
}

$sere	= array();
$UserID	= $_SESSION['user_id'];
$inhalt	= null;

/*
 *	STARTSEITE
 */
$Template	= new tpl("member.tpl");

$arrayMenue = array(
	1 => array("url" => "member.php?p=1",		"text" => "Start"),
	2 => array("url" => "member.php?p=2",		"text" => "Shoutbox")
);

if(isset($_GET['p'])) {
	$p = cleanInput($_GET['p']);
	switch($p) {
		case 1:
			$selected = 1;
		break;
		case 2:
			$selected = 2;
		break;
		default:
			$selected = 1;
		break;
	}
}
 
$sere		= array("menue" => createMenue($arrayMenue, $selected));
$inhalt .= $Template->fill_tpl("start", $sere);

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