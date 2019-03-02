<?php
header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Origin: http://localhost:8080');

require_once '../../model/User.php';
require_once '../../model/Follower.php';

$method = $_SERVER['REQUEST_METHOD'];
if ($method !== 'GET') {
    echo json_encode(['code' => 400, 'message' => 'invalid request method']);
    exit;
}

if (!filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    echo json_encode(['code' => 404, 'message' => 'cant find the requested user']);
    exit;
}

try {
    $user = new User;
    $logged_user = $user->getLoggedUser();
    if (!$logged_user) {
        echo json_encode(['code' => 403, 'message' => 'unauthorized user.']);
        exit;
    }
    $user = $user->selectById($_GET['id']);
    if (!$user) {
        echo json_encode(['code' => 404, 'message' => 'cant find the requested user']);
        exit;
    }

    if ($user->id === $logged_user->id) {
        echo json_encode(['code' => 500, 'message' => 'something went wrong!']);
        exit;
    }

    $follow = new Follower;
    $follow->user_id = $user->id;
    $follow->follower_id = $logged_user->id;
    $follower = $follow->checkFollower();
    if ($follower) {
        $follower->delete();
        echo json_encode(['code' => 200, 'message' => 'you unfollowed this user!']);
        exit;
    }
    $follow->create();
    echo json_encode(['code' => 200, 'message' => 'you followed this user!']);
} catch (Exception $e) {
    echo json_encode(['code' => 500, 'message' => $e->getMessage()]);
}
