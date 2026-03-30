<?php 
    require __DIR__ . '/../src/database.php';

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        session_start();
        $_SESSION['user'] = $user;
        echo "Connexion réussie. Bienvenue " . htmlspecialchars($user['username']);
    } else {
        echo "Identifiants invalides.";
    }

?>