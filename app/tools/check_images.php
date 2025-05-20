
<?php
// Script de diagnostic pour vérifier les chemins d'images
session_start();
require_once dirname(__DIR__) . '/core/Database.php';
require_once dirname(__DIR__) . '/core/functions.php';

use app\core\Database;

// Vérifier les permissions d'admin
if (!isset($_SESSION['user_id']) || !\app\core\isAdmin($_SESSION['user_id'])) {
    die("Accès restreint aux administrateurs.");
}

// Fonctions utilitaires
function checkPath($path) {
    $docRoot = $_SERVER['DOCUMENT_ROOT'];
    $fullPath = $docRoot . $path;
    
    if (file_exists($fullPath)) {
        return "<span style='color:green'>✓ OK - $path</span>";
    } else {
        return "<span style='color:red'>✗ NON TROUVÉ - $path</span>";
    }
}

// Header HTML
echo "<!DOCTYPE html><html><head><title>Vérificateur d'images</title>";
echo "<style>body{font-family:Arial; margin:20px;} h1{color:#333;} .path{background:#f9f9f9; padding:5px; margin:5px 0;}</style>";
echo "</head><body>";
echo "<h1>Vérification des chemins d'images</h1>";

// Vérifier les répertoires d'images principaux
$directories = [
    '/app/assets/images/',
    '/app/assets/images/dishes/',
    '/app/assets/images/profile/',
    '/app/assets/images/restaurant/'
];

echo "<h2>Répertoires d'images</h2>";
foreach ($directories as $dir) {
    echo "<div class='path'>" . checkPath($dir) . "</div>";
}

// Vérifier les images par défaut
$defaultImages = [
    '/app/assets/images/dishes/default_dish.png',
    '/app/assets/images/profile/default_profile.png',
    '/app/assets/images/restaurant/default_restaurant.png',
    '/app/assets/images/default.png'
];

echo "<h2>Images par défaut</h2>";
foreach ($defaultImages as $img) {
    echo "<div class='path'>" . checkPath($img) . "</div>";
}

// Vérifier les images de plats dans la base de données
$db = Database::getInstance();
$pdo = $db->getPdo();

echo "<h2>Images de plats dans la base de données</h2>";
$stmt = $pdo->query("SELECT name, image_url FROM dish WHERE image_url IS NOT NULL LIMIT 20");
$dishes = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($dishes) > 0) {
    foreach ($dishes as $dish) {
        $imgPath = $dish['image_url'];
        if (!empty($imgPath)) {
            echo "<div class='path'>Plat: " . htmlspecialchars($dish['name']) . " - " . checkPath($imgPath) . "</div>";
        }
    }
} else {
    echo "<div>Aucune image de plat trouvée dans la base de données.</div>";
}

// Vérifier les images selon le tableau dish_images
echo "<h2>Images de plats du tableau statique</h2>";
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
    'Mochi Glacé' => '/app/assets/images/dishes/Mochi_Glace.png'
];

foreach ($dish_images as $dish => $path) {
    echo "<div class='path'>Plat: " . htmlspecialchars($dish) . " - " . checkPath($path) . "</div>";
}

// Lien pour créer