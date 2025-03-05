<?php
session_start();

// Add path definitions
define('ROOT_PATH', dirname(__FILE__, 4));  // Remonte de 4 niveaux pour atteindre la racine
define('APP_PATH', ROOT_PATH . '/app');
define('VIEWS_PATH', APP_PATH . '/views');

if (!isset($_SESSION['user_id']) && isset($_GET['showLogin'])) {
    header('Location: connexionV2.php?return=' . urlencode($_SERVER['PHP_SELF']));
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - RestaurantApp</title>
    <link rel="stylesheet" href="/php/New/app/assets/css/styles.css">
    <link rel="stylesheet" href="/php/New/app/assets/css/header.css">
    <link rel="stylesheet" href="/php/New/app/assets/css/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="contact">
    <div class="page-wrapper">
        <?php include VIEWS_PATH . '/includes/header.php'; ?>
        
        <main class="main-content">
            <div class="contact-container">
                <h1>Contactez-nous</h1>
                
                <div class="contact-info">
                    <div class="info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <h3>Adresse</h3>
                        <p>123 Rue Example<br>75000 Paris, France</p>
                    </div>
                    
                    <div class="info-item">
                        <i class="fas fa-phone"></i>
                        <h3>Téléphone</h3>
                        <p>+33 1 23 45 67 89</p>
                    </div>
                    
                    <div class="info-item">
                        <i class="fas fa-envelope"></i>
                        <h3>Email</h3>
                        <p>contact@votresite.com</p>
                    </div>
                </div>

                <?php if (isset($message)): ?>
                    <div class="<?php echo $success ? 'success-message' : 'error-message'; ?>">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>

                <form class="contact-form" action="traitement-contact.php" method="POST">
                    <div class="form-group">
                        <label for="nom">Nom</label>
                        <input type="text" id="nom" name="nom" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="sujet">Sujet</label>
                        <input type="text" id="sujet" name="sujet" required>
                    </div>

                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" required></textarea>
                    </div>

                    <button type="submit">Envoyer le message</button>
                </form>
            </div>
        </main>

        <?php include VIEWS_PATH . '/includes/footer.php'; ?>
    </div>
</body>
</html>