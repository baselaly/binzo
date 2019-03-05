<?php
require '../../vendor/autoload.php';

header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Origin: http://localhost:8080');

require_once '../../model/User.php';
require_once '../../model/Post.php';
require_once '../../model/PostImage.php';
require_once '../../model/Follower.php';

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
    $logged_user->image = 'http://localhost/binzo/backend/uploads/users/' . $logged_user->image;
    $follow = new Follower;
    $statistics = $follow->getUserStatistics($logged_user->id);
    $post = new Post;
    $posts = $post->getUserPosts($logged_user->id);
    $post_image = new PostImage;
    foreach ($posts['data'] as $post) {
        $PostImage = $post_image->getPostImage($post->id);
        if ($PostImage) {
            $post->image = 'http://localhost/binzo/backend/uploads/posts/' . $PostImage->image;
        } else {
            $post->image = null;
        }
        $date = date_create($post->created_at);
        $post->created_at = date_format($date, 'Y-m-d g:i A');
        $post->owner_image = $logged_user->image;
    }
    echo json_encode(['code' => 200, 'user' => $logged_user, 'posts' => $posts, 'statistics' => $statistics]);
} catch (Exception $e) {
    echo json_encode(['code' => 500, 'message' => $e->getMessage()]);
}
