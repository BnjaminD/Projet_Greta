<?php
session_start();

// Update require paths to use absolute paths from project root
require_once __DIR__ . '/../../core/Database.php';
require_once __DIR__ . '/../../views/includes/functions.php';
require_once __DIR__ . '/../../core/config.php';
require_once __DIR__ . '/../../controllers/TicketController.php';
require_once __DIR__ . '/TicketView.php';
require_once __DIR__ . '/../../models/Comment.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: connexionV2.php');
    exit();
}

// Initialiser le contrôleur
$controller = new TicketController($pdo);

// Vérifier les droits admin
if (!$controller->checkAdmin($_SESSION['user_id'])) {
    echo "Accès réservé aux administrateurs";
    exit();
}

// Gérer les actions POST
if (isset($_POST['action']) && isset($_POST['comment_id'])) {
    $controller->handleAction($_POST['action'], $_POST['comment_id'], $_SESSION['user_id']);
    header('Location: ticket.php');
    exit();
}

// Afficher la vue
$tickets = $controller->getTickets();
$view = new TicketView();
$view->render($tickets);
