<?php
require_once('db_connect.php');
require_once('class/Template.class.php');
include_once('Functions.inc.php');

$sere	= array();
$inhalt = null;



/*
 *	HAUPTGERÜST
 */
$Template	= new tpl("main.tpl");
$sere = array (
		"title"				=> "Der Schuppen - Wenn's nicht rockt, isses für'n Arsch",
		"inhalt"			=> $inhalt
		//"navigation"		=> create_Navigation($mysqli)
);

echo $Template->fill_tpl("start", $sere);
?>