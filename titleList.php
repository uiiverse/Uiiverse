<?php
require_once('lib/htm.php');
include('lib/htmUsers.php');
require_once('lib/connect.php');
$tabTitle = 'Uiiverse - Communities';
if (!isset($_SESSION['signed_in'])) {
$bodyClass = 'guest-top guest';
}
$get_user = $dbc->prepare('SELECT * FROM users WHERE user_id = ? LIMIT 1');
	$get_user->bind_param('i', $_SESSION['user_id']);
	$get_user->execute();
	$user_result = $get_user->get_result();
	$user = $user_result->fetch_assoc();
    if ($user['user_level'] == -1){
    banScreen();
	} elseif ($user['user_level'] == -2) {
	verificationScreen();
    } else {
    printHeader(3);
?>
<?
 if (!isset($_SESSION['signed_in'])) {
  echo '<div id="about">
  <div id="about-inner">
    <div id="about-text">
      <h2 class="welcome-message">Welcome to Uiiverse!</h2>
      <p>Uiiverse is a re-creation of Nintendo\'s well-known social network, Miiverse. It allows you to interact with fellow Nintendo fans, give Yeahs to posts, and share screenshots of your favorite games.</p>
      <div class="guest-terms-content">
        <a class="symbol guest-terms-link test-guest-terms " href="/guide/terms">Use of Uiiverse</a>
      </div>
    </div>
    <img src="/assets/img/miiglobe.png">
  </div>
</div>';
 }
   ?>
<div class="body-content" id="community-top" data-region="USA">
  <? if (!isset($_SESSION['signed_in'])) {
  echo'
  <div style="padding:15px 35px 15px 15px;margin-bottom:20px;border:1px solid #bcf1C2;border-radius:4px;color:#348f31;background-color:#ddf5d1">
Uiiverse is a closed-source revival of Nintendo and Hatena\'s <a href="https://miiverse.nintendo.net/en/">Miiverse</a>.
<br>It is non-profit and its only purpose is to bring back the original concept of the website. This website is based off of <a href="http://olv-pearl.gq">Pearl</a> and <a href="https://github.com/EnergeticBark/Cedar-PHP">Cedar</a>. Please support the original developers.  <a href="#" style="float:right;font-size:21px;font-weight:700;line-height:1;text-shadow:0 1px 0 #fff;opacity:.2;filter:alpha(opacity=20);text-decoration:none;position:relative;top:-2px;right:-21px;color:inherit" onclick="event.preventDefault();$(this).parent().remove();document.cookie=\'cme=o\'">×</a>';
}
  ?>
</div>

  <div class="community-top-sidebar">
    <form method="GET" action="/titles/search" class="search">
      <input type="text" name="query" placeholder="Search Communities" minlength="2" maxlength="20"><input type="submit" value="q" title="Search">
    </form>

    <div id="identified-user-banner">
      <a href="/identified_user_posts" data-pjax="#body" class="list-button us">
        <span class="title">Get the latest news here!</span>
        <span class="text">Posts from Verified Users</span>
      </a>
    </div>    
    <br>
   <div class="post-list-outline" style="text-align: center">
			<h2 class="label">What is Uiiverse?</h2>
			<p style="width: 90%; display: inine-block; padding: 10px;">Uiiverse is a social media website that allows you to communicate with other fans of Nintendo from around the world.</p>
		</div>
	<? if (isset($_COOKIE['dark-mode'])) {
    echo '<iframe src="https://discordapp.com/widget?id=450074414600683540" width="320" height="500" allowtransparency="true" frameborder="0"></iframe> <!-- Discord server widget -->';
  } elseif (isset($_COOKIE['amoled-mode'])) {
    echo '<iframe src="https://discordapp.com/widget?id=450074414600683540" width="320" height="500" allowtransparency="true" frameborder="0"></iframe> <!-- Discord server widget -->';
  } elseif (isset($_COOKIE['translucent-mode'])) {
    echo '<iframe src="https://discordapp.com/widget?id=450074414600683540" width="320" height="500" allowtransparency="true" frameborder="0"></iframe> <!-- Discord server widget -->';
  } elseif (isset($_COOKIE['blur-mode'])) {
    echo '<iframe src="https://discordapp.com/widget?id=450074414600683540" width="320" height="500" allowtransparency="true" frameborder="0"></iframe> <!-- Discord server widget -->';
  } else {
    echo '<iframe src="https://discordapp.com/widget?id=450074414600683540&amp;theme=light" width="320" height="500" allowtransparency="true" frameborder="0"></iframe> <!-- Discord server widget -->';
  } ?>
	<nav>
<a href="https://paypal.me/uiiverse"><img src="https://image.ibb.co/hE4cCe/donate.png" alt="PayPal - The safer, easier way to pay online!"></a>
<img src="https://forthebadge.com/images/badges/ages-12.svg"> <!-- Ages 12+ button -->
	</nav>
    </div>
  <div class="community-main">

<?php

if (!empty($_SESSION['signed_in'])) {
    echo '<h3 class="community-title symbol community-favorite-title">Favorite Communities</h3>';

    $get_fav_titles = $dbc->prepare('SELECT titles.title_id, titles.title_icon FROM titles, favorite_titles WHERE titles.title_id = favorite_titles.title_id AND favorite_titles.user_id = ? ORDER BY favorite_titles.fav_id DESC LIMIT 8');
    $get_fav_titles->bind_param('i', $_SESSION['user_id']);
    $get_fav_titles->execute();
    $fav_titles_result = $get_fav_titles->get_result();
    if ($fav_titles_result->num_rows == 0) {
        echo '
	  <div class="no-content no-content-favorites">
		<div>
		  <p>Tap the ☆ button on a community\'s page to have it show up as a favorite community here.</p>
		  <a href="/communities/favorites" class="favorite-community-link symbol"><span class="symbol-label">Show More</span></a>
        </div>
      </div>';
    } else {
        echo '<div class="card" id="community-favorite"><ul>';

        $empty_space = 0;

        while ($fav_titles = $fav_titles_result->fetch_assoc()) {
            echo '<li class="test-favorite-community">
    		<a href="/titles/'. $fav_titles['title_id'] .'" class="icon-container"><img src="'. $fav_titles['title_icon'] .'" class="icon"></a></li>';
            $empty_space++;
        }

        for ($i = 8; $i > $empty_space; $i--) {
            echo '<li class="test-favorite-empty-placeholder"><span class="empty-icon"><img src="/assets/img/empty.png"></span></li>';
        }
        echo '
    	<li class="read-more">
          <a href="/communities/favorites" class="favorite-community-link symbol"><span class="symbol-label">Show More</span></a>
        </li>
      </ul>
    </div>';
    }
}

//Popular communities (these aren't dynamic so you have to change them right here)
echo '
<h3 class="community-title symbol">Popular Communities</h3>
<div>
  <ul class="list community-list community-card-list test-hot-communities">';

$get_pop_titles = $dbc->prepare('SELECT * FROM titles INNER JOIN (SELECT COUNT(id) AS FUCK_SQL, post_title FROM posts GROUP BY post_title) AS ok ON post_title = title_id WHERE title_id IN (SELECT post_title FROM posts GROUP BY post_title) ORDER BY FUCK_SQL DESC LIMIT 4');
$get_pop_titles->execute();
$pop_titles_result = $get_pop_titles->get_result();
while ($pop_titles = $pop_titles_result->fetch_assoc()) {
    printTitleInfo($pop_titles);
}

echo '
  </ul>
</div>

<h3 class="community-title symbol">
        <span>New Communities (Wii U)</span><button class="symbol filter-button" type="button" data-modal-open="#wiiu-filter-select-page">Filter</button></h3>
<div>
<ul class="list community-list community-card-list device-new-community-list">';

$get_titles = $dbc->prepare('SELECT * FROM titles WHERE type = 1  LIMIT 6');
$get_titles->execute();
$titles_result = $get_titles->get_result();

while ($titles = $titles_result->fetch_assoc()) {
    printTitleInfo($titles);
}

echo '
</ul><a href="/communities/categories/wiiu_all" class="big-button">Show More</a>';

while ($titles = $titles_result->fetch_assoc()) {
    printTitleInfo($titles);
}
echo '
  </ul>
</div>

<h3 class="community-title symbol">
        <span>New Communities (3DS)</span><button class="symbol filter-button" type="button" data-modal-open="#wiiu-filter-select-page">Filter</button></h3>
<div>
<ul class="list community-list community-card-list device-new-community-list">';

$get_titles = $dbc->prepare('SELECT * FROM titles WHERE type = 2 LIMIT 6');
$get_titles->execute();
$titles_result = $get_titles->get_result();

while ($titles = $titles_result->fetch_assoc()) {
    printTitleInfo($titles);
}
echo '
</ul><a href="/communities/categories/3ds_all" class="big-button">Show More</a>';
    echo '

<h3 class="community-title symbol">
        <span>New Communities (Switch)</span><button class="symbol filter-button" type="button" data-modal-open="#wiiu-filter-select-page">Filter</button></h3>
<div>
<ul class="list community-list community-card-list device-new-community-list">';

$get_titles = $dbc->prepare('SELECT * FROM titles WHERE type = 4 LIMIT 6');
$get_titles->execute();
$titles_result = $get_titles->get_result();

while ($titles = $titles_result->fetch_assoc()) {
    printTitleInfo($titles);
}
echo '
</ul><a href="/communities/categories/switch_all" class="big-button">Show More</a>';
echo '
</div>
<h3 class="community-title symbol">
        <span>New Communities (Special)</span><button class="symbol filter-button" type="button" data-modal-open="#wiiu-filter-select-page">Filter</button></h3>
<div>
<ul class="list community-list community-card-list device-new-community-list">';

$get_titles = $dbc->prepare('SELECT * FROM titles WHERE type = 6 LIMIT 6');
$get_titles->execute();
$titles_result = $get_titles->get_result();

while ($titles = $titles_result->fetch_assoc()) {
    printTitleInfo($titles);
}
echo '
</ul><a href="/communities/categories/special_all" class="big-button">Show More</a>';
echo '
</div>';
?>
    <div id="community-guide-footer">
			<div id="guide-menu"> <!-- Support menu -->
				<a class="arrow-button" href="/guide/"><span>Code of Conduct</span></a>
				<a class="arrow-button" href="/guide/terms"><span>Use of Uiiverse</span></a>
				<a class="arrow-button" href="/guide/faq"><span>Frequently Asked Questions (FAQ)</span></a>
			</div>
		</div>
	<!-- Footer -->
    <div id="footer"><div id="footer-inner"><div class="link-container"><p><a href="https://api.uiiverse.xyz/">API</a></p><!-- API button --><p><a href="https://downloads.uiiverse.xyz/">Downloads</a></p><!-- Downloads --><p><a href="https://status.uiiverse.xyz/">Status</a></p><!-- Status button --><p><a href="/android">Android</a></p><!-- Android button --><p><a href="/wii/">Wii</a></p><p><a href="/switch/">Switch</a></p><!-- Wii button --><p><a href="/desktop/">Desktop</a></p><!-- Desktop button --><p id="copyright"><a href="https://nintendo.com/">Uiiverse is a non-profit revival of Nintendo and Hatena's Miiverse service. We are not affiliated with these companies and they deserve your business.</a><!-- Legal text --></p></div></div></div>
</div>
<? } ?>
