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
if ($user['user_level'] == -1){
    printHeader('');
	hiddenUser();
}
else {
	if(!((isset($_GET['offset']) && is_numeric($_GET['offset'])) && isset($_GET['dateTime']))){

		$tabTitle = 'Uiiverse - '. htmlspecialchars($user['nickname'], ENT_QUOTES) .'\'s Profile';

		printHeader('');

		echo '<script>var loadOnScroll=true;</script><div id="sidebar" class="user-sidebar">';

		userContent($user, "yeahs");

		userSidebarSetting($user, 2);

		userInfo($user);

		echo '</div>
		<div class="main-column">
           <div class="post-list-outline">
    <div class="diary_post" id="diary_post-post-list">
      <h2 class="label symbol label-diary_post">Recent Play Journal Entries</h2>
       <div class="empty post-list">
          <p>No Play Journal Entries posted yet.</p>
        </div>
          </div>
        </div>
        <a href="/users/'. $user['user_name'] .'/" class="big-button">
    View Play Journal
  </a>
            <div class="post-list-outline">
    <div class="artwork_post" id="artwork_post-post-list">
      <h2 class="label symbol label-artwork_post">Recent Drawings</h2>
        <div class="empty post-list">
          <p>No drawings posted yet.</p>
        </div>
    </div>
  </div>
  <div class="post-list-outline">
    <div class="topic_post test-topic-posts-section" id="topic_post-post-list">
      <h2 class="label symbol label-topic_post">Open Discussion</h2>
        <div class="empty post-list">
          <p>No open discussions.</p>
        </div>
    </div>
  </div>';
    }
}
}