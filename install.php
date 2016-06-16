<?php $create_table_query = "CREATE TABLE IF NOT EXISTS `comments` ( `id` int(11) NOT NULL AUTO_INCREMENT, `parent` int(11) NOT NULL, `body` text NOT NULL, `date_submitted` int(10) NOT NULL, PRIMARY KEY (`id`) ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;"; ?>

<!DOCTYPE html>
<html lang='en'>
	<head>
		<meta charset='utf-8'>
		<meta name='viewport' content='width=device-width, initial-scale=1.0'>

		<title>Comment System</title>

		<link rel='stylesheet' type='text/css' href='style.css' media='screen'>
		
		<style>
			/*-----------------------------------------------------------------------
			   INSTALLATION STYLES
			   Yes, I know it's better if I put the styles in a separate CSS file,
			   but this is an all-in-one installation file, so... deal with it.
			-----------------------------------------------------------------------*/
			body.install {
				margin-top: 60px;
			}
			.install label {
				display: block;
				cursor: pointer;
				line-height: 30px;
				margin: 15px auto;
			}
			body.install > * {
				width: 100%;
				max-width: 400px;
				margin: 15px auto;
			}
			.install input,
			.install select,
			.install textarea,
			.install button {
				float: right;
				width: 100%;
				max-width: 200px;
				border: 1px solid #aaa;
				border-radius: 3px;
				height: 30px;
				padding: 0;
				text-indent: 15px;
			}
			.install button {
				text-indent: 0;
				cursor: pointer;
			}
			ul#status {
				list-style-type: none;
				padding: 0;
				margin-bottom: 60px;
			}
			ul#status li {
				margin-bottom: 16px;
			}
			.install .icon {
				font-weight: bold;
				color: #25d425;
				font-size: 18px;
				text-transform: uppercase;
				position: relative;
			}
			.install .icon ~ form {
				margin-top: 60px;
			}
			.install .icon:nth-child(3) {
				margin-bottom: 60px;
			}
			.install .icon:before {
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
			.install .icon.error {
				color: #bd4545;
			}
			.install .icon.error:before {
				content: "✖";
				background: #bd4545;
			}
			.install #confirm {
				text-align: center;
			}
			.install #confirm button {
				float: none;
				display: block;
				margin: 30px auto 20px;
			}
			.install .error label {
				color: red;
				font-weight: bold;
			}
			.install .error input {
				border-color: red;
			}
		</style>
	</head>
	<body class='install'>

<?php
$error = 0;
if (
	isset($_POST['host']) &&
	isset($_POST['user']) &&
	isset($_POST['password']) &&
	isset($_POST['database'])
) {
	$host = $_POST['host'];
	$user = $_POST['user'];
	$password = $_POST['password'];
	$database = $_POST['database'];

	// Update "constants.php" file with new information. This bit will automatically create the 'constants.php' file if it doesn't exist.
		$constants = fopen("includes/constants.php", "w") or die("Unable to open file!");
		$txt = "";
		$txt .= "<?php\n";
			$txt .= "\tdefine('HOST', '{$host}'); // The host you want to connect to.\n";
			$txt .= "\tdefine('USER', '{$user}'); // The database username.\n";
			$txt .= "\tdefine('PASSWORD', '{$password}'); // The database password.\n";
			$txt .= "\tdefine('DATABASE', '{$database}'); // The database name.\n";
			$txt .= "\tdefine('TABLE', 'comments'); // The table name.\n";
		$txt .= "?>";
		fwrite($constants, $txt);
		fclose($constants);

	// Try connecting to the database with the provided credentials
		$mysqli = @new mysqli($host, $user, $password, $database);

	// Was the database connection attempt successful?
		if ($mysqli->connect_error) {
			// No, it wasn't
				echo "<ul id='status'>";
					echo "<li class='icon error'>Database connection</li>";
				echo "</ul>";
				$error++;

				echo "<form method='post' autocomplete='false' class='error'>";
					echo "<label>";
						echo "HOST:";
						echo "<input type='text' name='host' maxlength='255' value='<?=$host?>' required />";
					echo "</label>";
					echo "<label>";
						echo "USERNAME:";
						echo "<input type='text' name='user' maxlength='255' value='<?=$user?>' required />";
					echo "</label>";
					echo "<label>";
						echo "PASSWORD:";
						echo "<input type='password' name='password' maxlength='255' />";
					echo "</label>";
					echo "<label>";
						echo "DATABASE:";
						echo "<input type='text' name='database' maxlength='255' value='<?=$database?>' required />";
					echo "</label>";
					echo "<button>Check connection</button>";
				echo "</form>";
		} else {
			// Yes, it was
				echo "<ul id='status'>";
					echo "<li class='icon success'>Database connection</li>";

				if (isset($_POST['confirm'])) {
					if ($mysqli->query($create_table_query)) {
						echo "<li class='icon success'>Create table</li>";
					} else {
						echo "<li class='icon error'>Create table</li>";
						$error++;
					}
					echo "</ul>";

					// If there was an issue with creating the table, let the user know
						if ($error) {
							echo "<p>Something went wrong. If you'd like, <a href='install.php'>you&nbsp;can&nbsp;start&nbsp;from&nbsp;the&nbsp;beginning</a>.</p>";
						} else {
							echo "<p>Script installed. Thank you.</p>";
							echo "<p><strong>PLEASE DELETE 'index.php' IMMEDIATELY!</strong></p>";
							echo "<p>Please <a href='index.php'>return home</a>.</p>";
							echo "<script>alert('Thank you for installing this script.\\n\\nYou still need to remove the install.php file. Please do so IMMEDIATELY, or your site may be at risk of being hacked.\\n\\n\\n\\n')</script>";
						}
				} else {
					echo "</ul>";

					echo "<p>Are you sure you wish to install this script?</p>";
					echo "<p>By agreeing to install this script, you understand that you are choosing to install </p>";
					echo "<p>";
						echo "<form method='post' id='confirm'>";
							echo "<input type='hidden' name='host' value='{$host}' />";
							echo "<input type='hidden' name='user' value='{$user}' />";
							echo "<input type='hidden' name='password' value='{$password}' />";
							echo "<input type='hidden' name='database' value='{$database}' />";
							echo "<input type='hidden' name='confirm' value='confirm' />";
							echo "<button>Install</button>";
							echo "<a href='index.php'>Cancel</a>";
						echo "</form>";
					echo "</p>";
			}
		}
} else {
	//--------------------------------------------------------------------------
	// POSSIBLE SECURITY ISSUE:
	//--------------------------------------------------------------------------
	// If the host is already defined, then don't allow the user to reset stuff.
	// This is a major security hole, and needs to be prevented. If the user is
	// legitimate, they will be able to delete or modify the constants without
	// having to go through this installation tool.

	$error=0;
	if (file_exists('includes/constants.php')) {
		include_once 'includes/constants.php';
		if (!defined('HOST')) {
			$error++;
		} else {
			// Try connecting to the database with the provided credentials
				$mysqli = @new mysqli($host, $user, $password, $database);

			// Was the database connection attempt successful?
				if ($mysqli->connect_error) {
					// No, it wasn't.
					$error++;
				} else {
					if(!$mysqli->query("DESCRIBE `".TABLE."`")) {
						// Can't connect to table.
						$error++;
					}
				}
		}
	} else {
		$error++;
	}

	if ($error) {
		echo "<h1>Welcome,</h1>";
		echo "<p>Before you can install this script, you need to enter your database credentials below.</p>";
		echo "<form method='post' autocomplete='false'>";
			echo "<label>";
				echo "HOST:";
				echo "<input type='text' name='host' maxlength='255' value='localhost' required />";
			echo "</label>";
			echo "<label>";
				echo "USERNAME:";
				echo "<input type='text' name='user' maxlength='255' required />";
			echo "</label>";
			echo "<label>";
				echo "PASSWORD:";
				echo "<input type='password' name='password' maxlength='255' value='' />";
			echo "</label>";
			echo "<label>";
				echo "DATABASE:";
				echo "<input type='text' name='database' maxlength='255' required />";
			echo "</label>";
			echo "<button>Check connection</button>";
		echo "</form>";
	} else {
		echo "<p>The information for this script has already been defined. Please manually remove the 'constants.php' file before coming back to this tool.</p>";
	}
}
?>
	</body>
</html>