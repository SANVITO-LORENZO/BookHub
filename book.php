<?php
session_start();
require_once 'classes/book.php';
require_once 'managers/database.php';

$libro = null;
$commenti = [];

if (isset($_GET['isbn'])) {
    $isbn = trim($_GET['isbn']);
    $libro = Book::fromISBN($isbn);
    $commenti=[];
    // Recupera i commenti dal DB
    $commenti= getComments($isbn);
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
                <?php if ($libro): ?>
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <?php if (!empty($libro->copertina)): ?>
                                <img src="<?= $libro->copertina ?>" class="img-fluid rounded shadow" alt="Copertina libro">
                            <?php else: ?>
                                <div class="alert alert-secondary">Copertina non disponibile</div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-8">
                            <h2><?= $libro->titolo ?></h2>
                            <p><strong>Autore:</strong> <?= $libro->autori ?></p>
                            <p><strong>ISBN:</strong> <?= $libro->isbn ?></p>
                            <p><?= $libro->descrizione ?></p>

                            <div class="mt-3">
                                <?php if (!empty($libro->previewLink)): ?>
                                    <a href="<?= $libro->previewLink ?>" target="_blank" class="btn btn-primary">Anteprima su Google Books</a>
                                <?php endif; ?>
                                <a href="index.php" class="btn btn-secondary ms-2">Torna indietro</a>
                            </div>

                            <!-- BOTTONI AGGIUNTI -->
                            <div class="mt-3">
                                <?php if (isset($_SESSION['username'])): ?>
                                    <form method="POST" action="aggiungi_preferito.php" class="d-inline">
                                        <input type="hidden" name="isbn" value="<?= $libro->isbn ?>">
                                        <button type="submit" class="btn btn-warning">Aggiungi ai Preferiti</button>
                                    </form>

                                    <button class="btn btn-info ms-2" onclick="document.getElementById('commentForm').classList.toggle('d-none')">Commenta</button>
                                <?php endif; ?>
                            </div>

                            <!-- FORM COMMENTO -->
                            <div id="commentForm" class="mt-4 d-none">
                                <form method="POST" action="aggiungi_commento.php">
                                    <input type="hidden" name="isbn" value="<?= $libro->isbn ?>">
                                    <div class="mb-3">
                                        <label for="commento" class="form-label">Il tuo commento</label>
                                        <textarea class="form-control" id="commento" name="commento" rows="3" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-success">Invia commento</button>
                                </form>
                            </div>

                            <!-- AREA COMMENTI -->
                            <?php if ($commenti): ?>
                                <div class="mt-5">
                                    <h5>Commenti:</h5>
                                    <?php foreach ($commenti as $c): ?>
                                        <div class="border rounded p-2 mb-2 bg-white">
                                            <p class="mb-1"><strong><?= htmlspecialchars($c['username']) ?></strong> ha scritto il <?= $c['data'] ?>:</p>
                                            <p><?= htmlspecialchars($c['testo']) ?></p>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">
                        Libro non trovato o ISBN non valido.
                    </div>
                    <a href="index.php" class="btn btn-secondary">Torna alla ricerca</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
