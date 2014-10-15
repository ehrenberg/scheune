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