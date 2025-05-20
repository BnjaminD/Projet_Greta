<?php
session_start();

// Mise à jour des chemins d'inclusion et utilisation de Database.php
require_once dirname(__DIR__, 2) . '/core/Database.php';
require_once dirname(__DIR__, 2) . '/core/functions.php';
require_once dirname(__DIR__, 2) . '/controllers/TicketController.php';
require_once __DIR__ . '/TicketView.php';
require_once dirname(__DIR__, 2) . '/models/Comment.php';

// Utiliser le namespace approprié
use app\core\Database;

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: /connexionV2.php');
    exit();
}

// Obtenir une instance de la base de données
$db = Database::getInstance();

// Initialiser le contrôleur avec l'instance Database
$controller = new TicketController($db);

// Variable pour suivre si l'utilisateur est admin
$isAdmin = $controller->checkAdmin($_SESSION['user_id']);

// Définir une classe CSS pour le body
$bodyClass = 'admin-tickets';

// Stocker la classe dans une variable pour l'utiliser dans le header
$pageTitle = 'Gestion des Tickets';

// Gérer les actions POST (valider, mettre en cours, terminer, supprimer)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && isset($_POST['comment_id'])) {
    $action = $_POST['action'];
    $commentId = (int)$_POST['comment_id'];
    
    // Vérifier si l'utilisateur peut modifier ce ticket
    if ($controller->canModifyTicket($commentId, $_SESSION['user_id'], $isAdmin)) {
        $success = $controller->handleAction($action, $commentId, $_SESSION['user_id']);
        
        if ($success) {
            $message = "L'action a été effectuée avec succès.";
            $messageType = "success";
        } else {
            $message = "Une erreur est survenue lors de l'exécution de l'action.";
            $messageType = "error";
        }
    } else {
        $message = "Vous n'avez pas les droits nécessaires pour effectuer cette action.";
        $messageType = "error";
    }
    
    // Rediriger pour éviter la soumission multiple du formulaire
    header('Location: ticket.php' . ($message ? "?message=" . urlencode($message) . "&type=" . $messageType : ""));
    exit();
}

// Récupérer les tickets selon les permissions de l'utilisateur
$tickets = $isAdmin ? $controller->getTickets() : $controller->getUserTickets($_SESSION['user_id']);

// Créer la variable pour le header qui contient le lien vers la feuille de style spécifique
$additionalStyles = '<link rel="stylesheet" href="/app/assets/css/tickets.css">';

// Afficher la vue
$view = new TicketView();
$view->render($tickets, $isAdmin, $_SESSION['user_id']);
