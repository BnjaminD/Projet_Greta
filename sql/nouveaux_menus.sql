
-- Menus pour Le Bistrot Parisien (restaurant_id = 4)
INSERT INTO menu (restaurant_id, name, description, is_active) VALUES
(4, 'Menu Traditionnel', 'Une sélection de nos plats traditionnels français', 1),
(4, 'Menu Dégustation', 'Un voyage culinaire à travers la cuisine française', 1);

-- Plats pour Le Bistrot Parisien
INSERT INTO dish (menu_id, name, description, price, category, image_url, available) VALUES
-- Menu Traditionnel
(7, 'Soupe à l''Oignon', 'Soupe traditionnelle à l''oignon gratinée au fromage', 8.99, 'Entrée', './images/dishes/soupe_oignon.png', 1),
(7, 'Escargots de Bourgogne', 'Escargots au beurre d''ail et aux herbes', 12.99, 'Entrée', './images/dishes/escargots.png', 1),
(7, 'Blanquette de Veau', 'Veau mijoté en sauce blanche avec légumes', 24.99, 'Plat Principal', './images/dishes/blanquette.png', 1),
(7, 'Crème Brûlée', 'Crème vanillée avec croûte de sucre caramélisé', 7.99, 'Dessert', './images/dishes/creme_brulee.png', 1),

-- Menu Dégustation
(8, 'Foie Gras Maison', 'Foie gras mi-cuit avec chutney de figues', 16.99, 'Entrée', './images/dishes/foie_gras.png', 1),
(8, 'Magret de Canard', 'Magret de canard avec sauce aux fruits rouges', 28.99, 'Plat Principal', './images/dishes/magret.png', 1),
(8, 'Profiteroles', 'Choux à la crème avec sauce au chocolat', 9.99, 'Dessert', './images/dishes/profiteroles.png', 1);

-- Menus pour Taj Mahal (restaurant_id = 5)
INSERT INTO menu (restaurant_id, name, description, is_active) VALUES
(5, 'Menu Royal', 'Une expérience gastronomique indienne royale', 1),
(5, 'Menu Végétarien', 'Sélection de nos meilleurs plats végétariens', 1);

-- Plats pour Taj Mahal
INSERT INTO dish (menu_id, name, description, price, category, image_url, available) VALUES
-- Menu Royal
(9, 'Samosas', 'Chaussons frits aux légumes et épices', 7.99, 'Entrée', './images/dishes/samosas.png', 1),
(9, 'Poulet Tandoori', 'Poulet mariné aux épices et cuit au tandoor', 19.99, 'Plat Principal', './images/dishes/tandoori.png', 1),
(9, 'Agneau Biryani', 'Riz parfumé avec agneau et épices', 22.99, 'Plat Principal', './images/dishes/biryani.png', 1),
(9, 'Gulab Jamun', 'Boulettes de lait sucrées au sirop', 6.99, 'Dessert', './images/dishes/gulab.png', 1),

-- Menu Végétarien
(10, 'Dal Makhani', 'Lentilles noires mijotées en sauce crémeuse', 16.99, 'Plat Principal', './images/dishes/dal.png', 1),
(10, 'Palak Paneer', 'Fromage indien aux épinards', 17.99, 'Plat Principal', './images/dishes/palak.png', 1),
(10, 'Naan Nature', 'Pain indien traditionnel', 3.99, 'Accompagnement', './images/dishes/naan.png', 1);

-- Menus pour El Tapas (restaurant_id = 6)
INSERT INTO menu (restaurant_id, name, description, is_active) VALUES
(6, 'Menu Tapas', 'Assortiment de tapas traditionnelles espagnoles', 1),
(6, 'Menu Paella', 'Spécialités de riz espagnol', 1);

-- Plats pour El Tapas
INSERT INTO dish (menu_id, name, description, price, category, image_url, available) VALUES
-- Menu Tapas
(11, 'Patatas Bravas', 'Pommes de terre sauce épicée', 6.99, 'Tapas', './images/dishes/patatas.png', 1),
(11, 'Jamón Ibérico', 'Jambon ibérique de bellota', 14.99, 'Tapas', './images/dishes/jamon.png', 1),
(11, 'Tortilla Española', 'Omelette espagnole aux pommes de terre', 8.99, 'Tapas', './images/dishes/tortilla.png', 1),
(11, 'Gambas al Ajillo', 'Crevettes à l''ail', 12.99, 'Tapas', './images/dishes/gambas.png', 1),

-- Menu Paella
(12, 'Paella Valenciana', 'Paella traditionnelle au poulet et fruits de mer', 24.99, 'Plat Principal', './images/dishes/paella.png', 1),
(12, 'Paella Marinera', 'Paella aux fruits de mer', 26.99, 'Plat Principal', './images/dishes/paella_mar.png', 1),
(12, 'Crema Catalana', 'Crème catalane traditionnelle', 6.99, 'Dessert', './images/dishes/crema.png', 1);