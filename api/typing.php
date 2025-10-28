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
    $room = $data['room'] ?? 'general';
    $typing = $data['typing'] ?? false;
    
    try {
        if ($typing) {
            // Пользователь начал печатать
            $stmt = $pdo->prepare("
                INSERT INTO typing_indicators (user_id, username, room, last_activity) 
                VALUES (?, ?, ?, NOW())
                ON DUPLICATE KEY UPDATE last_activity = NOW()
            ");
            $stmt->execute([$_SESSION['user_id'], $_SESSION['username'], $room]);
        } else {
            // Пользователь закончил печатать
            $stmt = $pdo->prepare("
                DELETE FROM typing_indicators 
                WHERE user_id = ? AND room = ?
            ");
            $stmt->execute([$_SESSION['user_id'], $room]);
        }
        
        echo json_encode(['success' => true]);
        
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Ошибка обновления индикатора печати']);
    }
    
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Получение пользователей, которые печатают
    $room = $_GET['room'] ?? 'general';
    
    $stmt = $pdo->prepare("
        SELECT username 
        FROM typing_indicators 
        WHERE room = ? AND last_activity > DATE_SUB(NOW(), INTERVAL 3 SECOND)
        ORDER BY last_activity DESC
    ");
    $stmt->execute([$room]);
    $typingUsers = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    
    echo json_encode(['success' => true, 'typing_users' => $typingUsers]);
}
?>