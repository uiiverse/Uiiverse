<?php
require_once('lib/htm.php');

if (empty($_SESSION['signed_in'])) {
	return;
}

$get_user = $dbc->prepare('SELECT * FROM users WHERE user_id = ?');
$get_user->bind_param('i', $_SESSION['user_id']);
$get_user->execute();
$user_result = $get_user->get_result();
$user = $user_result->fetch_assoc();

if (isset($_POST['title_id'])){
	$get_title = $dbc->prepare('SELECT * FROM titles WHERE title_id = ?');
	$get_title->bind_param('i', $_POST['title_id']);
	$get_title->execute();
	$title_result = $get_title->get_result();
	if ($title_result->num_rows == 0) {
		exit("ok");
	}
	$title = $title_result->fetch_array();
}

if (!(($title['perm'] == 1 && $user['user_level'] > 1) || $title['perm'] == NULL)) {
	return;
}

if (($title['perm'] == 1) && ($title['title_by'] !== $_SESSION['user_id'])) {
	return;
}

if ($_SERVER['REQUEST_METHOD'] != 'POST'){
	
	echo '<form id="post-form" method="post" action="/discussionText.php" enctype="multipart/form-data">
		<div class="post-count-container">
			<input type="hidden" name="title_id" value="'. $title['title_id'] .'">
		</div>';

	if (!strpos($user['user_face'], "imgur") && !strpos($user['user_face'], "cloudinary")) { 
		
		?>
		<div class="feeling-selector js-feeling-selector test-feeling-selector">
			<label class="symbol feeling-button feeling-button-normal checked">
				<input type="radio" name="feeling_id" value="0" checked="">
				<span class="symbol-label">normal</span>
			</label>
			<label class="symbol feeling-button feeling-button-happy">
				<input type="radio" name="feeling_id" value="1">
				<span class="symbol-label">happy</span>
			</label>
			<label class="symbol feeling-button feeling-button-like">
				<input type="radio" name="feeling_id" value="2">
				<span class="symbol-label">like</span>
			</label>
			<label class="symbol feeling-button feeling-button-surprised">
				<input type="radio" name="feeling_id" value="3">
				<span class="symbol-label">surprised</span>
			</label>
			<label class="symbol feeling-button feeling-button-frustrated">
				<input type="radio" name="feeling_id" value="4">
				<span class="symbol-label">frustrated</span>
			</label>
			<label class="symbol feeling-button feeling-button-puzzled">
				<input type="radio" name="feeling_id" value="5">
				<span class="symbol-label">puzzled</span>
			</label>
		</div>
		<?php

	}
	
	?>
		<div class="textarea-container">
			<textarea name="text_data" class="textarea-text textarea" maxlength="800" placeholder="Compartir tus pensamientos en una publicación a esta comunidad."></textarea>
		</div>
<div class="post-form-topic">
                            <p>Discussion Type</p>
                            <select class="post-form-topic-select" name="topic">
                                <option value="0" selected="">Discusión Abierta</option>
                                <option value="1">Buscando un Compañero de Equipo</option>
                                <option value="2">Pista</option>
                                <option value="3">Pregunta</option>
                                <option value="4">Buscando un Oponente</option>
                                <option value="5">Torneo</option>
                                <option value="6">Evento</option>
                            </select>
                        </div>
		<div class="form-buttons">
			<input type="submit" name="submit" class="black-button post-button disabled" value="Enviar" disabled="">
		</div>
	</form>
	<?php

} else {
	$errors = array();
	$image = NULL;

	if (empty($_POST['text_data'])) {
		$errors[] = 'Por favor escriba su discusión. Publicaciones vacías cuentan como spam y están prohibidas.';
	} elseif (mb_strlen($_POST['text_data']) > 800) { 
		$errors[] = 'Discusiones no pueden ser más largas que 800 caracteres.';
	}

	if (empty($_POST['feeling_id']) || strval($_POST['feeling_id']) >= 6) {
		$_POST['feeling_id'] = 0;
	} 

	if(empty($errors)){
		$id = mt_rand(0, 99999999);
		$discussion_text = $dbc->prepare('INSERT INTO discussions (id, discussion_by_id, discussion_title, feeling_id, text, discussion_image) VALUES (?, ?, ?, ?, ?, ?)');
		$discussion_text->bind_param('iiiiss', $id, $_SESSION['user_id'], $title['title_id'], $_POST['feeling_id'], $_POST['text_data'], $image);
		$discussion_text->execute();

		$get_discussions = $dbc->prepare('SELECT * FROM discussions INNER JOIN users ON user_id = discussion_by_id WHERE id = ?');
		$get_discussions->bind_param('i', $id);
		$get_discussions->execute();
		$discussion_result = $get_discussions->get_result();
		$discussion = $discussions_result->fetch_array();

		echo '<div class="post trigger" data-href="/discussions/'. $discussion['id'] .'" style="display: none;">';
		printDiscussion($discussion, 0);
	} else {
		http_response_code(201);
		echo '<script type="text/javascript">popup("Error", "'. $errors[0] .'");</script>';
	}
}