<?php
require_once('lib/htm.php');

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

        <title>Create an account</title>
        <div class="hb-contents-wrapper"><div class="hb-container hb-l-inside">
            <h2>Sign Up</h2>
            <p>Create a User ID for Uiiverse.</p>
        </div>

        <form method="post" enctype="multipart/form-data">
            <div class="hb-container hb-l-inside-half hb-mg-top-none">              

                <div class="auth-input-double">               
                    <label>
                        <input type="text" name="username" maxlength="16" title="Uiiverse ID" placeholder="User ID" value="">
                    </label>
					<label>
                        <input type="email" name="email" title="Email" placeholder="Email" value="">
                    </label>
                    <label>
                        <input type="password" name="password" maxlength="16" title="Password" placeholder="Password">
                    </label>
                    <label>
                        <input type="password" name="confirm_password" maxlength="16" title="Password" placeholder="Confirm Password">
                    </label>
                    <label>
                        <input type="text" name="name" maxlength="16" title="Name" placeholder="Name" value="">
                    </label>
                    <input type="text" name="face" placeholder="Nintendo Network ID / Imgur Image URL">
                <input type="submit" name="submit" class="hb-btn hb-is-decide" style="margin-top: 4px;" id="btn_text" value="Sign Up">
            </form>
        </div>

    <?php
    } else {
    	if (isset($_POST['submit'])) {
    		$errors = array();
			if (isset($_POST['face'])) {
				if (strpos($_POST['face'], "i.imgur")){
					$face = $_POST['face'];
				} else {
					$ch = curl_init();
					curl_setopt_array($ch, array(
						CURLOPT_URL => 'https://ariankordi.pf2m.com/seth/'. $_POST['face'],
						CURLOPT_HEADER => true,
						CURLOPT_RETURNTRANSFER => true));
					$response = curl_exec($ch);


					$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
					if($httpCode == 404) {
						$errors[] = 'Invalid NNID.';
					} else {
						$body = substr($response, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
						$dom = new DOMDocument;
						$dom->loadHTML($body);
						$face = $body;
					}

					if(!empty($errors)){
						exit($errors[0]);
					}
				}
			}
$forbidden = array("faggot", "nigga", "whore", "nigger", "fucker", "fuck", "fucking", "fucka", "zipperhead", "sex", "cunt", "anal", "dick", "cock", "bitch", "kike", "towelhead", "gook", "spic", "kill yourself", "penis", "vagina", "pussy", "kys", "suicide", "sexy", "niggas");
	function match($wrongwords, $string) {
	    foreach($wrongwords as $wrongword){
	        if (strpos($string, $wrongword) !== false) {
	            return true;
	        }
	    }
	    return false;
	}

    		if (strlen($_POST['username']) > 16) {
    			$errors[] = 'User ID connot be longer than 16 characters.';
    		}
    		if (empty($_POST['username'])){
    			$errors[] = 'User ID cannot be empty.';
    		}
    		if(preg_match("/([%\$#\*\/\ ]+)/", $_POST['username'])){
    			$errors[] = 'User ID cannot contain special characters or spaces.';
    		} elseif (match($forbidden, strtolower(trim($_POST['username'])))) {
		$errors[] = 'Your User ID contains a word that is not allowed on Uiiverse.';
	}

    		$search_user = $dbc->prepare('SELECT * FROM users WHERE users.user_name = ? LIMIT 1');
    		$search_user->bind_param('s', $_POST['username']);
    		$search_user->execute();
    		$user_result = $search_user->get_result();

    		if ($user_result->num_rows > 0) {
    			$errors[] = 'User ID already exists.';
    		}

    		if ($_POST['password'] != $_POST['confirm_password']) {
    			$errors[] = 'Passwords do not match.';
    		}
    		if (empty($_POST['password'])) {
    			$errors[] = 'Password cannot be empty.';
			}
			if (empty($_POST['email'])) {
				$errors[] = 'Email cannot be empty.';
			}
			if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
				$errors[] = 'Email is invalid.';
			}
    		if (strlen($_POST['name']) > 16){
    			$errors[] = 'Name connot be longer than 16 characters.';
    		}
    		if (empty($_POST['name'])){
    			$errors[] = 'Name cannot be empty.';
    		} elseif (match($forbidden, strtolower(trim($_POST['name'])))) {
		$errors[] = 'Your name contains a word that is not allowed on Uiiverse.';
	}


    		if (!empty($errors)){
    			echo '<script type="text/javascript">alert("' . $errors[0] . '");</script><META HTTP-EQUIV="refresh" content="0;URL=/signup">';
    		} else {

    			$username = htmlspecialchars($_POST['username'], ENT_QUOTES);
				$name = $_POST['name'];
				$email = $_POST['email'];

				$password_gen = password_hash($_POST['password'], PASSWORD_DEFAULT);
				
				$activation_code = md5($email.time());

    			$new_user = $dbc->prepare('INSERT INTO users (user_name, user_pass, nickname, user_face, date_created, ip, user_level, email, activation_code) VALUES (?,?,?,?,NOW(),?,-2,?,?)');
    			$new_user->bind_param('sssssss', $username, $password_gen, $name, $face, $_SERVER['REMOTE_ADDR'], $email, $activation_code);
    			$new_user->execute();

    			$get_user = $dbc->prepare('SELECT user_id FROM users WHERE user_name = ? LIMIT 1');
    			$get_user->bind_param('s', $username);
    			$get_user->execute();
    			$user_result = $get_user->get_result();

    			if ($user_result->num_rows == 0){
    				printHeader();
    				exit('<br>There was an error creating your account please try again.');
    			} else {

    				$user = $user_result->fetch_assoc();

    				$new_profile = $dbc->prepare('INSERT INTO profiles (user_id) VALUES (?)');
    				$new_profile->bind_param('i', $user['user_id']);
    				$new_profile->execute();
					
					$to = $email;
					$subject = "Activate your Uiiverse account, ". $name ."!";
					$header = "From: no-reply@uiiverse.xyz \r\n";
					$header .= "MIME-Version: 1.0\r\n";
        			$header .= "Content-type: text/html\r\n";
					$body = "<img src='https://i.ibb.co/dMPvqk9/logo.png' alt='Uiiverse' width='165' height='35'><br>
					Hey ". $name ."!<br>
					It seems you have recently created an account for Uiiverse. Before using your account though, you need to activate it.<br>
					To do so, just <a href='https://uiiverse.xyz/activate/". $activation_code ."'>click this link</a> or go to the next URL: https://uiiverse.xyz/activate/". $activation_code ."<br>
					<br>
					Have a great day!<br>
					<br>
					The Uiiverse Team<br>
					https://uiiverse.xyz/<br>
					contact@uiiverse.xyz<br>
					<br>
					<small>All emails sent by this address are automatically generated. Don't reply to any of these emails or email this address, since none of them are going to be replied to.</small>";
					mail($to,$subject,$body,$header);
					$_SESSION['signed_in'] = true;
    				$_SESSION['user_id'] = $user['user_id'];
    				echo '<META HTTP-EQUIV="refresh" content="0;URL=/">';
    			}
    		}
    	}
    }
}
