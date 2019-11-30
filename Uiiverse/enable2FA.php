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
	$get_user = $dbc->prepare('SELECT * FROM users INNER JOIN profiles ON profiles.user_id = users.user_id WHERE users.user_id = ? LIMIT 1');
	$get_user->bind_param('i', $_SESSION['user_id']);
	$get_user->execute();
	$user_result = $get_user->get_result();
	$user = $user_result->fetch_assoc();
	$secret = $tfa->createSecret();
	$tabTitle = 'Uiiverse - Enable Two Factor Authentication';
	printHeader('');
	echo '<div class="main-column"><div class="post-list-outline"><h2 class="label">Enable Two Factor Authentcation</h2><p>2FA helps you add a second layer of security to your Uiiverse account. You\'ll have to download the Google Authenticator or Authy on your phone</p>';

