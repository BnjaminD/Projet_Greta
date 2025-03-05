<?php
include 'session.php';
include 'database.php';
include 'functions.php';

$dish_id = isset($_GET['dish_id']) ? intval($_GET['dish_id']) : 0;

if ($dish_id <= 0) {
    header('Location: menu.php');
    exit;
}

try {
    $pdo = Database::getInstance()->getConnection();
    
    // Requête modifiée pour inclure la quantité et correspondre à la structure exacte
    $query = "
        SELECT 
            d.name as dish_name, 
            d.description as dish_description,
            i.name as ingredient_name, 
            i.allergen,
            di.quantity
        FROM dish d
        LEFT JOIN dish_ingredient di ON d.dish_id = di.dish_id
        LEFT JOIN ingredient_catalog i ON di.ingredient_id = i.ingredient_id
        WHERE d.dish_id = ?";

    $stmt = $pdo->prepare($query);
    $stmt->execute([$dish_id]);
    
    $dish_info = null;
    $ingredients = [];
    $allergens = [];

    while ($row = $stmt->fetch()) {
        if (!$dish_info) {
            $dish_info = [
                'name' => $row['dish_name'],
                'description' => $row['dish_description']
            ];
        }
        if ($row['ingredient_name']) {
            $ingredients[] = [
                'name' => $row['ingredient_name'],
                'quantity' => $row['quantity'],
                'allergen' => $row['allergen']
            ];
            if ($row['allergen']) {
                $allergens[] = $row['ingredient_name'];
            }
        }
    }
} catch (PDOException $e) {
    error_log("Erreur dans ingredients.php: " . $e->getMessage());
    echo "<p>Une erreur est survenue lors de la récupération des ingrédients.</p>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ingrédients - <?= htmlspecialchars($dish_info['name'] ?? 'Plat inconnu') ?></title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .ingredients-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            color: #333; /* Ajout de la couleur de texte par défaut */
        }
        h1, h2, h3 {
            color: #333; /* Couleur pour tous les titres */
            margin-bottom: 15px;
        }
        .allergen-warning {
            background-color: #fff3cd;
            color: #856404;
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
        }
        .ingredients-list {
            list-style-type: none;
            padding: 0;
        }
        .ingredients-list li {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .allergen {
            color: #dc3545;
            font-weight: bold;
        }
        .back-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container ingredients-container">
        <?php if ($dish_info): ?>
            <h1><?= htmlspecialchars($dish_info['name']) ?></h1>
            <p><?= htmlspecialchars($dish_info['description']) ?></p>

            <?php if (!empty($allergens)): ?>
                <div class="allergen-warning">
                    <h3>⚠️ Allergènes présents :</h3>
                    <ul class="ingredients-list">
                        <?php foreach ($allergens as $allergen): ?>
                            <li class="allergen"><?= htmlspecialchars($allergen) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <h2>Liste des ingrédients :</h2>
            <?php if (!empty($ingredients)): ?>
                <ul class="ingredients-list">
                    <?php foreach ($ingredients as $ingredient): ?>
                        <li<?= $ingredient['allergen'] ? ' class="allergen"' : '' ?>>
                            <?= htmlspecialchars($ingredient['name']) ?>
                            <?php if ($ingredient['quantity']): ?>
                                (<?= htmlspecialchars($ingredient['quantity']) ?>)
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Aucun ingrédient listé pour ce plat.</p>
            <?php endif; ?>

            <a href="javascript:history.back()" class="back-button">Retour au menu</a>
        <?php else: ?>
            <p>Plat non trouvé.</p>
            <a href="menu.php" class="back-button">Retour au menu</a>
        <?php endif; ?>
    </div>

    <?php 
    $pdo = null;
    include 'footer.php'; 
    ?>
</body>
</html>