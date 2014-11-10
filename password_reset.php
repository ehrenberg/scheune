<?php
include 'include/db_connect.php';
include 'include/functions.inc.php';
require_once('class/Template.class.php');

sec_session_start($mysqli);

//Variablen
$Template = new tpl("login.tpl");
$ID = $_GET['id'];

//Passwort zurücksetzen
if(isset($_POST['p'])) {
		$password		= $_POST['p'];
		$random_salt 	= hash('sha512', uniqid(mt_rand(0,16), TRUE));
		$user_id		= $_POST['userid'];
		
		$password = hash('sha512', $password . $random_salt);

		if($stmt = $mysqli->prepare("UPDATE ".T_MEMBER." SET Salt = ?, Password = ?, Reset_Hash = NULL WHERE ID = ?")) {
			$stmt->bind_param('ssi',$random_salt,$password,$user_id);
			$stmt->execute();
			$stmt->close();
			$inhalt .= 'Neues Passwort erfolgreich vergeben :)<br /><a href="login.php"><button>Login</button></a>';
		} else {
			$inhalt .= '<div class="error">Fehler</div>';
		}
} else {
	$inhalt .= '<div class="error">Du hast kein Passwort eingegeben!</div>';
}

//Link aus Email wird aufgerufen
if(isset($ID)) {
	if ($stmt = $mysqli->prepare("SELECT ID FROM ".T_MEMBER." WHERE Reset_Hash = ? LIMIT 1")) {
        $stmt->bind_param('s', $ID);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($user_id);
        $stmt->fetch();
		
		if($stmt->num_rows() == 1) {
			$sere		= array("userid" => $user_id);
			$inhalt		= $Template->fill_tpl("password_reset", $sere);
		} else {
			$inhalt = '<div class="error">Dieser Link ist ungültig!</div>';
		}
	}
}


$sere = array (
		"title"		=> WEBSITE_NAME." - Passwort zurücksetzen",
		"inhalt"	=> $inhalt
);
echo $Template->fill_tpl("main", $sere);

?>