<?php
session_start();
// Corriger les chemins d'inclusion avec des chemins absolus
require_once dirname(__DIR__, 2) . '/core/database.php';
require_once dirname(__DIR__, 2) . '/core/functions.php';
require_once dirname(__DIR__, 2) . '/models/Reservation.php';
require_once dirname(__DIR__, 2) . '/controllers/ReservationController.php';

use function app\core\{sanitize, connexionUtilisateur};
use app\models\Reservation;

if (!isset($_SESSION['user_id'])) {
    header('Location: connexionV2.php');
    exit();
}

// Obtenir l'instance de la base de données
$db = \app\core\Database::getInstance();
$pdo = $db->getPdo();

$controller = new \ReservationController($pdo);
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = sanitize($_POST['date']);
    $heure = sanitize($_POST['heure']);
    $personnes = sanitize($_POST['personnes']);
    $user_id = $_SESSION['user_id'];
    $restaurant_id = $_GET['restaurant_id'];
    $specialRequests = isset($_POST['special_requests']) ? sanitize($_POST['special_requests']) : null;

    // Corriger l'ordre des paramètres pour correspondre à la signature de la méthode du contrôleur
    if ($controller->createReservation($restaurant_id, $user_id, $date, $heure, $personnes, $specialRequests)) {
        $message = "Réservation confirmée !";
    }
}

$restaurant_id = $_GET['restaurant_id'] ?? null;
$restaurant = null;

// Récupérer les informations du restaurant depuis la table restaurant
if ($restaurant_id) {
    $query = "SELECT * FROM restaurant WHERE restaurant_id = ?";
    $stmt = $pdo->prepare($query);
    $query = "SELECT * FROM restaurant WHERE restaurant_id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$restaurant_id]);
    $restaurant = $stmt->fetch();
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <title>Réservation</title>
</head>
<body class="reservationV2">

    <?php require_once __DIR__ . '/../includes/header.php'; ?>
    
    <div class="reservation-wrapper">
        <div class="container">
            <div class="form-container">
                <?php if ($restaurant): ?>
                    <div class="restaurant-info">
                        <h2><?= htmlspecialchars($restaurant['name']) ?></h2>
                        <p><strong>Adresse:</strong> <?= htmlspecialchars($restaurant['address']) ?></p>
                        <p><strong>Téléphone:</strong> <?= htmlspecialchars($restaurant['phone_number']) ?></p>
                        <p><strong>Cuisine:</strong> <?= htmlspecialchars($restaurant['cuisine_type']) ?></p>
                    </div>
                <?php endif; ?>
                <h1>Formulaire de Réservation</h1>
                <?php if($message): ?>
                    <p><?= $message ?></p>
                <?php endif; ?>
                <form method="POST">
                    <input type="date" name="date" required>
                    <input type="time" name="heure" required>
                    <input type="number" name="personnes" placeholder="Nombre de personnes" min="1" max="10" required>
                    <input type="text" name="nom" placeholder="Votre nom" required>
                    <input type="email" name="email" placeholder="Votre email" required>
                    <textarea name="special_requests" placeholder="Demandes spéciales"></textarea>
                    <input type="submit" value="Réserver" class="btn">
                </form>
            </div>
        </div>
    </div>

    <script>
        document.querySelector('.menu-toggle').addEventListener('click', function() {
            document.querySelector('.navbar-menu').classList.toggle('active');
        });
    </script>
</body>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
</html>
