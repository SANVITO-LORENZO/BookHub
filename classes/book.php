<?php
require_once 'api/googleBooks.php';

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
}
