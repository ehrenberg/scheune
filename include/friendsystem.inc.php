<?php
error_reporting(-1);
include 'db_connect.php';
include_once('../class/Notifications.class.php');
sec_session_start();

$Noti		= new Notifications($db, $_SESSION['user_id']);

if (login_check($mysqli) == false) {
	header('Location: login.php');
}

if (isset($_POST['type']) && isset($_POST['user'])) {
	$user = $_POST['user'];
	
	$cols = array("ID", "Username");
	$db->where("ID", $user);
	$db->where("Active", 1);
	$user = $db->getOne("".T_MEMBER."", null, $cols);
	if($db->count == 0) {
		echo "".$user['Username']." existiert nicht.";
		exit();
	}
	
	if($_POST['type'] == "friend") {
	
		//Wurde ich blockiert?
		$db->where("MemberID", $user['ID']);
		$db->where("BlockedID", $_SESSION['user_id']);
		$db->getOne(T_MEMBER_BLOCKEDUSER);
		if($db->count > 0) {
			echo "".$user['Username']." hat dich blockiert";
	        exit();
		}
		
		
		//Habe ich blockiert?
		$cols = array("ID");
		$db->where("MemberID", $_SESSION['user_id']);
		$db->where("BlockedID", $user['ID']);
		$db->get("".T_MEMBER_BLOCKEDUSER."", null, $cols);
		if($db->count > 0) {
			echo "Du musst ".$user['Username']." zuerst entblockieren";
	        exit();
		}
		
		//Schon mein Freund?
		$cols = array("ID");
		$db->where("MemberID", $_SESSION['user_id']);
		$db->where("FriendID", $user['ID']);
		$db->where("Active", 1);
		$db->get("".T_MEMBER_FRIEND."", null, $cols);
		if($db->count > 0) {
			echo "Du bist mit ".$user['Username']." bereits befreundet";
	        exit();
		}
		
		//Freunschaftanfrage bereits versendet?
		$cols = array("ID");
		$db->where("MemberID", $_SESSION['user_id']);
		$db->where("FriendID", $user['ID']);
		$db->where("Active", 0);
		$db->get("".T_MEMBER_FRIEND."", null, $cols);
		if($db->count > 0) {
			echo "Du hast ".$user['Username']." bereits eine Freundschaftanfrage gesendet";
	        exit();
		}
		
		//Freunschaftanfrage bereits gesendet?
		$cols = array("ID");
		$db->where("MemberID", $user['ID']);
		$db->where("FriendID", $_SESSION['user_id']);
		$db->where("Active", 0);
		$db->get("".T_MEMBER_FRIEND."", null, $cols);
		if($db->count > 0) {
			echo "".$user['Username']." hat dir eine Freundschaftanfrage gesendet";
	        exit();
		}
		
		$data = Array ("MemberID"	=> $_SESSION['user_id'],
						"FriendID"	=> $user['ID'],
						"Since"		=> date('Y-m-d H:i:s',time()),
						"Active"	=> 0
		);
		$id = $db->insert("".T_MEMBER_FRIEND."", $data);
		if($id) {
			$Noti->insertUserNotification($user['ID'], 3, "".$_SESSION['username']." möchte dein Freund sein.", "friends.php?requests");
			echo "friend_request_sent";
		} else {
			echo "Fehler";
		}
		
		exit();
	}
	//als Freund entfernen
	else if($_POST['type'] == "unfriend"){
		//MyFriend
		$db->where("MemberID", $_SESSION['user_id']);
		$db->where("FriendID", $user);
		$db->where("Active", 1);
		$db->getOne("".T_MEMBER_FRIEND."", null, "ID");
		if($db->count > 0) {
			$db->where("MemberID", $_SESSION['user_id']);
			$db->where("FriendID", $user);
			$db->where("Active", 1);
			$db->delete("".T_MEMBER_FRIEND."");
			echo "unfriend_ok";
		} else {
			echo "Es besteht keine Freunschaft zwischen dir und $user, somit kann die Freundschaft auch nicht beendet werden :)";
		}
		
	}
	//Freundschaftanfrage ablehnen
	else if($_POST['type'] == "refuse"){
		$sql = "SELECT COUNT(ID) FROM ".T_MEMBER_FRIEND." WHERE MemberID='".$_SESSION['user_id']."' AND FriendID='$user' AND Active='0' LIMIT 1";
		$query = $mysqli->query($sql);
		$row_count = $query->fetch_row();
	    if ($row_count[0] > 0) {
	        $sql = "DELETE FROM ".T_MEMBER_FRIEND." WHERE MemberID='".$_SESSION['user_id']."' AND FriendID='$user' AND Active='0' LIMIT 1";
			$query = $mysqli->query($sql);
			$mysqli->close();
	        echo "refuse_ok";
	        exit();
	    } else {
			$mysqli->close();
	        echo "$user, hat dir keine Freunschaftsanfrage gesendet :)";
	        exit();
		}
	}
	//Freunschaft bestätigen
	else if($_POST['type'] == "confirm"){
		
		$db->where("F.MemberID", $user['ID']);
		$db->where("F.FriendID", $_SESSION['user_id']);
		$db->where("F.Active", 0);
		$mf = $db->getOne("".T_MEMBER_FRIEND." F", null, array("F.ID","NM.NotificationID"));
		if($db->count > 0) {
			$data["Active"] = 1;
			$db->where("ID", $mf['ID']);
			$db->update("".T_MEMBER_FRIEND."", $data);
			
			$db->where("MemberID", $_SESSION['user_id']);
			$db->where("UserID", $user['ID']);
			$db->where("FriendRequest", 1);
			$noti = $db->getOne("".T_NOTIFICATION_MEMBER."",null, "NotificationID");
			
			$Noti->readedUserNotification($noti['NotificationID'], $user['ID'], 3);	//Als gelesen markieren
			$Noti->deleteUserNotification($noti['NotificationID']);						//Benachrichtigung löschen
			
			echo "confirm_ok";
		} else {
			echo $user['Username']." hat dir keine Freunschaftanfrage gesendet";
		}
	}
}
?>