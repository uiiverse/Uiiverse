<?php
require_once('lib/htm.php');

$get_user_plus = $dbc->prepare('SELECT * FROM users WHERE user_id = ?');
$get_user_plus->bind_param('i', $_SESSION['user_id']);
$get_user_plus->execute();
$user_result_plus = $get_user_plus->get_result();
$user_plus = $user_result_plus->fetch_assoc();

if (!empty($_SESSION['signed_in'])) {
    if ($user_plus['user_level'] >= 0) {
        if (isset($_POST['postId']) && isset($_POST['yeahType'])) {

            if ((checkPostExists($_POST['postId']) && $_POST['yeahType'] == 'post') || (checkReplyExists($_POST['postId']) && $_POST['yeahType'] == 'reply')) {
                if ($_POST['yeahType'] == 'post') {
                    if (!checkPostCreator($_POST['postId'], $_SESSION['user_id'])) {
                        $yeah = $dbc->prepare('INSERT INTO yeahs (yeah_post, type, date_time, yeah_by) VALUES (?, ?, NOW(), ?)');
                        $yeah->bind_param('isi', $_POST['postId'], $_POST['yeahType'], $_SESSION['user_id']);
                        $yeah->execute();

                        $get_user = $dbc->prepare('SELECT * FROM posts INNER JOIN profiles ON user_id = post_by_id WHERE id = ?');
                        $get_user->bind_param('i', $_POST['postId']);
                        $get_user->execute();
                        $user_result = $get_user->get_result();
                        $user = $user_result->fetch_assoc();

                        if ($user['yeah_notifs'] == 1) {
                            notify($user['post_by_id'], 0, $_POST['postId']);
                        }

                        echo 'success';
                    }
                } else {
                    if (!checkReplyCreator($_POST['postId'], $_SESSION['user_id'])) {
                        $yeah = $dbc->prepare('INSERT INTO yeahs (yeah_post, type, date_time, yeah_by) VALUES (?, ?, NOW(), ?)');
                        $yeah->bind_param('isi', $_POST['postId'], $_POST['yeahType'], $_SESSION['user_id']);
                        $yeah->execute();

                        $get_user = $dbc->prepare('SELECT * FROM replies INNER JOIN profiles ON user_id = reply_by_id WHERE reply_id = ?');
                        $get_user->bind_param('i', $_POST['postId']);
                        $get_user->execute();
                        $user_result = $get_user->get_result();
                        $user = $user_result->fetch_assoc();

                        if($user['yeah_notifs']==1){
                            notify($user['reply_by_id'], 1, $_POST['postId']);
                        }


                        echo 'success';
                    }
                }
            }
        }
    } else {
        echo 'fail';
    }
}