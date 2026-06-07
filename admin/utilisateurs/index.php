<?php
require_once '../../config/connexion.php';
require_once '../verif-session.php';
require_once '../../fonctions.php';

$erreur  = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'supprimer' && isset($_GET['id'])) {
    verifierTokenCSRF($_POST['csrf_token'] ?? '');
    $id_del = (int)$_GET['id'];
    if ($id_del === (int)$_SESSION['admin_id']) {
        $erreur = "Vous ne pouvez pas supprimer votre propre compte.";
    } else {
        try {
            $pdo->prepare("DELETE FROM administrateurs WHERE id = :id")->execute([':id' => $id_del]);
            $success = "Administrateur supprimé avec succès.";
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $erreur = "Erreur lors de la suppression.";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter_admin'])) {
    verifierTokenCSRF($_POST['csrf_token'] ?? '');
    $prenom = nettoyer($_POST['prenom'] ?? '');
    $nom    = nettoyer($_POST['nom'] ?? '');
    $email  = nettoyer($_POST['email'] ?? '');
    $mdp    = $_POST['mot_de_passe'] ?? '';

    if (!empty($prenom) && !empty($nom) && filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($mdp)) {
        try {
            $verif = $pdo->prepare("SELECT id FROM administrateurs WHERE email = :email");
            $verif->execute([':email' => $email]);
            if ($verif->fetch()) {
                $erreur = "Cet email est déjà utilisé.";
            } else {
                $hash = password_hash($mdp, PASSWORD_BCRYPT);
                $pdo->prepare("INSERT INTO administrateurs (prenom, nom, email, mot_de_passe) VALUES (?, ?, ?, ?)")
                    ->execute([$prenom, $nom, $email, $hash]);
                $success = "Nouvel administrateur ajouté avec succès.";
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $erreur = "Erreur lors de l'ajout.";
        }
    } else {
        $erreur = "Veuillez remplir tous les champs.";
    }
}

$csrf_token = genererTokenCSRF();
$admins     = $pdo->query("SELECT id, prenom, nom, email, date_creation FROM administrateurs ORDER BY date_creation DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin | Gestion Administrateurs</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body style="background: #f4f6f9; font-family: 'Inter', sans-serif; padding: 20px;">

    <div style="max-width: 900px; margin: 40px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">

        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #eee; padding-bottom: 15px; margin-bottom: 20px;">
            <h2 style="color: #1a252f; margin: 0;">👥 Gestion des Administrateurs</h2>
            <a href="../dashboard.php" style="background: #6c757d; color: white; padding: 10px 15px; border-radius: 4px; text-decoration: none;">← Dashboard</a>
        </div>

        <?php if (!empty($success)): ?>
            <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 20px;"><?= echapper($success) ?></div>
        <?php endif; ?>
        <?php if (!empty($erreur)): ?>
            <div style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 4px; margin-bottom: 20px;"><?= echapper($erreur) ?></div>
        <?php endif; ?>

        <h3 style="color: #333; margin-top: 0;">➕ Ajouter un administrateur</h3>
        <form action="index.php" method="POST" style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 30px;">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
            <input type="hidden" name="ajouter_admin" value="1">
            <div>
                <label>Prénom *</label><br>
                <input type="text" name="prenom" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; margin-top: 4px;">
            </div>
            <div>
                <label>Nom *</label><br>
                <input type="text" name="nom" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; margin-top: 4px;">
            </div>
            <div>
                <label>Email *</label><br>
                <input type="email" name="email" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; margin-top: 4px;">
            </div>
            <div>
                <label>Mot de passe *</label><br>
                <input type="password" name="mot_de_passe" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; margin-top: 4px;">
            </div>
            <div style="grid-column: 1 / -1;">
                <button type="submit" style="background: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Créer l'administrateur</button>
            </div>
        </form>

        <h3 style="color: #333;">📜 Liste des administrateurs</h3>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #1a252f; color: white;">
                    <th style="padding: 10px; text-align: left;">Prénom Nom</th>
                    <th style="padding: 10px; text-align: left;">Email</th>
                    <th style="padding: 10px; text-align: left;">Créé le</th>
                    <th style="padding: 10px; text-align: center;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($admins as $admin): ?>
                <tr style="border-bottom: 1px solid #dee2e6;">
                    <td style="padding: 10px;"><?= echapper($admin['prenom']) ?> <?= echapper($admin['nom']) ?></td>
                    <td style="padding: 10px;"><?= echapper($admin['email']) ?></td>
                    <td style="padding: 10px;"><?= echapper($admin['date_creation']) ?></td>
                    <td style="padding: 10px; text-align: center;">
                        <?php if ((int)$admin['id'] !== (int)$_SESSION['admin_id']): ?>
                            <form action="index.php?action=supprimer&id=<?= $admin['id'] ?>" method="POST" onsubmit="return confirm('Supprimer cet administrateur ?');" style="display: inline;">
                                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                                <button type="submit" style="color: #dc3545; border: none; background: none; cursor: pointer; text-decoration: underline; font-weight: bold;">Supprimer</button>
                            </form>
                        <?php else: ?>
                            <span style="color: #6c757d; font-style: italic;">Vous</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>