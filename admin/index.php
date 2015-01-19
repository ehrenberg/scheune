<?php
include '../include/db_connect.php';
include '../include/functions.inc.php';
require_once('../class/Template.class.php');
require_once( '../class/Plugin.class.php');
sec_session_start($mysqli);
if (!login_check($mysqli) OR !admin_check($mysqli)) header('Location: ../member.php');

$sere		= array ();
$inhalt		= null;
$Template	= new tpl("admin.tpl");

if(isset($_POST['edit_settings'])) {
	$playertext = $_POST['playertext'];
	
	$data = Array ('playerText' => $playertext);
	$db->where ('ID', 1);
	if ($db->update ('settings', $data)) $inhalt .= '<div class="alert-box success">Einstellungen erfolgreich gespeichert</div>';
	else echo 'update failed: ' . $db->getLastError();

}

//Settings laden
$cols		= array("playerText");
$settings	= $db->getOne(T_SETTINGS, null, $cols);


if(isset($_GET['p'])) {
	$p		= $_GET['p'];
	if(isset($_GET['typ']))$typ	= $_GET['typ'];
	
	if($p == 'termine') {
		if(isset($typ) == 'woche') {
			$inhalt .= '<a class="btn" href="index.php?p=termine"><span class="lsf icon2x">list</span> Alle</a>';
			
			//Nächster Sonntag / Heute Sonntag?
			if(date("w", time()) == 0) {
				$sunday		= new DateTime('today');
			} else {
				$sunday		= new DateTime('next sunday');
			}

			/*letzter Montag
			if(date("w", time()) == 1) {
				$monday		= new DateTime('today');
			} else {*/
				$monday		= new DateTime('last monday');
			//}
			
			$cols		= array("ID", "Text", "Von", "Bis");
			$db->where("Von", $monday->format('Y-m-d H:i:s'), ">");
			$db->where("Bis", $sunday->format('Y-m-d H:i:s'), "<");
			$termine	= $db->get(T_TERMINE, null, $cols);
			
		} else {
			$inhalt .= '<a href="index.php?p=termine&typ=woche" class="btn"><span class="lsf icon2x">calendar</span> Aktuelle Woche</a>
						<a href="termin.php?add=week" class="btn"><span class="lsf icon2x">dailycalendar</span> Neue Woche</a>';
			$cols		= array("ID", "Text", "Von", "Bis");
			$termine	= $db->get(T_TERMINE, null, $cols);
		}
			
		$inhalt .= '<table class="standard sortable">
					<thead>
						<th width="60%">Termin</th>
						<th width="12%">Datum</th>
						<th width="10%">Von</th>
						<th width="10%">Bis</th>
						<th width="8%"></th>
					</thead>';
		foreach ($termine as $termin) {
			$inhalt .= '<tr>
					<td>'.$termin['Text'].'</td>
					<td>'.date('d.m.Y', strtotime($termin['Von'])).'</td>
					<td>'.date('H:i', strtotime($termin['Von'])).' Uhr</td>
					<td>'.date('H:i', strtotime($termin['Bis'])).' Uhr</td>
					<td>
						<a href="termin.php?edit='.$termin['ID'].'" class="lsf icon2x">pen</a>
						<a href="termin.php?delete='.$termin['ID'].'" class="lsf icon2x">trash</a>
					</td>
				</tr>';
		}
	
		$inhalt .= '</table>';
	}
} else {
	$sere = array (
			"playertext"	=> $settings['playerText']
	);
	$inhalt = $Template->fill_tpl("start", $sere);
}



$sere = array (
		"title"			=> WEBSITE_NAME,
		"inhalt"		=> $inhalt,
		"AddCSS"		=> $Template->createStyles('admin;screen;iconset', true),
		"AddJS"			=> $Template->createScripts('protoplasm/protoplasm;sorttable', true)
);

echo $Template->fill_tpl("main", $sere);
?>