<?php

//funzione per la connessione al db
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
    $password_md5 = md5($password);
    
    $q = "INSERT INTO utenti (username, password, email, nome, cognome) 
            VALUES ('$username', '$password_md5', '$email', '$nome', '$cognome')";
    
    if ($conn->query($q)) {
        $result = true;
    } else {
        $result = false;
    }
    
    $conn->close();
    return $result;
}

//Funzione per verificare il login
function verifica_login($username, $password) {

    $conn = connetti_db();
    $password_md5 = md5($password);
    
    $q = "SELECT id, username, nome, cognome, email FROM utenti 
            WHERE username = '$username' AND password = '$password_md5'";
    
    $result = $conn->query($q);
    
    if ($result && $result->num_rows == 1) {
        $_SESSION['autenticato'] = true;
        $_SESSION['username'] = $username;
        $conn->close();
        return true;
    } else {
        $conn->close();
        return false;
    }
}

//ottiene informazioni da ogni tabella passata come parametro
function ottieni_informazioni($tipo) {
    $conn = connetti_db();
    $risultati = [];
    
    $q = "SELECT id, nome FROM $tipo ORDER BY nome";
    $result = $conn->query($q);
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $risultati[] = $row;
        }
    }
    
    $conn->close();
    return $risultati;
}

function getUserInfo($username) {
    $conn = connetti_db();
    
    $q = "SELECT id, username, nome, cognome, email FROM utenti WHERE username = '$username'";
    $result = $conn->query($q);
    
    if ($result && $result->num_rows == 1) {
        $utente = $result->fetch_assoc();
        $conn->close();
        return $utente;
    } else {
        $conn->close();
        return null;
    }
}


function aggiorna_utente($username, $nome, $cognome, $email, $nuova_password = null) {
    $conn = connetti_db();
    
    $query = "UPDATE utenti SET nome = '$nome', cognome = '$cognome', email = '$email'";
    
    if (!empty($nuova_password)) {
        $password_md5 = md5($nuova_password);
        $query .= ", password = '$password_md5'";
    }

    $query .= " WHERE username = '$username'";
    
    $risultato = $conn->query($query);
    
    $conn->close();
    
    if ($risultato) {
        return true;
    } else {
        return "Errore durante l'aggiornamento" ;
    }
}
?>