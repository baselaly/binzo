<?php
header('content-type:application/json');
header('Access-Control-Allow-Origin: http://localhost:8080');

require_once '../../model/Post.php';
require_once '../../model/Comment.php';
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
    $comment = new Comment;
    $comments = $comment->getPostComments($post->id);
    $user = new User;
    foreach ($comments['data'] as $comment) {
        $user = $user->selectById($comment->user_id);
        $comment->user_image = 'http://localhost/binzo/backend/uploads/users/' . $user->image;
        $comment->user_name = $user->first_name . ' ' . $user->last_name;
        $date = date_create($comment->created_at);
        $comment->created_at = date_format($date, 'Y-m-d g:iA');
    }
    echo json_encode(['code' => 200, 'comments' => $comments]);
} catch (Exception $e) {
    echo json_encode(['code' => 500, 'message' => $e->getMessage()]);
}
