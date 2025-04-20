<?php
session_start();
require_once "managers/database.php";

if (!isset($_SESSION['autenticato']) || $_SESSION['autenticato'] !== true) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
$followers = ottieni_followers($username);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>I miei Followers - BookHub</title>
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
                    <h3 class="mb-0">I miei Followers</h3>
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
                
                if (empty($followers)) {
                    echo '<div class="alert alert-info">';
                    echo 'Ancora nessun follower. Condividi il tuo profilo per farti seguire!';
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
                    
                    foreach ($followers as $follower) {
                        echo '<tr>';
                        echo '<td>' . $follower['username'] . '</td>';
                        echo '<td>' . $follower['nome'] . '</td>';
                        echo '<td>' . $follower['cognome'] . '</td>';
                        echo '<td>';
                        echo '<a href="profilo_utente.php?user=' . $follower['username'] . '" class="btn btn-sm btn-outline-primary me-1">';
                        echo '<i class="bi bi-person"></i> Profilo';
                        echo '</a>';
                        
                        if (!verifica_follower($username, $follower['username'])) {
                            if ($follower['username'] != $username) {
                                echo '<form action="gestione_follow.php" method="POST" class="d-inline">';
                                echo '<input type="hidden" name="azione" value="follow">';
                                echo '<input type="hidden" name="utente" value="' . $follower['username'] . '">';
                                echo '<button type="submit" class="btn btn-sm btn-outline-success">';
                                echo '<i class="bi bi-person-plus"></i> Segui anche tu';
                                echo '</button>';
                                echo '</form>';
                            }
                        } else {
                            echo '<form action="gestione_follow.php" method="POST" class="d-inline">';
                            echo '<input type="hidden" name="azione" value="follow">';
                            echo '<input type="hidden" name="utente" value="' . $follower['username'] . '">';
                            echo '<button type="submit" class="btn btn-sm btn-outline-success">';
                            echo '<i class="bi bi-person-plus"></i> Segui anche tu';
                            echo '</button>';
                            echo '</form>';
                        }
                        
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