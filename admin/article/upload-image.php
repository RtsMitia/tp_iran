<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

header('Content-Type: application/json');

function fail(string $msg, int $code = 400): void {
    http_response_code($code);
    echo json_encode(['error' => $msg]);
    exit;
}

function uploadErrorMessage(int $error): string {
    return match ($error) {
        UPLOAD_ERR_INI_SIZE => 'Fichier trop volumineux (limite serveur upload_max_filesize).',
        UPLOAD_ERR_FORM_SIZE => 'Fichier trop volumineux (limite formulaire).',
        UPLOAD_ERR_PARTIAL => 'Upload incomplet, recommencez.',
        UPLOAD_ERR_NO_FILE => 'Aucun fichier recu.',
        UPLOAD_ERR_NO_TMP_DIR => 'Dossier temporaire manquant sur le serveur.',
        UPLOAD_ERR_CANT_WRITE => 'Impossible d ecrire le fichier sur le disque.',
        UPLOAD_ERR_EXTENSION => 'Upload bloque par une extension PHP.',
        default => 'Erreur upload inconnue.',
    };
}

if (empty($_SESSION['user'])) {
    fail('Session admin expiree, reconnectez-vous.', 401);
}

$webDir = '/assets/uploads';
$absDir = __DIR__ . '/../../public/assets/uploads';
$allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

if (empty($_FILES)) {
    fail('Aucun fichier recu');
}

$f = null;
if (isset($_FILES['file'])) {
    $f = $_FILES['file'];
} elseif (isset($_FILES['files'])) {
    $f = $_FILES['files'];
} else {
    $first = reset($_FILES);
    if ($first !== false) {
        $f = $first;
    }
}

if ($f === null) {
    fail('Aucun fichier recu');
}

if (is_array($f['name'])) {
    $index = 0;
    if (!isset($f['tmp_name'][$index])) {
        fail('Aucun fichier recu');
    }
    $f = [
        'name' => $f['name'][$index] ?? '',
        'type' => $f['type'][$index] ?? '',
        'tmp_name' => $f['tmp_name'][$index] ?? '',
        'error' => $f['error'][$index] ?? UPLOAD_ERR_NO_FILE,
        'size' => $f['size'][$index] ?? 0,
    ];
}

if (!isset($f['error']) || (int) $f['error'] !== UPLOAD_ERR_OK) {
    fail(uploadErrorMessage((int) ($f['error'] ?? UPLOAD_ERR_NO_FILE)), 500);
}

if (!is_dir($absDir) && !mkdir($absDir, 0777, true)) {
    fail('Impossible de creer le dossier upload', 500);
}
if (!is_writable($absDir)) {
    fail('Dossier upload non accessible en ecriture', 500);
}

$ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
if (!in_array($ext, $allowed, true)) {
    fail('Format non autorise');
}

if (@getimagesize($f['tmp_name']) === false) {
    fail('Le fichier n est pas une image valide');
}

$name = uniqid('img_', true) . '.' . $ext;
$dest = rtrim($absDir, '/\\') . '/' . $name;

if (!move_uploaded_file($f['tmp_name'], $dest)) {
    fail('Impossible d enregistrer l image', 500);
}

echo json_encode(['location' => rtrim($webDir, '/') . '/' . $name]);
