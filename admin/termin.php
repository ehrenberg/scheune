<?php
require_once('../db_connect.php');
include_once('../Functions.inc.php');

$ID		= null;
$inhalt = null;


if(isset($_POST['edit'])) {
	$ID		= $_POST['id'];
	$Text	= $_POST['text'];
	$Von	= $_POST['von'];
	$Bis	= $_POST['bis'];
	
	$data = Array (
		'Text'	=> $Text,
		'Von'	=> $Von,
		'Bis'	=> $Bis
	);
	$db->where('ID', $ID);
	if ($db->update('termine', $data))echo 'Der Eintrag wurde erfolgreich bearbeitet!';
	else echo 'update failed: ' . $db->getLastError();
}

if(isset($_POST['delete'])) {
	$ID		= $_POST['id'];

	$db->where('ID', $ID);
	if($db->delete('termine')) echo 'Erfolgreich gelöscht';
}


if(isset($_GET['delete'])) {
	$ID = $_GET['delete'];
	
	$inhalt .= '<form method="POST" action="termin.php">
		<input type="hidden" name="id" value="'.$ID.'">
		<input type="submit" name="delete" value="Wirklich löschen">
	</form>';
}

if(isset($_GET['edit'])) {
	$id = $_GET['edit'];
	
	$cols		= array("ID", "Text", "Von", "Bis");
	$db->where("ID", $id);
	$termine	= $db->get(T_TERMINE, null, $cols);
	
	foreach ($termine as $termin) {
		$inhalt .= '<form method="POST" action="index.php?p=termine"><input type="submit" name="back" value="Zurück"></form>
		<form method="POST">
			<input type="text" name="text" value="'.$termin['Text'].'" size="50"><br />
			<input type="text" name="von" value="'.$termin['Von'].'"><input type="text" name="bis" value="'.$termin['Bis'].'"><br />
		<input type="hidden" name="id" value="'.$termin['ID'].'">
		<input type="submit" name="edit" value="Speichern">
		</form>';
	}
}

/*
 * Woche auf einmal eintragen
 */
if(isset($_POST['add_week'])) {
	$text	= $_POST['text'];
	
	$von_std	= $_POST['von_std'];
	$von_min	= $_POST['von_min'];
	$bis_std	= $_POST['bis_std'];
	$bis_min	= $_POST['bis_min'];
	
	$startDate	= new DateTime($_POST['startDate']);
	$endDate	= new DateTime($_POST['endDate']);
	$todayDate	= new DateTime($startDate->format('d.m.Y H:i'));
	$diff		= $startDate->diff($endDate);
	
	for($i = 0; $i <= $diff->days; $i++) {
		$day_von = new DateTime($todayDate->format('d.m.Y').' '.$von_std[$i].':'.$von_min[$i]);
		
		if($bis_std[$i] < $von_std[$i]) {
			date_add($todayDate, date_interval_create_from_date_string('1 day'));
			$day_bis = new DateTime($todayDate->format('d.m.Y').' '.$bis_std[$i].':'.$bis_min[$i]);
		} else {
			$day_bis = new DateTime($todayDate->format('d.m.Y').' '.$bis_std[$i].':'.$bis_min[$i]);
			date_add($todayDate, date_interval_create_from_date_string('1 day'));
		}
		
		$data = Array ("Text"	=> $text[$i],
					   "Von"	=> $day_von->format('Y-m-d H:i:s'),
					   "Bis"	=> $day_bis->format('Y-m-d H:i:s')
		);
		$id = $db->insert('termine', $data);
		if($id)header("Location:termin.php?add=week&ok");
		else echo $db->getLastError();
		
		
	}
	
}

if(isset($_GET['add']) == 'week') {
	if(isset($_GET['ok'])) {
		$inhalt = 'Woche erfolgreich hinzugefügt';
	} else {
		if(isset($_POST['selected'])) {
			$von		= $_POST['von'];
			$bis		= $_POST['bis'];
			$startDate	= new DateTime($von);
			$endDate	= new DateTime($bis);
			$todayDate	= new DateTime($startDate->format('d.m.Y'));
			$diff		= $startDate->diff($endDate);

			$inhalt .= '<form method="POST"><table class="standard">
						<thead>
							<th>Datum</th>
							<th>Text</th>
							<th>Von</th>
							<th>Bis</th>
						</thead>';
			for($i = 0; $i <= $diff->days; $i++) {
				$inhalt .= '<tr>
								<td>'.$todayDate->format('d.m.Y').'</td>
								<td><input type="text" size="40" name="text['.$i.']"></td>
								<td><input type="text" size="2" name="von_std['.$i.']">:<input type="text" size="2" name="von_min['.$i.']"></td>
								<td><input type="text" size="2" name="bis_std['.$i.']">:<input type="text" size="2" name="bis_min['.$i.']"></td>
							</tr>';
				date_add($todayDate, date_interval_create_from_date_string('1 day'));
			}
			
			$inhalt .= '<tr>
							<td colspan="5">
								<input type="hidden" name="startDate" value="'.$startDate->format('d.m.Y').'">
								<input type="hidden" name="endDate" value="'.$endDate->format('d.m.Y').'">
								<input type="submit" name="add_week" value="Speichern">
							</td>
						</tr></table></form>';
		} else {
			$inhalt = '<form method="POST" action="termin.php?add=week">
				<input type="date" name="von">
				<input type="date" name="bis">
				<input type="submit" name="selected" value="Eintrag für Woche">
			</form>';
		}
	}
}

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