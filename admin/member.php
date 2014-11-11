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

if(isset($_GET['edit_ok'])) $inhalt .= '<div class="alert-box success">Benutzer erfolgreich bearbeitet</div>';


//Member bearbeiten
else if(isset($_GET['edit'])) {
	$ID = cleanInput($_GET['edit']);
	
	//Passwort zurücksetzen
	if(isset($_GET['pwreset'])) {
		$cols = Array ("ID", "Username", "Email", "Reset_Hash");
		$db->where("ID", $ID);
		$user = $db->getOne("".T_MEMBER."", null, $cols);
			
		$reset_hash = sha1(md5(rand(0,2000)));
		
		$data = Array ('Reset_Hash'=> $reset_hash);
		$db->where ('ID', $user['ID']);
		$db->update ("".T_MEMBER."", $data);

		$empfaenger	= $user['Email'];
		$absender	= EMAIL;
		$antwortan	= $absender;

		$header		= "MIME-Version: 1.0\r\n";
		$header		.= "Content-type: text/html; charset=iso-8859-1\r\n";
		$header		.= "From: $absender\r\n";
		$header		.= "Reply-To: $antwortan\r\n";
		$header		.= "X-Mailer: PHP ". phpversion();

		$betreff	= WEBSITE_NAME.' - Passwort zurückgesetzt';
		$nachricht	= 'Hallo '.$user['Username'].',<br />Klicke http://www.radio-rockscheune.de/password_reset.php?id='.$user['Reset_Hash'].' um das Passwort deines Accounts neu zu vergeben!<br /><br />Weiterhin Viel Spaß :)';

		if(mail($empfaenger,$betreff,$nachricht,$header)) {
			header("Location:member.php");
		}
	}
	//Änderungen übernehmen
	else if(isset($_POST['edit_member'])) {
		$username	= cleanInput($_POST['username']);
		$email		= cleanInput($_POST['email']);
		
		$data = Array (
			'Username'	=> $username,
			'Email'		=> $email
		);
		$db->where ('id', $ID);
		if($db->update ("".T_MEMBER."", $data)) {
			header("Location:member.php?edit_ok");
		}
		
	}
	//Formular anzeigen
	else {
		$cols = Array ("ID", "Username", "Email", "Online_Since", "Online_Last", "IsAdmin", "Active");
		$db->where("ID", $ID);
		$user = $db->getOne("".T_MEMBER."", null, $cols);
		$sere['id']				= $user['ID'];
		$sere['username']		= $user['Username'];
		$sere['email']			= $user['Email'];
		$inhalt .= $Template->fill_tpl("member_editform", $sere);
	}
} else {

	$cols = Array ("ID", "Username", "Email", "Online_Since", "Online_Last", "IsAdmin", "Active");
	/*
	 * Suche
	 */
	if(isset($_POST['search'])) {
		$username = $_POST['username'];
		$db->where("Username LIKE '%".$username."%'");
	}
	$users = $db->get("".T_MEMBER."", null, $cols);
	//Suche
	$inhalt .= '<form method="POST" action="member.php">
		<table class="standard">
			<tr>
				<td>Benutzername</td>
				<td><input type="text" name="username" /></td>
				<td><input type="submit" name="search" value="Suchen"></td>
			</tr>
		</table>
	</form>';
	//Benutzertabelle
	$inhalt .= '<table class="standard">
				<thead>
					<th>Benutzername</th>
					<th>E-Mail</th>
					<th>Online seit</th>
					<th>zuletzt Online</th>
					<th width="2%"></th>
					<th width="2%"></th>
				</thead>';



	foreach($users as $user) {
		$sere['id']				= $user['ID'];
		$sere['username']		= $user['Username'];
		$sere['email']			= $user['Email'];
		
		if($user['Online_Since'] != NULL) {
			$sere['onlinesince']	= date('d.m.Y H:i',$user['Online_Since']);
		} else $sere['onlinesince'] = '';
		
		if($user['Online_Last'] != NULL) {
			$sere['onlinelast']		= date('d.m.Y H:i',$user['Online_Last']);
		} else $sere['onlinelast'] = '';
		
		$inhalt .= $Template->fill_tpl("member_bit", $sere);
	}

	$inhalt .= '</table>';
}
/*
 *	HAUPTGERÜST
 */
$sere = array (
		"title"			=> WEBSITE_NAME,
		"inhalt"		=> $inhalt
);
echo $Template->fill_tpl("main", $sere);
?>