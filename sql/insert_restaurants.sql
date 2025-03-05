-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : mar. 26 nov. 2024 à 10:39
-- Version du serveur : 8.0.35
-- Version de PHP : 8.2.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `Application`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin_user`
--

CREATE TABLE `admin_user` (
  `admin_id` int NOT NULL,
  `user_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `is_super_admin` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `admin_user`
--

INSERT INTO `admin_user` (`admin_id`, `user_id`, `created_at`, `is_super_admin`) VALUES
(1, 3, '2024-11-19 10:55:13', 1);

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

CREATE TABLE `comment` (
  `comment_id` int NOT NULL,
  `restaurant_id` int NOT NULL,
  `user_id` int NOT NULL,
  `content` text NOT NULL,
  `rating` float DEFAULT NULL,
  `is_moderated` tinyint(1) DEFAULT '0',
  `moderated_at` timestamp NULL DEFAULT NULL,
  `moderated_by` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `comment`
--

INSERT INTO `comment` (`comment_id`, `restaurant_id`, `user_id`, `content`, `rating`, `is_moderated`, `moderated_at`, `moderated_by`, `created_at`) VALUES
(1, 1, 1, 'Amazing food and great ambiance!', 5, 0, NULL, NULL, '2024-11-19 10:55:13'),
(2, 2, 2, 'Pizza was delicious but delivery took too long.', 4, 1, NULL, 1, '2024-11-19 10:55:13');

-- --------------------------------------------------------

--
-- Structure de la table `dish`
--

CREATE TABLE `dish` (
  `dish_id` int NOT NULL,
  `menu_id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(100) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `available` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `dish`
--

INSERT INTO `dish` (`dish_id`, `menu_id`, `name`, `description`, `price`, `category`, `image_url`, `available`) VALUES
(1, 1, 'Steak au Poivre', 'Pepper-crusted steak with creamy sauce.', 29.99, 'Main Course', 'https://example.com/steak.jpg', 1),
(2, 2, 'Margherita Pizza', 'Classic pizza with tomato, mozzarella, and basil.', 12.99, 'Pizza', 'https://example.com/margherita.jpg', 1);

-- --------------------------------------------------------

--
-- Structure de la table `dish_ingredient`
--

CREATE TABLE `dish_ingredient` (
  `dish_id` int NOT NULL,
  `ingredient_id` int NOT NULL,
  `quantity` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `dish_ingredient`
--

INSERT INTO `dish_ingredient` (`dish_id`, `ingredient_id`, `quantity`) VALUES
(1, 4, '1 tsp'),
(1, 5, '100 ml'),
(2, 1, '100 g'),
(2, 2, '150 g'),
(2, 3, '5 leaves');

-- --------------------------------------------------------

--
-- Structure de la table `favorite`
--

CREATE TABLE `favorite` (
  `user_id` int NOT NULL,
  `restaurant_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `favorite`
--

INSERT INTO `favorite` (`user_id`, `restaurant_id`, `created_at`) VALUES
(1, 1, '2024-11-19 10:55:13'),
(2, 2, '2024-11-19 10:55:13');

-- --------------------------------------------------------

--
-- Structure de la table `ingredient_catalog`
--

CREATE TABLE `ingredient_catalog` (
  `ingredient_id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `allergen` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `ingredient_catalog`
--

INSERT INTO `ingredient_catalog` (`ingredient_id`, `name`, `allergen`) VALUES
(1, 'Tomato', 0),
(2, 'Mozzarella', 1),
(3, 'Basil', 0),
(4, 'Black Pepper', 0),
(5, 'Cream', 1);

-- --------------------------------------------------------

--
-- Structure de la table `like`
--

CREATE TABLE `like` (
  `user_id` int NOT NULL,
  `restaurant_id` int NOT NULL,
  `is_moderated` tinyint(1) DEFAULT '0',
  `moderated_at` timestamp NULL DEFAULT NULL,
  `moderated_by` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `like`
--

INSERT INTO `like` (`user_id`, `restaurant_id`, `is_moderated`, `moderated_at`, `moderated_by`, `created_at`) VALUES
(1, 1, 0, NULL, NULL, '2024-11-19 10:55:13'),
(2, 2, 0, NULL, NULL, '2024-11-19 10:55:13');

-- --------------------------------------------------------

--
-- Structure de la table `menu`
--

CREATE TABLE `menu` (
  `menu_id` int NOT NULL,
  `restaurant_id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `menu`
--

INSERT INTO `menu` (`menu_id`, `restaurant_id`, `name`, `description`, `is_active`, `created_at`) VALUES
(1, 1, 'Dinner Specials', 'A selection of our finest dishes.', 1, '2024-11-19 10:55:13'),
(2, 2, 'Pizza Menu', 'All your favorite pizzas.', 1, '2024-11-19 10:55:13');

-- --------------------------------------------------------

--
-- Structure de la table `moderation_action`
--

CREATE TABLE `moderation_action` (
  `action_id` int NOT NULL,
  `admin_id` int NOT NULL,
  `action_type` varchar(50) NOT NULL,
  `target_type` varchar(50) NOT NULL,
  `target_id` int NOT NULL,
  `reason` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `moderation_action`
--

INSERT INTO `moderation_action` (`action_id`, `admin_id`, `action_type`, `target_type`, `target_id`, `reason`, `created_at`) VALUES
(1, 1, 'ban', 'user', 2, 'Spamming inappropriate comments.', '2024-11-19 10:55:13');

-- --------------------------------------------------------

--
-- Structure de la table `oauth_account`
--

CREATE TABLE `oauth_account` (
  `oauth_id` int NOT NULL,
  `user_id` int NOT NULL,
  `provider` varchar(50) NOT NULL,
  `provider_user_id` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `oauth_account`
--

INSERT INTO `oauth_account` (`oauth_id`, `user_id`, `provider`, `provider_user_id`, `created_at`) VALUES
(1, 1, 'google', 'google_user_123', '2024-11-19 10:55:13'),
(2, 2, 'facebook', 'facebook_user_456', '2024-11-19 10:55:13');

-- --------------------------------------------------------

--
-- Structure de la table `order`
--

CREATE TABLE `order` (
  `order_id` int NOT NULL,
  `user_id` int NOT NULL,
  `restaurant_id` int NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `ordered_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `order`
--

INSERT INTO `order` (`order_id`, `user_id`, `restaurant_id`, `total_price`, `status`, `ordered_at`) VALUES
(1, 1, 1, 59.98, 'completed', '2024-11-19 10:55:13'),
(2, 2, 2, 25.98, 'pending', '2024-11-19 10:55:13');

-- --------------------------------------------------------

--
-- Structure de la table `order_item`
--

CREATE TABLE `order_item` (
  `order_item_id` int NOT NULL,
  `order_id` int NOT NULL,
  `dish_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `order_item`
--

INSERT INTO `order_item` (`order_item_id`, `order_id`, `dish_id`, `quantity`, `price`) VALUES
(1, 1, 1, 2, 29.99),
(2, 2, 2, 2, 12.99);

-- --------------------------------------------------------

--
-- Structure de la table `reservation`
--

CREATE TABLE `reservation` (
  `reservation_id` int NOT NULL,
  `restaurant_id` int NOT NULL,
  `user_id` int NOT NULL,
  `reservation_time` timestamp NOT NULL,
  `number_of_guests` int NOT NULL,
  `status` varchar(50) NOT NULL,
  `special_requests` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `reservation`
--

INSERT INTO `reservation` (`reservation_id`, `restaurant_id`, `user_id`, `reservation_time`, `number_of_guests`, `status`, `special_requests`, `created_at`) VALUES
(1, 1, 1, '2024-11-20 18:00:00', 2, 'confirmed', 'Table near the window.', '2024-11-19 10:55:13'),
(2, 2, 2, '2024-11-21 19:30:00', 4, 'pending', NULL, '2024-11-19 10:55:13');

-- --------------------------------------------------------

--
-- Structure de la table `restaurant`
--

CREATE TABLE `restaurant` (
  `restaurant_id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `address` varchar(255) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `opening_hours` varchar(255) NOT NULL,
  `capacity` int NOT NULL,
  `rating` float DEFAULT NULL,
  `cuisine_type` varchar(100) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `restaurant`
--

INSERT INTO `restaurant` (`restaurant_id`, `name`, `description`, `address`, `phone_number`, `opening_hours`, `capacity`, `rating`, `cuisine_type`, `image_url`, `latitude`, `longitude`, `created_at`) VALUES
(1, 'The Fancy Fork', 'A fine dining experience.', '123 Main St, City', '123-456-7890', '9:00 AM - 10:00 PM', 50, 4.8, 'French', 'https://example.com/fancyfork.jpg', 48.85660000, 2.35220000, '2024-11-19 10:55:13'),
(2, 'Pizza Paradise', 'Best pizza in town!', '456 Elm St, City', '987-654-3210', '11:00 AM - 11:00 PM', 100, 4.5, 'Italian', 'https://example.com/pizzaparadise.jpg', 48.85670000, 2.35230000, '2024-11-19 10:55:13'),
(3, 'Sushi World', 'Fresh sushi and sashimi.', '789 Oak St, City', '555-123-4567', '12:00 PM - 10:00 PM', 80, 4.7, 'Japanese', 'https://example.com/sushiworld.jpg', 48.85680000, 2.35240000, '2024-11-19 10:55:13');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `user_id` int NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `username` varchar(100) NOT NULL,
  `firstname` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `profile_picture_url` varchar(255) DEFAULT NULL,
  `bio` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `is_banned` tinyint(1) DEFAULT '0',
  `banned_at` timestamp NULL DEFAULT NULL,
  `banned_by` int DEFAULT NULL,
  `email_verified` tinyint(1) DEFAULT '0',
  `failed_login_attempts` int DEFAULT '0',
  `account_locked_until` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`user_id`, `email`, `password_hash`, `username`, `firstname`, `lastname`, `profile_picture_url`, `bio`, `created_at`, `last_login`, `is_active`, `is_banned`, `banned_at`, `banned_by`, `email_verified`, `failed_login_attempts`, `account_locked_until`) VALUES
(1, 'user1@example.com', 'hashedpassword1', 'UserOne', NULL, NULL, 'https://example.com/profile1.jpg', 'Bio of User One', '2024-11-19 10:55:13', NULL, 1, 0, NULL, NULL, 1, 0, NULL),
(2, 'user2@example.com', 'hashedpassword2', 'UserTwo', NULL, NULL, 'https://example.com/profile2.jpg', 'Bio of User Two', '2024-11-19 10:55:13', NULL, 1, 0, NULL, NULL, 0, 1, NULL),
(3, 'admin@example.com', 'hashedpassword3', 'AdminUser', NULL, NULL, 'https://example.com/admin.jpg', 'Bio of Admin User', '2024-11-19 10:55:13', NULL, 1, 0, NULL, NULL, 1, 0, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `user_activity_log`
--

CREATE TABLE `user_activity_log` (
  `activity_id` int NOT NULL,
  `user_id` int NOT NULL,
  `action` varchar(255) NOT NULL,
  `performed_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `details` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `user_activity_log`
--

INSERT INTO `user_activity_log` (`activity_id`, `user_id`, `action`, `performed_at`, `details`) VALUES
(1, 1, 'Login', '2024-11-19 10:55:13', 'User logged in successfully.'),
(2, 2, 'Reservation', '2024-11-19 10:55:13', 'User reserved a table for 4.');

-- --------------------------------------------------------

--
-- Structure de la table `user_roles`
--

CREATE TABLE `user_roles` (
  `user_role_id` int NOT NULL,
  `user_id` int NOT NULL,
  `role_name` varchar(50) NOT NULL,
  `assigned_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `user_roles`
--

INSERT INTO `user_roles` (`user_role_id`, `user_id`, `role_name`, `assigned_at`) VALUES
(1, 1, 'user', '2024-11-19 10:55:13'),
(2, 2, 'user', '2024-11-19 10:55:13'),
(3, 3, 'admin', '2024-11-19 10:55:13');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `admin_user`
--
ALTER TABLE `admin_user`
  ADD PRIMARY KEY (`admin_id`),
  ADD KEY `fk_admin_user` (`user_id`);

--
-- Index pour la table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `fk_comment_user` (`user_id`),
  ADD KEY `fk_comment_moderated_by` (`moderated_by`),
  ADD KEY `idx_comment_restaurant` (`restaurant_id`);

--
-- Index pour la table `dish`
--
ALTER TABLE `dish`
  ADD PRIMARY KEY (`dish_id`),
  ADD KEY `idx_dish_menu` (`menu_id`);

--
-- Index pour la table `dish_ingredient`
--
ALTER TABLE `dish_ingredient`
  ADD PRIMARY KEY (`dish_id`,`ingredient_id`),
  ADD KEY `fk_dish_ingredient_ingredient` (`ingredient_id`);

--
-- Index pour la table `favorite`
--
ALTER TABLE `favorite`
  ADD PRIMARY KEY (`user_id`,`restaurant_id`),
  ADD KEY `fk_favorite_restaurant` (`restaurant_id`);

--
-- Index pour la table `ingredient_catalog`
--
ALTER TABLE `ingredient_catalog`
  ADD PRIMARY KEY (`ingredient_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Index pour la table `like`
--
ALTER TABLE `like`
  ADD PRIMARY KEY (`user_id`,`restaurant_id`),
  ADD KEY `fk_like_restaurant` (`restaurant_id`),
  ADD KEY `fk_like_moderated_by` (`moderated_by`);

--
-- Index pour la table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`menu_id`),
  ADD KEY `fk_menu_restaurant` (`restaurant_id`);

--
-- Index pour la table `moderation_action`
--
ALTER TABLE `moderation_action`
  ADD PRIMARY KEY (`action_id`),
  ADD KEY `fk_moderation_action_admin` (`admin_id`);

--
-- Index pour la table `oauth_account`
--
ALTER TABLE `oauth_account`
  ADD PRIMARY KEY (`oauth_id`),
  ADD KEY `fk_oauth_user` (`user_id`),
  ADD KEY `idx_oauth_provider_user` (`provider`,`provider_user_id`);

--
-- Index pour la table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `fk_order_restaurant` (`restaurant_id`),
  ADD KEY `idx_order_user_created` (`user_id`,`ordered_at`);

--
-- Index pour la table `order_item`
--
ALTER TABLE `order_item`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `fk_order_item_order` (`order_id`),
  ADD KEY `fk_order_item_dish` (`dish_id`);

--
-- Index pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`reservation_id`),
  ADD KEY `fk_reservation_user` (`user_id`),
  ADD KEY `idx_reservation_restaurant_date` (`restaurant_id`,`reservation_time`);

--
-- Index pour la table `restaurant`
--
ALTER TABLE `restaurant`
  ADD PRIMARY KEY (`restaurant_id`),
  ADD KEY `idx_restaurant_cuisine_type` (`cuisine_type`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `fk_banned_by` (`banned_by`),
  ADD KEY `idx_user_email` (`email`),
  ADD KEY `idx_user_username` (`username`);

--
-- Index pour la table `user_activity_log`
--
ALTER TABLE `user_activity_log`
  ADD PRIMARY KEY (`activity_id`),
  ADD KEY `fk_activity_log_user` (`user_id`);

--
-- Index pour la table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`user_role_id`),
  ADD KEY `fk_user_roles_user` (`user_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `admin_user`
--
ALTER TABLE `admin_user`
  MODIFY `admin_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `comment`
--
ALTER TABLE `comment`
  MODIFY `comment_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `dish`
--
ALTER TABLE `dish`
  MODIFY `dish_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `ingredient_catalog`
--
ALTER TABLE `ingredient_catalog`
  MODIFY `ingredient_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `menu`
--
ALTER TABLE `menu`
  MODIFY `menu_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `moderation_action`
--
ALTER TABLE `moderation_action`
  MODIFY `action_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `oauth_account`
--
ALTER TABLE `oauth_account`
  MODIFY `oauth_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `order`
--
ALTER TABLE `order`
  MODIFY `order_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `order_item`
--
ALTER TABLE `order_item`
  MODIFY `order_item_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `reservation_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `restaurant`
--
ALTER TABLE `restaurant`
  MODIFY `restaurant_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `user_activity_log`
--
ALTER TABLE `user_activity_log`
  MODIFY `activity_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `user_role_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `admin_user`
--
ALTER TABLE `admin_user`
  ADD CONSTRAINT `fk_admin_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Contraintes pour la table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `fk_comment_moderated_by` FOREIGN KEY (`moderated_by`) REFERENCES `admin_user` (`admin_id`),
  ADD CONSTRAINT `fk_comment_restaurant` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`restaurant_id`),
  ADD CONSTRAINT `fk_comment_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Contraintes pour la table `dish`
--
ALTER TABLE `dish`
  ADD CONSTRAINT `fk_dish_menu` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`menu_id`);

--
-- Contraintes pour la table `dish_ingredient`
--
ALTER TABLE `dish_ingredient`
  ADD CONSTRAINT `fk_dish_ingredient_dish` FOREIGN KEY (`dish_id`) REFERENCES `dish` (`dish_id`),
  ADD CONSTRAINT `fk_dish_ingredient_ingredient` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredient_catalog` (`ingredient_id`);

--
-- Contraintes pour la table `favorite`
--
ALTER TABLE `favorite`
  ADD CONSTRAINT `fk_favorite_restaurant` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`restaurant_id`),
  ADD CONSTRAINT `fk_favorite_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Contraintes pour la table `like`
--
ALTER TABLE `like`
  ADD CONSTRAINT `fk_like_moderated_by` FOREIGN KEY (`moderated_by`) REFERENCES `admin_user` (`admin_id`),
  ADD CONSTRAINT `fk_like_restaurant` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`restaurant_id`),
  ADD CONSTRAINT `fk_like_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Contraintes pour la table `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `fk_menu_restaurant` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`restaurant_id`);

--
-- Contraintes pour la table `moderation_action`
--
ALTER TABLE `moderation_action`
  ADD CONSTRAINT `fk_moderation_action_admin` FOREIGN KEY (`admin_id`) REFERENCES `admin_user` (`admin_id`);

--
-- Contraintes pour la table `oauth_account`
--
ALTER TABLE `oauth_account`
  ADD CONSTRAINT `fk_oauth_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Contraintes pour la table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `fk_order_restaurant` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`restaurant_id`),
  ADD CONSTRAINT `fk_order_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Contraintes pour la table `order_item`
--
ALTER TABLE `order_item`
  ADD CONSTRAINT `fk_order_item_dish` FOREIGN KEY (`dish_id`) REFERENCES `dish` (`dish_id`),
  ADD CONSTRAINT `fk_order_item_order` FOREIGN KEY (`order_id`) REFERENCES `order` (`order_id`);

--
-- Contraintes pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `fk_reservation_restaurant` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`restaurant_id`),
  ADD CONSTRAINT `fk_reservation_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk_banned_by` FOREIGN KEY (`banned_by`) REFERENCES `user` (`user_id`);

--
-- Contraintes pour la table `user_activity_log`
--
ALTER TABLE `user_activity_log`
  ADD CONSTRAINT `fk_activity_log_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Contraintes pour la table `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `fk_user_roles_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

INSERT INTO restaurant (name, description, address, phone_number, opening_hours, capacity, rating, cuisine_type, image_url, latitude, longitude) 
VALUES 
('The Fancy Fork', 'A fine dining experience.', '123 Main St, City', '123-456-7890', '9:00 AM - 10:00 PM', 50, 4.8, 'French', 'Fancy_fork.jpg', 48.85660000, 2.35220000),
('Pizza Paradise', 'Best pizza in town!', '456 Elm St, City', '987-654-3210', '11:00 AM - 11:00 PM', 100, 4.5, 'Italian', 'Pizza_paradise.jpg', 48.85670000, 2.35230000),
('Sushi World', 'Fresh sushi and sashimi.', '789 Oak St, City', '555-123-4567', '12:00 PM - 10:00 PM', 80, 4.7, 'Japanese', 'sushi_world.jpg', 48.85680000, 2.35240000);

INSERT INTO restaurant (name, description, address, phone_number, opening_hours, capacity, rating, cuisine_type, image_url, latitude, longitude)
VALUES 
('Le Bistrot Parisien', 'Authentic French cuisine in a cozy atmosphere', '15 Rue de la Paix, Paris', '01-23-45-67-89', '11:30 AM - 11:00 PM', 40, 4.6, 'French', 'bistrot_parisien.jpg', 48.87034, 2.33186),
('Taj Mahal', 'Fine Indian dining experience', '78 Avenue des Champs-Élysées, Paris', '01-98-76-54-32', '12:00 PM - 10:30 PM', 60, 4.4, 'Indian', 'taj_mahal.jpg', 48.86923, 2.30984),
('El Tapas', 'Traditional Spanish tapas bar', '25 Rue du Commerce, Paris', '01-45-67-89-12', '6:00 PM - 2:00 AM', 35, 4.3, 'Spanish', 'el_tapas.jpg', 48.84789, 2.29456);

-- Mise à jour des chemins d'images pour les restaurants existants
UPDATE restaurant 
SET image_url = CASE name
    WHEN 'The Fancy Fork' THEN 'Fancy_fork.jpg'
    WHEN 'Pizza Paradise' THEN 'Pizza_paradise.jpg'
    WHEN 'Sushi World' THEN 'sushi_world.jpg'
END
WHERE name IN ('The Fancy Fork', 'Pizza Paradise', 'Sushi World');