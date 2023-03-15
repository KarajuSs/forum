<?php
foreach (Forum_Categories::_getCategories() as $category) {
	$subcategories = Forum_SubCategories::_getSubCategories($category->id);
	foreach ($subcategories as $subcat) {
		$checkTitle = $subcat->id.'-'.strtolower($subcat->title);
		if ($checkTitle == $_GET['subcategory']) {
			generatePageDescription($subcat);
			generateButton('/sub/'.$subcat->id.'-'.surlencode(strtolower($subcat->title)));
		}
	}
}
?>
<div class="nk-gap-2"></div>
<ul class="nk-forum">
	<?php
	foreach (Forum_Categories::_getCategories() as $category) {
		$subcategories = Forum_SubCategories::_getSubCategories($category->id);
		foreach ($subcategories as $subcat) {
			$checkTitle = $subcat->id.'-'.strtolower($subcat->title);
			if ($checkTitle == $_GET['subcategory']) {
				$posts = Forum_Posts::_getPosts($subcat->id, false);
				if (sizeof($posts) > 0) {
					foreach ($posts as $post) {
						$link = 'topic/'.$post->id.'-'.surlencode(strtolower($post->title));

						$amountReplies = getAmountOfReplies(' WHERE postId='.$post->id);
						$replyTrigger = 'odpowiedzi';
						if ($amountReplies == 1) {
							$replyTrigger = 'odpowiedź';
						}

						$newestReply = Forum_Replies::_getReplies($post->id, true, 'LIMIT 1');
						if ($amountReplies > 0) {
							foreach ($newestReply as $newestReply) {
								generateForum($post, $newestReply, $link, $amountReplies.' '.$replyTrigger);
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