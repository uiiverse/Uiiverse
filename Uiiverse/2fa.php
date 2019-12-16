<?php
require_once('lib/htm.php');
require_once('lib/2fa.php');
if(empty($_SESSION['signed_in'])){
    if($_SERVER['REQUEST_METHOD'] != 'POST'){
    	?>
        <script src="/assets/js/jquery-3.2.1.min.js"></script>
        <script src="/assets/js/yeah.js"></script>
        <script src="/assets/js/pace.min.js"></script>
        <script src="/assets/js/favico.js"></script>
        <script src="https://unpkg.com/tippy.js@2.0.9/dist/tippy.all.min.js"></script>
            <meta name="viewport" content="width=device-width,minimum-scale=1, maximum-scale=1">
            <link rel="stylesheet" type="text/css" href="/assets/css/login.css">

            <title>Autenticación de dos factores</title>
            <div class="hb-contents-wrapper"><div class="hb-container hb-l-inside">
                <h2>Autenticación de dos factores</h2>
                <p>Ingrese el código en su aplicación de autenticación.</p>
            </div>

            <form method="post" enctype="multipart/form-data">
                <div class="hb-container hb-l-inside-half hb-mg-top-none">              

                    <div class="auth-input-double">
                        <label>
                            <input type="text" name="code" maxlength="6" title="Código A2F" placeholder="Código A2F">
                        </label>
                    <input type="submit" name="submit" class="hb-btn hb-is-decide" style="margin-top: 4px;" id="btn_text" value="Enviar">
                </form>
            </div>

        <?php
        } else {
        	if (isset($_POST['submit'])) {
                $errors = array();

                $get_user = $dbc->prepare('SELECT * FROM users WHERE user_id = ? LIMIT 1');
		        $get_user->bind_param('i', $_SESSION['user_id']);
		        $get_user->execute();
		        $user_result = $get_user->get_result();
		        $user = $user_result->fetch_assoc();
                
                $tfaresult = $tfa->verifyCode($user['2fa_secret'], $_POST['code']);

                if ($tfaresult == FALSE) {
                    $errors[] = "El código es incorrecto. Por favor inténtelo nuevamente.";
                }

                if (empty($errors)) {
                    echo '<div id="main-body">Redirigiendo a Uiiverse...';
                    $_SESSION['signed_in'] = true;
                    $update_ip = $dbc->prepare('UPDATE users SET ip = ? WHERE user_id = ?');
                    $update_ip->bind_param('si', $_SERVER['HTTP_CF_CONNECTING_IP'], $_SESSION['user_id']);
                    $update_ip->execute();
                    echo '<META HTTP-EQUIV="refresh" content="0;URL=/">';
                } else {
                    echo '<script type="text/javascript">alert("' . $errors[0] . '");</script><META HTTP-EQUIV="refresh" content="0;URL=/login">';
                }
        	}
        }
    }