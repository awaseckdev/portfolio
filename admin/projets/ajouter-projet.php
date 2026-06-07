<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../connexion.php');
    exit;
}

require_once '../../config/connexion.php';
require_once '../../fonctions.php';

$erreur  = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    verifierTokenCSRF($token);

    $titre       = nettoyer($_POST['titre'] ?? '');
    $description = nettoyer($_POST['description'] ?? '');
    $technologies = nettoyer($_POST['technologies'] ?? '');
    $lien        = nettoyer($_POST['lien'] ?? '');
    $image_nom   = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
            $nom = uniqid('proj_') . '.' . $ext;
            if (move_uploaded_file($_FILES['image']['tmp_name'], '../../images/projets/' . $nom)) {
                $image_nom = $nom;
            } else {
                $erreur = "Erreur lors du transfert de l'image.";
            }
        } else {
            $erreur = "Format d'image invalide. Formats acceptés : jpg, jpeg, png, webp, gif.";
        }
    }

    if (empty($erreur)) {
        $req = $pdo->prepare("INSERT INTO projets (titre, description, technologies, lien, image) VALUES (?, ?, ?, ?, ?)");
        $req->execute([$titre, $description, $technologies, $lien, $image_nom]);
        $success = true;
    }
}

$csrf_token = genererTokenCSRF();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin | Ajouter un Projet</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body style="background: #f4f6f9; font-family: 'Inter', sans-serif; padding: 20px;">

    <div style="max-width: 600px; margin: 40px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">

        <h2 style="color: #1a252f; margin-top: 0; border-bottom: 2px solid #eee; padding-bottom: 15px;">➕ Ajouter un nouveau projet</h2>

        <?php if ($success): ?>
            <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 20px;">
                Projet ajouté avec succès ! <a href="liste-projet.php" style="color: #155724; font-weight: bold;">Voir la liste</a>
            </div>
        <?php endif; ?>

        <?php if (!empty($erreur)): ?>
            <div style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 4px; margin-bottom: 20px;">
                <?= echapper($erreur) ?>
            </div>
        <?php endif; ?>

        <form action="ajouter-projet.php" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 15px;">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

            <label>Titre du projet *</label>
            <input type="text" name="titre" required style="padding: 10px; border: 1px solid #ccc; border-radius: 4px;">

            <label>Description *</label>
            <textarea name="description" required style="padding: 10px; border: 1px solid #ccc; border-radius: 4px; min-height: 100px;"></textarea>

            <label>Technologies utilisées *</label>
            <input type="text" name="technologies" required style="padding: 10px; border: 1px solid #ccc; border-radius: 4px;">

            <label>Image du projet (jpg, png, webp, gif — optionnel)</label>
            <input type="file" name="image">

            <label>Lien du projet (optionnel)</label>
            <input type="text" name="lien" style="padding: 10px; border: 1px solid #ccc; border-radius: 4px;">

            <button type="submit" style="background: #28a745; color: white; padding: 12px; border: none; border-radius: 4px; font-weight: bold; font-size: 1em; cursor: pointer; margin-top: 10px;">Enregistrer le projet</button>
        </form>
    </div>

</body>
</html>