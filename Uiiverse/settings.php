<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
require_once('lib/htm.php');
require_once('lib/htmUsers.php');
if(empty($_SESSION['signed_in'])){
	$tabTitle = 'Uiiverse';
	printHeader('');
	echo '<div class="warning-content warning-content-forward"><div><strong>Welcome to Uiiverse!</strong><p>You must sign in to view this page.</p>
    <a class="button" href="/">Uiiverse</a></div></div>';
} else {
	function validateDate($date, $format = 'Y-m-d H:i:s')
	{
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
	}
	if($_SERVER['REQUEST_METHOD'] != 'POST'){
		$get_user = $dbc->prepare('SELECT * FROM users INNER JOIN profiles ON profiles.user_id = users.user_id WHERE users.user_id = ? LIMIT 1');
		$get_user->bind_param('i', $_SESSION['user_id']);
		$get_user->execute();
		$user_result = $get_user->get_result();
		$user = $user_result->fetch_assoc();
		$tabTitle = 'Uiiverse - Profile Settings';
		printHeader('');
		$post_count = $dbc->prepare('SELECT COUNT(id) FROM posts WHERE post_by_id = ?');
		$post_count->bind_param('i', $user['user_id']);
		$post_count->execute();
		$result_count = $post_count->get_result();
		$post_amount = $result_count->fetch_assoc();
		$yeah_count = $dbc->prepare('SELECT COUNT(yeah_by) FROM yeahs WHERE yeah_by = ?');
		$yeah_count->bind_param('i', $user['user_id']);
		$yeah_count->execute();
		$result_count = $yeah_count->get_result();
		$yeah_amount = $result_count->fetch_assoc();
		echo '<div id="sidebar" class="user-sidebar">';
		userContent($user, "settings");
        userSidebarSetting($user, 0);
		userInfo($user);
		echo '</div><div class="main-column"><div class="post-list-outline"><h2 class="label">Profile Settings</h2>';
		$get_prof = $dbc->prepare('SELECT * FROM profiles INNER JOIN posts ON id = fav_post AND deleted = 0 WHERE user_id = ?');
		$get_prof->bind_param('i', $user['user_id']);
		$get_prof->execute();
		$prof_result = $get_prof->get_result();
		$profile = $prof_result->fetch_assoc();
		echo '<form class="setting-form" action="" method="post" enctype="multipart/form-data">
		 <ul class="settings-list">
    <li class="setting-profile-comment">
		      <p class="settings-label">Profile Comment</p>
		      <textarea id="profile-text" class="textarea" name="profile_comment" maxlength="1000" placeholder="Write about yourself here.">'. $user['bio'] .'</textarea>
              <span class="note-attention">Attention</span>
              <p class="note">
              Please refrain from including any of the following information:
              <br>
              -Your address, telephone number, e-mail address, the name of your school, your city, your full name, or anything else that could be used to personally idenfify you.
              </br>
              <br>
              -Anything other content prohibited by the Uiiverse Code of Conduct.
              </br>
<br>
Posting such information is against the Uiiverse Code of Conduct and may result in your profile being hidden from the public.
</br>
              </p>
		    </li>
      <p class="settings-label"><label for="select_gender">What is your gender?</label></p>
      <div class="select-content">
        <div class="select-button">
          <select name="gender" id="select_gender">
         <option value="1" ';

        if($profile['gender']==1){
        	echo ' selected';
        }

        echo '>Male</option>
        <option value="2" ';

        if($profile['gender']==2){
        	echo ' selected';
        }

        echo '>Female</option>
        <option value="3" ';

        if($profile['gender']==3){
        	echo ' selected';
        }

        echo '>Not applicable</option>
          </select>
        </div>
      </div>
    </li>
      <p class="settings-label"><label for="select_country">What is your region?</label></p>
		      <div class="select-content">
		        <div class="select-button">
		          <select name="country" id="select_country">
		            <option value="1" ';

        if($profile['country']==1){
        	echo ' selected';
        }

        echo '>North America</option>
        <option value="2" ';

        if($profile['country']==2){
        	echo ' selected';
        }

        echo '>Europe/Oceania</option>
        <option value="3" ';

        if($profile['country']==3){
        	echo ' selected';
        }

        echo '>Japan</option>
        </select>
      </div>
    </div>
  </li>
      <p class="settings-label"><label for="select_game_skill">How would you describe your experience with games?</label></p>
		      <div class="select-content">
		        <div class="select-button">
		          <select name="skill" id="select_game_skill">
		            <option value="1" ';

        if($profile['skill']==1){
        	echo ' selected';
        }

        echo '>Beginner</option>
        <option value="2" ';

        if($profile['skill']==2){
        	echo ' selected';
        }

        echo '>Intermediate</option>
        <option value="3" ';

        if($profile['skill']==3){
        	echo ' selected';
        }

        echo '>Expert</option>
        </select>
      </div>
    </div>
  </li>
  <p class="settings-label"><label for="select_user_relationship_visibility">Do you want others to see who follow you/users you are following?</label></p>
		      <div class="select-content">
		        <div class="select-button">
		          <select name="user_relationship_visibility" id="select_user_relationship_visibility">
		            <option value="1" ';

        if($profile['user_relationship_visibility']==1){
        	echo ' selected';
        }

        echo '>Yes</option>
        <option value="2" ';

        if($profile['user_relationship_visibility']==2){
        	echo ' selected';
        }

        echo '>No</option>
        </select>
      </div>
    </div>
  </li>
    
		    <li>
		      <p class="settings-label">Screen Name</p>
		      <input class="textarea" placeholder="Screen Name" type="text" maxlength="16" name="name" style="cursor: auto; height: auto;" value="'. htmlspecialchars($user['nickname'], ENT_QUOTES) .'" />
		    </li>

		    <li class="setting-profile-post">
		      <p class="settings-label">Favorite Post</p>
		      <p class="note">You can set one of your posts as your favorite via the settings button of that post.</p>
		      <div class="select-content"><button id="profile-post" type="button" class="submit"></span><span class="symbol">Remove</span></button></div>
		    </li>

		    <li>
			  <div style="text-align: center;">
		        <p style="display: inline;">Nintendo Network ID:</p>
		        <input name="face-type" type="radio" value="2" checked style="margin-left: 5px; display: inline;">
				<p style="display: inline;">Custom Image:</p>
				<input name="face-type" type="radio" value="1" style="margin-left: 5px; display: inline; margin-right: 50px; margin-top: 20px;">
				</div>
			  <div class="custom-face none">
		        <p class="settings-label">Profile picture</p>
		        <input class="textarea" placeholder="Imgur image URL" type="url" name="profilePic" style="cursor: auto; height: auto;" />
		      </div>
		      <div class="nnid-face">
		        <p class="settings-label">Nintendo Network ID</p>
		        <input class="textarea" placeholder="Enter the Nintendo Network ID you used on Miiverse." type="text" maxlength="16" name="face" style="cursor: auto; height: auto;" />
		      </div>
		    </li>

		    <li>
  <li>
    <p class="settings-label"><label for="select_birthday">When is your Birthday?</label></p>
    <div class="select-content">
      <div class="select-button">
        <input type="date" name="birthday" min="2017-01-01" max="2017-12-31" value="'. (isset($user['birthday']) ? date('Y-m-d', strtotime($user['birthday'])) : '') .'" style="width: auto; max-width: 100%; min-width: 50%; font-size: 16px;">
      </div>
    </div>
    <p class="note">Only the day and month are stored.</p>
  </li>
  <li>
    <p class="settings-label">Set up Two Factor Authentication</p>
    <p class="note">Two Factor Authentication uses an app on your phone to generate a unique code every 30 seconds. This adds a second layer of security to your Uiiverse account, so malicious users are less likely to break through.</p>
    <div class="select-content">
      <div class="select-button">';
    if ($user['2fa_enabled'] == 0) {
      echo '
        <a href="/enable-2fa" class="black-button">Enable Two Factor Authentication</a>';
    } else {
        echo '<a href="/disable-2fa" class="black-button">Disable Two Factor Authentication</a>';
    }
    echo '</div>
    </div>
  </li>
</ul>
<div class="form-buttons">
<input type="submit" name="submit" class="black-button apply-button" value="Save Settings" /></div></form></div></div></div></div>';
    } else {
    	if(!empty($_POST['name'])){
    		if(strlen($_POST['name']) > 16){
    			$errors[] = 'Name cannot be longer than 16 characters';
    		}
    		if(empty($errors)){
    			$name = $_POST['name'];
    			$user_change = $dbc->prepare('UPDATE users SET nickname = ? WHERE users.user_id = ?');
    			$user_change->bind_param('ss', $name, $_SESSION['user_id']);
    			$user_change->execute();
    		}
    	}
    	if(isset($_POST['birthday']) && validateDate($_POST['birthday'], 'Y-m-d')){
    		$birthday = date('Y-m-d', strtotime($_POST['birthday']));
    		$user_change = $dbc->prepare('UPDATE profiles SET birthday = ? WHERE user_id = ?');
    		$user_change->bind_param('si', $birthday, $_SESSION['user_id']);
    		$user_change->execute();
    	}
    	if($_POST['country'] == 1 || 2 || 3 || 4 || 5 || 6 || 7){
    		$user_change = $dbc->prepare('UPDATE profiles SET country = ? WHERE user_id = ?');
    		$user_change->bind_param('ii', $_POST['country'], $_SESSION['user_id']);
    		$user_change->execute();
    	}
      if($_POST['skill'] == 1 || 2 || 3 || 4 || 5 || 6 || 7){
    		$user_change = $dbc->prepare('UPDATE profiles SET skill = ? WHERE user_id = ?');
    		$user_change->bind_param('ii', $_POST['skill'], $_SESSION['user_id']);
    		$user_change->execute();
            
    	}
        if($_POST['user_relationship_visibility'] == 1 || 2 || 3 || 4 || 5 || 6 || 7){
    		$user_change = $dbc->prepare('UPDATE profiles SET user_relationship_visibility = ? WHERE user_id = ?');
    		$user_change->bind_param('ii', $_POST['user_relationship_visibility'], $_SESSION['user_id']);
    		$user_change->execute();
            
    	}
        if($_POST['gender'] == 1 || 2 || 3 || 4 || 5 || 6 || 7){
    		$user_change = $dbc->prepare('UPDATE profiles SET gender = ? WHERE user_id = ?');
    		$user_change->bind_param('ii', $_POST['gender'], $_SESSION['user_id']);
    		$user_change->execute();
            
    	}
    	if(strlen($_POST['profile_comment']) > 400){
    		$errors[] = 'Profile Comment cannot be longer than 400 characters';
    	}
    	if(empty($errors)){
    		if(!empty($_POST['profile_comment'])){
    			$bio = htmlspecialchars($_POST['profile_comment'], ENT_QUOTES);
    			$user_change = $dbc->prepare('UPDATE profiles SET bio = ? WHERE user_id = ?');
    			$user_change->bind_param('si', $bio, $_SESSION['user_id']);
    		} else {
    			$user_change = $dbc->prepare('UPDATE profiles SET bio = NULL WHERE user_id = ?');
    			$user_change->bind_param('i', $_SESSION['user_id']);
    		}
    		$user_change->execute();
    	}
        if (isset($_POST['name-color']) && $_POST['name-color'] !== '#ffffff') {
            $user_change = $dbc->prepare('UPDATE profiles SET name_color = ? WHERE user_id = ?');
            $user_change->bind_param('si', $_POST['name-color'], $_SESSION['user_id']);
            $user_change->execute();
        } else {
            $user_change = $dbc->prepare('UPDATE profiles SET name_color = NULL WHERE user_id = ?');
            $user_change->bind_param('i', $_SESSION['user_id']);
            $user_change->execute();
        }
    	if (isset($_POST['face'])) {
    		if ($_POST['face-type'] == 2) {
    			$ch = curl_init();
    			curl_setopt_array($ch, array(
    				CURLOPT_URL => 'https://ariankordi.pf2m.com/seth/'. $_POST['face'],
    				CURLOPT_RETURNTRANSFER => true));
    			$response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                if($httpCode == 404) {
                    $errors[] = 'Invalid NNID.';
				}
    			if(empty($errors)){
    				$user_change = $dbc->prepare('UPDATE users SET user_face = ? WHERE users.user_id = ?');
    				$user_change->bind_param('si', $response, $_SESSION['user_id']);
    				$user_change->execute();
    			} else {
                    exit($errors[0]);
                }
    		} elseif ($_POST['face-type'] == 1) {
					$user_change = $dbc->prepare('UPDATE users SET user_face = ? WHERE users.user_id = ?');
    				$user_change->bind_param('si', $_POST['profilePic'], $_SESSION['user_id']);
    				$user_change->execute();

			}
		}
	echo 'Settings saved.';
	}
}
?>
