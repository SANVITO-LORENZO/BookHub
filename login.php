<?php
session_start();

if (isset($_SESSION['autenticato'])) {
    header("Location: index.php");
    exit;
}

$error = $_SESSION['login_error'] ?? '';

if (isset($_SESSION['login_error'])) {
    unset($_SESSION['login_error']);
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Login - BookHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-white text-center py-3">
                    <img src="extra/img/logo.jpg" alt="BookHub Logo" style="max-height: 80px;" class="img-fluid">
                    <h3 class="mt-3">Accedi</h3>
                </div>
                <div class="card-body p-4">
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" action="controllers/loginControllers.php">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Accedi</button>
                        </div>
                    </form>
                    
                    <div class="text-center mt-4">
                        <p>Non hai un account?</p>
                        <a href="register.php" class="btn btn-outline-success">Registrati</a>
                    </div>
                </div>
                <div class="card-footer bg-white text-center py-3">
                    <a href="index.php" class="text-decoration-none">Torna alla Home</a>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>