<?php
session_start(); 
require_once 'classes/book.php';
require_once 'managers/database.php'; 
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['isbn']) && $_POST['isbn'] != '') {
        $isbn = $_POST['isbn'];
        header("Location: book.php?isbn=" . $isbn);
        exit;
    }
    
    if (isset($_POST['string']) && $_POST['string'] != '') {
        $query = $_POST['string'];
        $risultati = Book::search($query);
        
        if ($risultati && count($risultati) > 0) {
            $isbn = $risultati[0]->isbn;
            header("Location: book.php?isbn=" . $isbn);
            exit;
        } else {
            $error = "Nessun libro trovato";
        }
    }
}

$top_libri_isbn = getTopPreferiti(5);
$top_libri = [];

if ($top_libri_isbn) {
    foreach ($top_libri_isbn as $isbn) {
        $libro = Book::fromISBN($isbn);
        if ($libro) {
            $top_libri[] = $libro;
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
    <link rel="stylesheet" href="extra/style.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="flex-grow-1 text-center">
                    <img src="extra/img/logo.jpg" alt="BookHub Logo" style="max-height: 100px;" class="img-fluid">
                </div>
                <div>
                    <?php
                    if (isset($_SESSION['autenticato']) && $_SESSION['autenticato']) {
                        echo '<a href="personalArea.php">
                                <img src="extra/img/user-icon.jpg" alt="Area Personale" title="Area Personale" class="user-icon">
                              </a>';
                    } else {
                        echo '<a href="login.php" class="btn btn-primary">Login</a>';
                    }
                    ?>
                </div>
            </div>

            <?php 

            if ($error) {
                echo '<div class="alert alert-warning">' . $error . '</div>';
            }
            ?>

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
                            <input type="text" name="string" class="form-control" placeholder="Titolo, autore, genere...">
                            <button class="btn btn-success" type="submit">Cerca</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-4">
        <a href="quiz.php" class="btn btn-outline-dark btn-lg px-4 py-2">
            Scopri il libro perfetto per te con un quiz
        </a>
    </div>

    <div class="mt-5">
        <h3 class="text-center mb-4">I 5 libri più amati del mese</h3>
        
        <?php 
        if (empty($top_libri)) { 
        ?>
            <div class="alert alert-info text-center">
                Non ci sono ancora libri nei preferiti, sii il primo ad aggiungerne!
            </div>
        <?php 
        } else { 
        ?>
            <div class="row row-cols-1 row-cols-md-5 g-4">
            <?php 
            foreach ($top_libri as $libro) { 
            ?>
                <div class="col">
                    <div class="card h-100">
                        <?php 
                        if ($libro->copertina) {
                        ?>
                            <img src="<?= $libro->copertina ?>" class="card-img-top" alt="<?= $libro->titolo ?>">
                        <?php 
                        } else { 
                        ?>
                            <div class="card-img-top bg-secondary text-white d-flex justify-content-center align-items-center" style="height: 200px;">
                                <span>Copertina non disponibile</span>
                            </div>
                        <?php 
                        } 
                        ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= $libro->titolo ?></h5>
                            <p class="card-text text-muted"><?= $libro->autori ?></p>
                            <a href="book.php?isbn=<?= $libro->isbn ?>" class="btn btn-sm btn-primary">Dettagli</a>
                        </div>
                    </div>
                </div>
            <?php 
            } 
            ?>
            </div>
        <?php 
        } 
        ?>
    </div>
</div>

</body>
</html>