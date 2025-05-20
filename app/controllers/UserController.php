<?php
namespace app\controllers;

use app\core\Database;
use app\models\User;

class UserController {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Récupère les informations de l'utilisateur courant
     * @param int $userId ID de l'utilisateur
     * @return array|null Informations sur l'utilisateur ou null si non trouvé
     */
    public function getCurrentUser($userId) {
        $sql = "SELECT u.*, r.role_name 
                FROM user u 
                LEFT JOIN user_roles r ON u.user_id = r.user_id 
                WHERE u.user_id = ?";
        
        return $this->db->fetchOne($sql, [$userId]);
    }
    
    // Autres méthodes du contrôleur User...
}
