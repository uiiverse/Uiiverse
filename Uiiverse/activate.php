<?
require_once('lib/htm.php');
if (isset($code)) {
    $find_user = $dbc->prepare('SELECT * FROM users WHERE activation_code = ? LIMIT 1');
    $find_user->bind_param('s', $code);
    $find_user->execute();
    $user_result = $find_user->get_result();
    $user = $user_result->fetch_assoc();

    if ($user_result->num_rows == 0) {
        echo('El código que ha ingresado es inválido. Por favor inténtelo de nuevo con otro código.');
    } elseif ($user['user_level'] !== -2) {
        echo('El usuario no necesita activación.');
    } else {
        $activate_user = $dbc->prepare('UPDATE users SET user_level=0 WHERE user_id = ?');
        $activate_user->bind_param('s', $user['user_id']);
        $activate_user->execute();
        echo('<META HTTP-EQUIV="refresh" content="0;URL=/">El usuario ha sido exitosamente activado. Redirigiendo a Uiiverse...');
    }
} else {
    echo('No ingresó un código de activación. Por favor ingrese uno antes de proceder.');
}
?>