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

// Start session for everybody
session_start();
if(isset($_POST['action']) && $_POST['action'] === 'logout')
{
	session_destroy();
	unset($_SESSION);
	unset($_POST);
	session_start();
	$GLOBALS['infos'][] = 'Session closed';
}

if(!isset($_COOKIE['token']) || !isset($_SESSION['token']))
{
	$GLOBALS['token_error'] = true;
	$GLOBALS['warnings'][] = 'No session token';
	// TODO: process no token situation
}
else if($_COOKIE['token'] !== $_SESSION['token'])
{
	$GLOBALS['token_error'] = true;
	$GLOBALS['warnings'][] = 'Wrong session token';
	// TODO: process wrong token situation
}
else
{
	$GLOBALS['token_error'] = false;
}
