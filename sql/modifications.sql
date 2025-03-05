
-- 1. Table pour les horaires spécifiques des restaurants
CREATE TABLE `restaurant_hours` (
  `hours_id` int NOT NULL AUTO_INCREMENT,
  `restaurant_id` int NOT NULL,
  `day_of_week` tinyint NOT NULL COMMENT '0=Dimanche, 1=Lundi, etc.',
  `opening_time` time,
  `closing_time` time,
  `is_closed` boolean DEFAULT false,
  PRIMARY KEY (`hours_id`),
  KEY `fk_hours_restaurant` (`restaurant_id`),
  CONSTRAINT `fk_hours_restaurant` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`restaurant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- 2. Table pour les promotions
CREATE TABLE `promotion` (
  `promotion_id` int NOT NULL AUTO_INCREMENT,
  `restaurant_id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `discount_percent` decimal(5,2),
  `discount_amount` decimal(10,2),
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `conditions` text,
  `is_active` boolean DEFAULT true,
  PRIMARY KEY (`promotion_id`),
  KEY `fk_promotion_restaurant` (`restaurant_id`),
  CONSTRAINT `fk_promotion_restaurant` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`restaurant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- 3. Tables pour la catégorisation des restaurants
CREATE TABLE `cuisine_category` (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `restaurant_category` (
  `restaurant_id` int NOT NULL,
  `category_id` int NOT NULL,
  PRIMARY KEY (`restaurant_id`, `category_id`),
  CONSTRAINT `fk_rest_cat_restaurant` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`restaurant_id`),
  CONSTRAINT `fk_rest_cat_category` FOREIGN KEY (`category_id`) REFERENCES `cuisine_category` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- 5. Table pour les périodes de pointe
CREATE TABLE `peak_hours` (
  `peak_id` int NOT NULL AUTO_INCREMENT,
  `restaurant_id` int NOT NULL,
  `day_of_week` tinyint NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `surge_multiplier` decimal(3,2) DEFAULT 1.00,
  PRIMARY KEY (`peak_id`),
  KEY `fk_peak_restaurant` (`restaurant_id`),
  CONSTRAINT `fk_peak_restaurant` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`restaurant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Mise à jour des AUTO_INCREMENT
ALTER TABLE `restaurant_hours` AUTO_INCREMENT = 1;
ALTER TABLE `promotion` AUTO_INCREMENT = 1;
ALTER TABLE `cuisine_category` AUTO_INCREMENT = 1;
ALTER TABLE `peak_hours` AUTO_INCREMENT = 1;

-- Ajout des index nécessaires
CREATE INDEX `idx_restaurant_hours_day` ON `restaurant_hours` (`day_of_week`);
CREATE INDEX `idx_promotion_dates` ON `promotion` (`start_date`, `end_date`);
CREATE INDEX `idx_category_name` ON `cuisine_category` (`name`);
CREATE INDEX `idx_peak_hours_day` ON `peak_hours` (`day_of_week`);