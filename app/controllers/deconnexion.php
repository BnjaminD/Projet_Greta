<?php
session_start(); // Démarrer la session avant de la détruire

// Vérification et correction du chemin d'inclusion - TRÈS IMPORTANT
require_once __DIR__ . '/../core/functions.php';

// Journal de l'activité de déconnexion si l'utilisateur était connecté
if (isset($_SESSION['user_id'])) {
    // Si l'utilisateur était connecté, journaliser la déconnexion
    try {
        \app\core\logUserActivity($_SESSION['user_id'], 'Logout', 'Déconnexion effectuée');
    } catch (\Exception $e) {
        // Ignorer les erreurs de journalisation pour ne pas bloquer la déconnexion
    }
}

// Déconnexion - destruction de la session
session_unset();
session_destroy();

// Rediriger vers la page espace_personnel.php au lieu de accueil.php
header("Location: ../views/pages/accueil.php");
exit();
