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

            <title>Two Factor Authentication</title>
            <div class="hb-contents-wrapper"><div class="hb-container hb-l-inside">
                <h2>Two Factor Authentication</h2>
                <p>Enter the code on your authentication app.</p>
            </div>

            <form method="post" enctype="multipart/form-data">
                <div class="hb-container hb-l-inside-half hb-mg-top-none">              

                    <div class="auth-input-double">
                        <label>
                            <input type="text" name="code" maxlength="6" title="2FA Code" placeholder="2FA Code">
                        </label>
                    <input type="submit" name="submit" class="hb-btn hb-is-decide" style="margin-top: 4px;" id="btn_text" value="Submit">
                </form>
            </div>

        <?php
        } else {
        	if (isset($_POST['submit'])) {
        		$errors = array()
                $get_user = $dbc->prepare('SELECT * FROM users WHERE user_id = ? LIMIT 1');
                $get_user->bind_param('s', $_SESSION['user_id']);
                $get_user->execute();
                $user = $get_user->get_result();
                $result = $2fa->verifyCode($user['2fa_secret'], $_POST['code']);
                if ($result == false) {
                    $errors[] = "The auth code didn't match. Please try again.";
                }

                if (empty($errors)) {
                    echo '<div id="main-body">Redirecting to Uiiverse...';
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
}