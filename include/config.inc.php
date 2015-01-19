<?php
/**
 * These are the database login details
 */  
define("WEBSITE_NAME",	"Radio - Rockscheune");
define("VERSION",		"0.0.1");
define("VERSION_TEXT",	"Rockscheune");
define("HOST",			"localhost");     // The host you want to connect to.
define("USER",			"root");
define("DATABASE",		"scheune");
define("PASSWORD",		"");

define("EMAIL",			"mail@radio-rockscheune.de");
 
define("CAN_REGISTER",	"ANY");
define("DEFAULT_ROLE",	"member");
 
define("SECURE",		FALSE);

//Tabellen
define("T_BLOG","blog");
define("T_BLOG_COMMENTS","blog_comments");
define("T_CHAT_MEMBER_ROOM","chat_member_room");
define("T_CHAT_MESSAGE","chat_message");
define("T_CHAT_ROOM","chat_room");
define("T_LOGIN_ATTEMPTS","login_attempts");
define("T_MEMBER","member");
define("T_MEMBER_FRIEND","member_friend");
define("T_MEMBER_BLOCKEDUSER","member_blockeduser");
define("T_MEMBER_PROFILE","member_profile");
define("T_MEMBER_PROFILE_GENDER","member_profile_gender");
define("T_MEMBER_PROFILE_HAIR","member_profile_hair");
define("T_MEMBER_PROFILE_EYES","member_profile_eyes");
define("T_MEMBER_PROFILE_PHYSIQUE","member_profile_physique");
define("T_MEMBER_PROFILE_RELATIONSHIP","member_profile_relationship");
define("T_MESSAGE","message");
define("T_NAVIGATION","navigation");
define("T_DIALOG","dialog");
define("T_DIALOG_MEMBER","dialog_member");
define("T_DIALOG_PM","dialog_pm");
define("T_PLUGIN","plugin");
define("T_NOTIFICATION_MEMBER","notification_member");
define("T_NOTIFICATION","notification");
define("T_NOTIFICATION_TYPES","notification_types");
define("T_NEWS","news");

define("T_TERMINE",					"termine");
define("T_ABSTIMMUNG",				"abstimmung");
define("T_ABSTIMMUNG_TITEL",		"abstimmung_titel");
define("T_ABSTIMMUNG_IP",			"abstimmung_ip");
define("T_ABSTIMMUNG_VORSCHLAEGE",	"abstimmung_vorschlaege");
define("T_TEMPLATES",				"templates");
define("T_SETTINGS",				"settings");

define("T_SHOUTBOX", "shoutbox");

//Ordner
define("DIR_PLUGINS",	$_SERVER['DOCUMENT_ROOT'].'/scheune/plugins');
define("DIR_ROOT", $_SERVER['DOCUMENT_ROOT']."/scheune");
//define("DIR_ROOT","");
define("DIR_IMG_ICO", "/img/ico");

//PLUGINS
define("INPROCESS", true);

//Bilder
define("IMG_ICO_DELETE", "img/ico/delete.png");
define("IMG_ICO_MSG", "img/ico/msg.png");
define("IMG_ICO_NOTIFICATIONS", "img/ico/notifications.png");
define("IMG_ICO_PM", "img/ico/pm.png");
define("IMG_ICO_CONFIRM", "img/ico/confirm.png");
define("IMG_ICO_REFUSE", "img/ico/refuse.png");
define("IMG_LOGO", "img/logo.png");

//Strings
define("STR_NEIN", "Nein");
define("STR_JA", "Ja");
?>