<?php
// Démarrer la session et inclure les fichiers nécessaires avec les bons chemins
session_start();
require_once dirname(__DIR__, 2) . '/core/Database.php';
require_once dirname(__DIR__, 2) . '/core/functions.php';

use app\core\Database;

// Définir une fonction locale qui fait appel à la fonction du namespace
function getImagePath($category, $name, $dbImagePath = null) {
    return \app\core\getImagePath($category, $name, $dbImagePath);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Menu</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body class="menu-page">
    <?php
    // Inclure le header avec le chemin correct
    require_once dirname(__DIR__) . '/includes/header.php';
    
    // Tableau d'association des images pour les plats avec les bons chemins
    // Mise à jour des chemins pour pointer vers le bon répertoire
    $dish_images = [
        'Steak au Poivre' => '/app/assets/images/dishes/Steak.png',
        'Coq au Vin' => '/app/assets/images/dishes/Coq_au_vin.png',
        'Tarte Tatin' => '/app/assets/images/dishes/Tarte_tatin.png',
        'Salade César' => '/app/assets/images/dishes/Salade_cesar.png',
        'Boeuf Bourguignon' => '/app/assets/images/dishes/boeuf_bourguignon.png',
        'Cordon Bleu' => '/app/assets/images/dishes/Cordon_bleu.png',
        'Saumon en Papillotte' => '/app/assets/images/dishes/Saumon_papillotte.png',
        'Margherita Pizza' => '/app/assets/images/dishes/pizza.png',
        'Pizza Quattro Formaggi' => '/app/assets/images/dishes/pizza2.png',
        'Pizza Pepperoni' => '/app/assets/images/dishes/pizza3.png',
        'Pâtes Carbonara' => '/app/assets/images/dishes/pasta.png',
        'Lasagne Bolognaise' => '/app/assets/images/dishes/pasta1.png',
        'Tiramisu' => '/app/assets/images/dishes/Tiramisu.png',
        'Sushi Mix' => '/app/assets/images/dishes/sushi.png',
        'California Roll' => '/app/assets/images/dishes/sushi1.png',
        'Sashimi Saumon' => '/app/assets/images/dishes/sushi2.png',
        'Tempura Crevettes' => '/app/assets/images/dishes/Tempura_Crevettes.png',
        'Ramen Miso' => '/app/assets/images/dishes/Ramen_Miso.png',
        'Mochi Glacé' => '/app/assets/images/dishes/Mochi_Glace.png',
        'default' => '/app/assets/images/dishes/default_dish.png'
    ];

    // Récupérer l'ID du restaurant depuis l'URL
    $restaurant_id = isset($_GET['restaurant_id']) ? intval($_GET['restaurant_id']) : 0;

    if ($restaurant_id <= 0) {
        echo "<div class='container'><p>Restaurant non spécifié.</p></div>";
        include dirname(__DIR__) . '/includes/footer.php';
        exit;
    }

    // Obtenir l'instance de PDO
    $db = Database::getInstance();
    $pdo = $db->getPdo();

    // Récupérer les informations du restaurant avec PDO
    $restaurant_query = "SELECT name FROM restaurant WHERE restaurant_id = ?";
    $stmt = $pdo->prepare($restaurant_query);
    $stmt->execute([$restaurant_id]);
    $restaurant = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$restaurant) {
        echo "<div class='container'><p>Restaurant non trouvé.</p></div>";
        include dirname(__DIR__) . '/includes/footer.php';
        exit;
    }

    // Récupérer les menus et leurs plats avec PDO
    $menu_query = "
        SELECT m.menu_id, m.name as menu_name, m.description as menu_description,
               d.dish_id, d.name as dish_name, d.description as dish_description,
               d.price, d.category, d.image_url
        FROM menu m
        LEFT JOIN dish d ON m.menu_id = d.menu_id
        WHERE m.restaurant_id = ? AND m.is_active = 1
        ORDER BY m.menu_id, d.category, d.name";

    $stmt = $pdo->prepare($menu_query);
    $stmt->execute([$restaurant_id]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $menus = [];
    foreach ($result as $row) {
        $menu_id = $row['menu_id'];
        if (!isset($menus[$menu_id])) {
            $menus[$menu_id] = [
                'name' => $row['menu_name'],
                'description' => $row['menu_description'],
                'dishes' => []
            ];
        }
        if ($row['dish_id']) {
            $menus[$menu_id]['dishes'][] = [
                'name' => $row['dish_name'],
                'description' => $row['dish_description'],
                'price' => $row['price'],
                'category' => $row['category'],
                'image_url' => $row['image_url'],
                'dish_id' => $row['dish_id']
            ];
        }
    }
    ?>

    <div class="container">
        <h1><?= htmlspecialchars($restaurant['name']) ?> - Menus</h1>

        <?php if (empty($menus)): ?>
            <p>Aucun menu disponible pour ce restaurant.</p>
        <?php else: ?>
            <?php foreach ($menus as $menu): ?>
                <div class="menu-section">
                    <h2><?= htmlspecialchars($menu['name']) ?></h2>
                    <?php if ($menu['description']): ?>
                        <p class="menu-description"><?= htmlspecialchars($menu['description']) ?></p>
                    <?php endif; ?>

                    <?php
                    // Grouper les plats par catégorie
                    $dishes_by_category = [];
                    foreach ($menu['dishes'] as $dish) {
                        $category = $dish['category'];
                        if (!isset($dishes_by_category[$category])) {
                            $dishes_by_category[$category] = [];
                        }
                        $dishes_by_category[$category][] = $dish;
                    }
                    ?>

                    <?php foreach ($dishes_by_category as $category => $dishes): ?>
                        <div class="category-section">
                            <h3><?= htmlspecialchars($category) ?></h3>
                            <div class="dishes-grid">
                                <?php foreach ($dishes as $dish): ?>
                                    <a href="ingredients.php?dish_id=<?= $dish['dish_id'] ?>" class="dish-card">
                                        <?php 
                                            // Utiliser la fonction utilitaire avec un try/catch pour éviter les erreurs
                                            try {
                                                $imageUrl = \app\core\getImagePath('dishes', $dish['name'], $dish['image_url']);
                                            } catch (Exception $e) {
                                                error_log("Erreur dans getImagePath: " . $e->getMessage());
                                                $imageUrl = '/app/assets/images/dishes/default_dish.png';
                                            }
                                            
                                            // Vérifier que l'URL n'est pas vide
                                            if (empty($imageUrl)) {
                                                $imageUrl = '/app/assets/images/dishes/default_dish.png';
                                            }
                                        ?>
                                        <img src="<?= htmlspecialchars($imageUrl) ?>" 
                                            alt="<?= htmlspecialchars($dish['name']) ?>" 
                                            class="dish-image"
                                            onerror="this.src='/app/assets/images/dishes/default_dish.png'">
                                            
                                        <div class="dish-info">
                                            <h4><?= htmlspecialchars($dish['name']) ?></h4>
                                            <p class="dish-description"><?= htmlspecialchars($dish['description']) ?></p>
                                            <p class="dish-price"><?= number_format($dish['price'], 2) ?> €</p>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- Bouton de réservation -->
        <?php if (!empty($menus) && isset($_SESSION['user_id'])): ?>
            <div class="reservation-button-container">
                <a href="reservationV2.php?restaurant_id=<?= $restaurant_id ?>" class="btn reservation-button">
                    Réserver une table
                </a>
            </div>
        <?php elseif (!empty($menus) && !isset($_SESSION['user_id'])): ?>
            <div class="reservation-button-container">
                <a href="connexionV2.php?redirect=reservationV2.php?restaurant_id=<?= $restaurant_id ?>" class="btn reservation-button">
                    Connectez-vous pour réserver
                </a>
            </div>
        <?php endif; ?>
    </div>

    <?php
    // Inclure le footer avec le chemin correct
    require_once dirname(__DIR__) . '/includes/footer.php';
    ?>

    <script>
        // Script pour l'affichage des détails des plats au survol
        document.querySelectorAll('.dish-card').forEach(function(card) {
            card.addEventListener('mouseenter', function() {
                this.querySelector('.dish-description').style.display = 'block';
            });
            
            card.addEventListener('mouseleave', function() {
                this.querySelector('.dish-description').style.display = 'none';
            });
        });

        // Script pour le bouton de retour en haut de page
        window.onscroll = function() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                document.getElementById('back-to-top').style.display = 'block';
            } else {
                document.getElementById('back-to-top').style.display = 'none';
            }
        };
    </script>

    <!-- Bouton de retour en haut de page -->
    <a id="back-to-top" href="#" class="back-to-top" style="display: none;">
        <i class="fa fa-arrow-up"></i>
    </a>
</body>
</html>