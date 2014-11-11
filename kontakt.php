<?php
require_once('include/db_connect.php');
require_once('class/Template.class.php');
include_once('include/functions.inc.php');

if(isset($_GET['p'])) {
	$p = $_GET['p'];
	if($p == 'danke') {
		$inhalt .= '<h1>Danke für deine Unterstützung</h1>';
	}
}

$zieladresse		= 'mail@radio-rockscheune.de';
$absenderadresse	= 'mail@radio-rockscheune.de';
$absendername		= 'Radio - Rockscheune';
$betreff			= 'Feedback';
$urlDankeSeite		= 'kontakt.php?p=danke';
$trenner			= ":\t";

if ($_SERVER['REQUEST_METHOD'] === "POST") {

	$header = array();
	$header[] = "From: ".mb_encode_mimeheader($absendername, "utf-8", "Q");
	$header[] = "MIME-Version: 1.0";
	$header[] = "Content-type: text/plain; charset=utf-8";
	$header[] = "Content-transfer-encoding: 8bit";
	
    $mailtext = "";

    foreach ($_POST as $name => $wert) {
        if (is_array($wert)) {
		    foreach ($wert as $einzelwert) {
			    $mailtext .= $name.$trenner.$einzelwert."\n";
            }
        } else {
            $mailtext .= $name.$trenner.$wert."\n";
        }
    }

    mail(
    	$zieladresse, 
    	mb_encode_mimeheader($betreff, "utf-8", "Q"), 
    	$mailtext,
    	implode("\n", $header)
    ) or die("Die Mail konnte nicht versendet werden.");
    header("Location: $urlDankeSeite");
    exit;
}



$sere = array();

/*
 *	STARTSEITE
 */
$Template	= new tpl("kontakt.tpl");
$inhalt		= $Template->fill_tpl("start", $sere);

/*
 *	HAUPTGERÜST
 */
$Template	= new tpl("main.tpl");
$sere = array (
		"title"				=> "Rockscheune - Kontakt",
		"inhalt"			=> $inhalt,
		"playerText"		=> $settings['playerText'],
		"member_logout"		=> ''
);
echo $Template->fill_tpl("start", $sere);
?>