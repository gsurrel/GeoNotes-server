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

// Renew session
function session_new(){
	session_destroy();
	unset($_SESSION);
	unset($_POST);
	session_start();
	session_regenerate_id(true);
}

// Code modified from Shaarli. Generate an unique sess_id, usable only once.
function new_token()
{
	$rnd = sha1(uniqid('',true).mt_rand());  // We generate a random string.
	$_SESSION['token'] = $rnd;  // Store it on the server side.
	setcookie("token", $rnd);
	return $rnd;
}

// Tells if token is ok. Using this function will destroy the token.
// true=token is ok.
function check_token() {
	if (isset($_SESSION['token']) && isset($_COOKIE['token']))
	{
		if($_COOKIE['token'] === $_SESSION['token'])
		{
			unset($_SESSION['token']); // Token is used: destroy it.
			// Token OK, regenerate one
			new_token();
			return true;
		}
	}
	return false; // Wrong token, or already used.
}

