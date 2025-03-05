
-- Mise à jour des chemins d'images pour les restaurants
UPDATE restaurant 
SET image_url = CASE name
    WHEN 'The Fancy Fork' THEN '/images/restaurant/fancy_fork.png'
    WHEN 'Pizza Paradise' THEN '/images/restaurant/pizza_paradise.png'
    WHEN 'Sushi World' THEN '/images/restaurant/sushi_world.png'
    WHEN 'Le Bistrot Parisien' THEN '/images/restaurant/bistrot_parisien.png'
    WHEN 'Taj Mahal' THEN '/images/restaurant/taj_mahal.png'
    WHEN 'El Tapas' THEN '/images/restaurant/el_tapas.png'
    ELSE '/images/restaurant/default_restaurant.png'
END;

-- Mise à jour des chemins d'images pour les plats
UPDATE dish 
SET image_url = CASE name
    WHEN 'Tarte Tatin' THEN '/images/dishes/Tarte_tatin.png'
    WHEN 'Coq au Vin' THEN '/images/dishes/Coq_au_vin.png'
    WHEN 'Cordon Bleu' THEN '/images/dishes/Cordon_bleu.png'
    WHEN 'Boeuf Bourguignon' THEN '/images/dishes/Boeuf_bourguignon.png'
    WHEN 'Saumon en Papillotte' THEN '/images/dishes/Saumon_papillotte.png'
    WHEN 'Steak au Poivre' THEN '/images/dishes/Steak.png'
    WHEN 'Margherita Pizza' THEN '/images/dishes/pizza.png'
    WHEN 'Pizza Quattro Formaggi' THEN '/images/dishes/pizza2.png'
    WHEN 'Pizza Pepperoni' THEN '/images/dishes/pizza3.png'
    WHEN 'Pâtes Carbonara' THEN '/images/dishes/pasta.png'
    WHEN 'Lasagne Bolognaise' THEN '/images/dishes/pasta1.png'
    WHEN 'Sushi Mix' THEN '/images/dishes/sushi.png'
    WHEN 'California Roll' THEN '/images/dishes/sushi1.png'
    WHEN 'Sashimi Saumon' THEN '/images/dishes/sushi2.png'
    WHEN 'Tiramisu' THEN '/images/dishes//Tiramisu.png'
    WHEN 'Tempura Crevettes' THEN '/images/dishes/Tempura_Crevettes.png'
    WHEN 'Ramen Miso' THEN '/images/dishes/Ramen_Miso.png'
    WHEN 'Mochi Glacé' THEN '/images/dishes//Mochi_Glace.png'
    ELSE '/images/dishes/default_dish.png'
END;