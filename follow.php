<?php
require_once('lib/htm.php');
$get_user_plus = $dbc->prepare('SELECT * FROM users WHERE user_id = ?');
$get_user_plus->bind_param('i', $_SESSION['user_id']);
$get_user_plus->execute();
$user_result_plus = $get_user_plus->get_result();
$user_plus = $user_result_plus->fetch_assoc();

if (!empty($_SESSION['signed_in'])) {
	if ($user_plus['user_level'] >= 0) {
		if ($_SESSION['user_id'] != $_POST['userId']){
			if(isset($_POST['userId']) && isset($_POST['followType'])) {
				if($_POST['followType'] == 'follow'){
					$yeah = $dbc->prepare('INSERT INTO follows (follow_by, follow_to) VALUES (?, ?)');
					$yeah->bind_param('ii', $_SESSION['user_id'], $_POST['userId']);
					$yeah->execute();

					notify($_POST['userId'], 4, NULL);

					echo 'success';

				} else {

					$yeah = $dbc->prepare('DELETE FROM follows WHERE follow_by = ? AND follow_to = ?');
					$yeah->bind_param('ii', $_SESSION['user_id'], $_POST['userId']);
					$yeah->execute();
					echo 'success';
				}
			}
		}
	} else {
		echo 'fail';
	}
}