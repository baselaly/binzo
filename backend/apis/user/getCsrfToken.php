<?php
header('content-type:application/json');
header('Access-Control-Allow-Origin: *');

require_once '../../config/Csrf_protection.php';

$method = $_SERVER['REQUEST_METHOD'];
if ($method !== 'GET') {
    echo json_encode(['code' => 400, 'message' => 'invalid request method']);
    exit;
}

$token = CSRF::get_csrf_token();

echo json_encode(['code' => 200, 'csrf_token' => $token]);
