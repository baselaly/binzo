<?php
require '../../vendor/autoload.php';

header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Origin: http://localhost:8080');
header('Access-Control-Allow-Headers: content-type');

require_once '../../model/User.php';
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

$required_errors = $validator->checkRequired($_POST, [
    'email', 'first_name', 'last_name', 'password', 'country', 'city',
]);

if (count($required_errors) > 0) {
    echo json_encode(['code' => 422, 'message' => $required_errors[0]]);
    exit;
}

$email = $validator->sanitize_input($_POST['email'], 'email');
$first_name = $validator->sanitize_input($_POST['first_name'], 'string');
$last_name = $validator->sanitize_input($_POST['last_name'], 'string');
$country = $validator->sanitize_input($_POST['country'], 'string');
$city = $validator->sanitize_input($_POST['city'], 'string');
$password = $validator->sanitize_input($_POST['password'], 'string');

if (!$validator->email_validate($email)) {
    echo json_encode(['code' => 422, 'message' => 'invalid email format']);
    exit;
}

if (!$validator->max_lenght($email, 255)) {
    echo json_encode(['code' => 422, 'message' => 'email must not be morethan 255 chars']);
    exit;
}

if (!$validator->max_lenght($first_name, 70)) {
    echo json_encode(['code' => 422, 'message' => 'firstname must not be morethan 70 chars']);
    exit;
}

if (!$validator->max_lenght($last_name, 70)) {
    echo json_encode(['code' => 422, 'message' => 'lastname must not be morethan 70 chars']);
    exit;
}

if (!$validator->max_lenght($country, 70)) {
    echo json_encode(['code' => 422, 'message' => 'country must not be morethan 70 chars']);
    exit;
}

if (!$validator->max_lenght($city, 70)) {
    echo json_encode(['code' => 422, 'message' => 'city must not be morethan 70 chars']);
    exit;
}

if (!$validator->max_lenght($password, 100) || !$validator->min_lenght($password, 8)) {
    echo json_encode(['code' => 422, 'message' => 'password must be between 8 and 100 chars']);
    exit;
}

$errors = $validator->password_validate($password);
if (count($errors) > 0) {
    echo json_encode(['code' => 422, 'message' => $errors[0]]);
    exit;
}

if (isset($_FILES['image'])) {
    $acceptable = array('image/jpeg', 'image/jpg', 'image/gif', 'image/png');
    $errors = $validator->validate_image($_FILES['image'], 1024 * 2000, $acceptable);
    if (count($errors) > 0) {
        echo json_encode(['code' => 422, 'message' => $errors[0]]);
    }
}

try {
    $user = new User;
    if ($user->emailExists($email)) {
        echo json_encode(['code' => 422, 'message' => 'email already taken']);
        exit;
    }

    if (isset($_FILES['image'])) {
        $name = $_FILES['image']['name'];
        $tmp_name = $_FILES['image']['tmp_name'];
        $location = '../../uploads/users/';
        $ex = pathinfo($name, PATHINFO_EXTENSION);
        $image = uniqid() . "." . $ex;
        $location = $location . $image;
        if (!move_uploaded_file($tmp_name, $location)) {
            echo json_encode(['code' => 500, 'message' => 'something went wrong in uploading image']);
            exit;
        }
        $user->image = $image;
    } else {
        $user->image = 'user.png';
    }

    $user->email = $email;
    $user->first_name = $first_name;
    $user->last_name = $last_name;
    $user->country = $country;
    $user->city = $city;
    $user->password = password_hash($password, PASSWORD_DEFAULT);
    $user->create();

    echo json_encode(['code' => 200, 'message' => 'register done successfully']);
} catch (Exception $e) {
    echo json_encode(['code' => 500, 'message' => $e->getMessage()]);
}
