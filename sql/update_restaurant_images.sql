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

UPDATE restaurant 
SET image_url = CASE name
    WHEN 'The Fancy Fork' THEN 'Fancy_fork.jpg'
    WHEN 'Pizza Paradise' THEN 'Pizza_paradise.jpg'
    WHEN 'Sushi World' THEN 'sushi_world.jpg'
END
WHERE name IN ('The Fancy Fork', 'Pizza Paradise', 'Sushi World');