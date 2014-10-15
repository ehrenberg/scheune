<?php
require_once('db_connect.php');
require_once('class/Template.class.php');
include_once('Functions.inc.php');

//Ermitteln des Wochenanfangs und Ende
$inhalt				= null;
$jahr				= Date("Y");
$kalenderwoche		= strftime("%V");
$tabelle_termine	= null;


$monday		= new DateTime('last monday');
$sunday		= new DateTime('next sunday');

$cols	= array("ID", "Text", "Von", "Bis");
$db->where("Von", $monday->format('d.m.Y H:i:s'), ">");
$db->where("Bis", $sunday->format('d.m.Y H:i:s'), "<");
$termine	= $db->get(T_TERMINE, null, $cols);

foreach ($termine as $termin) { 
	print_r ($user);
	echo '123';
	/*
	$inhalt .= getWochenTag(strtotime($Von));
	$inhalt .= getWochenTag(strtotime($Bis));
	$i++;
	
	if($i == 1)$wochentag = 'Montag';
	else if($i == 2)$wochentag = 'Dienstag';
	else if($i == 3)$wochentag = 'Mittwoch';
	else if($i == 4)$wochentag = 'Donnerstag';
	else if($i == 5)$wochentag = 'Freitag';
	else if($i == 6)$wochentag = 'Samstag';
	else if($i == 7)$wochentag = 'Sonntag';
	
	$tabelle_termine .= '<tr>
							<td>'.date("d.m.Y",strtotime($Von)).'</td>
							<td>'.$wochentag.'</td><td>'.$Text.'</td>
							<td>'.date("H:i",strtotime($Von)).'</td>
							<td>'.date("H:i",strtotime($Bis)).'</td>
						</tr>';
						*/
}

$tabelle_termine .= '<table>';


$i = 0;
/*
while ($stmt->fetch()) {
	$inhalt .= getWochenTag(strtotime($Von));
	$inhalt .= getWochenTag(strtotime($Bis));
	$i++;
	
	if($i == 1)$wochentag = 'Montag';
	else if($i == 2)$wochentag = 'Dienstag';
	else if($i == 3)$wochentag = 'Mittwoch';
	else if($i == 4)$wochentag = 'Donnerstag';
	else if($i == 5)$wochentag = 'Freitag';
	else if($i == 6)$wochentag = 'Samstag';
	else if($i == 7)$wochentag = 'Sonntag';
	
	$tabelle_termine .= '<tr>
							<td>'.date("d.m.Y",strtotime($Von)).'</td>
							<td>'.$wochentag.'</td><td>'.$Text.'</td>
							<td>'.date("H:i",strtotime($Von)).'</td>
							<td>'.date("H:i",strtotime($Bis)).'</td>
						</tr>';
}*/
$tabelle_termine .= '</table>';

$inhalt .= $tabelle_termine;

/*
 *	HAUPTGERÜST
 */
$Template	= new tpl("main.tpl");
$sere = array (
		"title"				=> "Der Schuppen - Termine",
		"inhalt"			=> $inhalt
		//"navigation"		=> create_Navigation($mysqli)
);

echo $Template->fill_tpl("start", $sere);
?>