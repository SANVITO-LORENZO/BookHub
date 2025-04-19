<?php
session_start();
require_once('managers/database.php');
require_once('classes/user.php');

if (!isset($_SESSION['autenticato']) || $_SESSION['autenticato'] !== true) {
    header('Location: login.php');
    exit;
}
$username = $_SESSION['username'];
$userInfo = getUserInfo($username);
$errore = '';
$successo = '';

if (!$userInfo) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['aggiorna'])) {

    $nome = $_POST['nome'];
    $cognome = $_POST['cognome'];
    $email = $_POST['email'];
    $password_attuale = $_POST['password_attuale'];
    $nuova_password = $_POST['nuova_password'];
    $conferma_password = $_POST['conferma_password'];
    
    $verifica_login = verifica_login($username, $password_attuale);    

    if (!$verifica_login) {
        $errore = "Password attuale non corretta";
    } 

    else if (empty($nome) || empty($cognome) || empty($email)) {
        $errore = "Nome, cognome e email sono campi obbligatori";
    } 

    else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errore = "Formato email non valido";
    }

    else if (!empty($nuova_password) && $nuova_password != $conferma_password) {
        $errore = "Le nuove password non corrispondono";
    }

    else {
        
        $risultato = aggiorna_utente($username, $nome, $cognome, $email, $nuova_password);
        
        if ($risultato === true) {
            $successo = "Informazioni utente aggiornate con successo";
            $userInfo = getUserInfo($username);
        } else {
            $errore = $risultato; 
        }
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifica Profilo - BookHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">



<div class="container py-3">

    <div class="card shadow">
        <div class="text-center mb-4 mt-4">
    <a href="index.php">
        <img src="extra/img/logo.jpg"  style="max-height: 80px;" class="img-fluid">
    </a>
</div>
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="mb-0">Modifica Profilo</h3>
                <div>
                    <a href="personalArea.php" class="btn btn-outline-secondary me-2">Area Personale</a>
                    <a href="logout.php" class="btn btn-danger">Logout</a>
                </div>
            </div>
        </div>
        
        <div class="card-body p-4">
            <?php 
            if (!empty($errore)) { 
            ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $errore; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php 
            } 
            
            if (!empty($successo)) { 
            ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $successo; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php 
            } 
            ?>
            
            <form method="post" action="">
                <div class="mb-4">
                    <h4 class="mb-3">Informazioni Personali</h4>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" value="<?php echo $userInfo['username']; ?>" disabled>
                            <div class="form-text">L'username non può essere modificato</div>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo $userInfo['email']; ?>" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome" value="<?php echo $userInfo['nome']; ?>" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="cognome" class="form-label">Cognome</label>
                            <input type="text" class="form-control" id="cognome" name="cognome" value="<?php echo $userInfo['cognome']; ?>" required>
                        </div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <h4 class="mb-3">Modifica Password</h4>
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label  class="form-label">Password Attuale</label>
                            <input type="password" class="form-control" id="password_attuale" name="password_attuale" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label  class="form-label">Nuova Password</label>
                            <input type="password" class="form-control" id="nuova_password" name="nuova_password">
                            <div class="form-text">Lasciare vuoto per non modificare la password</div>
                        </div>
                        
                        <div class="col-md-6">
                            <label  class="form-label">Conferma Nuova Password</label>
                            <input type="password" class="form-control" id="conferma_password" name="conferma_password">
                        </div>
                    </div>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" name="aggiorna" class="btn btn-primary">Salva Modifiche</button>
                </div>
            </form>
        </div>
        
        <div class="card-footer bg-white text-center py-3">
            <a href="index.php" class="text-decoration-none">Torna alla Home</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>