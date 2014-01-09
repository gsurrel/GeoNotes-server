<h1>Login</h1>
<form method="POST">
	<input type="hidden" name="action" value="login"/>
	<input type="text" name="username_email" value="username or email"/>
	<input type="password" name="password" value="password"/>
	<input type="submit" value="Login" />
</form>

<h1>Register</h1>
<form method="POST">
	<input type="hidden" name="action" value="register"/>
	<label for="username">Username</label>
	<input id="username" type="text" name="username" value="Username"/>
	<br/>
	<label for="email">email</label>
	<input id="email" type="email" name="email" value="email@provider.tld"/>
	<br/>
	<label for="password">Password</label>
	<input id="password" type="password" name="password" value="password" autocomplete="off" />
	<br/>
	<input type="submit" value="Register" />
</form>
