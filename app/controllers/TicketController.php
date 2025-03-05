<?php

class TicketController {
    private $pdo;
    private $view;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        $this->view = new TicketView();
    }

    public function checkAdmin($user_id) {
        $stmt = $this->pdo->prepare("SELECT ur.role_name FROM user_roles ur WHERE ur.user_id = ? AND ur.role_name = 'admin'");
        $stmt->execute([$user_id]);
        return $stmt->fetchColumn();
    }

    public function handleAction($action, $comment_id, $user_id) {
        if ($action === 'approve') {
            $stmt = $this->pdo->prepare("UPDATE comment SET is_moderated = 1, moderated_at = NOW(), moderated_by = ? WHERE comment_id = ?");
            return $stmt->execute([$user_id, $comment_id]);
        } elseif ($action === 'delete') {
            $stmt = $this->pdo->prepare("DELETE FROM comment WHERE comment_id = ?");
            return $stmt->execute([$comment_id]);
        }
        return false;
    }

    public function getTickets() {
        $stmt = $this->pdo->prepare("
            SELECT c.*, u.username, r.name as restaurant_name 
            FROM comment c
            JOIN user u ON c.user_id = u.user_id
            JOIN restaurant r ON c.restaurant_id = r.restaurant_id
            ORDER BY c.created_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
