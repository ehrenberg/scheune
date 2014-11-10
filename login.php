<?php
include 'include/db_connect.php';
include 'include/functions.inc.php';
require_once('class/Template.class.php');

sec_session_start($mysqli);

$sere			 = array();
$strFriends 	 = null;
$txtmail		 = null;
$txtmail_checked = null;
$txtError		 = null;
$boolError		 = false;
$inhalt			 = '';

//Template: LOGIN
$Template	= new tpl("login.tpl");

/*
 *	LOGIN _ POST
 */
if (isset($_POST['email'], $_POST['p'])) {
    $email		= $_POST['email'];
    $password	= $_POST['p']; //hashed Passwort
	$openedURL	= $_SESSION['openedURL'];
	
	if(isset($_POST['remember_login']) OR isset($_POST['remember_email'])){
		setcookie("login_user", $email, time()+60*60*24*100, "/");
	}
	
	if(isset($_POST['remember_email'])){
		setcookie("mail", $email, time()+60*60*24*100, "/");
	} else {
		setcookie("mail", '0', time()+60*60*24*100, "/");
	}
	
    if (login($email, $password, $mysqli)) {
		if(isset($openedURL) != '') {
			$_SESSION['openedURL'] = '';
			header('Location: '.$openedURL.'');
		} else {
			header('Location: member.php');
		}
    } else {
        header('Location: login.php?error=1');
    }
}


/*
 *	RESET _ POST
 */
if(isset($_POST['reset'], $_POST['email'])) {
	if ($stmt = $mysqli->prepare("SELECT ID, Username, Reset_Hash FROM ".T_MEMBER." WHERE Email = ? LIMIT 1")) {
        $stmt->bind_param('s', $_POST['email']);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($user_id, $username, $reset_hash);
        $stmt->fetch();
		
		if($stmt->num_rows() == 1) {
			$reset_hash = sha1(md5(rand(0,2000)));
			$mysqli->query("UPDATE ".T_MEMBER." SET Reset_Hash = '".$reset_hash."' WHERE ID = ".$user_id."");
			
			$empfaenger = $_POST['email'];
			$absender   = EMAIL;
			$antwortan  = $absender;
			
			$header  = "MIME-Version: 1.0\r\n";
			$header .= "Content-type: text/html; charset=iso-8859-1\r\n";
			$header .= "From: $absender\r\n";
			$header .= "Reply-To: $antwortan\r\n";
			// $header .= "Cc: $cc\r\n";  // falls an CC gesendet werden soll
			$header .= "X-Mailer: PHP ". phpversion();
			
			$betreff	= WEBSITE_NAME.' - Passwort zurückgesetzt';
			$nachricht  = 'Hallo '.$username.',<br />Klicke http://www.radio-rockscheune.de/password_reset.php?id='.$reset_hash.' um das Passwort deines Accounts neu zu vergeben!<br /><br />Weiterhin Viel Spaß :)';
			
			if(mail($empfaenger,$betreff,$nachricht,$header)) {
				$inhalt .= 'Hallo '.$username.',<br/>Wir haben dir nun eine E-Mail mit einem Link zum Zurücksetzen deines Passworts gesendet.';
			}
			
		} else {
			$inhalt = 'Diese E-Mail Adresse ist nicht bekannt<br /><a href="login.php?reset"><button>Zurück</button></a>';
		}
	}
} else {
	if(isset($_COOKIE['mail'])){
		$txtmail		 = $_COOKIE['mail'];
		$txtmail_checked = 'checked="checked"';
	}

	if(isset($_GET['error'])) {
		$boolError = true;
		$txtError = '<div class="error">Fehler beim Einloggen. Versuche es noch Mal</div>';
	}

	$sere		= array(
				  "txtmail"  		=> $txtmail,
				  "errormsg" 		=> $txtError,
				  "txtmail_checked" => $txtmail_checked
	);

	if(isset($_GET['reset'])) {
		$inhalt		= $Template->fill_tpl("reset", $sere);
	} else {
		$inhalt		= $Template->fill_tpl("start", $sere);
	}
}

$Template	= new tpl("main.tpl");
$sere = array (
		"title"		=> WEBSITE_NAME." - Login",
		"inhalt"	=> $inhalt,
		"playerText"		=> $settings['playerText']
);
echo $Template->fill_tpl("start", $sere);
?>