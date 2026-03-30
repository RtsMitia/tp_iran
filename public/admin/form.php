<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="/assets/css/admin-form.css">
</head>
<body>
    <main class="login-card">
        <h1 class="login-title">Connexion Admin</h1>
        <p class="login-subtitle">Accedez au backoffice avec vos identifiants.</p>
        <form action="/admin/traitement-login.php" method="post">
            <div class="form-group">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" id="username" name="username" value="admin" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" value="admin123" required>
            </div>
            <button type="submit">Valider</button>
        </form>
    </main>
</body>
</html>