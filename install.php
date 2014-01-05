<?php
	// Initialisation script
	require 'inc/inc.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>GeoPost server</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

<?php
	// pdo_sqlite
	if (!extension_loaded('pdo_sqlite')) {
		$errors[] = '<li><b>pdo_sqlite</b> PHP-modules not loaded. Please enable before retrying setup.</li>'."\n";
	}
	// check directory readability
	if (!is_writable('.') ) {
		$errors[] = '<li>Write rights needed. (chmod of home folder must be 644 at least, 777 recommended).</li>'."\n";
	}
	// Everything fine?
	if (!empty($errors)) {
		echo '<ul>'."\n";
		echo implode($errors, '');
		echo '</ul>'."\n";
		echo '<p>Installation aborded.</p>'."\n";
		echo '</div>'."\n".'</div>'."\n".'</html>';
		die;
	} else {
		if ($GLOBALS['lang']['id'] === 'default') {
			// Stage 1 in setup
			$langs = array(
			        "fr" => 'Français',
			        "en" => 'English'
			    );

			echo '<ul>';
			foreach ($langs as $id => $lang) {
				echo "<li><a href='".$_SERVER['PHP_SELF']."?lang=$id'><code>[$id]</code> $lang</a></li>";
			}
			echo '</ul>';
		} else if (!isset($_POST['username']) || !isset($_POST['password']) || !isset($_POST['email'])) {
			// Stage 2
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
		} else {
			// Stage 3, create initial user
			require_once 'inc/sqli.php';

			echo '<h1>'.$GLOBALS['lang']['h1_create_user'].'</h1>'."\n";

			open_base();
			$req = db_user('add', array(
			                        'email' => $_POST['email'],
			                        'username' => $_POST['username'],
			                        'password' => $_POST['password']));

			// Login the user right now
			$user = login_user($_POST['username'], $_POST['password']);

			if($req === true)
			{
				echo '<p>'.$GLOBALS['lang']['p_create_user_success'].'</p>';
			}
			else if($req === 'Err. user>add : SQLSTATE[23000]: Integrity constraint violation: 19 column username is not unique')
			{
				echo '<p>'.$GLOBALS['lang']['p_create_user_error'].' ';
				echo       $GLOBALS['lang']['p_create_user_error_username'].'</p>';
			}
			else if($req === 'Err. user>add : SQLSTATE[23000]: Integrity constraint violation: 19 column email is not unique')
			{
				echo '<p>'.$GLOBALS['lang']['p_create_user_error'].' ';
				echo       $GLOBALS['lang']['p_create_user_error_email'].'</p>';
			}
			else
			{
				echo '<p>'.$GLOBALS['lang']['p_create_user_error'].'</p>';
				echo '<p>'.$GLOBALS['lang']['p_create_user_error_unknown'].'</p>';
				echo '<pre>'.$req.'</pre>';
			}



			echo '<h1>Note</h1>';
			echo '<h2>Add</h2>';
			echo '<h2>Edit</h2>';
			echo '<h2>Delete</h2>';

			echo '<h1>Clean</h1>';
		}
	}

?>
</body>
</html>
