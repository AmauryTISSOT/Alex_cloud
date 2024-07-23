<?php
class Database {
    // Instance unique de la classe
    private static $instance = null;
    // Instance de PDO
    private $pdo;

    // Informations de connexion
    private $host = 'localhost';
    private $dbName = 'users';
    private $username = 'app';
    private $password = 'app';

    // Constructeur privé pour empêcher l'instanciation directe
    private function __construct() {
        try {
            $dsn = "mysql:host=$this->host;dbname=$this->dbName;charset=utf8";
            $this->pdo = new PDO($dsn, $this->username, $this->password);
            // Configuration de PDO pour lancer des exceptions en cas d'erreurs
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Gestion des erreurs de connexion
            die('Erreur de connexion : ' . $e->getMessage());
        }
    }

    // Méthode statique pour obtenir l'instance unique
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    // Méthode pour obtenir la connexion PDO
    public function getConnection() {
        return $this->pdo;
    }
}
?>
