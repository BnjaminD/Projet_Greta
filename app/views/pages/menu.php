<?php
include 'session.php';
include 'db_connection.php';
include 'functions.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Menu</title>
    <link rel="stylesheet" href="./css/styles.css">
</head>
<body class="menu-page">
    <?php
    include 'header.php';
    
    // Tableau d'association des images pour les plats avec les bons chemins
    $dish_images = [
        'Steak au Poivre' => '/Projet_Greta/app/assets/img/dishes/Steak.png',
        'Coq au Vin' => '/Projet_Greta/app/assets/img/dishes/Coq_au_vin.png',
        'Tarte Tatin' => '/Projet_Greta/app/assets/img/dishes/Tarte_tatin.png',
        'Salade César' => '/Projet_Greta/app/assets/img/dishes/Salade_cesar.png',
        'Boeuf Bourguignon' => '/Projet_Greta/app/assets/img/dishes/boeuf_bourguignon.png',
        'Cordon Bleu' => '/Projet_Greta/app/assets/img/dishes/Cordon_bleu.png',
        'Saumon en Papillotte' => '/Projet_Greta/app/assets/img/dishes/Saumon_papillotte.png',
        'Margherita Pizza' => '/Projet_Greta/app/assets/img/dishes/pizza.png',
        'Pizza Quattro Formaggi' => '/Projet_Greta/app/assets/img/dishes/pizza2.png',
        'Pizza Pepperoni' => '/Projet_Greta/app/assets/img/dishes/pizza3.png',
        'Pâtes Carbonara' => '/Projet_Greta/app/assets/img/dishes/pasta.png',
        'Lasagne Bolognaise' => '/Projet_Greta/app/assets/img/dishes/pasta1.png',
        'Tiramisu' => '/Projet_Greta/app/assets/img/dishes/Tiramisu.png',
        'Sushi Mix' => '/Projet_Greta/app/assets/img/dishes/sushi.png',
        'California Roll' => '/Projet_Greta/app/assets/img/dishes/sushi1.png',
        'Sashimi Saumon' => '/Projet_Greta/app/assets/img/dishes/sushi2.png',
        'Tempura Crevettes' => '/Projet_Greta/app/assets/img/dishes/Tempura_Crevettes.png',
        'Ramen Miso' => '/Projet_Greta/app/assets/img/dishes/Ramen_Miso.png',
        'Mochi Glacé' => '/Projet_Greta/app/assets/img/dishes/Mochi_Glace.png',
        'default' => '/Projet_Greta/app/assets/img/dishes/default_dish.png'
    ];

    // Récupérer l'ID du restaurant depuis l'URL
    $restaurant_id = isset($_GET['restaurant_id']) ? intval($_GET['restaurant_id']) : 0;

    if ($restaurant_id <= 0) {
        echo "<div class='container'><p>Restaurant non spécifié.</p></div>";
        include 'footer.php';
        exit;
    }

    // Récupérer les informations du restaupng
    $restaurant_query = "SELECT name FROM restaurant WHERE restaurant_id = ?";
    $stmt = $conn->prepare($restaurant_query);
    $stmt->bind_param("i", $restaurant_id);
    $stmt->execute();
    $restaurant_result = $stmt->get_result();
    $restaurant = $restaurant_result->fetch_assoc();

    if (!$restaurant) {
        echo "<div class='container'><p>Restaurant non trouvé.</p></div>";
        include 'footer.php';
        exit;
    }

    // Récupérer les menus et leurs plats
    $menu_query = "
        SELECT m.menu_id, m.name as menu_name, m.description as menu_description,
               d.dish_id, d.name as dish_name, d.description as dish_description,
               d.price, d.category, d.image_url
        FROM menu m
        LEFT JOIN dish d ON m.menu_id = d.menu_id
        WHERE m.restaurant_id = ? AND m.is_active = 1
        ORDER BY m.menu_id, d.category, d.name";

    $stmt = $conn->prepare($menu_query);
    $stmt->bind_param("i", $restaurant_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $menus = [];
    while ($row = $result->fetch_assoc()) {
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
                'dish_id' => $row['dish_id']  // Ajout de dish_id
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
                                            // Logique améliorée pour la sélection d'image
                                            $imageUrl = $dish_images['default']; // Image par défaut
                                            if (isset($dish_images[$dish['name']])) {
                                                $fullPath = $_SERVER['DOCUMENT_ROOT'] . $dish_images[$dish['name']];
                                                if (file_exists($fullPath)) {
                                                    $imageUrl = $dish_images[$dish['name']];
                                                }
                                            }
                                        ?>
                                        <img src="<?= htmlspecialchars($imageUrl) ?>" 
                                            alt="<?= htmlspecialchars($dish['name']) ?>" 
                                            class="dish-image"
                                            onerror="this.src='<?= htmlspecialchars($dish_images['default']) ?>'">
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
    </div>

    <?php
    $conn->close();
    include 'footer.php';
    ?>
</body>
</html>