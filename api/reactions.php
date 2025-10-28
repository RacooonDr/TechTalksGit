<?php
header('Content-Type: application/json');
require_once 'config.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Не авторизован']);
        exit;
    }
    
    $data = json_decode(file_get_contents('php://input'), true);
    $message_id = $data['message_id'] ?? '';
    $emoji = $data['emoji'] ?? '';
    
    // Здесь должна быть логика добавления реакции в БД
    echo json_encode(['success' => true]);
}
?>