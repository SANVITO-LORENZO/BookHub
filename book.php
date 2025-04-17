<?php
function getLibroByISBN($isbn) {
    $apiKey = 'AIzaSyCbC8lYgDcYMiJXIq-M5xjxXsbh92wXdMg';
    $url = "https://www.googleapis.com/books/v1/volumes?q=isbn:" . urlencode($isbn) . "&key=" . $apiKey;

    $response = file_get_contents($url);
    $data = json_decode($response, true);

    if (!empty($data['items'][0]['volumeInfo'])) {
        $info = $data['items'][0]['volumeInfo'];
        return [
            'titolo' => $info['title'] ?? '',
            'autori' => implode(', ', $info['authors'] ?? []),
            'descrizione' => $info['description'] ?? 'Nessuna descrizione disponibile.',
            'copertina' => $info['imageLinks']['thumbnail'] ?? '',
            'isbn' => $isbn,
            'previewLink' => $info['previewLink'] ?? '#'
        ];
    }

    return null;
}

$libro = null;
if (isset($_GET['isbn'])) {
    $libro = getLibroByISBN($_GET['isbn']);
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Dettagli Libro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <?php if ($libro): ?>
            <div class="row">
                <div class="col-md-4">
                    <?php if ($libro['copertina']): ?>
                        <img src="<?= htmlspecialchars($libro['copertina']) ?>" class="img-fluid rounded shadow" alt="Copertina libro">
                    <?php endif; ?>
                </div>
                <div class="col-md-8">
                    <h2><?= htmlspecialchars($libro['titolo']) ?></h2>
                    <p><strong>Autore:</strong> <?= htmlspecialchars($libro['autori']) ?></p>
                    <p><strong>ISBN:</strong> <?= htmlspecialchars($libro['isbn']) ?></p>
                    <p><?= nl2br(htmlspecialchars($libro['descrizione'])) ?></p>
                    <a href="<?= htmlspecialchars($libro['previewLink']) ?>" target="_blank" class="btn btn-primary">📖 Anteprima su Google Books</a>
                    <a href="index.php" class="btn btn-secondary ms-2">⬅️ Torna indietro</a>
                </div>
            </div>
        <?php else: ?>
            <p class="alert alert-warning">Libro non trovato.</p>
            <a href="index.php" class="btn btn-secondary">Torna indietro</a>
        <?php endif; ?>
    </div>
</body>
</html>
