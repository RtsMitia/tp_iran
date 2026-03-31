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
            <?php foreach ($articles as $index => $article): ?>
                <article class="article-item">
                    <div class="article-item-inner">
                        <a class="article-thumb" href="<?= getArticleUrl($article) ?>" aria-label="Voir l'article <?= htmlspecialchars($article['title']) ?>">
                            <?php if (!empty($article['image_path'])): ?>
                                <img
                                    src="assets/uploads/<?= htmlspecialchars($article['image_path']) ?>"
                                    alt="<?= htmlspecialchars($article['image_alt'] ?? $article['title']) ?>"
                                    width="220"
                                    height="145"
                                    <?php if ($index === 0): ?>
                                        fetchpriority="high"
                                    <?php else: ?>
                                        loading="lazy"
                                    <?php endif; ?>
                                >
                            <?php else: ?>
                                <span class="article-thumb-placeholder" aria-hidden="true">Aucune image</span>
                            <?php endif; ?>
                        </a>

                        <div class="article-main">
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
                        </div>
                    </div>
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