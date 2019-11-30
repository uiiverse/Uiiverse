<?php 
require_once('lib/2fa.php');
require_once('lib/htm.php');
require_once('lib/htmUsers.php');
$user_change = $dbc->prepare('UPDATE users SET 2fa_secret = "", 2fa_enabled = 0 WHERE user_id = ?');
$user_change->bind_param('i', $_SESSION['user_id']);
$user_change->execute();
exit('Two Factor Authentication has been disabled successfully.');
?>