<?php
# *** LICENSE ***
# This file is part of GeoNotes-server.
#
# 2013-2014 Grégoire Surrel
#
# GeoNotes-server is free software.
# You can redistribute it under the terms of the MIT / X11 Licence.
#
# *** LICENSE ***

require 'inc/inc.php';

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>GeoNotes server</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

<?php if(!isset($_SESSION['user'])): ?>

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

<?php else: ?>

<h1>Welcome <?php echo $_SESSION['user']->username; ?></h1>

<form method="POST"><input type="hidden" name="action" value="logout"/><input type="submit" value="Logout"/></form>

<h2>List of notes</h2>
<?php
	$notes = get_user_notes();
	d($notes);
	foreach($notes as $note)
	{ ?>
<table style="display: inline-block" border>
<thead><th>Key</th><th>Value</th></thead>
<tbody>
<?php	foreach($note as $key => $value)
		{
			echo "<tr><td>$key</td><td>$value</td></tr>\n";
		} ?>
</tbody>
</table>
<?php }
?>

<p><i>TODO: add note</i></p>

<h2>Settings:</h2>
<?php $settings = json_decode($_SESSION['user']->settings, true); ?>
<table border>
	<thead><th>Key</th><th>Value</th></thead>
	<tbody>
		<?php foreach($settings as $key => $value) echo "<tr><td>$key</td><td>$value</td></tr>" ?>
	</tbody>
</table>

<p><i>TODO: edit user settings</i></p>

<?php endif; ?>

<h1>Infos, warnings and errors</h1>
<?php !d($GLOBALS['infos']); ?>
<?php !d($GLOBALS['warnings']); ?>
<?php !d($GLOBALS['errors']); ?>

<h1>Admin</h1>
<?php d($_SESSION); ?>
<?php d(get_users()); ?>
<?php d(get_notes()); ?>
