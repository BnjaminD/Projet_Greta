-- Désactiver temporairement la vérification des clés étrangères
SET FOREIGN_KEY_CHECKS = 0;

-- Suppression des anciennes données dans l'ordre correct (ordre hiérarchique)
DELETE FROM order_item WHERE 1=1;
DELETE FROM dish_ingredient WHERE 1=1;
DELETE FROM dish WHERE 1=1;
DELETE FROM menu WHERE 1=1;

-- Réactiver la vérification des clés étrangères
SET FOREIGN_KEY_CHECKS = 1;

-- Réinitialisation des auto-increment
ALTER TABLE order_item AUTO_INCREMENT = 1;
ALTER TABLE dish AUTO_INCREMENT = 1;
ALTER TABLE menu AUTO_INCREMENT = 1;

-- Vérification de l'existence des restaurants
INSERT INTO menu (restaurant_id, name, description, is_active)
SELECT restaurant_id, 'Menu Gastronomique', 'Notre sélection de plats raffinés', 1
FROM restaurant WHERE restaurant_id = 1;

INSERT INTO menu (restaurant_id, name, description, is_active)
SELECT restaurant_id, 'Menu Dégustation', 'Une expérience culinaire unique', 1
FROM restaurant WHERE restaurant_id = 1;

INSERT INTO menu (restaurant_id, name, description, is_active)
SELECT restaurant_id, 'Menu Pizza', 'Nos meilleures pizzas italiennes', 1
FROM restaurant WHERE restaurant_id = 2;

INSERT INTO menu (restaurant_id, name, description, is_active)
SELECT restaurant_id, 'Menu Pasta', 'Sélection de pâtes fraîches', 1
FROM restaurant WHERE restaurant_id = 2;

INSERT INTO menu (restaurant_id, name, description, is_active)
SELECT restaurant_id, 'Menu Sushi', 'Assortiment de sushis frais', 1
FROM restaurant WHERE restaurant_id = 3;

INSERT INTO menu (restaurant_id, name, description, is_active)
SELECT restaurant_id, 'Menu Découverte', 'Spécialités japonaises', 1
FROM restaurant WHERE restaurant_id = 3;

-- Insertion des plats avec vérification des menus existants
INSERT INTO dish (menu_id, name, description, price, category, image_url, available)
SELECT m.menu_id, 'Steak au Poivre', 'Un steak juteux avec sauce au poivre noir frais moulu', 29.99, 'Plat Principal', './image/Steak.png', 1
FROM menu m WHERE m.restaurant_id = 1 AND m.name = 'Menu Gastronomique';

INSERT INTO dish (menu_id, name, description, price, category, image_url, available)
SELECT m.menu_id, 'Coq au Vin', 'Poulet mijoté au vin rouge avec légumes de saison', 24.99, 'Plat Principal', './image/Coq_au_vin.png', 1
FROM menu m WHERE m.restaurant_id = 1 AND m.name = 'Menu Gastronomique';

INSERT INTO dish (menu_id, name, description, price, category, image_url, available)
SELECT m.menu_id, 'Tarte Tatin', 'Tarte aux pommes caramélisées et pâte feuilletée', 9.99, 'Dessert', './image/Tarte_tatin.png', 1
FROM menu m WHERE m.restaurant_id = 1 AND m.name = 'Menu Gastronomique';

INSERT INTO dish (menu_id, name, description, price, category, image_url, available)
SELECT m.menu_id, 'Salade César', 'Laitue romaine, parmesan, croûtons et sauce César maison', 12.99, 'Entrée', './image/salad.png', 1
FROM menu m WHERE m.restaurant_id = 1 AND m.name = 'Menu Gastronomique';

INSERT INTO dish (menu_id, name, description, price, category, image_url, available)
SELECT m.menu_id, 'Boeuf Bourguignon', 'Bœuf mijoté au vin rouge avec champignons et lardons', 28.99, 'Plat Principal', './image/Boeuf_bourguignon.png', 1
FROM menu m WHERE m.restaurant_id = 1 AND m.name = 'Menu Dégustation';

INSERT INTO dish (menu_id, name, description, price, category, image_url, available)
SELECT m.menu_id, 'Cordon Bleu', 'Escalope de poulet farcie au jambon et fromage', 25.99, 'Plat Principal', './image/Cordon_bleu.png', 1
FROM menu m WHERE m.restaurant_id = 1 AND m.name = 'Menu Dégustation';

INSERT INTO dish (menu_id, name, description, price, category, image_url, available)
SELECT m.menu_id, 'Saumon en Papillotte', 'Saumon frais cuit en papillote aux herbes', 26.99, 'Plat Principal', './image/Saumon_papillotte.png', 1
FROM menu m WHERE m.restaurant_id = 1 AND m.name = 'Menu Dégustation';

INSERT INTO dish (menu_id, name, description, price, category, image_url, available)
SELECT m.menu_id, 'Margherita Pizza', 'Sauce tomate, mozzarella fraîche et basilic', 12.99, 'Pizza', './image/pizza.png', 1
FROM menu m WHERE m.restaurant_id = 2 AND m.name = 'Menu Pizza';

INSERT INTO dish (menu_id, name, description, price, category, image_url, available)
SELECT m.menu_id, 'Pizza Quattro Formaggi', 'Mélange de quatre fromages italiens', 14.99, 'Pizza', './image/pizza2.png', 1
FROM menu m WHERE m.restaurant_id = 2 AND m.name = 'Menu Pizza';

INSERT INTO dish (menu_id, name, description, price, category, image_url, available)
SELECT m.menu_id, 'Pizza Pepperoni', 'Pepperoni, mozzarella et sauce tomate', 13.99, 'Pizza', './image/pizza3.png', 1
FROM menu m WHERE m.restaurant_id = 2 AND m.name = 'Menu Pizza';

INSERT INTO dish (menu_id, name, description, price, category, image_url, available)
SELECT m.menu_id, 'Pâtes Carbonara', 'Spaghetti à la crème, œuf et lardons', 15.99, 'Pâtes', './image/pasta.png', 1
FROM menu m WHERE m.restaurant_id = 2 AND m.name = 'Menu Pasta';

INSERT INTO dish (menu_id, name, description, price, category, image_url, available)
SELECT m.menu_id, 'Lasagne Bolognaise', 'Lasagnes traditionnelles à la sauce bolognaise', 16.99, 'Pâtes', './image/pasta1.png', 1
FROM menu m WHERE m.restaurant_id = 2 AND m.name = 'Menu Pasta';

INSERT INTO dish (menu_id, name, description, price, category, image_url, available)
SELECT m.menu_id, 'Tiramisu', 'Dessert italien au café et mascarpone', 7.99, 'Dessert', './image/Tiramisu.png', 1
FROM menu m WHERE m.restaurant_id = 2 AND m.name = 'Menu Pasta';

INSERT INTO dish (menu_id, name, description, price, category, image_url, available)
SELECT m.menu_id, 'Sushi Mix', 'Assortiment de sushis variés (12 pièces)', 22.99, 'Sushi', './image/sushi.png', 1
FROM menu m WHERE m.restaurant_id = 3 AND m.name = 'Menu Sushi';

INSERT INTO dish (menu_id, name, description, price, category, image_url, available)
SELECT m.menu_id, 'California Roll', 'Rouleaux de crabe, avocat et concombre (8 pièces)', 16.99, 'Maki', './image/sushi1.png', 1
FROM menu m WHERE m.restaurant_id = 3 AND m.name = 'Menu Sushi';

INSERT INTO dish (menu_id, name, description, price, category, image_url, available)
SELECT m.menu_id, 'Sashimi Saumon', 'Tranches de saumon frais (10 pièces)', 18.99, 'Sashimi', './image/sushi2.png', 1
FROM menu m WHERE m.restaurant_id = 3 AND m.name = 'Menu Sushi';

INSERT INTO dish (menu_id, name, description, price, category, image_url, available)
SELECT m.menu_id, 'Tempura Crevettes', 'Crevettes frites en tempura avec sauce', 17.99, 'Entrée', './image/Tempura_Crevettes.png', 1
FROM menu m WHERE m.restaurant_id = 3 AND m.name = 'Menu Découverte';

INSERT INTO dish (menu_id, name, description, price, category, image_url, available)
SELECT m.menu_id, 'Ramen Miso', 'Soupe ramen au miso avec porc chachu', 15.99, 'Plat Principal', './image/Ramen_Miso.png', 1
FROM menu m WHERE m.restaurant_id = 3 AND m.name = 'Menu Découverte';

INSERT INTO dish (menu_id, name, description, price, category, image_url, available)
SELECT m.menu_id, 'Mochi Glacé', 'Dessert japonais glacé aux fruits', 6.99, 'Dessert', './image/Mochi_Glace.png', 1
FROM menu m WHERE m.restaurant_id = 3 AND m.name = 'Menu Découverte';

-- Mettre à jour les chemins d'images pour correspondre à la structure du projet
UPDATE dish SET image_url = './images/dishes/steak.png' WHERE name = 'Steak au Poivre';
UPDATE dish SET image_url = './images/dishes/coq_au_vin.png' WHERE name = 'Coq au Vin';
UPDATE dish SET image_url = './images/dishes/tarte_tatin.png' WHERE name = 'Tarte Tatin';
UPDATE dish SET image_url = './images/dishes/salad.png' WHERE name = 'Salade César';
UPDATE dish SET image_url = './images/dishes/boeuf_bourguignon.png' WHERE name = 'Boeuf Bourguignon';
UPDATE dish SET image_url = './images/dishes/cordon_bleu.png' WHERE name = 'Cordon Bleu';
UPDATE dish SET image_url = './images/dishes/saumon_papillotte.png' WHERE name = 'Saumon en Papillotte';
UPDATE dish SET image_url = './images/dishes/pizza.png' WHERE name = 'Margherita Pizza';
UPDATE dish SET image_url = './images/dishes/pizza2.png' WHERE name = 'Pizza Quattro Formaggi';
UPDATE dish SET image_url = './images/dishes/pizza3.png' WHERE name = 'Pizza Pepperoni';
UPDATE dish SET image_url = './images/dishes/pasta.png' WHERE name = 'Pâtes Carbonara';
UPDATE dish SET image_url = './images/dishes/pasta1.png' WHERE name = 'Lasagne Bolognaise';
UPDATE dish SET image_url = './images/dishes/tiramisu.png' WHERE name = 'Tiramisu';
UPDATE dish SET image_url = './images/dishes/sushi.png' WHERE name = 'Sushi Mix';
UPDATE dish SET image_url = './images/dishes/sushi1.png' WHERE name = 'California Roll';
UPDATE dish SET image_url = './images/dishes/sushi2.png' WHERE name = 'Sashimi Saumon';
UPDATE dish SET image_url = './images/dishes/tempura_crevettes.png' WHERE name = 'Tempura Crevettes';
UPDATE dish SET image_url = './images/dishes/ramen_miso.png' WHERE name = 'Ramen Miso';
UPDATE dish SET image_url = './images/dishes/mochi_glace.png' WHERE name = 'Mochi Glacé';