<?php
require_once('lib/htm.php');

if(empty($_SESSION['signed_in'])){
	if($_SERVER['REQUEST_METHOD'] != 'POST'){
		?>
		<!DOCTYPE html>
        <html lang="en">
        <head>
        	<title>Sign in</title>
        	<meta name="viewport" content="width=device-width,minimum-scale=1, maximum-scale=1">
        	<link rel="stylesheet" type="text/css" href="/assets/css/login.css">
          <link rel="stylesheet" type="text/css" href="/assets/css/style-auth.css">
        </head>
        <body>
          <div class="hb-wrapper hb-l-fixed">
        <!-- .hb-wrapper     -->
        <header class="hb-header">
            <!-- header -->
          <div class="hb-header-wrapper">
                <h1>UIIVERSE NETWORK</h1>
                <div class="hb-select">
<select id="language-select" class="hb-select-language">
    <option value="en-US">English (US)</option>
     <option value="en-GB">English (UK/Australia)</option>
    <option value="es-ES">Español (España)</option>
    <option value="es-MX">Español (Latinoamérica)</option>
    <option value="fr-CA">Français (Canada)</option>
    <option value="fr-FR">Français (France)</option>
    <option value="de-DE">Deutsch</option>
    <option value="it-IT">Italiano</option>
    <option value="nl-NL">Nederlands</option>
    <option value="pt-BR">Português (Brasil)</option>
    <option value="pt-PT">Português (Portugal)</option>
    <option value="ru-RU">Русский</option>
    <option value="ja-JP">日本語</option>
</select>
</div>            </div>
            </header>
            <div class="hb-footer-wrapper">
                <div class="hb-footer-link">
                    <a class="hb-icon-external" href="../help/terms">Terms &amp; Privacy Policy</a>
			<a class="hb-icon-external" href="/forgot/">Forgot your password?</a>
                </div>                            
                <p class="hb-footer-copyright">©Uiiverse</p>
            </div>
        	<div class="hb-contents-wrapper">
        		<div class="hb-container hb-l-inside">
        			<h2>Sign In</h2>
        			<p>Please sign in with a Uiiverse User ID to proceed.</p>
        			<p>Or <a href="/signup">create an account</a>.</p>
        		</div>
        		<form method="post">
        			<div class="hb-container hb-l-inside-half hb-mg-top-none">              
        				<div class="auth-input-double">               
        					<label><input type="text" name="username" maxlength="16" title="User ID" placeholder="User ID" value=""></label>
						<label><input type="password" name="password" maxlength="16" title="Password" placeholder="Password"></label>
					</div>
					<div id="hb-checkbox">
						<input type="checkbox" name="rememberMe" title="Remember me for 30 days">
					</div>
        				<input type="submit" name="submit" class="hb-btn hb-is-decide" style="margin-top: 4px;" id="btn_text" value="Sign In">
        			</div>
                  <p align="center">
        		</form>
        	</div>
            <footer class="hb-footer">
            <!-- footer -->
            <div class="hb-footer-wrapper">
                <div class="hb-footer-link">
                    <a class="hb-icon-external" href="/guide/terms">Terms &amp; Privacy Policy</a>
 		    <a class="hb-icon-external" href="/forgot/">Forgot your password?</a>
                </div>                            
                <p class="hb-footer-copyright">©Uiiverse 2019</p>
            </div>
            <!-- //footer -->
        </footer>
          </div>
        </body>
        <?php
	} else {

		$errors = array();

		if(!empty($_SESSION['signed_in'])) {
			$errors[] = 'Already signed in';
		}

		if(empty($_POST['username'])){
			$errors[] = 'User ID cannot be empty';
		}

		if(empty($_POST['password'])){
			$errors[] = 'Password cannot be empty';
		}

		$search_user = $dbc->prepare('SELECT * FROM users WHERE user_name = ? LIMIT 1');
		$search_user->bind_param('s', $_POST['username']);
		$search_user->execute();
		$user_result = $search_user->get_result();

		if(!$user_result || $user_result->num_rows == 0) {
			$errors[] = 'User ID doesn\'t exist.';
			exit('<script type="text/javascript">alert("' . $errors[0] . '");</script><META HTTP-EQUIV="refresh" content="0;URL=/login">');
		}

		$user = $user_result->fetch_assoc();

		if(!password_verify($_POST['password'], $user['user_pass'])) {
			$errors[] = 'User ID and password don\'t match'; 
		} 

		if (empty($errors)) {
            if ($user['2fa_enabled'] == 1) {
                $_SESSION['user_id'] = $user['user_id'];
                session_start();
                echo '<META HTTP-EQUIV="refresh" content="0;URL=/2fa">';
            }
    			echo '<div id="main-body">Redirecting to Uiiverse...';
    			if ($_POST['rememberMe'] == true) {
    				$lifetime = 2419200;
    				session_set_cookie_param($lifetime);
    			}
    			$_SESSION['signed_in'] = true;
    			$_SESSION['user_id'] = $user['user_id'];
    			session_start();
    			$update_ip = $dbc->prepare('UPDATE users SET ip = ? WHERE user_id = ?');
    			$update_ip->bind_param('si', $_SERVER['HTTP_CF_CONNECTING_IP'], $_SESSION['user_id']);
    			$update_ip->execute();
    			echo '<META HTTP-EQUIV="refresh" content="0;URL=/">';
		} else {
			echo '<script type="text/javascript">alert("' . $errors[0] . '");</script><META HTTP-EQUIV="refresh" content="0;URL=/login">';
		}
	}
}
