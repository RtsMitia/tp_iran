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
            <?php foreach ($images as $index => $image): 
                $imgPath = __DIR__ . '/assets/uploads/' . $image['path'];
                $imgWidth = '';
                $imgHeight = '';
                if (file_exists($imgPath)) {
                    $size = getimagesize($imgPath);
                    if ($size) {
                        $imgWidth = $size[0];
                        $imgHeight = $size[1];
                    }
                }
            ?>
                <figure>
                    <img src="assets/uploads/<?= htmlspecialchars($image['path']) ?>" 
                         alt="<?= htmlspecialchars($image['alt']) ?>"
                         <?php if ($imgWidth): ?>width="<?= $imgWidth ?>" height="<?= $imgHeight ?>"<?php endif; ?>
                         <?php if ($index === 0): ?>
                             fetchpriority="high"
                         <?php else: ?>
                             loading="lazy" 
                         <?php endif; ?>
                         style="max-width: 100%; height: auto;">
                    <figcaption><?= htmlspecialchars($image['alt']) ?></figcaption>
                </figure>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="article-content">
        <?php 
            
            echo $article['content']; 
        ?>
    </div>

    
        <a href="index.php">&larr; Retour à la liste des articles</a>
   
</article>

<?php 
include __DIR__ . '/../src/includes/footer.php'; 
?>