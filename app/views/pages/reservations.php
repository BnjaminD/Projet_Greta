
<?php
// Correction des chemins d'inclusion
require_once dirname(__DIR__, 2) . '/core/functions.php';
require_once dirname(__DIR__, 2) . '/core/Database.php';

// Importer les namespaces appropriés
use app\core\Database;

session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Rediriger vers la page de connexion si non connecté
    header("Location: connexionV2.php");
    exit();
}

// Obtenir une instance de la base de données
$db = Database::getInstance();

// Récupération des réservations de l'utilisateur
$userId = $_SESSION['user_id'];
$reservations = $db->fetchAll(
    "SELECT r.*, rest.name as restaurant_name, rest.address 
     FROM reservation r 
     JOIN restaurant rest ON r.restaurant_id = rest.restaurant_id 
     WHERE r.user_id = ? 
     ORDER BY r.reservation_time DESC", 
    [$userId]
);

// Inclure le header
include_once dirname(__DIR__) . '/includes/header.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Réservations - RestaurantApp</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body class="reservations">
    <div class="container">
        <h1>Mes Réservations</h1>
        
        <?php if (empty($reservations)): ?>
            <div class="alert alert-info">
                Vous n'avez pas encore de réservations. 
                <a href="restaurant.php">Découvrez nos restaurants</a> pour faire votre première réservation !
            </div>
        <?php else: ?>
            <div class="reservations-list">
                <?php foreach ($reservations as $reservation): ?>
                    <div class="reservation-card">
                        <div class="reservation-header">
                            <h3><?= htmlspecialchars($reservation['restaurant_name']) ?></h3>
                            <span class="status status-<?= strtolower($reservation['status']) ?>">
                                <?= htmlspecialchars($reservation['status']) ?>
                            </span>
                        </div>
                        
                        <div class="reservation-details">
                            <p>
                                <i class="fas fa-calendar"></i> 
                                Date: <?= date('d/m/Y', strtotime($reservation['reservation_time'])) ?>
                            </p>
                            <p>
                                <i class="fas fa-clock"></i> 
                                Heure: <?= date('H:i', strtotime($reservation['reservation_time'])) ?>
                            </p>
                            <p>
                                <i class="fas fa-users"></i> 
                                Nombre de personnes: <?= htmlspecialchars($reservation['number_of_guests']) ?>
                            </p>
                            <p>
                                <i class="fas fa-map-marker-alt"></i> 
                        Adresse: <?= htmlspecialchars($reservation['address']) ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </body>
                                </html>