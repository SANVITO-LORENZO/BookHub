<?php
// File: controllers/registerController.php
session_start();

// Includi il file con le funzioni del database
require_once '../managers/database.php';

// Verifica che la richiesta sia di tipo POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupera i dati dal form
    $nome = trim($_POST["nome"] ?? '');
    $cognome = trim($_POST["cognome"] ?? '');
    $username = trim($_POST["username"] ?? '');
    $email = trim($_POST["email"] ?? '');
    $password = $_POST["password"] ?? '';
    $conferma_password = $_POST["conferma_password"] ?? '';
    $termini = isset($_POST["termini"]);
    
    // Validazione base dei dati
    if (empty($nome) || empty($cognome) || empty($username) || empty($email) || empty($password)) {
        $_SESSION['errore_registrazione'] = "Tutti i campi sono obbligatori";
        header("Location: ../registrazione.php");
        exit;
    }
    
    // Verifica che le password coincidano
    if ($password !== $conferma_password) {
        $_SESSION['errore_registrazione'] = "Le password non coincidono";
        header("Location: ../registrazione.php");
        exit;
    }
    
    // Verifica che l'utente abbia accettato i termini e le condizioni
    if (!$termini) {
        $_SESSION['errore_registrazione'] = "Devi accettare i termini e le condizioni";
        header("Location: ../registrazione.php");
        exit;
    }
    
    // Validazione email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['errore_registrazione'] = "Formato email non valido";
        header("Location: ../registrazione.php");
        exit;
    }
    
    // Lunghezza minima della password
    if (strlen($password) < 8) {
        $_SESSION['errore_registrazione'] = "La password deve contenere almeno 8 caratteri";
        header("Location: ../registrazione.php");
        exit;
    }
    
    // Effettua la registrazione utilizzando la funzione del database
    $risultato = registra_utente($username, $password, $email, $nome, $cognome);
    
    // Verifica il risultato della registrazione
    if (strpos($risultato, 'successo') !== false) {
        $_SESSION['successo_registrazione'] = "Registrazione completata con successo. Ora puoi accedere con le tue credenziali.";
        header("Location: ../login.php");
        exit;
    } else {
        // Se c'è stato un errore nel DB, estrai il messaggio
        $errore = $risultato;
        
        // Gestisci errori comuni come username o email già esistenti
        if (strpos($errore, 'Duplicate entry') !== false) {
            if (strpos($errore, 'username') !== false) {
                $errore = "Username già in uso. Scegli un altro username.";
            } elseif (strpos($errore, 'email') !== false) {
                $errore = "Email già registrata. Utilizza un'altra email o effettua il login.";
            }
        }
        
        $_SESSION['errore_registrazione'] = $errore;
        header("Location: ../registrazione.php");
        exit;
    }
} else {
    // Se la richiesta non è POST, reindirizza alla pagina di registrazione
    header("Location: ../registrazione.php");
    exit;
}
?>