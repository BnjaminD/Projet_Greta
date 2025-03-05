<?php

namespace App\Models;

use PDO;
use PDOException;
use App\Exceptions\DatabaseException;
use App\Interfaces\IAdminRepository;

class AdminData implements IAdminRepository {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    private function fetchAll(string $sql, array $params = []): array {
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new DatabaseException("Database query failed: " . $e->getMessage());
        }
    }

    private function fetchOne(string $sql, array $params = []): array {
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: [];
        } catch (PDOException $e) {
            throw new DatabaseException("Database query failed: " . $e->getMessage());
        }
    }

    /**
     * Récupère les logs système avec protection XSS
     */
    public function getSystemLogs(int $limit = 100): array {
        $sql = "SELECT * FROM system_logs ORDER BY created_at DESC LIMIT :limit";
        return $this->fetchAll($sql, ['limit' => $limit]);
    }

    /**
     * Récupère les actions des administrateurs
     */
    public function getAdminActions(int $limit = 50): array {
        $sql = "SELECT aa.*, u.username 
                FROM admin_actions aa 
                JOIN user u ON aa.user_id = u.user_id 
                ORDER BY performed_at DESC LIMIT :limit";
        return $this->fetchAll($sql, ['limit' => $limit]);
    }

    /**
     * Statistiques utilisateurs
     */
    public function getUserStatistics(): array {
        return [
            'total_users' => $this->countTotalUsers(),
            'active_users' => $this->countActiveUsers(),
            'banned_users' => $this->countBannedUsers(),
            'new_users_today' => $this->countNewUsersToday()
        ];
    }

    /**
     * Contenu modéré
     */
    public function getModeratedContent(): array {
        return [
            'comments' => $this->getModeratedComments(),
            'likes' => $this->getModeratedLikes()
        ];
    }

    private function countTotalUsers(): int {
        $sql = "SELECT COUNT(*) as total FROM user";
        $result = $this->fetchOne($sql);
        return (int) ($result['total'] ?? 0);
    }

    private function countActiveUsers(): int {
        $sql = "SELECT COUNT(*) as count FROM user WHERE is_active = :active";
        return (int) $this->fetchOne($sql, ['active' => 1])['count'];
    }

    private function countBannedUsers(): int {
        $sql = "SELECT COUNT(*) as count FROM user WHERE is_banned = :banned";
        return (int) $this->fetchOne($sql, ['banned' => 1])['count'];
    }

    private function countNewUsersToday(): int {
        $sql = "SELECT COUNT(*) FROM user WHERE DATE(created_at) = CURDATE()";
        return (int) $this->fetchOne($sql)['COUNT(*)'];
    }

    private function getModeratedComments(): array {
        $sql = "SELECT c.*, u.username 
                FROM comment c 
                JOIN user u ON c.user_id = u.user_id 
                WHERE c.is_moderated = :moderated 
                ORDER BY c.created_at DESC";
        return $this->fetchAll($sql, ['moderated' => true]);
    }

    private function getModeratedLikes(): array {
        $sql = "SELECT l.*, u.username 
                FROM likes l 
                JOIN user u ON l.user_id = u.user_id 
                WHERE l.is_moderated = :moderated 
                ORDER BY l.created_at DESC";
        return $this->fetchAll($sql, ['moderated' => true]);
    }
}