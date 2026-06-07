<?php
require_once '../../config/connexion.php';
require_once '../verif-session.php';
require_once '../../fonctions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'supprimer' && isset($_GET['id'])) {
    verifierTokenCSRF($_POST['csrf_token'] ?? '');
    $id = (int)$_GET['id'];
    $suppr = $pdo->prepare("DELETE FROM demandes_projet WHERE id = :id");
    $suppr->execute([':id' => $id]);
    header('Location: index.php');
    exit;
}

$demandes   = $pdo->query("SELECT * FROM demandes_projet ORDER BY date_demande DESC")->fetchAll();
$csrf_token = genererTokenCSRF();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin | Gérer les Demandes</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body style="background: #f4f6f9; font-family: 'Inter', sans-serif; padding: 20px;">

    <div style="max-width: 900px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; border-bottom: 2px solid #eee; padding-bottom: 15px;">
            <h2 style="color: #1a252f; margin: 0;">💼 Demandes de Projet</h2>
            <a href="../dashboard.php" style="background: #6c757d; color: white; padding: 10px 15px; border-radius: 4px; text-decoration: none;">Retour</a>
        </div>

        <?php if (empty($demandes)): ?>
            <p style="text-align: center; color: #777;">Aucune demande pour le moment.</p>
        <?php else: ?>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #1a252f; color: white;">
                        <th style="padding: 12px; text-align: left;">Client</th>
                        <th style="padding: 12px; text-align: left;">Projet</th>
                        <th style="padding: 12px; text-align: left;">Budget</th>
                        <th style="padding: 12px; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($demandes as $d):
                        $style = ($d['lu'] == 0) ? 'background-color: #eef2f7; font-weight: bold;' : '';
                    ?>
                        <tr style="border-bottom: 1px solid #ddd; <?= $style ?>">
                            <td style="padding: 12px;">
                                <strong><?= echapper($d['nom']) ?></strong><br>
                                <small><a href="mailto:<?= echapper($d['email']) ?>"><?= echapper($d['email']) ?></a></small>
                            </td>
                            <td style="padding: 12px;">
                                <a href="voir-demandes.php?id=<?= $d['id'] ?>" style="text-decoration: none; color: inherit;">
                                    <?= echapper($d['type_projet']) ?>
                                </a>
                            </td>
                            <td style="padding: 12px;"><strong><?= echapper($d['budget']) ?> FCFA</strong></td>
                            <td style="padding: 12px; text-align: center;">
                                <form action="index.php?action=supprimer&id=<?= $d['id'] ?>" method="POST" onsubmit="return confirm('Supprimer cette demande ?');" style="display: inline;">
                                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                                    <button type="submit" style="color: #dc3545; border: none; background: none; cursor: pointer; font-weight: bold; text-decoration: underline;">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>