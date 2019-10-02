<?php
require_once('lib/htm.php');
printHeader(0);
if (!empty($_SESSION['signed_in'])) {

	$get_user = $dbc->prepare('SELECT * FROM users WHERE user_id = ?');
	$get_user->bind_param('i', $_SESSION['user_id']);
	$get_user->execute();
	$user_result = $get_user->get_result();
	$user = $user_result->fetch_assoc();

	if ($user['user_level'] > 0) {

		if (isset($action)){
			if ($action == 'test') {
				echo 'test';
			} elseif ($action == 'delete_post') {
				$get_post = $dbc->prepare('SELECT * FROM posts WHERE id = ? LIMIT 1');
				$get_post->bind_param('i', $_POST['post_id']);
				$get_post->execute();
				$post_result = $get_post->get_result();
				if ($post_result->num_rows == 0) {
					exit('{"success":0,"problem":"Post does not exist."}');
				} elseif ($_POST['post_violation_type'] == '') {
					exit('{"success":0,"problem":"Please specify a violation type."}');
				} elseif ($_POST['post_violation_type'] == 0 && $_POST['post_reason'] == '') {
					exit('{"success":0,"problem":"Please specify a reason for deletion."}');
				} else {
					$post = $post_result->fetch_assoc();

					if ($_POST['post_violation_type'] == 0) {
						$admin_message = $dbc->prepare('INSERT INTO admin_messages (admin_type, admin_text, admin_to, admin_by, admin_post, is_reply) VALUES (?, ?, ?, ?, ?, 0)');
						$admin_message->bind_param('isiii', $_POST['post_violation_type'], $_POST['post_reason'], $post['post_by_id'], $_SESSION['user_id'], $post['id']);
					} else {
						$admin_message = $dbc->prepare('INSERT INTO admin_messages (admin_type, admin_to, admin_by, admin_post, is_reply) VALUES (?, ?, ?, ?, 0)');
						$admin_message->bind_param('iiii', $_POST['post_violation_type'], $post['post_by_id'], $_SESSION['user_id'], $post['id']);
				    }
					$admin_message->execute();

					$get_notif = $dbc->prepare('SELECT * FROM notifs WHERE notif_type = 5 AND notif_to = ? LIMIT 1');
					$get_notif->bind_param('i', $post['post_by_id']);
					$get_notif->execute();
					$notif_result = $get_notif->get_result();
					if ($notif_result->num_rows == 0) {
						$admin_notif = $dbc->prepare('INSERT INTO notifs (notif_type, notif_to) VALUES (5, ?)');
						$admin_notif->bind_param('i', $post['post_by_id']);
						$admin_notif->execute();
					} else {
						$notif = $notif_result->fetch_assoc();

						$admin_notif = $dbc->prepare('UPDATE notifs SET notif_read = 0, notif_date = NOW() WHERE notif_id = ?');
						$admin_notif->bind_param('i', $notif['notif_id']);
						$admin_notif->execute();
					}

					$delete_post = $dbc->prepare('UPDATE posts SET deleted = 1 WHERE id = ?');
					$delete_post->bind_param('i', $post['id']);
					$delete_post->execute();
					exit('{"success":1}');
				}
			
			} elseif ($action == 'delete_reply') {
				$get_reply = $dbc->prepare('SELECT * FROM replies WHERE reply_id = ? LIMIT 1');
				$get_reply->bind_param('i', $_POST['reply_id']);
				$get_reply->execute();
				$reply_result = $get_reply->get_result();
				if ($reply_result->num_rows == 0) {
					exit('{"success":0,"problem":"Reply does not exist."}');
				} elseif ($_POST['reply_violation_type'] == '') {
					exit('{"success":0,"problem":"Please specify a violation type."}');
				} elseif ($_POST['reply_violation_type'] == 0 && $_POST['reply_reason'] == '') {
					exit('{"success":0,"problem":"Please specify a reason for deletion."}');
				} else {
					$reply = $reply_result->fetch_assoc();

					if ($_POST['reply_violation_type'] == 0) {
						$admin_message = $dbc->prepare('INSERT INTO admin_messages (admin_type, admin_text, admin_to, admin_by, admin_post, is_reply) VALUES (?, ?, ?, ?, ?, 1)');
						$admin_message->bind_param('isiii', $_POST['reply_violation_type'], $_POST['reply_reason'], $reply['reply_by_id'], $_SESSION['user_id'], $reply['reply_id']);
					} else {
						$admin_message = $dbc->prepare('INSERT INTO admin_messages (admin_type, admin_to, admin_by, admin_post, is_reply) VALUES (?, ?, ?, ?, 1)');
						$admin_message->bind_param('iiii', $_POST['reply_violation_type'], $reply['reply_by_id'], $_SESSION['user_id'], $reply['reply_id']);
				    }
					$admin_message->execute();

					$get_notif = $dbc->prepare('SELECT * FROM notifs WHERE notif_type = 5 AND notif_to = ? LIMIT 1');
					$get_notif->bind_param('i', $reply['reply_by_id']);
					$get_notif->execute();
					$notif_result = $get_notif->get_result();
					if ($notif_result->num_rows == 0) {
						$admin_notif = $dbc->prepare('INSERT INTO notifs (notif_type, notif_to) VALUES (5, ?)');
						$admin_notif->bind_param('i', $reply['reply_by_id']);
						$admin_notif->execute();
					} else {
						$notif = $notif_result->fetch_assoc();

						$admin_notif = $dbc->prepare('UPDATE notifs SET notif_read = 0, notif_date = NOW() WHERE notif_id = ?');
						$admin_notif->bind_param('i', $notif['notif_id']);
						$admin_notif->execute();
					}

					$delete_reply = $dbc->prepare('UPDATE replies SET deleted = 1 WHERE reply_id = ?');
					$delete_reply->bind_param('i', $reply['reply_id']);
					$delete_reply->execute();
					exit('{"success":1}');
				}
			} elseif ($action == 'verify_user') {
				$get_user = $dbc->prepare('SELECT * FROM users WHERE user_name = ? LIMIT 1');
				$get_user->bind_param('s', $_POST['user_id']);
				$get_user->execute();
				$user_result = $get_user->get_result();
				if ($user_result->num_rows == 0){
					exit('{"success":0, "problem":"User does not exist."}');
				} else {
					$user = $user_result->fetch_assoc();
					if ($user['user_level'] >= 0) {
						exit('{"success":0,"problem":"You can\'t verify a verified user."}');
					} else {
						$verify_user = $dbc->prepare('UPDATE users SET user_level = 0 WHERE users.user_name = ?');
						$verify_user->bind_param('s', $_POST['user_id']);
						$verify_user->execute();
						exit('{"success":1}');
					}
			    } 
			} elseif ($action == 'ban_user') {
				$get_user = $dbc->prepare('SELECT * FROM users WHERE user_name = ? LIMIT 1');
				$get_user->bind_param('s', $_POST['user_id']);
				$get_user->execute();
				$user_result = $get_user->get_result();
				if ($user_result->num_rows == 0){
					exit('{"success":0, "problem":"User does not exist."}');
				} else {
					$user = $user_result->fetch_assoc();
					if ($user['user_level'] > 0) {
						exit('{"success":0,"problem":"You can\'t ban an admin."}');
					} else {
						$ban_user = $dbc->prepare('UPDATE users SET user_level = -1 WHERE users.user_name = ?');
						$ban_user->bind_param('s', $_POST['user_id']);
						$ban_user->execute();
						exit('{"success":1}');
					}
				}
			} elseif ($action == 'password_change') {
				$get_user = $dbc->prepare('SELECT * FROM users WHERE user_name = ? LIMIT 1');
				$get_user->bind_param('s', $_POST['user_id']);
				$get_user->execute();
				$user_result = $get_user->get_result();
				if ($user_result->num_rows == 0){
					exit('{"success":0, "problem":"User does not exist."}');
				} else {
					$user = $user_result->fetch_assoc();
					if ($user['user_level'] > 0) {
						exit('{"success":0,"problem":"You can\'t change an admin password."}');
					} else {
						$password_change = $dbc->prepare('UPDATE users SET user_pass = ? WHERE users.user_name = ?');
						$password_change->bind_param('ss', $_POST['password'], $_POST['user_id']);
						$password_change->execute();
						exit('{"success":1}');
					}
				}
			} else {
				header('HTTP/1.0 404 Forbidden');
			}
		} else {
			?>

		<!DOCTYPE html>

		<html lang="en">
		  <head>
		    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		      <title>Admin Panel</title>
		      <link rel="icon" type="image/png" sizes="96x96" href="/assets/img/favicon-96x96.png">
		      <link rel="stylesheet" href="../assets/css/style.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
		      <link rel="stylesheet" type="text/css" href="/admin/css/style.css">
	      </head>

	      <div class="main-column">
  <div class="post-list-outline">
    <div class="body-content" id="community-top" data-region="USA">
      <h2 class="label">Admin Panel</h2>
      <ul class="list community-list">
	      <div class="before-renewal">
        <div class="post-body">
          <p class="description">Welcome to the A M A Z I N G admin panel. Yeah, I know it doesn't look that good, but it's something.</p></div></div>
		  <div class="user-data" align="center">
      <div class="user-main-profile data-content">
        <h4><span>Verify User</span></h4>
                 <div style="color:#969696;" align="center">
                    Verifies a user on Uiiverse.</div>
                    <form id="verify_user" method="POST" action="/admin_panel/verify_user">
                      <input class="textarea" style="cursor: auto; height: auto;" type="text" name="user_id" placeholder="User ID">
                      <input type="submit" class="black-button apply-button" value="Verify">
                    </form>
                  </div>
                </div>
	     <div class="user-data" align="center">
      <div class="user-main-profile data-content">
        <h4><span>Ban User</span></h4>
                 <div style="color:#969696;" align="center">
                    Temporarily ban a user, giving them a "hidden" profile for a certain amount of time.</div>
                    <div style="color:#969696;" align="center" role="alert">
                      Note: Only the owner of the site can currently reverse bans.
                    </div>
                    <form id="ban_user" method="POST" action="/admin_panel/ban_user">
                      <input class="textarea" style="cursor: auto; height: auto;" type="text" name="user_id" placeholder="User ID">
                      <input type="submit" class="black-button apply-button" value="Ban">
                    </form>
                  </div>
				</div>
				<div class="user-data" align="center">
      <div class="user-main-profile data-content">
        <h4><span>Change User Password</span></h4>
                 <div style="color:#969696;" align="center">
                    Changes a user password.</div>
                    <form id="password_change" method="POST" action="/admin_panel/password_change">
					  <input class="textarea" style="cursor: auto; height: auto;" type="text" name="user_id" placeholder="User ID">
					  <input class="textarea" style="cursor: auto; height: auto;" type="text" name="password_hash" placeholder="Password Hash">
                      <input type="submit" class="black-button apply-button" value="Change Password">
                    </form>
                  </div>
                </div>
              <div class="user-data" align="center">
      <div class="user-main-profile data-content">
        <h4><span>Delete Post</span></h4>
        <div style="color:#969696;" align="center">
                    Pretty self-explanatory. If a post exhibits any of these violation types, delete it.</div>
                    <div style="color:#969696;" align="center" role="alert">
                      Only the violating user can view their deleted posts.
                    </div>
                    <form id="delete_post" action="/admin_panel/delete_post">
                      <input class="textarea" style="cursor: auto; height: auto;" type="text" name="post_id" placeholder="Post ID">
                    <p style="color:#969696;">
                    Violation Type
                    </p>
                      <li>
                      <select name="post_violation_type" class="form-control">
                        <option value="">Select the violation type.</option>
                        <option value="1">Spam</option>
                        <option value="2">Sexually Explicit</option>
                        <option value="3">Hateful/Bullying</option>
                        <option value="4">Advertising</option>
                        <option value="6">Violent Content</option>
                        <option value="0">Other</option>
                      </select>
                      </li>
                      <br>
                     <input type="submit" class="black-button apply-button" value="Delete">
                    </form>
                  </div>
                </div>

              <div class="user-data" align="center">
      <div class="user-main-profile data-content">
        <h4><span>Delete Comment</span></h4>
        <div style="color:#969696;" align="center">
                    Yeah, you can delete comments too.</div>
                    <div style="color:#969696;" align="center">
                    Just make sure that they are in violation of the Code of Conduct.</div>
                    <form id="delete_reply" action="/admin_panel/delete_reply">
                      <input class="textarea" style="cursor: auto; height: auto;" type="text" name="reply_id" placeholder="Comment ID">
                      <p>Violation Type</p>
                      <li>
                      <select name="reply_violation_type" class="form-control">
                        <option value="">Select the violation type.</option>
                        <option value="1">Spam</option>
                        <option value="2">Sexually Explicit</option>
                        <option value="3">Hateful/Bullying</option>
                        <option value="4">Advertising</option>
                        <option value="6">Violent Content</option>
                        <option value="0">Other</option>
                      </select>
                      </li>
                      <br>
                      <input type="submit" class="black-button apply-button" value="Delete">
                      </br>
                    </form>
                  </div>
                </div>
              </div>

            </div>

	        <script src="/assets/js/jquery-3.3.1.min.js"></script>
	        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
	        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
	        <script src="/admin/js/admin.js"></script>
	      </body>
	    </html>

	    <?php
	}

	} else {
		header('HTTP/1.0 403 Forbidden');
	}

} else {
	header('HTTP/1.0 403 Forbidden');
}