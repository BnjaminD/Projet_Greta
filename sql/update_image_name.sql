
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
(6, 2, 'Cordon Bleu', 'Escalope de poulet farcie au jambon et fromage', 25.99, 'dishes//images/dishes/Cordon_bleu.png', 1),