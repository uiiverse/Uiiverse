<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
require_once('lib/htm.php');
require_once('lib/htmUsers.php');
if(empty($_SESSION['signed_in'])){
	$tabTitle = 'Uiiverse';
	printHeader('');
	echo '<div class="warning-content warning-content-forward"><div><strong>Welcome to Uiiverse!</strong><p>You must sign in to view this page.</p>
    <a class="button" href="/">Uiiverse</a></div></div>';
} else {
	session_start();
	if($_SERVER['REQUEST_METHOD'] != 'POST'){
		$get_user = $dbc->prepare('SELECT * FROM users INNER JOIN profiles ON profiles.user_id = users.user_id WHERE users.user_id = ? LIMIT 1');
		$get_user->bind_param('i', $_SESSION['user_id']);
		$get_user->execute();;
		$user_result = $get_user->get_result();
		$user = $user_result->fetch_assoc();
		$tabTitle = 'Uiiverse - Change your Email';
		printHeader('');
		echo '<div class="main-column"><div class="post-list-outline"><h2 class="label">Change your Email</h2><ul class="settings-list"><p>This page will allow you to change your Uiiverse email<form action="/change-email" method="POST"><p>Enter your new email.</p><input type="text" name="email"><input type="submit" class="black-button" value="Submit"></form></ul>';
	} else {
            if (!filter_var($_POST['email'], FITLER_VALIDATE_EMAIL)) {
                echo('Email is not valid.<META HTTP-EQUIV="refresh" content="0;URL=/">');
            } else {
                $activation_code = md5($email.time());
	    		$user_change = $dbc->prepare('UPDATE users SET user_level=-2 email=? activation_code=? WHERE users.user_id = ?');
	    		$user_change->bind_param('ss', $_POST['email'], $activation_code);
	    		$user_change->execute();
	    		exit('Your email has been successfully changed. Redirecting to Uiiverse.<META HTTP-EQUIV="refresh" content="0;URL=/">');
			} else {
				echo '<script type="text/javascript">alert("The code you entered in was invalid. Please try again.");</script><META HTTP-EQUIV="refresh" content="0;URL=/login">';
			}
		}
}?>