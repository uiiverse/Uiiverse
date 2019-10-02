<?php
require_once('lib/htm.php');
require_once('lib/htmUsers.php');

$tabTitle = 'Uiiverse - Community List (3DS Communities)';

printHeader(3);

echo '<div id="sidebar" class="general-sidebar">';

if(!empty($_SESSION['signed_in'])){
	$get_user = $dbc->prepare('SELECT * FROM users WHERE user_id = ? LIMIT 1');
	$get_user->bind_param('i', $_SESSION['user_id']);
	$get_user->execute();
	$user_result = $get_user->get_result();
	$user = $user_result->fetch_assoc();
	userContent($user, "");
}

sidebarSetting();
echo '</div>'; 

echo '
<div class="main-column">
  <div class="post-list-outline">
    <div class="body-content" id="community-top" data-region="USA">
      <h2 class="label">Communities<img class="platform-logo" src="http://web.archive.org/web/20160307233619im_/https://d13ph7xrk1ee39.cloudfront.net/img/3ds-logo.png?rAKZLSs8ENHaZWaZpKKv6w" width="94" hight="17"></h2>
      <h3 class="label label-3ds">All Software</h3>
      <ul class="list community-list">';

$get_titles = $dbc->prepare('SELECT * FROM titles WHERE type = 2');
$get_titles->execute();
$titles_result = $get_titles->get_result();

while ($titles = $titles_result->fetch_assoc()){
	printTitleInfo($titles);
}
