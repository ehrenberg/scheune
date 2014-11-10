<?php
include 'include/db_connect.php';
include 'include/functions.inc.php';
require_once('class/Template.class.php');

//Template: LOGIN
$Template	= new tpl("register.tpl");
$error_msg	= null;
$boolError	= false;

if (isset($_POST['username'], $_POST['email'], $_POST['p'])) {
	if(CAN_REGISTER == 'NO') {
		$error_msg = '<div class="alert-box error">Die Registrierung ist nicht aktiviert</div>';
	}
	else {
		$error_msg  = null;
		
		$username	= filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
		$email		= filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
		$email		= filter_var($email, FILTER_VALIDATE_EMAIL);
		$password	= filter_input(INPUT_POST, 'p', FILTER_SANITIZE_STRING);
		
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$boolError = true;
			$error_msg .= '<div class="alert-box error">Die eingegebene E-Mail-Adresse ist nicht korrekt</div>';
		}
		if (strlen($password) != 128) {
			$boolError = true;
			$error_msg .= '<div class="alert-box error">Das Passwort ist nicht korrekt</div>';
		}

		$db->where("Email", $email);
		$db->getOne(T_MEMBER,"ID");
		if($db->count > 0) {
			$boolError = true;
			$error_msg .= '<div class="alert-box error">Es existiert bereits ein Benutzer mit dieser E-Mail-Adresse</div>';
		}
		
		if ($boolError == false) {
			$random_salt	= hash('sha512', uniqid(mt_rand(0,16), TRUE));
			$password		= hash('sha512', $password . $random_salt);
			$activation		= md5(uniqid(rand(), true));
			
			$data = array("Username"	=> $username,
							"Email"		=> $email,
							"Password"	=> $password,
							"Salt"		=> $random_salt,
							"Activation"=> $activation,
							"Active"	=> false
			);
			$id = $db->insert(T_MEMBER, $data);
			if($id != 0) {
                $message	= " Klicke auf folgenden Link, um deinen Account zu aktivieren:\n\n";
                $message	.= DIR_ROOT.'/activate.php?email='.urlencode($email)."&key=$activation";
                mail($email, 'Registrierung Bestätigung', $message, 'From: '.EMAIL);
				
				header('Location: index.php?registered');
			} else {
				$error_msg .= '<div class="alert-box error">Fehler bei der Registrierung</div>';
			}
		}
	}
}


$sere		= array("errormsg" 		=> $error_msg);
$inhalt		= $Template->fill_tpl("register", $sere);
$Template	= new tpl("main.tpl");
$sere = array (
		"title"		=> WEBSITE_NAME." - Registrierung",
		"inhalt"	=> $inhalt,
		"playerText"		=> $settings['playerText']
);
echo $Template->fill_tpl("start", $sere);

?>