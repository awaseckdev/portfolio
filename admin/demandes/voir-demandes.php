<?php
<?php
require_once '../../config/connexion.php';
require_once '../verif-session.php';
require_once '../../fonctions.php';

$id = (int)$_GET['id'];

$update = $pdo->prepare("UPDATE demandes_projet SET lu = 1 WHERE id = ?");
$update->execute([$id]);

$req = $pdo->prepare("SELECT * FROM demandes_projet WHERE id = ?");
$req->execute([$id]);
$d = $req->fetch();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détail demande</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body style="padding: 20px;">
    <div style="max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
        <h2>Projet : <?= echapper($d['type_projet']) ?></h2>
        <p><strong>De :</strong> <?= echapper($d['nom']) ?> (<?= echapper($d['email']) ?>)</p>
        <p><strong>Budget estimé :</strong> <?= echapper($d['budget']) ?> CFA</p>
        <hr>
        <p style="white-space: pre-line;"><?= echapper($d['description']) ?></p>
        <br>
        <a href="index.php" style="color: #007bff; text-decoration: none;">← Retour à la liste</a>
    </div>
</body>
</html>