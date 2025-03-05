<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site en Maintenance</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="maintenance-container">
        <div class="icon">⚙️</div>
        <h1>En Maintenance</h1>
        <p>Nous mettons à jour notre site pour une meilleure expérience utilisateur. Merci de votre patience, nous serons bientôt de retour !</p>
        <a href="connexionV2.php">Retour à la page de Connexion</a>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
