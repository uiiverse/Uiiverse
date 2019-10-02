<?php
require_once('lib/htm.php');
require_once('lib/htmUsers.php');

$get_user = $dbc->prepare('SELECT * FROM users INNER JOIN profiles ON profiles.user_id = users.user_id WHERE user_name = ? LIMIT 1');
$get_user->bind_param('s', $action);
$get_user->execute();
$user_result = $get_user->get_result();

if ($user_result->num_rows == 0){
    printHeader('');
    noUser();
} else {

	$user = $user_result->fetch_assoc();

	if(!(isset($_GET['offset']) && is_numeric($_GET['offset']))){

		$tabTitle = 'Uiiverse - '. $user['nickname'] .'\'s profile';

		printHeader('');

		echo '<div id="sidebar" class="user-sidebar">';

		userContent($user, "friends");

		userSidebarSetting($user, 0);

		userInfo($user);

		echo '</div>
<div class="main-column"><div class="post-list-outline">
<h2 class="label">'. $user['nickname'] .'\'s Friends</h2><div class="list follow-list friends">
  <div id="user-page-no-content" class="no-content"><div>
      <p>No friends to display.</p>
  </div></div>
</div>
</div></div>';
    }
}