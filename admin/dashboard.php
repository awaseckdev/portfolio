<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header('Location: connexion.php');
    exit;
}

require_once '../config/connexion.php';
require_once '../fonctions.php';

$nbProjets         = $pdo->query("SELECT COUNT(*) FROM projets")->fetchColumn();
$nbMsgNonLus       = $pdo->query("SELECT COUNT(*) FROM messages_contact WHERE lu = 0")->fetchColumn();
$nbDemandesNonLues = $pdo->query("SELECT COUNT(*) FROM demandes_projet WHERE lu = 0")->fetchColumn();

$messages = $pdo->query("SELECT * FROM messages_contact ORDER BY date_envoi DESC LIMIT 5")->fetchAll();
$devis    = $pdo->query("SELECT * FROM demandes_projet ORDER BY date_demande DESC LIMIT 5")->fetchAll();
$visites  = $pdo->query("SELECT adresse_ip, page, date_visite FROM visites ORDER BY date_visite DESC LIMIT 5")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Tableau de Bord</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body style="background-color: #f4f6f9; color: #333; font-family: 'Inter', sans-serif; padding: 20px;">

    <header style="background: #1a252f; color: white; padding: 20px; border-radius: 8px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h1>Tableau de Bord de <span style="color: #ffcc00;"><?= echapper($_SESSION['admin_prenom']) ?></span></h1>
        <a href="deconnexion.php" style="background: #e74c3c; color: white; padding: 10px 15px; border-radius: 4px; text-decoration: none; font-weight: bold;">Déconnexion</a>
    </header>

    <main style="display: flex; flex-direction: column; gap: 30px;">

        <div style="display: flex; gap: 20px; justify-content: center;">
            <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); text-align: center; flex: 1;">
                <h3 style="margin-top: 0; color: #7f8c8d;">📂 Projets publiés</h3>
                <p style="font-size: 2em; font-weight: bold; margin: 10px 0; color: #2c3e50;"><?= $nbProjets ?></p>
            </div>
            <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); text-align: center; flex: 1;">
                <h3 style="margin-top: 0; color: #7f8c8d;">💬 Messages non lus</h3>
                <p style="font-size: 2em; font-weight: bold; margin: 10px 0; color: #e74c3c;"><?= $nbMsgNonLus ?></p>
            </div>
            <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); text-align: center; flex: 1;">
                <h3 style="margin-top: 0; color: #7f8c8d;">💼 Demandes non lues</h3>
                <p style="font-size: 2em; font-weight: bold; margin: 10px 0; color: #e67e22;"><?= $nbDemandesNonLues ?></p>
            </div>
        </div>

        <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
            <h2 style="color: #2c3e50; border-bottom: 2px solid #ecf0f1; padding-bottom: 10px;">💬 5 Derniers Messages</h2>
            <?php foreach ($messages as $msg): ?>
                <div style="background: #f8f9fa; padding: 10px; margin-bottom: 10px; border-left: 4px solid <?= $msg['lu'] == 0 ? '#e74c3c' : '#ffcc00' ?>;">
                    <strong><?= echapper($msg['nom']) ?></strong> — <small><?= echapper($msg['date_envoi']) ?></small>
                    <?php if ($msg['lu'] == 0): ?>
                        <span style="color:#e74c3c; font-size:0.8em; margin-left:8px;">● non lu</span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
            <h2 style="color: #2c3e50; border-bottom: 2px solid #ecf0f1; padding-bottom: 10px;">💼 5 Dernières demandes de projet</h2>
            <?php foreach ($devis as $d): ?>
                <div style="background: #f8f9fa; padding: 10px; margin-bottom: 10px; border-left: 4px solid #3498db;">
                    <strong><?= echapper($d['nom']) ?></strong> (<?= echapper($d['type_projet']) ?>)
                    <?php if ($d['lu'] == 0): ?>
                        <span style="color:#e67e22; font-size:0.8em; margin-left:8px;">● non lu</span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
            <h2 style="color: #2c3e50; border-bottom: 2px solid #ecf0f1; padding-bottom: 10px;">🌍 5 Dernières visites</h2>
            <ul>
                <?php foreach ($visites as $v): ?>
                    <li>IP : <?= echapper($v['adresse_ip']) ?> | Page : <?= echapper($v['page']) ?> | <?= echapper($v['date_visite']) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

    </main>
</body>
</html>