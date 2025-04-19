<?php
class render {

    public static function renderBooks($risultati) {
        if (!empty($risultati)) {
            echo '<h3 class="mb-4">Risultati consigliati:</h3>';
            echo '<div class="row">';
            
            foreach ($risultati as $libro) {
                echo '<div class="col-md-4 mb-4">';
                echo '<div class="card h-100 shadow-sm">';
                
                if ($libro['copertina']) {
                    echo '<a href="book.php?isbn=' . $libro['isbn'] . '">';
                    echo '<img src="' . $libro['copertina'] . '" class="card-img-top img-thumbnail mx-auto d-block" style="max-height: 180px; width: auto;" alt="Copertina libro">';
                    echo '</a>';
                }
                
                echo '<div class="card-body">';
                echo '<h5 class="card-title">' . $libro['titolo'] . '</h5>';
                echo '<p class="card-text"><strong>Autore:</strong> ' . $libro['autori'] . '</p>';
                echo '<p class="card-text">' . $libro['descrizione'] . '</p>';
                echo '</div>';
                
                echo '<div class="card-footer small text-muted">';
                echo 'ISBN: ' . $libro['isbn'];
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            
            echo '</div>';
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            echo '<div class="alert alert-warning mt-4">Nessun risultato trovato per i criteri selezionati.</div>';
        }
    }

    public static function renderBookDetails($libro, $commenti = [], $isPreferito = false) {
        if ($libro) {
            ?>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <?php 
                    if (!empty($libro->copertina)) {
                        echo '<img src="'.$libro->copertina.'" class="img-fluid rounded shadow">';
                    } else {
                        echo '<div class="alert alert-secondary">Copertina non disponibile</div>';
                    }
                    ?>
                </div>
                <div class="col-md-8">
                    <h2><?php echo $libro->titolo; ?></h2>
                    <p><strong>Autore:</strong> <?php echo $libro->autori; ?></p>
                    <p><strong>ISBN:</strong> <?php echo $libro->isbn; ?></p>
                    <p><?php echo $libro->descrizione; ?></p>
    
                    <div class="mt-3">
                        <?php 
                        if (!empty($libro->previewLink)) {
                            echo '<a href="'.$libro->previewLink.'" target="_blank" class="btn btn-primary">Anteprima su Google Books</a>';
                        }
                        ?>
                        <a href="index.php" class="btn btn-secondary ms-2">Torna indietro</a>
                    </div>
    
                    <div class="mt-3">
                        <?php 
                        if (isset($_SESSION['username'])) {
                            if ($isPreferito) {
                                ?>
                                <form method="POST" action="rimuovi_preferito.php" class="d-inline">
                                    <input type="hidden" name="isbn" value="<?php echo $libro->isbn; ?>">
                                    <button type="submit" class="btn btn-danger">Rimuovi dai Preferiti</button>
                                </form>
                                <?php
                            } else {
                                ?>
                                <form method="POST" action="aggiungi_preferito.php" class="d-inline">
                                    <input type="hidden" name="isbn" value="<?php echo $libro->isbn; ?>">
                                    <button type="submit" class="btn btn-warning">Aggiungi ai Preferiti</button>
                                </form>
                                <?php
                            }
                            ?>
                            <button class="btn btn-info ms-2" onclick="document.getElementById('boxcommenti').classList.toggle('d-none')">Commenta</button>
                            <?php
                        }
                        ?>
                    </div>
    
                    <div id="boxcommenti" class="mt-4 d-none">
                        <form method="POST" action="aggiungi_commento.php">
                            <input type="hidden" name="isbn" value="<?php echo $libro->isbn; ?>">
                            <div class="mb-3">
                                <label for="commento" class="form-label">Il tuo commento</label>
                                <textarea class="form-control" id="commento" name="commento" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-success">Invia commento</button>
                        </form>
                    </div>
    
                    <?php 
                    if ($commenti) {
                        ?>
                        <div class="mt-5">
                            <h5>Commenti:</h5>
                            <?php 
                            foreach ($commenti as $c) {
                                ?>
                                <div class="border rounded p-2 mb-2 bg-white">
                                    <p class="mb-1"><strong><?php echo $c['username']; ?></strong> ha scritto il <?php echo $c['data']; ?>:</p>
                                    <p><?php echo $c['testo']; ?></p>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <?php
        } else {
            ?>
            <div class="alert alert-warning">
                Libro non trovato o ISBN non valido.
            </div>
            <a href="index.php" class="btn btn-secondary">Torna alla ricerca</a>
            <?php
        }
    }
    public static function renderPreferiti($preferiti) {
        if (empty($preferiti)) {
            ?>
            <div class="alert alert-info">
                Non hai ancora aggiunto libri ai preferiti. Esplora la libreria e aggiungi i tuoi libri preferiti!
            </div>
            <?php 
        } else {
            ?>
            <div class="row">
                <?php 
                foreach ($preferiti as $libro) {
                    ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="text-center p-3">
                                <?php 
                                if (!empty($libro->copertina)) {
                                    echo '<img src="'.$libro->copertina.'" class="img-fluid rounded" alt="Copertina libro" style="max-height: 200px;">';
                                } else {
                                    ?>
                                    <div class="bg-light p-4 text-center">
                                        <span class="text-muted">Copertina non disponibile</span>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $libro->titolo; ?></h5>
                                <p class="card-text text-muted"><?php echo $libro->autori; ?></p>
                            </div>
                            <div class="card-footer bg-white">
                                <div class="d-flex justify-content-between">
                                    <a href="book.php?isbn=<?php echo $libro->isbn; ?>" class="btn btn-sm btn-primary">Dettagli</a>
                                    <a href="preferiti.php?rimuovi=<?php echo $libro->isbn; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Sei sicuro di voler rimuovere questo libro dai preferiti?')">Rimuovi</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
            <?php
        }
    }
    
    public static function renderMessaggiPreferiti() {
        if (isset($_GET['successo']) && $_GET['successo'] == 'rimosso') {
            ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Libro rimosso dai preferiti con successo!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php
        }
        
        if (isset($_GET['errore']) && $_GET['errore'] == 'rimozione') {
            ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Errore durante la rimozione del libro dai preferiti.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php
        }
    }
}
?>
