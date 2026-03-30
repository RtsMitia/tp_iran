<?php

function getAllArticles($pdo) {
    $sql = "SELECT id, title, slug, excerpt, published_at 
            FROM articles 
            ORDER BY published_at DESC";
    
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