<?
require_once('lib/htm.php');
if (isset($code)) {
    $find_user = $dbc->prepare('SELECT * FROM users WHERE reset_code = ? LIMIT 1');
    $find_user->bind_param('s', $code);
    $find_user->execute();
    $user_result = $find_user->get_result();
    $user = $user_result->fetch_assoc();
    
    if ($user_result->num_rows == 0) {
        echo('The code you entered is invalid.');
    } else {
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
            <p>Enter your new password here.</p>
        </div>

        <form method="post" enctype="multipart/form-data">
            <div class="hb-container hb-l-inside-half hb-mg-top-none">              

                <div class="auth-input-double">               
                    <label>
                        <input type="password" name="password" maxlength="16" title="Password" placeholder="Password">
                    </label>
                    <label>
                        <input type="password" name="confirm_password" maxlength="16" title="Password" placeholder="Confirm Password">
                    </label>
                <input type="submit" name="submit" class="hb-btn hb-is-decide" style="margin-top: 4px;" id="btn_text" value="Submit">
            </form>
        </div>
    <?
        } else {
            if (isset($_POST['submit'])) {
                if ($_POST['password'] != $_POST['confirm_password']) {
                    echo('Passwords don\'t match.');
                } elseif (empty($_POST['password'])) {
                    echo('Password cannot be empty.');
                } elseif (empty($_POST['confirm_password'])) {
                    echo('Password confirmation cannot be empty.');
                } else {
                    $password_gen = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $email = $user['email'];
                    $name = $user['nickname'];
                    $user_change = $dbc->prepare('UPDATE users SET user_pass = ?, reset_code = "" WHERE users.user_id = ?');
                    $user_change->bind_param('ss', $password_gen, $user['user_id']);
                    $user_change->execute();
                    $to = $email;
                    $subject = "Password has been changed";
                    $header = "From: no-reply@uiiverse.xyz \r\n";
                    $header .= "MIME-Version: 1.0\r\n";
                    $header .= "Content-type: text/html\r\n";
                    $body = "<img src='https://i.ibb.co/dMPvqk9/logo.png' alt='Uiiverse' width='165' height='30'><br>
                    Hey ". $name ."!<br>
                    We're sending you this email to notify you that your password has been changed.<br>
                    If you didn't change your password, please email contact@uiiverse.xyz instantly or DM our moderators so we can give you access back into your account.<br>
                    <br>
                    Have a great day!<br>
                    <br>
                    The Uiiverse Team<br>
                    https://uiiverse.xyz/<br>
                    contact@uiiverse.xyz<br>
                    <br>
                    <small>All emails sent by this address are automatically generated. Don't reply to any of these emails or email this address, since none of them are going to be replied to.</small>";
                    mail($to,$subject,$body,$header);
                    echo('Your password has been successfully reset! You can try to login now.');
                }
            }
        }
    }
} else {
    echo('<META HTTP-EQUIV="refresh" content="0;URL=/forgot/">');
}