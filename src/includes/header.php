<?php
// Variables surchargables depuis chaque page
$pageTitle = $pageTitle ?? 'Front Office';
$metaDescription = $metaDescription ?? 'Front office TP Iran';
$metaKeywords = $metaKeywords ?? 'front office, tp iran';
$bodyClass = $bodyClass ?? '';
$additionalHead = $additionalHead ?? '';
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <meta name="description" content="<?= htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8') ?>">
    <meta name="keywords" content="<?= htmlspecialchars($metaKeywords, ENT_QUOTES, 'UTF-8') ?>">
    <meta name="author" content="Front Office">
    
    <!-- Preload critical CSS -->
    <link rel="preload" href="assets/css/style.css" as="style">
    <link rel="stylesheet" href="assets/css/style.css">
    
    <?= $additionalHead ?>
</head>
<body class="<?= htmlspecialchars($bodyClass, ENT_QUOTES, 'UTF-8') ?>">
    <header id="site-header">
        <!-- Section Header -->
    </header>

    <main id="content">
        <!-- Section Content -->
