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
    // Verifica che tutti i campi necessari siano presenti
    if (isset($_POST['isbn']) && isset($_POST['commento']) && !empty($_POST['commento'])) {
        $isbn = trim($_POST['isbn']);
        $commento = trim($_POST['commento']);
        $username = $_SESSION['username'];
        
        // Validazione di base
        if (empty($isbn) || empty($commento)) {
            header("Location: book.php?isbn=$isbn&errore=campi_vuoti");
            exit;
        }
        
        // Chiama la funzione per aggiungere il commento
        $risultato = aggiungiCommento($isbn, $username, $commento);
        
        if ($risultato === true) {
            // Commento aggiunto con successo
            header("Location: book.php?isbn=$isbn&successo=commento_aggiunto");
        } else {
            // Errore nell'aggiunta del commento
            header("Location: book.php?isbn=$isbn&errore=commento_fallito");
        }
    } else {
        // Parametri mancanti
        header("Location: index.php?errore=parametri_mancanti");
    }
} else {
    // Accesso diretto alla pagina senza POST
    header("Location: index.php");
}
exit;
?>