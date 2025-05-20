<?php

use app\core\Database;

class TicketController {
    private $db;
    private $view;

    public function __construct(Database $db) {
        $this->db = $db;
        $this->view = new TicketView();
    }

    public function index() {
        // Vérifier si l'utilisateur est connecté
        $currentUserId = $_SESSION['user_id'] ?? null;
        $isAdmin = $currentUserId ? $this->checkAdmin($currentUserId) : false;
        
        // Récupérer tous les tickets ou seulement ceux de l'utilisateur
        $tickets = $isAdmin ? $this->getTickets() : $this->getUserTickets($currentUserId);
        
        // Afficher la vue
        $this->view->render($tickets, $isAdmin, $currentUserId);
    }

    public function handleRequest() {
        // Vérifier si l'utilisateur est connecté
        $currentUserId = $_SESSION['user_id'] ?? null;
        
        if (!$currentUserId) {
            header('Location: /login');
            exit;
        }
        
        // Vérifier si une action est demandée
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['comment_id'])) {
            $action = $_POST['action'];
            $commentId = (int)$_POST['comment_id'];
            $isAdmin = $this->checkAdmin($currentUserId);
            
            // Vérifier si l'utilisateur peut effectuer cette action
            if ($this->canModifyTicket($commentId, $currentUserId, $isAdmin)) {
                $success = $this->handleAction($action, $commentId, $currentUserId);
                
                if ($success) {
                    // Rediriger avec un message de succès
                    header('Location: /tickets?success=1');
                    exit;
                }
            }
            
            // Si on arrive ici, c'est qu'il y a eu un problème
            header('Location: /tickets?error=1');
            exit;
        }
        
        // Si aucune action n'est demandée, afficher la page
        $this->index();
    }

    public function checkAdmin($user_id) {
        $sql = "SELECT ur.role_name FROM user_roles ur WHERE ur.user_id = ? AND ur.role_name = 'admin'";
        $result = $this->db->fetchOne($sql, [$user_id]);
        return $result ? $result['role_name'] : false;
    }

    public function canModifyTicket($comment_id, $user_id, $isAdmin) {
        if ($isAdmin) {
            return true;
        }
        
        // Vérifier si l'utilisateur est le créateur du ticket
        $sql = "SELECT 1 FROM comment WHERE comment_id = ? AND user_id = ?";
        $result = $this->db->fetchOne($sql, [$comment_id, $user_id]);
        
        return !empty($result);
    }

    public function handleAction($action, $comment_id, $user_id) {
        switch ($action) {
            case 'validate':
                $sql = "UPDATE comment SET is_moderated = 1, moderated_at = NOW(), moderated_by = ? WHERE comment_id = ?";
                return $this->db->execute($sql, [$user_id, $comment_id]);
                
            case 'progress':
                $sql = "UPDATE comment SET is_in_progress = 1, is_moderated = 1, moderated_at = NOW(), moderated_by = ? WHERE comment_id = ?";
                return $this->db->execute($sql, [$user_id, $comment_id]);
                
            case 'complete':
                $sql = "UPDATE comment SET is_completed = 1, is_in_progress = 0, is_moderated = 1, moderated_at = NOW(), moderated_by = ? WHERE comment_id = ?";
                return $this->db->execute($sql, [$user_id, $comment_id]);
                
            case 'delete':
                $sql = "DELETE FROM comment WHERE comment_id = ?";
                return $this->db->execute($sql, [$comment_id]);
                
            default:
                return false;
        }
    }

    public function getTickets() {
        $sql = "SELECT c.*, 
                       u.username, 
                       r.name as restaurant_name,
                       c.is_moderated,
                       IFNULL(c.is_in_progress, 0) as is_in_progress,
                       IFNULL(c.is_completed, 0) as is_completed
                FROM comment c
                JOIN user u ON c.user_id = u.user_id
                JOIN restaurant r ON c.restaurant_id = r.restaurant_id
                ORDER BY c.created_at DESC";
        return $this->db->fetchAll($sql);
    }
    
    public function getUserTickets($user_id) {
        if (!$user_id) {
            return [];
        }
        
        $sql = "SELECT c.*, 
                       u.username, 
                       r.name as restaurant_name,
                       c.is_moderated,
                       IFNULL(c.is_in_progress, 0) as is_in_progress,
                       IFNULL(c.is_completed, 0) as is_completed
                FROM comment c
                JOIN user u ON c.user_id = u.user_id
                JOIN restaurant r ON c.restaurant_id = r.restaurant_id
                WHERE c.user_id = ?
                ORDER BY c.created_at DESC";
        return $this->db->fetchAll($sql, [$user_id]);
    }
}
