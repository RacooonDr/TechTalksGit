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
    $name = $data['name'] ?? '';
    $description = $data['description'] ?? '';
    $is_private = $data['is_private'] ?? false;
    
    if (empty($name)) {
        http_response_code(400);
        echo json_encode(['error' => 'Название комнаты обязательно']);
        exit;
    }
    
    // Здесь должна быть логика создания комнаты в БД
    // Пока возвращаем заглушку
    echo json_encode(['success' => true, 'room_id' => uniqid()]);
}
?>