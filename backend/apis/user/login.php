<?php
header('content-type:application/json');
header('Access-Control-Allow-Origin: http://localhost:8080');

require_once '../../config/Csrf_protection.php';
require_once '../../model/User.php';
require_once '../../config/Validation.php';

$method = $_SERVER['REQUEST_METHOD'];
if ($method !== 'POST') {
    echo json_encode(['code' => 400, 'message' => 'invalid request method']);
    exit;
}

try {
    CSRF::check_csrf_token();
} catch (Exception $e) {
    echo json_encode(['code' => 419, 'message' => $e->getMessage()]);
    exit;
}

$validator = new Validator;

$required_errors = $validator->checkRequired(['email', 'password']);

if (count($required_errors) > 0) {
    echo json_encode(['code' => 422, 'message' => $required_errors[0]]);
    exit;
}

$email = $validator->sanitize_input($_POST['email'], 'email');
$password = $validator->sanitize_input($_POST['password'], 'string');

try {
    $user = new User;
    $logged_user = $user->login(['email' => $email, 'password' => $password]);

    if (!$logged_user) {
        echo json_encode(['code' => 401, 'message' => 'wrong email or password']);
        exit;
    }
    if ($logged_user->status === 0) {
        echo json_encode(['code' => 401, 'message' => 'please verify your email.']);
        exit;
    }

    echo json_encode(['code' => 200, 'token' => $logged_user->jwt_token]);

} catch (Exception $e) {
    echo json_encode(['code' => 500, 'message' => $e->getMessage()]);
}
