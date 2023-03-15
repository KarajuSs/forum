<?php
class MainPage extends Page {
	/**
	 * this method can write additional http headers, for example for cache control.
	 *
	 * @return true, to continue the rendering, false to not render the normal content
	 */
	function writeHttpHeader() {
		global $protocol;
		if ($protocol == 'https') {
		    header('X-XRDS-Location: '.LOGIN_TARGET.'/?id=content/account/openid-provider&xrds');
		}
		return true;
	}

	public function writeHtmlHeader() {
		echo '<title>'.substr(GAME_TITLE, strpos(GAME_TITLE, ' ', 2) + 1).'</title>'."\n";
		echo '<link rel="alternate" type="application/rss+xml" title="PolanieOnLine News" href="/rss/news.rss" >'."\n";
		echo '<meta name="keywords" content="polska, pol, PolanieOnLine, game, gra, Spiel, Rollenspiel, juego, role, gioco, online, open, source, multiplayer, roleplaying, foss, floss, Adventurespiel, morpg, rpg">';
		echo '<meta name="description" content="PolanieOnLine to przyjazna dla zabawy i darmowa gra online dla wielu graczy. Zacznij grać, zaczepiaj się... Pobierz kod źródłowy i dodaj własne pomysły ...">';
	}

	function writeContent() {
		include(TEMPLATE_PATH.'/'.TEMPLATE.'/'.TEMPLATE.'_welcome.php');

		if (ENABLE_NEWS) {
			include(TEMPLATE_PATH.'/'.TEMPLATE.'/'.TEMPLATE.'_news.php');
		}

		include(TEMPLATE_PATH.'/'.TEMPLATE.'/'.TEMPLATE.'_aside.php');
	}

	function writeAfterJS() {
		echo '<script src="'.WEB_FOLDER.'/assets/js/pol-top3.js"></script>';
	}
}
$page = new MainPage();