<?php
session_start(); // Ajout de session_start() au début

// Vérifier si l'utilisateur est connecté et est admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: connexionV2.php');
    exit();
}

require_once 'database.php';
require_once 'functions.php';

// Récupérer toutes les commandes
$query = "SELECT o.order_id, o.total_price, o.status, o.ordered_at, r.name as restaurant_name, u.email as user_email 
          FROM `order` o 
          JOIN restaurant r ON o.restaurant_id = r.restaurant_id 
          JOIN user u ON o.user_id = u.user_id 
          ORDER BY o.ordered_at DESC";
$stmt = $pdo->query($query);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer toutes les réservations
$query_reservations = "SELECT res.reservation_id, res.reservation_time, res.number_of_guests, 
                             res.status, res.special_requests, res.created_at,
                             r.name as restaurant_name, u.email as user_email
                      FROM reservation res
                      JOIN restaurant r ON res.restaurant_id = r.restaurant_id
                      JOIN user u ON res.user_id = u.user_id
                      ORDER BY res.reservation_time DESC";
$stmt_reservations = $pdo->query($query_reservations);
$reservations = $stmt_reservations->fetchAll(PDO::FETCH_ASSOC);

// Ajout de la requête pour les commentaires
$query_comments = "SELECT c.comment_id, c.content, c.rating, c.created_at, 
                         c.is_moderated, c.moderated_at,
                         r.name as restaurant_name, u.email as user_email
                  FROM comment c
                  JOIN restaurant r ON c.restaurant_id = r.restaurant_id
                  JOIN user u ON c.user_id = u.user_id
                  ORDER BY c.created_at DESC";
$stmt_comments = $pdo->query($query_comments);
$comments = $stmt_comments->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Monitoring - Administration</title>
    <link href="css/styles.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="admin-monitoring">
    <div class="page-wrapper">
        <?php include 'header.php'; ?>
        
        <div class="container">
            <h1>Monitoring des commandes</h1>
            
            <table class="table">
                <thead>
                    <tr>
                        <th>N° Commande</th>
                        <th>Restaurant</th>
                        <th>Client</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($orders as $order): ?>
                    <tr>
                        <td>#<?= htmlspecialchars($order['order_id']) ?></td>
                        <td><?= htmlspecialchars($order['restaurant_name']) ?></td>
                        <td><?= htmlspecialchars($order['user_email']) ?></td>
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
                </tbody>
            </table>
    
            <h1>Monitoring des réservations</h1>
            <table class="table">
                <thead>
                    <tr>
                        <th>N° Réservation</th>
                        <th>Restaurant</th>
                        <th>Client</th>
                        <th>Date de réservation</th>
                        <th>Nombre de personnes</th>
                        <th>Statut</th>
                        <th>Demandes spéciales</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($reservations as $reservation): ?>
                    <tr>
                        <td>#<?= htmlspecialchars($reservation['reservation_id']) ?></td>
                        <td><?= htmlspecialchars($reservation['restaurant_name']) ?></td>
                        <td><?= htmlspecialchars($reservation['user_email']) ?></td>
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
                </tbody>
            </table>
    
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
                </tbody>
            </table>
        </div>
        
        <?php include 'footer.php'; ?>
    </div>
</body>
</html>