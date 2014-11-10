<?php
include 'include/db_connect.php';
include 'include/functions.inc.php';
sec_session_start();

$sql = 'UPDATE '.T_MEMBER.' SET Online_Since = NULL, Online_Last = '.time().' WHERE ID = ?';
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$stmt->close();

//UNSET Session
$_SESSION = array();
$params = session_get_cookie_params();
setcookie(session_name(),'', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]); //Aktuellen Cookie löschen
session_destroy();
header('Location: index.php');
?>