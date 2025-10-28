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
    if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
        http_response_code(400);
        echo json_encode(['error' => 'Ошибка загрузки файла']);
        exit;
    }
    
    $file = $_FILES['avatar'];
    
    // Проверка типа файла
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($file['type'], $allowedTypes)) {
        http_response_code(400);
        echo json_encode(['error' => 'Разрешены только JPEG, PNG, GIF и WebP изображения']);
        exit;
    }
    
    // Проверка размера файла (максимум 5MB)
    if ($file['size'] > 5 * 1024 * 1024) {
        http_response_code(400);
        echo json_encode(['error' => 'Размер файла не должен превышать 5MB']);
        exit;
    }
    
    try {
        // Создаем папку для аватаров, если её нет
        $uploadDir = '../uploads/avatars/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Генерируем уникальное имя файла
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'avatar_' . $_SESSION['user_id'] . '_' . time() . '.' . $extension;
        $filepath = $uploadDir . $filename;
        
        // Сохраняем файл
        if (!move_uploaded_file($file['tmp_name'], $filepath)) {
            throw new Exception('Ошибка сохранения файла');
        }
        
        // Обновляем запись в базе данных
        $stmt = $pdo->prepare("
            UPDATE users SET avatar = ? WHERE id = ?
        ");
        $stmt->execute([$filename, $_SESSION['user_id']]);
        
        echo json_encode([
            'success' => true, 
            'avatar_url' => '/uploads/avatars/' . $filename,
            'message' => 'Аватар успешно обновлен'
        ]);
        
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Ошибка загрузки аватара: ' . $e->getMessage()]);
    }
}
?>