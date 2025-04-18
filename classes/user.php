<?php
require_once __DIR__ . '/../managers/database.php';


class User {
    private $nome;
    private $cognome;
    private $username;
    private $email;
    private $password;
    
    public function __construct() {
    }

    public function setData($nome, $cognome, $username, $email, $password) {
        $this->nome = $nome;
        $this->cognome = $cognome;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
    }
    
    public function validateData() {
        if (empty($this->nome) || empty($this->cognome) || empty($this->username) || 
            empty($this->email) || empty($this->password)) {
            return "Tutti i campi sono obbligatori";
        }
        
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            return "Formato email non valido";
        }
        
        return true;
    }
    
    public function register() {
        try {
            $risultato = registra_utente(
                $this->username, 
                $this->password, 
                $this->email, 
                $this->nome, 
                $this->cognome
            );
            
            if ($risultato) {
                return true;
            } else {
                return "Errore durante la registrazione. Username o email già esistenti.";
            }
        } catch (Exception $e) {
            return "Errore durante la registrazione: " . $e->getMessage();
        }
    }
    
    public function verifyPasswords($password, $conferma_password) {
        return $password === $conferma_password;
    }
    
    public function login($username, $password) {
        return verifica_login($username, $password);
    }
    
}
?>