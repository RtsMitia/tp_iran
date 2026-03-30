<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <form action="/admin/traitement-login.php" method="post">
        <label for="username">Nom d'utilisateur</label><br>
        <input type="text" id="username" name="username" value="admin"><br>
        <label for="password">Mot de passe</label><br>
        <input type="password" id="password" name="password" value="admin123"><br><br>
        <button type="submit">Valider</button>
    </form>
</body>
</html>