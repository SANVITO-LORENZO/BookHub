<?php
session_start();
require_once "managers/database.php";

if (!isset($_SESSION['autenticato']) || $_SESSION['autenticato'] !== true) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
if (isset($_GET['q'])) {
    $ricerca = $_GET['q'];
} else {
    $ricerca = '';
}
$utenti = [];

if (!empty($ricerca)) {
    $utenti = cerca_utenti($ricerca);
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Cerca Utenti - BookHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">

<div class="container py-3">
    <div class="card shadow">
        <div class="text-center mb-4 mt-4">
            <a href="index.php">
                <img src="extra/img/logo.jpg" style="max-height: 80px;" class="img-fluid">
            </a>
        </div>
        
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="mb-0">Cerca Utenti</h3>
                <a href="personalArea.php" class="btn btn-primary">Torna all'area personale</a>
            </div>
        </div>
        
        <div class="card-body p-4">
            <div class="mb-4">
                <form action="cerca_utenti.php" method="GET" class="d-flex">
                    <input type="text" name="q" class="form-control me-2" placeholder="Cerca utenti..." value="<?php echo $ricerca; ?>">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Cerca
                    </button>
                </form>
            </div>
            
            <?php
            if (!empty($ricerca)) {
                echo '<h4>Risultati per "' . $ricerca . '"</h4>';
                
                if (empty($utenti)) {
                    echo '<div class="alert alert-info">
                            Nessun utente trovato per questa ricerca.
                          </div>';
                } else {
                    echo '<div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Username</th>
                                        <th>Nome</th>
                                        <th>Cognome</th>
                                        <th>Stato</th>
                                        <th>Azioni</th>
                                    </tr>
                                </thead>
                                <tbody>';
                                
                    foreach ($utenti as $utente) {
                        if ($utente['username'] != $username) {
                            echo '<tr>
                                <td>' . $utente['username'] . '</td>
                                <td>' . $utente['nome'] . '</td>
                                <td>' . $utente['cognome'] . '</td>
                                <td>';
                                
                            if ($utente['profilo_pubblico']) {
                                echo '<span class="badge bg-success">Pubblico</span>';
                            } else {
                                echo '<span class="badge bg-secondary">Privato</span>';
                            }
                            
                            echo '</td>
                                <td>
                                    <a href="profilo_utente.php?user=' . $utente['username'] . '" class="btn btn-sm btn-outline-primary me-1">
                                        <i class="bi bi-person"></i> Profilo
                                    </a>';
                                    
                            if (verifica_follower($username, $utente['username'])) {
                                echo '<form action="gestione_follow.php" method="POST" class="d-inline">
                                        <input type="hidden" name="azione" value="unfollow">
                                        <input type="hidden" name="utente" value="' . $utente['username'] . '">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-person-dash"></i> Non seguire più
                                        </button>
                                    </form>';
                            } else {
                                echo '<form action="gestione_follow.php" method="POST" class="d-inline">
                                        <input type="hidden" name="azione" value="follow">
                                        <input type="hidden" name="utente" value="' . $utente['username'] . '">
                                        <button type="submit" class="btn btn-sm btn-outline-success">
                                            <i class="bi bi-person-plus"></i> Segui
                                        </button>
                                    </form>';
                            }
                            
                            echo '</td>
                            </tr>';
                        }
                    }
                    
                    echo '</tbody>
                        </table>
                    </div>';
                }
            }
            ?>
        </div>
        
        <div class="card-footer bg-white text-center py-3">
            <a href="index.php" class="text-decoration-none">Torna alla Home</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>