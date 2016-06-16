<?php
$error = 0;

if (file_exists('includes/constants.php')) {
	include_once('includes/constants.php');

	// We know the constants.php file exists, but now does it have the proper variable that we need?
		if (
			defined('HOST') &&
			defined('USER') &&
			defined('PASSWORD') &&
			defined('DATABASE') &&
			defined('TABLE')
		) {
			// Attempt to connect to the database
			$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

			// Was the database connection attempt successful?
			if ($mysqli->connect_error) {
				// No
					$error++;
			}

			// Is the table installed? Just by checking the table, we'll know if we need to do any repairs. We should probably also check to make sure the necessary columns are there, but... I'm lazy right now.
			if(!$mysqli->query("DESCRIBE `".TABLE."`")) {
				$error++;
			}
		} else {
			// The 'constants.php' file is either empty, or doesn't contain the necessary variables
				$error++;
		}
} else {
	// The 'constants.php' file doesn't exist
		$error++;
}


// If there are errors, just redirect to the installation file. When all else fails: reinstall.
	if ($error) {
		if (file_exists('install.php')) {
			header('Location: install.php');
		} else {
			// We can't connect to the database, and there is no installation file, so we need to display an error message for confused users.
?>
				<!DOCTYPE html>
				<html lang='en'>
					<head>
						<meta charset='utf-8'>
						<meta name='viewport' content='width=device-width, initial-scale=1.0'> 

						<title>Comment System</title>

						<style>
							body {
								margin: 15px;
								font-family: Arial;
								font-size: 14px;
							}
							article {
								width: 100%;
								max-width: 400px;
								margin: 60px auto;
							}
							.icon {
								font-weight: bold;
								color: #25d425;
								font-size: 18px;
								text-transform: uppercase;
								position: relative;
							}
							.icon:before {
								content: "✔";
								background: #25d425;
								color: white;
								border-radius: 100%;
								display: inline-block;
								width: 24px;
								height: 24px;
								font-size: 17px;
								text-align: center;
								margin-right: 10px;
							}
							.icon.error {
								color: #bd4545;
							}
							.icon.error:before {
								content: "✖";
								background: #bd4545;
							}
						</style>
					</head>
					<body>
						<article>
							<h2 class='icon error'>Oh no! Database error</h2>
							<p>We seem to be having problems connecting to the database. Please try <a href='index.php'>reloading this page</a> in a few minutes.</p>
							<p>If this problem persists, please contact the administrator of this website.</p>
							<p>Thank you for your patience.</p>
						</article>
					</body>
				</html>

<?php
			// Break the rest of the page, so that users don't see any further errors
				exit;
		}
	}
?>