<?php
namespace app\core;

use PDO;

// No need for requires since we're in the same directory
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/config.php';

// Standalone functions
function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

// Ajouter cette fonction pour le débogage direct
function testUser($username) {
    $db = Database::getInstance();
    
    // Récupérer toutes les informations sur l'utilisateur pour déboguer
    $sql = "SELECT * FROM user WHERE username = ?";
    $user = $db->fetchOne($sql, [$username]);
    
    if (!$user) {
        return "Utilisateur '$username' non trouvé dans la base de données.";
    }
    
    // Formatage des données pour faciliter le débogage
    $result = [
        'user_id' => $user['user_id'],
        'username' => $user['username'],
        'email' => $user['email'],
        'password_hash' => $user['password_hash'],
        'is_active' => $user['is_active'] ? 'Oui' : 'Non'
    ];
    
    // Récupérer les rôles
    $roleSql = "SELECT role_name FROM user_roles WHERE user_id = ?";
    $roles = $db->fetchAll($roleSql, [$user['user_id']]);
    
    $result['roles'] = [];
    foreach ($roles as $role) {
        $result['roles'][] = $role['role_name'];
    }
    
    return $result;
}

// Modifions également la fonction connexionUtilisateur pour être plus explicite
function connexionUtilisateur($username, $password) {
    $db = Database::getInstance();
    
    // Log pour débogage
    error_log("==================== TENTATIVE DE CONNEXION ====================");
    error_log("Tentative de connexion pour l'utilisateur : $username");
    
    // Récupérer directement l'utilisateur complet pour examen avec tous les champs requis
    $sql = "SELECT u.*, r.role_name FROM user u 
            LEFT JOIN user_roles r ON u.user_id = r.user_id 
            WHERE u.username = ?";
    $user = $db->fetchOne($sql, [$username]);
    
    if (!$user) {
        error_log("ERREUR: Utilisateur '$username' non trouvé dans la base de données.");
        return false;
    }
    
    // Afficher l'utilisateur trouvé pour débogage
    error_log("Utilisateur trouvé: ID={$user['user_id']}, Username={$user['username']}");
    error_log("Hash stocké: {$user['password_hash']}");
    error_log("Is_active: " . ($user['is_active'] ? 'Oui' : 'Non'));
    error_log("Rôle: " . ($user['role_name'] ?? 'Non défini'));
    
    // Vérifier explicitement si le compte est actif (convertir en booléen)
    $isActive = (bool)$user['is_active'];
    if (!$isActive) {
        error_log("ERREUR: Compte inactif pour l'utilisateur '$username'");
        return false;
    }
    
    // Vérifier si le compte est verrouillé
    if (!empty($user['account_locked_until'])) {
        $lockTime = strtotime($user['account_locked_until']);
        if ($lockTime > time()) {
            error_log("ERREUR: Compte verrouillé pour l'utilisateur '$username' jusqu'à " . $user['account_locked_until']);
            return false;
        }
    }
    
    // Tester la correspondance du mot de passe
    $passwordMatches = password_verify($password, $user['password_hash']);
    error_log("Résultat password_verify: " . ($passwordMatches ? "SUCCÈS" : "ÉCHEC"));
    
    if (!$passwordMatches) {
        updateFailedLoginAttempts($username);
        error_log("ERREUR: Mot de passe incorrect pour l'utilisateur '$username'");
        error_log("==================== FIN TENTATIVE DE CONNEXION ====================");
        return false;
    }
    
    // En cas de succès, redéfinir le tableau utilisateur pour n'inclure que les informations nécessaires
    $userInfo = [
        'user_id' => $user['user_id'],
        'username' => $user['username'],
        'role' => $user['role_name'] ?? 'user'
    ];
    
    // Réinitialiser le compteur de tentatives infructueuses
    resetFailedLoginAttempts($userInfo['user_id']);
    
    try {
        // Journaliser la connexion
        logUserActivity($userInfo['user_id'], 'Login', 'Connexion réussie');
        // Mettre à jour la date de dernière connexion
        updateLastLogin($userInfo['user_id']);
    } catch (\Exception $e) {
        // Ne pas bloquer la connexion en cas d'erreur de journalisation
        error_log("Erreur non bloquante: " . $e->getMessage());
    }
    
    error_log("SUCCÈS: Connexion réussie pour l'utilisateur '$username'");
    error_log("==================== FIN TENTATIVE DE CONNEXION ====================");
    
    return $userInfo;
}

// Fonction pour mettre à jour les tentatives de connexion infructueuses
function updateFailedLoginAttempts($username) {
    $db = Database::getInstance();
    $sql = "UPDATE user SET failed_login_attempts = failed_login_attempts + 1 WHERE username = ?";
    $db->execute($sql, [$username]);
    
    // Vérifier si le compte doit être verrouillé (après X tentatives)
    $maxAttempts = 5; // À configurer selon vos besoins
    $lockDuration = 15; // minutes
    
    $user = $db->fetchOne("SELECT failed_login_attempts FROM user WHERE username = ?", [$username]);
    if ($user && $user['failed_login_attempts'] >= $maxAttempts) {
        $lockUntil = date('Y-m-d H:i:s', time() + ($lockDuration * 60));
        $db->execute(
            "UPDATE user SET account_locked_until = ? WHERE username = ?",
            [$lockUntil, $username]
        );
        error_log("Compte verrouillé pour l'utilisateur '$username' jusqu'à $lockUntil");
    }
}

// Fonction pour réinitialiser les tentatives de connexion infructueuses
function resetFailedLoginAttempts($userId) {
    $db = Database::getInstance();
    
    // S'assurer que $userId est un entier
    $userId = intval($userId);
    
    $sql = "UPDATE user SET failed_login_attempts = 0, account_locked_until = NULL WHERE user_id = ?";
    $db->execute($sql, [$userId]);
}

// Fonction pour journaliser l'activité utilisateur
function logUserActivity($userId, $action, $details = null) {
    $db = Database::getInstance();
    $data = [
        'user_id' => $userId,
        'action' => $action,
        'details' => $details
    ];
    
    try {
        $db->insert('user_activity_log', $data);
    } catch (\Exception $e) {
        error_log("Erreur lors de la journalisation de l'activité: " . $e->getMessage());
    }
}

// Fonction pour mettre à jour la date de dernière connexion
function updateLastLogin($userId) {
    $db = Database::getInstance();
    $sql = "UPDATE user SET last_login = NOW() WHERE user_id = ?";
    $db->execute($sql, [$userId]);
}

// Fonction pour vérifier si un utilisateur est un admin
function isAdmin($userId) {
    $db = Database::getInstance();
    $sql = "SELECT COUNT(*) as count FROM user_roles WHERE user_id = ? AND role_name = 'admin'";
    $result = $db->fetchOne($sql, [$userId]);
    
    return $result && $result['count'] > 0;
}

// Fonction pour obtenir les informations d'un utilisateur
function getUserById($userId) {
    $db = Database::getInstance();
    $sql = "SELECT * FROM user WHERE user_id = ?";
    return $db->fetchOne($sql, [$userId]);
}

// Fonction pour créer un nouvel utilisateur
function createUser($username, $email, $password, $role = 'user') {
    $db = Database::getInstance();
    
    // Valider les entrées
    if (empty($username) || empty($email) || empty($password)) {
        return [
            'success' => false,
            'message' => ERROR_REQUIRED_FIELDS
        ];
    }
    
    // Vérifier si l'email ou le username existent déjà
    $checkUser = "SELECT COUNT(*) as count FROM user WHERE email = ? OR username = ?";
    $userExists = $db->fetchOne($checkUser, [$email, $username]);
    
    if ($userExists && $userExists['count'] > 0) {
        return [
            'success' => false,
            'message' => 'Email ou nom d\'utilisateur déjà utilisé'
        ];
    }
    
    // Hash du mot de passe
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Insérer l'utilisateur
    try {
        $userId = $db->insert('user', [
            'username' => $username,
            'email' => $email,
            'password_hash' => $hashedPassword,
            'created_at' => date('Y-m-d H:i:s'),
            'is_active' => 1
        ]);
        
        // Ajouter le rôle
        $db->insert('user_roles', [
            'user_id' => $userId,
            'role_name' => $role
        ]);
        
        return [
            'success' => true,
            'user_id' => $userId,
            'message' => 'Inscription réussie'
        ];
    } catch (\Exception $e) {
        error_log("Erreur lors de la création de l'utilisateur: " . $e->getMessage());
        return [
            'success' => false,
            'message' => 'Erreur lors de l\'inscription'
        ];
    }
}

// Fonction pour mettre à jour un utilisateur
function updateUser($userId, $data) {
    $db = Database::getInstance();
    
    // Valider l'ID
    if (empty($userId) || !is_numeric($userId)) {
        return [
            'success' => false,
            'message' => 'ID utilisateur invalide'
        ];
    }
    
    // Vérifier si l'utilisateur existe
    $user = getUserById($userId);
    if (!$user) {
        return [
            'success' => false,
            'message' => 'Utilisateur non trouvé'
        ];
    }
    
    // Si changement de mot de passe
    if (isset($data['password']) && !empty($data['password'])) {
        $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        unset($data['password']);
    }
    
    try {
        $updated = $db->update('user', $data, 'user_id = ?', [$userId]);
        
        return [
            'success' => $updated > 0,
            'message' => $updated > 0 ? 'Mise à jour réussie' : 'Aucune modification'
        ];
    } catch (\Exception $e) {
        error_log("Erreur lors de la mise à jour de l'utilisateur: " . $e->getMessage());
        return [
            'success' => false,
            'message' => 'Erreur lors de la mise à jour'
        ];
    }
}

// Fonction pour récupérer des restaurants
function getRestaurants($limit = null, $offset = 0, $filters = []) {
    $db = Database::getInstance();
    
    $sql = "SELECT * FROM restaurant";
    $params = [];
    
    // Ajouter des conditions si des filtres sont fournis
    if (!empty($filters)) {
        $whereConditions = [];
        
        if (isset($filters['cuisine_type']) && !empty($filters['cuisine_type'])) {
            $whereConditions[] = "cuisine_type = ?";
            $params[] = $filters['cuisine_type'];
        }
        
        if (isset($filters['rating']) && is_numeric($filters['rating'])) {
            $whereConditions[] = "rating >= ?";
            $params[] = $filters['rating'];
        }
        
        if (!empty($whereConditions)) {
            $sql .= " WHERE " . implode(" AND ", $whereConditions);
        }
    }
    
    // Ajouter tri
    $sql .= " ORDER BY rating DESC, name ASC";
    
    // Ajouter limite si spécifiée
    if ($limit !== null && is_numeric($limit)) {
        $sql .= " LIMIT ?, ?";
        $params[] = (int)$offset;
        $params[] = (int)$limit;
    }
    
    return $db->fetchAll($sql, $params);
}

// Fonction pour effectuer une réservation
function createReservation($userId, $restaurantId, $reservationTime, $numberOfGuests, $specialRequests = null) {
    $db = Database::getInstance();
    
    // Validation basique
    if (empty($userId) || empty($restaurantId) || empty($reservationTime) || empty($numberOfGuests)) {
        return [
            'success' => false,
            'message' => ERROR_REQUIRED_FIELDS
        ];
    }
    
    try {
        $reservationId = $db->insert('reservation', [
            'user_id' => $userId,
            'restaurant_id' => $restaurantId,
            'reservation_time' => $reservationTime,
            'number_of_guests' => $numberOfGuests,
            'special_requests' => $specialRequests,
            'status' => 'pending'
        ]);
        
        // Journaliser l'activité
        logUserActivity($userId, 'Reservation', "Reservation created for restaurant ID: $restaurantId");
        
        return [
            'success' => true,
            'reservation_id' => $reservationId,
            'message' => 'Réservation créée avec succès'
        ];
    } catch (\Exception $e) {
        error_log("Erreur lors de la création de la réservation: " . $e->getMessage());
        return [
            'success' => false,
            'message' => 'Erreur lors de la création de la réservation'
        ];
    }
}

// Fonction pour obtenir le menu d'un restaurant
function getRestaurantMenu($restaurantId) {
    $db = Database::getInstance();
    
    // Récupérer d'abord les menus
    $menuSql = "SELECT * FROM menu WHERE restaurant_id = ? AND is_active = 1";
    $menus = $db->fetchAll($menuSql, [$restaurantId]);
    
    if (empty($menus)) {
        return [];
    }
    
    // Pour chaque menu, récupérer les plats
    $result = [];
    foreach ($menus as $menu) {
        $dishSql = "SELECT * FROM dish WHERE menu_id = ? AND available = 1 ORDER BY category, name";
        $dishes = $db->fetchAll($dishSql, [$menu['menu_id']]);
        
        $menu['dishes'] = $dishes;
        $result[] = $menu;
    }
    
    return $result;
}

// Fonction pour journaliser les erreurs système
function logSystemError($message, $severity = 'ERROR', $context = null) {
    $db = Database::getInstance();
    
    try {
        $db->insert('system_logs', [
            'log_type' => 'SYSTEM_ERROR',
            'message' => $message,
            'severity' => $severity,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
            'context' => $context ? json_encode($context) : null
        ]);
    } catch (\Exception $e) {
        // Si la journalisation dans la base échoue, on utilise error_log standard
        error_log("Erreur système: $message | Sévérité: $severity");
    }
}

// Fonction pour paginer les résultats
function paginate($total, $limit, $currentPage) {
    $totalPages = ceil($total / $limit);
    
    return [
        'total' => $total,
        'per_page' => $limit,
        'current_page' => $currentPage,
        'last_page' => $totalPages,
        'from' => (($currentPage - 1) * $limit) + 1,
        'to' => min($currentPage * $limit, $total)
    ];
}