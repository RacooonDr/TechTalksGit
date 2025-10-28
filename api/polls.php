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
    // Получение списка опросов
    $stmt = $pdo->prepare("
        SELECT p.*, 
               COUNT(DISTINCT v.id) as total_votes,
               GROUP_CONCAT(DISTINCT po.id) as option_ids
        FROM polls p
        LEFT JOIN poll_options po ON p.id = po.poll_id
        LEFT JOIN poll_votes v ON po.id = v.option_id
        WHERE p.expires_at > NOW() OR p.expires_at IS NULL
        GROUP BY p.id
        ORDER BY p.created_at DESC
        LIMIT 10
    ");
    $stmt->execute();
    $polls = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $result = [];
    foreach ($polls as $poll) {
        // Получаем варианты ответов
        $optionsStmt = $pdo->prepare("
            SELECT po.*, 
                   COUNT(v.id) as vote_count,
                   ROUND(COUNT(v.id) * 100.0 / GREATEST(?, 1), 1) as percentage
            FROM poll_options po
            LEFT JOIN poll_votes v ON po.id = v.option_id
            WHERE po.poll_id = ?
            GROUP BY po.id
        ");
        $optionsStmt->execute([$poll['total_votes'], $poll['id']]);
        $options = $optionsStmt->fetchAll(PDO::FETCH_ASSOC);
        
        $poll['options'] = $options;
        $result[] = $poll;
    }
    
    echo json_encode(['success' => true, 'polls' => $result]);
    
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Создание нового опроса
    $data = json_decode(file_get_contents('php://input'), true);
    $question = $data['question'] ?? '';
    $options = $data['options'] ?? [];
    $room = $data['room'] ?? 'general';
    
    if (empty($question) || count($options) < 2) {
        http_response_code(400);
        echo json_encode(['error' => 'Введите вопрос и хотя бы 2 варианта ответа']);
        exit;
    }
    
    try {
        $pdo->beginTransaction();
        
        // Создаем опрос
        $stmt = $pdo->prepare("
            INSERT INTO polls (question, created_by, room, created_at) 
            VALUES (?, ?, ?, NOW())
        ");
        $stmt->execute([$question, $_SESSION['user_id'], $room]);
        $pollId = $pdo->lastInsertId();
        
        // Добавляем варианты ответов
        $optionStmt = $pdo->prepare("
            INSERT INTO poll_options (poll_id, text, created_at) 
            VALUES (?, ?, NOW())
        ");
        
        foreach ($options as $optionText) {
            $optionStmt->execute([$pollId, $optionText]);
        }
        
        $pdo->commit();
        
        // Отправляем опрос как сообщение
        $messageStmt = $pdo->prepare("
            INSERT INTO messages (sender, content, room, type, timestamp) 
            VALUES (?, ?, ?, 'poll', NOW())
        ");
        $messageStmt->execute([$_SESSION['username'], $pollId, $room]);
        
        echo json_encode(['success' => true, 'poll_id' => $pollId]);
        
    } catch (Exception $e) {
        $pdo->rollBack();
        http_response_code(500);
        echo json_encode(['error' => 'Ошибка создания опроса: ' . $e->getMessage()]);
    }
    
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Голосование в опросе
    $data = json_decode(file_get_contents('php://input'), true);
    $pollId = $data['poll_id'] ?? null;
    $optionIndex = $data['option_index'] ?? null;
    
    if ($pollId === null || $optionIndex === null) {
        http_response_code(400);
        echo json_encode(['error' => 'Неверные параметры']);
        exit;
    }
    
    try {
        // Получаем ID варианта ответа
        $stmt = $pdo->prepare("
            SELECT id FROM poll_options 
            WHERE poll_id = ? 
            ORDER BY id ASC 
            LIMIT 1 OFFSET ?
        ");
        $stmt->execute([$pollId, $optionIndex]);
        $option = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$option) {
            http_response_code(400);
            echo json_encode(['error' => 'Неверный вариант ответа']);
            exit;
        }
        
        // Удаляем предыдущий голос пользователя
        $deleteStmt = $pdo->prepare("
            DELETE FROM poll_votes 
            WHERE user_id = ? AND option_id IN (
                SELECT id FROM poll_options WHERE poll_id = ?
            )
        ");
        $deleteStmt->execute([$_SESSION['user_id'], $pollId]);
        
        // Добавляем новый голос
        $insertStmt = $pdo->prepare("
            INSERT INTO poll_votes (user_id, option_id, created_at) 
            VALUES (?, ?, NOW())
        ");
        $insertStmt->execute([$_SESSION['user_id'], $option['id']]);
        
        echo json_encode(['success' => true]);
        
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Ошибка голосования: ' . $e->getMessage()]);
    }
}
?>