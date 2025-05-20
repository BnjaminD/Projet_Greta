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

// Traiter les actions de requête comme la validation, la suppression, etc.
$controller->handleRequest();
