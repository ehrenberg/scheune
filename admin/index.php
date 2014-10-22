<?php
require_once('../db_connect.php');
include_once('../Functions.inc.php');

$inhalt = null;

if(isset($_GET['p'])) {
	$p		= $_GET['p'];
	if(isset($_GET['typ']))$typ	= $_GET['typ'];
	
	if($p == 'termine') {
		if(isset($typ) == 'woche') {
			$inhalt .= '<a class="btn" href="index.php?p=termine">Alle</a>';
			
			//Nächster Sonntag / Heute Sonntag?
			if(date("w", time()) == 0) {
				$sunday		= new DateTime('today');
			} else {
				$sunday		= new DateTime('next sunday');
			}

			//letzter Montag
			if(date("w", time()) == 1) {
				$monday		= new DateTime('today');
			} else {
				$monday		= new DateTime('last monday');
			}
			
			$cols		= array("ID", "Text", "Von", "Bis");
			$db->where("Von", $monday->format('Y-m-d H:i:s'), ">");
			$db->where("Bis", $sunday->format('Y-m-d H:i:s'), "<");
			$termine	= $db->get(T_TERMINE, null, $cols);
			
		} else {
			$inhalt .= '<a class="btn" href="index.php?p=termine&typ=woche">Aktuelle Woche</a>
						<a class="btn" href="termin.php?add=week">Neue Woche eintragen</a>';
			$cols		= array("ID", "Text", "Von", "Bis");
			$termine	= $db->get(T_TERMINE, null, $cols);
		}
			
		$inhalt .= '<table class="standard">
					<thead>
						<th>Text</th>
						<th>Von</th>
						<th>Bis</th>
						<th></th>
					</thead>';
		foreach ($termine as $termin) {
			$inhalt .= '<tr>
					<td>'.$termin['Text'].'</td>
					<td>'.date('d.m.Y H:i', strtotime($termin['Von'])).' Uhr</td>
					<td>'.date('d.m.Y H:i', strtotime($termin['Bis'])).' Uhr</td>
					<td>
						<a href="termin.php?edit='.$termin['ID'].'"><img src="img/edit.png"></a>
						<a href="termin.php?delete='.$termin['ID'].'"><img src="img/delete.png"></a>
					</td>
				</tr>';
		}
	
		$inhalt .= '</table>';
	}
} else {
	$inhalt .= '<div class="box_overview">
					<span class="title">Termine</span>
					<a class="btn" href="index.php?p=termine&typ=woche">Aktuelle Woche anzeigen</a>
					<a class="btn" href="termin.php?add=week">Neue Termine eintragen</a>
				</div>
				<div class="box_overview">
					<span class="title">Abstimmungen</span>
					<a class="btn" href="abstimmung.php?add">Neue Abstimmung erstellen</a>
				</div>';
}

?>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" href="../css/tcal.css">
	<script type="text/javascript" src="../js/simpletcal.js"></script>
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