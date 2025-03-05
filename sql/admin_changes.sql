START TRANSACTION;

-- 1. Mise à jour des références vers l'ancien admin
UPDATE `comment` SET moderated_by = NULL WHERE moderated_by = 1;
UPDATE `like` SET moderated_by = NULL WHERE moderated_by = 1;
-- Créer d'abord le nouvel admin pour avoir son ID
INSERT INTO user (email, password_hash, username, profile_picture_url, bio, email_verified) 
VALUES ('nouvel.admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'super_admin1.jpg', 'administrateur', 1);

SET @new_admin_id = LAST_INSERT_ID();

-- Configurer les privilèges admin avant de transférer les actions
INSERT INTO admin_user (user_id, is_super_admin) VALUES (@new_admin_id, 1);
SET @new_admin_user_id = LAST_INSERT_ID();

-- Transférer les actions de modération vers le nouvel admin
UPDATE moderation_action SET admin_id = @new_admin_user_id WHERE admin_id = 1;

-- 1. Mise à jour des références vers l'ancien admin
UPDATE `moderation_action` SET admin_id = NULL WHERE admin_id = 1;
UPDATE `user` SET banned_by = NULL WHERE banned_by = 3;

-- 2. Supprimer les dépendances de l'ancien admin
DELETE FROM user_roles WHERE user_id = 3;
DELETE FROM admin_user WHERE user_id = 3;
DELETE FROM user WHERE user_id = 3;

-- 3. Créer le nouvel utilisateur admin
INSERT INTO user (
    email, 
    password_hash, 
    username, 
    profile_picture_url, 
    bio, 
    email_verified
) VALUES (
    'nouvel.admin@example.com', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- hash pour 'admin'
    'admin', 
    'super_admin1.jpg', 
    'administrateur', 
    1
);

-- 4. Récupérer l'ID du nouvel utilisateur
SET @new_admin_id = LAST_INSERT_ID();

-- 5. Attribuer le rôle admin
INSERT INTO user_roles (user_id, role_name) 
VALUES (@new_admin_id, 'admin');

-- 6. Configurer les privilèges admin
INSERT INTO admin_user (user_id, is_super_admin) 
VALUES (@new_admin_id, 1);

COMMIT;

/*
ouai je suis d'accord pour le mot de passe mais il faut bien que je puisse me connecter avec la cession admin

GitHub Copilot
Je comprends votre besoin. Pour la connexion admin, on peut utiliser un mot de passe temporaire connu mais sécurisé avec un hash. Voici la modification:
Les identifiants temporaires seront :

Email: nouvel.admin@example.com
Mot de passe: admin!
N'oubliez pas de changer ce mot de passe dès votre première connexion pour des raisons de sécurité.

admin_changes.sql
Modification du mot de passe admin avec un hash bcrypt connu.

admin_changes.sql
Les identifiants temporaires seront :

Email: nouvel.admin@example.com
Mot de passe: Admin123!
N'oubliez pas de changer ce mot de passe dès votre première connexion pour des raisons de sécurité.

que signifie le START TRANSACTION; ?

START TRANSACTION; est une commande SQL qui permet de démarrer une transaction de base de données. Voici son utilité :

Atomicité : Garantit que toutes les opérations dans la transaction sont exécutées comme une seule unité atomique

Soit toutes les opérations réussissent
Soit aucune n'est appliquée en cas d'erreur
Cohérence : Protège l'intégrité des données

Si une erreur survient pendant la suppression/création de l'admin
La base de données revient automatiquement à son état initial (rollback)
Dans notre cas avec admin_changes.sql, c'est crucial car :

On supprime l'ancien admin
On crée un nouvel admin
On met à jour plusieurs tables liées
Sans transaction, on risquerait d'avoir des données incohérentes si une erreur survient au milieu du processus.

La transaction se termine par COMMIT; qui valide tous les changements si tout s'est bien passé.

Pour annuler une transaction, on utiliserait ROLLBACK;. Cela annulerait tous les changements effectués depuis le START TRANSACTION; initial.

*/