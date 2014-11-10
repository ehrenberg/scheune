<?php
include_once 'config.inc.php';
include_once 'db_connect.php';

function sec_session_start() {
    $session_name = 'session_id';   // Set a custom session name
    $secure = SECURE;
    // This stops JavaScript being able to access the session id.
    $httponly = true;
    // Forces sessions to only use cookies.
    ini_set('session.use_only_cookies', 1);
    // Gets current cookies params.
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"],
        $cookieParams["path"], 
        $cookieParams["domain"], 
        $secure,
        $httponly);
    // Sets the session name to the one set above.
    session_name($session_name);
    session_start();            // Start the PHP session 
    session_regenerate_id();    // regenerated the session, delete the old one. 
}

/*
 * Online-Zeiten der Benutzer aktualisieren
 */
function refreshOnlineUsers($mysqli) {
	if(isset($_SESSION['user_id'])) {
	
		$vor = (strtotime(date("d.m.Y H:i:s")) - (strtotime(date("d.m.Y H:i:s") - 30*60)));
		
		if($mysqli->query("UPDATE ".T_MEMBER."
						SET Online_Since = '".strtotime(date("d.m.Y H:i:s"))."'
						WHERE ID = '".$_SESSION['user_id']."'"))
		{
			$mysqli->query("UPDATE ".T_MEMBER."
						SET Online_Since = NULL
						WHERE Online_Since < ".$vor." AND ID != '".$_SESSION['user_id']."'");
		}
		
		return true;
	}
}


/*
 * Einloggen des Benutzers
 *
 */
function login($email, $password, $mysqli) {
	if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$sql = "SELECT ID, Username, Password, Salt FROM ".T_MEMBER." WHERE Email = ? LIMIT 1";
	} else {
		$sql = "SELECT ID, Username, Password, Salt FROM ".T_MEMBER." WHERE Username = ? LIMIT 1";
	}
	
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($user_id, $username, $db_password, $salt);
        $stmt->fetch(); 
        // hash von Passwort mit Salt
        $password = hash('sha512', $password . $salt);
		
		//Existiert der Benutzer(E-Mail)?
        if ($stmt->num_rows == 1) {
			//Brute-Force unterbinden
            if (checkbrute($user_id, $mysqli) == true) {
                return false;
            } else {
				//Stimmt das Passwort?
                if ($db_password == $password) {
                    $user_browser	= $_SERVER['HTTP_USER_AGENT'];
                    $user_id		= preg_replace("/[^0-9]+/", "", $user_id);                    
                    $username		= preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username);
					
					$_SESSION['user_id'] = $user_id;
                    $_SESSION['username'] = $username;
                    $_SESSION['login_string'] = hash('sha512', $password . $user_browser);
					$_SESSION['user_browser'] = $user_browser;
					
					//"Zuletzt online" und "Online seit" aktualisieren
					$sql = "UPDATE ".T_MEMBER." SET Online_Since = '".time("Y-m-d H:i:s")."',Online_Last = ".time()." WHERE ID = ?";
					if (!$stmt = $mysqli->prepare($sql)) echo $mysqli->error;
					$stmt->bind_param('i', $_SESSION['user_id']);
					if (!$stmt->execute()) echo $stmt->error;
					
					//Ist das der erste Login?
					//Wenn, dann Profil erzeugen
					if(firstlogin_check($mysqli)) {
						$mysqli->query("INSERT INTO ".T_MEMBER_PROFILE."(member_id) VALUES ('".$_SESSION['user_id']."')");	
					}
					
					$stmt->close();
                    return true;
                } else {
					//Password incorrect
                    $now = time();
                    $mysqli->query("INSERT INTO ".T_LOGIN_ATTEMPTS."(User_ID, time) VALUES ('$user_id', '$now')");
                    return false;
				}
            }
        } else {
            //Benutzer existiert nicht
            return false;
        }
    }
	
}

/*
 * Auf merfach falsche Eingabe des Passworts überprüfen
 *
 */
function checkbrute($user_id, $mysqli) {
    $now = time();
    $valid_attempts = $now - (1 * 60 * 60);
	
    if ($stmt = $mysqli->prepare("SELECT time FROM ".T_LOGIN_ATTEMPTS." <code><pre> WHERE User_ID = ? AND time > '$valid_attempts'")) {
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $stmt->store_result();
		
		// Wenn 5 x falsch
        if ($stmt->num_rows > 5) {
            return true;
        } else {
            return false;
        }
    }
}


/*
 * Erster Login?
 */
function firstlogin_check($mysqli) {
	if ($stmt = $mysqli->prepare("SELECT member_id FROM ".T_MEMBER_PROFILE." WHERE member_id = ?")) {
        $stmt->bind_param('i', $_SESSION['user_id']);
        $stmt->execute();
        $stmt->store_result();
        $stmt->fetch(); 
        if ($stmt->num_rows == 1)
			return false;
		else
			return true;
	}
}
 
/*
 *	LOGIN Überprüfung
 *
 */
function login_check($mysqli) {
    if (isset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['login_string'])) {
        $user_id		= $_SESSION['user_id'];
        $username		= $_SESSION['username'];
		$login_string	= $_SESSION['login_string'];
		
        $user_browser	= $_SERVER['HTTP_USER_AGENT'];
		
		if ($stmt = $mysqli->prepare("SELECT Password FROM ".T_MEMBER." WHERE ID = ? LIMIT 1")) {
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows == 1) {
                $stmt->bind_result($password);
                $stmt->fetch();
                $login_check = hash('sha512', $password . $user_browser);
                if ($login_check == $login_string) {
                    //Eingeloggt
                    return true;
                } else {
                    //NICHT Eingeloggt
                    return false;
                }
            } else {
                // Nicht eingeloggt
                return false;
            }
        } else {
            // Nicht eingeloggt
            return false;
        }
    } else {
        // Nicht eingeloggt
        return false;
    }
}
function notLoggedIn() {
	$_SESSION['openedURL'] = str_replace("/schuppen/","",$_SERVER['REQUEST_URI']);
	header('Location: login.php');
}

/*
 * Administrator Überprüfung
 */
function admin_check($mysqli) {
	if ($stmt = $mysqli->prepare("SELECT IsAdmin FROM member WHERE ID = ? AND IsAdmin = 1 LIMIT 1")) {
		$stmt->bind_param('i', $_SESSION['user_id']);
		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			return true;
		} else {
			return false;
		}
	}	
}

/*
 * Ist ein Profilbild vorhanden?
 */
function profile_pic_check($UserID,$mysqli) {
	if (!$stmt = $mysqli->prepare("SELECT PicAvail FROM member WHERE ID = ? LIMIT 1")) {
		echo $mysqli->error;
	}
	$stmt->bind_param('i', $UserID);
	if (!$stmt->execute()) {
		echo $stmt->error;
	}
	$stmt->store_result();
	$stmt->bind_result($PicAvail);
	if($stmt->fetch()) {
		if($PicAvail == true)return true;
		else return false;
	}
}

/*
 * ID Des Profilbilds
 */
function getProfilePicID($mysqli) {
	if ($stmt = $mysqli->prepare("SELECT PicID FROM member_profile WHERE member_id = ? LIMIT 1")) {
		$stmt->bind_param('i', $_SESSION['user_id']);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($PicID);
		if($stmt->fetch())
			$ret = $PicID;
		else
			$ret = 'Fehler';
	} 
	return $ret;
}



function getNavigationNodes() {
	global $db;
	$nodes = array();
	
	$db->orderBy("parent", "ASC");
	$db->orderBy("prio", "ASC");
	$navis = $db->get(T_NAVIGATION, null, "ID, IFNULL(parent, 0) AS parent, prio, Name");
	
	foreach($navis as $navi) {
		$nodes[$navi['parent']][$navi['ID']] = $navi['Name'];
	}
	return $nodes;
}

function create_Navigation() {
	global $db;
	$ret = '<ul>';
	$nodes = getNavigationNodes();
	
	foreach ($nodes[0] as $id => $name) {

		$db->where("ID", $id);
		$row = $db->getOne(T_NAVIGATION, "url");

		$ret .= '<li><a href="'.$row['url'].'">'. htmlSpecialChars($name).'</a>';
		if (isset($nodes[$id])) {
			$ret .= "<ul>\n";
			foreach ($nodes[$id] as $cId => $cName) {
				$db->where("ID", $cId);
				$row = $db->getOne(T_NAVIGATION, "url");
				$ret .= '<li><a href="'.$row['url'].'">'. htmlSpecialChars($cName). "</a></li>\n";
			}
			$ret .= "</ul>\n";
		}
		$ret .= "</li>\n";
	}
	$ret .= '</ul>';
	
	return $ret;
}

function get_OnlineUsers() {
	global $db;
	$db->where("Online_Since <= ".(strtotime(date("d.m.Y H:i:s")) - 60*60)."");
	$users = $db->get(T_MEMBER, null, "ID, Online_Since, Username");

	$neu = array();
	foreach($users as $user) {
		$neu[] = array('ID' => $user['ID'], 'Online' => $user['Online_Since'], 'Username' => $user['Username']);
	}
	return $neu;
}

function create_OnlineUsers() {
	$ret	= null;
	$users	= get_OnlineUsers();
	
	foreach($users as $user)
	{
		if($user['Online'] != NULL) {
			$Status = '<div title="'.$user['Online'].'" class="status on"></div>';
		} else {
			$Status = '<div title="'.$user['Online'].'" class="status off"></div>';
		}
			
		$ret .= '<div class="user">
					'.$Status.'
					<div class="name"><a href="profile.php?user='.$user['ID'].'">'.$user['Username'].'</a></div>
					<div class="bit"><a href="messages.php?action=new&ID='.$user['ID'].'"><img width="30px" height="30px" src="'.IMG_ICO_MSG.'"></a></div>
					<div class="bit"><a href="pm.php?action=new&ID='.$user['ID'].'" onclick="return popup(this,800,400)" title="..."><img width="30px" height="30px" src="'.IMG_ICO_PM.'"></a></div>
				</div>';
	}
	$ret .= '<div class="clear"></div>';
	return $ret;
}

/*
 * Username ermitteln
 */
function get_UserName($ID) {
	global $db;
	$db->where("ID", $ID);
	$user	= $db->getOne(T_MEMBER);
	return $user['Username'];
}


function get_UserID($Name) {
	global $db;
	$db->where("Username LIKE ".$Name."");
	$user	= $db->getOne(T_MEMBER);
	return $user['ID'];
}

function get_SignID($birthday) {
	$timestamp = strtotime($birthday);
	$day	= idate("d",$timestamp);
	$month	= idate("m",$timestamp);
	
	switch($month) {
		case 1:
			if($day >= 21)	$sign = 'Wassermann';
			else			$sign = 'Steinbock';
		break;
		case 2:
			if($day >= 20)	$sign = 'Fische';
			else			$sign = 'Wassermann';
		break;
		case 3:
			if($day >= 21)	$sign = 'Widder';
			else			$sign = 'Fische';
		break;
		case 4:
			if($day >= 21)	$sign = 'Stier';
			else			$sign = 'Widder';
		break;
		case 5:
			if($day >= 21)	$sign = 'Zwilling';
			else			$sign = 'Steinbock';
		break;
		case 6:
			if($day >= 22)	$sign = 'Krebs';
			else			$sign = 'Zwilling';
		break;
		case 7:
			if($day >= 23)	$sign = 'Löwe';
			else			$sign = 'Krebs';
		break;
		case 8:
			if($day >= 24)	$sign = 'Jungfrau';
			else			$sign = 'Löwe';
		break;
		case 9:
			if($day >= 24)	$sign = 'Waage';
			else			$sign = 'Jungfrau';
		break;
		case 10:
			if($day >= 24)	$sign = 'Skorpion';
			else			$sign = 'Waage';
		break;
		case 11:
			if($day >= 23)	$sign = 'Schütze';
			else			$sign = 'Skorpion';
		break;
		case 12:
			if($day >= 22)	$sign = 'Steinbock';
			else			$sign = 'Schütze';
		break;
	}

	return $sign;
}

/*
 * Aktuellen Wochentag durch Timestamp ermitteln
 */
function getWochenTag($timestamp) {
	$Nr = date("w",$timestamp);
	switch($Nr) {
		case 0:
			return 'Sonntag';
		break;
		case 1:
			return 'Montag';
		break;
		case 2:
			return 'Dienstag';
		break;
		case 3:
			return 'Mittwoch';
		break;
		case 4:
			return 'Donnerstag';
		break;
		case 5:
			return 'Freitag';
		break;
		case 6:
			return 'Samstag';
		break;
	}
}


/*
 *	Profilbilder / Image-Upload
 *
 */
function img_resize($target, $newcopy, $w, $h, $ext) {
    list($w_orig, $h_orig) = getimagesize($target);
    $scale_ratio = $w_orig / $h_orig;
    if (($w / $h) > $scale_ratio) {
           $w = $h * $scale_ratio;
    } else {
           $h = $w / $scale_ratio;
    }
    $img = "";
    $ext = strtolower($ext);
    if ($ext == "gif"){ 
      $img = imagecreatefromgif($target);
    } else if($ext =="png"){ 
      $img = imagecreatefrompng($target);
    } else { 
      $img = imagecreatefromjpeg($target);
    }
    $tci = imagecreatetruecolor($w, $h);
    // imagecopyresampled(dst_img, src_img, dst_x, dst_y, src_x, src_y, dst_w, dst_h, src_w, src_h)
    imagecopyresampled($tci, $img, 0, 0, 0, 0, $w, $h, $w_orig, $h_orig);
    imagejpeg($tci, $newcopy, 84);
}



/*
 * Erstellen eines Menüs
 *
 * PARAMETER
 * site			(int)		:	Momentane Unterseite
 * arrayMenue	(array)		:	Array mit den Punkten
 *
 * return	(string)	: HTML Menue
 *
 */
function createMenue($arrayMenue, $selected) {

	$count		= sizeof($arrayMenue);
	$percent	= 99.99 / $count;
	$ret		= null;
	$ret .= '<div id="profilemenue"><ul>';
	
	foreach($arrayMenue as $key => $value) {
		$ret .= '<li style="width:'.$percent.'%"';
		if($selected == $key)$ret .= 'class="selected"';
		$ret .= '><a href="'.$value['url'].'">'.$value['text'].'</a></li>';
	}
	$ret .= '</ul>
		<div class="clear"></div>
	</div>';
	return $ret;
}
?>