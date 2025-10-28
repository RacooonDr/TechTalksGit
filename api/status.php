<?php
header('Content-Type: application/json');
require_once 'config.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Не авторизован']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $status = $data['status'] ?? 'online';
    
    $allowedStatuses = ['online', 'away', 'dnd', 'offline'];
    if (!in_array($status, $allowedStatuses)) {
        http_response_code(400);
        echo json_encode(['error' => 'Неверный статус']);
        exit;
    }
    
    try {
        $stmt = $pdo->prepare("
            INSERT INTO user_status (user_id, status, last_seen) 
            VALUES (?, ?, NOW())
            ON DUPLICATE KEY UPDATE status = ?, last_seen = NOW()
        ");
        $stmt->execute([$_SESSION['user_id'], $status, $status]);
        
        echo json_encode(['success' => true]);
        
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Ошибка обновления статуса']);
    }
    
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Получение статусов пользователей
    $stmt = $pdo->prepare("
        SELECT u.username, us.status, us.last_seen
        FROM users u
        LEFT JOIN user_status us ON u.id = us.user_id
        WHERE us.last_seen > DATE_SUB(NOW(), INTERVAL 5 MINUTE)
        ORDER BY us.last_seen DESC
    ");
    $stmt->execute();
    $statuses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode(['success' => true, 'statuses' => $statuses]);
}
?>