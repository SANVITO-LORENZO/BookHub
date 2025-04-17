<?php
class GoogleBooksApi {
    private static $apiKey = 'AIzaSyCbC8lYgDcYMiJXIq-M5xjxXsbh92wXdMg';

    public static function getLibroByISBN($isbn) {
        $url = "https://www.googleapis.com/books/v1/volumes?q=isbn:" . urlencode($isbn) . "&key=" . self::$apiKey;
        $response = file_get_contents($url);
        $data = json_decode($response, true);

        if (!empty($data['items'][0]['volumeInfo'])) {
            $info = $data['items'][0]['volumeInfo'];
            return [
                'titolo' => $info['title'] ?? '',
                'autori' => implode(', ', $info['authors'] ?? []),
                'descrizione' => $info['description'] ?? 'Nessuna descrizione disponibile.',
                'copertina' => $info['imageLinks']['thumbnail'] ?? '',
                'isbn' => $isbn,
                'previewLink' => $info['previewLink'] ?? '#'
            ];
        }

        return null;
    }

    public static function cercaGoogleBooks($string) {
        $url = 'https://www.googleapis.com/books/v1/volumes?q=' . urlencode($string) . '&key=' . self::$apiKey;
        $response = file_get_contents($url);
        $data = json_decode($response, true);

        $risultati = [];

        if (!empty($data['items'])) {
            foreach ($data['items'] as $item) {
                $info = $item['volumeInfo'];

                $risultati[] = [
                    'titolo' => $info['title'] ?? '',
                    'autori' => implode(', ', $info['authors'] ?? []),
                    'descrizione' => $info['description'] ?? '',
                    'copertina' => $info['imageLinks']['thumbnail'] ?? '',
                    'isbn' => $info['industryIdentifiers'][0]['identifier'] ?? '',
                    'previewLink' => $info['previewLink'] ?? '#'
                ];
            }
        }

        return $risultati;
    }
}
