<?php
// api/register.php

require_once 'config.php';

session_start();
header('Content-Type: application/json');

try {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $username = $input['username'] ?? '';
    $password = $input['password'] ?? '';
    $publicKey = $input['publicKey'] ?? [];

    if (empty($username) || empty($password)) {
        throw new Exception('Имя пользователя и пароль обязательны');
    }

    if (strlen($username) < 4) {
        throw new Exception('Имя пользователя должно быть не менее 4 символов');
    }

    if (strlen($password) < 8) {
        throw new Exception('Пароль должен быть не менее 8 символов');
    }

    $pdo = Config::getPDO();
    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, password_hash, public_key) VALUES (?, ?, ?)");
    $stmt->execute([$username, $hash, json_encode($publicKey)]);

    $userId = $pdo->lastInsertId();

    $_SESSION['user_id'] = $userId;
    $_SESSION['username'] = $username;

    echo json_encode([
        'success' => true,
        'userId' => $userId,
        'username' => $username
    ]);
    
} catch (PDOException $e) {
    if ($e->getCode() == 23000) {
        http_response_code(400);
        echo json_encode(['error' => 'Имя пользователя уже занято']);
    } else {
        error_log('Registration error: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['error' => 'Ошибка регистрации']);
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}
?>