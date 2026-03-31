<?php

function getAllArticles($pdo) {
    $sql = "SELECT a.id, a.title, a.slug, a.excerpt, a.published_at,
                   (
                       SELECT i.path
                       FROM images i
                       WHERE i.article_id = a.id
                       ORDER BY i.id ASC
                       LIMIT 1
                   ) AS image_path,
                   (
                       SELECT i.alt
                       FROM images i
                       WHERE i.article_id = a.id
                       ORDER BY i.id ASC
                       LIMIT 1
                   ) AS image_alt
            FROM articles a
            ORDER BY a.published_at DESC";
    
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}

function getArticleById($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function getArticleImages($pdo, $articleId) {
    $stmt = $pdo->prepare("SELECT * FROM images WHERE article_id = ?");
    $stmt->execute([$articleId]);
    return $stmt->fetchAll();
}

function getArticleUrl($article) {
    // Exemple de sortie : article-guerre-iran-12.html
    return "article-" . $article['slug'] . "-" . $article['id'] . ".html";
}