<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
require_once('lib/2fa.php')
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
		$get_user->execute();
		$user_result = $get_user->get_result();
		$user = $user_result->fetch_assoc();
		$secret = $2fa->createSecret();
		$_SESSION['secret'] = $secret;
		$tabTitle = 'Uiiverse - Enable Two Factor Authentication';
		printHeader('');
		echo '<div class="main-column"><div class="post-list-outline"><h2 class="label">Enable Two Factor Authentcation</h2><p>2FA helps you add a second layer of security to your Uiiverse account. You\'ll have to download the Google Authenticator or Authy on your phone. Scan the next QR code:</p><img src='. echo $2fa->getQRCodeImageAsDataUri($user['user_name'], $secret); .'><p>or use the following secret code: '. echo $secret; .'</p><form action="enable2FA.php" method="post"><p>Enter the generated code.</p><input type="text" name="2faCode"><input type="submit" class="black-button" value="Submit"></form>';
	} else {
		if (isset($_POST['submit'])) {
			$result = $2fa-<verifyCode($_SESSION['secret'], $_POST['2faCode']);
			if ($result == true) {
	    		$user_change = $dbc->prepare('UPDATE users SET 2fa_secret = ? AND 2fa_enabled = 1 WHERE users.user_id = ?');
	    		$user_change->bind_param('ss', $_SESSION['secret'], $_SESSION['user_id']);
	    		$user_change->execute();
	    		exit('Two Factor Authentication has been enabled successfully. Redirecting to Uiiverse. <META HTTP-EQUIV="refresh" content="0;URL=/">');
			} else {
				echo '<script type="text/javascript">alert("The code you entered in was invalid. Please try again.");</script><META HTTP-EQUIV="refresh" content="0;URL=/login">';
			}
		}
	}
}?>

