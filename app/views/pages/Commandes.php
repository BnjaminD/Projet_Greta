<?php
session_start(); // Ajout de session_start() au début

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: connexionV2.php');
    exit();
}

// Correction des chemins d'inclusion
require_once dirname(__DIR__, 2) . '/core/Database.php';
require_once dirname(__DIR__, 2) . '/core/functions.php';

// Utiliser les namespaces appropriés
use app\core\Database;

// Déterminer si l'utilisateur est admin
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
$user_id = $_SESSION['user_id'];

// Obtenir une instance de la base de données
$db = Database::getInstance();

// Requêtes différentes selon que l'utilisateur est admin ou non
if ($isAdmin) {
    // Pour l'admin: récupérer toutes les commandes
    $query = "SELECT o.order_id, o.total_price, o.status, o.ordered_at, r.name as restaurant_name, u.email as user_email 
              FROM `order` o 
              JOIN restaurant r ON o.restaurant_id = r.restaurant_id 
              JOIN user u ON o.user_id = u.user_id 
              ORDER BY o.ordered_at DESC";
    $orders = $db->fetchAll($query);
} else {
    // Pour l'utilisateur standard: récupérer uniquement ses commandes
    $query = "SELECT o.order_id, o.total_price, o.status, o.ordered_at, r.name as restaurant_name 
              FROM `order` o 
              JOIN restaurant r ON o.restaurant_id = r.restaurant_id 
              WHERE o.user_id = ? 
              ORDER BY o.ordered_at DESC";
    $orders = $db->fetchAll($query, [$user_id]);
}

// Récupérer les réservations
if ($isAdmin) {
    // Pour l'admin: toutes les réservations
    $query_reservations = "SELECT res.reservation_id, res.reservation_time, res.number_of_guests, 
                               res.status, res.special_requests, res.created_at,
                               r.name as restaurant_name, u.email as user_email
                          FROM reservation res
                          JOIN restaurant r ON res.restaurant_id = r.restaurant_id
                          JOIN user u ON res.user_id = u.user_id
                          ORDER BY res.reservation_time DESC";
    $reservations = $db->fetchAll($query_reservations);
} else {
    // Pour l'utilisateur standard: uniquement ses réservations
    $query_reservations = "SELECT res.reservation_id, res.reservation_time, res.number_of_guests, 
                               res.status, res.special_requests, res.created_at,
                               r.name as restaurant_name
                          FROM reservation res
                          JOIN restaurant r ON res.restaurant_id = r.restaurant_id
                          WHERE res.user_id = ?
                          ORDER BY res.reservation_time DESC";
    $reservations = $db->fetchAll($query_reservations, [$user_id]);
}

// Récupérer les commentaires uniquement pour l'admin
if ($isAdmin) {
    $query_comments = "SELECT c.comment_id, c.content, c.rating, c.created_at, 
                             c.is_moderated, c.moderated_at,
                             r.name as restaurant_name, u.email as user_email
                      FROM comment c
                      JOIN restaurant r ON c.restaurant_id = r.restaurant_id
                      JOIN user u ON c.user_id = u.user_id
                      ORDER BY c.created_at DESC";
    $comments = $db->fetchAll($query_comments);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= $isAdmin ? "Monitoring - Administration" : "Mes Commandes et Réservations" ?></title>
    <link href="../../assets/css/styles.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="<?= $isAdmin ? 'admin-monitoring' : 'user-orders' ?>">
    <div class="page-wrapper">
        <?php include dirname(__DIR__) . '/includes/header.php'; ?>
        
        <div class="container">
            <h1><?= $isAdmin ? "Monitoring des commandes" : "Mes Commandes" ?></h1>
            
            <table class="table">
                <thead>
                    <tr>
                        <th>N° Commande</th>
                        <th>Restaurant</th>
                        <?php if ($isAdmin): ?><th>Client</th><?php endif; ?>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($orders)): ?>
                    <tr>
                        <td colspan="<?= $isAdmin ? 7 : 6 ?>" class="text-center">Aucune commande trouvée</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach($orders as $order): ?>
                        <tr>
                            <td>#<?= htmlspecialchars($order['order_id']) ?></td>
                            <td><?= htmlspecialchars($order['restaurant_name']) ?></td>
                            <?php if ($isAdmin): ?><td><?= htmlspecialchars($order['user_email']) ?></td><?php endif; ?>
                            <td><?= date('d/m/Y H:i', strtotime($order['ordered_at'])) ?></td>
                            <td>
                                <span class="status-badge status-<?= strtolower($order['status']) ?>">
                                    <?= htmlspecialchars($order['status']) ?>
                                </span>
                            </td>
                            <td><?= number_format($order['total_price'], 2) ?>€</td>
                            <td>
                                <a href="order-detail.php?id=<?= $order['order_id'] ?>" class="btn">
                                    Détails
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
    
            <h1><?= $isAdmin ? "Monitoring des réservations" : "Mes Réservations" ?></h1>
            <table class="table">
                <thead>
                    <tr>
                        <th>N° Réservation</th>
                        <th>Restaurant</th>
                        <?php if ($isAdmin): ?><th>Client</th><?php endif; ?>
                        <th>Date de réservation</th>
                        <th>Nombre de personnes</th>
                        <th>Statut</th>
                        <th>Demandes spéciales</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($reservations)): ?>
                    <tr>
                        <td colspan="<?= $isAdmin ? 8 : 7 ?>" class="text-center">Aucune réservation trouvée</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach($reservations as $reservation): ?>
                        <tr>
                            <td>#<?= htmlspecialchars($reservation['reservation_id']) ?></td>
                            <td><?= htmlspecialchars($reservation['restaurant_name']) ?></td>
                            <?php if ($isAdmin): ?><td><?= htmlspecialchars($reservation['user_email']) ?></td><?php endif; ?>
                            <td><?= date('d/m/Y H:i', strtotime($reservation['reservation_time'])) ?></td>
                            <td><?= htmlspecialchars($reservation['number_of_guests']) ?></td>
                            <td>
                                <span class="status-badge status-<?= strtolower($reservation['status']) ?>">
                                    <?= htmlspecialchars($reservation['status']) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($reservation['special_requests'] ?? 'Aucune') ?></td>
                            <td>
                                <a href="reservation-detail.php?id=<?= $reservation['reservation_id'] ?>" class="btn">
                                    Détails
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
    
            <?php if ($isAdmin): ?>
            <h1>Monitoring des commentaires</h1>
            <table class="table">
                <thead>
                    <tr>
                        <th>N° Commentaire</th>
                        <th>Restaurant</th>
                        <th>Client</th>
                        <th>Commentaire</th>
                        <th>Note</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($comments)): ?>
                    <tr>
                        <td colspan="8" class="text-center">Aucun commentaire trouvé</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach($comments as $comment): ?>
                        <tr>
                            <td>#<?= htmlspecialchars($comment['comment_id']) ?></td>
                            <td><?= htmlspecialchars($comment['restaurant_name']) ?></td>
                            <td><?= htmlspecialchars($comment['user_email']) ?></td>
                            <td><?= htmlspecialchars(substr($comment['content'], 0, 50)) ?>...</td>
                            <td><?= number_format($comment['rating'], 1) ?>/5</td>
                            <td><?= date('d/m/Y H:i', strtotime($comment['created_at'])) ?></td>
                            <td>
                                <span class="status-badge status-<?= $comment['is_moderated'] ? 'moderated' : 'pending' ?>">
                                    <?= $comment['is_moderated'] ? 'Modéré' : 'En attente' ?>
                                </span>
                            </td>
                            <td>
                                <a href="comment-detail.php?id=<?= $comment['comment_id'] ?>" class="btn">
                                    Détails
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
        
        <?php include dirname(__DIR__) . '/includes/footer.php'; ?>
    </div>
</body>
</html>