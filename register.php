<?php
session_start();
$errore = isset($_SESSION['errore_registrazione']) ? $_SESSION['errore_registrazione'] : '';

if(isset($_SESSION['errore_registrazione'])) unset($_SESSION['errore_registrazione']);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Registrazione - BookHub</title>
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
        <div class="card-header bg-white text-center py-3">
            <h3>Registrazione</h3>
            <p class="text-muted">Crea un nuovo account per accedere a tutte le funzionalità</p>
        </div>
        
        <div class="card-body p-4">
            <?php if(!empty($errore)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($errore); ?></div>
            <?php endif; ?>
            
            <?php if(!empty($successo)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($successo); ?></div>
            <?php endif; ?>
            
            <form action="controllers/registerControllers.php" method="POST">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="nome" name="nome" required>
                    </div>
                    <div class="col-md-6">
                        <label for="cognome" class="form-label">Cognome</label>
                        <input type="text" class="form-control" id="cognome" name="cognome" required>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                    <div class="form-text">Username univoco per il tuo account</div>
                </div>
                
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="col-md-6">
                        <label for="conferma_password" class="form-label">Conferma Password</label>
                        <input type="password" class="form-control" id="conferma_password" name="conferma_password" required>
                    </div>
                </div>
                
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Registrati</button>
                </div>
            </form>
        </div>
        
        <div class="card-footer bg-white text-center py-3">
            <p>Hai già un account? <a href="login.php">Accedi</a></p>
            <a href="index.php" class="text-decoration-none">Torna alla Home</a>
        </div>
    </div>
</div>

</body>
</html>