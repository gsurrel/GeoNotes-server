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

//require_once('inc/helper.php');
// Reporte toutes les erreurs PHP
error_reporting(-1);
foreach($_GET as $key => $value)
{
	$_POST[$key] = $value;
}

// Placeholder for answer
$response = NULL;
// Processing
require_once 'inc/inc.php';
// Formatting of answer
$response = array('infos' => $GLOBALS['infos'],
                  'warnings' => $GLOBALS['warnings'],
                  'errors' => $GLOBALS['errors'],
                  'data' => $response);

if(isset($_GET['api'])):
	header('Content-Type: application/json');
	echo json_encode($response);
else:

	include('tpl/head.php');
	echo d(json_encode($response));
	include('tpl/infobox.php');

	if(!isset($_SESSION['user'])): // Not logged in
		include('tpl/home.php');
	else: // Logged in
		srand(); // Init rand generator
		include('tpl/actions.php');

		if($_POST['action'] === 'user'):
			include('tpl/user.php');
		elseif($_POST['action'] === 'list' || $_POST['action'] === 'list_mine'):
			include('tpl/list.php');
		else:
			!d($response['data']);
		endif;
	endif;

	if(isset($_GET['debug'])): ?>
		<h1>Admin</h1>
		<?php d($_SESSION); ?>
		<?php d(get_users()); ?>
		<?php d(get_notes());
	endif;

endif;
