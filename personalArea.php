<?php
session_start();
require_once "managers/database.php";

if (!isset($_SESSION['autenticato']) || $_SESSION['autenticato'] !== true) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
$profilo = ottieni_profilo_utente($username);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Area Personale - BookHub</title>
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
                    <h3 class="mb-0">Area Personale</h3>
                    <a href="logout.php" class="btn btn-danger">Logout</a>
                </div>
            </div>
            
            <div class="card-body p-4">
                <div class="alert alert-success">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4>Benvenuto, <?php echo $username; ?>!</h4>
                        <div>
                            <span class="badge bg-primary me-2">
                                <i class="bi bi-people-fill"></i> Follower: <?php echo $profilo['followers']; ?>
                            </span>
                            <span class="badge bg-info">
                                <i class="bi bi-person-plus-fill"></i> Seguiti: <?php echo $profilo['followed']; ?>
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <form action="cerca_utenti.php" method="GET" class="d-flex">
                        <input type="text" name="q" class="form-control me-2" placeholder="Cerca utenti...">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Cerca
                        </button>
                    </form>
                </div>
                
                <div class="row mt-4 g-3">
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">Il mio profilo</h5>
                                <p class="card-text">Modifica le tue informazioni e la tua password.</p>
                                <a href="modifica.php" class="btn btn-outline-primary">Modifica profilo</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">I miei libri preferiti</h5>
                                <p class="card-text">Visualizza la tua lista di libri preferiti.</p>
                                <a href="preferiti.php" class="btn btn-outline-primary">Visualizza preferiti</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">Social</h5>
                                <p class="card-text">Gestisci i tuoi follower e seguiti.</p>
                                <div class="d-flex flex-column">
                                    <a href="followers.php" class="btn btn-outline-primary mb-2">I miei follower</a>
                                    <a href="seguiti.php" class="btn btn-outline-primary">Utenti seguiti</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <div class="card">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Impostazioni privacy</h5>
                        </div>
                        <div class="card-body">
                            <form action="aggiorna_privacy.php" method="POST">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" 
                                           id="profiloPubblico" name="profilo_pubblico" 
                                           <?php if($profilo['profilo_pubblico']) echo 'checked'; ?>>
                                    <label class="form-check-label" for="profiloPubblico">Profilo pubblico</label>
                                </div>
                                <button type="submit" class="btn btn-sm btn-outline-primary mt-2">
                                    Salva impostazioni
                                </button>
                            </form>
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