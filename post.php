<?php
include_once 'includes/functions.php';

if (isset($_POST['body'])) {
	// Receive and pass variables. Don't sanatize the strings here, do that in the 'submitComment()' function, so it can be centralized for future updates.
		$body = $_POST['body'];
		if (isset($_POST['parent'])) {
			$parent = $_POST['parent'];
		} else {
			$parent = 0;
		}

	// Make a call to the 'submitComment()' function, and push it to the database
		submitComment($body, $parent);

	// Once we're done, you can redirect to the homepage so the user can see the newly posted comment. This won't be tripped when we use AJAX to post without reloading the page.
		header('Location: index.php');
}
?>