
-- Mise à jour des chemins d'images de profil pour les utilisateurs existants
UPDATE `user` 
SET `profile_picture_url` = CASE user_id
    WHEN 1 THEN 'image/profile/user1.png'
    WHEN 2 THEN 'image/profile/user2.png'
    WHEN 3 THEN 'image/profile/admin.png'
    WHEN 17 THEN 'image/profile/super_admin1.png'
    WHEN 18 THEN 'image/profile/super_admin.png'
    ELSE 'image/profile/default.png'
END
WHERE user_id IN (1, 2, 3, 17, 18) 
OR profile_picture_url IS NULL;

-- Création d'un trigger pour définir une image par défaut pour les nouveaux utilisateurs
DELIMITER //
CREATE TRIGGER set_default_profile_picture
BEFORE INSERT ON `user`
FOR EACH ROW
BEGIN
    IF NEW.profile_picture_url IS NULL THEN
        SET NEW.profile_picture_url = 'image/profile/default.jpg';
    END IF;
END//
DELIMITER ;