<?php
if(isset($_GET['p'])) {
	$p = $_GET['p'];
	if($p == 'danke')$inhalt .= '<h1>Danke für deine Unterstützung</h1>';
	else $inhalt = '';
}
// An welche Adresse sollen die Mails gesendet werden?
$zieladresse = 'mail@radio-rockscheune.de';

// Welche Adresse soll als Absender angegeben werden?
// (Manche Hoster lassen diese Angabe vor dem Versenden der Mail ueberschreiben)
$absenderadresse = 'mail@radio-rockscheune.de';

// Welcher Absendername soll verwendet werden?
$absendername = 'Radio - Rockscheune';

// Welchen Betreff sollen die Mails erhalten?
$betreff = 'Feedback';

// Zu welcher Seite soll als "Danke-Seite" weitergeleitet werden?
// Wichtig: Sie muessen hier eine gueltige HTTP-Adresse angeben!
$urlDankeSeite = 'kontakt.php?p=danke';

// Welche(s) Zeichen soll(en) zwischen dem Feldnamen und dem angegebenen Wert stehen?
$trenner = ":\t"; // Doppelpunkt + Tabulator

/**
 * Ende Konfiguration
 */

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

header("Content-type: text/html; charset=utf-8");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de">
<head>
	<title>Radio Rockscheune - Wenn`s nicht rockt ist es für`n Arsch</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="css/style.css" />
</head>
<body>
<div id="container">
	<div id="header">
		<div id="status">
			<iframe scrolling="no" class="playerstatus" src="http://status.streamplus.de/active2.php?serverid=24478&onlinecolor=black"></iframe>
		</div>
		
		<div class="clear"></div>
		<div id="player">
			<a href="http://streamplus18.leonex.de:23106/listen.pls"><img src="img/vlc-icon.png"><br/>Click`n Rock</a>
		</div>
		<div class="clear"></div>
	</div>
	<div id="navigation">
		<ul>
			<a href="/"><li>Startseite</li></a>
			<a href="kontakt.php"><li>Kontakt</li></a>
			<a href="info.php"><li>Info</li></a>

                        
		</ul>
		<div class="clear"></div>
	</div>
	
	<div id="content">
		<? echo $inhalt; ?>
        <div style="float:right;top:0px;position:relative;">
			<img src="img/made_in_de.png">
		</div>
		<form action="kontakt.php" method="post" class="contactform">
            <dl>
				<dt>Name:</dt>
				<dd><input type="text" name="Versender" /></dd>
                <dt></dt>
                <dd><input type="radio" name="Geschlecht" value="Mann" />Mann <input type="radio" name="Geschlecht" value="Frau" />Frau</dd>
                <dt>Grüße, Wünsche & Kritik: (oder bei Skype: Radio RockScheune)</dt>
                <dd><textarea maxlength="500" name="Text" rows="8" cols="50"></textarea></dd>
            </dl>
            <p><input type="submit" value="Senden" /></p>
        </form>
		
		<div class="clear"></div>
		<div id="footer">
			<ul>
				<a href="impressum.php"><li>Impressum</li></a>
			</ul>
			<div class="clear"></div>
		</div>
    </body>
</html>