<?php
require __DIR__ . '/../auth.php';
require __DIR__ . '/../../src/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /admin/article/');
    exit;
}

$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
if ($id <= 0) {
    header('Location: /admin/article/');
    exit;
}

$stmt = $pdo->prepare('DELETE FROM articles WHERE id = ?');
$stmt->execute([$id]);

header('Location: /admin/article/');
exit;
