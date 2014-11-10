function formhash(form, password) {
	// Neues hidden Input-Feld. Später Hashed Passwort
	var p = document.createElement("input");
	form.appendChild(p);
	p.name = "p";
	p.type = "hidden";
	p.value = hex_sha512(password.value);
	password.value = "";
	form.submit();
}

function regformhash(form, uid, email, password, conf) {
	if (uid.value == '' || email.value == ''  || password.value == '' || conf.value == '') {
		alert('Es wurden nicht alle Angaben gemacht!');
		return false;
	}

	// Benutzername überprüfen
	re = /^\w+$/;
	if(!re.test(form.username.value)) {
		alert("Der Benutzername darf nur aus Buchstaben, Zahlen und Unterstrichen bestehen");
		form.username.focus();
		return false;
	}

	// Ist das Passwort min. 6 Zeichen lang?
	if (password.value.length < 6) {
		alert('Das Passwort muss min. 6 Zeichen lang sein');
		form.password.focus();
		return false;
	}
	
	var re = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/;
	if (!re.test(password.value)) {
		alert('Passwörter müssen mindestens eine Zahl, einen Kleinbuchstaben und einen Großbuchstaben enthalten');
		return false;
	}

	// Stimmen Passwort und Bestätigung überein?
	if (password.value != conf.value) {
		alert('Passwort und Bestätigung stimmen nicht überein');
		form.password.focus();
		return false;
	}

	// Neues hidden Input-Feld. Hashed Passwort
	var p = document.createElement("input");
	// Add the new element to our form.
	form.appendChild(p);
	p.name = "p";
	p.type = "hidden";
	p.value = hex_sha512(password.value);
	password.value = "";
	conf.value = "";
	// SUBMIT
	form.submit();
	return true;
}

function resetformhash(form, uid, password, conf) {
	if (uid.value == '' || password.value == '' || conf.value == '') {
		alert('Es wurden nicht alle Angaben gemacht!');
		return false;
	}

	// Ist das Passwort min. 6 Zeichen lang?
	if (password.value.length < 6) {
		alert('Das Passwort muss min. 6 Zeichen lang sein');
		form.password.focus();
		return false;
	}
	
	var re = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/;
	if (!re.test(password.value)) {
		alert('Passwörter müssen mindestens eine Zahl, einen Kleinbuchstaben und einen Großbuchstaben enthalten');
		return false;
	}

	// Stimmen Passwort und Bestätigung überein?
	if (password.value != conf.value) {
		alert('Passwort und Bestätigung stimmen nicht überein');
		form.password.focus();
		return false;
	}

	// Neues hidden Input-Feld. Hashed Passwort
	var p = document.createElement("input");
	// Add the new element to our form.
	form.appendChild(p);
	p.name = "p";
	p.type = "hidden";
	p.value = hex_sha512(password.value);
	password.value = "";
	conf.value = "";
	// SUBMIT
	form.submit();
	return true;
}