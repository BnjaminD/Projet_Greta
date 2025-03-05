<?php
require_once 'config.php';
require_once 'functions.php';
require_once 'database.php';

// Vérification de la session admin
session_start();
if (!isAdmin()) {
    header('Location: connexionV2.php');
    exit();
}

try {
    $pdo = Database::getInstance()->getConnection();

    // Requête pour récupérer l'historique des activités avec les détails utilisateur
    $query = "SELECT 
                ual.*,
                u.username,
                u.email,
                u.is_active,
                COALESCE(ur.role_name, 'user') as role_name
              FROM user_activity_log ual
              LEFT JOIN user u ON ual.user_id = u.user_id
              LEFT JOIN user_roles ur ON u.user_id = ur.user_id
              ORDER BY ual.performed_at DESC
              LIMIT 100"; // Limitez à 100 entrées pour la performance
              
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Ajout d'un log pour le débogage
    if (count($activities) === 0) {
        error_log("Aucune activité trouvée dans la base de données");
    }

} catch(PDOException $e) {
    error_log("Erreur de monitoring : " . $e->getMessage());
    die("Une erreur est survenue. Veuillez réessayer plus tard.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring des Activités Utilisateurs</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="admin-monitoring">
    <?php include 'header.php'; ?>
    
    <main class="container">
        <h1>Historique des Activités Utilisateurs</h1>
        
        <div class="tickets-filters">
            <button class="filter-btn active" data-filter="all">Tous</button>
            <button class="filter-btn" data-filter="login">Connexions</button>
            <button class="filter-btn" data-filter="reservation">Réservations</button>
            <button class="filter-btn" data-filter="profile">Profils</button>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Utilisateur</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Action</th>
                    <th>Détails</th>
                    <th>Statut</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($activities as $activity): ?>
                    <tr class="activity-row" data-type="<?php echo strtolower($activity['action']); ?>">
                        <td><?php echo sanitize($activity['activity_id']); ?></td>
                        <td><?php echo sanitize($activity['username']); ?></td>
                        <td><?php echo sanitize($activity['email']); ?></td>
                        <td><?php echo sanitize($activity['role_name']); ?></td>
                        <td>
                            <span class="status-badge status-<?php echo strtolower($activity['action']); ?>">
                                <?php echo sanitize($activity['action']); ?>
                            </span>
                        </td>
                        <td><?php echo sanitize($activity['details']); ?></td>
                        <td>
                            <span class="status-badge <?php echo $activity['is_active'] ? 'status-active' : 'status-inactive'; ?>">
                                <?php echo $activity['is_active'] ? 'Actif' : 'Inactif'; ?>
                            </span>
                        </td>
                        <td><?php echo date('d/m/Y H:i:s', strtotime($activity['performed_at'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
    
    <?php include 'footer.php'; ?>
    
    <script>
        // Filtrage des activités
        document.querySelectorAll('.filter-btn').forEach(button => {
            button.addEventListener('click', () => {
                const filter = button.dataset.filter;
                
                // Mise à jour des boutons actifs
                document.querySelectorAll('.filter-btn').forEach(btn => {
                    btn.classList.remove('active');
                });
                button.classList.add('active');
                
                // Filtrage des lignes
                document.querySelectorAll('.activity-row').forEach(row => {
                    if (filter === 'all' || row.dataset.type === filter) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    </script>
</body>
</html>
