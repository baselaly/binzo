<?php
require '../../vendor/autoload.php';

header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Origin: http://localhost:8080');
header('Access-Control-Allow-Headers: Authorization');

require_once '../../model/User.php';
require_once '../../model/Follower.php';

$method = $_SERVER['REQUEST_METHOD'];
if ($method !== 'GET') {
    echo json_encode(['code' => 400, 'message' => 'invalid request method']);
    exit;
}

try {
    $user = new User;
    $follow = new Follower;
    $logged_user = $user->getLoggedUser();
    if (!$logged_user) {
        echo json_encode(['code' => 403, 'message' => 'unauthorized user.']);
        exit;
    }

    $users = $user->search($_GET['q']);

    foreach ($users as $user){
        unset($user->password);
        $statistics = $follow->getUserStatistics($user->id);
        $user->image = 'http://localhost/binzo/backend/uploads/users/' . $user->image;
        $user->full_name=$user->first_name.' '.$user->last_name;
        $user->statistics = $statistics;
        $follow->user_id = $user->id;
        $follow->follower_id = $logged_user->id;
        $follower = $follow->checkFollower();
        $user->follow= $follower ? false : true;
    }

    echo json_encode(['code' => 200, 'users' => $users]);
} catch (Exception $e) {
    echo json_encode(['code' => 500, 'message' => $e->getMessage()]);
}