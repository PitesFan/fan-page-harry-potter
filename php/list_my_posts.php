<?php

session_start();

header("Content-Type: application/json");

if (!isset($_SESSION['user'])) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'error' => 'not_authenticated']);
    exit();
}

require_once __DIR__ . "/posts.class.php";

$postManager = new PostManager();
$posts = $postManager->getByAuthor($_SESSION['user']);

usort($posts, function ($a, $b) {
    return strcmp($b['created_at'] ?? '', $a['created_at'] ?? '');
});

echo json_encode([
    'ok' => true,
    'posts' => $posts,
]);
