<?php
require '../../vendor/autoload.php';

header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Origin: http://localhost:8080');

require_once '../../model/User.php';
require_once '../../model/Like.php';
require_once '../../model/Post.php';

$method = $_SERVER['REQUEST_METHOD'];
if ($method !== 'GET') {
    echo json_encode(['code' => 400, 'message' => 'invalid request method']);
    exit;
}

if (!filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    echo json_encode(['code' => 404, 'message' => 'cant find the requested post']);
    exit;
}

try {
    $user = new User;
    $logged_user = $user->getLoggedUser();
    if (!$logged_user) {
        echo json_encode(['code' => 403, 'message' => 'unauthorized user.']);
        exit;
    }
    $post = new Post;
    $post = $post->selectById($_GET['id']);
    if (!$post) {
        echo json_encode(['code' => 404, 'message' => 'cant find the requested post']);
        exit;
    }
    $like = new Like;
    $like->user_id = $logged_user->id;
    $like->post_id = $post->id;
    $liked = $like->checkLike();
    if ($liked) {
        $liked->delete();
        echo json_encode(['code' => 200, 'message' => 'you unliked this post!']);
        exit;
    }
    $like->create();
    echo json_encode(['code' => 200, 'message' => 'you liked this post!']);
} catch (Exception $e) {
    echo json_encode(['code' => 500, 'message' => $e->getMessage()]);
}
