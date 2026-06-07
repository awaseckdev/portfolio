<?php
require_once '../../config/connexion.php';
require_once '../verif-session.php';
require_once '../../fonctions.php';

$messages = $pdo->query("SELECT * FROM messages_contact ORDER BY date_envoi DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin | Messages</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body style="padding: 20px; font-family: sans-serif;">

    <h2>📩 Boîte de réception</h2>

    <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <thead>
            <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                <th style="padding: 10px; text-align: left;">Nom</th>
                <th style="padding: 10px; text-align: left;">Aperçu du message</th>
                <th style="padding: 10px; text-align: left;">Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($messages as $msg):
                $style = ($msg['lu'] == 0) ? 'background-color: #eef2f7; font-weight: bold;' : '';
            ?>
                <tr style="border-bottom: 1px solid #dee2e6; <?= $style ?>">
                    <td style="padding: 10px;"><?= echapper($msg['nom']) ?></td>
                    <td style="padding: 10px;">
                        <a href="voir-message.php?id=<?= $msg['id'] ?>" style="text-decoration: none; color: #007bff;">
                            <?= echapper(mb_substr($msg['message'], 0, 60)) ?>...
                        </a>
                    </td>
                    <td style="padding: 10px;"><?= echapper($msg['date_envoi']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <br><a href="../dashboard.php" style="color: #007bff; text-decoration: none;">← Retour au dashboard</a>
</body>
</html>