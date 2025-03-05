-- Mettre à jour les chemins d'images des restaurants
UPDATE restaurant 
SET image_url = CASE 
    WHEN name = 'The Fancy Fork' THEN 'Fancy_fork.png'
    WHEN name = 'Pizza Paradise' THEN 'Pizza_paradise.png'
    WHEN name = 'Sushi World' THEN 'sushi_world.png'
    WHEN name = 'Le Bistrot Parisien' THEN 'bistrot_parisien.png'
    WHEN name = 'Taj Mahal' THEN 'Taj_mahal.png'
    WHEN name = 'El Tapas' THEN 'el_tapas.png'
END;

-- Mettre à jour les chemins d'images des plats
UPDATE dish 
SET image_url = CONCAT('dishes/', image_url)
WHERE image_url IS NOT NULL;

-- Mise à jour du chemin pour le Coq au Vin et correction de la casse pour le Boeuf Bourguignon
UPDATE dish 
SET image_url = CASE name
    WHEN 'Coq au Vin' THEN './images/dishes/Coq_au_vin.png'
    WHEN 'Boeuf Bourguignon' THEN './images/dishes/boeuf_bourguignon.png'
    WHEN 'Salade César' THEN './images/dishes/Salade_cesar.png'
    ELSE image_url
END
WHERE name IN ('Coq au Vin', 'Boeuf Bourguignon', 'Salade César');