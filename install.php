<?php
	// Initialisation script
	require 'kint/Kint.class.php';
	require_once 'inc/lang.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>GeoNotes server</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

<?php
	// pdo_sqlite
	if (!extension_loaded('pdo_sqlite')) {
		$errors[] = '<li><b>pdo_sqlite</b> PHP-modules not loaded. Please enable before retrying setup.</li>'."\n";
	}
	// check directory readability
	if (!is_writable('../') ) {
		$errors[] = '<li>Write rights needed. (chmod of home folder must be 644 at least, 777 recommended).</li>'."\n";
	}
	// Everything fine?
	if (!empty($errors)) {
		echo '<ol>'."\n";
		echo implode($errors, '');
		echo '</ol>'."\n";
		echo '<p>Installation aborded.</p>'."\n";
		echo '</div>'."\n".'</div>'."\n".'</html>';
		die;
	} else {
		if ($GLOBALS['lang']['id'] === 'default') {
			$langs = array(
			        "fr" => 'Français',
			        "en" => 'English'
			    );

			echo '<ul>';
			foreach ($langs as $id => $lang) {
				echo "<li><a href='".$_SERVER['PHP_SELF']."?lang=$id'><code>[$id]</code> $lang</a></li>";
			}
			echo '</ul>';
		} else {
			echo '<h1>'.$GLOBALS['lang']['welcome'].'</h1>'."\n";
			echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'].'">'."\n";
			echo '<p>';
			echo '<label for="username">'.$GLOBALS['lang']['username'].'</label><input type="text" name="username" id="username" size="30" value="" />'."\n";
			echo '</p>'."\n";
			echo '<p>';
			echo '<label for="password">'.$GLOBALS['lang']['password'].'</label><input type="password" name="password" id="password" size="30" value="" autocomplete="off" />'."\n";
			echo '</p>'."\n";
			echo '<p>';
			echo '<label for="email">'.$GLOBALS['lang']['email'].'</label><input type="email" name="email" id="email" size="30" value="" />'."\n";
			echo '</p>'."\n";
			echo '<input type="hidden" name="lang" value="'.$GLOBALS['lang']['id'].'" />'."\n";
			echo '<input type="submit" name="send" value="OK" />'."\n";
			echo '</form>'."\n";
		}
	}

?>
</body>
</html>
