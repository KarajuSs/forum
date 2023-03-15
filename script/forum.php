<?php
abstract class Forum {
	public $id;
	public $title;
	public $description;

	function __construct($id, $title, $description) {
		$this->id = $id;
		$this->title = $title;
		$this->description = $description;
	}
};

function getUsersRank($user) {
	$sql = 'SELECT forum_ranks.name AS rankName, forum_ranks.privilegeLevel AS privilegeLevel, forum_users_role.name AS nickName FROM forum_ranks LEFT JOIN forum_users_role ON forum_ranks.id = forum_users_role.rank WHERE forum_users_role.name = \''.$user.'\';';
	$stmt = DB::web()->query($sql);
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getPrivilegeLevel($player) {
	$privilegeLevel = '0';
	foreach (getUsersRank($player->name) as $rank) {
		$privilegeLevel = $rank['privilegeLevel'];
		continue;
	}
	if ($player->adminlevel > 0) {
		return $player->adminlevel;
	}
	return $privilegeLevel;
}

function renderNameColour($player, $rank) {
	$class = '';
	if ($rank == 'Administrator') {
		$class = 'class="'.strtolower(renderUserRank($player)).'"';
	} else if ($rank == 'Moderator') {
		$class = 'class="'.strtolower(renderUserRank($player)).'"';
	} else if ($rank == 'Redaktor') {
		$class = 'class="'.strtolower(renderUserRank($player)).'"';
	} else if ($rank == 'Koder') {
		$class = 'class="'.strtolower(renderUserRank($player)).'"';
	} else if ($rank == 'Honorowy') {
		$class = 'class="'.strtolower(renderUserRank($player)).'"';
	} else if ($rank == 'Rycerz') {
		$class = 'class="'.strtolower(renderUserRank($player)).'"';
	} else if ($player->adminlevel >= 1) {
		$class = 'class="'.strtolower(renderUserRank($player)).'"';
	}
	return $class;
}

function renderUserRank($player) {
	$rankTitle = 'Rycerz';
	foreach (getUsersRank($player->name) as $rank) {
		$rankTitle = $rank['rankName'];
		continue;
	}
	if ($player->adminlevel >= 7 && $player->adminlevel < 1000) {
		$rankTitle = 'GameMaster';
	} else if ($player->adminlevel >= 1000 && $player->adminlevel < 5000) {
		$rankTitle = 'GameAdministrator';
	} else if ($player->adminlevel == 5000) {
		$rankTitle = 'GameSupervisor';
	}
	return $rankTitle;
}

function getPrefix($post) {
	if ($post->pinned == 1) {
		return '[Przypięty] ';
	} else if ($post->closed == 1) {
		return '[Zamknięty] ';
	}
}

function plMonths($month) {
	$plMonths = array(
		'Jan' => 'Stycznia',
        'Feb' => 'Lutego',
		'Mar' => 'Marca',
		'Apr' => 'Kwietnia',
		'May' => 'Maja',
		'Jun' => 'Czerwca',
		'Jul' => 'Lipca',
		'Aug' => 'Sierpnia',
		'Sep' => 'Wrzesnia',
		'Oct' => 'Pazdziernika',
		'Nov' => 'Listopada',
		'Dec' => 'Grudnia'
	);
	return $plMonths[$month];
}

function renderDate($date, $time = false) {
	$day = date("j", mktime(0, 0, 0, 0, $date['day'], 0));
	$month = plMonths(date("M", mktime(0, 0, 0, $date['month'] + 1, 0, 0)));
	$year = date("Y", mktime(0, 0, 0, 0, 0, $date['year'] + 1));
	$_date = $day.' '.$month.' '.$year;
	if ($time) {
		$time = date("H:i", mktime($date['hour'], $date['minute'], 0, 0, 0, 0));
		$_date .= ' '.$time;
		return $_date;
	}

	return $_date;
}

function getForumCategories() {
	$sql = 'SELECT * FROM forum_categories';
	$stmt = DB::web()->query($sql);
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getSubCategories($where = '') {
	$sql = 'SELECT * FROM forum_topics'.$where;
	$stmt = DB::web()->query($sql);
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getPosts($where = '') {
	$sql = 'SELECT * FROM forum_posts'.$where;
	$stmt = DB::web()->query($sql);
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAmountOfPosts($where = '') {
	$amount = array();
	$sql = 'SELECT count(*) as amount FROM forum_posts'.$where;
	$rows = DB::web()->query($sql);
	foreach ($rows as $row) {
		$amount=$row['amount'];
	}
	return $amount;
}

function getReplies($where = '') {
	$sql = 'SELECT * FROM forum_replies'.$where;
	$stmt = DB::web()->query($sql);
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAmountOfReplies($where = '') {
	$amount = array();
	$sql = 'SELECT count(*) as amount FROM forum_replies'.$where;
	$rows = DB::web()->query($sql);
	foreach ($rows as $row) {
		$amount=$row['amount'];
	}
	return $amount;
}

function getUserSumOfTopicsAndReplies($nickname) {
	$where = ' WHERE author=\''.$nickname.'\'';
	$topics = getAmountOfPosts($where);
	$replies = getAmountOfReplies($where);

	return $topics + $replies;
}

function generateButton($link) {
	echo '<div class="row justify-content-end">';
	echo '	<div class="col-md-3 text-right">';
	echo '		<a href="/forum'.$link.'/add.html" class="nk-btn nk-btn-rounded nk-btn-color-white">Nowy temat</a>';
	echo '	</div>';
	echo '</div>';
}

function generateReplyButton() {
	echo '<div class="row justify-content-end">';
	echo '	<div class="col-md-3 text-right">';
	echo '		<a href="#forum-reply" class="nk-btn nk-btn-rounded nk-btn-color-white nk-anchor">Odpowiedz</a>';
	echo '	</div>';
	echo '</div>';
}

function generatePageDescription($entity) {
	echo '<div class="nk-feature-1 nk-box-1 bg-dark-2-glass">';
    echo '<span class="forumpage-title">';
    echo ucfirst($entity->title);
    echo '</span>';
	echo '<hr/>';
    echo '<p class="forumpage-description">';
    echo $entity->description;
    echo '</p>';
	echo '</div>';
	echo '<div class="nk-gap"></div>';
}

function generateSubNoTopics($link, $subCategory, $activity, $noTitle) {
	$forumpage = HOME_PAGE.'/forum';

	echo '<li>';
	echo '	<div class="nk-forum-title">';
	echo '		<h3><a href="'.$forumpage.'/'.$link.'">'.$subCategory->title.'</a></h3>';
	echo '		<div class="nk-forum-title-sub">'.$subCategory->description.'</div>';
	echo '	</div>';
	echo '	<div class="nk-forum-count">'.$activity.'</div>';
	echo '	<div class="nk-forum-activity">';
	echo '		<div class="nk-forum-activity-title" title="'.$noTitle.'">';
	echo '			<span>'.$noTitle.'</span>';
	echo '		</div>';
	echo '	</div>';
	echo '</li>';
}

function generateSub($link, $subCategory, $post, $topicActivity, $replyActivity) {
	if ($post->author != null) {
		$player = getPlayer($post->author);
	}
	$forumpage = HOME_PAGE.'/forum';

	echo '<li>';
	echo '	<div class="nk-forum-title">';
	echo '		<h3><a href="'.$forumpage.'/'.$link.'">'.$subCategory->title.'</a></h3>';
	echo '		<div class="nk-forum-title-sub">'.$subCategory->description.'</div>';
	echo '	</div>';
	echo '	<div class="nk-forum-count">'.$topicActivity.'<br/>'.$replyActivity.'</div>';
	if ($post->author != null) {
		echo '	<div class="nk-forum-activity-avatar">';
		echo '		<a href="/character/'.preg_replace('/_/', '+', surlencode($player->name)).'.html">';
		echo '			<img src="/images/outfit/'.surlencode($player->outfit).'.png" alt="'.$post->author.'">';
		echo '		</a>';
		echo '	</div>';
		echo '	<div class="nk-forum-activity">';
		echo '		<div class="nk-forum-activity-title" title="'.$post->title.'">';
		echo '			<a href="'.WEB_FOLDER.'/forum/topic/'.$post->id.'-'.surlencode(strtolower($post->title)).'">'.getPrefix($post).$post->title.'</a>';
		echo '		</div>';
		echo '		<div class="nk-forum-activity-title activity-author" title="'.$player->name.'">';
		echo '			przez <a '.renderNameColour($player, renderUserRank($player)).' href="/character/'.preg_replace('/_/', '+', surlencode($player->name)).'.html">'.$player->name.'</a>';
		echo '		</div>';
		$FDP = date_parse($post->date);
		echo '		<div class="nk-forum-activity-date">'.renderDate($FDP, true).'</div>';
		echo '	</div>';
	}
	echo '</li>';
}

function generateForum($post, $reply, $link, $activity) {
	$player_author = getPlayer($post->author);
	$player_reply = getPlayer($reply->author);
	$forumpage = HOME_PAGE.'/forum';

	echo $post->closed == 1 ? '<li class="nk-forum-locked">' : '<li>';
	echo '	<div class="nk-forum-icon">';
	if ($post->closed == 0) {
		echo '		'.$post->pinned == 1 ? '<span class="ion-pin"></span>' : '<span class="ion-chatboxes"></span>';
	} else {
		echo '		<span class="ion-locked"></span>';
	}
	echo '	</div>';
	echo '	<div class="nk-forum-title">';
	echo '		<h3><a href="'.$forumpage.'/'.$link.'">'.getPrefix($post).$post->title.'</a></h3>';
	$CDP = date_parse($post->date);
	echo '		<div class="nk-forum-title-sub">Rozpoczął <a '.renderNameColour($player_author, renderUserRank($player_author)).' href="/character/'.preg_replace('/_/', '+', surlencode($player_author->name)).'.html">'.$post->author.'</a> '.renderDate($CDP, true).'</div>';
	echo '	</div>';
	echo '	<div class="nk-forum-count">'.$activity.'</div>';
	echo '	<div class="nk-forum-activity-avatar">';
	echo '		<a href="/character/'.preg_replace('/_/', '+', surlencode($player_reply->name)).'.html">';
	echo '			<img src="/images/outfit/'.surlencode($player_reply->outfit).'.png" alt="'.htmlspecialchars($reply->author).'">';
	echo '		</a>';
	echo '	</div>';
	echo '	<div class="nk-forum-activity">';
	echo '		<div class="nk-forum-activity-title" title="'.$reply->author.'">';
	echo '			<a '.renderNameColour($player_reply, renderUserRank($player_reply)).' href="/character/'.preg_replace('/_/', '+', surlencode($player_reply->name)).'.html">'.$reply->author.'</a>';
	echo '		</div>';
	$FDP = date_parse($reply->date);
	echo '		<div class="nk-forum-activity-date">'.renderDate($FDP, true).'</div>';
	echo '	</div>';
	if (isset($_SESSION['account'])) {
		$loggedPlayer = getPlayer($_SESSION['account']->username);
		if (getPrivilegeLevel($loggedPlayer) > 7) {
			echo '	<div class="forum-activity-menu">';
			echo '		<span></span>';
			echo '		<form method="post" action="'.postManager().'">';
			echo '			<ul class="kebab-dropdown">';
			echo '				<input type="hidden" name="postID" value="'.$post->id.'">';
			echo $post->pinned == 1 ? '<li><input type="submit" name="postUnpin" value="Odepnij"/></li>' : '<li><input type="submit" name="postPin" value="Przypnij"/></li>';
			echo $post->closed == 1 ? '<li><input type="submit" name="postOpen" value="Otwórz"/></li>' : '<li><input type="submit" name="postClose" value="Zamknij"/></li>';
			echo '				<li><hr/></li>';
			echo '				<li><input type="submit" name="postDelete" value="Usuń"/></li>';
			echo '			</ul>';
			echo '		</form>';
			echo '	</div>';
		}
	}
	echo '</li>';
}

function generateTopic($id, $description, $author, $date, $isComment = false) {
	$player = getPlayer($author);
	$account=$player->getAccountInfo();

	echo '<li>';
	echo '	<div class="nk-forum-topic-author">';
	echo '		<a href="/character/'.preg_replace('/_/', '+', surlencode($player->name)).'.html">';
	echo '			<img src="/images/outfit/'.surlencode($player->outfit).'.png" alt="'.htmlspecialchars($author).'">';
	echo '		</a>';
	echo '		<div class="nk-forum-topic-author-name" title="'.$author.'">';
	echo '			<a '.renderNameColour($player, renderUserRank($player)).' href="/character/'.preg_replace('/_/', '+', surlencode($player->name)).'.html">'.htmlspecialchars($author).'</a>';
	echo '		</div>';
	echo '		<div class="nk-forum-topic-author-role '.strtolower(renderUserRank($player)).'">'.renderUserRank($player).'</div>';
	$ADP = date_parse($account["register"]);
	echo '		<div class="nk-forum-topic-author-stats">';
	echo '			<div class="nk-forum-topic-author-posts"> Postów: '.getUserSumOfTopicsAndReplies($player->name).'</div>';
	echo '			<div class="nk-forum-topic-author-since"> Użytkownik od <br/>'.renderDate($ADP, false).'</div>';
	echo '		</div>';
	echo '	</div>';
	echo '	<div class="nk-forum-topic-content">';
	echo '		'.$description;
	echo '	</div>';
	echo '	<div class="nk-forum-topic-footer">';
	$FDP = date_parse($date);
	echo '		<span class="nk-forum-topic-date">'.renderDate($FDP, true).'</span>';
	if (isset($_SESSION['account'])) {
		$loggedPlayer = getPlayer($_SESSION['account']->username);
		if (getPrivilegeLevel($loggedPlayer) > 7 && $isComment) {
			echo '	<form class="delete-form" method="post" action="'.deleteComments().'">';
			echo '		<input type="hidden" name="delete" value="'.$id.'">';
			echo '		<span class="nk-forum-action-btn">';
			echo '			<button type="submit" name="commentDelete"><span class="fa fa-trash"></span> Usuń</button>';
			echo '		</span>';
			echo '	</form>';
		}
	}
	echo '		<span class="nk-forum-action-btn">';
	echo '			<a href="#forum-reply" class="nk-anchor"><span class="fa fa-reply"></span> Odpowiedz</a>';
	echo '		</span>';
	// echo '		<span class="nk-forum-action-btn">';
	// echo '			<a href="#"><span class="fa fa-flag"></span> Spam</a>';
	// echo '		</span>';
	echo '	</div>';
	echo '</li>';
}

function postManager() {
	if (isset($_POST['postPin'])) {
		$postID = $_POST['postID'];
		try {
			$stmt = DB::web()->prepare('UPDATE forum_posts SET pinned=:pinned WHERE postId=:postId');
			$stmt->execute(array(
				':pinned' => '1',
				':postId' => $postID
			));
		} catch(PDOException $e) {
			echo '<span class="text-xs-center font-weight-bold text-danger fs-18">Podczas przypinania tematu wystąpił problem!</span>';
			error_log('ERROR postPin: ' . $e->getMessage());
		}
	} else if (isset($_POST['postUnpin'])) {
		$postID = $_POST['postID'];
		try {
			$stmt = DB::web()->prepare('UPDATE forum_posts SET pinned=:pinned WHERE postId=:postId');
			$stmt->execute(array(
				':pinned' => '0',
				':postId' => $postID
			));
		} catch(PDOException $e) {
			echo '<span class="text-xs-center font-weight-bold text-danger fs-18">Podczas odpinania tematu wystąpił problem!</span>';
			error_log('ERROR postUnpin: ' . $e->getMessage());
		}
	}
	if (isset($_POST['postClose'])) {
		$postID = $_POST['postID'];
		try {
			$stmt = DB::web()->prepare('UPDATE forum_posts SET closed=:closed WHERE postId=:postId');
			$stmt->execute(array(
				':closed' => '1',
				':postId' => $postID
			));
		} catch(PDOException $e) {
			echo '<span class="text-xs-center font-weight-bold text-danger fs-18">Podczas zamykania tematu wystąpił problem!</span>';
			error_log('ERROR postClose: ' . $e->getMessage());
		}
	} else if (isset($_POST['postOpen'])) {
		$postID = $_POST['postID'];
		try {
			$stmt = DB::web()->prepare('UPDATE forum_posts SET closed=:closed WHERE postId=:postId');
			$stmt->execute(array(
				':closed' => '0',
				':postId' => $postID
			));
		} catch(PDOException $e) {
			echo '<span class="text-xs-center font-weight-bold text-danger fs-18">Podczas otwierania tematu wystąpił problem!</span>';
			error_log('ERROR postOpen: ' . $e->getMessage());
		}
	}
	if (isset($_POST['postDelete'])) {
		$postID = $_POST['postID'];
		try {
			if (sizeof(getReplies(' WHERE postId='.$postID)) > 0) {
				$replies_stmt = DB::web()->prepare('DELETE FROM forum_replies where postId=:postId');
				$replies_stmt->execute(array(
					':postId' => $postID
				)); 
			}
			$stmt = DB::web()->prepare('DELETE FROM forum_posts where postId=:postId');
			$stmt->execute(array(
				':postId' => $postID
			));
		} catch(PDOException $e) {
			echo '<span class="text-xs-center font-weight-bold text-danger fs-18">Podczas usuwania tematu wystąpił problem!</span>';
			error_log('ERROR postDelete: ' . $e->getMessage());
		}
	}
}

function deleteComments() {
	if (isset($_POST['commentDelete'])) {
		$id = $_POST['delete'];
		try {
			$stmt = DB::web()->prepare('DELETE FROM forum_replies where id=:id');
			$stmt->execute(array(
				':id' => $id
			));
		} catch(PDOException $e) {
			echo '<span class="text-xs-center font-weight-bold text-danger fs-18">Podczas usuwania komentarza wystąpił problem!</span>';
			error_log('ERROR deleteComment: ' . $e->getMessage());
		}
	}
}

function showCategories($iconClass) {
	$categories = Forum_Categories::_getCategories();
	$forumpage = HOME_PAGE.'/forum';

	foreach ($categories as $category) {
		$subcategories = Forum_SubCategories::_getSubCategories($category->id);
		// Check if the category is locked
		echo $category->locked == 1 ? '<li class="nk-forum-locked">' : '<li>';
		// Set the icon class
		echo '<div class="nk-forum-icon">';
		echo $category->locked == 1 ? '<span class="ion-locked">' : '<span class="'.$iconClass.'"></span>';
		echo '</div>';
		// Set the category title and description
		echo '<div class="nk-forum-title"><h3><a href="'.$forumpage.'/'.surlencode(strtolower($category->title)).'">'.$category->title.'</a></h3>';
		echo '<div class="nk-forum-title-sub">'.$category->description.'</div>';
		if (sizeof($subcategories) > 0) {
			echo '<div class="forum-sub-category">';
			foreach ($subcategories as $subcat) {
				echo '<div class="forum-sub-category-title"><a href="'.$forumpage.'/sub/'.$subcat->id.'-'.surlencode(strtolower($subcat->title)).'">'.$subcat->title.'</a></div>';
			}
			echo '</div>';
		}
		echo '</div>';
		$newestPost = Forum_Posts::_getPosts($category->id, true, 'LIMIT 1');
		$amountPosts = getAmountOfPosts(' WHERE catId='.$category->id);
		$topic = 'tematów';
		if ($amountPosts == 1) {
			$topic = 'temat';
		} elseif ($amountPosts > 1 && $amountPosts < 5) {
			$topic = 'tematy';
		}
		$topicActivity = $amountPosts.' '.$topic;
		$amountReplies = getAmountOfReplies(' WHERE catId='.$category->id);
		$reply = 'odpowiedzi';
		if ($amountReplies == 1) {
			$reply = 'odpowiedź';
		}
		$replyActivity = $amountReplies.' '.$reply;
		echo '<div class="nk-forum-count">'.$topicActivity.'<br/>'.$replyActivity.'</div>';
		foreach ($newestPost as $post) {
			$newestReply = Forum_Replies::_getReplies($post->id, true, 'LIMIT 1');
			if (sizeof($newestReply) > 0) {
				foreach ($newestReply as $reply) {
					renderUserActivity($post, $reply);
				}
			} else {
				renderUserActivity($post, $post);
			}
			continue;
		}
		echo '</div>';
		echo '</li>';
	}
}

function renderUserActivity($post, $entity) {
	$player = getPlayer($entity->author);
	echo '<div class="nk-forum-activity-avatar"><a href="/character/'.preg_replace('/_/', '+', surlencode($player->name)).'.html"><img src="/images/outfit/'.surlencode($player->outfit).'.png" alt="'.$player->name.'"/></a></div>';
	echo '<div class="nk-forum-activity">';
	echo '	<div class="nk-forum-activity-title" title="'.$post->title.'"><a href="'.WEB_FOLDER.'/forum/topic/'.$post->id.'-'.surlencode(strtolower($post->title)).'">'.getPrefix($post).$post->title.'</a></div>';
	echo '	<div class="nk-forum-activity-title activity-author" title="'.$player->name.'">';
	echo '		przez <a '.renderNameColour($player, renderUserRank($player)).' href="/character/'.preg_replace('/_/', '+', surlencode($player->name)).'.html">'.$player->name.'</a>';
	echo '	</div>';
	$FDP = date_parse($entity->date);
	echo '	<div class="nk-forum-activity-date">'.renderDate($FDP, true).'</div>';
}

function addTopic($categoryId, $subCategoryId, $title, $description, $author) {
	try {
		$stmt = DB::web()->prepare("INSERT INTO forum_posts (topicId, catId, title, description, author) values "
			."(:topicId, :catId, :title, :description, :author)");
		$stmt->execute(array(
			':topicId' => $subCategoryId,
			':catId' => $categoryId,
			':title' => $title,
			':description' => $description,
			':author' => $author
		));
	} catch(PDOException $e) {
		echo '<span class="text-xs-center font-weight-bold text-danger fs-18">Wyskoczył nam jakiś nieznośny błąd! Spróbuj go usunąć tworząc ponownie temat!</span>';
		error_log('ERROR addTopic: ' . $e->getMessage());
	}
}

function addReply($postId, $catId, $subCatId, $description, $author) {
	try {
		$stmt = DB::web()->prepare("INSERT INTO forum_replies (postId, catId, subCatId, description, author) values "
			."(:postId, :catId, :subCatId, :description, :author)");
		$stmt->execute(array(
			':postId' => $postId,
			':catId' => $catId,
			':subCatId' => $subCatId,
			':description' => $description,
			':author' => $author
		));
	} catch(PDOException $e) {
		echo '<span class="text-xs-center font-weight-bold text-danger fs-18">Wyskoczył nam jakiś nieznośny błąd! Spróbuj go usunąć tworząc odpowiadając ponownie!</span>';
		error_log('ERROR addReply: ' . $e->getMessage());
	}
}

// function addGRP($gId, $author, $title, $description) {
// 	try {
// 		$stmt = DB::web()->prepare("insert into grp (author, title, description, grp_type_id) values "
// 			."(:author, :title, :description, :grp_type_id)");
// 		$stmt->execute(array(
// 			':author' => $author,
// 			':title' => $title,
// 			':description' => $description,
// 			':grp_type_id' => $gId
// 		));
// 	} catch(PDOException $e) {
// 		echo '<span class="text-xs-center font-weight-bold text-danger fs-18">Wystąpił jakiś błąd ze wstawianiem tego na tablice!</span>';
// 		error_log('ERROR addGRP: ' . $e->getMessage());
// 	}
// }

// function deleteGRP($id) {
// 	try {
// 		$stmt = DB::web()->prepare('delete from grp where id=:id');
// 		$stmt->execute(array(
// 			':id' => $id
// 		));
// 	} catch(PDOException $e) {
// 		echo '<span class="text-xs-center font-weight-bold text-danger fs-18">Wystąpił jakiś błąd z usuwaniem wybranego tematu!</span>';
// 		error_log('ERROR deleteGRP: ' . $e->getMessage());
// 	}
// }

// function updateGRP($id, $gId, $title, $description, $incUpdateCount = true) {
// 	$update = '';
// 	if ($incUpdateCount) {
// 		$update = 'updateCount=updateCount+1';
// 	}
// 	try {
// 		$query="UPDATE grp SET grp_type_id=:grp_type_id, title=:title, description=:description, modified=CURRENT_TIMESTAMP, ".$update." WHERE id=:id";
// 		$stmt = DB::web()->prepare($query);
// 		$stmt->execute(array(
//	 			':grp_type_id' => $gId,
// 				':title' => $title,
// 				':description' => $description,
// 				':id' => $id
// 		));
// 	} catch(PDOException $e) {
// 		echo '<span class="text-xs-center font-weight-bold text-danger fs-18">Wystąpił jakiś błąd podczas wprowadzania zmian tematu!</span>';
// 		error_log('ERROR updateGRP: ' . $e->getMessage());
// 	}
// }

// function getGRPCategories() {
// 	$sql = 'SELECT id, category, description FROM grp_type ORDER BY id';
// 	$stmt = DB::web()->query($sql);
// 	return $stmt->fetchAll(PDO::FETCH_ASSOC);
// }

// function countGRPForCategory($gId) {
//	 $sql = 'SELECT grp_type_id, count(*) AS countGRP FROM grp WHERE grp_type_id='.$gId;
//	 $stmt = DB::web()->query($sql);
//	 return $stmt->fetchAll(PDO::FETCH_ASSOC);
// }

class Forum_Categories extends Forum {
	public $locked;

	function __construct($id, $title, $description, $locked) {
		parent::__construct($id, $title, $description);
		$this->locked=$locked;
	}

	public static function _getCategories() {
		$sql = 'SELECT catId, title, description, locked FROM forum_categories;';
		$rows = DB::web()->query($sql);
		$list = array();
		foreach($rows as $row) {
			$list[] = new Forum_Categories(
					$row['catId'],
					$row['title'],
					$row['description'],
					$row['locked']
			);
		}
		return $list;
	}
}

class Forum_SubCategories extends Forum {
	function __construct($id, $title, $description) {
		parent::__construct($id, $title, $description);
	}

	public static function _getSubCategories($catId) {
		$sql = 'SELECT topicId, title, description FROM forum_topics WHERE catId = '.$catId.';';
		$rows = DB::web()->query($sql);
		$list = array();
		foreach($rows as $row) {
			$list[] = new Forum_SubCategories(
					$row['topicId'],
					$row['title'],
					$row['description']
			);
		}
		return $list;
	}
}

class Forum_Posts extends Forum {
	public $author;
	public $pinned;
	public $closed;
	/** Date in ISO format YYYY-MM-DD HH:mm */
	public $date;

	function __construct($id, $title, $description, $author, $pinned, $closed, $date) {
		parent::__construct($id, $title, $description);
		$this->author=$author;
		$this->pinned=$pinned;
		$this->closed=$closed;
		$this->date=$date;
	}

	public static function _getPosts($getId, $noSubCat = false, $limit = '') {
		$sql = 'SELECT postId, title, description, author, pinned, closed, created FROM forum_posts WHERE topicId = '.$getId.' ORDER BY created DESC '.$limit.';';
		if ($noSubCat) {
			$sql = 'SELECT postId, title, description, author, pinned, closed, created FROM forum_posts WHERE catId = '.$getId.' ORDER BY created DESC '.$limit.';';
		}
		$rows = DB::web()->query($sql);
		$list = array();
		foreach($rows as $row) {
			$list[] = new Forum_Posts(
					$row['postId'],
					$row['title'],
					$row['description'],
					$row['author'],
					$row['pinned'],
					$row['closed'],
					$row['created']
			);
		}
		return $list;
	}
}

class Forum_Replies {
	public $id;
	public $description;
	public $author;
	public $date;

	function __construct($id, $description, $author, $date) {
		$this->id=$id;
		$this->description=$description;
		$this->author=$author;
		$this->date=$date;
	}

	public static function _getReplies($postId, $order = false, $limit = '') {
		$sql = 'SELECT id, description, author, date FROM forum_replies WHERE postId = '.$postId.';';
		if ($order) {
			$sql = 'SELECT id, description, author, date FROM forum_replies WHERE postId = '.$postId.' ORDER BY date DESC '.$limit.';';
		}
		$rows = DB::web()->query($sql);
		$list = array();
		foreach($rows as $row) {
			$list[] = new Forum_Replies(
					$row['id'],
					$row['description'],
					$row['author'],
					$row['date']
			);
		}
		return $list;
	}
}