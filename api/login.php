<?php
// api/login.php

require_once 'config.php';

header('Content-Type: application/json');

// Получаем JSON данные
$input = json_decode(file_get_contents('php://input'), true);
$username = trim($input['username'] ?? '');
$password = trim($input['password'] ?? '');

if (empty($username) || empty($password)) {
    http_response_code(400);
    echo json_encode(['error' => 'Имя пользователя и пароль обязательны']);
    exit;
}

try {
    $pdo = Config::getPDO();

    $stmt = $pdo->prepare("SELECT id, username, password_hash, prefix FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['prefix'] = $user['prefix'] ?? 'USER';

        echo json_encode([
            'success' => true,
            'userId' => $user['id'],
            'username' => $user['username'],
            'prefix' => $user['prefix'] ?? 'USER'
        ]);
    } else {
        http_response_code(401);
        echo json_encode(['error' => 'Неверные учетные данные']);
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Ошибка сервера']);
}
?>
