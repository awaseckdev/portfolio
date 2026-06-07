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

if (!isset($_GET['id'])) {
    header('Location: liste-projet.php');
    exit;
}

$id = (int)$_GET['id'];
$projet = $pdo->prepare("SELECT * FROM projets WHERE id = ?");
$projet->execute([$id]);
$p = $projet->fetch();

if (!$p) {
    header('Location: liste-projet.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifierTokenCSRF($_POST['csrf_token'] ?? '');

    $titre      = nettoyer($_POST['titre'] ?? '');
    $desc       = nettoyer($_POST['description'] ?? '');
    $tech       = nettoyer($_POST['technologies'] ?? '');
    $lien       = nettoyer($_POST['lien'] ?? '');
    $image_nom  = $p['image'];

    if (!empty($_FILES['image']['name'])) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
            $nouveau_nom = uniqid('proj_') . '.' . $ext;
            if (move_uploaded_file($_FILES['image']['tmp_name'], '../../images/projets/' . $nouveau_nom)) {
                if (!empty($image_nom) && file_exists('../../images/projets/' . $image_nom)) {
                    unlink('../../images/projets/' . $image_nom);
                }
                $image_nom = $nouveau_nom;
            }
        }
    }

    $sql = "UPDATE projets SET titre=?, description=?, technologies=?, lien=?, image=? WHERE id=?";
    $pdo->prepare($sql)->execute([$titre, $desc, $tech, $lien, $image_nom, $id]);
    header('Location: liste-projet.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un Projet</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body style="background: #f4f6f9; font-family: 'Inter', sans-serif; padding: 20px;">

    <div style="max-width: 600px; margin: 40px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
        <h2 style="color: #1a252f; margin-top: 0; border-bottom: 2px solid #eee; padding-bottom: 15px;">✏️ Modifier le projet</h2>

        <form method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 15px;">
            <input type="hidden" name="csrf_token" value="<?= genererTokenCSRF() ?>">

            <label>Titre *</label>
            <input type="text" name="titre" value="<?= echapper($p['titre']) ?>" required style="padding: 10px; border: 1px solid #ccc; border-radius: 4px;">

            <label>Description *</label>
            <textarea name="description" required style="padding: 10px; border: 1px solid #ccc; border-radius: 4px; min-height: 100px;"><?= echapper($p['description']) ?></textarea>

            <label>Technologies *</label>
            <input type="text" name="technologies" value="<?= echapper($p['technologies']) ?>" required style="padding: 10px; border: 1px solid #ccc; border-radius: 4px;">

            <label>Image actuelle</label>
            <?php if (!empty($p['image'])): ?>
                <img src="../../images/projets/<?= echapper($p['image']) ?>" width="100" style="border-radius: 4px;">
            <?php endif; ?>
            <input type="file" name="image" style="padding: 4px;">

            <label>Lien du projet (optionnel)</label>
            <input type="text" name="lien" value="<?= echapper($p['lien']) ?>" style="padding: 10px; border: 1px solid #ccc; border-radius: 4px;">

            <div style="display: flex; gap: 10px; margin-top: 10px;">
                <button type="submit" style="background: #007bff; color: white; padding: 12px 20px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Enregistrer les modifications</button>
                <a href="liste-projet.php" style="background: #6c757d; color: white; padding: 12px 20px; border-radius: 4px; text-decoration: none; font-weight: bold;">Annuler</a>
            </div>
        </form>
    </div>

</body>
</html>