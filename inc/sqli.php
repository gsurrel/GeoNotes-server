<?php
# *** LICENSE ***
# This file is part of GeoNotes-server.
#
# 2006      Frederic Nassar.
# 2010-2013 Timo Van Neerden <ti-mo@myopera.com>
# 2013-2014 Grégoire Surrel
#
# GeoNotes-server is free software.
# You can redistribute it under the terms of the MIT / X11 Licence.
#
# *** LICENSE ***

// TODO:
//  - MVC arch
//  - Reformat
$GLOBALS['salt'] = 'my_geonotes_server_salt';

/*
 * Open a base
*/
function open_base() {
	$handle = create_tables();
	$GLOBALS['db_handle'] = $handle;
	return $handle;
}


/*
 * Creates a new GeoNotes-server base.
 * if file does not exists, it is created, as well as the tables.
 * if file does exists, tables are checked and created if not exists
*/
function create_tables() {
	$requests['gn_notes'] = 'CREATE TABLE gn_notes
		(
			ID INTEGER PRIMARY KEY,
			lat REAL,
			lon REAL,
			title TEXT,
			text TEXT,
			user INTEGER,
			karma INTEGER,
			creation NUMERIC,
			lifetime INTEGER,
			lang TEXT,
			cat TEXT
		); CREATE INDEX toUser ON gn_notes ( user );';

	$requests['gn_users'] = 'CREATE TABLE gn_users
		(
			ID INTEGER PRIMARY KEY,
			email TEXT UNIQUE,
			username TEXT UNIQUE,
			password TEXT,
			settings TEXT
		);
		CREATE INDEX userEmail ON gn_users ( email );
		CREATE INDEX userUsername ON gn_users ( username );';

	/*
	* SQLite : opens file, check tables by listing them, create the one that miss.
	*/
	if ( !is_dir('config') ) {
		if (mkdir('config', 0777) === TRUE) {
			file_put_contents('config/index.html', '');
		}
	}

	$file = 'config/db.sqlite';

	try {
		$db_handle = new PDO('sqlite:'.$file);
		$db_handle->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db_handle->query('PRAGMA temp_store=MEMORY; PRAGMA synchronous=OFF; PRAGMA journal_mode=WAL;');
		// list tables
		$list_tbl = $db_handle->query('SELECT name FROM sqlite_master WHERE type=\'table\'');
		// make an normal array, need for "in_array()"
		$tables = array();
		foreach($list_tbl as $j) {
			$tables[] = $j['name'];
		}

		// check each wanted table (this is because the "IF NOT EXISTS" condition doesn’t exist in lower versions of SQLite.
		$wanted_tables = array('gn_notes', 'gn_users');
		foreach ($wanted_tables as $i => $name) {
			if (!in_array($name, $tables)) {
				$results = $db_handle->exec($requests[$name]);
			}
		}
	} catch (Exception $e) {
		$GLOBALS['errors'][] = 'CreateDB: '.$e->getMessage();
	}

	return $db_handle;
}


/*
 * Handles ADD, EDIT and DELETE actions
 * on the NOTES table
*/
function db_note($what, $note) {
	if ($what == 'add') {
		try {
			$req = $GLOBALS['db_handle']->prepare('INSERT INTO gn_notes
			(
				lat,
				lon,
				title,
				text,
				user,
				karma,
				creation,
				lifetime,
				lang,
				cat
			)
			VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');

			$req->execute(array(
				$note['lat'],
				$note['lon'],
				$note['title'],
				$note['text'],
				$note['user'],
				$note['karma'],
				$note['creation'],
				$note['lifetime'],
				$note['lang'],
				$note['cat'],
			));
			return TRUE;
		} catch (Exception $e) {
			$GLOBALS['errors'][] = 'Err. note>add : '.$e->getMessage();
		}

	} elseif ($what == 'edit') {
		try {
			$req = $GLOBALS['db_handle']->prepare('UPDATE gn_notes SET
				title=?,
				text=?,
				lifetime=?,
				lang=?,
				cat=?
				WHERE ID=?');
			$req->execute(array(
				$note['title'],
				$note['text'],
				$note['lifetime'],
				$note['lang'],
				$note['cat'],
				$note['ID'],
			));
			return TRUE;
		} catch (Exception $e) {
			$GLOBALS['errors'][] = 'Err. note>edit : '.$e->getMessage();
		}
	}

	elseif ($what == 'delete') {
		try {
			$req = $GLOBALS['db_handle']->prepare('DELETE FROM gn_notes WHERE ID=?');
			$req->execute(array($note['ID']));
			return TRUE;
		} catch (Exception $e) {
			$GLOBALS['errors'][] = 'Err. note>delete : '.$e->getMessage();
		}
	}
}

/*
 * Handles ADD, EDIT and DELETE actions
 * on the USERS table
*/
function db_user($what, $user) {
	if ($what == 'add') {
		try {
			$req = $GLOBALS['db_handle']->prepare('INSERT INTO gn_users
			(
				email,
				username,
				password,
				settings
			)
			VALUES (?, ?, ?, ?)');
			$req->execute(array(
				$user['email'],
				$user['username'],
				crypt($user['password'], $GLOBALS['salt']),
				json_encode(array('lang' => 'en')), // TODO, Fix that
			));
			return TRUE;
		} catch (Exception $e) {
			$GLOBALS['errors'][] = 'Err. user>add : '.$e->getMessage();
		}

	} elseif ($what == 'edit') {
		try {
			$req = $GLOBALS['db_handle']->prepare('UPDATE gn_users SET
				email=?,
				username=?,
				password=?
				WHERE ID=?');
			$req->execute(array(
				$user['email'],
				$user['username'],
				$user['password'],
				$user['ID'],
			));
			return TRUE;
		} catch (Exception $e) {
			$GLOBALS['errors'][] = 'Err. user>edit : '.$e->getMessage();
		}
	}

	elseif ($what == 'delete') {
		try {
			$req = $GLOBALS['db_handle']->prepare('DELETE FROM gn_users WHERE ID=?');
			$req->execute(array($user['ID']));
			return TRUE;
		} catch (Exception $e) {
			$GLOBALS['errors'][] = 'Err. user>delete : '.$e->getMessage();
		}
	}
}

/*
 * Lists all users details
*/
function get_users() {
	try {
		$req = $GLOBALS['db_handle']->query('SELECT * FROM gn_users');
		return $req->fetchAll(PDO::FETCH_CLASS);
	} catch (Exception $e) {
		$GLOBALS['errors'][] = 'Err. get_user : '.$e->getMessage();
	}
}

/*
 * Lists all notes details
*/
function get_notes() {
	try {
		$req = $GLOBALS['db_handle']->query('SELECT * FROM gn_notes');
		return $req->fetchAll(PDO::FETCH_CLASS);
	} catch (Exception $e) {
		$GLOBALS['errors'][] = 'Err. get_notes : '.$e->getMessage();
	}
}

/*
 * Lists notes for a specific user
*/
function get_user_notes() {
	try {
		$req = $GLOBALS['db_handle']->prepare('SELECT * FROM gn_notes WHERE user=?');
		$req->execute(array(
		                  $_SESSION['user']->ID,
		                  ));
		return $req->fetchAll(PDO::FETCH_CLASS);
	} catch (Exception $e) {
		$GLOBALS['errors'][] = 'Err. get_user_notes : '.$e->getMessage();
	}
}

/*
 * Login
*/
function login_user($username_email, $password) {
	try
	{
		$req = $GLOBALS['db_handle']->prepare('SELECT * FROM gn_users WHERE username=? and password=? LIMIT 1');
		$req->execute(array(
		        $username_email,
		        crypt($password, $GLOBALS['salt']),
		    ));
	}
	catch (Exception $e)
	{
		$GLOBALS['errors'][] = 'Err. login : '.$e->getMessage();
	}

	// Check if result right, else try with email
	$user = $req->fetch(PDO::FETCH_OBJ);
	if($user === false)
	{
		// Retry with email this time
		try
		{
			$req = $GLOBALS['db_handle']->prepare('SELECT * FROM gn_users WHERE email=? and password=? LIMIT 1');
			$req->execute(array(
					$username_email,
					crypt($password, $GLOBALS['salt']),
				));
		}
		catch (Exception $e)
		{
			$GLOBALS['errors'][] = 'Err. login : '.$e->getMessage();
		}
		$user = $req->fetch(PDO::FETCH_OBJ);
	}

	// If still false, login is wrong
	if($user === false)
	{
		// wait for 0.5 seconds for preventing bruteforcing
		usleep(500000);
		$GLOBALS['infos'][] = 'Wrong login';
	}
	else
	{
		// Fill session with candy
		session_set_cookie_params(365*24*60*60);
		$_SESSION['user'] = $user;
		$GLOBALS['infos'][] = 'Login successful';
	}
}

