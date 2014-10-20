[start]
<!DOCTYPE html>
<html>
<head>
	<title>{TITLE}</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
	<script type="text/javascript" src="jPlayer/js/jquery.jplayer.min.js"></script>
	<link rel="stylesheet" href="css/style.css" />
</head>
<body>
<div id="container">
	<div id="header">
		<div id="status">
			<iframe scrolling="no" class="playerstatus" src="http://status.streamplus.de/active2.php?serverid=26174&onlinecolor=black"></iframe>
		</div>
		<div class="clear"></div>
		<div id="player">
			Auf Sendung :   <br/>Rock NonStop<br/>
			<a href="http://login.streamplus.de/app.php/shoutcast/public/playlist/download/26174.m3u"><img src="img/radio.png"><br/>Zum Radio</a>
		</div>
		<div class="clear"></div>
	</div>
	<div id="navigation">
		<ul>
			<a href="index.php"><li>Startseite</li></a>
			<a href="kontakt.php"><li>Kontakt</li></a>
			<a href="termine.php"><li>Termine</li></a>
			<a href="toplist.php"><li>Abstimmen</li></a>
			<a href="info.php"><li>Info</li></a>
		</ul>
		<div class="clear"></div>
	</div>
	<div id="content">
		{INHALT}
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