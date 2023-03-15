<?php
class ForumSubPage extends Page {
	public function writeHtmlHeader() {
		echo '<title>Forum'.GAME_TITLE.'</title>';
		if (isset($_POST['postDelete']) || isset($_POST['postPin']) || isset($_POST['postClose']) || isset($_POST['postUnpin']) || isset($_POST['postOpen'])) {
			echo '<meta http-equiv="refresh" content="0;url='.$_SERVER['REQUEST_URI'].'">';
		}
	}

	function getTitle() {
		$url = $_GET['subcategory'];
		$pos = strrpos($url, '-');
		$title = substr($url, $pos + 1);
		return $title;
	}

	function getSubcategoryID() {
		$url = $_GET['subcategory'];

		if (is_numeric($url)) {
			return abs(intval($url));
		}

		$pos = strrpos($url, '-');
		$id = substr($url, $pos - 1);
		return intval($id);
	}

	function getCategory() {
		$categoryTitle = null;
		foreach (Forum_Categories::_getCategories() as $category) {
			foreach (getSubCategories() as $subcategory) {
				if ($subcategory['topicId'] == $this->getSubcategoryID()) {
					if ($subcategory['catId'] == $category->id) {
						$categoryTitle = $category->title;
					}
				}
			}
		}
		return $categoryTitle;
	}

	function writeContent() {
		if (!isset($_GET['subcategory'])) {
			include(TEMPLATE_PATH.'/'.TEMPLATE.'/forum/forum_page.php');
			return;
		}

		include(TEMPLATE_PATH.'/'.TEMPLATE.'/forum/forum_subcategory.php');
		return;
	}

	public function getBreadCrumbs() {
		$array = array('Forum', '/forum/');
		if (isset($_GET['subcategory'])) {
			if ($this->getCategory() !== null) {
				$array[] = ucfirst(htmlspecialchars($this->getCategory()));
				$array[] = '/forum/'.htmlspecialchars(strtolower($this->getCategory()));
			}
			$array[] = ucfirst(htmlspecialchars($this->getTitle()));
			$array[] = '/forum/sub/'.$_GET['subcategory'];
		}
		return $array;
	}
}

$page = new ForumSubPage();