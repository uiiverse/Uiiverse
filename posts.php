<?php
require_once('lib/htm.php');

$search_post = $dbc->prepare('SELECT * FROM posts WHERE posts.id = ? LIMIT 1');
$search_post->bind_param('i', $id);
$search_post->execute();
$post_result = $search_post->get_result();

if ($post_result->num_rows == 0) {
    printHeader(0);
    exit('<title>Uiiverse - Error</title><div class="no-content track-error" data-track-error="404"><div><p>The post could not be found.</p></div></div>');
}
if (isset($_COOKIE['retro-mode'])) {
    echo '<link rel="stylesheet" type="text/css" href="/assets/css/retro/posts-replies.css "> <link rel="stylesheet" type="text/css" href="/assets/css/retro/root.css">';
    }
    $post = $post_result->fetch_assoc();

    $get_user = $dbc->prepare('SELECT * FROM users INNER JOIN profiles ON users.user_id = profiles.user_id WHERE users.user_id = ?');
    $get_user->bind_param('i', $post['post_by_id']);
    $get_user->execute();
    $user_result = $get_user->get_result();
    $user = $user_result->fetch_assoc();
    $get_prof = $dbc->prepare('SELECT * FROM profiles INNER JOIN posts ON id = fav_post AND deleted = 0 WHERE user_id = ?');
		$get_prof->bind_param('i', $user['user_id']);
		$get_prof->execute();
		$prof_result = $get_prof->get_result();
		$profile = $prof_result->fetch_assoc();
    $tabTitle = 'Uiiverse - '. htmlspecialchars($user['nickname'], ENT_QUOTES) .'\'s Post';
    $bodyID = 'post-permlink';
    echo '<script src="https://apis.google.com/_/scs/apps-static/_/js/k=oz.gapi.en_US.MGdIDI8wTVg.O/m=auth/exm=plusone/rt=j/sv=1/d=1/ed=1/am=QQ/rs=AGLTcCPawbJm9qhJY3moxayCKAdmv4AXJQ/cb=gapi.loaded_1" async=""></script>
    <script src="https://apis.google.com/_/scs/apps-static/_/js/k=oz.gapi.en_US.MGdIDI8wTVg.O/m=plusone/rt=j/sv=1/d=1/ed=1/am=QQ/rs=AGLTcCPawbJm9qhJY3moxayCKAdmv4AXJQ/cb=gapi.loaded_0" async=""></script>
    <script id="twitter-wjs" src="http://platform.twitter.com/widgets.js"></script>
    <script id="facebook-jssdk" src="//connect.facebook.net/en_US/sdk.js#xfbml=1&amp;version=v2.3"></script>
    <script charset="utf-8" src="https://platform.twitter.com/js/button.e96bb6acc0f8bda511c0c46a84ee18e4.js"></script>';
    printHeader('');

if ($post['deleted'] == 1 && $post['post_by_id'] != $_SESSION['user_id']) {
    echo '<div class="no-content track-error" data-track-error="deleted"><div><p class="deleted-message">
            Deleted by administrator.<br>
            Post ID: '. $post['id'] .'
          </p></div></div>';
} elseif ($post['deleted'] == 2) {
    echo '<div class="no-content track-error" data-track-error="deleted"><div><p>Deleted by poster.</p></div></div>';
} else {
    echo '<div class="main-column"><div class="post-list-outline"><section id="post-content" class="post post-subtype-default">';

    $get_title = $dbc->prepare('SELECT * FROM titles WHERE title_id = ? LIMIT 1');
    $get_title->bind_param('i', $post['post_title']);
    $get_title->execute();
    $title_result = $get_title->get_result();
    $title = $title_result->fetch_assoc();

    echo '<meta property="og:title" content="Post to '. $title['title_name'] .' - Uiiverse">
		<meta property="og:url" content="http://cedar.rf.gd/posts/'. $post['id'] .'">
		<meta property="og:description" content="'. htmlspecialchars($user['nickname'], ENT_QUOTES) .' : '. (mb_strlen($post['text']) > 46 ? htmlspecialchars(mb_substr($post['text'], 0, 47)) .'...' : htmlspecialchars($post['text'], ENT_QUOTES)) .' - Uiiverse">

		<header class="community-container">
        <a href="/titles/'. $title['title_id'] .'" class="post-subtype-label post-subtype-label-via-api">In-Game</a>
		  <h1 class="community-container-heading">
		    <a href="/titles/'. $title['title_id'] .'"><img src="'. $title['title_icon'] .'" class="community-icon">'. $title['title_name'] .'</a>
		  </h1>
		</header>

		<div id="user-content">
		  <a href="/users/'. $user['user_name'] .'/" class="icon-container'. ($user['user_level'] > 1 ? ' official-user' : '') .'">
		    <img src="'. printFace($user['user_face'], $post['feeling_id']) .'" class="icon">
		  </a>
		  <div class="user-name-content">
			<p class="user-name">
			  <a href="/users/'. $user['user_name'] .'/" '.(isset($user['name_color']) ? 'style="color: '. $user['name_color'] .'"' : '').'>'. htmlspecialchars($user['nickname'], ENT_QUOTES) .'</a><span class="user-id">'. $user['user_name'] .'</span></p><p class="timestamp-container"><span class="timestamp">'. humanTiming(strtotime($post['date_time'])) .'<span class="spoiler-status">·Spoilers</span></span></p></div><div><div class="body">';

    if ($post['deleted'] == 1) {
        echo '<p class="deleted-message">
            Deleted by administrator.<br>
            Post ID: '. $post['id'] .'
          </p>';
    }

    if (!empty($post['post_image'])) {
        echo '<div class="screenshot-container still-image"><img src="'. $post['post_image'] .'"></div>';
    }

    $post['text'] = htmlspecialchars($post['text'], ENT_QUOTES);

    $post['text'] = preg_replace('|([\w\d]*)\s?(https?://([\d\w\.-]+\.[\w\.]{2,6})[^\s\]\[\<\>]*/?)|i', '$1 <a href="$2" target="_blank" class="post-link">$2</a>', $post['text']);

    echo '<p class="post-content-text">'. nl2br($post['text'], ENT_QUOTES) .'</p></div><div id="post-meta">';

    $yeah_count = $dbc->prepare('SELECT COUNT(yeah_by) FROM yeahs WHERE type = "post" AND yeah_post = ?');
    $yeah_count->bind_param('i', $post['id']);
    $yeah_count->execute();
    $result_count = $yeah_count->get_result();
    $yeah_amount = $result_count->fetch_assoc();


    $yeahs = $yeah_amount['COUNT(yeah_by)'];

        echo '<button type="button" class="symbol submit empathy-button';

    if (!empty($_SESSION['signed_in']) && checkYeahAdded($post['id'], 'post', $_SESSION['user_id'])) {
        echo ' empathy-added';
    }

    echo '"';

    if (empty($_SESSION['signed_in']) || checkPostCreator($post['id'], $_SESSION['user_id'])) {
        echo ' disabled';
    }

    echo 'data-track-label="post"><span class="empathy-button-text">';

    if (!empty($_SESSION['signed_in']) && checkYeahAdded($post['id'], 'post', $_SESSION['user_id'])) {
        echo 'Unyeah';
    } else {
        echo 'Yeah!';
    }

    echo '</span></button>';
    echo '<div class="empathy symbol"><span class="empathy-count">'. $yeahs .'</span></div>';

    $reply_count = $dbc->prepare('SELECT COUNT(reply_id) FROM replies WHERE reply_post = ? AND deleted = 0');
    $reply_count->bind_param('i', $post['id']);
    $reply_count->execute();
    $result_count = $reply_count->get_result();
    $reply_amount = $result_count->fetch_assoc();

    echo '<div class="reply symbol"><span id="reply-count">'. $reply_amount['COUNT(reply_id)'] .'</span></div></div>
		</div></section>
        ';

    //yeah content

    if ($post['deleted'] != 1) {
        $get_user = $dbc->prepare('SELECT * FROM users WHERE user_id = ?');
        $get_user->bind_param('i', $_SESSION['user_id']);
        $get_user->execute();
        $user_result = $get_user->get_result();
        $user = $user_result->fetch_assoc();

        if (empty($yeah_amount['COUNT(yeah_by)'])) {
            echo '<div id="empathy-content" class="none">';
        } else {
            echo '<div id="empathy-content">' ;
        }

        if (!checkYeahAdded($post['id'], 'post', $_SESSION['user_id'])) {
            echo '<a href="/users/'. $user['user_name'] .'/posts" class="icon-container visitor'. ($user['user_level'] > 1 ? ' official-user' : '') .'" style="display: none;">
				<img src="'. printFace($user['user_face'], $post['feeling_id']) .'" class="icon"></a>';
        } else {
            echo '<a href="/users/'. $user['user_name'] .'/posts" class="icon-container visitor'. ($user['user_level'] > 1 ? ' official-user' : '') .'">
				<img src="'. printFace($user['user_face'], $post['feeling_id']) .'" class="icon"></a>';
        }

        if (!empty($_SESSION['signed_in'])) {
            $yeahs_by = $dbc->prepare('SELECT * FROM users, yeahs WHERE users.user_id = yeahs.yeah_by AND yeahs.yeah_post = ? AND NOT users.user_id = ? ORDER BY yeahs.yeah_id DESC LIMIT 14');
            $yeahs_by->bind_param('ii', $post['id'], $_SESSION['user_id']);
        } else {
            $yeahs_by = $dbc->prepare('SELECT * FROM users, yeahs WHERE users.user_id = yeahs.yeah_by AND yeahs.yeah_post = ? ORDER BY yeahs.yeah_id DESC LIMIT 14');
            $yeahs_by->bind_param('i', $post['id']);
        }
        $yeahs_by->execute();
        $yeahs_by_result = $yeahs_by->get_result();

        while ($yeah_by = $yeahs_by_result->fetch_array()) {
            echo '<a href="/users/'. $yeah_by['user_name'] .'/posts" class="icon-container'. ($yeah_by['user_level'] > 1 ? ' verified' : '') .'">
				  <img src="'. printFace($yeah_by['user_face'], $post['feeling_id']) .'" class="icon"></a>';
        }

        echo '</div>';

        //edit button

        echo '<div class="buttons-content">
  <h5 class="social-buttons-heading">Share this Post</h5>
  <div class="post-social-buttons-wrapper social-buttons-container is-disable-twitter">

<div class="social-buttons-content social-buttons-content-primary">
  <div class="social-buttons-content-cell facebook">
  <script>(function(d, s, id) {
              var js, fjs = d.getElementsByTagName(s)[0];
              if (d.getElementById(id)) return;
              js = d.createElement(s); js.id = id;
              js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.3";
              fjs.parentNode.insertBefore(js, fjs);
            }(document, \'script\', \'facebook-jssdk\'));</script>
                 <div class="fb-like fb_iframe_widget" data-href="http://redesign.use-cedar.epizy.com" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false" fb-xfbml-state="rendered" fb-iframe-plugin-query="action=like&amp;app_id=&amp;container_width=0&amp;href=http://use-cedar.epizy.comamp;layout=button_count&amp;locale=en_US&amp;sdk=joey&amp;share=false&amp;show_faces=false"><span style="vertical-align: bottom; width: 63px; height: 20px;"><iframe name="f39b305e5804d18" width="1000px" height="1000px" frameborder="0" allowtransparency="true" allowfullscreen="true" scrolling="no" allow="encrypted-media" title="fb:like Facebook Social Plugin" src="https://www.facebook.com/v2.3/plugins/like.php?action=like&amp;app_id=&amp;channel=https%3A%2F%2Fstaticxx.facebook.com%2Fconnect%2Fxd_arbiter%2Fr%2FafATJJjxKE6.js%3Fversion%3D43%23cb%3Df1b61e3f1404524%26domain%3Dlocalhost%26origin%3Dhttp%253A%252F%252Flocalhost%252Ff19ac35844ce408%26relation%3Dparent.parent&amp;container_width=0&amp;href=http://redesign.use-cedar.epizy.com&amp;layout=button_count&amp;locale=en_US&amp;sdk=joey&amp;share=false&amp;show_faces=false" style="border: none; visibility: visible; width: 63px; height: 20px;" class=""></iframe></span></div>
  </div>
</div>
<div class="social-buttons-content social-buttons-content-secondary">
<li>
            <iframe id="twitter-widget-0" scrolling="no" frameborder="0" allowtransparency="true" class="twitter-share-button twitter-share-button-rendered twitter-tweet-button" title="Twitter Tweet Button" src="https://platform.twitter.com/widgets/tweet_button.c9b0d6e1ef0320c49dc875c581cc9586.en.html#dnt=false&amp;id=twitter-widget-0&amp;lang=en&amp;original_referer=http://redesign.use-cedar.epizy.com?locale.lang=en&amp;size=m&amp;text=Uiiverse&amp;time=1543272960760&amp;type=share&amp;url=http://redesign.use-cedar.epizy.com?locale.lang=en" style="position: static; visibility: visible; width: 63px; height: 20px;" data-url="http://redesign.use-cedar.epizy.com?locale.lang=en"></iframe>
            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?\'http\':\'https\';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+\'://platform.twitter.com/widgets.js\';fjs.parentNode.insertBefore(js,fjs);}}(document, \'script', 'twitter-wjs\');</script>
          </li>
</div>
  </div>
  
  <button type="button" class="embed-link-button" data-modal-open="#view-embed-link-code">Embed</button>
  
</div>';

        if ($post['post_by_id'] == $_SESSION['user_id']) {
            echo '<button type="button" class="symbol button edit-button edit-post-button" data-modal-open="#edit-post-page">
				<span class="symbol-label">Edit</span></button>';
        }

        echo '
        ';

        //comments
        echo '<div id="reply-content"><h2 class="reply-label">Comments</h2><ul class="list reply-list test-reply-list">';
        $search_replies = $dbc->prepare('SELECT * FROM replies INNER JOIN users ON user_id = reply_by_id INNER JOIN profiles ON users.user_id = profiles.user_id WHERE reply_post = ? AND deleted < 2 ORDER BY date_time ASC');
        $search_replies->bind_param('i', $id);
        $search_replies->execute();
        $replies_result = $search_replies->get_result();

        if ($replies_result->num_rows == 0) {
            echo '<div class="no-reply-content"><div><p>This post has no comments.</p></div></div>';
        } else {
            while ($replies = $replies_result->fetch_array()) {
                echo '<li id="reply-AYMHAAADAAADV44LLbUc-Q" data-href="/replies/'. $replies['reply_id'] .'" class="post other trigger" data-href="/replies/'. $replies['reply_id'] .'">';
                printReply($replies);
            }
        }

        echo '</ul><h2 class="reply-label">Add a Comment</h2>';
if (empty($_SESSION['signed_in'])) {
	echo ' 
		<div class="guest-message">
  <p>You must sign in to post a comment.<br>
<br>Sign in using a Uiiverse account to make posts and comments, as well as give Yeahs and follow users.</p>
  <a href="/guide/terms" class="arrow-button"><span>Use of Uiiverse</span></a>
  <a href="http://www.nintendo.com/wiiu/built-in-software/#/miiverse" class="arrow-button"><span>Details about Miiverse</span></a>
</div>';
}
      if ($profile['user_relationship_visibility'] == 1) {
            echo '<div class="cannot-reply">
                        <p>You cannot comment on this post.</p>
                    </div>';
        } else 

        include 'postReply.php';

        echo '
			<div id="edit-post-page" class="dialog none" data-modal-types="edit-post">
	          <div class="dialog-inner">
	            <div class="window">
	              <h1 class="window-title">Edit Post</h1>
	              <div class="window-body">
	                <form method="post" class="edit-post-form" action="">
	                  <input type="hidden" name="token" value="2wdaCleDbc7i8JOwRK8_vw">
	                  <p class="select-button-label">Select an action:</p>
	                  <select name="edit-type">
	                    <option value="" selected="">Select an option.</option>
	                    '. (isset($post['post_image']) ? '<option value="screenshot-profile-post" data-action="/posts/'. $post['id'] .'/image.set_profile_post">Set Image as Favorite Post</option>' : ''). '
	                    <option value="delete" data-action="/deletePost.php?postId='. $post['id'] .'&postType=post" data-track-action="deletePost">Delete</option>
	                  </select>
	                  <div class="form-buttons">
	                    <input type="button" class="olv-modal-close-button gray-button" value="Cancel">
	                    <input type="submit" class="post-button black-button disabled" value="Submit" disabled="">
	                  </div>
	                </form>
	              </div>
	            </div>
	          </div>
	        </div>

	        </div></div></div>';
    }
}
