[register]
{ERRORMSG}
<ul class="bedingungen">
	<li>Benutzernamen dürfen nur Ziffern, Groß-und Kleinbuchstaben und Unterstriche enthalten</li>
	<li>Das Passwort muss mindestens 6 Zeichen lang sein</li>
	<li>Passwort muss enthalten:
		<ol>
			<li>Mindestens einen Großbuchstaben (A..Z)</li>
			<li>Mindestens einen Kleinbuchstaben (a..z)</li>
			<li>Mindestens eine Zahl (0..9)</li>
		</ol>
	</li>
</ul>
<form action="register.php" method="post" name="login_form">
	<table class="standard">
		<tr>
			<td>Benutzername</td>
			<td><input type="text" name="username" id="username"/></td>
		</tr>
		<tr>
			<td>E-Mail</td>
			<td><input type="text" name="email" id="email"/></td>
		</tr>
		<tr>
			<td>Passwort</td>
			<td><input type="password" name="password" id="password"/></td>
		</tr>
		<tr>
			<td>Passwort bestätigen</td>
			<td><input type="password" name="confirmpwd" id="confirmpwd"/></td>
		</tr>
		<tr>
			<td colspan="2"><input class="btn" type="button" value="Registrieren" onclick="return regformhash(this.form,this.form.username,this.form.email,this.form.password,this.form.confirmpwd);" /></td>
		</tr>
	</table>
</form>

[activated]
<div class="alert-box success">Dein Account ist nun aktiviert. Willkommen :)</div>
[not_activated]
<div class="alert-box error">Dein Account konnte nicht aktiviert werden :(</div>