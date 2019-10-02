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

    <?php
    } else {
    	if (isset($_POST['submit'])) {
    		$errors = array();
            
    		if ($_POST['password'] != $_POST['confirm_password']) {
    			$errors[] = 'Passwords do not match.';
    		}
    		if (empty($_POST['password'])) {
    			$errors[] = 'Password cannot be empty.';
    		}

    		if (!empty($errors)){
    			echo '<script type="text/javascript">alert("' . $errors[0] . '");</script><META HTTP-EQUIV="refresh" content="0;URL=/signup">';
    		} else {
    			$password_gen = password_hash($_POST['password'], PASSWORD_DEFAULT);
                echo '<center><br><br><br><br><br><p>Send this to a moderator on Uiiverse or at contact@uiiverse.xyz: <p>' . $password_gen . '</center>';
    		}
    	}
    }
