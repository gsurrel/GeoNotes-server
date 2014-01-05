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

// Get invasive warning reporting to disable this bypass feature in prod
$debug_token_bypass = true;
if($debug_token_bypass === true) $GLOBALS['warnings'][] = 'Token bypass enabled';

if(isset($_POST['action']) && (!$GLOBALS['token_error'] || ($debug_token_bypass && isset($_GET['debug']))))
{
	// Get invasive error reporting to disable this bypass feature in prod
	if(isset($_GET['debug'])) $GLOBALS['errors'][] = 'Token bypassed by DEBUG flag';

if($_POST['action'] === 'note_add')
{
	$response = db_note('add', array(
								     'lat' => $_POST['lat'],
								     'lon' => $_POST['lon'],
								     'title' => $_POST['title'],
								     'text' => $_POST['text'],
								     'user' => $_SESSION['user']->ID,
								     'karma' => '0',
								     'creation' => date('U'),
								     'lifetime' => $_POST['lifetime'],
								     'lang' => $_POST['lang'],
								     'cat' => $_POST['cat']));
	$GLOBALS['infos'][] = 'Note added (probably)';
	// If HTML view, then forward to my_notes listing
	//if(!isset($_GET['api'])) $_POST['action'] = 'list_mine';
}
else if($_POST['action'] === 'list')
{
	$response = get_notes();
}
else if($_POST['action'] === 'list_mine')
{
	$response = get_user_notes();
}
else if($_POST['action'] === 'user')
{
	$response = $_SESSION['user'];
}
else
{
	$GLOBALS['errors'][] = 'Action not found';
}

}
