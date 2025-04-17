<?php
class render {

    // Funzione che renderizza la lista dei libri
    public static function renderBooks($risultati) {
        // Verifica se ci sono risultati
        if (!empty($risultati)) {
            echo '<h3 class="mb-4">Risultati consigliati:</h3>';
            echo '<div class="row">';
            
            // Itera sui risultati dei libri
            foreach ($risultati as $libro) {
                echo '<div class="col-md-4 mb-4">';
                echo '<div class="card h-100 shadow-sm">';
                
                // Se il libro ha una copertina, visualizzala
                if ($libro['copertina']) {
                    echo '<a href="book.php?isbn=' . urlencode($libro['isbn']) . '">';
                    echo '<img src="' . htmlspecialchars($libro['copertina']) . '" class="card-img-top img-thumbnail mx-auto d-block" style="max-height: 180px; width: auto;" alt="Copertina libro">';
                    echo '</a>';
                }
                
                // Dettagli del libro (titolo, autore, descrizione)
                echo '<div class="card-body">';
                echo '<h5 class="card-title">' . htmlspecialchars($libro['titolo']) . '</h5>';
                echo '<p class="card-text"><strong>Autore:</strong> ' . htmlspecialchars($libro['autori']) . '</p>';
                echo '<p class="card-text">' . htmlspecialchars(mb_strimwidth($libro['descrizione'], 0, 200, '...')) . '</p>';
                echo '</div>';
                
                // Footer con l'ISBN
                echo '<div class="card-footer small text-muted">';
                echo 'ISBN: ' . htmlspecialchars($libro['isbn']);
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            
            echo '</div>';
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            echo '<div class="alert alert-warning mt-4">Nessun risultato trovato per i criteri selezionati.</div>';
        }
    }
}
?>
