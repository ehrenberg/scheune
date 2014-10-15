<?php
	require_once('db_connect.php');
	require_once('Functions.inc.php');
	
	//Ermitteln des Wochenanfangs und Ende
	$jahr				= Date("Y");
	$kalenderwoche		= strftime("%V");
	$timestamp_montag	= strtotime("{$jahr}-W{$kalenderwoche}");
	$timestamp_sonntag	= strtotime("{$jahr}-W{$kalenderwoche}-7");
	
	$tabelle_termine = null;

	
	$sql = "SELECT ID, Text, Von, Bis
			FROM ".T_TERMINE." T
			WHERE Von > '".date('Y-m-d H:i:s', $timestamp_montag)."'
			AND Bis < '".date('Y-m-d H:i:s', $timestamp_sonntag)."'
			ORDER BY T.Von ASC";
	if (!$stmt = $mysqli->prepare($sql)) {
		echo $mysqli->error;		
	}
	
	if (!$stmt->execute()) {
		echo $stmt->error;
	}
	
	$stmt->bind_result($ID,$Text,$Von,$Bis);
	
	echo getWochenTag(strtotime($Von));
	
	$i = 0;
	while ($stmt->fetch())
	{
		$i++;
		
		if($i == 1)$wochentag = 'Montag';
		else if($i == 2)$wochentag = 'Dienstag';
		else if($i == 3)$wochentag = 'Mittwoch';
		else if($i == 4)$wochentag = 'Donnerstag';
		else if($i == 5)$wochentag = 'Freitag';
		else if($i == 6)$wochentag = 'Samstag';
		else if($i == 7)$wochentag = 'Sonntag';
		
		$tabelle_termine .= '<tr><td>'.date("d.m.Y",strtotime($Von)).'</td><td>'.$wochentag.'</td><td>'.$Text.'</td><td>'.date("H:i",strtotime($Von)).'</td><td>'.date("H:i",strtotime($Bis)).'</tr>';
	}
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
			<a href="http://streamplus18.leonex.de:23106/listen.pls"><img src="img/vlc-icon.png" width="40px" height="40px"><br/>Click`n Rock</a>
		</div>
		<div class="clear"></div>
	</div>
	<div id="navigation">
		<ul>
			<a href="/"><li>Startseite</li></a>
			<a href="kontakt.php"><li>Kontakt</li></a>
			<a href="termine.php"><li>Termine</li></a>
			<a href="info.php"><li>Info</li></a>
		</ul>
		<div class="clear"></div>
	</div>
	<div id="content">
		<p>Radio RockScheue 24 Stunden non stop werbefreie Edelware</p>
		<br />
		<table class="table_termine">
			<?php
				echo $tabelle_termine;
			?>
		</table>
		<img src="img/no_limit.png">
	</div>
	
	<div id="footer">
		<ul>
			<a href="impressum.php"><li>Impressum</li></a>
		</ul>
		<div class="clear"></div>
	</div>
</div>
</body>

</html>