<?php
header('content-type:application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Authorization');

require_once '../../model/User.php';
require_once '../../model/Post.php';
require_once '../../model/PostImage.php';
// Prepend a base path if Predis is not available in your "include_path".
// require '../../predis/src/Autoloader.php';

// Predis\Autoloader::register();

// $redis = new Predis\Client([
//     'scheme' => 'tcp',
//     'host' => '127.0.0.1',
//     'port' => 6379,
// ]);

$method = $_SERVER['REQUEST_METHOD'];
if ($method !== 'GET') {
    echo json_encode(['code' => 400, 'message' => 'invalid request method']);
    exit;
}

try {
    $user = new User;
    $logged_user = $user->getLoggedUser();
    if (!$logged_user) {
        echo json_encode(['code' => 403, 'message' => 'unauthorized user.']);
        exit;
    }
    $posts = $user->getHomePosts($logged_user->id);
    $post_image = new PostImage();
    foreach ($posts as $post) {
        $post->user_image = 'http://localhost/binzo/backend/uploads/users/' . $post->image;
        $PostImage = $post_image->getPostImage($post->id);
        if ($PostImage) {
            $post->image = 'http://localhost/binzo/backend/uploads/posts/' . $PostImage->image;
        } else {
            $post->image = null;
        }
    }
    echo json_encode(['code' => 200, 'posts' => $posts]);
} catch (Exception $e) {
    echo json_encode(['code' => 500, 'message' => $e->getMessage()]);
}
