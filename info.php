<?php
require_once('db_connect.php');
require_once('class/Template.class.php');
include_once('Functions.inc.php');
$sere = array();

/*
 *	STARTSEITE
 */
$Template	= new tpl("info.tpl");
$inhalt		= $Template->fill_tpl("start", $sere);

/*
 *	HAUPTGERÜST
 */
$Template	= new tpl("main.tpl");
$sere = array (
		"title"				=> "Der Schuppen - Info",
		"inhalt"			=> $inhalt
		//"navigation"		=> create_Navigation($mysqli)
);

echo $Template->fill_tpl("start", $sere);
?>