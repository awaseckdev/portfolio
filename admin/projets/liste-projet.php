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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'supprimer' && isset($_GET['id'])) {
    $token = $_POST['csrf_token'] ?? '';
    verifierTokenCSRF($token);
    try {
        $id = (int)$_GET['id'];
        $suppr = $pdo->prepare("DELETE FROM projets WHERE id = :id");
        $suppr->execute([':id' => $id]);
        header('Location: liste-projet.php');
        exit;
    } catch (PDOException $e) {
        error_log($e->getMessage());
    }
}

try {
    $requete = $pdo->query("SELECT * FROM projets ORDER BY date_creation DESC");
    $projets = $requete->fetchAll();
} catch (PDOException $e) {
    error_log($e->getMessage());
    $projets = [];
}

$csrf_token = genererTokenCSRF();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin | Gestion des Projets</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body style="background: #f4f6f9; font-family: 'Inter', sans-serif; margin: 0; padding: 20px;">

    <div style="max-width: 1000px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; border-bottom: 2px solid #eee; padding-bottom: 15px;">
            <h2 style="color: #1a252f; margin: 0;">🛠️ Gestion des Projets</h2>
            <div>
                <a href="ajouter-projet.php" style="background: #28a745; color: white; padding: 10px 15px; border-radius: 4px; text-decoration: none; font-weight: bold; margin-right: 10px;">+ Ajouter un Projet</a>
                <a href="../dashboard.php" style="background: #6c757d; color: white; padding: 10px 15px; border-radius: 4px; text-decoration: none;">Retour Dashboard</a>
            </div>
        </div>

        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #1a252f; color: white;">
                    <th style="padding: 12px;">Titre</th>
                    <th style="padding: 12px;">Description</th>
                    <th style="padding: 12px;">Technologies</th>
                    <th style="padding: 12px; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($projets)): ?>
                    <tr>
                        <td colspan="4" style="padding: 15px; text-align: center; color: #777;">Aucun projet enregistré pour le moment.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($projets as $projet): ?>
                        <tr style="border-bottom: 1px solid #ddd;">
                            <td style="padding: 12px; font-weight: bold; color: #333;"><?= echapper($projet['titre']) ?></td>
                            <td style="padding: 12px; color: #666; max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?= echapper($projet['description']) ?></td>
                            <td style="padding: 12px;"><span style="background: #e9ecef; padding: 4px 8px; border-radius: 4px; font-size: 0.9em;"><?= echapper($projet['technologies']) ?></span></td>
                            <td style="padding: 12px; text-align: center;">
                                <a href="modifier-projet.php?id=<?= $projet['id'] ?>" style="margin-right: 10px;">Modifier</a>
                                <form action="liste-projet.php?action=supprimer&id=<?= $projet['id'] ?>" method="POST" onsubmit="return confirm('Supprimer ce projet ?');" style="display: inline;">
                                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                                    <button type="submit" style="color: #dc3545; border: none; background: none; cursor: pointer; font-weight: bold; text-decoration: underline;">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>