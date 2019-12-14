<?php

require_once 'AltoRouter.php';
$router = new AltoRouter();

$router->addRoutes(array(

    array('GET|POST', '/', 'titleList.php', 'Title-list'),
    array('GET|POST', '/titles/[i:title_id]', 'list.php', 'Post-list'),
    array('GET|POST', '/titles/[i:title_id]/popular', 'popularPosts.php', 'Popular-posts'),
    array('GET|POST', '/posts/[i:id]', 'posts.php', 'Post-view'),
    array('GET|POST', '/replies/[i:id]', 'replies.php', 'Reply-view'),
    array('GET|POST', '/login', 'login.php', 'Login'),
    array('GET|POST', '/signup', 'signup.php', 'Signup'),
    array('GET|POST', '/logout', 'logout.php', 'Logout'),
    array('GET|POST', '/2fa', '2fa.php', '2FA'),
    array('GET|POST', '/enable-2fa', 'enable2FA.php', 'Enable 2FA'),
    array('GET|POST', '/disable-2fa', 'disable2FA.php', 'Disable 2FA'),
    array('GET|POST', '/change-email', 'changeEmail.php', 'Change-email'),
    array('GET|POST', '/settings/profile', 'settings.php', 'Settings'),
    array('GET|POST', '/guide/terms', 'terms.php', 'Terms'),
    array('GET|POST', '/guide/', 'rules.php', 'Rules'),
    array('GET|POST', '/guide/faq', 'faq.php', 'FAQ'),
    array('GET|POST', '/activity', 'activity.php', 'Activity-feed'),
    array('GET|POST', '/settings/account', 'account_settings.php', 'Account-settings'),
    array('GET|POST', '/settings/theme', 'theme_settings.php', 'Theme-settings'),
    array('GET|POST', '/admin_panel', 'admin/admin.php', 'Admin'),
    array('GET|POST', '/admin_panel/[*:action]', 'admin/admin.php', 'Admin-option'),
    array('GET|POST', '/activate/[*:code]', 'activate.php', 'Activate'),
    array('GET', '/users/[*:action]/posts', 'users.php', 'Users'),
    array('GET', '/users/[*:action]/yeahs', 'userYeahs.php', 'User-yeahs'),
    array('GET', '/users/[*:action]/', 'userDiary.php', 'User-profile'),
    array('GET', '/users/[*:action]/following', 'userFollowing.php', 'Following'),
    array('GET', '/users/[*:action]/followers', 'userFollowers.php', 'Followers'),
    array('GET', '/users/[*:action]/friends', 'userFriends.php', 'Friends'),
    array('GET', '/communities/favorites', 'favorites.php', 'Your-Favorites'),
    array('GET', '/my_blacklist', 'blacklist.php', 'Blocked-Users'),
    array('GET', '/users/[*:action]/favorites', 'favorites.php', 'Favorites'),
    array('GET', '/titles/search', 'searchTitles.php', 'Search-titles'),
    array('GET', '/communities/categories/official', 'allOfficialTitles.php', 'All-Official-Titles'),
    array('GET', '/communities/categories/special_all', 'allSpecialTitles.php', 'All-Special-Titles'),
    array('GET', '/communities/categories/wiiu_all', 'allWiiUTitles.php', 'All-Wii-U-Titles'),
    array('GET', '/communities/categories/switch_all', 'allSwitchTitles.php', 'All-Switch-Titles'),
    array('GET', '/communities/categories/3ds_all', 'all3DSTitles.php', 'All-3DS-Titles'),
    array('GET', '/identified_user_posts', 'verifiedPosts.php', 'Verified-posts'),
    array('GET', '/news/my_news', 'notifs.php', 'Notifs'),
    array('GET', '/check_update.json', 'check_update.php', 'Check-update'),
    array('GET', '/users', 'searchUsers.php', 'Search-users'),
    array('GET', '/admin_messages', 'adminMessages.php', 'Admin-messages'),
    array('POST', '/yeah', 'yeah.php', 'Yeah'),
    array('POST', '/posts/[i:id]/replies', 'postReply.php', 'Comment'),
    array('POST', '/posts/[i:id]/image.set_profile_post', 'favoritePost.php', 'Favorite'),
    array('POST', '/settings/profile_post.unset.json', 'favoritePost.php', 'Unfavorite'),
  
// Put other arrays here
array('GET|POST', '/titles/[i:title_id]/topic', 'discussion-list.php', 'Open-discussions'),
array('GET|POST', '/titles/[i:title_id]/artwork', 'drawing-list.php', 'Artwork'),
array('GET|POST', '/titles/[i:title_id]/diary', 'diary-list.php', 'Community-diary'),
array('GET|POST', '/forgot/', 'forgot.php', 'Forgot your Password?'),
array('GET|POST', '/reset/[*:code]', 'reset.php', 'Reset-code'),
array('GET|POST', '/reset/', 'reset.php', 'Reset')
));
// Match the current request
$match = $router->match(urldecode($_SERVER['REQUEST_URI']));
if ($match) {
    foreach ($match['params'] as &$param) {
        ${key($match['params'])} = $param;
    }
    require_once $match['target'];
} else {
    http_response_code(404);
    exit("<link rel='stylesheet' type='text/css' href='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css'><link rel='icon' type='image/png' href='data:image/png;base64,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'><center><br><br><br><br><br><b><h1>4... Oh, 4...</h1></b><p>This page seems to not exist. Sorry!</p><a href='https://uiiverse.xyz/'><b>« Return to Uiiverse</b></a></center>");
}
