<?php
namespace app\models;

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
}