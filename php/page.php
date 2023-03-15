<?php
/**
 * this class represents a page of the Stendhal website
 *
 * @author hendrik
 */
class Page {
	/**
	 * this method can write additional http headers, for example for cache control.
	 *
	 * @return true, to continue the rendering, false to not render the normal content
	 */
	public function writeHttpHeader() {
		// do nothing
		return true;
	}

	/**
	 * this method can write additional html headers, for example the &lt;title&gt; tag.
	 */
	public function writeHtmlHeader() {
		echo '<title>'.GAME_TITLE.'</title>';
	}

	/**
	 * this methos can add attributes to the body tag.
	 *
	 * @return string attributes for the body tag
	 */
	public function getBodyTagAttributes() {
		return "";
	}

	/**
	 * this methods writes the content area of the page.
	 */
	public function writeContent() {
		// do nothing
	}
	/**
	 * this methods writes the modals.
	 */
	public function writeModals() {
	    // do nothing
	}

	/**
	 * this methods returns breadcrumb information
	 *
	 * @return array breadcrumb information (name1, url1, name2, url2)
	 */
	public function getBreadCrumbs() {
		return null;
	}

	public function includeJs() {
		// deprecated
	}

	public function writeAfterJS() {
	}

	/**
	 * adds a box with related pages, if there are any resutls
	 *
	 * @param string $propName name of property
	 * @param string $category category to filter the results
	 * @param string $title title of the box
	 */
	public function writeRelatedPages($propName, $category, $title) {
		$res = Wiki::findRelatedPages($propName, $category);
		if (count($res) > 0) {
		    startHeader3(htmlspecialchars($title));
			echo '<ul>';
			foreach ($res as $row) {
				echo '<li><a href="'.htmlspecialchars($row['path']).'">'.htmlspecialchars($row['title']).'</a>';
			}
			echo '</ul>';
		}
	}
}
$page = new Page();