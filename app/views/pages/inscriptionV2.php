<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('config.php');

use function app\core\{inscriptionUtilisateur};
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $test = new PDO($dsn, DB_USER, DB_PASS);
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
    die();
}

require_once ('database.php');
require_once ('functions.php');
require_once APP_PATH . '/core/UserManager.php';
require_once APP_PATH . '/models/User.php';

function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = isset($_POST['username']) ? sanitize($_POST['username']) : '';
    $email = isset($_POST['email']) ? sanitize($_POST['email']) : '';
    $mot_de_passe = isset($_POST['mot_de_passe']) ? $_POST['mot_de_passe'] : '';
    $confirmation_mdp = isset($_POST['confirmation_mdp']) ? $_POST['confirmation_mdp'] : '';

    if ($username && $email && $mot_de_passe && $confirmation_mdp) {
        if ($mot_de_passe !== $confirmation_mdp) {
            $message = "Les mots de passe ne correspondent pas.";
        } elseif (strlen($mot_de_passe) < 8) {
            $message = "Le mot de passe doit contenir au moins 8 caractères.";
        } else {
            $userManager = new \app\core\UserManager($pdo);
            if ($userManager->inscriptionUtilisateur($username, $email, $mot_de_passe)) {
                header("Location: connexionV2.php?inscription=success");
                exit();
            } else {
                $message = "Une erreur s'est produite lors de l'inscription.";
            }
        }
    } else {
        $message = "Tous les champs sont obligatoires.";
    }
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
    <title>Inscription</title>
</head>
<?php require_once __DIR__ . '/../includes/header.php'; ?>
<body class="inscriptionV2">
    <div class="container">
        <div class="form-container">
            <h1>Inscription</h1>
            <?php if ($message): ?>
                <div class="message"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>
            <form method="POST">
                <input type="text" name="username" placeholder="Pseudo" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>
                <input type="password" name="confirmation_mdp" placeholder="Confirmer le mot de passe" required>
                <input type="submit" value="S'inscrire">
            </form>
            <p>Déjà un compte ? <a href="connexionV2.php">Connectez-vous</a></p>
        </div>
    </div>
</body>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
</html>
