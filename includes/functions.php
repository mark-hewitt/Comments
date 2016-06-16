<?php
	date_default_timezone_set('America/New_York');
	include_once 'includes/connect.php';


	// Post function, allowing us to submit comments and replies
		function submitComment($body, $parent = 0) {
			global $mysqli;
			$parent = $mysqli->real_escape_string($parent);
			$body = $mysqli->real_escape_string($body);

			$query = "INSERT INTO `".TABLE."` (`parent`, `body`, `date_submitted`) VALUES ({$parent}, \"".$body."\", ".time().");";
			if (!empty($body)) {
				$mysqli->query($query);
			}

			return $query;
		}


	// Returns an array of all of the comments from the database
		function getComments() {
			global $mysqli;
			$comments = [];

			$query = "SELECT * FROM ".TABLE." ORDER BY date_submitted ASC, parent ASC";
			$posts = $mysqli->query($query);

			// While we're at it, how many comments do we have in total?
				$number_of_comments = $posts->num_rows;

			while ($post = $posts->fetch_assoc()) {
				$comments[] = $post;
			}

			return $comments;
		}


		// Return the total number of comments
			function getNumberOfComments($comment_id = 0) {
				global $mysqli;

				// This is an extremely fast method of polling how many rows are in the database
					$query = "SELECT COUNT(*) AS count FROM ".TABLE;
					$number_of_comments = $mysqli->query($query)->fetch_assoc();

				// Return the count (from the "AS count" part of the query)
					return $number_of_comments['count'];
			}


	// Return a string of HTML containing the comment thread of choice. Pass in a '$comment_id' in order to view the replies to that specific comment.
		$html = "";
		function getChildren($comment_id = 0) {
			global $html;
			$comments = getComments();

			foreach ($comments as $key => $comment) {
					if ($comment['parent'] == $comment_id) {
						$html .= "<article class='{$comment_id}'>";
							$html .= "<div class='tagline'>";
								$html .= "<a class='minimize' href='#'>[--]</a> <a class='user' href='search.php?s=Anonymous'>Anonymous</a>";
								$html .= date('n/j/Y g:ia',$comment['date_submitted']);
							$html .= "</div>";
							$html .= "<div class='body'>";
								$body = stripslashes(str_replace(array('\r\n','\n'),array('<br/>','<br/>'),$comment['body']));
								$html .= $body;
							$html .= "</div>";
							$html .= "<div class='actions'>";

								// Reply to comment
									$html .= "<a class='reply' href='#'>Reply</a>";
								// Toggle child comments for this post
									if ($comment['parent'] == 0) {
										$html .= "<a class='toggle_child_comments' href='#'>Hide child comments</a>";
									}

								// Put the form inside the actions div so that the form is tied to the reply link
									$html .= "<form action='post.php' method='post'>";
										$html .= "<input type='hidden' name='parent' value='{$comment['id']}' />";
										$html .= "<p><textarea name='body' maxlength='5000'></textarea></p>";
										$html .= "<p><button>Save</button> <button type='reset' class='cancel'>Cancel</button></p>";
									$html .= "</form>";
							$html .= "</div>";

							$comment['children'] = getChildren($comment['id']);
						$html .= "</article>";
					}
			}
			return $html;
		}
?>