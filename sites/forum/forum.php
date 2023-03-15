<?php
class ForumPage extends Page {
	public function writeHtmlHeader() {
		echo '<title>Forum'.GAME_TITLE.'</title>';
		if (isset($_POST['postDelete']) || isset($_POST['postPin']) || isset($_POST['postClose']) || isset($_POST['postUnpin']) || isset($_POST['postOpen'])) {
			echo '<meta http-equiv="refresh" content="0;url='.$_SERVER['REQUEST_URI'].'">';
		}
	}

	function writeContent() {
		if (!isset($_GET['category'])) {
			include(TEMPLATE_PATH.'/'.TEMPLATE.'/forum/forum_page.php');
			return;
		}

		include(TEMPLATE_PATH.'/'.TEMPLATE.'/forum/forum_category.php');
		return;
	}

	function getCategory() {
		foreach (Forum_Categories::_getCategories() as $category) {
			if (surlencode(strtolower($category->title)) == $_GET['category']) {
				return $category->title;
			}
		}
	}

	public function getBreadCrumbs() {
		$array = array('Forum', '/forum/');
		if (isset($_GET['category'])) {
			$array[] = ucfirst($this->getCategory());
			$array[] = 'no_link';
		}
		return $array;
	}
}

$page = new ForumPage();