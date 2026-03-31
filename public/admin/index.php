<?php
session_start();

$route = trim((string) ($_GET['route'] ?? ''), '/');

if (str_starts_with($route, 'tinymce/')) {
    $relative = substr($route, strlen('tinymce/'));
    $relative = str_replace('\\', '/', $relative);

    if ($relative === '' || str_contains($relative, '..')) {
        http_response_code(400);
        echo 'Chemin invalide.';
        exit;
    }

    $file = __DIR__ . '/../../admin/tinymce/' . $relative;
    if (!is_file($file)) {
        http_response_code(404);
        echo 'Fichier TinyMCE introuvable.';
        exit;
    }

    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    $mime = match ($ext) {
        'js' => 'application/javascript',
        'css' => 'text/css',
        'svg' => 'image/svg+xml',
        'json' => 'application/json',
        'woff' => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf' => 'font/ttf',
        'png' => 'image/png',
        'jpg', 'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        default => 'application/octet-stream',
    };

    header('Content-Type: ' . $mime);
    readfile($file);
    exit;
}

$targets = [
    '' => __DIR__ . '/../../admin/form.php',
    'form.php' => __DIR__ . '/../../admin/form.php',
    'traitement-login.php' => __DIR__ . '/../../admin/traitement-login.php',
    'logout.php' => __DIR__ . '/../../admin/logout.php',
    'article' => __DIR__ . '/../../admin/article/index.php',
    'article/' => __DIR__ . '/../../admin/article/index.php',
    'article/index.php' => __DIR__ . '/../../admin/article/index.php',
    'article/form.php' => __DIR__ . '/../../admin/article/form.php',
    'article/save.php' => __DIR__ . '/../../admin/article/save.php',
    'article/delete.php' => __DIR__ . '/../../admin/article/delete.php',
    'article/upload-image.php' => __DIR__ . '/../../admin/article/upload-image.php',
];

if (!array_key_exists($route, $targets)) {
    http_response_code(404);
    echo 'Page admin introuvable.';
    exit;
}

require $targets[$route];