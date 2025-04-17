<?php
require_once 'classes/Book.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['isbn'])) {
        $isbn = trim($_POST['isbn']);
        header("Location: book.php?isbn=" . urlencode($isbn));
        exit;
    }

    if (!empty($_POST['string'])) {
        $query = trim($_POST['string']);
        $risultati = Book::search($query);

        if (!empty($risultati)) {
            $isbn = $risultati[0]->isbn;
            header("Location: book.php?isbn=" . urlencode($isbn));
            exit;
        } else {
            $error = "Nessun libro trovato con la ricerca inserita.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h1 class="mb-4 text-center">Benvenuto su BookHub</h1>

    <?php if (!empty($error)): ?>
        <div class="alert alert-warning"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-6">
            <h4>Cerca per ISBN</h4>
            <form method="POST" class="mb-4">
                <div class="input-group">
                    <input type="text" name="isbn" class="form-control" placeholder="Inserisci ISBN">
                    <button class="btn btn-primary" type="submit">Cerca</button>
                </div>
            </form>
        </div>

        <div class="col-md-6">
            <h4>Cerca per parole chiave</h4>
            <form method="POST" class="mb-4">
                <div class="input-group">
                    <input type="text" name="string" class="form-control">
                    <button class="btn btn-success" type="submit">Cerca</button>
                </div>
            </form>
        </div>
    </div>

    <hr>

    <div class="text-center mt-4">
        <p>Oppure esplora i <strong>10 libri più amati del mese</strong> (da implementare)</p>
        <!-- Qui potrai aggiungere una sezione con i libri top, solo immagini -->
    </div>
</div>

</body>
</html>
