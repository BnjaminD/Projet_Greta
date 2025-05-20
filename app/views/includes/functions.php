<?php
/**
 * Fichier de redirection - Les fonctions ont été déplacées vers app/core/functions.php
 * Ce fichier existe uniquement pour maintenir la compatibilité avec le code existant
 */

namespace app\views\includes;

// Importation des fonctions du namespace core
use app\core;
use function app\core\sanitize;
use function app\core\connexionUtilisateur;
use function app\core\updateFailedLoginAttempts;
use function app\core\resetFailedLoginAttempts;
use function app\core\updateLastLogin;
use function app\core\logUserActivity;
use function app\core\isAdmin;
use function app\core\getUserById;
use function app\core\createUser;
use function app\core\updateUser;
use function app\core\getRestaurants;
use function app\core\createReservation;
use function app\core\getRestaurantMenu;
use function app\core\logSystemError;
use function app\core\paginate;

// Inclure le nouveau fichier de fonctions
require_once __DIR__ . '/../../core/functions.php';

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
        return sanitize($data);
    }
    
    /**
     * Redirige vers la fonction connexionUtilisateur
     */
    public static function connexionUtilisateur($username, $password) {
        return connexionUtilisateur($username, $password);
    }
    
    /**
     * Redirige vers la fonction isAdmin
     */
    public static function isAdmin($userId) {
        return isAdmin($userId);
    }
    
    /**
     * Redirige vers la fonction getUserById
     */
    public static function getUserById($userId) {
        return getUserById($userId);
    }
    
    /**
     * Redirige vers la fonction createUser
     */
    public static function createUser($username, $email, $password, $role = 'user') {
        return createUser($username, $email, $password, $role);
    }
    
    /**
     * Redirige vers la fonction updateUser
     */
    public static function updateUser($userId, $data) {
        return updateUser($userId, $data);
    }
    
    /**
     * Redirige vers la fonction getRestaurants
     */
    public static function getRestaurants($limit = null, $offset = 0, $filters = []) {
        return getRestaurants($limit, $offset, $filters);
    }
    
    /**
     * Redirige vers la fonction createReservation
     */
    public static function createReservation($userId, $restaurantId, $reservationTime, $numberOfGuests, $specialRequests = null) {
        return createReservation($userId, $restaurantId, $reservationTime, $numberOfGuests, $specialRequests);
    }
    
    /**
     * Redirige vers la fonction getRestaurantMenu
     */
    public static function getRestaurantMenu($restaurantId) {
        return getRestaurantMenu($restaurantId);
    }
    
    /**
     * Redirige vers la fonction logUserActivity
     */
    public static function logUserActivity($userId, $action, $details = null) {
        return logUserActivity($userId, $action, $details);
    }
    
    /**
     * Redirige vers la fonction logSystemError
     */
    public static function logSystemError($message, $severity = 'ERROR', $context = null) {
        return logSystemError($message, $severity, $context);
    }
    
    /**
     * Redirige vers la fonction paginate
     */
    public static function paginate($total, $limit, $currentPage) {
        return paginate($total, $limit, $currentPage);
    }
}