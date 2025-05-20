<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Application Restaurant' ?></title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/css/header.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="<?= $bodyClass ?? '' ?>">
    <header>
        <nav class="navbar">
            <div class="nav-container">
                <a href="../pages/accueil.php" class="navbar-brand">Restaurant App</a>
                <ul class="navbar-menu">
                    <li><a href="../pages/accueil.php">Accueil</a></li>
                    <li><a href="../pages/restaurant.php">Restaurants</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <li><a href="../pages/ticket.php">Tickets</a></li>
                            <li><a href="../pages/monitoring.php">Admin</a></li>
                            <li><a href="../pages/Commandes.php">Monitoring</a></li>
                            <li><a href="../pages/mon_compte.php">Mon Compte</a></li>
                        <?php else: ?>
                            <li><a href="../pages/Commandes.php">Mes Réservations</a></li>
                        <?php endif; ?>
                        
                        <li><a href="../../controllers/deconnexion.php">Déconnexion</a></li>
                    <?php else: ?>
                        <li><a href="../pages/connexionV2.php">Connexion</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>
    
    <?php if (isset($_GET['message'])): ?>
        <div class="alert alert-<?= $_GET['type'] ?? 'info' ?>">
            <?= htmlspecialchars($_GET['message']) ?>
        </div>
    <?php endif; ?>