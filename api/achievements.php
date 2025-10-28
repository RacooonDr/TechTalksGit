<?php
header('Content-Type: application/json');
require_once 'config.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Не авторизован']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Получение достижений пользователя
    $stmt = $pdo->prepare("
        SELECT a.*, 
               CASE WHEN ua.user_id IS NOT NULL THEN 1 ELSE 0 END as unlocked,
               ua.unlocked_at
        FROM achievements a
        LEFT JOIN user_achievements ua ON a.id = ua.achievement_id AND ua.user_id = ?
        ORDER BY a.priority ASC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $achievements = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode(['success' => true, 'achievements' => $achievements]);
    
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Разблокировка достижения
    $data = json_decode(file_get_contents('php://input'), true);
    $achievementId = $data['achievement_id'] ?? null;
    
    if (!$achievementId) {
        http_response_code(400);
        echo json_encode(['error' => 'Не указан ID достижения']);
        exit;
    }
    
    try {
        // Проверяем, не разблокировано ли уже достижение
        $checkStmt = $pdo->prepare("
            SELECT 1 FROM user_achievements 
            WHERE user_id = ? AND achievement_id = ?
        ");
        $checkStmt->execute([$_SESSION['user_id'], $achievementId]);
        
        if ($checkStmt->fetch()) {
            echo json_encode(['success' => true, 'already_unlocked' => true]);
            exit;
        }
        
        // Разблокируем достижение
        $stmt = $pdo->prepare("
            INSERT INTO user_achievements (user_id, achievement_id, unlocked_at) 
            VALUES (?, ?, NOW())
        ");
        $stmt->execute([$_SESSION['user_id'], $achievementId]);
        
        // Получаем информацию о достижении для уведомления
        $achievementStmt = $pdo->prepare("SELECT * FROM achievements WHERE id = ?");
        $achievementStmt->execute([$achievementId]);
        $achievement = $achievementStmt->fetch(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'success' => true, 
            'achievement' => $achievement,
            'message' => "Достижение разблокировано: {$achievement['name']}"
        ]);
        
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Ошибка разблокировки достижения: ' . $e->getMessage()]);
    }
}
?>