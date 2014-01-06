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
	echo json_encode($response);
else:
?><!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>GeoPost server</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7.1/leaflet.css" />
	<script src="http://cdn.leafletjs.com/leaflet-0.7.1/leaflet.js?2"></script>
</head>
<body>

<?php echo d(json_encode($response)); ?>

<table style="background: #000;border-radius: 8pt;margin: 0 auto 5mm;padding: 0 4pt;box-shadow: 4px 6px 5px #888;"><tr style='vertical-align: text-top;'>
<td style='display: inline-block; color: lightgreen;'>
<h2 style='margin: 0;'>Infos</h2>
<ul style='margin: 0;'>
<?php foreach($GLOBALS['infos'] as $msg) echo '<li>'.$msg.'</li>'; ?>
</ul>
</td>
<td style='display: inline-block; color: orange;'>
<h2 style='margin: 0;'>Warnings</h2>
<ul style='margin: 0;'>
<?php foreach($GLOBALS['warnings'] as $msg) echo '<li>'.$msg.'</li>'; ?>
</ul>
</td>
<td style='display: inline-block; color: red;'>
<h2 style='margin: 0;'>Errors</h2>
<ul style='margin: 0;'>
<?php foreach($GLOBALS['errors'] as $msg) echo '<li>'.$msg.'</li>'; ?>
</ul>
</div>
</tr></table>

<?php if(!isset($_SESSION['user'])): // Not logged in ?>

<h1>Login</h1>
<form method="POST">
	<input type="hidden" name="action" value="login"/>
	<input type="text" name="username_email" value="username or email"/>
	<input type="password" name="password" value="password"/>
	<input type="submit" value="Login" />
</form>

<h1>Register</h1>
<form method="POST">
	<input type="hidden" name="action" value="register"/>
	<label for="username">Username</label>
	<input id="username" type="text" name="username" value="Username"/>
	<br/>
	<label for="email">email</label>
	<input id="email" type="email" name="email" value="email@provider.tld"/>
	<br/>
	<label for="password">Password</label>
	<input id="password" type="password" name="password" value="password" autocomplete="off" />
	<br/>
	<input type="submit" value="Register" />
</form>

<?php else: // Logged in ?>
<style>#forms>form{display: inline-block;} #forms{text-align: center;}</style>
<div id="forms">
<form method="POST">
	<input type="hidden" name="action" value="user"/><input type="submit" value="'user' details"/>
</form>
<form method="POST">
	<input type="hidden" name="action" value="list"/><input type="submit" value="'list' notes around"/>
</form>
<form method="POST">
	<input type="hidden" name="action" value="list_mine"/><input type="submit" value="'list_mine' (notes)"/>
</form>
<form method="POST">
	<?php srand(); ?>
	<input type="hidden" name="action" value="note_add"/>
	<input type="hidden" name="lat" value="<?php echo rand(-90, 90).'.'.rand(); ?>"/>
	<input type="hidden" name="lon" value="<?php echo rand(-180, 180).'.'.rand(); ?>"/>
	<input type="hidden" name="title" value="<?php echo shell_exec("shuf -n5 /usr/share/dict/words | tr '\n' ' '"); ?>"/>
	<input type="hidden" name="text" value="<?php echo shell_exec("shuf -n100 /usr/share/dict/words | tr '\n' ' '"); ?>"/>
	<input type="hidden" name="lifetime" value="0"/>
	<input type="hidden" name="lang" value=""/>
	<input type="hidden" name="cat" value=""/>
	<input type="submit" value="'note_add'"/>
</form>
<form method="POST">
	<input type="hidden" name="action" value="logout"/><input type="submit" value="'logout'"/>
</form>
</div>

<?php if($_POST['action'] === 'user'): ?>

<h1>Welcome <?php echo $response['data']->username; ?>
</h1>

<table style="display: inline-block" border>
	<caption>User details</caption>
	<thead><th>Key</th><th>Value</th></thead>
	<tbody>
	<?php	foreach($response['data'] as $key => $value)
			{
				echo "<tr><td>$key</td><td>$value</td></tr>\n";
			} ?>
	</tbody>
</table>
<table style="display: inline-block" border>
	<caption>Pretty settings</caption>
	<thead><th>Key</th><th>Value</th></thead>
	<tbody>
		<?php foreach(json_decode($response['data']->settings) as $key => $value) echo "<tr><td>$key</td><td>$value</td></tr>" ?>
	</tbody>
</table>

<?php elseif($_POST['action'] === 'list' || $_POST['action'] === 'list_mine'): // $_POST['action'] ?>

<h2>List of <?php if($_POST['action'] === 'list_mine') echo 'my '; ?>notes</h2>
<div id="map" style='height: 550px; width: 700px; margin: auto;'></div>
<script>
	// initialize the map on the "map" div with a given center and zoom
	var map = new L.Map('map', {
		center: new L.LatLng(20, 0),
		zoom: 1,
		minZoom: 1
	});
	// create a tile layer
	var mapquestUrl = 'http://otile{s}.mqcdn.com/tiles/1.0.0/osm/{z}/{x}/{y}.png';
	var mapquest = new L.TileLayer(mapquestUrl, {maxZoom: 18, subdomains: '1234'});
	map.addLayer(mapquest);
	// Data
	var data = <?php echo json_encode($response['data']); ?>;
	for(i=0; i<data.length; i++)
	{
		var marker = L.marker([data[i].lat, data[i].lon]).addTo(map);
		marker.bindPopup("<b>"+data[i].title+"</b><br>"+data[i].text);
	}
</script>
<?php
	foreach($response['data'] as $note)
	{ ?>
<table style="display: inline-block" border>
<thead><th>Key</th><th>Value</th></thead>
<tbody>
<?php	foreach($note as $key => $value)
		{
			echo "<tr><td>$key</td><td>$value</td></tr>\n";
		} ?>
</tbody>
</table>
<?php } ?>

<?php else: // $_POST['action']
	!d($response['data']);
endif; // $_POST['action'] ?>

<?php endif; // (Not) logged in ?>

<?php if(isset($_GET['debug'])): // Debug variables ?>

<h1>Admin</h1>
<?php d($_SESSION); ?>
<?php d(get_users()); ?>
<?php d(get_notes()); ?>

<?php endif; // End of debug variables ?>

<?php endif; // End of API display or HTML ?>
