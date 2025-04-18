<?php
require_once 'classes/book.php';

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
  
    <div class="container py-3">
        <div class="card shadow">
            <div class="text-center mb-4">
                <br>
                <a href="index.php">
                    <img src="extra/img/logo.jpg" alt="BookHub Logo" style="max-height: 50px;" class="img-fluid">
                </a>
            </div>
            <div class="card-body">
                <?php
                if ($libro) {
                    echo '<div class="row">';
                    echo '<div class="col-md-4 mb-4">';
                    if (!empty($libro->copertina)) {
                        echo '<img src="' . $libro->copertina. '" class="img-fluid rounded shadow" alt="Copertina libro">';
                    } else {
                        echo '<div class="alert alert-secondary">Copertina non disponibile</div>';
                    }
                    echo '</div>';
                    echo '<div class="col-md-8">';
                    echo '<h2>' . $libro->titolo . '</h2>';
                    echo '<p><strong>Autore:</strong> ' . $libro->autori . '</p>';
                    echo '<p><strong>ISBN:</strong> ' . $libro->isbn . '</p>';
                    echo '<p>' . $libro->descrizione . '</p>';
                    
                    echo '<div class="mt-3">';
                    if (!empty($libro->previewLink)) {
                        echo '<a href="' . $libro->previewLink . '" target="_blank" class="btn btn-primary">Anteprima su Google Books</a>';
                    }
                    echo '<a href="index.php" class="btn btn-secondary ms-2">Torna indietro</a>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                } else {
                    echo '<div class="alert alert-warning">';
                    echo 'Libro non trovato o ISBN non valido.';
                    echo '</div>';
                    echo '<a href="index.php" class="btn btn-secondary">Torna alla ricerca</a>';
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
