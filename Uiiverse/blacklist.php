<?php
require_once('lib/htm.php');
require_once('lib/htmUsers.php');
$get_user = $dbc->prepare('SELECT * FROM users INNER JOIN profiles ON profiles.user_id = users.user_id WHERE users.user_id = ? LIMIT 1');
	$get_user->bind_param('i', $_SESSION['user_id']);
	$get_user->execute();
	$user_result = $get_user->get_result();
	$user = $user_result->fetch_assoc();
	$tabTitle = 'Ziiverse - Blocked Users';
    printHeader('');
echo '<div id="sidebar" class="user-sidebar">';
userContent($user, "Favorites");
sidebarSetting();
echo '</div><div class="main-column"><div class="post-list-outline">
<h2 class="label">Blocked Users</h2><ul class="list community-list">';
$get_blacklist = $dbc->prepare('SELECT * FROM blacklist INNER JOIN users ON blacklist.source = users.user_id WHERE blacklist.source = ? LIMIT 1');
	$get_blacklist->bind_param('i', $_SESSION['user_id']);
	$get_blacklist->execute();
	$blacklist_result = $get_blacklist->get_result();
	$blacklist = $blacklist_result->fetch_assoc();
    if ($blacklist_result->num_rows == 0){
	echo '<div class="no-content"><div><p>No blocked users.</p></div></div>';
    }
    else {

	echo '<ul class="list community-list">';

	while ($get_blacklist = $blacklist_result->fetch_assoc()){
		printBlacklist($fav_titles);
	}
}
