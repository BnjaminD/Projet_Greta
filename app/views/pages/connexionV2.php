<?php
session_start();

require_once __DIR__ . '/../../core/Database.php';
require_once __DIR__ . '/../../core/functions.php';
require_once __DIR__ . '/../../core/config.php';

use app\core\Database;
use app\core\Functions;
use function app\core\{sanitize, connexionUtilisateur};

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['username'], $_POST['mot_de_passe'])) {
        $username = sanitize($_POST['username']);
        $mot_de_passe = $_POST['mot_de_passe'];
        
        error_log("DEBUG: Tentative de connexion - Username: " . $username);
        
        $user = connexionUtilisateur($username, $mot_de_passe);
        
        if ($user && isset($user['id'])) {
            error_log("DEBUG: Connexion réussie - ID: " . $user['id']);
            
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['logged_in'] = true;
            $_SESSION['role'] = $user['role'];
            
            error_log("DEBUG: Session initialisée: " . print_r($_SESSION, true));
            
            // Redirection selon le rôle
            if ($user['role'] === 'admin') {
                header("Location: Commandes.php");
            } else {
                header("Location: espace_personnel.php");
            }
            exit();
        } else {
            $message = "Identifiants incorrects ou compte inactif";
        }
    }
}

// Afficher message de succès après inscription
if (isset($_GET['inscription']) && $_GET['inscription'] === 'success') {
    $message = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/php/New/app/assets/css/styles.css">
    <link rel="stylesheet" href="/php/New/app/assets/css/header.css">
    <link rel="stylesheet" href="/php/New/app/assets/css/footer.css">
    <title>Connexion</title>
</head>
<body class="connexionV2">
    <?php require_once __DIR__ . '/../includes/header.php'; ?>
    <div class="container">
        <div class="form-container">
            <h1>Connexion</h1>
            <?php if ($message): ?>
                <div class="message"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            <form method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>
                <input type="submit" value="Se connecter">
            </form>
            <p>Pas de compte ? <a href="inscriptionV2.php">Inscrivez-vous</a></p>
        </div>
    </div>
    <?php require_once __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
