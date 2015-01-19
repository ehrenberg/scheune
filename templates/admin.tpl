[main]
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<title>{TITLE}</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
{ADDCSS}
{ADDJS}
<script language="javascript">
	Protoplasm.use('datepicker')
		.transform('input.datepicker', { 'locale': 'de_DE' });
		
	Protoplasm.use('timepicker')
		.transform('input.timepicker', {use24hrs: true});
</script>
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
		<h1>Administratorbereich</h1>
	</div>
	<div id="navi">
		<ul>
			<li><a href="../">Zurück zur Webseite</a></li>
			<li><a href="index.php">Startseite</a></li>
			<li><a href="index.php?p=termine">Terminkalendar</a></li>
			<li><a href="abstimmung.php">Abstimmungen</a></li>
			<li><a href="vorschlaege.php">Abstimmungen - Vorschläge</a></li>
			<li><a href="templates.php">Templates</a></li>
			<li><a href="member.php">Benutzerverwaltung</a></li>
			<li><a href="plugins.php">Plugins</a></li>
		</ul>
	</div>
	<div id="content">
	{INHALT}
	</div>
	<div class="clear"></div>
</div>
</body>
</html>

############ STARTSEITE ################
[start]
<div class="box_overview">
	<span class="title">Termine</span>
	<a class="btn" href="index.php?p=termine&typ=woche">Aktuelle Woche anzeigen</a>
	<a class="btn" href="termin.php?add=week">Neue Termine eintragen</a>
</div>
<div class="box_overview">
	<span class="title">Abstimmungen</span>
	<a class="btn" href="abstimmung.php?add">Neue Abstimmung erstellen</a>
</div>
<div class="box_overview">
	<span class="title">Einstellungen</span>
	<form method="POST" action="index.php">
		<table class="standard">
			<tr>
				<td>Text über Radio:</td>
				<td><input type="text" name="playertext" size="40" value="{PLAYERTEXT}"></td>
			</tr>
			<tr>
				<td colspan="2"><input type="submit" name="edit_settings" value="Bearbeiten" class="btn"></td>
			</tr>
		</table>
	</form>
</div>


#
#  PLUGINS
#
[start_plugins]
<ul class="pluginList">
{PLUGINLIST}
</ul>
<div class="clear"></div>

[plugin_bit]
<li>
	<a href="plugins.php?edit={ID}">{NAME}</a><span class="right">{ICO_ACTIVE}</span>&nbsp;&nbsp;<span class="right">v.{VERSION}</span></li>
</li>




#
# Mitglieder
#
[member_bit]
<tr>
	<td>{USERNAME}</td>
	<td>{EMAIL}</td>
	<td>{ONLINESINCE}</td>
	<td>{ONLINELAST}</td>
	<td><a href="member.php?edit={ID}"><img src="../img/ico/edit.png"></a></td>
	<td><a href="member.php?edit={ID}&pwreset"><img src="../img/ico/pw-reset.png"></a></td>
</tr>

[member_editform]
<a href="member.php" class="btn">Zurück</a>
<br /><br />
<form method="POST" action="member.php?edit={ID}">
	<table class="standard">
		<tr>
			<td>Benutzername</td>
			<td><input type="text" name="username" value="{USERNAME}"></td>
		</tr>
		<tr>
			<td>E-Mail</td>
			<td><input type="text" name="email" value="{EMAIL}"></td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" name="edit_member" class="btn" value="Speichern"></td>
		</tr>
	</table>
</form>