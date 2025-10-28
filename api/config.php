<?php
// config.php
// header('Content-Type: application/json'); // УБРАНО, как ты и отметил

class Config {
    public static $db_host = 'localhost';
    public static $db_name = 'u3293048_default';
    public static $db_user = 'u3293048_default';
    public static $db_pass = 'L6xnAh64ZK7B2bTt';
    // --- ВАЖНО: ЗАМЕНИТЕ ЭТОТ КЛЮЧ НА ВАШ РЕАЛЬНЫЙ СЕКРЕТНЫЙ КЛЮЧ ---
    // Оставлено на случай, если используется в других частях приложения (например, API для мобильного клиента)
    // НЕ ИСПОЛЬЗУЕТСЯ для аутентификации пользователей чата (там используется сессия)
    public static $jwt_secret = 'y0B1n2y_kl1uCSHh_BB1aUGht_zAeBa1k'; // <- СЮДА

    public static function getPDO() {
        try {
            $pdo = new PDO(
                "mysql:host=" . self::$db_host . ";dbname=" . self::$db_name . ";charset=utf8mb4",
                self::$db_user,
                self::$db_pass
            );
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Установка кодировки соединения
            $pdo->exec("SET NAMES utf8mb4");
            return $pdo;
        } catch (PDOException $e) {
            // Лучше логировать ошибку и не отправлять детали в HTTP-ответ, если это не для отладки
            error_log('Database connection failed: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Database connection failed']);
            exit;
        }
    }
}

// --- 👇 Добавлено: настройка длительной сессии (7 дней) ---
$lifetime = 60 * 60 * 24 * 7; // 7 дней
session_set_cookie_params([
    'lifetime' => $lifetime,
    'path' => '/',
    'httponly' => true,
    'samesite' => 'Lax'
]);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
