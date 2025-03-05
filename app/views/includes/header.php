<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/app/assets/css/header.css">
    <title>Restaurants</title>
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="nav-container">
                <a href="index.php" class="navbar-brand">
                    <span>RestaurantApp</span>
                </a>
                <ul class="navbar-menu">
                    <li><a href="restaurant.php">Restaurants</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <li><a href="monitoring.php">Monitoring</a></li>
                            <li><a href="ticket.php">Tickets</a></li>
                            <li><a href="Commandes.php">Commandes</a></li>
                            <li><a href="mon_compte.php">Mon Compte</a></li>
                            <li><a href="deconnexion.php?return=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>">Déconnexion</a></li>
                        <?php else: ?>
                            <li><a href="espace_personnel.php">Mes Réservations</a></li>
                            <li><a href="mon_compte.php">Mon Compte</a></li>
                            <li><a href="deconnexion.php?return=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>">Déconnexion</a></li>
                        <?php endif; ?>
                    <?php else: ?>
                        <li><a href="connexionV2.php" class="btn-connexion <?php echo isset($_GET['showLogin']) ? 'highlight' : ''; ?>">Se Connecter</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>
    <main>