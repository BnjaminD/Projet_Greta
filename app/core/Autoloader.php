
<?php
spl_autoload_register(function ($class) {
    // Convertir le namespace en chemin de fichier
    $prefix = 'app\\';
    $base_dir = dirname(__DIR__) . '/';
    
    // Vérifier si la classe utilise le préfixe
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    // Obtenir le chemin relatif de la classe
    $relative_class = substr($class, $len);
    
    // Remplacer les séparateurs de namespace par des séparateurs de répertoire
    // et ajouter .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    // Si le fichier existe, l'inclure
    if (file_exists($file)) {
        require $file;
    }
});