-- Réinitialisation de la table
TRUNCATE TABLE dish_ingredient;

-- Plats Français

-- Steak au Poivre (ID: 1)
INSERT INTO dish_ingredient (dish_id, ingredient_id, quantity) VALUES
(1, 6, '250g'),     -- Beef
(1, 4, '15g'),      -- Black Pepper
(1, 11, '30g'),     -- Butter (allergène)
(1, 5, '100ml'),    -- Cream (allergène)
(1, 12, '2 gousses'); -- Garlic

-- Coq au Vin (ID: 2)
INSERT INTO dish_ingredient (dish_id, ingredient_id, quantity) VALUES
(2, 7, '1 poulet'), -- Chicken
(2, 14, '200g'),    -- Mushrooms
(2, 13, '2'),       -- Onion
(2, 12, '3 gousses'), -- Garlic
(2, 11, '50g');     -- Butter (allergène)

-- Tarte Tatin (ID: 3)
INSERT INTO dish_ingredient (dish_id, ingredient_id, quantity) VALUES
(3, 9, '250g'),     -- Flour (allergène)
(3, 11, '150g'),    -- Butter (allergène)
(3, 10, '2');       -- Eggs (allergène)

-- Salade César (ID: 4)
INSERT INTO dish_ingredient (dish_id, ingredient_id, quantity) VALUES
(4, 10, '2'),       -- Eggs (allergène)
(4, 2, '50g'),      -- Mozzarella (allergène)
(4, 12, '1 gousse'), -- Garlic
(4, 9, '50g');      -- Flour pour croûtons (allergène)

-- Boeuf Bourguignon (ID: 5)
INSERT INTO dish_ingredient (dish_id, ingredient_id, quantity) VALUES
(5, 6, '800g'),     -- Beef
(5, 14, '200g'),    -- Mushrooms
(5, 13, '3'),       -- Onion
(5, 12, '4 gousses'), -- Garlic
(5, 11, '50g');     -- Butter (allergène)

-- Plats Italiens

-- Margherita Pizza (ID: 8)
INSERT INTO dish_ingredient (dish_id, ingredient_id, quantity) VALUES
(8, 9, '300g'),     -- Flour (allergène)
(8, 1, '150g'),     -- Tomato
(8, 2, '150g'),     -- Mozzarella (allergène)
(8, 3, '10g');      -- Basil

-- Pizza Quattro Formaggi (ID: 9)
INSERT INTO dish_ingredient (dish_id, ingredient_id, quantity) VALUES
(9, 9, '300g'),     -- Flour (allergène)
(9, 2, '400g');     -- Mozzarella (allergène)

-- Pâtes Carbonara (ID: 11)
INSERT INTO dish_ingredient (dish_id, ingredient_id, quantity) VALUES
(11, 9, '350g'),    -- Flour (allergène)
(11, 10, '3'),      -- Eggs (allergène)
(11, 5, '200ml'),   -- Cream (allergène)
(11, 2, '100g');    -- Mozzarella (allergène)

-- Plats Japonais

-- Sushi Mix (ID: 14)
INSERT INTO dish_ingredient (dish_id, ingredient_id, quantity) VALUES
(14, 8, '200g'),    -- Salmon
(14, 9, '300g'),    -- Flour (allergène) pour le riz
(14, 10, '1');      -- Eggs (allergène)

-- California Roll (ID: 15)
INSERT INTO dish_ingredient (dish_id, ingredient_id, quantity) VALUES
(15, 8, '150g'),    -- Salmon
(15, 9, '250g');    -- Flour (allergène) pour le riz

-- Tempura Crevettes (ID: 17)
INSERT INTO dish_ingredient (dish_id, ingredient_id, quantity) VALUES
(17, 9, '200g'),    -- Flour (allergène)
(17, 10, '2');      -- Eggs (allergène)

-- Ramen Miso (ID: 18)
INSERT INTO dish_ingredient (dish_id, ingredient_id, quantity) VALUES
(18, 7, '100g'),    -- Chicken
(18, 10, '1'),      -- Eggs (allergène)
(18, 13, '1'),      -- Onion
(18, 9, '200g');    -- Flour (allergène) pour les nouilles

-- Desserts

-- Tiramisu (ID: 13)
INSERT INTO dish_ingredient (dish_id, ingredient_id, quantity) VALUES
(13, 10, '4'),      -- Eggs (allergène)
(13, 5, '250ml'),   -- Cream (allergène)
(13, 9, '100g');    -- Flour (allergène)

-- Mochi Glacé (ID: 19)
INSERT INTO dish_ingredient (dish_id, ingredient_id, quantity) VALUES
(19, 9, '150g'),    -- Flour (allergène)
(19, 5, '100ml');   -- Cream (allergène)