function _(x){
	return document.getElementById(x);
}
function toggleElement(x){
	var x = _(x);
	if(x.style.display == 'block'){
		x.style.display = 'none';
	}else{
		x.style.display = 'block';
	}
}

function ajaxObj(meth, url) {
	var x = new XMLHttpRequest();
	x.open(meth, url, true);
	x.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	return x;
}

function ajaxReturn(x) {
	if(x.readyState == 4 && x.status == 200) {
		return true;
	}
}

function friendToggle(type,user,elem){
	_(elem).innerHTML = 'bitte warten ...';
	var ajax = ajaxObj("POST", "include/friendsystem.inc.php");
	console.log(ajax);
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			if(ajax.responseText == "friend_request_sent"){
				_(elem).innerHTML = 'Freunschaftsanfrage wurde gesendet.';
			} else if(ajax.responseText == "unfriend_ok"){
				_(elem).innerHTML = '<button onclick="friendToggle(\'friend\',\'<?php echo $UserID; ?>\',\'friendBtn\')">Als Freund hinzufügen</button>';
			} else if(ajax.responseText == "refuse_ok"){
				_(elem).innerHTML = 'Freunschaftsanfrage von <?php echo $username;?> <b>nicht</b> bestätigt.';
			} else if(ajax.responseText == "confirm_ok"){
				_(elem).innerHTML = 'Die Freundschaft wurde bestätigt';
			} else {
				_(elem).innerHTML = ajax.responseText;
			}
		}
	}
	ajax.send("type="+type+"&user="+user);
}
function blockToggle(type,blockee,elem){
	var elem = document.getElementById(elem);
	elem.innerHTML = 'bitte warten ...';
	var ajax = ajaxObj("POST", "include/blocksystem.inc.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			if(ajax.responseText == "blocked_ok"){
				elem.innerHTML = '<button onclick="blockToggle(\'unblock\',\'<?php echo $UserID; ?>\',\'blockBtn\')">Unblock User</button>';
			} else if(ajax.responseText == "unblocked_ok"){
				elem.innerHTML = '<button onclick="blockToggle(\'block\',\'<?php echo $UserID; ?>\',\'blockBtn\')">Block User</button>';
			} else {
				elem.innerHTML = ajax.responseText;
			}
		}
	}
	ajax.send("type="+type+"&blockee="+blockee);
}