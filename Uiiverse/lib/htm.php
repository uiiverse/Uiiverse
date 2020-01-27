<?php
require_once('connect.php');
//This is mainly for storing functions. Using functions is faster than using include/require. I created printHeader() to get rid of header.php, yeah functions to get rid of postLib.php, etc.

function printFace($face, $feeling)
{
    if (strpos($face, "i.imgur") || strpos($face, "cloudinary")) {
        return $face;
    } elseif (!empty($face)) {
        switch ($feeling) {
            case 0:
                $type = "_normal_face.png";
                break;
            case 1:
                $type = "_happy_face.png";
                break;
            case 2:
                $type = "_like_face.png";
                break;
            case 3:
                $type = "_surprised_face.png";
                break;
            case 4:
                $type = "_frustrated_face.png";
                break;
            case 5:
                $type = "_puzzled_face.png";
                break;
        }
        return 'https://mii-secure.cdn.nintendo.net/'. htmlspecialchars($face, ENT_QUOTES) . $type;
    } else {
	return 'https://i.imgur.com/kmKVmny.png';
    }
}

function printHeader($on_page)
{
    global $dbc;
    global $tabTitle;
    global $bodyID;
    global $bodyClass;

    if (isset($_SERVER['HTTP_X_PJAX'])) {
        if (isset($tabTitle)) {
            echo '<title>'. $tabTitle .'</title>
            <script>
            $(\'#global-menu-list\').children().children().removeClass(\'selected\');';
            
            if ($on_page != '') {
                echo '$(\'#';

                switch ($on_page) {
                    case 1:
                        echo 'global-menu-mymenu';
                        break;
                    case 2:
                        echo 'global-menu-feed';
                        break;
                    case 3:
                        echo 'global-menu-community';
                        break;
                    case 4:
                        echo 'global-menu-news';
                        break;
                }
                echo '\').addClass(\'selected\');';
            }

            echo '</script>';
        }
        return;
    }
 
    echo '<!DOCTYPE html>
    <head>
    '. (isset($tabTitle) ? '<title>'. $tabTitle .'</title>' : '').'
    <link rel="stylesheet" type="text/css" href="/assets/css/style.css">';
    if (isset($_COOKIE['dark-mode'])) {
        echo '<link rel="stylesheet" type="text/css" href="/assets/css/dark.css">';
    } elseif (isset($_COOKIE['amoled-mode'])) {
        echo '<link rel="stylesheet" type="text/css" href="/assets/css/amoled.css">';
    } elseif (isset($_COOKIE['neon-mode'])) {
        echo '<link rel="stylesheet" type="text/css" href="/assets/css/neon.css">';
    } elseif (isset($_COOKIE['translucent-mode'])) {
        echo '<link rel="stylesheet" type="text/css" href="/assets/css/translucent.css">';
    } elseif (isset($_COOKIE['blur-mode'])) {
        echo '<link rel="stylesheet" type="text/css" href="/assets/css/blur.css">';
    } elseif (isset($_COOKIE['stripe-mode'])) {
        echo '<link rel="stylesheet" type="text/css" href="/assets/css/stripe.css">';
    } elseif (isset($_COOKIE['retro-mode'])) {
    	echo '<link rel="stylesheet" type="text/css" href="/assets/css/retro/root.css"> <!-- Retro theme -->';
	if (strpos($_SERVER['REQUEST_URI'], '/users/') !== false) {
		echo "<link rel='stylesheet' type='text/css' href='/assets/css/retro/users.css'>";
	} elseif (strpos($_SERVER['REQUEST_URI'], '/news/my_news') !== false) {
                echo "<link rel='stylesheet' type='text/css' href='/assets/css/retro/news-my-news.css'>";
        } elseif (strpos($_SERVER['REQUEST_URI'], '/titles') !== false) {
                echo "<link rel='stylesheet' type='text/css' href='/assets/css/retro/titles.css'>";
        } elseif (strpos($_SERVER['REQUEST_URI'], '/replies') !== false or strpos($_SERVER['REQUEST_URI'], '/posts') !== false) {
                echo "<link rel='stylesheet' type='text/css' href='/assets/css/retro/posts-replies.css'>";
        } elseif (strpos($_SERVER['REQUEST_URI'], '/activity') !== false or strpos($_SERVER['REQUEST_URI'], '/settings') !== false or strpos($_SERVER['REQUEST_URI'], '/guide') !== false or strpos($_SERVER['REQUEST_URI'], '/admin_panel') !== false or strpos($_SERVER['REQUEST_URI'], '/identified_user_posts') !== false) {
		echo "<link rel='stylesheet' type='text/css' href='/assets/css/retro/activity-settings-guide-admin_panel-identified_user_posts.css'>";
	}

    }

    if (isset($_COOKIE['stripe-color'])) {
        echo '<script>/* GLOBAL VARIABLES */
        var mainColor = "'. $_COOKIE['stripe-color'] .'"
        var mainColorR = 0
        var mainColorG = 0
        var maincolorB = 0
        var darkColor
        var darkerColor
       
 
        function changeThemeColor() {
           
            /* Change hex to RGB for maths (sets 3 variables) */
            hexToRgb(mainColor);  
           
           
            /* Function does not output anything if value is 0, so this has to be done*/
            if (!(mainColorR)) {mainColorR = 0;}
            if (!(mainColorG)) {mainColorG = 0;}
            if (!(mainColorB)) {mainColorB = 0;}
           
            /* Calculate darkColor & darkerColor */
            darkColor = rgb2hex(mainColorR / 4, mainColorG / 4, mainColorB / 4);
            darkerColor = rgb2hex(mainColorR / 8, mainColorG / 8, mainColorB / 8);
           
            /* Exception for #000000 */
           
            if (mainColor == "000000") { darkerColor = "#3f3f3f"; darkColor = "#1f1f1f" }
                if (mainColor == "8000ff") { 
                document.documentElement.style.setProperty("--theme", "initial");
                document.documentElement.style.setProperty("--theme-dark", "initial"); 
                document.documentElement.style.setProperty("--theme-darker", "initial");
                }
            /* Set CSS variables */
            document.documentElement.style.setProperty("--theme", `rgb(${mainColorR}, ${mainColorG}, ${mainColorB})`);
            document.documentElement.style.setProperty("--theme-dark", darkColor);
            document.documentElement.style.setProperty("--theme-darker", darkerColor);
           
            /* so stuff doesnt complain bc theres no return statement */
            return 0;
            }
           
                    /* hex -> rgb */
                     function hexToRgb(hex) {
                          var bigint = parseInt(hex, 16);
                          mainColorR = (bigint >> 16) & 255;
                          mainColorG = (bigint >> 8) & 255;
                          mainColorB = bigint & 255;
                     
                          return mainColorR + "," + mainColorG + "," + mainColorB;
                      }
                     
                      /* rgb -> hex */
                      function rgb2hex(red, green, blue) {
                            var rgb = blue | (green << 8) | (red << 16);
                            return "#" + (0x1000000 + rgb).toString(16).slice(1)
                      }
 
  function toDefault() {
  document.documentElement.style.setProperty("--theme", "initial");
  document.documentElement.style.setProperty("--theme-dark", "initial");
  document.documentElement.style.setProperty("--theme-darker", "initial");
  }

  changeThemeColor();

        </script>';
    }

if (isset($_COOKIE['neon-color'])) {
        echo '<script>/* GLOBAL VARIABLES */
        var mainColor = "'. $_COOKIE['neon-color'] .'"
        var mainColorR = 0
        var mainColorG = 0
        var maincolorB = 0
       
 
        function changeThemeColor() {
           
            /* Change hex to RGB for maths (sets 3 variables) */
            hexToRgb(mainColor);  
           
           
            /* Function does not output anything if value is 0, so this has to be done*/
            if (!(mainColorR)) {mainColorR = 0;}
            if (!(mainColorG)) {mainColorG = 0;}
            if (!(mainColorB)) {mainColorB = 0;}
          
           
            /* Set CSS variables */
            document.documentElement.style.setProperty("--color", `rgb(${mainColorR}, ${mainColorG}, ${mainColorB})`);
           
            /* so stuff doesnt complain bc theres no return statement */
            return 0;
            }
           
                    /* hex -> rgb */
                     function hexToRgb(hex) {
                          var bigint = parseInt(hex, 16);
                          mainColorR = (bigint >> 16) & 255;
                          mainColorG = (bigint >> 8) & 255;
                          mainColorB = bigint & 255;
                     
                          return mainColorR + "," + mainColorG + "," + mainColorB;
                      }
                     
                      /* rgb -> hex */
                      function rgb2hex(red, green, blue) {
                            var rgb = blue | (green << 8) | (red << 16);
                            return "#" + (0x1000000 + rgb).toString(16).slice(1)
                      }
 
  function toDefault() {
  document.documentElement.style.setProperty("--color", "initial");
  }

  changeThemeColor();

        </script>';
    }

    if (isset($_COOKIE['background'])) {
        echo  '<style>#wrapper, #image-header-content {
                background-image: url("'. $_COOKIE['background'] .'") !important;
            }</style>';
    }

    if (isset($_COOKIE['cedar_color_theme'])) {
        $HSL = explode(',', $_COOKIE['cedar_color_theme']);
        echo '';
    }

    ?>
     <link rel="stylesheet" type="text/css" href="/assets/css/embedded.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="icon" type="image/png" sizes="96x96" href="https://i.ibb.co/Q9Mvskm/uiiversefavicon.png">
    <script src="/assets/js/complete-en.js"></script>
    <script src="/assets/js/openverse.js"></script>
    <script src="/assets/js/jquery-3.3.1.min.js"></script>
    <script src="/assets/js/pace.min.js"></script>
    <script src="/assets/js/jquery.pjax.js"></script>
    <script src="/assets/js/favico.js"></script>
    <script src="/assets/js/tippy.all.min.js"></script>
    <script src="/assets/js/yeah.js"></script>
    <meta property="og:site_name" content="Uiiverse">
    <meta property="og:type" content="article">
    </head>

    <body
     <?
  if (!empty($bodyID)) {
  echo 'id="'.$bodyID.'"';
  }
?>
<?
  if (!empty($bodyClass)) {
  echo 'class="'.$bodyClass.'"';
  }
  if (!isset($_SESSION['signed_in'])) {
      echo ' class="guest"';
    }
?>>
  <div id="wrapper"<?
    if (!isset($_SESSION['signed_in'])) {
      echo ' class="guest"';
    }
      ?>>
        <div id="sub-body">
          <menu id="global-menu">
            <li id="global-menu-logo">
                <h1><a href="/"><img src="https://i.ibb.co/dMPvqk9/logo.png" alt="Uiiverse" width="165" height="30"></a></h1></li>
    <?php


    if (!empty($_SESSION['signed_in'])) {
        $get_user = $dbc->prepare('SELECT * FROM users LEFT JOIN titles ON titles.type = 5 WHERE user_id = ? LIMIT 1');
        $get_user->bind_param('i', $_SESSION['user_id']);
        $get_user->execute();
        $user_result = $get_user->get_result();
        $user = $user_result->fetch_assoc();

        ?>
        <li id="global-menu-list">
            <ul>
                <li id="global-menu-mymenu"<?= ($on_page == 1 ? ' class="selected"' : '') ?>>
                    <a href="/users/<?= $user['user_name'] ?>/">
                        <span class="icon-container<?= ($user['user_level'] > 4 ? ' official-user' : '') ?>">
                            <img src="<?= printFace($user['user_face'], 0) ?>" alt="User Page">
                        </span>
                        <span>User Page</span>
                    </a>
                </li>
                <li id="global-menu-feed"<?= ($on_page == 2 ? ' class="selected"' : '') ?>>
                    <a href="/activity" class="symbol"><span>Activity Feed</span></a>
                </li>
                <li id="global-menu-community"<?= ($on_page == 3 ? ' class="selected"' : '') ?>>
                    <a href="/" class="symbol"><span>Communities</span></a>
                </li>
                <li id="global-menu-news"<?= ($on_page == 4 ? ' class="selected"' : '') ?>>
                    <a href="/news/my_news" class="symbol"><span class="badge" style="display: none;">0</span></a>
                </li>
                <li id="global-menu-my-menu"><button class="symbol js-open-global-my-menu open-global-my-menu"></button>
                    <menu id="global-my-menu" class="invisible none">
                        <li><a href="/settings/profile" class="symbol my-menu-profile-setting"><span>Profile Settings</span></a></li>
                        <li><a href="/settings/theme" class="symbol my-menu-miiverse-setting"><span>Theme Settings</span></a></li>
                        <li><a href="/settings/account" class="symbol my-menu-miiverse-setting"><span>Uiiverse Settings</span></a></li> 
                        <li><a href="/guide/" class="symbol my-menu-guide"><span>Uiiverse Rules</span></a></li>
                        <li><a href="/guide/faq" class="symbol my-menu-guide"><span>Frequently Asked Questions (FAQ)</span></a></li>
                        <li><a href="/titles/<?= $user['title_id'] ?>" class="symbol my-menu-info"><span>Uiiverse Announcements</span></a></li>
                        <?= ($user['user_level'] > 4 ? '<li><a href="/admin_panel" class="symbol my-menu-miiverse-setting"><span>Admin Manager</span></a></li>' : '') ?>
			<li>
                            <form action="/logout" method="post" id="my-menu-logout" class="symbol">
                                <input type="submit" value="Sign out">
                            </form>
                        </li>
                  </menu>
                </li>
            </ul>
        </li>
            
        <?php
    } else {
        echo '<li id="global-menu-login"><a href="/login" style="box-shadow: none;"><img alt="Sign in" src="https://i.ibb.co/w47ympr/signin.png"></a></li>';
    }

    echo '</menu></div>';
    if (!isset($_SESSION['signed_in'])) {
        echo'
    <div style="background-color:white;text-align:right;position:fixed;width:100%;padding-top:1px;padding-bottom:2px;bottom:0;color:gray">This website uses some of <a href="http://olv-pearl.gq/">Pearl</a> and <a href="https://github.com/EnergeticBark/Cedar-PHP">Cedar</a> code. Please support the original developer.</div>';
    }
    echo '
<div id="main-body">';
}
function notify($to, $type, $post)
{
    //types 0: post yeah, 1: reply yeah, 2: comment on your post, 3: posters comment, 4: follow.
    global $dbc;

    $check_mergedusernews = $dbc->query('SELECT * FROM notifs WHERE notif_by = "'.$_SESSION['user_id'].'" AND notif_to = "'.$to.'" AND notif_type = '.$type.' '.($type != 4 ? 'AND notif_post = '.$post : '').' AND merged IS NOT NULL AND notif_date > NOW() - 7200 ORDER BY notif_date DESC');
    if ($check_mergedusernews->num_rows != 0) {
        $result_update_mergedusernewsagain = $dbc->query('UPDATE notifs SET notif_read = "0", notif_date = CURRENT_TIMESTAMP WHERE notif_id = "'.$check_mergedusernews->fetch_assoc()['merged'].'"');
    } else {
        $result_update_newsmergesearch = $dbc->query('SELECT * FROM notifs WHERE notif_to = '.$to.' '.($type != 4 ? 'AND notif_post = '.$post : '').' AND notif_date > NOW() - 7200 AND notif_type = '.$type.' ORDER BY notif_date DESC');
        if ($result_update_newsmergesearch->num_rows != 0) {
            $row_update_newsmergesearch = $result_update_newsmergesearch->fetch_assoc();
            $result_newscreatemerge = $dbc->query('INSERT INTO notifs(notif_by, notif_to, '.($type != 4 ? 'notif_post, ' : '').'merged, notif_type, notif_read) VALUES ("'.$_SESSION['user_id'].'", "'.$to.'", '.($type != 4 ? '"'.$post.'", ' : '').'"'.$row_update_newsmergesearch['notif_id'].'", '.$type.', "0")');
            $result_update_newsformerge = $dbc->query('UPDATE notifs SET notif_read = "0", notif_date = NOW() WHERE notif_id = "'.$row_update_newsmergesearch['notif_id'].'"');
        } else {
            $result_newscreate = $dbc->query('INSERT INTO notifs(notif_by, notif_to, '.($type != 4 ? 'notif_post,' : '').'notif_type, notif_read) VALUES ("'.$_SESSION['user_id'].'", "'.$to.'", '.($type != 4 ? '"'.$post.'",' : '').' '.$type.', "0")');
        }
    }
}

function printPost($post, $reply_pre)
{
    global $dbc;

    echo '<a href="/users/'. $post['user_name'] .'/" class="icon-container'.($post['user_level'] > 4 ? ' official-user' : '').'"><img src="'. printFace($post['user_face'], $post['feeling_id']) .'"class="icon"></a>
        <p class="user-name"><a href="/users/'. $post['user_name'] .'/" '.(isset($post['name_color']) ? 'style="color: '. $post['name_color'] .'"' : '').'>'. htmlspecialchars($post['nickname'], ENT_QUOTES) .'</a></p>
        <p class="timestamp-container"><a class="timestamp" href="/posts/'.$post['id'].'">'.humanTiming(strtotime($post['date_time'])).'</a></p><div id="body">';

    if ($post['deleted'] == 1) {
        echo '<p class="deleted-message">
            Deleted by administrator.<br>
            Post ID: '. $post['id'] .'
          </p>';
    }

    if (!empty($post['post_image'])) {
        echo '<div class="screenshot-container"><img src="'. $post['post_image'] .'"></div>';
    }

    $original_length = mb_strlen($post['text']);

    if ($original_length > 199) {
        $post['text'] = mb_substr($post['text'], 0, 200);
    }

    $post['text'] = htmlspecialchars($post['text'], ENT_QUOTES);

    $post['text'] = preg_replace('|([\w\d]*)\s?(https?://([\d\w\.-]+\.[\w\.]{2,6})[^\s\]\[\<\>]*/?)|i', ' <a href="$2" target="_blank" class="post-link">$2</a>', $post['text']);

    echo '<div id="post-body">';

    echo nl2br($post['text']);

    if ($original_length > 199) {
        echo '...';
    }

    echo '</div><div id="post-meta">';

    $yeah_count = $dbc->prepare('SELECT COUNT(yeah_by) FROM yeahs WHERE type = "post" AND yeah_post = ?');
    $yeah_count->bind_param('i', $post['id']);
    $yeah_count->execute();
    $result_count = $yeah_count->get_result();
    $yeah_amount = $result_count->fetch_assoc();

    $yeahs = $yeah_amount['COUNT(yeah_by)'];





    echo '<button class="yeah symbol';

    if (!empty($_SESSION['signed_in']) && checkYeahAdded($post['id'], 'post', $_SESSION['user_id'])) {
        echo ' yeah-added';
    }

    echo '"';

    if (empty($_SESSION['signed_in']) || checkPostCreator($post['id'], $_SESSION['user_id'])) {
        echo ' disabled ';
    }

    echo 'id="'. $post['id'] .'" data-track-label="post"><span class="yeah-button-text">';

    if (!empty($_SESSION['signed_in']) && checkYeahAdded($post['id'], 'post', $_SESSION['user_id'])) {
        echo 'Unyeah';
    } else {
        echo 'Yeah!';
    }

    echo '</span></button>';
    echo '<div class="empathy symbol"><span class="yeah-count">'. $yeahs .'</span></div>';

    $reply_count = $dbc->prepare('SELECT COUNT(reply_id) FROM replies WHERE reply_post = ? AND deleted = 0');
    $reply_count->bind_param('i', $post['id']);
    $reply_count->execute();
    $result_count = $reply_count->get_result();
    $reply_amount = $result_count->fetch_assoc();

    echo '<div class="reply symbol"><span id="reply-count">'.$reply_amount['COUNT(reply_id)'].'</span></div><div class="played symbol"><span class="symbol-label">Played</span></div></div>';

    if ($post['deleted'] == 0) {
        if ($reply_pre == 1) {
            $search_replies = $dbc->prepare('SELECT * FROM replies INNER JOIN users ON user_id = reply_by_id INNER JOIN profiles ON users.user_id = profiles.user_id WHERE reply_post = ? AND deleted = 0 ORDER BY date_time DESC LIMIT 1');
            $search_replies->bind_param('i', $post['id']);
            $search_replies->execute();
            $replies_result = $search_replies->get_result();
            $replies = $replies_result->fetch_assoc();

            if (!$reply_amount['COUNT(reply_id)'] == 0) {
                echo '<div class="recent-reply-content">
                '.($reply_amount['COUNT(reply_id)']>1?'<div class="recent-reply-read-more-container" data-href="/posts/'.$post['id'].'" tabindex="0">Read Other Comments ('.($reply_amount['COUNT(reply_id)']-1).')</div>':'').'
                <div id="recent-reply-AYQHAAABAAAtVHhpyFW9kQ" data-href="/posts/'.$post['id'].'" tabindex="0" class="recent-reply trigger"><div class="recent-reply trigger"><a href="/users/'.$replies['user_name'].'/posts" class="icon-container'.($replies['user_level']==2?' verified':'').'"><img src="'.printFace($replies['user_face'], $replies['feeling_id']).'" class="icon"></a>
                <p class="user-name"><a href="/users/'.$replies['user_name'].'/posts" '.(isset($replies['name_color']) ? 'style="color: '. $replies['name_color'] .'"' : '').'>'. htmlspecialchars($replies['nickname'], ENT_QUOTES) .'</a></p>
                <p class="timestamp-container"><a class="timestamp" href="/posts/'.$post['id'].'">'.humanTiming(strtotime($replies['date_time'])).'</a></p>
                <div class="body"><div class="post-content"><p class="recent-reply-content-text">'.$replies['text'].'</p></div></div></div></div></div>';
            }
        }
    }

    if ($reply_pre == 1) {
        echo '</div></div>';
    }
}



function checkPostCreator($post, $user_id)
{
    global $dbc;

    $check_posted = $dbc->prepare('SELECT * FROM posts WHERE posts.id = ? AND posts.post_by_id = ? LIMIT 1');
    $check_posted->bind_param('ss', $post, $user_id);
    $check_posted->execute();
    $posted_result = $check_posted->get_result();

    if (!$posted_result->num_rows == 0) {
        return true;
    } else {
        return false;
    }
}

function checkReplyCreator($reply, $user_id)
{
    global $dbc;

    $check_posted = $dbc->prepare('SELECT * FROM replies WHERE replies.reply_id = ? AND replies.reply_by_id = ? LIMIT 1');
    $check_posted->bind_param('ss', $reply, $user_id);
    $check_posted->execute();
    $posted_result = $check_posted->get_result();

    if (!$posted_result->num_rows == 0) {
        return true;
    } else {
        return false;
    }
}


function checkYeahAdded($post, $type, $user_id)
{
    global $dbc;

    $check_yeahed = $dbc->prepare('SELECT * FROM yeahs WHERE yeahs.yeah_post = ? AND yeahs.type = ? AND yeahs.yeah_by = ?');
    $check_yeahed->bind_param('sss', $post, $type, $user_id);
    $check_yeahed->execute();
    $yeahed_result = $check_yeahed->get_result();

    if (!$yeahed_result->num_rows == 0) {
        return true;
    } else {
        return false;
    }
}

function checkPostExists($post)
{
    global $dbc;

    $check_post = $dbc->prepare('SELECT * FROM posts WHERE id = ? LIMIT 1');
    $check_post->bind_param('s', $post);
    $check_post->execute();
    $post_result = $check_post->get_result();

    if (!$post_result->num_rows == 0) {
        return true;
    } else {
        return false;
    }
}

function checkReplyExists($reply)
{
    global $dbc;

    $check_post = $dbc->prepare('SELECT * FROM replies WHERE reply_id = ? LIMIT 1');
    $check_post->bind_param('s', $reply);
    $check_post->execute();
    $post_result = $check_post->get_result();

    if (!$post_result->num_rows == 0) {
        return true;
    } else {
        return false;
    }
}
function printDiscussion($discussion, $user_id)
{
    global $dbc;
    if (!empty($_SESSION['signed_in'])) {
        $get_discussion = $dbc->prepare('SELECT * FROM discussions');
        $get_discussion->bind_param('i', $discussion, $user_id);
        $get_discussion->execute();
        $discussion_result = $get_discussion->get_result();
        $discussion = $discussion_result->fetch_assoc();

    echo '<div id="post-AYMHAAACAAADVHlCwajYmg" data-href="/discussions/'.$discussion['id'].'" class="post post-subtype-topic trigger" tabindex="0">
    <div class="body">
    <div class="post-content"><a class="test-post-topic-category post-tag post-topic-category symbol" href="/titles/'.$discussion['topic_title'].'">Discussion</a>
     <p class="post-content-text topic-title test-topic-title">'.$discussion['topic_text'].'</p>
      <a href="/users/'.$discussion['user_name'].'/posts" class="icon-container" data-pjax="#body"><img src="'. printFace($discussion['user_face'], $discussion['feeling_id']) .'" class="icon"></a>
      <div class="user-container">
        <p class="user-name"><a href="/users/'.$discussion['user_name'].'/posts" data-pjax="#body">'.$discussion['nickname'].'</a></p>
          <p class="timestamp-container"><a class="timestamp" href="/posts/'.$discussion['id'].'">'.humanTiming(strtotime($discussion['date_time'])).'</a></p>
          </div>
          <div class="post-meta"><div class="js-topic-answer-accepting-status test-topic-answer-accepting-status accepting" data-test-accepting-status="1">
  <span class="accepting">Open</span>
</div></div></div>';
    }
}
function checkDiscussionCreator($discussion, $user_id)
{
    global $dbc;

    $check_discussed = $dbc->prepare('SELECT * FROM discussions WHERE discussions.id = ? AND discussions.topic_by_id = ? LIMIT 1');
    $check_discussed->bind_param('ss', $discussion, $user_id);
    $check_discussed->execute();
    $discussed_result = $check_discussed->get_result();

    if (!$discussed_result->num_rows == 0) {
        return true;
    } else {
        return false;
    }
}
function checkDiscussionExists($discussion)
{
    global $dbc;

    $check_discussion = $dbc->prepare('SELECT * FROM discussions WHERE id = ? LIMIT 1');
    $check_discussion->bind_param('s', $discussion);
    $check_discussion->execute();
    $discussion_result = $check_discussion->get_result();

    if (!$discussion_result->num_rows == 0) {
        return true;
    } else {
        return false;
    }
}
function printTitleInfo($title)
{
    echo '<li id="community-6437256809106444479" class="trigger test-community-list-item" data-href="/titles/'. $title['title_id'] .'" tabindex="0">
      <img src="'. $title['title_banner'] .'" class="community-list-cover">
      <div class="community-list-body">
        <span class="icon-container"><img src="'. $title['title_icon'] .'" class="icon"></span>
        <div class="body">
          <a class="title" href="/titles/'. $title['title_id'] .'" tabindex="-1">'. htmlspecialchars($title['title_name'], ENT_QUOTES) .'</a>';

    switch ($title['type']) {
        case 1:
            echo '<span class="platform-tag"><img src="/assets/img/platform-tag-wiiu.png"></span>';
            break;
        case 2:
            echo '<span class="platform-tag"><img src="/assets/img/platform-tag-3ds.png"></span>';
            break;
        case 3:
            echo '<span class="platform-tag"><img src="/assets/img/platform-tag-wiiu-3ds.png"></span>';
            break;
    }

    echo '<span class="text">';

    switch ($title['type']) {
        case 0:
            echo 'General Community';
            break;
        case 1:
            echo 'Wii U Games';
            break;
        case 2:
            echo '3DS Games';
            break;
        case 3:
            echo 'Wii U Games・3DS Games';
            break;
        case 4:
            echo 'Switch Games';
            break;
        case 6:
                echo 'Special Community';
            break;
        default:
            echo 'Special Community';
    }

    echo '</span>
    </div>
    </div>
    </li>';
}


function printReply($reply)
{
    global $dbc;

    echo '
    <a href="/users/'. $reply['user_name'] .'/" class="icon-container'. ($reply['user_level'] > 4 ? ' official-user' : '') .'">
    <img src="'. printFace($reply['user_face'], $reply['feeling_id']) .'" class="icon"></a>
    <div class="body">
    <div class="header">
    <p class="user-name"><a href="/users/'. $reply['user_name'] .'/">'. htmlspecialchars($reply['nickname'], ENT_QUOTES) .'</a></p>
    <p class="timestamp-container"><a class="timestamp">'. humanTiming(strtotime($reply['date_time'])) .'</a>
    <span class="spoiler-status"> ·Spoilers</span>
    </p>
    </div>';

    if ($reply['deleted'] == 1) {
        echo '<p class="deleted-message">
            Deleted by administrator.<br>
            Reply ID: '. $reply['reply_id'] .'
          </p>';
    }
    if ($reply['deleted'] == 0 || $reply['reply_by_id'] == $_SESSION['user_id']) {
        $reply['text'] = preg_replace('|([\w\d]*)\s?(https?://([\d\w\.-]+\.[\w\.]{2,6})[^\s\]\[\<\>]*/?)|i', '$1 <a href="$2" target="_blank" class="post-link">$2</a>', $reply['text']);

        echo '<p class="reply-content-text">'. $reply['text'] .'</p>';

        echo (!empty($reply['reply_image'])?'<div class="screenshot-container"><img src="'.$reply['reply_image'].'"></div>':'').'<div class="reply-meta">';


        $yeah_count = $dbc->prepare('SELECT COUNT(yeah_by) FROM yeahs WHERE type = "reply" AND yeah_post = ?');
        $yeah_count->bind_param('i', $reply['reply_id']);
        $yeah_count->execute();
        $result_count = $yeah_count->get_result();
        $yeah_amount = $result_count->fetch_assoc();


        $yeahs = $yeah_amount['COUNT(yeah_by)'];



        echo '<button class="yeah symbol';

        if (!empty($_SESSION['signed_in']) && checkYeahAdded($reply['reply_id'], 'reply', $_SESSION['user_id'])) {
            echo ' empathy-added';
        }

        echo '"';

        if (empty($_SESSION['signed_in']) || checkReplyCreator($reply['reply_id'], $_SESSION['user_id'])) {
            echo ' disabled ';
        }

        echo 'id="'. $reply['reply_id'] .'" data-track-label="reply"><span class="empathy-button-text">';

        if (!empty($_SESSION['signed_in']) && checkYeahAdded($reply['reply_id'], 'reply', $_SESSION['user_id'])) {
            echo 'Unyeah';
        } else {
            echo 'Yeah!';
        }

        echo '</span></button>';
 
        echo '<div class="empathy symbol"><span class="empathy-count">'. $yeahs .'</span>';
    }
    echo '</li>';
}




  

function uploadImage($filename) {
    $client_id="3b5ae0e67e15b04";
    $handle = fopen($filename, "r");
    $data = fread($handle, filesize($filename));
    $pvars = array('image' => base64_encode($data));
    $timeout = 60;
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'https://api.imgur.com/3/image.json');
    curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Client-ID ' . $client_id));
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $pvars);
    $out = curl_exec($curl);
    curl_close ($curl);
    $pms = json_decode($out,true);
    @$face=$pms['data']['link'] or $errors[] = 'Imgur upload failed';
}

function get_percentage($total, $number)
{
    if ($total>0) {
        return round($number/($total/100), 2);
    } else {
        return 0;
    }
}

function humanTiming($time)
{
    if (time() - $time >= 345600) {
        return date("m/d/Y g:i A", $time);
    }
    $time = time() - $time;
    if (strval($time) < 1) {
        $time = 1;
    }
    if ($time <= 59) {
        return 'Less than a minute ago';
    }
    $tokens = array(86400 => 'day', 3600 => 'hour', 60 => 'minute');
    foreach ($tokens as $unit => $text) {
        if ($time < $unit) {
            continue;
        }
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':''). ' ago';
    }
  function printFooter()
  {
echo '
    <div id="footer"><div id="footer-inner"><div class="link-container"><p><a href="/guide/">Code of Conduct</a></p><p><a href="https://www.paypal.me/">Donate</a></p><p id="copyright"><a href="https://nintendo.com/">Uiiverse is a non-profit revival of Nintendo and Hatena\'s Miiverse service. We are not affiliated with these companies and they deserve your business.</a><br><a href="/contact">Contact Us</a></p></div></div></div>';
  }
}
