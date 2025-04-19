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
    } 
    else {
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
    } 
    else {
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
    } 
    else {
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
    } 
    else {
        return "Errore durante l'aggiornamento";
    }
}

function getComments($isbn): array {
    $conn = connetti_db();
    $commenti = [];
    
    $q = "SELECT c.testo, u.username, c.data 
          FROM commenti c 
          JOIN utenti u ON c.utente_id = u.id 
          WHERE c.isbn = '$isbn' 
          ORDER BY c.data DESC";
    
    $result = $conn->query($q);
    
    if ($result && $result->num_rows > 0) { 
        while ($row = $result->fetch_assoc()) {
            $commenti[] = $row;
        }
    }
    
    $conn->close();
    return $commenti; 
}

function aggiungiCommento($isbn, $username, $testo) {
    $conn = connetti_db();
    
    $q_utente = "SELECT id FROM utenti WHERE username = '$username'";
    $result = $conn->query($q_utente);
    
    if (!$result || $result->num_rows === 0) {
        $conn->close();
        return false;
    }
    
    $utente = $result->fetch_assoc();
    $utente_id = $utente['id'];
    
    $data = date('Y-m-d H:i:s');
    
    $q_commento = "INSERT INTO commenti (isbn, utente_id, testo, data) 
                  VALUES ('$isbn', '$utente_id', '$testo', '$data')";
    
    $risultato = $conn->query($q_commento);
    
    $conn->close();
    if ($risultato) {
        return true;
    } else {
        return false;
    }
}

function aggiungiPreferito($isbn, $username) {
    $conn = connetti_db();
    

    $q_utente = "SELECT id FROM utenti WHERE username = '$username'";
    $result = $conn->query($q_utente);
    
    if (!$result || $result->num_rows == 0) {
        $conn->close();
        return "Utente non trovato";
    }
    
    $utente = $result->fetch_assoc();
    $utente_id = $utente['id'];
    $q_verifica = "SELECT id FROM preferiti WHERE isbn = '$isbn' AND utente_id = $utente_id";
    $result_verifica = $conn->query($q_verifica);
    
    if ($result_verifica && $result_verifica->num_rows > 0) {
        $conn->close();
        return "Questo libro è già nei tuoi preferiti";
    }

    $q_preferito = "INSERT INTO preferiti (isbn, utente_id) 
                   VALUES ('$isbn', '$utente_id')";
    
    $risultato = $conn->query($q_preferito);
    
    $conn->close();
    if ($risultato) {
        return true;
    } else {
        return "Errore durante l'aggiunta ai preferiti";
    }
}

function rimuoviPreferito($isbn, $username) {
    $conn = connetti_db();
    
    $q_utente = "SELECT id FROM utenti WHERE username = '$username'";
    $result = $conn->query($q_utente);
    
    if (!$result || $result->num_rows === 0) {
        $conn->close();
        return false;
    }
    
    $utente = $result->fetch_assoc();
    $utente_id = $utente['id'];
    
    $q_rimuovi = "DELETE FROM preferiti WHERE isbn = '$isbn' AND utente_id = $utente_id";
    $risultato = $conn->query($q_rimuovi);
    
    $conn->close();
    if ($risultato) {
        return true;
    } else {
        return false;
    }
}


function getPreferiti($username) {
    $conn = connetti_db();
    $preferiti = [];
    
    $q_utente = "SELECT id FROM utenti WHERE username = '$username'";
    $result = $conn->query($q_utente);
    
    if (!$result || $result->num_rows === 0) {
        $conn->close();
        return [];
    }
    
    $utente = $result->fetch_assoc();
    $utente_id = $utente['id'];
    
    $q_preferiti = "SELECT isbn FROM preferiti WHERE utente_id = $utente_id";
    
    $result_preferiti = $conn->query($q_preferiti);
    
    if ($result_preferiti && $result_preferiti->num_rows > 0) {
        while ($row = $result_preferiti->fetch_assoc()) {
            $preferiti[] = $row;
        }
    }
    
    $conn->close();
    return $preferiti;
}

function getTopPreferiti($numero = 5) {
    $conn = connetti_db();
    $top_preferiti = [];
    
    $query = "SELECT p.isbn, COUNT(p.isbn) as conteggio 
              FROM preferiti p 
              GROUP BY p.isbn 
              ORDER BY conteggio DESC 
              LIMIT $numero";
    
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $top_preferiti[] = $row['isbn'];
        }
    }
    
    $conn->close();
    return $top_preferiti;
}
?>