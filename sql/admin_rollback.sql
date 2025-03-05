START TRANSACTION;

-- 1. Supprimer le nouvel admin
DELETE FROM moderation_action WHERE admin_id = (SELECT admin_id FROM admin_user WHERE user_id = @new_admin_id);
DELETE FROM admin_user WHERE user_id = @new_admin_id;
DELETE FROM user_roles WHERE user_id = @new_admin_id;
DELETE FROM user WHERE user_id = @new_admin_id;

-- 2. Restaurer l'ancien admin
INSERT INTO user (
    user_id,
    email,
    password_hash,
    username,
    profile_picture_url,
    bio,
    email_verified
) VALUES (
    3,
    'ancien.admin@example.com',
    '$2y$10$ancien_hash',  -- remplacer par l'ancien hash
    'ancien_admin',
    'ancien_admin.jpg',
    'administrateur original',
    1
);

-- 3. Restaurer les privilèges de l'ancien admin
INSERT INTO user_roles (user_id, role_name) VALUES (3, 'admin');
INSERT INTO admin_user (user_id, is_super_admin) VALUES (3, 1);

-- Stocker l'ID admin généré
SET @restored_admin_id = LAST_INSERT_ID();

-- 4. Restaurer les références vers l'ancien admin
UPDATE moderation_action SET admin_id = @restored_admin_id WHERE admin_id IS NULL;
UPDATE comment SET moderated_by = @restored_admin_id WHERE moderated_by IS NULL;
UPDATE `like` SET moderated_by = @restored_admin_id WHERE moderated_by IS NULL;
UPDATE user SET banned_by = 3 WHERE banned_by IS NULL;

COMMIT;