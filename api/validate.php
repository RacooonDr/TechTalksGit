<?php
// api/validate.php

require_once 'config.php';
header('Content-Type: application/json');

if (isset($_SESSION['user_id'], $_SESSION['username'])) {
    echo json_encode([
        'success' => true,
        'userId' => $_SESSION['user_id'],
        'username' => $_SESSION['username'],
        'prefix' => $_SESSION['prefix'] ?? 'USER'
    ]);
} else {
    http_response_code(401);
    echo json_encode(['error' => 'Not authenticated']);
}
?>
