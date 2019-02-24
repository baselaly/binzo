<?php
header('content-type:application/json');
header('Access-Control-Allow-Origin: *');

require_once '../../model/User.php';

$method = $_SERVER['REQUEST_METHOD'];
if ($method !== 'GET') {
    echo json_encode(['code' => 400, 'message' => 'invalid request method']);
    exit;
}

try {
    $user = new User;
    $logged_user = $user->getLoggedUser();
    if (!$logged_user) {
        echo json_encode(['code' => 401, 'message' => 'unauthorized user.']);
        exit;
    }
    unset($logged_user->password);
    echo json_encode(['code' => 200, 'user' => $logged_user]);
} catch (Exception $e) {
    echo json_encode(['code' => 500, 'message' => $e->getMessage()]);
}
