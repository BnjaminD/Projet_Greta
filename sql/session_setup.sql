
-- Structure de la table `user_session`
CREATE TABLE `user_session` (
  `session_id` varchar(255) NOT NULL,
  `user_id` int NOT NULL,
  `last_activity` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`session_id`),
  KEY `fk_session_user` (`user_id`),
  CONSTRAINT `fk_session_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Procédure pour nettoyer les sessions expirées (plus de 15 minutes d'inactivité)
DELIMITER //
CREATE PROCEDURE `cleanup_expired_sessions`()
BEGIN
    DELETE FROM `user_session` 
    WHERE `last_activity` < NOW() - INTERVAL 15 MINUTE;
END //
DELIMITER ;

-- Événement pour exécuter le nettoyage automatiquement toutes les 5 minutes
CREATE EVENT `session_cleanup_event`
ON SCHEDULE EVERY 5 MINUTE
DO CALL cleanup_expired_sessions();