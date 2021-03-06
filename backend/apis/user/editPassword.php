<?php
require '../../vendor/autoload.php';

header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Origin: http://localhost:8080');
header('Access-Control-Allow-Headers: content-type');

require_once '../../model/User.php';
require_once '../../config/Validation.php';

$method = $_SERVER['REQUEST_METHOD'];
if ($method !== 'POST') {
    echo json_encode(['code' => 400, 'message' => 'invalid request method']);
    exit;
}

if (empty($_POST)) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}

$validator = new Validator;

$required_errors = $validator->checkRequired($_POST, [
    'old_password', 'new_password', 'password_confirmation',
]);
if (count($required_errors) > 0) {
    echo json_encode(['code' => 422, 'message' => $required_errors[0]]);
    exit;
}

$old_password = $_POST['old_password'];
$new_password = $validator->sanitize_input($_POST['new_password'], 'string');
$password_confirmation = $validator->sanitize_input($_POST['password_confirmation'], 'string');

if ($new_password !== $password_confirmation) {
    echo json_encode(['code' => 422, 'message' => 'your password confirmations doesnt match.']);
    exit;
}

if (!$validator->max_lenght($new_password, 100) || !$validator->min_lenght($new_password, 8)) {
    echo json_encode(['code' => 422, 'message' => 'new password must be between 8 and 100 chars']);
    exit;
}

$errors = $validator->password_validate($new_password);
if (count($errors) > 0) {
    echo json_encode(['code' => 422, 'message' => $errors[0]]);
    exit;
}

try {
    $user = new User;
    $logged_user = $user->getLoggedUser();

    if (!password_verify($old_password, $logged_user->password)) {
        echo json_encode(['code' => 403, 'message' => 'unauthorzied to change your password.']);
        exit;
    }

    $logged_user->password = password_hash($new_password, PASSWORD_DEFAULT);
    $logged_user->update_password();
    echo json_encode(['code' => 200, 'message' => 'password edited successfully']);
} catch (Exception $e) {
    echo json_encode(['code' => 500, 'message' => $e->getMessage()]);
}
