<?php
session_start();
require_once 'classes/book.php';


if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}


if (isset($_POST['isbn']) && isset($_POST['commento']) && !empty($_POST['commento'])) {
    $isbn = $_POST['isbn'];
    $commento = $_POST['commento'];
    $libro = Book::fromISBN($isbn);
    
    if ($libro) {
        $risultato = $libro->aggiungiCommento($_SESSION['username'], $commento);
        
        if ($risultato) {
            header('Location: book.php?isbn=' . $isbn);
        } else {
            header('Location: book.php?isbn=' . $isbn);
        }
    } else {
        header('Location: index.php');
    }
} else {
    if (isset($_POST['isbn'])) {
        $isbn = $_POST['isbn'];
    } else {
        $isbn = '';
    }
    header("Location: book.php?isbn=" . $isbn);
    exit;
}
exit;