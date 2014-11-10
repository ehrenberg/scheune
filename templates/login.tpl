[start]
<noscript><h1>Du hast JavaScript deaktiviert. Da kommst du hier nicht weit.(<a href="http://www.enable-javascript.com/de/">Wie aktiviere ich Javascript</a>)</h1></noscript>
{ERRORMSG}
<form action="login.php" method="post" name="login_form">
	<table class="standard">
		<tr>
			<td>Benutzername:</td>
			<td><input type="text" name="email" value="{TXTMAIL}"/></td>
		</tr>
		<tr>
			<td>Passwort:</td>
			<td><input type="password" name="password" id="password" placeholder="Passwort"/></td>
		</tr>
		<tr>
			<td></td>
			<td><input class="btn" type="submit" value="Login" onclick="formhash(this.form,this.form.password);" /></td>
		</tr>
		<tr>
			<td><input type="checkbox" name="remember_login" id="remember_login">Angemeldet bleiben?</td>
			<td><input type="checkbox" name="remember_email" id="remember_email" {TXTMAIL_CHECKED}>E-Mail-Adresse merken</td>
		</tr>
	</table>
</form>
<div class="login-help">
	<p><a href="login.php?reset">Passwort vergessen</a>.</p>
</div>


[reset]
<form action="login.php" method="post">
	<p>Durch die Eingabe deiner E-Mail-Adresse schicken wir dir ein neues Passwort</p>
	<p><input type="text" name="email" placeholder="E-Mail Adresse" value=""/></p>
	<p><input type="submit" name="reset" value="Neues Passwort anfordern">
</form>


[password_reset]
<form action="password_reset.php" method="post">
	<p>Nun gebe dein neues Passwort ein:</p>
	<p><input type="password" name="pwnew" placeholder="Passwort" value=""/></p>
	<p><input type="password" name="pwnew_confirm" placeholder="Passwort wiederholen" value=""/></p>
	<input type="hidden" name="userid" value="{USERID}">
	<p><input type="button" name="new_password" value="OK" onclick="return resetformhash(this.form,this.form.userid,this.form.pwnew,this.form.pwnew_confirm);">
</form>
