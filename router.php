<?php
declare(strict_types=1);

/**
 * Built-in PHP Server Router (php -S 0.0.0.0:8080 router.php)
 * Protects internal /includes and /storage directories while serving static assets & PHP pages.
 */

$uri = urldecode((string) parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH));

if (preg_match('#^/(includes|storage)(/|$)#i', $uri)) {
    http_response_code(403);
    echo '403 Forbidden';
    return true;
}

$filePath = __DIR__ . $uri;
if ($uri !== '/' && is_file($filePath)) {
    return false; // Let PHP's built-in server serve static assets and .php scripts directly
}

require __DIR__ . '/index.php';
return true;
