<?php
require_once('lib/htm.php');
require_once('lib/htmUsers.php');

if ((isset($_GET['offset']) && is_numeric($_GET['offset'])) && isset($_GET['dateTime'])) {
	$offset = ($_GET['offset'] * 20);
	$dateTime = htmlspecialchars($_GET['dateTime']);
if (isset($_COOKIE['retro-mode'])) {
    echo '<link rel="stylesheet" type="text/css" href="/assets/css/retro/activity-settings-guide-admin_panel-identified_user_posts.css"> <link rel="stylesheet" type="text/css" href="/assets/css/retro/root.css">';
    }
	$get_posts = $dbc->prepare('SELECT posts.*, users.*, titles.* FROM posts INNER JOIN users ON user_id = post_by_id INNER JOIN titles ON title_id = post_title WHERE deleted = 0 AND date_time < ? AND (post_by_id IN (SELECT follow_to FROM follows WHERE follow_by = ?) OR post_by_id = ?) ORDER BY date_time DESC LIMIT 20 OFFSET ?');
	$get_posts->bind_param('siii', $dateTime, $_SESSION['user_id'], $_SESSION['user_id'], $offset);
	$get_posts->execute();
	$posts_result = $get_posts->get_result();
} else {
	$tabTitle = 'Uiiverse - Actividad';

	printHeader(2);

	echo '<script>var loadOnScroll=true;</script>';

	$get_user = $dbc->prepare('SELECT * FROM users WHERE user_id = ? LIMIT 1');
	$get_user->bind_param('i', $_SESSION['user_id']);
	$get_user->execute();
	$user_result = $get_user->get_result();
	$user = $user_result->fetch_assoc();
    if ($user['user_level'] == -1){
    banScreen();
    } else {
	echo '<div id="sidebar" class="general-sidebar">';
	userContent($user, "");
	sidebarSetting();
	echo '</div>'; 

	echo '<div class="main-column"><div class="headline"><h2 class="headline-text"><span class="symbol activity-headline">Actividad</span></h2><form class="search" action="/users" method="GET"><!--
	--><input type="text" name="query" title="Buscar Usuarios" placeholder="Buscar Usuarios" minlength="1" maxlength="16"><!--
	--><input type="submit" value="q" title="Buscar">
	</form></div><div id="js-main">';

	$get_posts = $dbc->prepare('SELECT posts.*, users.*, titles.* FROM posts INNER JOIN users ON user_id = post_by_id INNER JOIN titles ON title_id = post_title WHERE deleted = 0 AND (post_by_id IN (SELECT follow_to FROM follows WHERE follow_by = ?) OR post_by_id = ?) ORDER BY posts.date_time DESC LIMIT 20');
	$get_posts->bind_param('ii', $_SESSION['user_id'], $_SESSION['user_id']);
	$get_posts->execute();
	$posts_result = $get_posts->get_result();

	if ($posts_result->num_rows == 0) {
		$get_verf_user = $dbc->prepare('SELECT users.*, profiles.* FROM users INNER JOIN profiles ON profiles.user_id = users.user_id WHERE user_level > 1 ORDER BY user_level ASC LIMIT 1');
		$get_verf_user->execute();
		$verf_user_result = $get_verf_user->get_result();
		$verf_user = $verf_user_result->fetch_assoc();

		echo '<div id="activity-feed-tutorial"><p class="tleft">En tu actividad, puedes ver publicaciones de tus amigos o de gente que estás siguiendo. Para empezar, ¿Por qué nos sigues a gente con publicaciones de tu mismo interés? Puedes igualmente buscar a tus amigos usando "Buscar Usuarios" en la esquina superior derecha.<br></p>
		<img src="/assets/img/tutorial/tutorial-activity-feed.png" class="tutorial-image">
		<h3>Últimas Publicaciones por Usuarios Verificados</h3>
		<ul class="list list-content-with-icon-and-text arrow-list follow-list">
		<li class="trigger" data-href="/users/'.$verf_user['user_name'].'/posts">
		<a href="/users/'.$verf_user['user_name'].'/posts" class="icon-container official-user"><img src="'.printFace($verf_user['user_face'], 0).'" class="icon"></a>
		<div class="toggle-button"><button type="button" data-user-id="'.$verf_user['user_id'].'" class="follow-button button symbol relationship-button" data-community-id="" data-url-id="" data-track-label="user" data-title-id="" data-track-action="follow" data-track-category="follow">Follow</button>
		<button type="button" class="button follow-done-button relationship-button symbol none" disabled="">Seguir</button></div>
		<div class="body">
		<p class="title"><span class="nick-name"><a href="/users/'.$verf_user['user_name'].'/posts">'. htmlspecialchars($verf_user['nickname'], ENT_QUOTES) .'</a></span><span class="id-name">'.$verf_user['user_name'].'</span></p><p class="text">'.$verf_user['bio'].'</p></div></li></ul></div>
		<div id="activity-feed-tutorial" class="no-content">
		<p>No hay publicaciones para mostrar</p>
		</div>';

	} else {

		echo '<div class="list post-list js-post-list" data-next-page-url="/activity?offset=1&dateTime='.date("Y-m-d H:i:s").'">';
	}
}

while ($post = $posts_result->fetch_assoc()) {
	echo '<div data-href="/posts/'.$post['id'].'" class="post post-subtype-default trigger post-list-outline" tabindex="0">
	<p class="community-container">
	<a class="test-community-link" href="/titles/'.$post['post_title'].'"><img src="'.$post['title_icon'].'" class="community-icon">'.$post['title_name'].'</a></p>';

	printPost($post, 0);

	echo '</div><a href="/users/'.$post['user_name'].'/posts" class="another-posts symbol">Publicaciones de '. htmlspecialchars($post['nickname'], ENT_QUOTES) .'</a></div>';
}
}