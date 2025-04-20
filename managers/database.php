<?php
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

function getComments($isbn) {
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

function cerca_utenti($termine_ricerca) {
    $conn = connetti_db();
    $utenti = [];
    
    $termine_ricerca = $conn->real_escape_string($termine_ricerca);
    
    $q = "SELECT id, username, nome, cognome, profilo_pubblico 
          FROM utenti 
          WHERE username LIKE '%$termine_ricerca%' 
             OR nome LIKE '%$termine_ricerca%' 
             OR cognome LIKE '%$termine_ricerca%'
          ORDER BY username";
    
    $result = $conn->query($q);
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $utenti[] = $row;
        }
    }
    
    $conn->close();
    return $utenti;
}

function ottieni_profilo_utente($username) {
    $conn = connetti_db();
    
    $username = $conn->real_escape_string($username);
    
    $q = "SELECT id, username, nome, cognome, email, profilo_pubblico FROM utenti 
          WHERE username = '$username'";
    
    $result = $conn->query($q);
    
    if ($result && $result->num_rows == 1) {
        $profilo = $result->fetch_assoc();
        
        $profilo['followers'] = conta_followers($profilo['id']);
        $profilo['followed'] = conta_followed($profilo['id']);
        
        $conn->close();
        return $profilo;
    } else {
        $conn->close();
        return null;
    }
}

function conta_followers($utente_id) {
    $conn = connetti_db();
    
    $utente_id = $conn->real_escape_string($utente_id);
    
    $q = "SELECT COUNT(*) as totale FROM followers 
          WHERE followed_id = $utente_id";
    
    $result = $conn->query($q);
    
    if ($result && $row = $result->fetch_assoc()) {
        $totale = $row['totale'];
        $conn->close();
        return $totale;
    } else {
        $conn->close();
        return 0;
    }
}

function conta_followed($utente_id) {
    $conn = connetti_db();
    
    $utente_id = $conn->real_escape_string($utente_id);
    
    $q = "SELECT COUNT(*) as totale FROM followers 
          WHERE follower_id = $utente_id";
    
    $result = $conn->query($q);
    
    if ($result && $row = $result->fetch_assoc()) {
        $totale = $row['totale'];
        $conn->close();
        return $totale;
    } else {
        $conn->close();
        return 0;
    }
}

function verifica_follower($follower_username, $followed_username) {
    $conn = connetti_db();
    
    $follower_username = $conn->real_escape_string($follower_username);
    $followed_username = $conn->real_escape_string($followed_username);
    
    $q = "SELECT COUNT(*) as esiste FROM followers f
          JOIN utenti u1 ON f.follower_id = u1.id
          JOIN utenti u2 ON f.followed_id = u2.id
          WHERE u1.username = '$follower_username' 
          AND u2.username = '$followed_username'";
    
    $result = $conn->query($q);
    
    if ($result && $row = $result->fetch_assoc()) {
        $conn->close();
        return $row['esiste'] > 0;
    } else {
        $conn->close();
        return false;
    }
}

function segui_utente($follower_username, $followed_username) {
    $conn = connetti_db();
    
    $q_follower = "SELECT id FROM utenti WHERE username = '$follower_username'";
    $q_followed = "SELECT id FROM utenti WHERE username = '$followed_username'";
    
    $result_follower = $conn->query($q_follower);
    $result_followed = $conn->query($q_followed);
    
    if (!$result_follower || !$result_followed || 
        $result_follower->num_rows === 0 || $result_followed->num_rows === 0) {
        $conn->close();
        return false;
    }
    
    $follower = $result_follower->fetch_assoc();
    $followed = $result_followed->fetch_assoc();
    
    $follower_id = $follower['id'];
    $followed_id = $followed['id'];
    
    $q_verifica = "SELECT id FROM followers 
                  WHERE follower_id = $follower_id AND followed_id = $followed_id";
    
    $result_verifica = $conn->query($q_verifica);
    
    if ($result_verifica && $result_verifica->num_rows > 0) {
        $conn->close();
        return "Segui già questo utente";
    }
    
    $q_segui = "INSERT INTO followers (follower_id, followed_id) 
               VALUES ($follower_id, $followed_id)";
    
    $risultato = $conn->query($q_segui);
    
    $conn->close();
    if ($risultato) {
        return true;
    } else {
        return "Errore durante l'operazione";
    }
}

function smetti_seguire($follower_username, $followed_username) {
    $conn = connetti_db();
    
    $q_follower = "SELECT id FROM utenti WHERE username = '$follower_username'";
    $q_followed = "SELECT id FROM utenti WHERE username = '$followed_username'";
    
    $result_follower = $conn->query($q_follower);
    $result_followed = $conn->query($q_followed);
    
    if (!$result_follower || !$result_followed || 
        $result_follower->num_rows === 0 || $result_followed->num_rows === 0) {
        $conn->close();
        return false;
    }
    
    $follower = $result_follower->fetch_assoc();
    $followed = $result_followed->fetch_assoc();
    
    $follower_id = $follower['id'];
    $followed_id = $followed['id'];
    
    $q_rimuovi = "DELETE FROM followers 
                 WHERE follower_id = $follower_id AND followed_id = $followed_id";
    
    $risultato = $conn->query($q_rimuovi);
    
    $conn->close();
    if ($risultato) {
        return true;
    } else {
        return false;
    }
}

function ottieni_seguiti($username) {
    $conn = connetti_db();
    $seguiti = [];
    
    $username = $conn->real_escape_string($username);
    
    $q = "SELECT u.username, u.nome, u.cognome 
          FROM followers f
          JOIN utenti u ON f.followed_id = u.id
          JOIN utenti u2 ON f.follower_id = u2.id
          WHERE u2.username = '$username'
          ORDER BY u.username";
    
    $result = $conn->query($q);
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $seguiti[] = $row;
        }
    }
    
    $conn->close();
    return $seguiti;
}

function ottieni_followers($username) {
    $conn = connetti_db();
    $followers = [];
    
    $username = $conn->real_escape_string($username);
    
    $q = "SELECT u.username, u.nome, u.cognome 
          FROM followers f
          JOIN utenti u ON f.follower_id = u.id
          JOIN utenti u2 ON f.followed_id = u2.id
          WHERE u2.username = '$username'
          ORDER BY u.username";
    
    $result = $conn->query($q);
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $followers[] = $row;
        }
    }
    
    $conn->close();
    return $followers;
}

function ottieni_preferiti_utente($username) {
    $conn = connetti_db();
    $preferiti = [];
    
    $username = $conn->real_escape_string($username);
    
    $q_privacy = "SELECT profilo_pubblico FROM utenti WHERE username = '$username'";
    $result_privacy = $conn->query($q_privacy);
    
    if (!$result_privacy || $result_privacy->num_rows === 0) {
        $conn->close();
        return null;
    }
    
    $privacy = $result_privacy->fetch_assoc();
    
    if (!$privacy['profilo_pubblico']) {
        $conn->close();
        return false;
    }
    
    $q_preferiti = "SELECT p.isbn FROM preferiti p
                   JOIN utenti u ON p.utente_id = u.id
                   WHERE u.username = '$username'";
    
    $result_preferiti = $conn->query($q_preferiti);
    
    if ($result_preferiti && $result_preferiti->num_rows > 0) {
        while ($row = $result_preferiti->fetch_assoc()) {
            $preferiti[] = $row;
        }
    }
    
    $conn->close();
    return $preferiti;
}

function aggiorna_privacy_profilo($username, $pubblico) {
    $conn = connetti_db();
    
    $username = $conn->real_escape_string($username);
    if ($pubblico) {
        $pubblico = 1;
    } else {
        $pubblico = 0;
    }
    
    $q = "UPDATE utenti SET profilo_pubblico = $pubblico WHERE username = '$username'";
    
    $risultato = $conn->query($q);
    
    $conn->close();
    if ($risultato) {
        return true;
    } else {
        return false;
    }
}
?>