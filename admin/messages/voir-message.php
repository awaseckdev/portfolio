<?php
require_once '../../config/connexion.php';
require_once '../verif-session.php';
require_once '../../fonctions.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = (int)$_GET['id'];

$update = $pdo->prepare("UPDATE messages_contact SET lu = 1 WHERE id = ?");
$update->execute([$id]);

$req = $pdo->prepare("SELECT * FROM messages_contact WHERE id = ?");
$req->execute([$id]);
$message = $req->fetch();

if (!$message) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Message de <?= echapper($message['nom']) ?></title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body style="padding: 20px;">
    <div style="max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
        <h2>Message de <?= echapper($message['nom']) ?></h2>
        <p><strong>Email :</strong> <?= echapper($message['email']) ?></p>
        <p><strong>Date :</strong> <?= echapper($message['date_envoi']) ?></p>
        <hr>
        <p style="white-space: pre-line;"><?= echapper($message['message']) ?></p>
        <br>
        <a href="index.php" style="color: #007bff; text-decoration: none;">← Retour à la liste</a>
    </div>
</body>
</html>