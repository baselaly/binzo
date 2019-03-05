<?php
require '../../vendor/autoload.php';

header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Origin: http://localhost:8080');
header('Access-Control-Allow-Headers: content-type');

require_once '../../model/User.php';
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

$validator = new Validator;

$required_errors = $validator->checkRequired($_POST, ['body']);

if (count($required_errors) > 0) {
    echo json_encode(['code' => 422, 'message' => $required_errors[0]]);
    exit;
}

if (isset($_FILES['image'])) {
    $acceptable = array('image/jpeg', 'image/jpg', 'image/gif', 'image/png');
    $size = 1024 * 2000; //4mb
    $errors = $validator->validate_image($_FILES['image'], $size, $acceptable);
    if (count($errors) > 0) {
        echo json_encode(['code' => 422, 'message' => $errors[0]]);
        exit;
    }
}

$body = $validator->sanitize_input($_POST['body'], 'string');

try {
    $user = new User;
    $user = $user->getLoggedUser();
    if (!$user) {
        echo json_encode(['code' => 401, 'message' => 'unauthorized user.']);
        exit;
    }

    if ($user->status === 0) {
        echo json_encode(['code' => 401, 'message' => 'please verify your email.']);
        exit;
    }

    if (isset($_FILES['image'])) {
        $name = $_FILES['image']['name'];
        $tmp_name = $_FILES['image']['tmp_name'];
        $location = '../../uploads/posts/';
        $ex = pathinfo($name, PATHINFO_EXTENSION);
        $image = uniqid() . "." . $ex;
        $location = $location . $image;
        if (!move_uploaded_file($tmp_name, $location)) {
            echo json_encode(['code' => 500, 'message' => 'something went wrong in uploading image']);
            exit;
        }
        $image = $image;
    }

    $post = new Post;
    $post->body = $body;
    $post->user_id = $user->id;
    if (isset($image)) {
        $post->create($image);
    } else {
        $post->create();
    }

    echo json_encode(['code' => 200, 'message' => 'post added successfully']);

} catch (Exception $e) {
    echo json_encode(['code' => 500, 'message' => $e->getMessage()]);
}
