<?php
declare(strict_types=1);

/**
 * Renders a single PHP page in an isolated CLI process and prints the resulting HTML.
 * Used by build-pages.php so that each page gets a clean session/global scope.
 *
 * Usage: php scripts/render-page.php index.php
 */

$page = $argv[1] ?? '';
if ($page === '' || !preg_match('/^[a-z0-9_-]+\.php$/i', basename($page))) {
    fwrite(STDERR, "Usage: php scripts/render-page.php <page.php>\n");
    exit(1);
}

$root = dirname(__DIR__);
$pagePath = $root . '/' . basename($page);

if (!is_file($pagePath)) {
    fwrite(STDERR, "Page not found: {$pagePath}\n");
    exit(1);
}

error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_WARNING);
ini_set('display_errors', 'stderr');

$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/' . basename($page);
$_SERVER['SCRIPT_NAME'] = '/' . basename($page);
$_SERVER['PHP_SELF'] = '/' . basename($page);
$_SERVER['SCRIPT_FILENAME'] = $pagePath;
$_SERVER['HTTP_HOST'] = 'www.softrings.lk';
$_SERVER['SERVER_NAME'] = 'www.softrings.lk';
$_SERVER['SERVER_PORT'] = '443';
$_SERVER['HTTPS'] = 'on';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_SERVER['HTTP_USER_AGENT'] = 'Softrings-Static-Builder/1.0';
$_GET = [];
$_POST = [];
$_COOKIE = [];

chdir($root);

ob_start();
require $pagePath;
$html = (string) ob_get_clean();

if (stripos($html, '<!DOCTYPE html') === false || stripos($html, '</html>') === false) {
    fwrite(STDERR, "Render produced incomplete HTML for {$page}\n");
    exit(2);
}

echo $html;
