<?php
session_start(); // Ajout de cette ligne
require_once 'functions.php';

// Liste des pages autorisées pour la redirection
$allowed_pages = [
    'index.php',
    'menu.php',
    'contact.php',
    'restaurant.php'
];

// Récupérer l'URL de retour
$return_url = isset($_GET['return']) ? $_GET['return'] : 'index.php';

// Vérifier si l'URL de retour est autorisée
$is_allowed = false;
foreach ($allowed_pages as $page) {
    if (strpos($return_url, $page) !== false) {
        $is_allowed = true;
        break;
    }
}

// Déconnexion
// Function to handle user deconnexion
function deconnexion() {
    // Destroy the session
    session_unset();
    session_destroy();
}deconnexion();

// Ajouter le paramètre showLogin=1 à l'URL de redirection
$separator = strpos($return_url, '?') !== false ? '&' : '?';
$return_url .= $separator . 'showLogin=1';

// Rediriger vers la page appropriée
if ($is_allowed) {
    header("Location: " . $return_url);
} else {
    header("Location: index.php?showLogin=1");
}
exit();
