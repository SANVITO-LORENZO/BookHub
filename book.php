<?php
require_once 'classes/Book.php';

$libro = null;

if (isset($_GET['isbn'])) {
    $isbn = trim($_GET['isbn']);
    $libro = Book::fromISBN($isbn);
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

    <?php if ($libro) { ?>
        <div class="row">
            <div class="col-md-4 mb-4">
                <?php
                if (!empty($libro->copertina)) {
                    echo '<img src="' . htmlspecialchars($libro->copertina) . '" class="img-fluid rounded shadow" alt="Copertina libro">';
                } else {
                    echo '<div class="alert alert-secondary">Copertina non disponibile</div>';
                }
                ?>
            </div>
            <div class="col-md-8">
                <h2><?= htmlspecialchars($libro->titolo) ?></h2>
                <p><strong>Autore:</strong> <?= htmlspecialchars($libro->autori) ?></p>
                <p><strong>ISBN:</strong> <?= htmlspecialchars($libro->isbn) ?></p>
                <p><?= nl2br(htmlspecialchars($libro->descrizione)) ?></p>

                <div class="mt-3">
                    <?php
                    if (!empty($libro->previewLink)) {
                        echo '<a href="' . htmlspecialchars($libro->previewLink) . '" target="_blank" class="btn btn-primary">📖 Anteprima su Google Books</a>';
                    }
                    ?>
                    <a href="index.php" class="btn btn-secondary ms-2">⬅️ Torna indietro</a>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <div class="alert alert-warning">
            Libro non trovato o ISBN non valido.
        </div>
        <a href="index.php" class="btn btn-secondary">Torna alla ricerca</a>
    <?php } ?>

</div>
</body>
</html>
