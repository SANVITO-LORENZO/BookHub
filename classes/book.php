<?php
require_once 'api/googleBooks.php';
require_once __DIR__ . '/../managers/database.php';

class Book {
    public $titolo;
    public $autori;
    public $descrizione;
    public $copertina;
    public $isbn;
    public $previewLink;

    public function __construct($data) {
        $this->titolo = $data['titolo'];
        $this->autori = $data['autori'];
        $this->descrizione = $data['descrizione'];
        $this->copertina = $data['copertina'];
        $this->isbn = $data['isbn'];
        $this->previewLink = $data['previewLink'];
    }

    public static function fromISBN($isbn) {
        $dati = GoogleBooksApi::getLibroByISBN($isbn);
        if ($dati) {
            return new Book($dati);
        }
        return null;
    }

    public static function search($string) {
        $risultati = GoogleBooksApi::cercaGoogleBooks($string);
        $books = [];

        foreach ($risultati as $dati) {
            $books[] = new Book($dati);
        }

        return $books;
    }

    public function getComments() {
        return getComments($this->isbn);
    }

    public function isPreferito($username) {
        $preferiti = getPreferiti($username);
        foreach ($preferiti as $preferito) {
            if ($preferito['isbn'] === $this->isbn) {
                return true;
            }
        }
        return false;
    }

    public function aggiungiAiPreferiti($username) {
        return aggiungiPreferito($this->isbn, $username);
    }

    public function rimuoviDaiPreferiti($username) {
        return rimuoviPreferito($this->isbn, $username);
    }

    public function aggiungiCommento($username, $testo) {
        return aggiungiCommento($this->isbn, $username, $testo);
    }
}