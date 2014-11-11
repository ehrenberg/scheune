<?php
require_once('include/db_connect.php');
include_once('include/functions.inc.php');
require_once('class/Template.class.php');
$sere = array();

/*
 *	STARTSEITE
 */
$Template	= new tpl("start.tpl");
$inhalt		= $Template->fill_tpl("start", $sere);

/*
 *	HAUPTGERÜST
 */
$Template	= new tpl("main.tpl");
$sere = array (
		"title"				=> "Rockscheune - Wenn's nicht rockt, isses für'n Arsch",
		"inhalt"			=> $inhalt,
		"playerText"		=> $settings['playerText'],
		"member_logout"		=> ''
);
echo $Template->fill_tpl("start", $sere);
?>