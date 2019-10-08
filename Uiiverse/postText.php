<?php
require_once('lib/htm.php');

if (empty($_SESSION['signed_in'])) {
	return;
}

$get_user = $dbc->prepare('SELECT * FROM users WHERE user_id = ?');
$get_user->bind_param('i', $_SESSION['user_id']);
$get_user->execute();
$user_result = $get_user->get_result();
$user = $user_result->fetch_assoc();

if (isset($_POST['title_id'])){
	$get_title = $dbc->prepare('SELECT * FROM titles WHERE title_id = ?');
	$get_title->bind_param('i', $_POST['title_id']);
	$get_title->execute();
	$title_result = $get_title->get_result();
	if ($title_result->num_rows == 0) {
		exit("ok");
	}
	$title = $title_result->fetch_array();
}

if (!(($title['perm'] == 1 && $user['user_level'] > 1) || $title['perm'] == NULL)) {
	return;
}

if (($title['perm'] == 1) && ($title['title_by'] !== $_SESSION['user_id'])) {
	return;
}

if ($_SERVER['REQUEST_METHOD'] != 'POST'){
	
	echo '<form id="post-form" method="post" action="/postText.php" enctype="multipart/form-data">
		<div class="post-count-container">
			<input type="hidden" name="title_id" value="'. $title['title_id'] .'">
		</div>';

	if (!strpos($user['user_face'], "imgur") && !strpos($user['user_face'], "cloudinary")) { 
		
		?>
		<div class="feeling-selector js-feeling-selector test-feeling-selector">
			<label class="symbol feeling-button feeling-button-normal checked">
				<input type="radio" name="feeling_id" value="0" checked="">
				<span class="symbol-label">normal</span>
			</label>
			<label class="symbol feeling-button feeling-button-happy">
				<input type="radio" name="feeling_id" value="1">
				<span class="symbol-label">happy</span>
			</label>
			<label class="symbol feeling-button feeling-button-like">
				<input type="radio" name="feeling_id" value="2">
				<span class="symbol-label">like</span>
			</label>
			<label class="symbol feeling-button feeling-button-surprised">
				<input type="radio" name="feeling_id" value="3">
				<span class="symbol-label">surprised</span>
			</label>
			<label class="symbol feeling-button feeling-button-frustrated">
				<input type="radio" name="feeling_id" value="4">
				<span class="symbol-label">frustrated</span>
			</label>
			<label class="symbol feeling-button feeling-button-puzzled">
				<input type="radio" name="feeling_id" value="5">
				<span class="symbol-label">puzzled</span>
			</label>
		</div>
		<?php

	}
	
	?>
		<menu class="textarea-menu">
                <li><label class="textarea-menu-text"><input type="radio" name="post_type" value="0" checked></label></li>
                <li><label class="textarea-menu-memo"><input type="radio" name="post_type" value="1"></label></li>
                <!--<li><label class="textarea-menu-poll"><input type="radio" name="post_type" value="2"></label></li>-->
        </menu>
		<div class="textarea-container">
			<textarea name="text_data" class="textarea-text textarea" maxlength="800" placeholder="Share your thoughts in a post to this community."></textarea>
		</div>
		<div class="textarea-memo none">
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
            </div>
<div class="post-form-footer-options">
<div class="post-form-footer-option-inner post-form-spoiler js-post-form-spoiler test-post-form-spoiler">
<label class="spoiler-button symbol ">
<input type="checkbox" id="is_spoiler" name="is_spoiler" value="1">
Spoilers
</label>
</div>
</div>
<style>#imageThing {
	padding: 1.8%;
	color: #ccc !important;
	background-color: var(--theme-darker) !important;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	-ms-border-radius: 5px;
	-o-border-radius: 5px;
	border-radius: 5px;
	border: 1px solid #969696;
}</style>
<center><input type="url" id="imageThing" name="image" placeholder="Direct Image URL"></center>
		<div class="form-buttons">
			<input type="submit" name="submit" class="black-button post-button disabled" value="Send" disabled="">
		</div>
	</form>
	<?php
/* WARNING: Strong language below */
} else {
	$forbidden = array("faggot", "nigga", "nigger", "fucker", "fuck", "fucking", "fucka", "zipperhead", "sex", "cunt", "anal", "dick", "cock", "bitch", "kike", "towelhead", "gook", "spic", "kill yourself", "penis", "vagina", "pussy", "kys", "suicide", "sexy", "niggas");
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

	if (empty(trim($_POST['text_data']))) {
		$errors[] = 'Posts cannot be empty.';
	} elseif (mb_strlen($_POST['text_data']) > 800) { 
		$errors[] = 'Posts cannot be longer than 800 characters.';
	} elseif (match($forbidden, strtolower(trim($_POST['text_data'])))) {
		$errors[] = 'Your post contains a word that is not allowed on Uiiverse.';
	} elseif($user['user_level'] == -1) {
		$errors[] = 'You\'re banned from Uiiverse.';
	} elseif($user['user_level'] == -2) {
		$errors[] = 'You\'re not a verified user.';
	}

	if (empty($_POST['feeling_id']) || strval($_POST['feeling_id']) >= 6) {
		$_POST['feeling_id'] = 0;
	} 

	
	if(empty($errors)){
		$id = mt_rand(0, 99999999);
		$post_text = $dbc->prepare('INSERT INTO posts (id, post_by_id, post_title, feeling_id, text, post_image) VALUES (?, ?, ?, ?, ?, ?)');
		$post_text->bind_param('iiiiss', $id, $_SESSION['user_id'], $title['title_id'], $_POST['feeling_id'], $_POST['text_data'], $_POST['image']);
		$post_text->execute();

		$get_posts = $dbc->prepare('SELECT * FROM posts INNER JOIN users ON user_id = post_by_id WHERE id = ?');
		$get_posts->bind_param('i', $id);
		$get_posts->execute();
		$posts_result = $get_posts->get_result();
		$post = $posts_result->fetch_array();

		echo '<div class="post trigger" data-href="/posts/'. $post['id'] .'" style="display: none;">';
		printPost($post, 0);
	} else {
		http_response_code(201);
		echo '<script type="text/javascript">popup("Error", "'. $errors[0] .'");</script>';
	}
}
