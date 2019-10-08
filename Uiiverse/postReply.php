<?php
require_once('lib/htm.php');

if (empty($_SESSION['signed_in'])) {
	return;
}

$get_user = $dbc->prepare('SELECT user_face FROM users WHERE user_id = ?');
$get_user->bind_param('i', $_SESSION['user_id']);
$get_user->execute();
$user_result = $get_user->get_result();
$user = $user_result->fetch_assoc();
if ($_SERVER['REQUEST_METHOD'] != 'POST') {

	echo '<form id="post-form" method="post" action="/posts/'.$post['id'].'/replies" enctype="multipart/form-data">
	  <div class="post-count-container">
	  </div>';

	if (!strpos($user['user_face'], "imgur") && !strpos($user['user_face'], "cloudinary")) {
		echo '<div class="feeling-selector js-feeling-selector test-feeling-selector"><label class="symbol feeling-button feeling-button-normal checked"><input type="radio" name="feeling_id" value="0" checked=""><span class="symbol-label">normal</span></label><label class="symbol feeling-button feeling-button-happy"><input type="radio" name="feeling_id" value="1"><span class="symbol-label">happy</span></label><label class="symbol feeling-button feeling-button-like"><input type="radio" name="feeling_id" value="2"><span class="symbol-label">like</span></label><label class="symbol feeling-button feeling-button-surprised"><input type="radio" name="feeling_id" value="3"><span class="symbol-label">surprised</span></label><label class="symbol feeling-button feeling-button-frustrated"><input type="radio" name="feeling_id" value="4"><span class="symbol-label">frustrated</span></label><label class="symbol feeling-button feeling-button-puzzled"><input type="radio" name="feeling_id" value="5"><span class="symbol-label">puzzled</span></label></div>';
	}

	echo '<menu class="textarea-menu">
	<li><label class="textarea-menu-text"><input type="radio" name="post_type" value="0" checked></label></li>
	<li><label class="textarea-menu-memo"><input type="radio" name="post_type" value="1"></label></li>
	<!--<li><label class="textarea-menu-poll"><input type="radio" name="post_type" value="2"></label></li>-->
</menu><div class="textarea-container"><textarea name="text_data" class="textarea-text textarea" maxlength="800" placeholder="Add a comment here."></textarea></div>            <div class="textarea-memo none">
	<div id="memo-drawboard-page" class="none">
		<div class="window-body">
			<div class="memo-buttons">
				<button type="button" class="artwork-clear"></button>
				<button type="button" class="artwork-undo"></button>
				<button type="button" class="artwork-pencil small selected"></button>
				<button type="button" class="artwork-eraser small"></button>
				<button type="button" class="artwork-fill"></button>
				<input type="text" class="artwork-color">
				<button type="button" class="artwork-zoom"></button>
			</div>
			<div class="memo-canvas">
				<canvas id="artwork-canvas" zoom="2"></canvas>
				<canvas id="artwork-canvas-undo"></canvas>
				<canvas id="artwork-canvas-redo"></canvas>
				<input type="hidden" name="painting">
			</div>
		</div>
	</div>
</div><style>#imageThing { padding: 1.8%; color: #ccc !important; background-color: var(--theme-darker) !important; -webkit-border-radius: 5px; -moz-border-radius: 5px; -ms-border-radius: 5px; -o-border-radius: 5px; border-radius: 5px; border: 1px solid #969696; }</style><center><input type="url" id="imageThing" name="image" placeholder="Direct Image URL"></center><div class="form-buttons"><input type="submit" name="submit" class="black-button post-button disabled" value="Send" disabled=""></div></form>';
} else {
	$forbidden = array("faggot", "nigga", "nigger", "fucker", "fuck", "fucking", "fucka", "zipperhead", "sex", "cunt", "anal", "dick", "cock", "bitch", "kike", "towelhead", "gook", "spic", "kill yourself", "penis", "vagina", "pussy", "kys", "suicide", "sexy", "niggers");
	$errors = array();
	$image = NULL;
	function match($wrongwords, $string) {
	    foreach($wrongwords as $wrongword){
	        if (strpos($string, $wrongword) !== false) {
	            return true;
	        }
	    }
	    return false;
	}

$get_user_plus = $dbc->prepare('SELECT * FROM users WHERE user_id = ?');
$get_user_plus->bind_param('i', $_SESSION['user_id']);
$get_user_plus->execute();
$user_result_plus = $get_user_plus->get_result();
$user_plus = $user_result_plus->fetch_assoc();

	if (empty(trim($_POST['text_data']))) {
		$errors[] = 'Post text cannot be empty.';
	} elseif (mb_strlen($_POST['text_data']) > 800) { 
		$errors[] = 'Replies cannot be longer than 800 characters.';
	} elseif(match($forbidden, strtolower(trim($_POST['text_data'])))) {
		$errors[] = 'Your reply contains a word that is not allowed on Uiiverse.';
	} elseif($user_plus['user_level'] == -1) {
		$errors[] = 'You\'re banned from Uiiverse.';
	} elseif($user_plus['user_level'] == -2) {
		$errors[] = 'You\'re not verified.';
	} else {
		$text = $_POST['text_data'];
	}

	if(empty($_POST['feeling_id']) || strval($_POST['feeling_id']) >= 6) {
		$_POST['feeling_id'] = 0;
	} 

	if (empty($errors)) {
		$text = htmlspecialchars($text, ENT_QUOTES);
		$reply_id = mt_rand(0, 99999999);

		$post_reply = $dbc->prepare('INSERT INTO replies (reply_id, reply_post, reply_by_id, feeling_id, text, reply_image) VALUES (?, ?, ?, ?, ?, ?)');
		$post_reply->bind_param('iiiiss', $reply_id, $id, $_SESSION['user_id'], $_POST['feeling_id'], $text, $_POST['image']);
		$post_reply->execute();

		$search_post = $dbc->prepare('SELECT * FROM posts WHERE id = ?');
		$search_post->bind_param('i', $id);
		$search_post->execute();
		$post_result = $search_post->get_result();
		$post = $post_result->fetch_assoc();

		if ($_SESSION['user_id'] == $post['post_by_id']) {
			$notif_getcomments = $dbc->prepare('SELECT reply_by_id FROM replies WHERE reply_post = ? AND reply_by_id != ? AND deleted = 0 GROUP BY reply_by_id');
			$notif_getcomments->bind_param('ii', $id, $_SESSION['user_id']);
			$notif_getcomments->execute();
			$result_notif_getcomments = $notif_getcomments->get_result();

			while ($notif_comments = mysqli_fetch_assoc($result_notif_getcomments)) {
				notify($notif_comments['reply_by_id'], 3, $id);
			}
		} else {
			notify($post['post_by_id'], 2, $id);
		}

		$search_reply = $dbc->prepare('SELECT * FROM replies INNER JOIN users ON user_id = reply_by_id WHERE reply_id = ?');
		$search_reply->bind_param('i', $reply_id);
		$search_reply->execute();
		$reply_result = $search_reply->get_result();
		$reply = $reply_result->fetch_assoc();
		
		echo '<li class="post'. ($reply['reply_by_id'] == $post['post_by_id'] ? ' my' : '') .' trigger" data-href="/replies/'.$reply['reply_id'].'" style="display: none;">';
		printReply($reply);

	} else {
		echo '<script type="text/javascript">alert("'. $errors[0] .'");</script>';
	}
}
