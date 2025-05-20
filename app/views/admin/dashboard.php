<?php
// Démarrer la session et inclure les fichiers fondamentaux d'abord
session_start();
// Activer la journalisation d'erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Vérification de l'authentification et du rôle admin

// Vérifier si AdminData existe déjà et utiliser cette classe si c'est le cas
$adminDataFile = dirname(__DIR__, 2) . '/models/AdminData.php';
$adminRepoFile = dirname(__DIR__, 2) . '/interfaces/IAdminRepository.php';

// Inclure les dépendances de base
require_once dirname(__DIR__, 2) . '/exceptions/DatabaseException.php';
require_once dirname(__DIR__, 2) . '/exceptions/UnauthorizedException.php';
require_once dirname(__DIR__, 2) . '/core/Database.php';
require_once dirname(__DIR__, 2) . '/core/functions.php';

// Si AdminData existe, l'utiliser plutôt que de créer notre propre implémentation
if (file_exists($adminDataFile)) {
    require_once $adminRepoFile;  // Charger l'interface d'abord
    require_once $adminDataFile;  // Puis la classe AdminData
} else {
    // Créer IAdminRepository.php si nécessaire
    if (!file_exists($adminRepoFile)) {
        // Créer le répertoire interfaces s'il n'existe pas
        if (!is_dir(dirname($adminRepoFile))) {
            mkdir(dirname($adminRepoFile), 0755, true);
        }
        
        // Créer le fichier d'interface
        file_put_contents($adminRepoFile, '<?php
namespace App\Interfaces;

interface IAdminRepository {
    public function getSystemLogs();
    public function getAdminActions();
    public function getUserStatistics();
    public function getModeratedContent();
}');
    }
    
    // Créer AdminRepository.php si nécessaire et si AdminData n'existe pas
    $adminRepoImplFile = dirname(__DIR__, 2) . '/repositories/AdminRepository.php';
    if (!file_exists($adminRepoImplFile)) {
        // Créer le répertoire repositories s'il n'existe pas
        if (!is_dir(dirname($adminRepoImplFile))) {
            mkdir(dirname($adminRepoImplFile), 0755, true);
        }
        
        // Créer le fichier d'implémentation
        file_put_contents($adminRepoImplFile, '<?php
namespace App\Repositories;

use app\core\Database;
use App\Interfaces\IAdminRepository;
use App\Exceptions\DatabaseException;

class AdminRepository implements IAdminRepository {
    private Database $db;
    
    public function __construct(Database $db) {
        $this->db = $db;
    }
    
    public function getSystemLogs() {
        try {
            return $this->db->fetchAll(
                "SELECT * FROM system_logs ORDER BY created_at DESC LIMIT 50",
                []
            );
        } catch (\Exception $e) {
            throw new DatabaseException("Failed to retrieve system logs: " . $e->getMessage());
        }
    }
    
    public function getAdminActions() {
        try {
            return $this->db->fetchAll(
                "SELECT * FROM user_activity_log WHERE action LIKE \'admin%\' ORDER BY timestamp DESC LIMIT 50",
                []
            );
        } catch (\Exception $e) {
            throw new DatabaseException("Failed to retrieve admin actions: " . $e->getMessage());
        }
    }
    
    public function getUserStatistics() {
        try {
            $stats = [
                \'total_users\' => 0,
                \'active_users\' => 0,
                \'new_users_last_30_days\' => 0
            ];
            
            $result = $this->db->fetchOne("SELECT COUNT(*) as count FROM user", []);
            if ($result) {
                $stats[\'total_users\'] = $result[\'count\'];
            }
            
            $result = $this->db->fetchOne("SELECT COUNT(*) as count FROM user WHERE is_active = 1", []);
            if ($result) {
                $stats[\'active_users\'] = $result[\'count\'];
            }
            
            $result = $this->db->fetchOne(
                "SELECT COUNT(*) as count FROM user WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)",
                []
            );
            if ($result) {
                $stats[\'new_users_last_30_days\'] = $result[\'count\'];
            }
            
            return $stats;
        } catch (\Exception $e) {
            throw new DatabaseException("Failed to retrieve user statistics: " . $e->getMessage());
        }
    }
    
    public function getModeratedContent() {
        // Dans un vrai système, cette méthode récupérerait le contenu modéré
        return [];
    }
}');
    }
    
    // Inclure les fichiers nouvellement créés si AdminData n'existe pas
    require_once $adminRepoFile;
    require_once $adminRepoImplFile;
}

// BLOC 3: Créer le fichier AuthenticationService.php si nécessaire
$authServiceFile = dirname(__DIR__, 2) . '/services/AuthenticationService.php';
if (!file_exists($authServiceFile)) {
    // Créer le répertoire services s'il n'existe pas
    if (!is_dir(dirname($authServiceFile))) {
        mkdir(dirname($authServiceFile), 0755, true);
    }
    
    // Créer le fichier de service
    file_put_contents($authServiceFile, '<?php
namespace App\Services;

class AuthenticationService {
    public function hasPermission($permission) {
        // Implémentation simplifiée - accepte n\'importe quel paramètre
        // et vérifie seulement que l\'utilisateur est admin
        return isset($_SESSION[\'role\']) && $_SESSION[\'role\'] === \'admin\';
    }
}');
}

// Inclure le fichier d'authentification
require_once $authServiceFile;
require_once dirname(__DIR__, 2) . '/controllers/AdminController.php';

// Utiliser des namespaces sans redéfinir de classes
use app\core\Database;
use App\Controllers\AdminController;
use App\Exceptions\UnauthorizedException;

// Créer les instances avec les dépendances appropriées
$db = Database::getInstance();

// Instancier le repository approprié (AdminData ou AdminRepository)
if (file_exists($adminDataFile)) {
    $adminRepository = new \App\Models\AdminData($db->getPdo());
} else {
    $adminRepository = new \App\Repositories\AdminRepository($db);
}

$authService = new \App\Services\AuthenticationService();

// Instancier le contrôleur correctement
$adminController = new AdminController($adminRepository, $authService);

// Déboguer les instances pour vérifier si elles sont correctement créées
// Ces lignes peuvent être commentées après débogage
/*
echo "<pre>";
echo "Repository class: " . get_class($adminRepository) . "<br>";
echo "Auth service class: " . get_class($authService) . "<br>";
echo "Controller class: " . get_class($adminController) . "<br>";
echo "</pre>";
*/

// Récupérer les données du tableau de bord
try {
    $dashboardData = $adminController->getDashboardData();
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'data' => $dashboardData
    ]);
} catch (UnauthorizedException $e) {
    header('Content-Type: application/json');
    http_response_code(403);
    echo json_encode([
        'error' => true,
        'message' => 'Accès non autorisé: ' . $e->getMessage()
    ]);
} catch (\Exception $e) {
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => 'Erreur serveur: ' . $e->getMessage(),
        'type' => get_class($e),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
}