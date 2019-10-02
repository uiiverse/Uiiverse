<?php
require_once('lib/htm.php');

if (!empty($_SESSION['signed_in'])) {
	if ($_SESSION['user_id'] != $_POST['userId']){
		if(isset($_POST['userId']) && isset($_POST['blockType'])) {

			if($_POST['blockType'] == 'block'){
				$yeah = $dbc->prepare('INSERT INTO blacklist (source, target) VALUES (?, ?)');
				$yeah->bind_param('ii', $_SESSION['user_id'], $_POST['userId']);
				$yeah->execute();

				notify($_POST['userId'], 4, NULL);

				echo 'success';

			} else {

				$yeah = $dbc->prepare('DELETE FROM blacklist WHERE source = ? AND target = ?');
				$yeah->bind_param('ii', $_SESSION['user_id'], $_POST['userId']);
				$yeah->execute();
				echo 'success';
			}
		}
	}
}