<?php
require '../../vendor/autoload.php';

header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Origin: http://localhost:8080');
header('Access-Control-Allow-Headers: Authorization');

require_once '../../model/User.php';
require_once '../../model/Post.php';
require_once '../../model/PostImage.php';
require_once '../../model/Like.php';
require_once '../../model/Comment.php';

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

    $user = new User;
    $post_owner = $user->selectById($post->user_id);
    if (!$post_owner) {
        echo json_encode(['code' => 404, 'message' => 'cant find the post owner']);
        exit;
    }
    $post->owner_image = 'http://localhost/binzo/backend/uploads/users/' . $post_owner->image;
    $post->owner_name = $post_owner->first_name . ' ' . $post_owner->last_name;
    $post->user_id==$logged_user->id?$post->owner=true:$post->owner=false;
    $post_image = new PostImage;
    $post_image = $post_image->getPostImage($post->id);
    if ($post_image) {
        $post->image = 'http://localhost/binzo/backend/uploads/posts/' . $post_image->image;
    } else {
        $post->image = null;
    }
    $like = new Like;
    $post->likes_count = $like->getLikesCount($post->id);
    $like->post_id = $post->id;
    $like->user_id = $logged_user->id;
    $liked = $like->checkLike();
    if (!$liked) {
        $post->liked = false;
    } else {
        $post->liked = true;
    }
    $comment = new Comment;
    $post->comments_count = $comment->getCommentsCount($post->id);
    $date = date_create($post->created_at);
    $post->created_at = date_format($date, 'Y-m-d g:iA');
    echo json_encode(['code' => 200, 'post' => $post]);
} catch (Exception $e) {
    echo json_encode(['code' => 500, 'message' => $e->getMessage()]);
}
