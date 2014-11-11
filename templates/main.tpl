[start]
<!DOCTYPE html>
<html>
<head>
	<title>{TITLE}</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
	<link rel="stylesheet" href="css/style.css"/>
	<script type="text/JavaScript" src="js/sha512.js"></script>
	<script type="text/JavaScript" src="js/forms.js"></script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-53856519-2', 'auto');
  ga('send', 'pageview');

</script>
</head>
<body>
<div id="container">
	<div id="header">
		<div id="status">
			<iframe scrolling="no" class="playerstatus" src="http://status.streamplus.de/active2.php?serverid=26174&onlinecolor=black"></iframe>
		</div>
		<div class="clear"></div>
		<div id="player">
			<a href="http://login.streamplus.de/app.php/shoutcast/public/playlist/download/26174.m3u">
				{PLAYERTEXT}<br/>
				<img src="img/radio.png" width="100px" height="66px"><br/>
				Zum Radio
			</a>
		</div>
		<div class="clear"></div>
	</div>
	<div id="navigation">
		<ul>
			<a href="index.php"><li>Startseite</li></a>
			<a href="kontakt.php"><li>Kontakt</li></a>
			<a href="termine.php"><li>Termine</li></a>
			<a href="toplist.php"><li>TOP 20 - Vote</li></a>
			<a href="info.php"><li>Info</li></a>
			<a href="member.php"><li>Clubhaus</li></a>
			{MEMBER_LOGOUT}
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
		<span class="right"><a href="https://www.facebook.com/pages/Radio-RockScheune/513412038789738"><img src="img/ico/fb.jpg" /></a></span>
		<div class="clear"></div>
		
	</div>
</div>
</body>
</html>