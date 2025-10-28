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
    $url = $data['url'] ?? '';
    
    if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
        http_response_code(400);
        echo json_encode(['error' => 'Неверный URL']);
        exit;
    }
    
    try {
        // Получаем содержимое страницы
        $context = stream_context_create([
            'http' => [
                'timeout' => 10,
                'header' => "User-Agent: Mozilla/5.0 (compatible; TechTalksBot/1.0)\r\n"
            ]
        ]);
        
        $html = file_get_contents($url, false, $context);
        if (!$html) {
            throw new Exception('Не удалось получить содержимое страницы');
        }
        
        // Парсим мета-теги
        $metaTags = [];
        preg_match_all('/<meta[^>]+>/i', $html, $matches);
        
        foreach ($matches[0] as $tag) {
            preg_match('/property="([^"]*)"/i', $tag, $property);
            preg_match('/name="([^"]*)"/i', $tag, $name);
            preg_match('/content="([^"]*)"/i', $tag, $content);
            
            $key = $property[1] ?? $name[1] ?? null;
            if ($key && $content[1]) {
                $metaTags[strtolower($key)] = $content[1];
            }
        }
        
        // Извлекаем заголовок
        preg_match('/<title[^>]*>(.*?)<\/title>/i', $html, $title);
        $pageTitle = $title[1] ?? '';
        
        // Формируем превью
        $preview = [
            'url' => $url,
            'title' => $metaTags['og:title'] ?? $metaTags['twitter:title'] ?? $pageTitle,
            'description' => $metaTags['og:description'] ?? $metaTags['twitter:description'] ?? $metaTags['description'] ?? '',
            'image' => $metaTags['og:image'] ?? $metaTags['twitter:image'] ?? ''
        ];
        
        // Очищаем данные
        foreach ($preview as &$value) {
            $value = html_entity_decode(strip_tags($value));
        }
        
        // Сохраняем превью в кэш
        $stmt = $pdo->prepare("
            INSERT INTO link_previews (url, title, description, image, created_at) 
            VALUES (?, ?, ?, ?, NOW())
            ON DUPLICATE KEY UPDATE title = ?, description = ?, image = ?, created_at = NOW()
        ");
        $stmt->execute([
            $url, $preview['title'], $preview['description'], $preview['image'],
            $preview['title'], $preview['description'], $preview['image']
        ]);
        
        echo json_encode(['success' => true, 'preview' => $preview]);
        
    } catch (Exception $e) {
        // В случае ошибки возвращаем базовую информацию
        $preview = [
            'url' => $url,
            'title' => parse_url($url, PHP_URL_HOST),
            'description' => '',
            'image' => ''
        ];
        
        echo json_encode(['success' => true, 'preview' => $preview]);
    }
}
?>