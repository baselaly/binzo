<?php
require '../../vendor/autoload.php';

header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Origin: http://localhost:8080');
header('Access-Control-Allow-Headers:Authorization');

require_once '../../model/User.php';
require_once '../../model/Post.php';
require_once '../../model/PostImage.php';
require_once '../../model/Follower.php';
require_once '../../model/Like.php';
require_once '../../model/Comment.php';

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
    $user_id = $_GET['id'];
    $user = new User;
    $logged_user = $user->getLoggedUser();
    if (!$logged_user) {
        echo json_encode(['code' => 401, 'message' => 'unauthorized user.']);
        exit;
    }
    $user = $user->selectById($user_id);
    if (!$user) {
        echo json_encode(['code' => 404, 'message' => 'cant find the requested user']);
        exit;
    }
    unset($user->password);
    $user->image = 'http://localhost/binzo/backend/uploads/users/' . $user->image;
    $follow = new Follower;
    $statistics = $follow->getUserStatistics($user->id);
    $post = new Post;
    $posts = $post->getUserPosts($user->id);
    $post_image = new PostImage;
    foreach ($posts['data'] as $post) {
        $post->user_image = $user->image;
        $post->fullname=$user->first_name.' '.$user->last_name;
        $PostImage = $post_image->getPostImage($post->id);
        if ($PostImage) {
            $post->image = 'http://localhost/binzo/backend/uploads/posts/' . $PostImage->image;
        } else {
            $post->image = null;
        }

        $post->deletable = $post->user_id == $logged_user->id ? true : false;

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
    }
    echo json_encode(['code' => 200, 'user' => $user, 'posts' => $posts, 'statistics' => $statistics]);
} catch (Exception $e) {
    echo json_encode(['code' => 500, 'message' => $e->getMessage()]);
}
