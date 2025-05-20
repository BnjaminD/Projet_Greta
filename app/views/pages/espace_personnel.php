<?php
// Correction des chemins d'inclusion
require_once dirname(__DIR__, 2) . '/core/functions.php';
require_once dirname(__DIR__, 2) . '/core/Database.php';

// Importer les namespaces appropriés
use app\core\Database;

session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Rediriger vers la page de connexion si non connecté
    header("Location: ../../../index.php");
    exit();
}

// Obtenir une instance de la base de données
$db = Database::getInstance();

// Récupération directe de l'utilisateur depuis la base de données
$userId = $_SESSION['user_id'];
$sql = "SELECT u.*, r.role_name 
        FROM user u 
        LEFT JOIN user_roles r ON u.user_id = r.user_id 
        WHERE u.user_id = ?";
$currentUser = $db->fetchOne($sql, [$userId]);

// Récupération directe des données de restaurant via Database
$topRestaurants = $db->fetchAll("SELECT * FROM restaurant ORDER BY rating DESC LIMIT 6");
$cuisineTypes = $db->fetchAll("SELECT DISTINCT cuisine_type FROM restaurant ORDER BY cuisine_type");

// Extraire les types de cuisine à partir du résultat
$cuisineTypesArray = [];
foreach ($cuisineTypes as $row) {
    $cuisineTypesArray[] = $row['cuisine_type'];
}

// Inclure le header avec le chemin complet
include_once dirname(__DIR__) . '/includes/header.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue sur RestaurantApp</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="home">
    <!-- Suppression de l'inclusion redondante du header ici -->

    <div class="hero">
        <div class="hero-slideshow">
            <?php
            $heroImages = [
                'sushi_world.png', 
                'Taj_mahal.png', 
                'Fancy_fork.png'
            ];
            foreach($heroImages as $index => $image): 
                $imagePath = "images/restaurant/" . $image;
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
                <!-- Suppression du bouton de connexion -->
            </div>
        </div>
    </div>

    <section class="featured">
        <h2>Restaurants les Mieux Notés</h2>
        <div class="restaurant-highlights">
            <?php foreach ($topRestaurants as $restaurant): 
                // Nettoyer le chemin de l'image en retirant le slash initial
                $imagePath = ltrim($restaurant['image_url'], '/');
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
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const slides = document.querySelectorAll('.hero-slideshow .slide');
        const dots = document.querySelectorAll('.carousel-dot');
        const prevBtn = document.querySelector('.carousel-arrow.prev');
        const nextBtn = document.querySelector('.carousel-arrow.next');
        let currentSlide = 0;
        let slideInterval = null;
        
        function showSlide(index) {
            slides.forEach(slide => {
                slide.classList.remove('active');
            });
            dots.forEach(dot => dot.classList.remove('active'));
            
            slides[index].classList.add('active');
            dots[index].classList.add('active');
            
            currentSlide = index;
        }
        
        function nextSlide() {
            showSlide((currentSlide + 1) % slides.length);
        }
        
        function prevSlide() {
            showSlide((currentSlide - 1 + slides.length) % slides.length);
        }
        
        // Event listeners
        prevBtn.addEventListener('click', () => {
            clearInterval(slideInterval);
            prevSlide();
            startSlideshow();
        });
        
        nextBtn.addEventListener('click', () => {
            clearInterval(slideInterval);
            nextSlide();
            startSlideshow();
        });
        
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                clearInterval(slideInterval);
                showSlide(index);
                startSlideshow();
            });
        });
        
        function startSlideshow() {
            clearInterval(slideInterval);
            slideInterval = setInterval(nextSlide, 5000);
        }
        
        // Initialisation
        showSlide(0);
        startSlideshow();
        
        // Pause au survol
        const heroSection = document.querySelector('.hero');
        heroSection.addEventListener('mouseenter', () => clearInterval(slideInterval));
        heroSection.addEventListener('mouseleave', startSlideshow);
    });
    </script>
</body>
</html>
