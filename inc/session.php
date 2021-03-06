<?php
# *** LICENSE ***
# This file is part of GeoPost-server.
#
# 2013-2014 Grégoire Surrel
#
# GeoPost-server is free software.
# You can redistribute it under the terms of the MIT / X11 Licence.
#
# *** LICENSE ***

// Start session for everybody
session_start();

// Disable any action if couldn't open DB
if($GLOBALS['db_handle'] === false)
{
	$GLOBALS['errors'][] = 'No DB handle';
	unset($_POST['action']);
}

// Check if signup, login or logout
if(isset($_POST['action']))
{
	// TODO: remove this dirty line:
	if(isset($_POST['username_email']) && $_POST['action'] !== 'login' && $_POST['action'] !== 'register') unset($_POST['username_email']);
	if($_POST['action'] === 'login' || (isset($_POST['username_email']) && isset($_POST['password'])))
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
			db_user('add', array(
								 'email' => $_POST['email'],
								 'username' => $_POST['username'],
								 'password' => $_POST['password']));
			login_user($_POST['username'], $_POST['password']);
		}
		else
		{
			$GLOBALS['warnings'][] = 'Missing login, email and/or password';
		}
	}
	else if($_POST['action'] === 'logout')
	{
		session_new();
		$GLOBALS['infos'][] = 'Session closed';
	}
}
else
{
	$GLOBALS['errors'][] = 'Action not specified';
	if(isset($_SESSION['user']))
	{
		$GLOBALS['infos'][] = 'Redirected action to "user" because of no action specified and user logged in';
		$_POST['action'] = 'user';
		$GLOBALS['warnings'][] = 'Action fallback on user details';
	}
}

// Handle tokens, cookies and sessions
if(isset($_SESSION['user']))
{
	if(!isset($_COOKIE['token']) || !isset($_SESSION['token']))
	{
		$GLOBALS['token_error'] = true;
		$GLOBALS['errors'][] = 'No session token';
		session_new();
	}
	else if(!check_token())
	{
		$GLOBALS['token_error'] = true;
		$GLOBALS['errors'][] = 'Wrong session token';
		session_new();
	}
	else
	{
		$GLOBALS['token_error'] = false;
		//$GLOBALS['infos'][] = 'Token OK';
	}
}
