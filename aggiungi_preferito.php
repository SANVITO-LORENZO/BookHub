<?php
session_start();
require_once 'managers/database.php';

// Controlla se l'utente è autenticato
if (!isset($_SESSION['autenticato']) || !$_SESSION['autenticato']) {
    // Reindirizza alla pagina di login con un messaggio di errore
    header("Location: login.php?errore=auth");
    exit;
}

// Controlla se il form è stato inviato via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica che l'ISBN sia presente
    if (isset($_POST['isbn']) && !empty($_POST['isbn'])) {
        $isbn = trim($_POST['isbn']);
        $username = $_SESSION['username'];
        
        // Chiama la funzione per aggiungere ai preferiti
        $risultato = aggiungiPreferito($isbn, $username);
        
        if ($risultato === true) {
            // Libro aggiunto ai preferiti con successo
            header("Location: book.php?isbn=$isbn&successo=preferito_aggiunto");
        } else {
            // Errore nell'aggiunta ai preferiti o libro già presente
            $errore = urlencode($risultato);
            header("Location: book.php?isbn=$isbn&errore=$errore");
        }
    } else {
        // ISBN mancante
        header("Location: index.php?errore=isbn_mancante");
    }
} else {
    // Accesso diretto alla pagina senza POST
    header("Location: index.php");
}
exit;
?>