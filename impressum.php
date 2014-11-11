<?php
require_once('include/db_connect.php');
require_once('class/Template.class.php');
include_once('include/functions.inc.php');
$sere = array();

/*
 *	STARTSEITE
 */
$Template	= new tpl("impressum.tpl");
$inhalt		= $Template->fill_tpl("start", $sere);

/*
 *	HAUPTGERÜST
 */
$Template	= new tpl("main.tpl");
$sere = array (
		"title"				=> "Rockscheune - Impressum",
		"inhalt"			=> $inhalt,
		"playerText"		=> $settings['playerText'],
		"member_logout"		=> ''
);
echo $Template->fill_tpl("start", $sere);
?>