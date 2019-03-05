<?php
require '../../vendor/autoload.php';

header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Origin: http://localhost:8080');

require_once '../../model/Post.php';
require_once '../../model/Like.php';
require_once '../../model/User.php';

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
    $post = new Post;
    $post = $post->selectById($_GET['id']);
    if (!$post) {
        echo json_encode(['code' => 404, 'message' => 'cant find the requested post']);
        exit;
    }
    $like = new Like;
    $likes = $like->getPostLikes($post->id);
    $user = new User;
    foreach ($likes['data'] as $like) {
        $user = $user->selectById($like->user_id);
        $like->user_image = 'http://localhost/binzo/backend/uploads/users/' . $user->image;
        $like->user_name = $user->first_name . ' ' . $user->last_name;
    }
    echo json_encode(['code' => 200, 'likes' => $likes]);
} catch (Exception $e) {
    echo json_encode(['code' => 500, 'message' => $e->getMessage()]);
}
