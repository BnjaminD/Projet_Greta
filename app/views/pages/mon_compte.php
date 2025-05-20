<?php
session_start();
// Correction des chemins de fichiers
require_once dirname(__DIR__, 2) . '/autoload.php';
// Directement inclure le fichier functions.php
require_once dirname(__DIR__, 2) . '/core/functions.php';

// Utiliser les classes et fonctions du core avec l'espace de noms correct
use app\core\Database;

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: connexionV2.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$success_message = '';
$error_message = '';

// Obtenir une instance de la base de données
$db = Database::getInstance();

// Traitement du formulaire de mise à jour
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Traitement de la photo de profil
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['profile_picture']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            if (in_array($ext, $allowed)) {
                // Create absolute path to ensure files are saved in the correct location
                $target_dir = dirname(__DIR__, 2) . "/images/profile/";
                
                // Ensure the directory exists
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0755, true);
                }
                
                $new_filename = uniqid() . "." . $ext;
                $target_file = $target_dir . $new_filename;

                if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
                    // Store only the filename in the database, not the path
                    $stmt = $db->prepare("UPDATE user SET profile_picture_url = ? WHERE user_id = ?");
                    $stmt->execute([$new_filename, $user_id]);
                    $success_message = "Photo de profil mise à jour avec succès";
                } else {
                    $error_message = "Erreur lors de l'upload du fichier";
                }
            } else {
                $error_message = "Type de fichier non autorisé";
            }
        }
        // Traitement des informations du profil
        else if (isset($_POST['username']) && isset($_POST['email'])) {
            $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $bio = !empty($_POST['bio']) ? htmlspecialchars($_POST['bio'], ENT_QUOTES, 'UTF-8') : '';
            
            if (empty($username) || empty($email)) {
                $error_message = "Le nom d'utilisateur et l'email sont requis";
            } else {
                // Traitement du mot de passe si fourni
                if (!empty($_POST['current_password'])) {
                    $current_password = $_POST['current_password'] ?? '';
                    $new_password = $_POST['new_password'] ?? '';
                    $confirm_password = $_POST['confirm_password'] ?? '';

                    $stmt = $db->prepare("SELECT password_hash FROM user WHERE user_id = ?");
                    $stmt->execute([$user_id]);
                    $user = $stmt->fetch();
                    
                    if (password_verify($current_password, $user['password_hash'])) {
                        if (!empty($new_password) && !empty($confirm_password)) {
                            if ($new_password === $confirm_password) {
                                $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
                                $stmt = $db->prepare("UPDATE user SET password_hash = ? WHERE user_id = ?");
                                $stmt->execute([$password_hash, $user_id]);
                                $success_message = "Mot de passe mis à jour avec succès";
                            } else {
                                $error_message = "Les nouveaux mots de passe ne correspondent pas";
                            }
                        }
                    } else {
                        $error_message = "Mot de passe actuel incorrect";
                    }
                }

                // Mise à jour des informations du profil
                if (empty($error_message)) {
                    $stmt = $db->prepare("UPDATE user SET username = ?, email = ?, bio = ? WHERE user_id = ?");
                    if ($stmt->execute([$username, $email, $bio, $user_id])) {
                        $success_message = "Profil mis à jour avec succès";
                    }
                }
            }
        }

    } catch (\PDOException $e) {
        $error_message = "Erreur lors de la mise à jour du profil: " . $e->getMessage();
    }
}

// Récupération des informations actuelles de l'utilisateur
try {
    $stmt = $db->prepare("SELECT * FROM user WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(\PDO::FETCH_ASSOC);
} catch (\PDOException $e) {
    $error_message = "Erreur lors de la récupération des informations";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Compte</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <style>
        input[type="file"] {
            color: #000; /* Texte en noir */
            background: #fff; /* Fond blanc */
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
            width: 100%;
        }
        input[type="file"]::file-selector-button {
            background: #007bff;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 10px;
        }
        input[type="file"]::file-selector-button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body class="mon-compte">
    <?php include dirname(__DIR__) . '/includes/header.php'; ?>

    <main class="container">
        <h1>Mon Compte</h1>
        
        <?php if ($success_message): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <div class="account-sections">
            <!-- Section Photo de profil -->
            <form method="POST" enctype="multipart/form-data" class="account-form">
                <h2>Photo de profil</h2>
                <div class="form-group">
                    <div class="profile-preview">
                        <?php if (!empty($user['profile_picture_url'])): ?>
                            <img src="../../images/profile/<?php echo htmlspecialchars($user['profile_picture_url']); ?>" alt="Photo de profil" class="profile-image">
                        <?php else: ?>
                            <img src="../../assets/images/default-profile.png" alt="Photo de profil par défaut" class="profile-image">
                        <?php endif; ?>
                    </div>
                    <label for="profile_picture">Changer votre photo de profil</label>
                    <input type="file" id="profile_picture" name="profile_picture" accept="image/*">
                </div>
                <button type="submit" class="btn btn-primary">Mettre à jour la photo</button>
            </form>

            <!-- Section Informations du compte -->
            <form method="POST" class="account-form">
                <h2>Informations du compte</h2>
                <div class="form-group">
                    <label for="username">Nom d'utilisateur</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="bio">Biographie</label>
                    <textarea id="bio" name="bio" rows="4"><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Mettre à jour les informations</button>
            </form>

            <!-- Section Changement de mot de passe -->
            <form method="POST" class="account-form">
                <h2>Changer le mot de passe</h2>
                <div class="form-group">
                    <label for="current_password">Mot de passe actuel</label>
                    <input type="password" id="current_password" name="current_password">
                </div>
                <div class="form-group">
                    <label for="new_password">Nouveau mot de passe</label>
                    <input type="password" id="new_password" name="new_password">
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirmer le mot de passe</label>
                    <input type="password" id="confirm_password" name="confirm_password">
                </div>
                <button type="submit" class="btn btn-primary">Changer le mot de passe</button>
            </form>
        </div>
    </main>
    <script src="../../assets/js/scripts.js"></script>
</body>

</html>