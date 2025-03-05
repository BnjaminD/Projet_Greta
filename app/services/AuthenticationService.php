<?php namespace App\Services;

use PDO;
use App\Exceptions\UnauthorizedException;

class AuthenticationService {
    private PDO $db;
    private ?array $userPermissions = null;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function hasPermission(string $permission): bool {
        if (!isset($_SESSION['user_id'])) {
            return false;
        }

        if ($this->userPermissions === null) {
            $this->loadUserPermissions($_SESSION['user_id']);
        }

        return in_array($permission, $this->userPermissions);
    }

    private function loadUserPermissions(int $userId): void {
        $sql = "SELECT p.permission_name 
                FROM user_permissions up
                JOIN permissions p ON up.permission_id = p.permission_id
                WHERE up.user_id = :user_id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        
        $this->userPermissions = $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getCurrentUserId(): ?int {
        return $_SESSION['user_id'] ?? null;
    }

    public function isAdmin(): bool {
        return $this->hasPermission('admin.access');
    }
}