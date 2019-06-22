<?php
require '../../vendor/autoload.php';

header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Origin: http://localhost:8080');
header('Access-Control-Allow-Headers: Authorization');

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

        $post->deletable = $post->user_id == $logged_user->id ? true : false;
        $date = date_create($post->created_at);
        $post->created_at = date_format($date, 'Y-m-d g:i A');
        $post->user_image = $logged_user->image;
        $post->fullname = $logged_user->first_name . ' ' . $logged_user->last_name; 

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
    echo json_encode(['code' => 200, 'user' => $logged_user, 'posts' => $posts, 'statistics' => $statistics]);
} catch (Exception $e) {
    echo json_encode(['code' => 500, 'message' => $e->getMessage()]);
}
