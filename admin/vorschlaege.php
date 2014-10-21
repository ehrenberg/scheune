<?php
require_once('../db_connect.php');
include_once('../Functions.inc.php');

$inhalt = null;

$cols			= array("ID", "Text", "IP", "ErstelltAm");
$db->orderBy("ErstelltAm", "DESC");
$abstimmungen	= $db->get(T_ABSTIMMUNG_VORSCHLAEGE, null, $cols);

$inhalt .= '<form method="POST" action="index.php">
<input type="submit" name="vs_delete" value="ausgewählte Löschen">
<input formaction="abstimmung.phpyyy" type="submit" name="vs_toplist" value="ausgewählte für Abstimmung benutzen">
<br />
<br />
<table class="standard">
	<thead>
		<th>Text</th>
		<th width="23%">Erstellt Am</th>
		<th width="20%">IP</th>
		<th width="5%"></th>
	</thead>';
foreach ($abstimmungen as $vorschlag) {
	$inhalt .= '<tr>
		<td>'.$vorschlag['Text'].'</td>
		<td>'.date('d.m.Y H:i', strtotime($vorschlag['ErstelltAm'])).' Uhr</td>
		<td>'.$vorschlag['IP'].'</a>
		<td><input type="checkbox" name="vorschlag[]"></td>
	</tr>';
}
$inhalt .= '</table></form>';
?>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div id="container">
		<h1>Administratorbereich</h1>
		<div id="navi">
			<ul>
				<li><a href="../">Zurück zur Webseite</a></li>
				<li><a href="index.php">Startseite</a></li>
				<li><a href="index.php?p=termine">Terminkalendar</a></li>
				<li><a href="abstimmung.php">Abstimmungen</a></li>
				<li><a href="vorschlaege.php">Abstimmungen - Vorschläge</a></li>
			</ul>
		</div>
		<div id="content">
		<?php
			echo $inhalt;
		?>
		</div>
		<div class="clear"></div>
	</div>
</body>
</html>