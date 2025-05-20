<?php
// Correction du chemin d'inclusion pour un fichier placé dans le dossier app
require_once __DIR__ . '/core/functions.php';  // Utilisez un chemin relatif depuis l'emplacement actuel

// Vérifier un utilisateur spécifique (remplacez par le nom d'utilisateur que vous voulez tester)
$username = 'marc';

echo "<h2>Informations sur l'utilisateur: $username</h2>";
$results = app\core\testUser($username);

if (is_string($results)) {
    echo "<p style='color:red'>$results</p>";
} else {
    echo "<pre>";
    print_r($results);
    echo "</pre>";
    
    // Tester le mot de passe
    $testPassword = 'testtest'; // Remplacez par le mot de passe que vous voulez tester
    echo "<h3>Test du mot de passe: $testPassword</h3>";
    $hash = $results['password_hash'];
    $verify = password_verify($testPassword, $hash);
    
    echo "<p>Hash stocké: $hash</p>";
    echo "<p>Résultat de password_verify: " . ($verify ? "<span style='color:green'>VALIDE</span>" : "<span style='color:red'>INVALIDE</span>") . "</p>";
    
    // Info sur le hash
    $info = password_get_info($hash);
    echo "<h3>Informations sur le hash:</h3>";
    echo "<pre>";
    print_r($info);
    echo "</pre>";
}