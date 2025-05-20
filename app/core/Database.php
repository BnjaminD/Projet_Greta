<?php
namespace app\core;  // Ajouter le namespace

use PDO;
use PDOException;
use PDOStatement;
use Exception;

require_once('config.php');

// Check if the class already exists to prevent duplicate declarations
if (!class_exists('\\app\\core\\Database')) {
    class Database {
        private static $instance = null;
        private $pdo;

        private function __construct() {
            try {
                $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
                ];
                
                $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
            } catch (PDOException $e) {
                error_log("DB Connection Error: " . $e->getMessage());
                throw new Exception(ERROR_DB_CONNECTION);
            }
        }

        // Pattern Singleton
        public static function getInstance(): self {
            if (self::$instance === null) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        // Protection XSS
        private function sanitize($data) {
            if (is_array($data)) {
                return array_map([$this, 'sanitize'], $data);
            }
            // Ajouter une vérification pour les valeurs NULL
            if ($data === null) {
                return null;
            }
            return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        }

        // Requête préparée sécurisée
        public function prepare($sql): PDOStatement {
            return $this->pdo->prepare($sql);
        }

        // Exécuter une requête avec paramètres
        public function execute($sql, array $params = []): PDOStatement {
            $stmt = $this->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        }

        // Récupérer une seule ligne
        public function fetchOne($sql, array $params = []): ?array {
            $stmt = $this->execute($sql, $params);
            $result = $stmt->fetch();
            return $result === false ? null : $this->sanitize($result);
        }

        // Récupérer toutes les lignes
        public function fetchAll($sql, array $params = []): array {
            $stmt = $this->execute($sql, $params);
            return $this->sanitize($stmt->fetchAll());
        }

        // Insérer des données
        public function insert(string $table, array $data): int {
            $columns = implode(', ', array_keys($data));
            $values = implode(', ', array_fill(0, count($data), '?'));
            $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$values})";
            
            $this->execute($sql, array_values($data));
            return $this->pdo->lastInsertId();
        }

        // Mettre à jour des données
        public function update(string $table, array $data, string $where, array $whereParams = []): int {
            $set = implode('=?, ', array_keys($data)) . '=?';
            $sql = "UPDATE {$table} SET {$set} WHERE {$where}";
            
            $params = array_merge(array_values($data), $whereParams);
            $stmt = $this->execute($sql, $params);
            return $stmt->rowCount();
        }

        public function getPdo(): PDO {
            return $this->pdo;
        }

        // Empêcher le clonage
        private function __clone() {}
        
        // Empêcher la désérialisation
        public function __wakeup() {}
    }
}

// Utilisation
try {
    $db = Database::getInstance();
} catch (Exception $e) {
    die('Erreur de connexion: ' . $e->getMessage());
}