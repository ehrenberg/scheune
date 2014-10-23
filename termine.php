<?php
require_once('db_connect.php');
require_once('class/Template.class.php');
include_once('Functions.inc.php');

//Ermitteln des Wochenanfangs und Ende
$inhalt				= null;
$jahr				= Date("Y");
$kalenderwoche		= strftime("%V");
$tabelle_termine	= null;
$i					= null;

$monday		= new DateTime('last monday');

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
$db->where("Bis", $sunday->format('Y-m-d').' 23:59:59', "<=");
$db->orderBy("Von","ASC");
$termine	= $db->get(T_TERMINE, null, $cols);

$tabelle_termine .= '<table class="termine">
						<thead>
							<th>Wochentag</th>
							<th>Sendung</th>
							<th>Von</th>
							<th>Bis</th>
						</thead>';
						
foreach ($termine as $termin) {
	
	$tabelle_termine .= '<tr>
							<td class="wday">'.getWochenTag(strtotime($termin['Von'])).' '.date('d.m.Y',strtotime($termin['Von'])).'</td>
							<td>'.$termin['Text'].'</td>
							<td class="time">'.date("H:i",strtotime($termin['Von'])).'</td>
							<td class="time">'.date("H:i",strtotime($termin['Bis'])).'</td>
						</tr>';
}

$tabelle_termine .= '</table>';

$inhalt .= $tabelle_termine;

/*
 *	HAUPTGERÜST
 */
$Template	= new tpl("main.tpl");
$sere = array (
		"title"				=> "Rockscheune - Termine",
		"inhalt"			=> $inhalt,
		"playerText"		=> $settings['playerText']
);
echo $Template->fill_tpl("start", $sere);
?>