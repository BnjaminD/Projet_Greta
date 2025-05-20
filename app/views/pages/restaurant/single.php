<main class="restaurant-detail">
    <h1><?= htmlspecialchars($restaurant->getName()) ?></h1>
    <div class="restaurant-content">
        <div class="restaurant-image">
            <img src="<?= htmlspecialchars($restaurant->getImagePath()) ?>" 
                 alt="<?= htmlspecialchars($restaurant->getName()) ?>" 
                 class="restaurant-detail-image">
        </div>
        <div class="restaurant-info">
            <p class="description"><?= htmlspecialchars($restaurant->getDescription()) ?></p>
            <div class="details">
                <p><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($restaurant->getAddress()) ?></p>
                <p><i class="fas fa-phone"></i> <?= htmlspecialchars($restaurant->getPhoneNumber()) ?></p>
                <p><i class="fas fa-clock"></i> <?= htmlspecialchars($restaurant->getOpeningHours()) ?></p>
                <p><i class="fas fa-users"></i> Capacité: <?= $restaurant->getCapacity() ?></p>
                <p><i class="fas fa-star"></i> Note: <?= $restaurant->getRating() ?>/5</p>
                <p><i class="fas fa-utensils"></i> Cuisine: <?= htmlspecialchars($restaurant->getCuisineType()) ?></p>
            </div>
            <div class="actions">
                <a href="/php/v1.02/Projet_Greta/app/views/pages/reservationV2.php?restaurant_id=<?= $restaurant->getId() ?>" class="btn btn-primary">Réserver</a>
                <a href="/php/v1.02/Projet_Greta/app/views/pages/menu.php?restaurant_id=<?= $restaurant->getId() ?>" 
                   class="btn btn-secondary">Voir le menu</a>
            </div>
        </div>
    </div>
</main>