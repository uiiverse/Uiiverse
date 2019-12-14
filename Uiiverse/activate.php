<?
require_once('lib/htm.php');
if (!isset($code)) {
    $find_user = $dbc->prepare('SELECT * FROM users WHERE activation_code = ? LIMIT 1');
    $find_user->bind_param('s', $code);
    $find_user->execute();
    $user_result = $find_user->get_result();
    $user = $user_result->fetch_assoc();

    if ($user_result->num_rows == 0) {
        echo('The code you entered is invalid. Please try again with another code.');
    } elseif ($user['user_level'] !== -2) {
        echo('User doesn\'t need activation.');
    } else {
        $activate_user = $dbc->prepare('UPDATE users SET user_level=0 WHERE user_id = ?');
        $activate_user->bind_param('s', $user['user_id']);
        $activate_user->execute();
        echo('<META HTTP-EQUIV="refresh" content="0;URL=/">User has been sucessfully activated. Redirecting to Uiiverse...');
    }
}
?>