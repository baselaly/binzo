<?php
header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Origin: http://localhost:8080');

require_once '../../model/User.php';
require_once '../../model/Comment.php';
require_once '../../model/Post.php';

$method = $_SERVER['REQUEST_METHOD'];
if ($method !== 'GET') {
    echo json_encode(['code' => 400, 'message' => 'invalid request method.']);
    exit;
}

if (!filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    echo json_encode(['code' => 404, 'message' => 'cant find the requested post.']);
    exit;
}

try {
    $user = new User;
    $logged_user = $user->getLoggedUser();
    if (!$logged_user) {
        echo json_encode(['code' => 403, 'message' => 'unauthorized user.']);
        exit;
    }
    $comment = new Comment;
    $comment = $comment->selectById($_GET['id']);
    if (!$comment) {
        echo json_encode(['code' => 404, 'message' => 'cant find the requested comment.']);
        exit;
    }
    if ($comment->user_id !== $logged_user->id) {
        echo json_encode(['code' => 403, 'message' => 'unauthorized to delete this comment.']);
        exit;
    }
    $comment->delete();
    echo json_encode(['code' => 200, 'message' => 'your comment has been deleted.']);
} catch (Exception $e) {
    echo json_encode(['code' => 500, 'message' => $e->getMessage()]);
}
