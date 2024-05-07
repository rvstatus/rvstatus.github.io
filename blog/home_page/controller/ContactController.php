<?php
	$success = false;
	$errors = [];
	if (empty($_POST['name'])) {
		$errors['name'] = "Please enter at least 5 characters";
	} elseif (empty($_POST['email'])) {
		$errors['email'] = "Please enter a valid Email";
	} elseif (empty($_POST['subject'])) {
		$errors['subject'] = "Please enter at least 8 characters of Subject";
	} elseif (empty($_POST['message'])) {
		$errors['message'] = "Please enter a valid Email";
	} else {
		$success = true;
	}
	echo json_encode(['success' => $success, 'errors' => $errors]);exit();
?>