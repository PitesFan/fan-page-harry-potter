<?php

session_start();

header("Content-Type: application/json");

if (!isset($_SESSION['user'])) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'error' => 'not_authenticated']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'method_not_allowed']);
    exit();
}

$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');

if ($title === '' || $description === '') {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'empty_fields']);
    exit();
}

if (!isset($_FILES['cover']) || $_FILES['cover']['error'] === UPLOAD_ERR_NO_FILE) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'no_image']);
    exit();
}

require_once __DIR__ . "/posts.class.php";

$postManager = new PostManager();

$upload = $postManager->handleUpload($_FILES['cover']);
if (!$upload['ok']) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => $upload['error']]);
    exit();
}

$users = json_decode(file_get_contents(__DIR__ . "/../data/users.json"), true) ?: [];
$username = '';
foreach ($users as $u) {
    if (($u['email'] ?? '') === $_SESSION['user']) {
        $username = $u['username'] ?? '';
        break;
    }
}

$post = [
    'id' => uniqid('post_', true),
    'author_email' => $_SESSION['user'],
    'author_username' => $username,
    'title' => htmlspecialchars($title, ENT_QUOTES, 'UTF-8'),
    'description' => htmlspecialchars($description, ENT_QUOTES, 'UTF-8'),
    'cover' => $upload['path'],
    'created_at' => date('c'),
];

$postManager->add($post);

echo json_encode(['ok' => true, 'post' => $post]);
