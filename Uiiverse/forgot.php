<?php
require_once('lib/htm.php');
if($_SERVER['REQUEST_METHOD'] != 'POST'){
	?>
    <script src="/assets/js/jquery-3.2.1.min.js"></script>
    <script src="/assets/js/yeah.js"></script>
    <script src="/assets/js/pace.min.js"></script>
    <script src="/assets/js/favico.js"></script>
    <script src="https://unpkg.com/tippy.js@2.0.9/dist/tippy.all.min.js"></script>
        <meta name="viewport" content="width=device-width,minimum-scale=1, maximum-scale=1">
        <link rel="stylesheet" type="text/css" href="/assets/css/login.css">

        <title>Forgot your password?</title>
        <div class="hb-contents-wrapper"><div class="hb-container hb-l-inside">
            <h2>Forgot your password?</h2>
            <p>Send an email to reset your password.</p>
        </div>

        <form method="post" enctype="multipart/form-data">
            <div class="hb-container hb-l-inside-half hb-mg-top-none">              

                <div class="auth-input-double">               
                    <label>
                        <input type="email" name="email" title="Email" placeholder="Email">
                    </label>
                <input type="submit" name="submit" class="hb-btn hb-is-decide" style="margin-top: 4px;" id="btn_text" value="Submit">
            </form>
        </div>

    <?php
    } else {
    	if (isset($_POST['submit'])) {
            
                $errors = array();

                $search_user = $dbc->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
                $search_user->bind_param('s', $_POST['email']);
                $search_user->execute();
                $user_result = $search_user->get_result();
                $user = $user_result->fetch_assoc();
                
                if ($user_result->num_rows == 0) {
                    $errors[] = 'User doesn\'t exist.';
                }
                if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    $errors[] = 'Email is invalid.';
                }
                if (empty($_POST['email'])) {
                    $errors[] = 'Email cannot be empty.';
                }

                if (!empty($errors)){
                    echo '<script type="text/javascript">alert("' . $errors[0] . '");</script><META HTTP-EQUIV="refresh" content="0;URL=/forgot">';
                } else {
                    $email = $_POST['email'];
                    $reset_code = md5($email.time());
                    $name = $user['nickname'];
                    $user_change = $dbc->prepare('UPDATE users SET reset_code = ? WHERE users.user_id = ?');
                    $user_change->bind_param('ss', $reset_code, $user['user_id']);
                    $user_change->execute();

                    $to = $email;
                    $subject = "Reset your password";
                    $header = "From: no-reply@uiiverse.xyz \r\n";
                    $header .= "MIME-Version: 1.0\r\n";
                    $header .= "Content-type: text/html\r\n";
                    $body = "<img src='https://i.ibb.co/dMPvqk9/logo.png' alt='Uiiverse' width='165' height='35'><br>
                    Hey ". $name ."!<br>
                    It seems you have requested a password reset.<br>
                    To reset your password, just <a href='https://uiiverse.xyz/reset/". $reset_code ."'>click this link</a> or go to the next URL: https://uiiverse.xyz/reset/". $reset_code ."<br>
                    If you didn't request a password reset, you can safely ignore this email.<br>
                    <br>
                    Have a great day!<br>
                    <br>
                    The Uiiverse Team<br>
                    https://uiiverse.xyz/<br>
                    contact@uiiverse.xyz<br>
                    <br>
                    <small>All emails sent by this address are automatically generated. Don't reply to any of these emails or email this address, since none of them are going to be replied to.</small>";
                    mail($to,$subject,$body,$header);
                    echo '<center><br><br><br><br><br><p>An email has been sent to your email address. Please check your inbox or your spam folder.</center>';
                }
    	    }
    }
