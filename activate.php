<?php
include 'include/db_connect.php';
include 'include/functions.inc.php';
require_once('class/Template.class.php');

$Template	= new tpl("register.tpl");
$error_msg	= null;
$boolError	= false;
$sere		= array();

if (isset($_GET['email']) && preg_match('/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/', $_GET['email'])) {
	$email = $_GET['email'];
}

if (isset($_GET['key']) && (strlen($_GET['key']) == 32)) {
	$key = $_GET['key'];
}

if (isset($email) && isset($key)) {
	$data = array('Activation' => NULL, 'Active' => true);
	$db->where("Email", $email);
	$db->where("Activation", $key);
	if($db->update(T_MEMBER, $data)) {
		$inhalt		= $Template->fill_tpl("activated", $sere);
	} else {
		$inhalt		= $Template->fill_tpl("not_activated", $sere);
	}
} else {
	$inhalt		= $Template->fill_tpl("not_activated", $sere);
}
$Template	= new tpl("main.tpl");
$sere = array (
		"title"		=> WEBSITE_NAME." - Aktivierung",
		"inhalt"	=> $inhalt,
		"playerText"		=> $settings['playerText']
);
echo $Template->fill_tpl("start", $sere);
?>