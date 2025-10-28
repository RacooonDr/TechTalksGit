<?php
require_once 'config.php';
session_start();
header('Content-Type: application/json; charset=utf-8');

// Проверка сессии
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$pdo = Config::getPDO();

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Берем последние 100 сообщений, исключая USER
        $stmt = $pdo->prepare("
            SELECT m.id, u.username as sender, m.content, m.timestamp
            FROM messages m
            JOIN users u ON u.id = m.sender_id
            WHERE u.prefix != 'USER'
            ORDER BY m.timestamp DESC
            LIMIT 100
        ");
        $stmt->execute();
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Возвращаем в нормальном порядке (сначала старые)
        echo json_encode(['messages' => array_reverse($messages)], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        $content = trim($input['content'] ?? '');
        if ($content === '') {
            http_response_code(400);
            echo json_encode(['error' => 'Message content cannot be empty']);
            exit;
        }

        $stmt = $pdo->prepare("INSERT INTO messages (sender_id, content) VALUES (?, ?)");
        $stmt->execute([$_SESSION['user_id'], $content]);

        $message_id = $pdo->lastInsertId();
        $stmt_ts = $pdo->prepare("SELECT timestamp FROM messages WHERE id = ?");
        $stmt_ts->execute([$message_id]);
        $message_ts = $stmt_ts->fetchColumn();

        echo json_encode([
            'success' => true,
            'id' => $message_id,
            'timestamp' => $message_ts
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
} catch (Throwable $e) {
    error_log('messages.php: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Server error']);
}
?>
