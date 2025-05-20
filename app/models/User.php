<?php
namespace app\models;

use app\core\Database;

class User {
    private $id;
    private $username;
    private $email;
    private $password;
    private $role;
    private $createdAt;

    public function __construct(array $data = []) {
        $this->hydrate($data);
    }

    public function hydrate(array $data): void {
        foreach ($data as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    // Getters
    public function getId(): ?int { return $this->id; }
    public function getUsername(): ?string { return $this->username; }
    public function getEmail(): ?string { return $this->email; }
    public function getPassword(): ?string { return $this->password; }
    public function getRole(): ?string { return $this->role; }
    public function getCreatedAt(): ?string { return $this->createdAt; }

    // Setters
    public function setId($id): void { $this->id = $id; }
    public function setUsername($username): void { $this->username = $username; }
    public function setEmail($email): void { $this->email = $email; }
    public function setPassword($password): void { $this->password = $password; }
    public function setRole($role): void { $this->role = $role; }
    public function setCreatedAt($createdAt): void { $this->createdAt = $createdAt; }

    /**
     * Récupère les informations de l'utilisateur courant
     * @param int $userId ID de l'utilisateur
     * @return array|null Informations sur l'utilisateur ou null si non trouvé
     */
    public function getCurrentUser($userId) {
        $db = Database::getInstance();
        $sql = "SELECT u.*, r.role_name 
                FROM user u 
                LEFT JOIN user_roles r ON u.user_id = r.user_id 
                WHERE u.user_id = ?";
        
        return $db->fetchOne($sql, [$userId]);
    }
}