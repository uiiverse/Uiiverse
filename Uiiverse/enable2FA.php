<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
require_once('lib/2fa.php');
require_once('lib/htm.php');
require_once('lib/htmUsers.php');
if(empty($_SESSION['signed_in'])){
	$tabTitle = 'Uiiverse';
	printHeader('');
	echo '<div class="warning-content warning-content-forward"><div><strong>Welcome to Uiiverse!</strong><p>You must sign in to view this page.</p>
    <a class="button" href="/">Uiiverse</a></div></div>';
} else {
	session_start();
	if($_SERVER['REQUEST_METHOD'] != 'POST'){
		$get_user = $dbc->prepare('SELECT * FROM users INNER JOIN profiles ON profiles.user_id = users.user_id WHERE users.user_id = ? LIMIT 1');
		$get_user->bind_param('i', $_SESSION['user_id']);
		$get_user->execute();;
		$user_result = $get_user->get_result();
		$user = $user_result->fetch_assoc();
		$secret = $tfa->createSecret();
		$_SESSION['secret'] = $secret;
		$tabTitle = 'Uiiverse - Habilitar Autenticación de Dos Factores';
		printHeader('');
		echo '<div class="main-column"><div class="post-list-outline"><h2 class="label">Habilitar Autenticación de Dos Factores</h2><ul class="settings-list"><p>A2F le permite agregar una segunda capa de seguridad a su cuenta de Uiiverse. Deberá instalar la aplicación Google Authenticator o Authy en su teléfono. Escanée el siguiente código QR:</p><img src='. $tfa->getQRCodeGoogleUrl($user['user_name'], $secret) .'><p>o use el siguiente código secreto: ' . $secret . '</p><form action="/enable-2fa" method="POST"><p>Ingrese el código generado.</p><input type="text" name="code"><input type="submit" class="black-button" value="Enviar"></form></ul>';
	} else {
			$result = $tfa->verifyCode($_SESSION['secret'], $_POST['code']);
			if ($result == TRUE) {
	    		$user_change = $dbc->prepare('UPDATE users SET 2fa_enabled = 1, 2fa_secret = ? WHERE users.user_id = ?');
	    		$user_change->bind_param('ss', $_SESSION['secret'], $_SESSION['user_id']);
	    		$user_change->execute();
	    		exit('La Autenticación de Dos Factores ha sido activada exitósamente. Redirigiendo a Uiiverse. <META HTTP-EQUIV="refresh" content="0;URL=/">');
			} else {
				echo '<script type="text/javascript">alert("El código que ha ingresado es inválido. Por favor inténtelo nuevamente.");</script><META HTTP-EQUIV="refresh" content="0;URL=/login">';
			}
		}
}?>

