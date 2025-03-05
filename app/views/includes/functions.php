<?php
namespace app\views\includes;

use PDO;
use app\core\Database;

require_once __DIR__ . '/../../core/Database.php';
require_once __DIR__ . '/../../core/config.php';

class Functions {
    private static ?Database $db = null;

    /**
     * Initialise la connexion à la base de données
     */
    public static function init(): void {
        if (self::$db === null) {
            self::$db = Database::getInstance();
        }
    }

     /**
     * Insert des données dans une table
     * @param string $table Nom de la table
     * @param array $data Données à insérer
     * @return int ID de l'enregistrement inséré
     * @throws \InvalidArgumentException Si les données sont invalides
     */
    public static function insert(string $table, array $data): int {
        self::init();
        
        try {
            // Validation des données
            if (empty($table) || empty($data)) {
                throw new \InvalidArgumentException('Table ou données manquantes');
            }

            // Validation du nom de table (protection injection SQL)
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $table)) {
                throw new \InvalidArgumentException('Nom de table invalide');
            }

            // Validation des clés du tableau
            foreach (array_keys($data) as $column) {
                if (!preg_match('/^[a-zA-Z0-9_]+$/', $column)) {
                    throw new \InvalidArgumentException('Nom de colonne invalide: ' . $column);
                }
            }

            // Insertion via Database avec les données validées
            return self::$db->insert($table, $data);

        } catch (\Exception $e) {
            error_log("Erreur d'insertion: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Récupère les réservations avec protection XSS
     */
    public static function getReservations(): array {
        self::init();
        $sql = "SELECT id, user_id, status FROM reservations";
        return self::$db->fetchAll($sql);
    }

    /**
     * Inscription utilisateur sécurisée
     */
    public static function inscriptionUtilisateur(string $username, string $email, string $password): array {
        self::init();
        
        try {
            // Validation
            if (!self::validateUserData($username, $email, $password)) {
                throw new \InvalidArgumentException('Données invalides');
            }

            // Hash du mot de passe
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Préparation des données
            $userData = [
                'username' => $username,
                'email' => $email,
                'password' => $hashedPassword,
                'created_at' => date('Y-m-d H:i:s')
            ];

            // Insertion sécurisée
            $userId = self::$db->insert('user', $userData);

            return [
                'success' => true,
                'user_id' => $userId,
                'message' => 'Inscription réussie'
            ];

        } catch (\Exception $e) {
            error_log("Erreur d'inscription: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erreur lors de l\'inscription'
            ];
        }
    }

    /**
     * Validation des données utilisateur
     */
    private static function validateUserData(string $username, string $email, string $password): bool {
        return !empty($username) 
            && strlen($username) >= 3 
            && filter_var($email, FILTER_VALIDATE_EMAIL) 
            && strlen($password) >= 8;
    }
}

// Utilisation
try {
    Functions::init();
} catch (\Exception $e) {
    die('Erreur d\'initialisation: ' . $e->getMessage());
}