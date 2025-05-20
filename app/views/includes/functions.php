<?php
/**
 * Fichier de redirection - Les fonctions ont été déplacées vers app/core/functions.php
 * Ce fichier existe uniquement pour maintenir la compatibilité avec le code existant
 */

namespace app\views\includes;

// Importation des fonctions du namespace core
use app\core;

// Inclure le nouveau fichier de fonctions si pas déjà inclus
if (!function_exists('\\app\\core\\sanitize')) {
    require_once __DIR__ . '/../../core/functions.php';
}

// Définir des alias de fonctions pour la compatibilité ascendante
if (!class_exists('\\app\\views\\includes\\Functions')) {
    /**
     * Classe de compatibilité - Redirige les appels statiques vers les fonctions globales
     */
    class Functions {
        private static ?\app\core\Database $db = null;

        /**
         * Initialise la connexion à la base de données
         */
        public static function init(): void {
            if (self::$db === null) {
                self::$db = \app\core\Database::getInstance();
            }
        }

        /**
         * Redirige l'appel à insert vers la fonction globale ou la méthode de Database
         */
        public static function insert(string $table, array $data): int {
            self::init();
            return self::$db->insert($table, $data);
        }
        
        /**
         * Redirige l'appel à update vers la méthode de Database
         */
        public static function update(string $table, array $data, string $where, array $whereParams = []): int {
            self::init();
            return self::$db->update($table, $data, $where, $whereParams);
        }
        
        /**
         * Redirige l'appel à fetchOne vers la méthode de Database
         */
        public static function fetchOne($sql, array $params = []): ?array {
            self::init();
            return self::$db->fetchOne($sql, $params);
        }
        
        /**
         * Redirige l'appel à fetchAll vers la méthode de Database
         */
        public static function fetchAll($sql, array $params = []): array {
            self::init();
            return self::$db->fetchAll($sql, $params);
        }
        
        /**
         * Redirige l'appel à execute vers la méthode de Database
         */
        public static function execute($sql, array $params = []) {
            self::init();
            return self::$db->execute($sql, $params);
        }
        
        /**
         * Redirige vers la fonction sanitize
         */
        public static function sanitize($data) {
            return \app\core\sanitize($data);
        }
        
        /**
         * Redirige vers la fonction connexionUtilisateur
         */
        public static function connexionUtilisateur($username, $password) {
            return \app\core\connexionUtilisateur($username, $password);
        }
        
        /**
         * Redirige vers la fonction isAdmin
         */
        public static function isAdmin($userId) {
            return \app\core\isAdmin($userId);
        }
        
        /**
         * Redirige vers la fonction getUserById
         */
        public static function getUserById($userId) {
            return \app\core\getUserById($userId);
        }
        
        /**
         * Redirige vers la fonction createUser
         */
        public static function createUser($username, $email, $password, $role = 'user') {
            return \app\core\createUser($username, $email, $password, $role);
        }
        
        /**
         * Redirige vers la fonction updateUser
         */
        public static function updateUser($userId, $data) {
            return \app\core\updateUser($userId, $data);
        }
        
        /**
         * Redirige vers la fonction getRestaurants
         */
        public static function getRestaurants($limit = null, $offset = 0, $filters = []) {
            return \app\core\getRestaurants($limit, $offset, $filters);
        }
        
        /**
         * Redirige vers la fonction createReservation
         */
        public static function createReservation($userId, $restaurantId, $reservationTime, $numberOfGuests, $specialRequests = null) {
            return \app\core\createReservation($userId, $restaurantId, $reservationTime, $numberOfGuests, $specialRequests);
        }
        
        /**
         * Redirige vers la fonction getRestaurantMenu
         */
        public static function getRestaurantMenu($restaurantId) {
            return \app\core\getRestaurantMenu($restaurantId);
        }
        
        /**
         * Redirige vers la fonction logUserActivity
         */
        public static function logUserActivity($userId, $action, $details = null) {
            return \app\core\logUserActivity($userId, $action, $details);
        }
        
        /**
         * Redirige vers la fonction logSystemError
         */
        public static function logSystemError($message, $severity = 'ERROR', $context = null) {
            return \app\core\logSystemError($message, $severity, $context);
        }
        
        /**
         * Redirige vers la fonction paginate
         */
        public static function paginate($total, $limit, $currentPage) {
            return \app\core\paginate($total, $limit, $currentPage);
        }
    }
}