<?php
require_once('db_connect.php');
require_once('class/Template.class.php');
include_once('Functions.inc.php');

$sere			= array();
$inhalt			= null;
$today			= new DateTime();
$canVotePos		= true;
$canVoteNeg		= true;
$canVoteBoth	= true;

if (!isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	$client_ip = $_SERVER['REMOTE_ADDR'];
} else {
	$client_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
}

if(isset($_GET['add_vorschlag_ok']))$inhalt .= '<div class="alert-box notice">Danke für deinen Vorschlag!</div>';

/*
 * Vorschlag POST
 */
if(isset($_POST['add_vorschlag'])) {
	$text = $_POST['vorschlag'];

	//Vorschlag eintragen
	$data = Array ("Text" => $text, "IP" => $client_ip);
	$id = $db->insert(T_ABSTIMMUNG_VORSCHLAEGE, $data);
	header("Location:toplist.php?add_vorschlag_ok");
}


/*
 * Voten
 */
if(isset($_GET['aid'])) {
	$aid = $_GET['aid'];
	
	$cols		= array("ID", "IP", "Pos", "Neg");
	$db->where("Abstimmung_ID", $aid);
	$ipdaten	= $db->get(T_ABSTIMMUNG_IP, null, $cols);
	
	foreach($ipdaten as $ip) {
		if($client_ip == $ip['IP']) {
			if($ip['Pos'] == 1) {
				$canVotePos = false;
			}
			if($ip['Neg'] == 1) {
				$canVoteNeg = false;
			}
			if($ip['Pos'] == 1 AND $ip['Neg'] == 1) {
				$canVoteBoth = false;
			}
			
		}
	}
	
	//Negative
	if(isset($_GET['vote_neg']) AND $canVoteNeg) {
		$id = $_GET['vote_neg'];
		
		//IP eintragen
		$data = Array ("Abstimmung_ID" => $aid, "IP" => $client_ip, "Neg" => '1');
		$db->insert(T_ABSTIMMUNG_IP, $data);
		
		$cols		= array("Stimmen");
		$db->where("ID", $id);
		$stimmen	= $db->get(T_ABSTIMMUNG_TITEL, null, $cols);
		
		foreach($stimmen as $stimme) {
			$anz_stimmen = $stimme["Stimmen"]-1;
		}
		
		$data = Array ('Stimmen' => $anz_stimmen);
		$db->where('ID', $id);
		$db->update(T_ABSTIMMUNG_TITEL, $data);
	}
	
	//Positiv
	if(isset($_GET['vote_pos']) AND $canVotePos) {
		$id = $_GET['vote_pos'];
		$data = Array ("Abstimmung_ID" => $aid, "IP" => $client_ip, "Pos" => '1');
		$db->insert(T_ABSTIMMUNG_IP, $data);
		
		$cols		= array("Stimmen");
		$db->where("ID", $id);
		$stimmen	= $db->get(T_ABSTIMMUNG_TITEL, null, $cols);
		
		foreach($stimmen as $stimme) {
			$anz_stimmen = $stimme["Stimmen"]+1;
		}
		
		$data = Array ('Stimmen' => $anz_stimmen);
		$db->where('ID', $id);
		$db->update(T_ABSTIMMUNG_TITEL, $data);
	}
	header("Location:toplist.php");
}


/*
 * Tabelle erstellen
 */
$cols		= array("ID", "Bezeichnung", "GueltigBis", "Aktiv");
$abstimmungen	= $db->get(T_ABSTIMMUNG, null, $cols);

foreach($abstimmungen as $abstimmung) {
	$gueltigBis = new DateTime($abstimmung['GueltigBis']);
	$aktiv		= $abstimmung['Aktiv'];
	
	$cols		= array("ID", "IP", "Pos", "Neg");
	$db->where("Abstimmung_ID", $abstimmung['ID']);
	$ipdaten	= $db->get(T_ABSTIMMUNG_IP, null, $cols);
	
	foreach($ipdaten as $ip) {
		if($client_ip == $ip['IP']) {
			if($ip['Pos'] == 1) {
				$canVotePos = false;
			}
			if($ip['Neg'] == 1) {
				$canVoteNeg = false;
			}
			if($ip['Pos'] == 1 AND $ip['Neg'] == 1) {
				$canVoteBoth = false;
			}
			
		}
	}
	
	if($aktiv == true) {
		$inhalt .= 'Einem Titel kannst du deine Stimme geben. Einem anderen kannst du die Kugel verpassen.<br /><br />';
		$inhalt .= '<h2>'.$abstimmung['Bezeichnung'].'</h2><h4>Bis: '.$gueltigBis->format('d.m.Y H:i').' Uhr</h4>';
		
		$cols		= array("ID", "Name", "Stimmen");
		$db->orderBy("Stimmen","DESC");
		$titeldaten	= $db->get(T_ABSTIMMUNG_TITEL, null, $cols);
		
		$inhalt .= '<table class="termine">
					<thead>
						<th>Titel</th>
						<th width="10%">Stimmen</th>
						<th width="20%">Bewerten</th>
					</thead>';
		foreach($titeldaten as $titel) {

			$inhalt .= '<tr>
							<td>'.$titel['Name'].'</td>
							<td>'.$titel['Stimmen'].'</td>
							<td width="15%">';
								if($canVotePos) {
									$inhalt .= '<a class="btnVote" href="toplist.php?aid='.$abstimmung['ID'].'&vote_pos='.$titel['ID'].'">Geil !</a>';
								}
								if($canVoteNeg) {
									$inhalt .= '<a class="btnVote" href="toplist.php?aid='.$abstimmung['ID'].'&vote_neg='.$titel['ID'].'">Nö !</a>';
								}
							$inhalt .= '</td>
						</tr>';
		}
		$inhalt .= '</table>';
	}
}
	$inhalt .= '<form method="POST">
					<br /><br />
					<label>Hast du einen Vorschlag für einen geilen Titel? Dann schreib uns:</label><br /><br />
					<input type="text" name="vorschlag" style="width:200px">
					<input type="submit" name="add_vorschlag" value="Vorschlagen">
				</form>';
/*
 *	HAUPTGERÜST
 */
$Template	= new tpl("main.tpl");
$sere = array (
		"title"				=> "Rockscheune - Wenn's nicht rockt, isses für'n Arsch",
		"inhalt"			=> $inhalt
		//"navigation"		=> create_Navigation($mysqli)
);

echo $Template->fill_tpl("start", $sere);
?>