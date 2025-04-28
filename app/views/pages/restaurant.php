<?php
session_start();

// Configuration de base avec chemins absolus
define('ROOT_PATH', dirname(__FILE__, 4));  // Remonte de 4 niveaux pour atteindre la racine
define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('VIEWS_PATH', APP_PATH . '/views');

// Gestion des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Inclusions dans le bon ordre avec chemins absolus
    require_once APP_PATH . '/core/database.php';
    require_once APP_PATH . '/controllers/AbstractController.php';  // Added this line
    require_once APP_PATH . '/models/RestaurantInterface.php';
    require_once APP_PATH . '/models/Restaurant.php';
    require_once APP_PATH . '/controllers/RestaurantController.php';

    // Get database instance
    $db = \app\core\Database::getInstance();
    $pdo = $db->getPdo();  // You'll need to add this method to Database class

    // Initialisation du contrôleur
    $restaurantController = new RestaurantController($pdo);

    // Vérification utilisateur
    $currentUser = null;
    if (isset($_SESSION['user_id'])) {
        $stmt = $pdo->prepare("SELECT * FROM user WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $_SESSION['user_id']]);
        $currentUser = $stmt->fetch(PDO::FETCH_ASSOC);
    }
} catch (Exception $e) {
    error_log('Error: ' . $e->getMessage());
    die('Une erreur est survenue. Veuillez réessayer plus tard.');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurants</title>
    <link rel="stylesheet" href="/Projet_Greta/app/assets/css/styles.css">
    <link rel="stylesheet" href="/Projet_Greta/app/assets/css/footer.css">
</head>
<body>
    <?php 
    try {
        include VIEWS_PATH . '/includes/header.php';
        
        echo '<div class="container">';
        
        // Validation et nettoyage de l'ID du restaurant
        $restaurant_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        
        if ($restaurant_id) {
            $restaurant = $restaurantController->findById($restaurant_id);
            if (!$restaurant) {
                throw new Exception("Restaurant non trouvé");
            }
            include VIEWS_PATH . '/pages/restaurants/single.php';
        } else {
            $restaurants = $restaurantController->findAll();
            include VIEWS_PATH . '/pages/restaurants/list.php';
        }
        
        echo '</div>';
        
        include VIEWS_PATH . '/includes/footer.php';
    } catch (Exception $e) {
        error_log('View Error: ' . $e->getMessage());
        echo '<div class="error">Une erreur est survenue lors de l\'affichage.</div>';
    }
    ?>
</body>
</html>