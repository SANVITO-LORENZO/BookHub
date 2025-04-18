<?php
session_start();
if (!isset($_SESSION['autenticato']) || $_SESSION['autenticato'] !== true) {
    header("Location: login.php");
    exit;
}
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Area Personale - BookHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">



<div class="container py-3">

    <div class="card shadow">
    <div class="text-center mb-4 mt-4">
    <a href="index.php">
        <img src="extra/img/logo.jpg" alt="BookHub Logo" style="max-height: 80px;" class="img-fluid">
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
                <h4>Benvenuto, <?php echo $username; ?>!</h4>
            </div>
            
            <div class="row mt-4">
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Il mio profilo</h5>
                            <p class="card-text">Gestisci le tue informazioni personali e modifica la password.</p>
                            <a href="modifica.php" class="btn btn-outline-primary">Modifica profilo</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">I miei libri preferiti</h5>
                            <p class="card-text">Accedi alla tua lista di libri preferiti.</p>
                            <a href="#" class="btn btn-outline-primary">Visualizza preferiti</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">La mia lista lettura</h5>
                            <p class="card-text">Gestisci la tua lista dei libri da leggere.</p>
                            <a href="#" class="btn btn-outline-primary">Visualizza lista</a>
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

</body>
</html>