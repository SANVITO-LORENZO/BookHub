<?php
function cercaGoogleBooks($query) {
    $apiKey = 'AIzaSyCbC8lYgDcYMiJXIq-M5xjxXsbh92wXdMg';
    $url = 'https://www.googleapis.com/books/v1/volumes?q=' . urlencode($query) . '&key=' . $apiKey;

    $response = file_get_contents($url);
    $data = json_decode($response, true);

    $risultati = [];

    if (!empty($data['items'])) {
        foreach ($data['items'] as $item) {
            $volumeInfo = $item['volumeInfo'];

            $risultati[] = [
                'titolo' => $volumeInfo['title'] ?? '',
                'autori' => implode(', ', $volumeInfo['authors'] ?? []),
                'descrizione' => $volumeInfo['description'] ?? '',
                'copertina' => $volumeInfo['imageLinks']['thumbnail'] ?? '',
                'isbn' => $volumeInfo['industryIdentifiers'][0]['identifier'] ?? ''
            ];
        }
    }

    return $risultati;
}

$risultati = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $genere = $_POST['genere'] ?? '';
    $umore = $_POST['umore'] ?? '';
    $tempo = $_POST['tempo'] ?? '';

    // Generazione query base per Google Books
    $query = "$genere $umore $tempo";
    $risultati = cercaGoogleBooks($query);
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Consigliami un Libro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <h1 class="mb-4">📚 Consigliami un Libro</h1>

        <form method="post" class="mb-5">
            <div class="mb-3">
                <label class="form-label">Che genere preferisci?</label>
                <select class="form-select" name="genere" required>
                    <option value="">-- Seleziona --</option>
                    <option>Fantasy</option>
                    <option>Thriller</option>
                    <option>Romanzo romantico</option>
                    <option>Saggio</option>
                    <option>Biografia</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Che umore hai oggi?</label>
                <select class="form-select" name="umore" required>
                    <option value="">-- Seleziona --</option>
                    <option>Evasione</option>
                    <option>Riflessione</option>
                    <option>Divertimento</option>
                    <option>Emozioni forti</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Quanto tempo hai per leggere?</label>
                <select class="form-select" name="tempo" required>
                    <option value="">-- Seleziona --</option>
                    <option>Libro breve</option>
                    <option>Libro medio</option>
                    <option>Libro lungo</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">📖 Trova un libro per me</button>
        </form>

        <?php if (!empty($risultati)): ?>
            <h2>📚 Risultati consigliati:</h2>
            <div class="row">
                <?php foreach ($risultati as $libro): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <?php if ($libro['copertina']): ?>
                                <a href="dettagli.php?isbn=<?= urlencode($libro['isbn']) ?>">
                                    <img src="<?= htmlspecialchars($libro['copertina']) ?>" class="card-img-top" alt="Copertina libro">
                                </a>
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($libro['titolo']) ?></h5>
                                <p class="card-text"><strong>Autore:</strong> <?= htmlspecialchars($libro['autori']) ?></p>
                                <p class="card-text"><?= htmlspecialchars(substr($libro['descrizione'], 0, 200)) ?>...</p>
                            </div>
                            <div class="card-footer">
                                <small class="text-muted">ISBN: <?= htmlspecialchars($libro['isbn']) ?></small>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
