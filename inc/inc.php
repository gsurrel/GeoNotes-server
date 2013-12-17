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

require_once 'kint/Kint.class.php';
// TODO: require some config to load vars, including:
$GLOBALS['infos'] = array();
$GLOBALS['warnings'] = array();
$GLOBALS['errors'] = array();
require_once 'session.php';
require_once 'sqli.php';
require_once 'api.php';
require_once 'lang.php';
