<?php
session_start();

require_once '../classes/user.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["nome"])) {
        $nome = $_POST["nome"];
    } else {
        $nome = '';
    }

    if (isset($_POST["cognome"])) {
        $cognome = $_POST["cognome"];
    } else {
        $cognome = '';
    }

    if (isset($_POST["username"])) {
        $username = $_POST["username"];
    } else {
        $username = '';
    }

    if (isset($_POST["email"])) {
        $email = $_POST["email"];
    } else {
        $email = '';
    }

    if (isset($_POST["password"])) {
        $password = $_POST["password"];
    } else {
        $password = '';
    }

    if (isset($_POST["conferma_password"])) {
        $conferma_password = $_POST["conferma_password"];
    } else {
        $conferma_password = '';
    }
    
    $user = new User();
    
    $user->setData($nome, $cognome, $username, $email, $password);
    
    if (!$user->verifyPasswords($password, $conferma_password)) {
        $_SESSION['errore_registrazione'] = "Le password non coincidono";
        header("Location: ../registrazione.php");
        exit;
    }
    
    $result = $user->validateData();
    if ($result !== true) {
        $_SESSION['errore_registrazione'] = $result;
        header("Location: ../registrazione.php");
        exit;
    }
    
    $risultato = $user->register();

    if ($risultato === true) {
        header("Location: ../login.php");
        exit;
    } else {
        $_SESSION['errore_registrazione'] = $risultato;
        header("Location: ../registrazione.php");
        exit;
    }
} else {
    header("Location: ../registrazione.php");
    exit;
}
?>