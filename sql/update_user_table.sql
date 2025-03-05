
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : lun. 17 fév. 2025 à 11:14
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
-- Structure de la table `admin_actions`
--

CREATE TABLE `admin_actions` (
  `action_id` int NOT NULL,
  `user_id` int NOT NULL,
  `action_type` varchar(50) NOT NULL,
  `action_details` text,
  `performed_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `admin_actions`
--

INSERT INTO `admin_actions` (`action_id`, `user_id`, `action_type`, `action_details`, `performed_at`, `ip_address`) VALUES
(1, 17, 'USER_BAN', 'Banned user ID 5 for spam', '2025-02-11 13:43:25', '192.168.1.1'),
(2, 18, 'COMMENT_DELETE', 'Deleted inappropriate comment ID 15', '2025-02-11 13:43:25', '192.168.1.2');

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
(4, 17, '2025-02-11 13:02:25', 1),
(5, 18, '2025-02-11 13:02:25', 1),
(8, 3, '2025-02-11 13:12:45', 1);

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
(1, 1, 1, 'Amazing food and great ambiance!', 5, 0, NULL, 8, '2024-11-19 10:55:13'),
(2, 2, 2, 'Pizza was delicious but delivery took too long.', 4, 1, NULL, 8, '2024-11-19 10:55:13'),
(3, 1, 1, 'Excellent service et plats délicieux !', 5, 0, NULL, 8, '2025-01-29 09:12:25'),
(4, 2, 2, 'Bonne ambiance, cuisine raffinée', 4, 0, NULL, 8, '2025-01-29 09:12:25'),
(5, 2, 1, 'Le menu est varié et les prix sont raisonnables', 4, 0, NULL, 8, '2025-01-29 09:12:25');

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
(1, 1, 'Steak au Poivre', 'Un steak juteux avec sauce au poivre noir frais moulu', 29.99, 'Plat Principal', 'dishes//images/dishes/Steak.png', 1),
(2, 1, 'Coq au Vin', 'Poulet mijoté au vin rouge avec légumes de saison', 24.99, 'Plat Principal', './images/dishes/Coq_au_vin.png', 1),
(3, 1, 'Tarte Tatin', 'Tarte aux pommes caramélisées et pâte feuilletée', 9.99, 'Dessert', 'dishes//images/dishes/Tarte_tatin.png', 1),
(4, 1, 'Salade César', 'Laitue romaine, parmesan, croûtons et sauce César maison', 12.99, 'Entrée', './images/dishes/Salade_cesar.png', 1),
(5, 2, 'Boeuf Bourguignon', 'Bœuf mijoté au vin rouge avec champignons et lardons', 28.99, 'Plat Principal', './images/dishes/boeuf_bourguignon.png', 1),
(6, 2, 'Cordon Bleu', 'Escalope de poulet farcie au jambon et fromage', 25.99, 'Plat Principal', 'dishes//images/dishes/Cordon_bleu.png', 1),
(7, 2, 'Saumon en Papillotte', 'Saumon frais cuit en papillote aux herbes', 26.99, 'Plat Principal', 'dishes//images/dishes/Saumon_papillotte.png', 1),
(8, 3, 'Margherita Pizza', 'Sauce tomate, mozzarella fraîche et basilic', 12.99, 'Pizza', 'dishes//images/dishes/pizza.png', 1),
(9, 3, 'Pizza Quattro Formaggi', 'Mélange de quatre fromages italiens', 14.99, 'Pizza', 'dishes//images/dishes/pizza2.png', 1),
(10, 3, 'Pizza Pepperoni', 'Pepperoni, mozzarella et sauce tomate', 13.99, 'Pizza', 'dishes//images/dishes/pizza3.png', 1),
(11, 4, 'Pâtes Carbonara', 'Spaghetti à la crème, œuf et lardons', 15.99, 'Pâtes', 'dishes//images/dishes/pasta.png', 1),
(12, 4, 'Lasagne Bolognaise', 'Lasagnes traditionnelles à la sauce bolognaise', 16.99, 'Pâtes', 'dishes//images/dishes/pasta1.png', 1),
(13, 4, 'Tiramisu', 'Dessert italien au café et mascarpone', 7.99, 'Dessert', 'dishes//images/dishes//Tiramisu.png', 1),
(14, 5, 'Sushi Mix', 'Assortiment de sushis variés (12 pièces)', 22.99, 'Sushi', 'dishes//images/dishes/sushi.png', 1),
(15, 5, 'California Roll', 'Rouleaux de crabe, avocat et concombre (8 pièces)', 16.99, 'Maki', 'dishes//images/dishes/sushi1.png', 1),
(16, 5, 'Sashimi Saumon', 'Tranches de saumon frais (10 pièces)', 18.99, 'Sashimi', 'dishes//images/dishes/sushi2.png', 1),
(17, 6, 'Tempura Crevettes', 'Crevettes frites en tempura avec sauce', 17.99, 'Entrée', 'dishes//images/dishes/Tempura_Crevettes.png', 1),
(18, 6, 'Ramen Miso', 'Soupe ramen au miso avec porc chachu', 15.99, 'Plat Principal', 'dishes//images/dishes/Ramen_Miso.png', 1),
(19, 6, 'Mochi Glacé', 'Dessert japonais glacé aux fruits', 6.99, 'Dessert', 'dishes//images/dishes/Mochi_Glace.png', 1);

-- --------------------------------------------------------

--
-- Structure de la table `dish_ingredient`
--

CREATE TABLE `dish_ingredient` (
  `dish_id` int NOT NULL,
  `ingredient_id` int NOT NULL,
  `quantity` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
(5, 'Cream', 1),
(6, 'Beef', 0),
(7, 'Chicken', 0),
(8, 'Salmon', 0),
(9, 'Flour', 1),
(10, 'Eggs', 1),
(11, 'Butter', 1),
(12, 'Garlic', 0),
(13, 'Onion', 0),
(14, 'Mushrooms', 0);

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
(1, 1, 0, NULL, 8, '2024-11-19 10:55:13'),
(2, 2, 0, NULL, 8, '2024-11-19 10:55:13');

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
(1, 1, 'Menu Gastronomique', 'Notre sélection de plats raffinés', 1, '2025-02-12 10:51:39'),
(2, 1, 'Menu Dégustation', 'Une expérience culinaire unique', 1, '2025-02-12 10:51:39'),
(3, 2, 'Menu Pizza', 'Nos meilleures pizzas italiennes', 1, '2025-02-12 10:51:39'),
(4, 2, 'Menu Pasta', 'Sélection de pâtes fraîches', 1, '2025-02-12 10:51:39'),
(5, 3, 'Menu Sushi', 'Assortiment de sushis frais', 1, '2025-02-12 10:51:39'),