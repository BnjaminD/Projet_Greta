<?php
// Correction des chemins d'inclusion
require_once dirname(__DIR__, 2) . '/core/functions.php';
require_once dirname(__DIR__, 2) . '/core/Database.php';

// Importer les namespaces appropriés
use app\core\Database;

session_start();

// Permettre aux utilisateurs non connectés de voir la page d'accueil
// Aucune redirection n'est nécessaire

// Obtenir une instance de la base de données
$db = Database::getInstance();

// Récupération de l'utilisateur uniquement s'il est connecté
$currentUser = null;
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $sql = "SELECT user.*, user_roles.role_name 
            FROM user 
            LEFT JOIN user_roles ON user_roles.user_id = user.user_id 
            WHERE user.user_id = ?";
    $currentUser = $db->fetchOne($sql, [$userId]);
}

// Récupération directe des données de restaurant via Database
$topRestaurants = $db->fetchAll("SELECT * FROM restaurant ORDER BY rating DESC LIMIT 6");
$cuisineTypes = $db->fetchAll("SELECT DISTINCT cuisine_type FROM restaurant ORDER BY cuisine_type");

// Extraire les types de cuisine à partir du résultat
$cuisineTypesArray = [];
foreach ($cuisineTypes as $row) {
    $cuisineTypesArray[] = $row['cuisine_type'];
}

// Ajouter après les autres déclarations au début du fichier
$baseUrl = '/php/v1.02/Projet_Greta/app/assets';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Réservation de Restaurants</title>
    <link rel="stylesheet" href="/php/v1.02/Projet_Greta/app/assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body class="home-page">

<?php include_once dirname(__DIR__) . '/includes/header.php'; ?>

<div class="hero">
    <div class="hero-slideshow">
        <?php
        $heroImages = [
            'sushi_world.png', 
            'Taj_mahal.png', 
            'Fancy_fork.png'
        ];
        foreach($heroImages as $index => $image): 
            // Utiliser le chemin absolu avec $baseUrl
            $imagePath = $baseUrl . "/images/restaurant/" . $image;
            $isActive = $index === 0 ? 'active' : '';
        ?>
            <div class="slide <?= $isActive ?>" 
                 style="background-image: url('<?= $imagePath ?>')">
            </div>
        <?php endforeach; ?>
    </div>
    
    <div class="carousel-arrows">
        <div class="carousel-arrow prev">
            <i class="fas fa-chevron-left"></i>
        </div>
        <div class="carousel-arrow next">
            <i class="fas fa-chevron-right"></i>
        </div>
    </div>
    
    <div class="carousel-controls">
        <?php foreach($heroImages as $index => $image): ?>
            <div class="carousel-dot <?= $index === 0 ? 'active' : '' ?>"></div>
        <?php endforeach; ?>
    </div>

    <div class="hero-content">
        <h1>Découvrez les Meilleurs Restaurants</h1>
        <p>Réservez une table dans les restaurants les plus appréciés</p>
        <div class="hero-buttons">
            <a href="restaurant.php" class="btn-primary">Explorer les Restaurants</a>
        </div>
    </div>
</div>

<section class="featured">
    <h2>Restaurants les Mieux Notés</h2>
    <div class="restaurant-highlights">
        <?php foreach ($topRestaurants as $restaurant): 
            // Convertir le chemin relatif en chemin absolu
            $imagePath = $restaurant['image_url'];
            if (strpos($imagePath, '/') !== 0 && strpos($imagePath, 'http') !== 0) {
                $imagePath = $baseUrl . '/images/restaurant/' . $imagePath;
            }
        ?>
            <div class="highlight-card">
                <div class="card-image" 
                     style="background-image: url('<?= $imagePath ?>');">
                </div>
                <div class="card-content">
                    <h3><?= htmlspecialchars($restaurant['name']) ?></h3>
                    <p class="rating">
                        <i class="fas fa-star"></i> 
                        <?= number_format($restaurant['rating'], 1) ?>
                    </p>
                    <p><?= htmlspecialchars($restaurant['cuisine_type']) ?></p>
                    <a href="reservationV2.php?restaurant_id=<?= $restaurant['restaurant_id'] ?>" 
                       class="btn-book">Réserver</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="cuisine-types">
    <h2>Explorez par Type de Cuisine</h2>
    <div class="cuisine-grid">
        <?php foreach ($cuisineTypesArray as $cuisine): ?>
            <a href="restaurant.php?cuisine=<?= urlencode($cuisine) ?>" class="cuisine-card">
                <i class="fas fa-utensils"></i>
                <h3><?= htmlspecialchars($cuisine) ?></h3>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<section class="features">
    <div class="feature">
        <i class="fas fa-clock"></i>
        <h3>Réservation Rapide</h3>
        <p>Réservez une table en quelques clics</p>
    </div>
    <div class="feature">
        <i class="fas fa-star"></i>
        <h3>Restaurants de Qualité</h3>
        <p>Sélectionnés pour leur excellence</p>
    </div>
    <div class="feature">
        <i class="fas fa-heart"></i>
        <h3>Avis Vérifiés</h3>
        <p>Par notre communauté de clients</p>
    </div>
</section>

<?php include_once dirname(__DIR__) . '/includes/footer.php'; ?>

<script src="/php/v1.02/Projet_Greta/app/assets/js/slideshow.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Vérifie si les images du slideshow sont chargées correctement
    const slides = document.querySelectorAll('.hero-slideshow .slide');
    slides.forEach((slide, index) => {
        const bgImage = slide.style.backgroundImage;
        console.log(`Slide ${index}: ${bgImage}`);
        
        // Crée un élément d'image pour tester si l'URL est valide
        const img = new Image();
        img.onload = () => console.log(`Image ${index} chargée avec succès`);
        img.onerror = () => console.error(`Erreur de chargement de l'image ${index}`);
        
        // Extrait l'URL du style background-image
        const url = bgImage.replace(/url\(['"]?([^'"]+)['"]?\)/, '$1');
        img.src = url;
    });
});
</script>
</body>
</html>