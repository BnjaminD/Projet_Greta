<?php namespace app\core;

class UserManager {
    private $pdo;

    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function inscriptionUtilisateur(string $username, string $email, string $password): bool {
        try {
            // Vérifier si l'email existe déjà
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM user WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetchColumn() > 0) {
                throw new \Exception("Cet email est déjà utilisé");
            }

            // Vérifier si le username existe déjà
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM user WHERE username = ?");
            $stmt->execute([$username]);
            if ($stmt->fetchColumn() > 0) {
                throw new \Exception("Ce nom d'utilisateur est déjà utilisé");
            }

            // Hash du mot de passe
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insertion du nouvel utilisateur
            $stmt = $this->pdo->prepare(
                "INSERT INTO user (username, email, password, role, created_at) 
                VALUES (?, ?, ?, 'user', NOW())"
            );

            return $stmt->execute([$username, $email, $hashedPassword]);

        } catch (\Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function getCurrentUser(int $userId): ?\app\models\User {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM user WHERE user_id = ?");
            $stmt->execute([$userId]);
            
            if ($userData = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $user = new \app\models\User();
                $user->hydrate([
                    'id' => $userData['user_id'],
                    'username' => $userData['username'],
                    'email' => $userData['email'],
                    'role' => $userData['role'],
                    'createdAt' => $userData['created_at']
                ]);
                return $user;
            }
            
            return null;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return null;
        }
    }
}