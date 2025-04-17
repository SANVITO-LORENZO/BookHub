<?php
// File: database.php

// Funzione per la connessione al database (mysqli)
function connetti_db() {
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "bookhub";
    
    $conn = new mysqli($host, $username, $password, $database);
    
    if ($conn->connect_error) {
        die("Connessione al database fallita: " . $conn->connect_error);
    }
    
    return $conn;
}

// Funzione per registrare un nuovo utente
function registra_utente($username, $password, $email, $nome, $cognome) {
    $conn = connetti_db();
    
    // Pulisci i dati in input
    $username = $conn->real_escape_string($username);
    $email = $conn->real_escape_string($email);
    $nome = $conn->real_escape_string($nome);
    $cognome = $conn->real_escape_string($cognome);
    
    // Cripta la password con MD5
    $password_md5 = md5($password);
    
    // Query per inserire il nuovo utente
    $sql = "INSERT INTO utenti (username, password, email, nome, cognome) 
            VALUES ('$username', '$password_md5', '$email', '$nome', '$cognome')";
    
    if ($conn->query($sql) === TRUE) {
        $result = "Utente registrato con successo";
    } else {
        $result = "Errore nella registrazione: " . $conn->error;
    }
    
    $conn->close();
    return $result;
}

// Funzione per verificare le credenziali di login
function verifica_login($username, $password) {
    $conn = connetti_db();
    
    // Pulisci i dati in input
    $username = $conn->real_escape_string($username);
    
    // Cripta la password con MD5 per confrontarla con quella nel database
    $password_md5 = md5($password);
    
    // Query per verificare le credenziali
    $sql = "SELECT id, username, nome, cognome, email FROM utenti 
            WHERE username = '$username' AND password = '$password_md5'";
    
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows == 1) {
        $_SESSION['autenticato'] = true;
        $_SESSION['username'] = $username;
        
        $conn->close();
    } else {
        // Credenziali non valide
        $conn->close();
        return false;
    }
}

// Funzione generica per ottenere elementi da qualsiasi tabella
function ottieni_informazioni($tipo) {
    $conn = connetti_db();
    $risultati = [];
    
    $sql = "SELECT id, nome FROM $tipo ORDER BY nome";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $risultati[] = $row;
        }
    }
    
    $conn->close();
    return $risultati;
}
?>