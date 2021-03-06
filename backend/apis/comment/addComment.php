<?php
require '../../vendor/autoload.php';

header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Origin: http://localhost:8080');
header('Access-Control-Allow-Headers: content-type,Authorization');

require_once '../../model/User.php';
require_once '../../model/Comment.php';
require_once '../../model/Post.php';
require_once '../../config/Validation.php';

$method = $_SERVER['REQUEST_METHOD'];
if ($method !== 'POST') {
    echo json_encode(['code' => 400, 'message' => 'invalid request method']);
    exit;
}

if (empty($_POST)) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}

try {
    $validator = new Validator;
    $required_errors = $validator->checkRequired($_POST, ['post_id', 'comment']);
    if (count($required_errors) > 0) {
        echo json_encode(['code' => 422, 'message' => $required_errors[0]]);
        exit;
    }
    $user_comment = $validator->sanitize_input($_POST['comment'], 'string');
    $post_id = $validator->sanitize_input($_POST['post_id'], 'integer');
    if (!$validator->max_lenght($user_comment, 500)) {
        echo json_encode(['code' => 422, 'message' => 'your comment must not exceed 500 characters']);
        exit;
    }
    $post = new Post;
    $post = $post->selectById($post_id);
    if (!$post) {
        echo json_encode(['code' => 404, 'message' => 'cant find the requested post']);
        exit;
    }

    $user = new User;
    $logged_user = $user->getLoggedUser();
    if (!$logged_user) {
        echo json_encode(['code' => 403, 'message' => 'unauthorized user.']);
        exit;
    }
    $comment = new Comment;
    $comment->user_id = $logged_user->id;
    $comment->post_id = $post->id;
    $comment->comment = $user_comment;
    $comment->create();
    echo json_encode(['code' => 200, 'message' => 'your comment added to this post!']);
} catch (Exception $e) {
    echo json_encode(['code' => 500, 'message' => $e->getMessage()]);
}
