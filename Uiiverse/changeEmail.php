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
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                echo('Email is not valid.<META HTTP-EQUIV="refresh" content="0;URL=/">');
            } else {
				$activation_code = md5($email.time());
				$name = $user['nickname'];
	    		$user_change = $dbc->prepare('UPDATE users SET user_level=-2, email=?, activation_code=? WHERE users.user_id = ?');
	    		$user_change->bind_param('sss', $_POST['email'], $activation_code, $_SESSION['user_id']);
				$user_change->execute();
				$to = $email;
				$subject = "Activate your Uiiverse account, ". $name ."!";
				$header = "From: no-reply@uiiverse.xyz \r\n";
				$header .= "MIME-Version: 1.0\r\n";
        		$header .= "Content-type: text/html\r\n";
				$body = "<img src='https://i.ibb.co/dMPvqk9/logo.png' alt='Uiiverse'>
				Hey ". $name ."!
				You have succesfully changed your email. Before you can use your account with your new email though, you need to activate it.
				To do so, just <a href='https://uiiverse.xyz/activate/". $activation_code ."'>click this link</a> or go to the next URL: https://uiiverse.xyz/activate/". $activation_code ."
				
				Have a great day!

				The Uiiverse Team
				https://uiiverse.xyz/
				contact@uiiverse.xyz
					
				<small>All emails sent by this address are automatically generated. Don't reply to any of these emails or email this address, since none of them are going to be replied to.</small>";
				mail($to,$subject,$body,$header);
	    		exit('Your email has been successfully changed. Redirecting to Uiiverse.<META HTTP-EQUIV="refresh" content="0;URL=/">');
			} 
		}
}?>