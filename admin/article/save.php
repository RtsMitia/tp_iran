<?php
require __DIR__ . '/../auth.php';
require __DIR__ . '/../../src/database.php';

function normalizeSlug(string $text): string {
    $text = trim(strtolower($text));
    $text = preg_replace('/[^a-z0-9]+/', '-', $text) ?? '';
    return trim($text, '-');
}

function normalizeSpace(string $text): string {
    return trim(preg_replace('/\s+/', ' ', $text) ?? '');
}

function cut160(string $text): string {
    if (function_exists('mb_substr')) {
        return mb_substr($text, 0, 160);
    }

    return substr($text, 0, 160);
}

function extractMetaFromHtml(string $html): array {
    $doc = new DOMDocument();
    @$doc->loadHTML('<?xml encoding="utf-8" ?>' . $html);

    $title = '';
    foreach (['h1', 'h2', 'h3', 'p'] as $tag) {
        $nodes = $doc->getElementsByTagName($tag);
        if ($nodes->length > 0) {
            $title = normalizeSpace((string) $nodes->item(0)?->textContent);
            if ($title !== '') {
                break;
            }
        }
    }

    $excerpt = '';
    $pNodes = $doc->getElementsByTagName('p');
    if ($pNodes->length > 0) {
        $excerpt = normalizeSpace((string) $pNodes->item(0)?->textContent);
    }

    if ($excerpt === '') {
        $excerpt = normalizeSpace(strip_tags($html));
    }

    return [
        'title' => $title,
        'excerpt' => cut160($excerpt),
    ];
}

$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
$title = trim((string) ($_POST['title'] ?? ''));
$slug = trim((string) ($_POST['slug'] ?? ''));
$excerpt = trim((string) ($_POST['excerpt'] ?? ''));
$content = (string) ($_POST['content'] ?? '');
$publishedAt = trim((string) ($_POST['published_at'] ?? ''));

if ($content === '' || $publishedAt === '') {
    http_response_code(400);
    echo 'Contenu ou date manquants.';
    exit;
}

$autoMeta = extractMetaFromHtml($content);
if ($title === '') {
    $title = $autoMeta['title'];
}
if ($excerpt === '') {
    $excerpt = $autoMeta['excerpt'];
}

if ($title === '' || $excerpt === '') {
    http_response_code(400);
    echo 'Impossible de generer titre/extrait depuis le HTML. Ajoute au moins un titre ou un paragraphe.';
    exit;
}

$slug = $slug !== '' ? normalizeSlug($slug) : normalizeSlug($title);
if ($slug === '') {
    http_response_code(400);
    echo 'Slug invalide.';
    exit;
}

if ($publishedAt === '') {
    $publishedAtSql = date('Y-m-d H:i:s');
} else {
    $publishedAtSql = str_replace('T', ' ', $publishedAt) . ':00';
}

try {
    if ($id > 0) {
        $stmt = $pdo->prepare('UPDATE articles SET title = ?, slug = ?, excerpt = ?, content = ?, published_at = ? WHERE id = ?');
        $stmt->execute([$title, $slug, $excerpt, $content, $publishedAtSql, $id]);
    } else {
        $stmt = $pdo->prepare('INSERT INTO articles (title, slug, excerpt, content, published_at) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$title, $slug, $excerpt, $content, $publishedAtSql]);
    }
} catch (PDOException $e) {
    http_response_code(400);
    if ((int) $e->getCode() === 23000) {
        echo 'Slug deja utilise. Choisissez-en un autre.';
        exit;
    }

    echo 'Erreur SQL: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    exit;
}

header('Location: /admin/article/');
exit;
