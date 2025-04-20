<?php
session_start();
require_once "managers/database.php";

if (!isset($_SESSION['autenticato']) || $_SESSION['autenticato'] !== true) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
$seguiti = ottieni_seguiti($username);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Utenti Seguiti - BookHub</title>
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
                    <h3 class="mb-0">Utenti Seguiti</h3>
                    <a href="personalArea.php" class="btn btn-primary">Torna all'area personale</a>
                </div>
            </div>
            
            <div class="card-body p-4">
                <?php
                if (isset($_SESSION['messaggio'])) {
                    echo '<div class="alert alert-success">';
                    echo $_SESSION['messaggio'];
                    unset($_SESSION['messaggio']);
                    echo '</div>';
                }
                
                if (empty($seguiti)) {
                    echo '<div class="alert alert-info">';
                    echo 'Non stai seguendo nessun utente. Utilizza la barra di ricerca per trovare nuovi utenti da seguire!';
                    echo '</div>';
                    echo '<div class="text-center mt-3">';
                    echo '<a href="cerca_utenti.php" class="btn btn-primary">';
                    echo '<i class="bi bi-search"></i> Cerca utenti';
                    echo '</a>';
                    echo '</div>';
                } else {
                    echo '<div class="table-responsive">';
                    echo '<table class="table table-hover">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th>Username</th>';
                    echo '<th>Nome</th>';
                    echo '<th>Cognome</th>';
                    echo '<th>Azioni</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                    
                    foreach ($seguiti as $seguito) {
                        echo '<tr>';
                        echo '<td>' . $seguito['username'] . '</td>';
                        echo '<td>' . $seguito['nome'] . '</td>';
                        echo '<td>' . $seguito['cognome'] . '</td>';
                        echo '<td>';
                        echo '<a href="profilo_utente.php?user=' . $seguito['username'] . '" class="btn btn-sm btn-outline-primary me-1">';
                        echo '<i class="bi bi-person"></i> Profilo';
                        echo '</a>';
                        
                        echo '<form action="gestione_follow.php" method="POST" class="d-inline">';
                        echo '<input type="hidden" name="azione" value="unfollow">';
                        echo '<input type="hidden" name="utente" value="' . $seguito['username'] . '">';
                        echo '<button type="submit" class="btn btn-sm btn-outline-danger">';
                        echo '<i class="bi bi-person-dash"></i> Non seguire più';
                        echo '</button>';
                        echo '</form>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    
                    echo '</tbody>';
                    echo '</table>';
                    echo '</div>';
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