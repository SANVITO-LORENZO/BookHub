<?php
session_start();
require_once 'classes/book.php';
require_once 'managers/database.php';
require_once 'managers/render.php';  

if (!isset($_SESSION['autenticato']) || !$_SESSION['autenticato']) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
$preferiti = [];

if (isset($_GET['rimuovi']) && !empty($_GET['rimuovi'])) {
    $isbn_da_rimuovere = $_GET['rimuovi'];
    $risultato = rimuoviPreferito($isbn_da_rimuovere, $username);
    
    if ($risultato) {
        header("Location: preferiti.php?successo=rimosso");
    }
    else {
        header("Location: preferiti.php?errore=rimozione");
    }
    exit;
}

$preferiti_isbn = getPreferiti($username);
$preferiti = [];
foreach ($preferiti_isbn as $row) {
    $libro = Book::fromISBN($row['isbn']);
    if ($libro) {
        $preferiti[] = $libro;
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>I Miei Preferiti - BookHub</title>
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
                <h2 class="mb-4">I Miei Libri Preferiti</h2>
                
                <?php render::renderMessaggiPreferiti(); ?>
                
                <div class="mb-3">
                    <a href="index.php" class="btn btn-primary">Torna alla Home</a>
                    <?php 
                    if (isset($_SESSION['username'])) {
                        echo '<a href="personalArea.php" class="btn btn-outline-primary ms-2">Il Mio Profilo</a>';
                    }
                    ?>
                </div>
                
                <?php render::renderPreferiti($preferiti); ?>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>