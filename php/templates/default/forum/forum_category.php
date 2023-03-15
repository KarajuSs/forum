<?php
$categories = Forum_Categories::_getCategories();
foreach ($categories as $category) {
	if (strtolower($category->title) == $_GET['category']) {
		generatePageDescription($category);

		$subcategories = Forum_SubCategories::_getSubCategories($category->id);
		if (sizeof($subcategories) < 1) {
			generateButton('/'.surlencode(strtolower($category->title)));
			continue;
		}
	}
}
?>
<div class="nk-gap-2"></div>
<ul class="nk-forum">
	<?php
	foreach ($categories as $category) {
		if (surlencode(strtolower($category->title)) == $_GET['category']) {
			$subcategories = Forum_SubCategories::_getSubCategories($category->id);
			if (sizeof($subcategories) > 0) {
				foreach ($subcategories as $subcat) {
					$link = 'sub/'.$subcat->id.'-'.surlencode(strtolower($subcat->title));
					$posts = Forum_Posts:: _getPosts($subcat->id, false, 'LIMIT 1');
					if (sizeof($posts) < 1) {
						generateSubNoTopics($link, $subcat, '0 tematów', 'Brak tematów');
					}
					foreach ($posts as $post) {
						$amount = getAmountOfPosts(' WHERE topicId='.$subcat->id);
						$topic = 'tematów';
						if ($amount == 1) {
							$topic = 'temat';
						} elseif ($amount > 1 && $amount < 5) {
							$topic = 'tematy';
						}
						$topicActivity = $amount.' '.$topic;
						$reply = 'odpowiedzi';
						if (getAmountOfPosts(' WHERE catId='.$category->id) == 1) {
							$reply = 'odpowiedź';
						}
						$replyActivity = getAmountOfReplies(' WHERE subCatId='.$subcat->id).' '.$reply;
						generateSub($link, $subcat, $post, $topicActivity, $replyActivity);
					}
				}
			} else {
				$posts = Forum_Posts::_getPosts($category->id, true);
				if (sizeof($posts) > 0) {
					foreach ($posts as $post) {
						$link = 'topic/'.$post->id.'-'.surlencode(strtolower($post->title));
						$amountReplies = getAmountOfReplies(' WHERE postId='.$post->id);
						$replyTrigger = 'odpowiedzi';
						if ($amountReplies == 1) {
							$replyTrigger = 'odpowiedź';
						}
						if ($amountReplies > 0) {
							foreach (Forum_Replies::_getReplies($post->id, true, 'LIMIT 1') as $reply) {
								generateForum($post, $reply, $link, $amountReplies.' '.$replyTrigger);
							}
						} else {
							generateForum($post, $post, $link, $amountReplies.' '.$replyTrigger);
						}
					}
				}
			}
		}
	}
	?>
</ul>
