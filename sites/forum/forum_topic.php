<?php
class ForumTopicPage extends Page {
	public function writeHtmlHeader() {
		echo '<title>Forum'.GAME_TITLE.'</title>';
		if (isset($_POST['commentDelete'])) {
			echo '<meta http-equiv="refresh" content="0;url='.$_SERVER['REQUEST_URI'].'">';
		}
	}

	function getIDFromURL() {
		$url = $_GET['topic'];

		if (is_numeric($url)) {
			return abs(intval($url));
		}

		$pos = strrpos($url, '-');
		$id = substr($url, $pos - 1);
		return intval($id);
	}

	function getPostFromID() {
		$sql = 'SELECT * FROM forum_posts WHERE postId = '.$this->getIDFromURL();
		$stmt = DB::web()->query($sql);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	function getCategoryFromID($id) {
		$sql = 'SELECT * FROM forum_categories WHERE catId = '.$id;
		$stmt = DB::web()->query($sql);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	function getTopicTitle() {
		foreach ($this->getPostFromID() as $post) {
			return $post['title'];
		}
	}

	function getTopicPrefix() {
		foreach ($this->getPostFromID() as $post) {
			if ($post['closed'] == 1) {
				return '[Zamknięty] ';
			} else if ($post['pinned']) {
				return '[Przypięty] ';
			}
		}
	}

	function getSubCategoryIfExists() {
		foreach ($this->getPostFromID() as $post) {
			if ($post['topicId'] > 0) {
				$topics = getSubCategories(' WHERE catId='.$post['catId']);
				foreach ($topics as $topic) {
					if ($post['topicId'] == $topic['topicId']) {
						return $topic['title'];
					}
				}
			}
			continue;
		}
	}

	function getSubCategoryTitleWithID() {
		foreach ($this->getPostFromID() as $post) {
			if ($post['topicId'] > 0) {
				$topics = getSubCategories(' WHERE catId='.$post['catId']);
				foreach ($topics as $topic) {
					if ($post['topicId'] == $topic['topicId']) {
						return $topic['topicId'].'-'.$topic['title'];
					}
				}
			}
			continue;
		}
	}

	function getCategory() {
		foreach ($this->getPostFromID() as $post) {
			$categories = $this->getCategoryFromID($post['catId']);
			foreach ($categories as $category) {
				return $category['title'];
			}
		}
	}

	function writeContent() {
		if (!isset($_GET['topic'])) {
			include(TEMPLATE_PATH.'/'.TEMPLATE.'/forum/forum_page.php');
			return;
		}

		include(TEMPLATE_PATH.'/'.TEMPLATE.'/forum/forum_topic.php');
		return;
	}

	public function getBreadCrumbs() {
		$array = array('Forum', '/forum/');
		if (isset($_GET['topic'])) {
			$array[] = ucfirst($this->getCategory());
			$array[] = '/forum/'.htmlspecialchars(strtolower($this->getCategory()));
			if ($this->getSubCategoryIfExists() !== null) {
				$array[] = ucfirst(htmlspecialchars($this->getSubCategoryIfExists()));
				$array[] = '/forum/sub/'.strtolower(surlencode($this->getSubCategoryTitleWithID()));
			}
			$array[] = $this->getTopicPrefix().ucfirst(htmlspecialchars($this->getTopicTitle()));
			$array[] = '/forum/topic/'.$_GET['topic'];
		}
		return $array;
	}
}

$page = new ForumTopicPage();