<?php
session_start();

require_once '../classes/user.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"] ?? '';
    $cognome = $_POST["cognome"] ?? '';
    $username = $_POST["username"] ?? '';
    $email = $_POST["email"] ?? '';
    $password = $_POST["password"] ?? '';
    $conferma_password = $_POST["conferma_password"] ?? '';
    
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