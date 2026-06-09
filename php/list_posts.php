<?php

session_start();

header("Content-Type: application/json");

require_once __DIR__ . "/posts.class.php";

$postManager = new PostManager();
$posts = $postManager->getAll();

usort($posts, function ($a, $b) {
    return strcmp($b['created_at'] ?? '', $a['created_at'] ?? '');
});

echo json_encode([
    'ok' => true,
    'posts' => $posts,
]);
