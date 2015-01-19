<?php
include '../include/db_connect.php';
include '../include/functions.inc.php';
require_once('../class/Template.class.php');
require_once( '../class/Plugin.class.php');
sec_session_start($mysqli);
if (!login_check($mysqli) OR !admin_check($mysqli)) header('Location: ../login.php');

$inhalt = null;
/*
 * POST: Abstimmung hinzufügen
 */
if(isset($_POST['add'])) {
	$Bezeichnung	= $_POST['bezeichnung'];
	$GueltigBis		= date('Y-m-d H:i:s',strtotime($_POST['gueltigbis']));
	
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
	$GueltigBis		= date('Y-m-d H:i:s',strtotime($_POST['gueltigbis']));
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
		<input type="submit" name="delete_titel" value="trash Wirklich löschen" class="btn lsf">
		<input type="submit" formaction="abstimmung.php" value="undo Doch nicht!" class="btn lsf">
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
		
		if($abstimmung['GueltigBis'] != '01.01.1970 01:00') {
			$gueltigbis_date = date('d.m.Y H:i', strtotime($abstimmung['GueltigBis']));
		} else {
			$gueltigbis_date = '';
		}
		
		$inhalt .= '<form method="POST" action="abstimmung.php"><input type="submit" name="back" value="Zurück" class="btn"></form>
		<form method="POST">
			<table class="standard">
				<tr>
					<td>Bezeichnung: </td>
					<td><input type="text" name="bezeichnung" id="bezeichnung" value="'.$abstimmung['Bezeichnung'].'" size="40"></td>
				</tr>
				<tr>
					<td>Gültig Bis:</td>
					<td><input type="text" name="gueltigbis" value="'.$gueltigbis_date.'" class="datepicker_de"></td>
				</tr>
				<tr>
					<td>Aktiv: </td>
					<td><input type="checkbox" name="Aktiv" id="aktiv" '.$checked.'></td>
				</tr>
				<tr>
					<td>
						<input type="hidden" name="id" value="'.$abstimmung['ID'].'">
						<input type="submit" name="edit" value="Speichern" class="btn">
					</td>
				</tr>
			</table>
		</form>';
	}
} else if(isset($_GET['edit_titel'])) {
	$id = $_GET['edit_titel'];
	
	$cols			= array("ID", "Name", "Stimmen");
	$db->where("ID", $id);
	$titeldaten	= $db->get(T_ABSTIMMUNG_TITEL, null, $cols);
	
	foreach ($titeldaten as $titel) {
		$inhalt .= '<form method="POST" action="abstimmung.php?id='.$_GET['aid'].'"><input type="submit" name="back" class="lsf" value="back Zurück"></form>
		<form method="POST">
			<table class="standard">
				<tr>
					<td>Name:</td>
					<td><input type="text" name="name" value="'.$titel['Name'].'" size="50"></td>
				</tr>
				<tr>
					<td>Stimmen:</td>
					<td><input type="number" name="stimmen" value="'.$titel['Stimmen'].'"></td>
				</tr>
				<tr>
					<td colspan="2">
						<input type="hidden" name="id" value="'.$titel['ID'].'">
						<input type="submit" name="edit_titel" class="lsf" value="save Speichern">
					</td>
				</tr>
			</table>
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
			<td><input type="text" name="gueltigbis_date" class="datepicker"><input type="text" class="timepicker" name="gueltigbis_time" size="4"> Uhr (Format:00:00)</td>
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

	$inhalt .= '<form method="POST" action="abstimmung.php?id='.$aid.'">
	<table class="standard">
		<thead>
			<th>Name</th>
			<th width="10%">Stimmen</th>
		</thead>';
	for($i = 0;$i < $count;$i++) {
		$inhalt .= '<tr>
			<td><input type="text" name="titel[]" style="width:85%"></td>
			<td><input type="text" name="stimmen[]" value="0"></td>
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
						Anzahl Titel: <input type="text" name="count" size="4">
						<input type="hidden" name="add_titel" value="">
						<input type="hidden" name="aid" value="'.$abstimmung['ID'].'">
						<input type="submit" value="add" class="btn lsf">
					</form>
					<a href="abstimmung.php?edit='.$abstimmung['ID'].'" class="btn"><span class="lsf">pen</span> Abstimmung bearbeiten</a>
					<table class="standard">
						<thead>
							<th width="45%">Name</th>
							<th width="10%">Stimmen</th>
							<th width="40%">Abstimmung</th>
							<th></th>
						</thead>';
		foreach ($titeldaten as $titel) {
			$inhalt .= '<tr>
							<td>'.$titel['Name'].'</td>
							<td>'.$titel['Stimmen'].'</td>
							<td>'.$abstimmung['Bezeichnung'].'</td>
							<td>
								<a href="abstimmung.php?edit_titel='.$titel['ID'].'&aid='.$abstimmung['ID'].'" class="lsf icon2x">pen</a>
								<a href="abstimmung.php?delete_titel='.$titel['ID'].'" class="lsf icon2x">trash</a>
							</td>
						</tr>';
		}
		$inhalt .= '</table>';
	}
	
} else {
	$cols			= array("ID", "Bezeichnung", "ErstelltAm", "GueltigBis");
	$abstimmungen	= $db->get(T_ABSTIMMUNG, null, $cols);
	
	$inhalt .= '<a href="abstimmung.php?add" class="btn lsf">star Neue Abstimmung</a>
	<table class="standard">
		<thead>
			<th width="50%">Abstimmung</th>
			<th width="20%">Erstellt Am</th>
			<th width="20%">Gültig Bis</th>
			<th width="10%"></th>
		</thead>';
	foreach ($abstimmungen as $abstimmung) {
		$link = 'abstimmung.php?id='.$abstimmung['ID'].'';
		
		if($abstimmung['GueltigBis'] != '1970-01-01 01:00:00') {
			$GueltigBis = date('d.m.Y H:i', strtotime($abstimmung['GueltigBis'])).' Uhr';
		} else {
			$GueltigBis = '';
		}
	
		$inhalt .= '<tr>
			<td>'.$abstimmung['Bezeichnung'].'</td>
			<td>'.date('d.m.Y H:i', strtotime($abstimmung['ErstelltAm'])).' Uhr</td>
			<td>'.$GueltigBis.'</td>
			<td>
				<a href="'.$link.'" class="lsf icon2x">table</a>
				<a href="abstimmung.php?edit='.$abstimmung['ID'].'" class="lsf icon2x">pen</a>
				<a href="abstimmung.php?delete='.$abstimmung['ID'].'" class="lsf icon2x">trash</a>
			</td>
		</tr>';
	}
	$inhalt .= '</table>';
}

$Template			= new tpl("admin.tpl");

$sere = array (
		"title"			=> WEBSITE_NAME,
		"inhalt"		=> $inhalt,
		"AddCSS"		=> $Template->createStyles('admin;screen;iconset', true),
		"AddJS"			=> $Template->createScripts('protoplasm/protoplasm', true),
);

$inhalt = $Template->fill_tpl("start", $sere);
echo $Template->fill_tpl("main", $sere);
?>