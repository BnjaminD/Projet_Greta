<?php
// Database configuration
define('DB_HOST', 'localhost');  // Port MAMP par défaut
define('DB_NAME', 'Application');      // Nom de la base de données du fichier .sql
define('DB_CHARSET', 'utf8mb4');
define('DB_USER', 'root');
define('DB_PASS', 'root');

// Error messages
define('ERROR_DB_CONNECTION', 'Erreur de connexion à la base de données');
define('ERROR_LOGIN_FAILED', 'Nom d\'utilisateur ou mot de passe incorrect');
define('ERROR_REQUIRED_FIELDS', 'Tous les champs sont requis');

// Session configuration
define('SESSION_LIFETIME', 3600); // 1 hour
define('SESSION_NAME', 'secure_session');
?>
