<?php
session_start();
require_once 'classes/book.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

if (isset($_POST['isbn'])) {
    $isbn = trim($_POST['isbn']);
    $libro = Book::fromISBN($isbn);
    
    if ($libro) {
        $risultato = $libro->rimuoviDaiPreferiti($_SESSION['username']);
        
        if ($risultato) {
            header('Location: book.php?isbn=' . $isbn );
        } else {

            header('Location: book.php?isbn=' . $isbn );
        }
    } else {
        header('Location: index.php');
    }
} else {
    header('Location: index.php?');
}
exit;
?>