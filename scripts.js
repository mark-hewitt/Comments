$(function() {
	// Comment action controls
		// Reply to comment
			$("article .actions a.reply").on('click',function(event) {
				event.preventDefault();
				$(this).parent().find('form').toggle();
			});

		// Toggle child comments
			$("article .actions a.toggle_child_comments").on('click',function(event) {
				event.preventDefault();

				var number_of_children = $(this).parent().parent().find('article').size();

				if ($(this).parent().parent().find('article').is(':visible')) {
					$(this).text("Show "+number_of_children+" child comments");
				} else {
					$(this).text("Hide child comments");
				}

				$(this).parent().parent().find('article').toggle();
			});

		// Hide ALL child comments
			$(".toggle_all_children_comments").on('click',function(event) {
				event.preventDefault();


				if ($("section > article").find('article').is(':visible')) {
					$("a.toggle_child_comments:contains('Hide')").click();
					$(this).text("Show all children comments");
				} else {
					$("a.toggle_child_comments:contains('Show')").click();
					$(this).text("Hide all children comments");
				}
			});


	$("article .cancel").on('click',function(event) {
		event.preventDefault();
		$(this).parent().parent().hide();
	});

	$("article a.minimize").on('click',function(event) {
		event.preventDefault();

		var comment_group = $(this).parentsUntil('article');

		if ($(this).text() == "[--]") {
			$(this).text("[+]");
			comment_group.next('.body').hide().next('.actions').hide();
			comment_group.parent().find('article').hide();
		} else {
			$(this).text("[--]");
			comment_group.next('.body').show().next('.actions').show();
			comment_group.parent().find('article').show();
		}
	});
});