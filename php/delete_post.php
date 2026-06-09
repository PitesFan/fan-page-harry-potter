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

$input = json_decode(file_get_contents('php://input'), true) ?? [];
$id = $input['id'] ?? ($_POST['id'] ?? '');

if ($id === '') {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'missing_id']);
    exit();
}

require_once __DIR__ . "/posts.class.php";

$postManager = new PostManager();
$ok = $postManager->deleteById($id, $_SESSION['user']);

if (!$ok) {
    http_response_code(404);
    echo json_encode(['ok' => false, 'error' => 'not_found']);
    exit();
}

echo json_encode(['ok' => true]);
