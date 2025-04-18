<?php
session_start();
require_once '../managers/database.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $_SESSION['login_error'] = "Username e password sono obbligatori";
        header("Location: login.php");
        exit;
    }

    $utente = verifica_login($username, $password);
    
    if ($utente) {
        $_SESSION['autenticato'] = true;
        $_SESSION['username'] = $username;

        header("Location: ../index.php");
        exit;
    } else {
        $_SESSION['login_error'] = "Username o password non validi";
        header("Location: ../login.php");
        exit;
    }
} else {
    header("Location: ../login.php");
    exit;
}
?>