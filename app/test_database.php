<?php
// test_connection.php
require_once 'database.php';

try {
    $db = connectDB();
    echo "Connexion réussie!";
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage();
}
?>