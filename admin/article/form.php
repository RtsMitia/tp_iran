<?php
require __DIR__ . '/../auth.php';
require __DIR__ . '/../../src/database.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$isEdit = $id > 0;

$article = [
    'id' => 0,
    'title' => '',
    'slug' => '',
    'excerpt' => '',
    'content' => '<p>Commencez votre article ici...</p>',
    'published_at' => date('Y-m-d\\TH:i'),
];

if ($isEdit) {
    $stmt = $pdo->prepare('SELECT * FROM articles WHERE id = ?');
    $stmt->execute([$id]);
    $dbArticle = $stmt->fetch();

    if (!$dbArticle) {
        header('Location: /admin/article/');
        exit;
    }

    $article = [
        'id' => (int) $dbArticle['id'],
        'title' => $dbArticle['title'],
        'slug' => $dbArticle['slug'],
        'excerpt' => $dbArticle['excerpt'],
        'content' => $dbArticle['content'],
        'published_at' => date('Y-m-d\\TH:i', strtotime((string) $dbArticle['published_at'])),
    ];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BO - <?= $isEdit ? 'Modifier' : 'Creer' ?> article</title>
    <script src="/admin/tinymce/tinymce.min.js"></script>
    <style>
        body { font-family: Arial, sans-serif; margin: 24px; }
        .row { margin-bottom: 14px; }
        label { display: block; margin-bottom: 6px; font-weight: 600; }
        input[type=text], input[type=datetime-local], textarea { width: 100%; padding: 10px; box-sizing: border-box; }
        .actions { margin-top: 16px; display: flex; gap: 10px; }
        .btn { display: inline-block; padding: 10px 14px; background: #111; color: #fff; text-decoration: none; border-radius: 6px; border: none; cursor: pointer; }
        .btn-secondary { background: #6b7280; }
    </style>
</head>
<body>
    <h1>Editeur TinyMCE</h1>

    <form method="post" action="/admin/article/save.php" id="article-form">
        <input type="hidden" name="id" value="<?= (int) $article['id'] ?>">
        <input type="hidden" id="published_at" name="published_at" value="<?= htmlspecialchars($article['published_at'], ENT_QUOTES, 'UTF-8') ?>">

        <div class="row">
            <label for="content">Contenu (TinyMCE)</label>
            <textarea id="myEditor" name="content"><?= htmlspecialchars($article['content'], ENT_NOQUOTES, 'UTF-8') ?></textarea>
        </div>

        <div class="actions">
            <button class="btn" type="submit"><?= $isEdit ? 'Mettre a jour' : 'Creer' ?></button>
            <button class="btn btn-secondary" type="button" id="preview-html">Voir le HTML</button>
            <a class="btn btn-secondary" href="/admin/article/">Retour</a>
        </div>
    </form>

    <h2>Code HTML genere :</h2>
    <pre id="html-output" style="background:#f0f0f0; padding:10px; white-space:pre-wrap;">Apercu vide</pre>

    <script>
        tinymce.init({
            selector: '#myEditor',
            menubar: 'file edit view insert format tools table help',
            plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media table | removeformat code fullscreen',
            block_formats: 'Paragraph=p; Heading 1=h1; Heading 2=h2; Heading 3=h3; Heading 4=h4; Heading 5=h5; Heading 6=h6; Preformatted=pre',
            height: 600,
            license_key: 'gpl',
            automatic_uploads: true,
            images_upload_url: '/admin/article/upload-image.php',
            images_upload_credentials: true,
            file_picker_types: 'image',
            promotion: false,
            toolbar_mode: 'wrap',
            file_picker_callback: (callback, value, meta) => {
                if (meta.filetype !== 'image') {
                    return;
                }

                const input = document.createElement('input');
                input.type = 'file';
                input.accept = 'image/*';
                input.multiple = true;

                input.onchange = async () => {
                    const files = Array.from(input.files || []);
                    if (files.length === 0) {
                        return;
                    }

                    const editor = tinymce.get('myEditor');
                    const uploadedUrls = [];

                    for (const file of files) {
                        const data = new FormData();
                        data.append('file', file);

                        try {
                            const response = await fetch('/admin/article/upload-image.php', {
                                method: 'POST',
                                body: data,
                                credentials: 'same-origin'
                            });

                            const json = await response.json();
                            if (!response.ok || !json.location) {
                                throw new Error(json.error || 'Erreur upload image');
                            }

                            uploadedUrls.push(json.location);
                        } catch (error) {
                            alert('Echec upload "' + file.name + '": ' + error.message);
                        }
                    }

                    if (uploadedUrls.length === 0) {
                        return;
                    }

                    callback(uploadedUrls[0], { alt: files[0].name });
                    if (uploadedUrls.length > 1) {
                        const extra = uploadedUrls.slice(1)
                            .map((url, i) => '<p><img src="' + url + '" alt="image-' + (i + 2) + '"></p>')
                            .join('');
                        editor.insertContent(extra);
                    }
                };

                input.click();
            }
        });

        document.getElementById('preview-html').addEventListener('click', () => {
            const html = tinymce.get('myEditor').getContent();
            document.getElementById('html-output').textContent = html;
        });
    </script>
</body>
</html>
