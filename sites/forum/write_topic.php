<?php
class ForumWriteTopic extends Page {
	function getSubCategoryTitleFromURL() {
		if (isset($_GET['subcategory'])) {
			$url = $_GET['subcategory'];
			$pos = strrpos($url, '-');
			$title = substr($url, $pos + 1);
			return $title;
		}
	}

	function getSubCategoryID() {
		if (isset($_GET['subcategory'])) {
			$url = $_GET['subcategory'];
			if (is_numeric($url)) {
				return abs(intval($url));
			}
			$pos = strrpos($url, '-');
			$id = substr($url, $pos - 1);
			return intval($id);
		}
		return '0';
	}

	function getCategoryID() {
		foreach (Forum_Categories::_getCategories() as $category) {
			if (isset($_GET['category'])) {
				if (strtolower($category->title) == $_GET['category']) {
					return $category->id;
				}
			} else {
				foreach (getSubCategories() as $subcategory) {
					if ($subcategory['topicId'] == $this->getSubCategoryID()) {
						if ($subcategory['catId'] == $category->id) {
							return $category->id;
						}
					}
				}
			}
		}
	}

	function getCategoryTitle() {
		foreach (Forum_Categories::_getCategories() as $category) {
			if (isset($_GET['category'])) {
				if (strtolower($category->title) == $_GET['category']) {
					return strtolower($category->title);
				}
			} else {
				foreach (getSubCategories() as $subcategory) {
					if ($subcategory['topicId'] == $this->getSubCategoryID()) {
						if ($subcategory['catId'] == $category->id) {
							return strtolower($category->title);
						}
					}
				}
			}
		}
	}

	function writeContent() {
		if (!isset($_SESSION['account'])) {
			echo '<div class="nk-box-1 bg-dark-2-glass rounded">';
			echo '	<div class="nk-info-box text-info">';
            echo '		<div class="nk-info-box-icon">';
            echo '    		<i class="ion-information"></i>';
            echo '		</div>';
            echo '		<h3>Wymagane logowanie!</h3>';
            echo '		<em>Zaloguj się na istniejące konto gry lub utwórz nowe, aby korzystać z forum!</em>';
        	echo '	</div>';
			echo '</div>';
			return;
		}

		if (isset($_POST['action'])) {
			if ($_REQUEST['action']=='submit') {
				?>
				<script type="text/javascript">
				window.location = "https://s1.polanieonline.eu/forum/";
				</script>
				<?php
				// categoryId, subCategoryId, title, description, author
				addTopic($_REQUEST['catId'], $_REQUEST['subcatId'], $_REQUEST['title'], $_REQUEST['description'], $_SESSION['account']->username);
			}
		}

		startHeader('Dodaj nowy', 'temat');
		echo '<div class="nk-box-1 bg-dark-2 rounded">';

		echo '<a name="editform"></a>';
		if ($this->getCategoryTitle() !== null) {
			echo '<form class="nk-form text-white" method="post" action="'.WEB_FOLDER.'/forum/'.$this->getCategoryTitle().'/add.html" name="submitTopic">';
		} else {
			echo '<form class="nk-form text-white" method="post" action="'.WEB_FOLDER.'/forum/sub/'.$this->getSubCategoryID().'-'.$this->getSubCategoryTitleFromURL().'/add.html" name="submitTopic">';
		}
		echo '	<input type="hidden" name="action" value="submit"/>';

		echo '	<input type="hidden" name="catId" value="'.$this->getCategoryID().'"/>';
		echo '	<input type="hidden" name="subcatId" value="'.$this->getSubCategoryID().'"/>';

		echo '	<span class="field_title">Tytuł</span>';
		echo '  <span class="field_required">Wymagane</span>';
		echo '	<input name="title" class="form-control" required>';
		echo '  <span class="field_description">Krótkie podsumowanie opisanego tematu / problemu.</span>';

		echo '	<div class="gap05"></div>';

		echo '	<span class="field_title">Treść</span>';
		echo '  <span class="field_required">Wymagane</span>';
		echo '	<textarea name="description" cols="30" rows="10" class="nk-summernote form-control" required></textarea>';

		echo '	<div class="nk-gap-2"></div>';
		echo '	<input type="submit" class="nk-btn nk-btn-rounded nk-btn-color-white nk-btn-block" value="Wyślij">';
		echo '</form>';
		echo '</div>';
	}
}

$page = new ForumWriteTopic();