<?php
session_start();
require_once "managers/database.php";

if (!isset($_SESSION['autenticato']) || $_SESSION['autenticato'] !== true) {
    header("Location: login.php");
    exit;
}

$username_corrente = $_SESSION['username'];

if (!isset($_GET['user'])) {
    header("Location: personalArea.php");
    exit;
}

$username_profilo = $_GET['user'];
$profilo = ottieni_profilo_utente($username_profilo);

if (!$profilo) {
    $_SESSION['messaggio'] = "Utente non trovato";
    header("Location: personalArea.php");
    exit;
}

$sta_seguendo = verifica_follower($username_corrente, $username_profilo);

$preferiti = null;
if ($profilo['profilo_pubblico'] || $username_profilo === $username_corrente) {
    $preferiti = ottieni_preferiti_utente($username_profilo);
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Profilo di <?php echo htmlspecialchars($username_profilo); ?> - BookHub</title>
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
                    <h3 class="mb-0">Profilo di <?php echo $username_profilo; ?></h3>
                    <?php 
                        echo '<a href="personalArea.php" class="btn btn-primary">Indietro</a>';
                    ?>
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
                ?>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">Informazioni</h5>
                            </div>
                            <div class="card-body">
                                <p><strong>Username:</strong> <?php echo $profilo['username']; ?></p>
                                <p><strong>Nome:</strong> <?php echo $profilo['nome']; ?></p>
                                <p><strong>Cognome:</strong> <?php echo $profilo['cognome']; ?></p>
                                
                                <div class="d-flex justify-content-between mt-3">
                                    <span class="badge bg-primary p-2">
                                        <i class="bi bi-people-fill"></i> Follower: <?php echo $profilo['followers']; ?>
                                    </span>
                                    <span class="badge bg-info p-2">
                                        <i class="bi bi-person-plus-fill"></i> Seguiti: <?php echo $profilo['followed']; ?>
                                    </span>
                                </div>
                                
                                <?php 
                                if ($username_profilo !== $username_corrente) {
                                    echo '<div class="mt-3">';
                                    if ($sta_seguendo) {
                                        echo '<form action="gestione_follow.php" method="POST">';
                                        echo '<input type="hidden" name="azione" value="unfollow">';
                                        echo '<input type="hidden" name="utente" value="' . $username_profilo . '">';
                                        echo '<button type="submit" class="btn btn-outline-danger w-100">';
                                        echo '<i class="bi bi-person-dash"></i> Non seguire più';
                                        echo '</button>';
                                        echo '</form>';
                                    } else {
                                        echo '<form action="gestione_follow.php" method="POST">';
                                        echo '<input type="hidden" name="azione" value="follow">';
                                        echo '<input type="hidden" name="utente" value="' . $username_profilo . '">';
                                        echo '<button type="submit" class="btn btn-outline-success w-100">';
                                        echo '<i class="bi bi-person-plus"></i> Segui';
                                        echo '</button>';
                                        echo '</form>';
                                    }
                                    echo '</div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">Libri preferiti</h5>
                            </div>
                            <div class="card-body">
                                <?php 
                                if ($preferiti === null) {
                                    echo '<div class="alert alert-secondary">';
                                    echo 'Il profilo di questo utente è privato. Non è possibile visualizzare i suoi libri preferiti.';
                                    echo '</div>';
                                } else if (empty($preferiti)) {
                                    echo '<div class="alert alert-info">';
                                    echo 'Questo utente non ha ancora aggiunto libri ai preferiti.';
                                    echo '</div>';
                                } else {
                                    echo '<div class="row row-cols-1 row-cols-md-3 g-4">';
                                    foreach ($preferiti as $libro) {
                                        echo '<div class="col">';
                                        echo '<div class="card h-100">';
                                        echo '<div class="card-body">';
                                        echo '<h6 class="card-title">ISBN: ' . $libro['isbn'] . '</h6>';
                                        echo '<a href="book.php?isbn=' . $libro['isbn'] . '" class="btn btn-sm btn-outline-primary mt-2">';
                                        echo '<i class="bi bi-book"></i> Visualizza libro';
                                        echo '</a>';
                                        echo '</div>';
                                        echo '</div>';
                                        echo '</div>';
                                    }
                                    echo '</div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card-footer bg-white text-center py-3">
                <a href="index.php" class="text-decoration-none">Torna alla Home</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>