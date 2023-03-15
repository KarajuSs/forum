<?php
foreach (getPosts() as $post) {
	if ($post['postId'] == $this->getIDFromURL()) {
		if ($post['closed'] == 1) {
			echo '<div class="row justify-content-end">';
			echo '	<div class="col-md-3 text-right">';
			echo '		<span class="locked"><i class="ion-locked"></i>&nbsp;&nbsp;Temat jest zamknięty</span>';
			echo '	</div>';
			echo '</div>';

			continue;
		}

		generateReplyButton();
	}
}
?>
<div class="nk-gap-2"></div>
<ul class="nk-forum nk-forum-topic">
	<?php
	function getIDFromURL() {
		$url = $_GET['topic'];

		if (is_numeric($url)) {
			return abs(intval($url));
		}

		$pos = strrpos($url, '-');
		$id = substr($url, $pos - 1);
		return intval($id);
	}

	if (isset($_POST['action'])) {
		if ($_REQUEST['action']=='submit') {
			addReply($_REQUEST['postId'], $_REQUEST['catId'], $_REQUEST['subCatId'], $_REQUEST['reply'], $_SESSION['account']->username);
			?>
			<script>
				if (window.history.replaceState) {
					window.history.replaceState(null, null, window.location.href);
				}
			</script>
			<?php
		}
	}

	foreach (getPosts() as $post) {
		if ($post['postId'] == $this->getIDFromURL()) {
			generateTopic(null, $post['description'], $post['author'], $post['created'], false);
		}
		if (sizeof(getReplies(' WHERE postId='.$post['postId'])) > 0) {
			foreach (getReplies(' WHERE postId='.$post['postId']) as $reply) {
				if ($reply['postId'] == $this->getIDFromURL()) {
					generateTopic($reply['id'], $reply['description'], $reply['author'], $reply['date'], true);
					continue;
				}
			}
		}
	}
	?>
</ul>
<div id="forum-reply"></div>
<div class="nk-gap-4"></div>
<?php
if (!isset($_SESSION['account'])) {
	echo '<h3 class="h4">Odpowiedz</h3>';
	echo '<div class="nk-info-box text-info">';
    echo '	<div class="nk-info-box-icon">';
    echo '    	<i class="ion-information"></i>';
    echo '	</div>';
    echo '	<h3>Wymagane logowanie!</h3>';
    echo '	<em>Zaloguj się na istniejące konto gry lub utwórz nowe, aby dołączyć do dyskusji!</em>';
    echo '</div>';
} else {
	foreach(getPosts() as $post) {
		if ($post['postId'] == $this->getIDFromURL()) {
			if ($post['closed'] == 1) {
				continue;
			}

			echo '<form method="post" action="'.WEB_FOLDER.'/forum/topic/'.$_GET['topic'].'">';
			echo '	<h3 class="h4">Odpowiedz</h3>';
			echo '	<div class="nk-gap-1"></div>';
			echo '	<input type="hidden" name="action" value="submit"/>';
			echo '	<input type="hidden" name="postId" value="'.$this->getIDFromURL().'"/>';
			echo '	<input type="hidden" name="catId" value="'.$post['catId'].'"/>';
			echo '	<input type="hidden" name="subCatId" value="'.$post['topicId'].'"/>';
			echo '	<textarea name="reply" cols="30" rows="10" class="nk-summernote form-control"></textarea>';
			echo '	<div class="nk-gap-1"></div>';
			echo '	<input type="submit" class="nk-btn nk-btn-rounded nk-btn-color-white" value="Odpowiedz">';
			echo '</form>';
		}
	}
}
?>