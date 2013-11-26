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

if (empty($GLOBALS['lang'])) $GLOBALS['lang'] = '';

$GLOBALS['lang'] = substr($_GET['lang'], 0, 2);

switch ($GLOBALS['lang']) {
	case 'fr':
		include_once('lang/fr_FR.php');
		break;
	case 'en':
		include_once('lang/en_EN.php');
		break;
	default:
		include_once('lang/fr_FR.php');
		$GLOBALS['lang']['id'] = 'default';
}
