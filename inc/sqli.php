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


/*  Creates a new GeoNotes-server base.
    if file does not exists, it is created, as well as the tables.
    if file does exists, tables are checked and created if not exists
*/
function create_tables() {
	$requests['notes'] = "CREATE TABLE notes
		(
			ID INTEGER PRIMARY KEY,
			gn_lat REAL,
			gn_lon REAL,
			gn_title TEXT,
			gn_text TEXT,
			gn_user INTEGER,
			gn_karma INTEGER,
			gn_creation NUMERIC,
			gn_lifetime INTEGER,
			gn_lang TEXT,
			gn_cat TEXT
		); CREATE INDEX toUser ON notes ( gn_usr );";

	$requests['users'] = "CREATE TABLE users
		(
			ID INTEGER PRIMARY KEY,
			gn_email TEXT,
			gn_username TEXT,
			gn_password TEXT,
			gn_settings TEXT
		);";

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
		$db_handle->query("PRAGMA temp_store=MEMORY; PRAGMA synchronous=OFF; PRAGMA journal_mode=WAL;");
		// list tables
		$list_tbl = $db_handle->query("SELECT name FROM sqlite_master WHERE type='table'");
		// make an normal array, need for "in_array()"
		$tables = array();
		foreach($list_tbl as $j) {
			$tables[] = $j['name'];
		}

		// check each wanted table (this is because the "IF NOT EXISTS" condition doesn’t exist in lower versions of SQLite.
		$wanted_tables = array('notes', 'users');
		foreach ($wanted_tables as $i => $name) {
			if (!in_array($name, $tables)) {
				$results = $db_handle->exec($requests['dbase_structure'][$name]);
			}
		}
	} catch (Exception $e) {
		die('Erreur 1: '.$e->getMessage());
	}

	return $db_handle;
}


/* Open a base */
function open_base() {
	$handle = create_tables();
	return $handle;
}

function db_note($note, $what) {
	if ($what == 'add') {
		try {
			$req = $GLOBALS['db_handle']->prepare('INSERT INTO notes
			(
				gn_lat,
				gn_lon,
				gn_title,
				gn_text,
				gn_user,
				gn_karma,
				gn_creation,
				gn_lifetime,
				gn_lang,
				gn_cat
			)
			VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');

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
				$note['cat']
			));
			return TRUE;
		} catch (Exception $e) {
			return 'Err. note>add : '.$e->getMessage();
		}

	} elseif ($what == 'edit') {
		try {
			$req = $GLOBALS['db_handle']->prepare('UPDATE notes SET
				gn_title=?,
				gn_text=?,
				gn_lifetime=?,
				gn_lang=?,
				gn_cat=?
				WHERE ID=?');
			$req->execute(array(
				$note['title'],
				$note['text'],
				$note['lifetime'],
				$note['lang'],
				$note['cat'],
				$note['ID']
			));
			return TRUE;
		} catch (Exception $e) {
			return 'Err. note>edit : '.$e->getMessage();
		}
	}

	elseif ($what == 'delete') {
		try {
			$req = $GLOBALS['db_handle']->prepare('DELETE FROM notes WHERE ID=?');
			$req->execute(array($note['ID']));
			return TRUE;
		} catch (Exception $e) {
			return 'Err. note>delete : '.$e->getMessage();
		}
	}
}

function db_user($user, $what) {
	if ($what == 'add') {
		try {
			$req = $GLOBALS['db_handle']->prepare('INSERT INTO users
			(
				gn_email,
				gn_username,
				gn_password,
			)
			VALUES (?, ?, ?)');

			$req->execute(array(
				$user['email'],
				$user['usuername'],
				$user['password']
			));
			return TRUE;
		} catch (Exception $e) {
			return 'Err. user>add : '.$e->getMessage();
		}

	} elseif ($what == 'edit') {
		try {
			$req = $GLOBALS['db_handle']->prepare('UPDATE users SET
				gn_email=?,
				gn_username=?,
				gn_password=?,
				WHERE ID=?');
			$req->execute(array(
				$user['email'],
				$user['username'],
				$user['password']
				$user['ID']
			));
			return TRUE;
		} catch (Exception $e) {
			return 'Err. user>edit : '.$e->getMessage();
		}
	}

	elseif ($what == 'delete') {
		try {
			$req = $GLOBALS['db_handle']->prepare('DELETE FROM users WHERE ID=?');
			$req->execute(array($user['ID']));
			return TRUE;
		} catch (Exception $e) {
			return 'Err. user>delete : '.$e->getMessage();
		}
	}
}
