<?php
session_start();
require_once "managers/database.php";

if (!isset($_SESSION['autenticato']) || $_SESSION['autenticato'] !== true) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $profilo_pubblico = isset($_POST['profilo_pubblico']) ? true : false;
    
    $risultato = aggiorna_privacy_profilo($username, $profilo_pubblico);
    
    if ($risultato === true) {
        $_SESSION['messaggio'] = "Impostazioni di privacy aggiornate con successo";
    } else {
        $_SESSION['messaggio'] = "Si è verificato un errore durante l'aggiornamento delle impostazioni";
    }
}

header("Location: personalArea.php");
exit;
?>