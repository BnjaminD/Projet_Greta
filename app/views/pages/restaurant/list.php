<div class="restaurant-grid">
    <?php foreach($restaurants as $restaurant): ?>
        <article class="restaurant-card">
            <div class="restaurant-card__image">
                <img src="<?= htmlspecialchars($restaurant->getImagePath()) ?>" 
                     alt="<?= htmlspecialchars($restaurant->getName()) ?>">
            </div>
            <div class="restaurant-card__content">
                <h2><?= htmlspecialchars($restaurant->getName()) ?></h2>
                <p class="restaurant-card__description">
                    <?= htmlspecialchars($restaurant->getDescription()) ?>
                </p>
                <div class="restaurant-card__details">
                    <p><strong>Cuisine:</strong> <?= htmlspecialchars($restaurant->getCuisineType()) ?></p>
                    <p><strong>Note:</strong> <?= $restaurant->getRating() ?>/5</p>
                </div>
                <div class="restaurant-card__actions">
                    <a href="/php/v1.02/Projet_Greta/app/views/pages/menu.php?restaurant_id=<?= $restaurant->getId() ?>" 
                       class="btn btn-secondary">Voir menu</a>
                    <a href="/php/v1.02/Projet_Greta/app/views/pages/reservationV2.php?restaurant_id=<?= $restaurant->getId() ?>" 
                       class="btn btn-primary">Réserver</a>
                </div>
            </div>
        </article>
    <?php endforeach; ?>
</div>