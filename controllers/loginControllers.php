<?php
session_start();
require_once '../managers/database.php'; 

// Verifica che sia una richiesta POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recupero username e password dal form
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Verifica che username e password non siano vuoti
    if (empty($username) || empty($password)) {
        $_SESSION['login_error'] = "Username e password sono obbligatori";
        header("Location: login.php");
        exit;
    }
    
    // Chiama la funzione richiestaLogin dal file database.php
    $utente = verifica_login($username, $password);
    
    if ($utente) {
        // Login riuscito
        $_SESSION['autenticato'] = true;
        $_SESSION['username'] = $username;

        header("Location: ../index.php");
        exit;
    } else {
        // Login fallito
        $_SESSION['login_error'] = "Username o password non validi";
        header("Location: ../login.php");
        exit;
    }
} else {
    // Se non è una richiesta POST, reindirizza alla pagina di login
    header("Location: ../login.php");
    exit;
}
?>