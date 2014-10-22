<?php
require_once('../db_connect.php');
include_once('../Functions.inc.php');

$inhalt = null;
/*
 * POST: Abstimmung hinzufügen
 */
if(isset($_POST['add'])) {
	$Bezeichnung	= $_POST['bezeichnung'];
	$GueltigBis		= date('Y-m-d H:i:s',strtotime($_POST['gueltigbis_date'].' '.$_POST['gueltigbis_time']));
	
	$data = Array ("Bezeichnung"	=> $Bezeichnung, "ErstelltAm" => date('Y-m-d H:i:s',time()), "GueltigBis" => $GueltigBis);
	$id = $db->insert(T_ABSTIMMUNG, $data);
	if($id)header("Location:index.php?p=toplist");
	else echo $db->getLastError();
}
/*
 * POST: Titel hinzufügen
 */
if(isset($_POST['add_titel'])) {
	$count = count($_POST['titel']);
	
	$Abstimmung_ID	= $_POST['aid'];
	$titel		= $_POST['titel'];
	$stimmen	= $_POST['stimmen'];
	
	for($i = 0;$i < $count;$i++) {
		$data = Array ("Abstimmung_ID"		=> $Abstimmung_ID,
					"Name"		=> $titel[$i],
					"Stimmen"	=> $stimmen[$i]
		);
		$id = $db->insert(T_ABSTIMMUNG_TITEL, $data);
		if(!$id)echo $db->getLastError();
	}
}
/*
 * POST Abstimmung bearbeiten
 */
if(isset($_POST['edit'])) {
	$ID				= $_POST['id'];
	$Bezeichnung	= $_POST['bezeichnung'];
	$GueltigBis		= date('Y-m-d H:i:s',strtotime($_POST['gueltigbis_date'].' '.$_POST['gueltigbis_time']));
	if(isset($_POST['Aktiv']))$Aktiv			= $_POST['Aktiv'];
	else $Aktiv = false;
	
	if($Aktiv == 'on')$Aktiv = true;
	
	$data = Array (
		'Bezeichnung'	=> $Bezeichnung,
		'GueltigBis'	=> $GueltigBis,
		'Aktiv'			=> $Aktiv
	);
	$db->where('ID', $ID);
	if ($db->update(T_ABSTIMMUNG, $data)) $inhalt .= '<div class="alert-box success">Der Eintrag wurde erfolgreich bearbeitet</div>';
	else echo 'update failed: ' . $db->getLastError();
}
/*
 * POST Titel bearbeiten
 */
if(isset($_POST['edit_titel'])) {
	$ID				= $_POST['id'];
	$Name			= $_POST['name'];
	$Stimmen		= $_POST['stimmen'];
	
	$data = Array (
		'Name'		=> $Name,
		'Stimmen'	=> $Stimmen
	);
	$db->where('ID', $ID);
	if ($db->update(T_ABSTIMMUNG_TITEL, $data)) $inhalt .= '<div class="alert-box success">Der Eintrag wurde erfolgreich bearbeitet</div>';
	else echo 'update failed: ' . $db->getLastError();
}
/*
 * POST Abstimmung löschen
 */
if(isset($_POST['delete'])) {
	$ID		= $_POST['id'];

	$db->where('ID', $ID);
	if($db->delete(T_ABSTIMMUNG)) $inhalt .= '<div class="alert-box success">Erfolgreich gelöscht</div>';
}
/*
 * POST Titel löschen
 */
if(isset($_POST['delete_titel'])) {
	$ID		= $_POST['id'];

	$db->where('ID', $ID);
	if($db->delete(T_ABSTIMMUNG_TITEL)) $inhalt .= '<div class="alert-box success">Erfolgreich gelöscht</div>';
}



//Formular: Abstimmung löschen
if(isset($_GET['delete'])) {
	$ID = $_GET['delete'];
	
	$inhalt .= '<form method="POST" action="abstimmung.php">
		<input type="hidden" name="id" value="'.$ID.'">
		<input type="submit" name="delete" value="Ja Wirklich löschen" class="btn">
		<input type="submit" formaction="abstimmung.php" value="Doch nicht!" class="btn">
	</form>';
}
//Formular: Titel löschen
else if(isset($_GET['delete_titel'])) {
	$ID = $_GET['delete_titel'];
	
	$inhalt .= '<form method="POST" action="abstimmung.php">
		<input type="hidden" name="id" value="'.$ID.'">
		<input type="submit" name="delete_titel" value="Wirklich löschen">
	</form>';
}
/*
 * Formular: Abstimmung bearbeiten
 */
else if(isset($_GET['edit'])) {
	$id = $_GET['edit'];
	
	$cols			= array("ID", "Bezeichnung", "GueltigBis", "Aktiv");
	$db->where("ID", $id);
	$abstimmungen	= $db->get(T_ABSTIMMUNG, null, $cols);
	
	foreach ($abstimmungen as $abstimmung) {
		if($abstimmung['Aktiv'] == 1)$checked = 'checked';
		else $checked = '';
		
		$gueltigbis_date = date('d.m.Y', strtotime($abstimmung['GueltigBis']));
		$gueltigbis_time = date('H:i', strtotime($abstimmung['GueltigBis']));
		
		$inhalt .= '<form method="POST" action="abstimmung.php"><input type="submit" name="back" value="Zurück"></form>
		<br />
		<form method="POST">
			<label for="bezeichnung">Bezeichnung: </label>
			<input type="text" name="bezeichnung" id="bezeichnung" value="'.$abstimmung['Bezeichnung'].'" size="40"><br />
			
			<label for="gueltigbis_date">Gültig Bis: </label>
			<input type="text" name="gueltigbis_date" value="'.$gueltigbis_date.'" class="tcal" size="10">
			<input type="text" name="gueltigbis_time" value="'.$gueltigbis_time.'" size="5"> Uhr<br />
			
			<label for="aktiv">Aktiv: </label><input type="checkbox" name="Aktiv" id="aktiv" '.$checked.'><br />
		<input type="hidden" name="id" value="'.$abstimmung['ID'].'">
		<br/>
		<input type="submit" name="edit" value="Speichern">
		</form>';
	}
} else if(isset($_GET['edit_titel'])) {
	$id = $_GET['edit_titel'];
	
	$cols			= array("ID", "Name", "Stimmen");
	$db->where("ID", $id);
	$titeldaten	= $db->get(T_ABSTIMMUNG_TITEL, null, $cols);
	
	foreach ($titeldaten as $titel) {
		$inhalt .= '<form method="POST" action="abstimmung.php?id='.$_GET['aid'].'"><input type="submit" name="back" value="Zurück"></form>
		<form method="POST">
			<input type="text" name="name" value="'.$titel['Name'].'" size="50"><br />
			<input type="text" name="stimmen" value="'.$titel['Stimmen'].'" size="50"><br />
			
			<input type="hidden" name="id" value="'.$titel['ID'].'">
			<input type="submit" name="edit_titel" value="Speichern">
		</form>';
	}
}
/*
 * Formular: Abstimmung hinzufügen
 */
else if(isset($_GET['add'])) {
	$inhalt .= '<form method="POST" action="abstimmung.php">
	<table>
		<tr>
			<td>Name:</td>
			<td><input type="text" name="bezeichnung" size="30"></td>
		</tr>
		<tr>
			<td>Gültig Bis:</td>
			<td><input type="text" name="gueltigbis_date" class="tcal" size="10"><input type="text" name="gueltigbis_time" size="4"> Uhr (Format:00:00)</td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" name="add" value="Speichern"></td>
		</tr>
	</table>
	</form>';
}
/*
 * Formular: Titel hinzufügen
 */
else if(isset($_GET['add_titel'])) {
	$aid	= $_GET['aid'];
	$count	= $_GET['count'];

	$inhalt .= '<form method="POST" action="abstimmung.php">
	<table class="standard">
		<thead>
			<th>Name</th>
			<th width="10%">Stimmen</th>
		</thead>';
	for($i = 0;$i < $count;$i++) {
		$inhalt .= '<tr>
			<td><input type="text" name="titel[]" style="width:100%"></td>
			<td><input type="text" name="stimmen[]" value="0" style="width:100%"></td>
		</tr>';
	}
		
	$inhalt .= '<tr>
			<td colspan="2"><input type="hidden" name="aid" value="'.$aid.'"><input type="submit" name="add_titel" value="Speichern"></td>
		</tr>
	</table>
	</form>';
}
/*
 * Abstimmung aufrufen
 */
else if(isset($_GET['id'])) {
	$ID				= $_GET['id'];

	$cols			= array("ID", "Bezeichnung");
	$db->where("ID", $ID);
	$abstimmungen	= $db->get(T_ABSTIMMUNG, null, $cols);
	
	foreach ($abstimmungen as $abstimmung) {
		$cols			= array("ID", "Stimmen", "Name");
		$db->where("Abstimmung_ID", $abstimmung['ID']);
		$db->orderBy("Stimmen", "DESC");
		$titeldaten		= $db->get(T_ABSTIMMUNG_TITEL, null, $cols);
		
		$inhalt .= '<form method="GET" action="abstimmung.php">
						<input type="hidden" name="add_titel" value="">
						<input type="hidden" name="aid" value="'.$abstimmung['ID'].'">
						
						Anzahl Titel:<input type="text" name="count" size="4">
						
						<input type="submit" value="hinzufügen" class="btn">
					</form>
					<table class="standard">
						<thead>
							<th>Name</th>
							<th width="11%">Stimmen</th>
							<th width="30%">Abstimmung</th>
							<th></th>
						</thead>';
		foreach ($titeldaten as $titel) {
			$inhalt .= '<tr>
							<td>'.$titel['Name'].'</td>
							<td>'.$titel['Stimmen'].'</td>
							<td>'.$abstimmung['Bezeichnung'].'</td>
							<td>
								<a href="abstimmung.php?edit_titel='.$titel['ID'].'&aid='.$abstimmung['ID'].'"><img src="img/edit.png"></a>
								<a href="abstimmung.php?delete_titel='.$titel['ID'].'"><img src="img/delete.png"></a>
							</td>
						</tr>';
		}
		$inhalt .= '</table>';
	}
	
} else {
	$cols			= array("ID", "Bezeichnung", "ErstelltAm", "GueltigBis");
	$abstimmungen	= $db->get(T_ABSTIMMUNG, null, $cols);
	
	$inhalt .= '<a class="btn" href="abstimmung.php?add">Neue Abstimmung</a>
	<table class="standard">
		<thead>
			<th>Text</th>
			<th>Erstellt Am</th>
			<th>Gültig Bis</th>
			<th></th>
		</thead>';
	foreach ($abstimmungen as $abstimmung) {
		$link = 'abstimmung.php?id='.$abstimmung['ID'].'';
	
		$inhalt .= '<tr>
			<td><a class="link" href="'.$link.'">'.$abstimmung['Bezeichnung'].'</a></td>
			<td><a class="link" href="'.$link.'">'.date('d.m.Y H:i', strtotime($abstimmung['ErstelltAm'])).' Uhr</a></td>
			<td><a class="link" href="'.$link.'">'.date('d.m.Y H:i', strtotime($abstimmung['GueltigBis'])).' Uhr</a></td>
			<td>
				<a href="abstimmung.php?edit='.$abstimmung['ID'].'"><img src="img/edit.png"></a>
				<a href="abstimmung.php?delete='.$abstimmung['ID'].'"><img src="img/delete.png"></a>
			</td>
		</tr>';
	}
	$inhalt .= '</table>';
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