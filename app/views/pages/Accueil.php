<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <h1>Bienvenue sur Notre Site</h1>
        <nav>
            <a href="connexion.php">Connexion</a>
            <a href="inscription.php">Inscription</a>
            <a href="#carousel">Carrousel</a>
        </nav>
    </header>
    <div class="container">
        <h2>Découvrez Nos Fonctionnalités</h2>
        <div id="carousel" class="carousel">
            <div class="carousel-images">
                <img src="/php/img/caroussel 1.webp" alt="Slide 1">
                <img src="/php/img/caroussel 2.jpg" alt="Slide 2">
                <img src="/php/img/caroussel 3.webp" alt="Slide 3">
            </div>
            <div class="carousel-buttons">
                <button id="prev-btn">&lt;</button>
                <button id="next-btn">&gt;</button>
            </div>
        </div>
    </div> <a href="deconnexion.php" class="btn btn-deconnexion">Déconnexion</a>
    <script>
        // JavaScript pour gérer le carrousel
        const images = document.querySelector('.carousel-images');
        const totalImages = images.children.length;
        let currentIndex = 0;

        document.getElementById('next-btn').addEventListener('click', () => {
            currentIndex = (currentIndex + 1) % totalImages;
            updateCarousel();
        });

        document.getElementById('prev-btn').addEventListener('click', () => {
            currentIndex = (currentIndex - 1 + totalImages) % totalImages;
            updateCarousel();
        });

        function updateCarousel() {
            images.style.transform = `translateX(-${currentIndex * 100}%)`;
        }
    </script>
</body>
</html>
