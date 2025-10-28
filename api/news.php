<?php
require_once 'config.php';
session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$pdo = Config::getPDO();

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $stmt = $pdo->prepare("
            SELECT n.id, n.text, n.created_at
            FROM news n
            ORDER BY n.created_at DESC
            LIMIT 50
        ");
        $stmt->execute();
        $news = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['news' => $news], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        $text = trim($input['text'] ?? '');

        if ($_SESSION['prefix'] !== 'ADMIN') {
            http_response_code(403);
            echo json_encode(['error' => 'Only admin can add news']);
            exit;
        }

        if ($text === '') {
            http_response_code(400);
            echo json_encode(['error' => 'News text cannot be empty']);
            exit;
        }

        $stmt = $pdo->prepare("INSERT INTO news (author_id, text) VALUES (?, ?)");
        $stmt->execute([$_SESSION['user_id'], $text]);

        echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()], JSON_UNESCAPED_UNICODE);
        exit;
    }

    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
} catch (Throwable $e) {
    error_log('news.php: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Server error']);
}
?>
