<?php
require_once('lib/htm.php');
require_once('lib/htmUsers.php');

	$tabTitle = 'Uiiverse - Theme Settings';
	printHeader('');

	$get_user = $dbc->prepare('SELECT * FROM users INNER JOIN profiles ON profiles.user_id = users.user_id WHERE users.user_id = ? LIMIT 1');
	$get_user->bind_param('i', $_SESSION['user_id']);
	$get_user->execute();
	$user_result = $get_user->get_result();
	$user = $user_result->fetch_assoc();
    $get_prof = $dbc->prepare('SELECT * FROM profiles INNER JOIN posts ON id = fav_post AND deleted = 0 WHERE user_id = ?');
		$get_prof->bind_param('i', $user['user_id']);
		$get_prof->execute();
		$prof_result = $get_prof->get_result();
		$profile = $prof_result->fetch_assoc();
	echo '<div id="sidebar" class="general-sidebar">';
	userContent($user, "");
	sidebarSetting();
	echo '</div>
	<div class="main-column">
	  <div class="post-list-outline">
		<h2 class="label">Theme Settings</h2>
			<b>    Set Theme</b>
			<center>
				</br>
				<input id="light" onclick="lightTheme()" class="black-button" type="button" value="Light">
				<input id="dark" onclick="darkTheme()" class="black-button" type="button" value="Dark">
				<input id="amoled" onclick="amoledTheme()" class="black-button" type="button" value="AMOLED">
            <input id="neon" onclick="neonTheme()" class="black-button" type="button" value="Neon">
				<input id="translucent" onclick="translucentTheme()" class="black-button" type="button" value="Translucent">
				<input id="blur" onclick="blurTheme()" class="black-button" type="button" value="Blur">
				<input id="stripe" onclick="stripeTheme()" class="black-button" type="button" value="Stripe"> 
				<input id="retro" onclick="retroTheme()" class="black-button" type="button" value="Retro WIP">
				</br>
			</center>
			<b>    Set Background</b>
			<center>
				</br>
				<input id="removeBackground" type="button" onclick="removeBackground()" class="black-button" value="Remove">
				<input id="backgroundURL" type="text" placeholder="Background URL">
				<input id="submitBackground" type="button" onclick="setBackground(backgroundURL.value)" class="black-button" value="Set">
				</br>
			</center>
			';
		if (isset($_COOKIE['stripe-mode'])) {
			echo '<b>    Set Color</b>
			<script src="/assets/js/color.js"></script>
			<center>
				<input type="color" class="black-button" value="#ffffff">
			</center>';
		} elseif (isset($_COOKIE['neon-mode'])) {
			echo '<b>    Set Color</b>
			<script src="/assets/js/color-neon.js"></script>
			<center>
				<input type="color" class="black-button" value="#ffffff">
			</center>';
		}
	  echo '
	  </div>
	</div>
  </div>
</div>';

