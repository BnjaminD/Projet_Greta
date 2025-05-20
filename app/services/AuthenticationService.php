<?php namespace App\Services;

use App\Exceptions\UnauthorizedException;

class AuthenticationService {
    // Association des rôles aux permissions
    private array $rolePermissions = [
        'admin' => [
            'admin.dashboard.view',
            'admin.users.manage',
            'admin.content.moderate',
            'admin.system.configure',
            'admin.settings.edit',
            'admin.reports.view'
        ],
        'moderator' => [
            'admin.content.moderate',
            'admin.reports.view'
        ],
        'user' => [
            'user.profile.edit',
            'user.bookings.manage'
        ]
    ];

    /**
     * Vérifie si l'utilisateur a une permission spécifique
     * 
     * @param string $permission La permission à vérifier
     * @return bool True si l'utilisateur a la permission, false sinon
     */
    public function hasPermission(string $permission): bool {
        // Si aucune session utilisateur n'existe, refuser l'accès
        if (!isset($_SESSION['role'])) {
            return false;
        }

        $userRole = $_SESSION['role'];
        
        // Vérifier d'abord le rôle admin (qui a toutes les permissions)
        if ($userRole === 'admin') {
            return true;
        }
        
        // Si le rôle n'existe pas dans notre mappage, refuser l'accès
        if (!isset($this->rolePermissions[$userRole])) {
            return false;
        }
        
        // Vérifier si le rôle de l'utilisateur a la permission demandée
        return in_array($permission, $this->rolePermissions[$userRole]);
    }

    /**
     * Vérifier une permission et lever une exception si l'utilisateur n'a pas la permission
     * 
     * @param string $permission La permission à vérifier
     * @throws UnauthorizedException Si l'utilisateur n'a pas la permission
     */
    public function checkPermission(string $permission): void {
        if (!$this->hasPermission($permission)) {
            throw new UnauthorizedException("Permission '$permission' required");
        }
    }

    /**
     * Vérifie si l'utilisateur est connecté
     * 
     * @return bool True si l'utilisateur est connecté, false sinon
     */
    public function isAuthenticated(): bool {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    /**
     * Vérifie si l'utilisateur a un rôle spécifique
     * 
     * @param string|array $role Un rôle ou un tableau de rôles
     * @return bool True si l'utilisateur a le rôle, false sinon
     */
    public function hasRole($role): bool {
        if (!isset($_SESSION['role'])) {
            return false;
        }
        
        if (is_array($role)) {
            return in_array($_SESSION['role'], $role);
        }
        
        return $_SESSION['role'] === $role;
    }

    /**
     * Récupère l'ID de l'utilisateur connecté
     * 
     * @return int|null L'ID de l'utilisateur ou null s'il n'est pas connecté
     */
    public function getUserId(): ?int {
        return $_SESSION['user_id'] ?? null;
    }

    /**
     * Récupère le rôle de l'utilisateur connecté
     * 
     * @return string|null Le rôle de l'utilisateur ou null s'il n'est pas connecté
     */
    public function getUserRole(): ?string {
        return $_SESSION['role'] ?? null;
    }
}
