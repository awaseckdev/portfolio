<?php
session_start();
require_once '../config/connexion.php';
require_once '../fonctions.php';

if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit();
}

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifierTokenCSRF($_POST['csrf_token'] ?? '');

    $email = nettoyer($_POST['email'] ?? '');
    $mot_de_passe = $_POST['password'] ?? '';

    if (filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($mot_de_passe)) {
        try {
            $requete = $pdo->prepare("SELECT * FROM administrateurs WHERE email = :email");
            $requete->execute([':email' => $email]);
            $admin = $requete->fetch();

            if ($admin && password_verify($mot_de_passe, $admin['mot_de_passe'])) {
                session_regenerate_id(true);
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_prenom'] = $admin['prenom'];
                
                header('Location: dashboard.php');
                exit;
            } else {
                $erreur = "Identifiants incorrects.";
            }
        } catch (PDOException $e) {
            error_log("Erreur de connexion admin : " . $e->getMessage());
            $erreur = "Une erreur technique est survenue.";
        }
    } else {
        $erreur = "Identifiants incorrects.";
    }
}

$token = genererTokenCSRF();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Connexion</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <main style="max-width: 400px; margin: 100px auto; padding: 30px; background: #1a252f; border-radius: 8px; color: white; box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
        <h2 style="text-align: center; color: #ffcc00; margin-bottom: 25px;">Connexion Admin</h2>
        
        <?php if (!empty($erreur)): ?>
            <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 20px; font-size: 0.9em; border: 1px solid #f5c6cb;">
                <?= echapper($erreur) ?>
            </div>
        <?php endif; ?>

        <form action="connexion.php" method="POST" style="display: flex; flex-direction: column; gap: 20px;">
            <input type="hidden" name="csrf_token" value="<?= $token ?>">

            <div style="display: flex; flex-direction: column; gap: 8px;">
                <label style="font-weight: bold; font-size: 0.9em;">Adresse Email</label>
                <input type="email" name="email" required style="padding: 12px; border-radius: 4px; border: 1px solid #ccc; font-size: 1em;">
            </div>

            <div style="display: flex; flex-direction: column; gap: 8px;">
                <label style="font-weight: bold; font-size: 0.9em;">Mot de passe</label>
                <input type="password" name="password" required style="padding: 12px; border-radius: 4px; border: 1px solid #ccc; font-size: 1em;">
            </div>

            <button type="submit" style="background: #ffcc00; color: #1a252f; padding: 12px; border: none; border-radius: 4px; font-weight: bold; font-size: 1em; cursor: pointer; transition: background 0.2s; margin-top: 10px;">
                Se connecter
            </button>
        </form>
    </main>
</body>
</html>