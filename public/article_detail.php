<?php
require_once __DIR__ . '/../src/database.php';
require_once __DIR__ . '/../src/functions.php';

// 1. Récupérer l'ID envoyé par le .htaccess
$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: index.php');
    exit;
}

// 2. Chercher l'article et ses images
$article = getArticleById($pdo, $id);
$images  = getArticleImages($pdo, $id);

// Si l'article n'existe pas, retour à l'accueil
if (!$article) {
    header('Location: index.php');
    exit;
}

// 3. Variables SEO dynamiques pour le header.php
$pageTitle       = $article['title'] . " - Histoire de l'Iran";
$metaDescription = $article['excerpt']; // Utilise l'extrait < 160 car.
$bodyClass       = "article-detail";

include __DIR__ . '/../src/includes/header.php';
?>

<article class="container">
    <header class="article-header">
        <h1><?= htmlspecialchars($article['title']) ?></h1>
        
        <p class="publish-date">
            Publié le : <?= date('d/m/Y', strtotime($article['published_at'])) ?>
        </p>
    </header>

    <?php if (!empty($images)): ?>
        <div class="article-gallery">
            <?php foreach ($images as $image): ?>
                <figure>
                    <img src="<?= htmlspecialchars($image['path']) ?>" 
                         alt="<?= htmlspecialchars($image['alt']) ?>" 
                         style="max-width: 100%; height: auto;">
                    <figcaption><?= htmlspecialchars($image['alt']) ?></figcaption>
                </figure>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="article-content">
        <?php 
            // On utilise echo sans htmlspecialchars ici car le contenu 
            // vient de TinyMCE et contient déjà des balises HTML sécurisées
            echo $article['content']; 
        ?>
    </div>

    <footer class="article-footer">
        <a href="index.php">&larr; Retour à la liste des articles</a>
    </footer>
</article>

<?php 
include __DIR__ . '/../src/includes/footer.php'; 
?>