<?php
header("Content-Type: application/json; charset=UTF-8");

$method = $_SERVER['REQUEST_METHOD'];
if ($method !== 'POST') {
    echo json_encode(['code' => 400, 'message' => 'invalid request method']);
    exit;
}

if (empty($_POST)) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}

try {
    $posted_date = new DateTime($_POST['date']);
    $now_date = new DateTime();
    $diff = $now_date->diff($posted_date);
    $elapsed = $diff->format('%y years %m months %a days %h hours %i minutes %s seconds');
    $diff = date_diff($posted_date, $now_date);
    // echo json_encode($diff);
    if ($posted_date > $now_date) {
        echo json_encode(['message' => 'posted_date is greater']);
    } elseif ($now_date > $posted_date) {
        echo json_encode(['message' => 'now is greater']);
    }
    exit;
} catch (Exception $e) {
    echo json_encode(['code' => 500, 'message' => $e->getMessage()]);
}
