<?php
require_once('../db_connect.php');
include_once('../Functions.inc.php');

$inhalt = null;

if(isset($_GET['vs_delete_ok'])) {
	$inhalt .= '<div class="alert-box success">Erfolgreich gelöscht</div>';
}
if(isset($_GET['abs_add_ok'])) {
	$inhalt .= '<div class="alert-box success">Abstimmung erfolgreich erstellt</div>';
}
/*
 * POST Vorschläge löschen
 */
if(isset($_POST['add_abstimmung'])) {
	$text_abstimmung	= $_POST['text_abstimmung'];
	$vorschlag_name		= $_POST['vorschlag_name'];
	$vorschlag_stimmen	= $_POST['vorschlag_stimmen'];
	$gueltig_bis		= date('Y-m-d', strtotime($_POST['gueltig_bis_date'])).' '.date('H:i:s', strtotime($_POST['gueltig_bis_time']));
	$count				= count($vorschlag_name);
	
	$data = Array ("Bezeichnung"	=> $text_abstimmung, "ErstelltAm" => date('Y-m-d H:i:s', time()), "GueltigBis" => $gueltig_bis);
	$id = $db->insert(T_ABSTIMMUNG, $data);
	
	for($i = 0;$i < $count;$i++) {
		$data = Array ("Name" => $vorschlag_name[$i], "Abstimmung_ID" => $id,"Stimmen" => $vorschlag_stimmen[$i]);
		$db->insert(T_ABSTIMMUNG_TITEL, $data);
	}
	header("Location:vorschlaege.php?abs_add_ok");
}
/*
 * POST Vorschläge löschen
 */
else if(isset($_POST['do'])) {
	$do		= $_POST['do'];
	$key	= array_keys($do);
	
	//Vorschläge löschen
	if($key[0] == 0) {
		$vorschlag	= $_POST['vorschlag'];
		$count		= count($vorschlag);
		foreach($vorschlag as $key => $v) {
			$db->where('id', $v);
			$db->delete(T_ABSTIMMUNG_VORSCHLAEGE);
		}
		header("Location:vorschlaege.php?vs_delete_ok");
	}
	//Vorschläge für Abstimmung
	else if($key[0] == 1) {
		$vorschlag	= $_POST['vorschlag'];
		$count		= count($vorschlag);
		
		$inhalt .= '<form method="POST" action="vorschlaege.php">
					<table class="standard">
						<tr>
							<td>
								Name Abstimmung:
							</td>
							<td>
								<input type="text" name="text_abstimmung" id="text_abstimmung" style="width:100px">
							</td>
						</tr>
						<tr>
							<td>
								<label for="gueltig_bis_date">Gültig Bis: </label>
							</td>
							<td>
								<input type="text" name="gueltig_bis" class="datetimepicker" value="'.date('d.m.Y H:i',time()).'">
							</td>
						</tr>
					</table>
					<table class="standard">
						<thead>
							<th width="80%">Name</th>
							<th>Stimmen</th>
						</thead>';
		foreach($vorschlag as $key => $v) {
			$cols		= array("ID", "Text", "IP", "ErstelltAm");
			$db_vorschlag	= $db->get(T_ABSTIMMUNG_VORSCHLAEGE, null, $cols);
			$inhalt		.= '<tr>
								<td><input type="text" name="vorschlag_name[]" value="'.$db_vorschlag[0]['Text'].'" style="width:100%;"></td>
								<td><input type="text" name="vorschlag_stimmen[]" value="0" style="width:100%;"></td>
							</tr>';
		}
		$inhalt .= '</table>
					<input type="submit" name="add_abstimmung" value="Abstimmung hinzufügen" class="btn">
				</form>';
	}
}
//Liste der Vorschläge anzeigen
else {
	$cols			= array("ID", "Text", "IP", "ErstelltAm");
	$db->orderBy("ErstelltAm", "DESC");
	$abstimmungen	= $db->get(T_ABSTIMMUNG_VORSCHLAEGE, null, $cols);

	$inhalt .= '<form method="POST" action="vorschlaege.php">
					<input type="submit" value="ausgewählte Löschen" class="btn" name="do[0]">
					<input type="submit" value="ausgewählte für Abstimmung benutzen" class="btn" name="do[1]">
	<table class="standard">
		<thead>
			<th>Vorschlag</th>
			<th width="23%">Erstellt Am</th>
			<th width="20%">IP</th>
			<th width="5%"></th>
		</thead>';
	foreach ($abstimmungen as $vorschlag) {
		$inhalt .= '<tr>
			<td>'.$vorschlag['Text'].'</td>
			<td>'.date('d.m.Y H:i', strtotime($vorschlag['ErstelltAm'])).' Uhr</td>
			<td>'.$vorschlag['IP'].'</a>
			<td><input type="checkbox" name="vorschlag[]" value="'.$vorschlag['ID'].'"></td>
		</tr>';
	}
	$inhalt .= '</table></form>';
}
?>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" href="../css/tcal.css">
	<script type="text/javascript" src="../js/simpletcal.js"></script>
	<link rel="stylesheet" href="../js/protoplasm/protoplasm_full.css">
	<script type="text/javascript" src="../js/protoplasm/protoplasm_full.js"></script>
	<script language="javascript">
		Protoplasm.use('datepicker')
			.transform('input.datetimepicker', { 'locale': 'de_DE','timePicker':true, dateTimeFormat: 'dd.MM.yyyy HH:mm', use24hrs: true, });
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