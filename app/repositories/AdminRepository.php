<?php namespace App\Repositories;

use app\core\Database;
use App\Interfaces\IAdminRepository;
use App\Exceptions\DatabaseException;

class AdminRepository implements IAdminRepository {
    private Database $db;
    
    public function __construct(Database $db) {
        $this->db = $db;
    }
    
    /**
     * Récupère les logs système
     * 
     * @param int $limit Nombre maximum de logs à récupérer
     * @return array Liste des logs système
     * @throws DatabaseException En cas d'erreur de base de données
     */
    public function getSystemLogs(int $limit = 100): array {
        try {
            // Vérifier si la table system_logs existe
            $tables = $this->db->fetchAll("SHOW TABLES LIKE 'system_logs'");
            if (empty($tables)) {
                // Table n'existe pas, retourner un tableau vide avec structure
                return [
                    ['id' => 0, 'log_type' => 'INFO', 'message' => 'Table system_logs non trouvée', 'created_at' => date('Y-m-d H:i:s')]
                ];
            }
            
            return $this->db->fetchAll(
                "SELECT * FROM system_logs ORDER BY created_at DESC LIMIT ?",
                [$limit]
            );
        } catch (\Exception $e) {
            // Créer un message d'erreur clair
            $error = "Erreur lors de la récupération des logs système: " . $e->getMessage();
            error_log($error);
            throw new DatabaseException($error);
        }
    }
    
    /**
     * Récupère les actions des administrateurs
     * 
     * @param int $limit Nombre maximum d'actions à récupérer
     * @return array Liste des actions des administrateurs
     * @throws DatabaseException En cas d'erreur de base de données
     */
    public function getAdminActions(int $limit = 50): array {
        try {
            // Vérifier si la table user_activity_log existe
            $tables = $this->db->fetchAll("SHOW TABLES LIKE 'user_activity_log'");
            if (empty($tables)) {
                // Table n'existe pas, retourner un tableau vide avec structure
                return [
                    ['id' => 0, 'user_id' => 0, 'action' => 'INFO', 'details' => 'Table user_activity_log non trouvée', 'timestamp' => date('Y-m-d H:i:s')]
                ];
            }
            
            // Vérifier si la colonne 'action' existe dans la table
            $columns = $this->db->fetchAll("SHOW COLUMNS FROM user_activity_log LIKE 'action'");
            if (empty($columns)) {
                // Colonne n'existe pas, faire une requête sans filtre sur action
                return $this->db->fetchAll(
                    "SELECT * FROM user_activity_log ORDER BY timestamp DESC LIMIT ?",
                    [$limit]
                );
            }
            
            return $this->db->fetchAll(
                "SELECT * FROM user_activity_log WHERE action LIKE 'admin%' ORDER BY timestamp DESC LIMIT ?",
                [$limit]
            );
        } catch (\Exception $e) {
            // Créer un message d'erreur clair
            $error = "Erreur lors de la récupération des actions administrateurs: " . $e->getMessage();
            error_log($error);
            throw new DatabaseException($error);
        }
    }
    
    /**
     * Récupère les statistiques utilisateurs
     * 
     * @return array Statistiques utilisateurs
     * @throws DatabaseException En cas d'erreur de base de données
     */
    public function getUserStatistics(): array {
        try {
            $stats = [
                'total_users' => 0,
                'active_users' => 0,
                'new_users_last_30_days' => 0
            ];
            
            $result = $this->db->fetchOne("SELECT COUNT(*) as count FROM user", []);
            if ($result) {
                $stats['total_users'] = $result['count'];
            }
            
            $result = $this->db->fetchOne("SELECT COUNT(*) as count FROM user WHERE is_active = 1", []);
            if ($result) {
                $stats['active_users'] = $result['count'];
            }
            
            $result = $this->db->fetchOne(
                "SELECT COUNT(*) as count FROM user WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)",
                []
            );
            if ($result) {
                $stats['new_users_last_30_days'] = $result['count'];
            }
            
            return $stats;
        } catch (\Exception $e) {
            throw new DatabaseException("Failed to retrieve user statistics: " . $e->getMessage());
        }
    }
    
    /**
     * Récupère le contenu modéré
     * 
     * @return array Contenu modéré
     */
    public function getModeratedContent(): array {
        // Pour le moment, retourner un tableau vide
        return [
            'comments' => [],
            'posts' => [],
            'likes' => []
        ];
    }
}