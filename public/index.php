<!doctype html>
<html lang="fr"> <head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <meta name="description" content="<?= htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8') ?>">
  
<?php
require_once __DIR__ . '/../src/database.php';
require_once __DIR__ . '/../src/functions.php';

// Logique : Récupérer les données
$articles = getAllArticles($pdo);

// Variables SEO
$pageTitle       = "Archives du Conflit en Iran : Histoire, Batailles et Analyses";
$metaDescription = "Explorez notre collection complète d'articles sur le conflit en Iran. De l'histoire des batailles aux analyses géopolitiques modernes.";
$metaKeywords    = "guerre Iran, histoire, conflit Moyen-Orient, archives militaires";
$bodyClass       = "home-page";

include __DIR__ . '/../src/includes/header.php';
?>

<main class="container">
    <header class="page-intro">
        <h1>Derniers Articles sur le Conflit en Iran</h1>
        <p class="subtitle">Documentation et perspectives historiques sur l'histoire militaire de la région.</p>
    </header>

    <div class="article-grid">
        <?php if (!empty($articles)): ?>
            <?php foreach ($articles as $article): ?>
                <article class="article-item">
                    <h2>
                        <a href="<?= getArticleUrl($article) ?>">
                            <?= htmlspecialchars($article['title']) ?>
                        </a>
                    </h2>

                    <div class="article-meta">
                        <time datetime="<?= $article['published_at'] ?>">
                            Publié le : <?= date('d/m/Y', strtotime($article['published_at'])) ?>
                        </time>
                    </div>

                    <p class="article-excerpt">
                        <?= htmlspecialchars($article['excerpt']) ?>
                    </p>

                    <a href="<?= getArticleUrl($article) ?>" class="btn-read-more">
                        Lire la suite &rarr;
                    </a>
                </article>
                <hr>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert">
                <p>Aucun article trouvé. Utilisez le panneau d'administration pour ajouter du contenu.</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php 
include __DIR__ . '/../src/includes/footer.php'; 
?>