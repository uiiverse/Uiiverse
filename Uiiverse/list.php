<?php
require_once('lib/htm.php');
require_once('lib/connect.php');
include ('lib/htmUsers.php');
$get_user = $dbc->prepare('SELECT * FROM users WHERE user_id = ? LIMIT 1');
	$get_user->bind_param('i', $_SESSION['user_id']);
	$get_user->execute();
	$user_result = $get_user->get_result();
	$user = $user_result->fetch_assoc();
    if ($user['user_level'] == -1){
    banScreen();
    } else {
$get_title = $dbc->prepare('SELECT * FROM titles WHERE title_id = ?');
$get_title->bind_param('i', $title_id);
$get_title->execute();
$title_result = $get_title->get_result();

if ($title_result->num_rows == 0) {
    printHeader(3);
    exit('<title>Uiiverse - Error</title><div class="no-content track-error" data-track-error="404"><div><p>The community could not be found.</p></div></div>');
}
if (isset($_COOKIE['retro-mode'])) {
    echo '<link rel="stylesheet" type="text/css" href="/assets/css/retro/titles.css "> <link rel="stylesheet" type="text/css" href="/assets/css/retro/root.css">';
    }
    
$title = $title_result->fetch_array();

if ((isset($_GET['offset']) && is_numeric($_GET['offset'])) && isset($_GET['dateTime'])) {
    //change this back to 50 when we have better servers
    $offset = ($_GET['offset'] * 25);
    $dateTime = htmlspecialchars($_GET['dateTime']);

    $get_posts = $dbc->prepare('SELECT * FROM posts INNER JOIN users ON user_id = post_by_id WHERE post_title = ? AND date_time < ? AND deleted = 0 ORDER BY date_time DESC LIMIT 25 OFFSET ?');
    $get_posts->bind_param('isi', $title_id, $dateTime, $offset);
} else {
    $tabTitle = 'Uiiverse - '. $title['title_name'];
    printHeader(3);

    echo '<script>var loadOnScroll=true;</script>
	<div id="sidebar">
  <section class="sidebar-container" id="sidebar-community">
      <span id="sidebar-cover">
	      <a href="/titles/'. $title['title_id'] .'">
	        <img src="'. $title['title_banner'] .'">
	      </a>
	    </span>
	    <header id="sidebar-community-body">
	    <span id="sidebar-community-img">
	      <span class="icon-container">
	    	<a href="/titles/'. $title['title_id'] .'">
	    	  <img src="'. $title['title_icon'] .'" class="icon">
	    	</a>
	      </span>
        <span class="platform-tag">';

    switch ($title['type']) {
        case 1:
            echo '<img src="/assets/img/platform-tag-wiiu.png">';
            break;
        case 2:
            echo '<img src="/assets/img/platform-tag-3ds.png">';
            break;
        case 3:
            echo '<img src="/assets/img/platform-tag-wiiu-3ds.png">';
            break;
        case 4:
            echo '<img src="/assets/img/platform-tag-switch.png">';
            break;
    }

    echo '</span>
    </span>
    '. ($title['type'] == 5 ? '<span class="news-community-badge">Announcement Community</span>' : '') .'
    '. ($title['type'] == 1 ? '<span class="news-community-badge">Main Community</span>' : '') .'
        '. ($title['type'] == 2 ? '<span class="news-community-badge">Main Community</span>' : '') .'
            '. ($title['type'] == 3 ? '<span class="news-community-badge">Main Community</span>' : '') .'
		'. ($title['type'] == 4 ? '<span class="news-community-badge">Main Community</span>' : '') . ($title['perm'] == 2 ? '<span class="news-community-badge">Private
</span>' : '') .'
    <h1 class="community-name"><a href="/titles/'. $title['title_id'] .'">'. htmlspecialchars($title['title_name'], ENT_QUOTES) .'</a></h1>
    </header>
      <div class="community-description js-community-description">
		<p class="text js-truncated-text">'. nl2br(htmlspecialchars($title['title_desc'], ENT_QUOTES)) .'</p>';
    if (!empty($title['title_by'])) {
        $get_title_owner = $dbc->prepare('SELECT * FROM users WHERE user_id = ?');
        $get_title_owner->bind_param('i', $title['title_by']);
        $get_title_owner->execute();
        $title_owner_result = $get_title_owner->get_result();
        $title_owner = $title_owner_result->fetch_array();
        echo '<p style="text-align:  center;">Community owner: <a href="/users/'. htmlspecialchars($title_owner['user_name'], ENT_QUOTES) .'/posts">'. $title_owner['user_name'] .'</a></p>';
    }
      echo '</div><div class="sidebar-setting">
      <div class="sidebar-post-menu">
      <a href="/titles/'. $title['title_id'] .'" class="sidebar-menu-in_game symbol selected">
            <span>In-Game Posts</span>
          </a>
        
          <a href="/titles/'. $title['title_id'] .'/diary" class="sidebar-menu-diary symbol">
            <span>Play Journal Entries</span>
          </a>
        
          <a href="/titles/'. $title['title_id'] .'/artwork" class="sidebar-menu-artwork symbol">
            <span>Drawings</span>
          </a>
        
          <a href="/titles/'. $title['title_id'] .'/topic" class="sidebar-menu-topic symbol">
            <span>Discussions</span>
          </a>
        
      </div>
    </div>
  ';
if (!empty($_SESSION['signed_in'])) {
        if ($_SESSION['user_id'] == $title['perm']) {
            echo '<div id="edit-title"><a class="button symbol" href="/titles/'. $title['title_id'] .'/edit">Community Settings</a></div>';
        }
        echo '<li><button type="button" class="symbol button favorite-button';

        $check_favorite = $dbc->prepare('SELECT * FROM favorite_titles WHERE user_id = ? AND title_id = ?');
        $check_favorite->bind_param('ii', $_SESSION['user_id'], $title['title_id']);
        $check_favorite->execute();
        $favorite_result = $check_favorite->get_result();

        if (!$favorite_result->num_rows == 0) {
            echo ' checked ';
        }

        echo '"data-title-id="'. $title['title_id'] .'"><span class="favorite-button-text">Favorite</span></button></li>';
    }

    echo '</section><div class="sidebar-setting"><div class="sidebar-post-menu"></div></div></section>
    <div class="sidebar-setting sidebar-container">
      <ul class="sidebar-other-menu test-related-communities">
        <li><a class="sidebar-menu-relation symbol test-related-communities-link"><span>Related Communities</span></a></li>
        <li class="community-list">
        </ul></li>
      </ul>
    </div>
    </div>
        <div class="main-column"><div class="post-list-outline">';

    if ($title['perm'] < 1) {
        echo '<h2 class="symbol label label-via_api">
        In-Game Posts
    </h2>
		';
    }


    include 'postText.php';
    echo '<div class="body-content" id="community-post-list"><div class="list post-list" data-next-page-url="/titles/'. $title['title_id'] .'?offset=1&dateTime='. date("Y-m-d H:i:s") .'">';

    $get_posts = $dbc->prepare('SELECT * FROM posts INNER JOIN users ON user_id = post_by_id INNER JOIN profiles ON users.user_id = profiles.user_id WHERE post_title = ? AND deleted = 0 ORDER BY date_time DESC LIMIT 25');
    $get_posts->bind_param('i', $title_id);
}
$get_posts->execute();
$posts_result = $get_posts->get_result();

if (!$posts_result->num_rows == 0) {
    while ($row = $posts_result->fetch_array()) {
        echo '<div class="post trigger" data-href="/posts/'. $row['id'] .'">';
        printPost($row, 1);
    }
    echo '';
    if (isset($_GET['offset']) && is_numeric($_GET['offset']) && isset($_GET['dateTime'])) {
        echo '<script>reloadYeahTooltip();</script>';
    }
} else {
    if (!(isset($_GET['offset']) && is_numeric($_GET['offset']) && isset($_GET['dateTime']))) {
        echo '<script>var aTbottom=true;</script><div class="no-content"><div><p>This community doesn\'t have any posts yet.</p></div></div>';
    }
}
    }
?>
<div id="footer"><div id="footer-inner"><div class="link-container"><p><a href="https://api.uiiverse.xyz/">API</a></p><!-- API button --><p><a href="https://downloads.uiiverse.xyz/">Downloads</a></p><!-- Downloads --><p><a href="https://status.uiiverse.xyz/">Status</a></p><!-- Status button --><p><a href="/android">Android</a></p><!-- Android button --><p><a href="/wii/">Wii</a></p><!-- Wii button --><p><a href="/desktop/">Desktop</a></p><!-- Desktop button --><p id="copyright"><a href="https://nintendo.com/">Uiiverse is a non-profit revival of Nintendo and Hatena's Miiverse service. We are not affiliated with these companies and they deserve your business.</a><!-- Legal text --></p></div></div></div>
</div>