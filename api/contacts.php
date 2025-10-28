<?php
header('Content-Type: application/json');
require_once 'config.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Не авторизован']);
    exit;
}

// Заглушка - возвращаем тестовые контакты
$contacts = [
    [
        'id' => 2,
        'username' => 'Alex',
        'status' => 'online',
        'status_text' => 'В сети',
        'avatar' => null
    ],
    [
        'id' => 3,
        'username' => 'Maria',
        'status' => 'away',
        'status_text' => 'Отошёл',
        'avatar' => null
    ],
    [
        'id' => 4,
        'username' => 'John',
        'status' => 'dnd',
        'status_text' => 'Не беспокоить',
        'avatar' => null
    ]
];

echo json_encode(['success' => true, 'contacts' => $contacts]);
?>