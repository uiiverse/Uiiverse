<?php 
require_once('lib/2fa.php');
$user_change = $dbc->prepare('UPDATE users SET 2fa_secret = "" AND 2fa_enabled = 0 WHERE users.user_id = ?');
$user_change->bind_param('ss', $_SESSION['secret'], $_SESSION['user_id']);
$user_change->execute();
exit('Two Factor Authentication has been disabled successfully. Redirecting to Uiiverse. <META HTTP-EQUIV="refresh" content="0;URL=/">');
?>