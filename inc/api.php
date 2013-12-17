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

// Open DB if session has user, we will probably always use it
open_base();

if((true || !$GLOBALS['token_error']) && isset($_POST['action']))
{
d('Bypassed token check');

if($_POST['action'] === 'login')
{
	if(isset($_POST['username_email']) && isset($_POST['password']))
	{
		login_user($_POST['username_email'], $_POST['password']);
	}
	else
	{
		$GLOBALS['warnings'][] = 'Missing login/email and/or password';
	}
}
else if($_POST['action'] === 'register')
{
	if(isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']))
	{
		$req = db_user('add', array(
		                          'email' => $_POST['email'],
		                          'username' => $_POST['username'],
		                          'password' => $_POST['password']));
		login_user($_POST['username'], $_POST['password']);
		$req = db_note('add', array(
		                          'lat' => '0.000000',
		                          'lon' => '0.000000',
		                          'title' => 'Title of the note',
		                          'text' => 'Note content is here.',
		                          'user' => $_SESSION['user']->ID,
		                          'karma' => '0',
		                          'creation' => date('U'),
		                          'lifetime' => '0',
		                          'lang' => 'en', // TODO: Fix that
		                          'cat' => '',
		                          ));
	}
	else
	{
		$GLOBALS['warnings'][] = 'Missing login, email and/or password';
	}
}
else
{
	$GLOBALS['errors'][] = 'Action not found';
}

}
