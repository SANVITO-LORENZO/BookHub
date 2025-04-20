<?php
require_once 'classes/book.php';
require_once 'managers/render.php';
require_once 'managers/database.php';

$risultati = [];

$generi = ottieni_informazioni('generi');
$umori = ottieni_informazioni('umori');
$tempi_lettura = ottieni_informazioni('tempi_lettura');
$lingue = ottieni_informazioni('lingue');
$fasce_eta = ottieni_informazioni('fasce_eta');

$genere = '';
$umore = '';
$tempo = '';
$lingua = '';
$eta = '';
$anno = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['genere'])) {
        $genere = $_POST['genere'];
    }
    if (isset($_POST['umore'])) {
        $umore = $_POST['umore'];
    }
    if (isset($_POST['tempo'])) {
        $tempo = $_POST['tempo'];
    }
    if (isset($_POST['lingua'])) {
        $lingua = $_POST['lingua'];
    }
    if (isset($_POST['eta'])) {
        $eta = $_POST['eta'];
    }
    if (isset($_POST['anno'])) {
        $anno = $_POST['anno'];
    }

    $query = trim("$genere $umore $tempo $eta");
    if (!empty($anno)) {
        $query .= " after:$anno";
    }

    $risultati = GoogleBooksApi::cercaGoogleBooksAvanzata($query, $lingua);
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Consigliami un Libro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5 position-relative">
<form method="post" class="bg-white p-4 rounded shadow-sm mb-5">
        <div class="row g-3">
        <a href="index.php">
            <img src="extra/img/logo.jpg" alt="BookHub Logo" style="max-height: 50px;" class="img-fluid">
        </a>

    <h1 class="mb-4 text-center">Consigliami un Libro</h1>
            <div class="col-md-4">
                <label class="form-label">Genere preferito</label>
                <select class="form-select" name="genere" required>
                    <option value="">-- Seleziona --</option>
                    <?php
                    if (!empty($generi)) {
                        foreach ($generi as $g) {
                            echo '<option value="' . $g['nome'] . '">' . $g['nome'] . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Umore del momento</label>
                <select class="form-select" name="umore" required>
                    <option value="">-- Seleziona --</option>
                    <?php
                    if (!empty($umori)) {
                        foreach ($umori as $u) {
                            echo '<option value="' . $u['nome'] . '">' . $u['nome'] . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Tempo per leggere</label>
                <select class="form-select" name="tempo" required>
                    <option value="">-- Seleziona --</option>
                    <?php
                    if (!empty($tempi_lettura)) {
                        foreach ($tempi_lettura as $t) {
                            echo '<option value="' . $t['nome']. '">' . $t['nome'] . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Lingua</label>
                <select class="form-select" name="lingua" required>
                    <option value="">-- Seleziona --</option>
                    <?php
                    if (!empty($lingue)) {
                        foreach ($lingue as $l) {
                            $lingua_value = $l['nome'];
                            echo '<option value="' . $lingua_value . '">' . $l['nome'] . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Fascia d'età</label>
                <select class="form-select" name="eta">
                    <option value="">-- Facoltativo --</option>
                    <?php
                    if (!empty($fasce_eta)) {
                        foreach ($fasce_eta as $f) {
                            echo '<option value="' . $f['nome'] . '">' . $f['nome'] . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Da quale anno in poi?</label>
                <input type="number" name="anno" class="form-control" placeholder="Es. 2015" min="1800" max="2025">
            </div>
        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary px-4">Trova un libro per me</button>
        </div>
    </form>

    <?php 
    if (!empty($risultati)) {
        render::renderBooks($risultati);
    }
    ?>
</div>
</body>
</html>
