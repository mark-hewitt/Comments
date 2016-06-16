<?php include_once 'includes/functions.php'; ?>
<!DOCTYPE html>
<html lang='en'>
	<head>
		<meta charset='utf-8'>
		<meta name='viewport' content='width=device-width, initial-scale=1.0'> 

		<title>Comment System</title>

		<link rel='stylesheet' type='text/css' href='style.css' media='screen'>
	</head>
	<body>

		<section class='container'>
			<div class='instructions'>
				<p>All Comments (<?=getNumberOfComments()?>)</p>
				<hr />
				<p>Enter a brief comment</p>
			</div>
			<form action='post.php' method='post'>
				<input type='hidden' name='parent' value='0' />
				<p><textarea name='body' maxlength='5000'></textarea></p>
				<p><button>Save</button> <button type='reset' class='cancel'>Cancel</button></p>
			</form>
			<a class='toggle_all_children_comments' href='#'>Hide all children comments</a>

			<?=getChildren()?>
		</section>

		<script src='https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js'></script>
		<script src='scripts.js'></script>

	</body>
</html>