<?php 
    require __DIR__ . '/../src/database.php';

    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        header('Location: /admin/form.php');
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;
        header('Location: /admin/article/');
        exit;
    } else {
        header('Location: /admin/form.php');
        exit;
    }

?>