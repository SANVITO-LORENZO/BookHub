<?php
session_start();
require_once 'classes/book.php';
require_once 'managers/render.php';

$libro = null;
$commenti = [];
$isPreferito = false;

if (isset($_GET['isbn'])) {
    $isbn = trim($_GET['isbn']);
    $libro = Book::fromISBN($isbn);
    
    if ($libro) {
        $commenti = $libro->getComments();
        if (isset($_SESSION['username'])) {
            $isPreferito = $libro->isPreferito($_SESSION['username']);
        }
    }
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
                    <img src="extra/img/logo.jpg" style="max-height: 50px;" class="img-fluid">
                </a>
            </div>
            <div class="card-body">
                <?php render::renderBookDetails($libro, $commenti, $isPreferito); ?>
            </div>
        </div>
    </div>
</body>
</html>