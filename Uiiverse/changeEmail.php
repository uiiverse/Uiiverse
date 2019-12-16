<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
require_once('lib/htm.php');
require_once('lib/htmUsers.php');
if(empty($_SESSION['signed_in'])){
	$tabTitle = 'Uiiverse';
	printHeader('');
	echo '<div class="warning-content warning-content-forward"><div><strong>¡Bienvenid@ a Uiiverse!</strong><p>Tienes que iniciar sesión para ver esta página.</p>
    <a class="button" href="/">Uiiverse</a></div></div>';
} else {
	session_start();
	if($_SERVER['REQUEST_METHOD'] != 'POST'){
		$get_user = $dbc->prepare('SELECT * FROM users INNER JOIN profiles ON profiles.user_id = users.user_id WHERE users.user_id = ? LIMIT 1');
		$get_user->bind_param('i', $_SESSION['user_id']);
		$get_user->execute();;
		$user_result = $get_user->get_result();
		$user = $user_result->fetch_assoc();
		$tabTitle = 'Uiiverse - Cambiar su Email';
		printHeader('');
		echo '<div class="main-column"><div class="post-list-outline"><h2 class="label">Cambiar su Email</h2><ul class="settings-list"><p>Ésta página le permitirá cambiar su email<form action="/change-email" method="POST"><p>Ingrese su nuevo email</p><input type="text" name="email"><input type="submit" class="black-button" value="Enviar"></form></ul>';
	} else {
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                echo('El correo no es válido.<META HTTP-EQUIV="refresh" content="0;URL=/">');
            } else {
				$email = $_POST['email'];
				$activation_code = md5($email.time());
				$name = $user['users.nickname'];
	    		$user_change = $dbc->prepare('UPDATE users SET user_level=-2, email=?, activation_code=? WHERE users.user_id = ?');
	    		$user_change->bind_param('sss', $_POST['email'], $activation_code, $_SESSION['user_id']);
				$user_change->execute();
				$to = $email;
				$subject = "Active su cuenta de Uiiverse!";
				$header = "From: no-reply@uiiverse.xyz \r\n";
				$header .= "MIME-Version: 1.0\r\n";
        		$header .= "Content-type: text/html\r\n";
				$body = "<img src='https://i.ibb.co/dMPvqk9/logo.png' alt='Uiiverse' width='165' height='35'><br>
				¡Hola!<br>
				Has cambiado exitosamente tu email. Antes de que puedas usar tu cuenta con tu nuevo email eso sí, tienes que primero activarla.<br>
				Para activarla, solo haga <a href='https://es.uiiverse.xyz/activate/". $activation_code ."'>click aquí</a> or vaya a la siguiente dirección: https://es.uiiverse.xyz/activate/". $activation_code ."<br>
				<br>
				¡Que tenga un buen día!<br>
				<br>
				El equipo Uiiverse<br>
				https://es.uiiverse.xyz/<br>
				contact@uiiverse.xyz<br>
				<br>
				<small>Todos los emails enviados por esta dirección son automáticamente generados. No responda a ninguno de éstos emails o envíe un email a esta dirección, ya que no serán contestados.</small>";
				mail($to,$subject,$body,$header);
	    		exit('Su email ha sido exitosamente cambiado. Redirigiendo a Uiiverse.<META HTTP-EQUIV="refresh" content="0;URL=/">');
			} 
		}
}?>