<?php
session_start(); 
require_once 'classes/Book.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['isbn'])) {
        $isbn = $_POST['isbn'];
        header("Location: book.php?isbn=" . $isbn);
        exit;
    }

    if (!empty($_POST['string'])) {
        $query = $_POST['string'];
        $risultati = Book::search($query);

        if (!empty($risultati)) {
            $isbn = $risultati[0]->isbn;
            header("Location: book.php?isbn=" . $isbn);
            exit;
        } else {
            $error = "Nessun libro trovato";
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
    <!-- Riquadro bianco contenente logo, login e form di ricerca -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="flex-grow-1 text-center">
                    <img src="extra/img/logo.jpg" alt="BookHub Logo" style="max-height: 100px;" class="img-fluid">
                </div>
                <div>
                    <?php
                    if (isset($_SESSION['autenticato'])) {
                        echo '<a href="logout.php" class="btn btn-danger">Logout</a>';
                    } else {
                        echo '<a href="login.php" class="btn btn-primary">Login</a>';
                    }
                    ?>
                </div>
            </div>

            <?php 
            if (!empty($error)) {
                echo '<div class="alert alert-warning">' . htmlspecialchars($error) . '</div>';
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

    <!-- Contenuto sotto il riquadro bianco -->
    <div class="text-center mt-4">
        <a href="quiz.php" class="btn btn-outline-dark btn-lg px-4 py-2">
            Scopri il libro perfetto per te con un quiz
        </a>
    </div>

    <div class="text-center mt-3">
        <p class="text-muted">Oppure esplora i <strong>10 libri più amati del mese</strong> (in arrivo!)</p>
    </div>
</div>

</body>
</html>