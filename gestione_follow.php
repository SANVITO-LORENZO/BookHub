<?php
session_start();
require_once "managers/database.php";

if (!isset($_SESSION['autenticato']) || $_SESSION['autenticato'] !== true) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
$redirect = 'cerca_utenti.php';

if (isset($_SERVER['HTTP_REFERER'])) {
    $redirect = $_SERVER['HTTP_REFERER'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['azione']) && isset($_POST['utente'])) {
    $azione = $_POST['azione'];
    $utente = $_POST['utente'];
    $messaggio = '';
    
    if ($azione === 'follow') {
        $risultato = segui_utente($username, $utente);
        if ($risultato === true) {
            $messaggio = "Hai iniziato a seguire $utente";
        } else {
            $messaggio = $risultato; 
        }
    } elseif ($azione === 'unfollow') {
        $risultato = smetti_seguire($username, $utente);
        if ($risultato === true) {
            $messaggio = "Hai smesso di seguire $utente";
        } else {
            $messaggio = "Si è verificato un errore";
        }
    }
    
    if (!empty($messaggio)) {
        $_SESSION['messaggio'] = $messaggio;
    }
}

header("Location: $redirect");
exit;
?>