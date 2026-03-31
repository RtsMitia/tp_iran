<?php

function getAllArticles($pdo, $startDate = null, $endDate = null) {
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
            FROM articles a";
    
    $params = [];
    $conditions = [];

    if ($startDate) {
        $conditions[] = "a.published_at >= ?";
        $params[] = $startDate . ' 00:00:00';
    }
    if ($endDate) {
        $conditions[] = "a.published_at <= ?";
        $params[] = $endDate . ' 23:59:59';
    }

    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(' AND ', $conditions);
    }

    $sql .= " ORDER BY a.published_at DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
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