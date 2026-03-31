<?php
require __DIR__ . '/../auth.php';
require __DIR__ . '/../../src/database.php';

$stmt = $pdo->query('SELECT id, title, slug, published_at FROM articles ORDER BY published_at DESC');
$articles = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BO - Articles</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 24px; }
        .topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #f2f2f2; }
        .actions a, .actions button { margin-right: 8px; }
        .btn { display: inline-block; padding: 8px 12px; background: #111; color: #fff; text-decoration: none; border-radius: 6px; border: none; cursor: pointer; }
        .btn-secondary { background: #6b7280; }
        .btn-danger { background: #b91c1c; }
        form.inline { display: inline; }
    </style>
</head>
<body>
    <div class="topbar">
        <h1>Gestion des articles</h1>
        <div>
            <a class="btn" href="/admin/article/form.php">Nouvel article</a>
            <a class="btn btn-secondary" href="/admin/logout.php">Se deconnecter</a>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Slug</th>
                <th>Date publication</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($articles)): ?>
                <tr><td colspan="5">Aucun article.</td></tr>
            <?php else: ?>
                <?php foreach ($articles as $article): ?>
                    <tr>
                        <td><?= (int) $article['id'] ?></td>
                        <td><?= htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($article['slug'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($article['published_at'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="actions">
                            <a class="btn btn-secondary" href="/admin/article/form.php?id=<?= (int) $article['id'] ?>">Editer</a>
                            <form class="inline" method="post" action="/admin/article/delete.php" onsubmit="return confirm('Supprimer cet article ?');">
                                <input type="hidden" name="id" value="<?= (int) $article['id'] ?>">
                                <button type="submit" class="btn btn-danger">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
