<?php
require_once '../config/connexion.php';
require_once '../fonctions.php';
enregistrerVisite($pdo);

$mot_cle  = nettoyer($_GET['q'] ?? '');
$resultats = [];

try {
    if ($mot_cle !== '') {
        $requete = $pdo->prepare("SELECT * FROM projets WHERE titre LIKE :titre OR description LIKE :desc OR technologies LIKE :tech ORDER BY date_creation DESC");
        $param = '%' . $mot_cle . '%';
        $requete->execute([
            ':titre' => $param,
            ':desc'  => $param,
            ':tech'  => $param
        ]);
        $resultats = $requete->fetchAll();
    } else {
        $requete  = $pdo->query("SELECT * FROM projets ORDER BY date_creation DESC");
        $resultats = $requete->fetchAll();
    }
} catch (PDOException $e) {
    error_log($e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio | Mes Projets</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>

    <?php require '../composants/navigation.php'; ?>

    <main class="projects-container">
        <h1>Mes <span class="highlight">Projets</span></h1>

        <section class="search-section">
            <form action="projets.php" method="GET" class="search-form">
                <label for="search-input">Filtrer les projets :</label>
                <div class="search-box">
                    <input type="search" id="search-input" name="q" placeholder="Ex: PHP, C, Réseau..." value="<?= echapper($mot_cle) ?>">
                    <button type="submit" class="btn-search">Rechercher</button>
                </div>
            </form>
        </section>

        <div class="projects-grid">
            <?php if (empty($resultats)): ?>
                <p style="grid-column: 1 / -1; text-align: center; color: #777; margin-top: 20px; font-size: 1.2em;">
                    Aucun projet ne correspond à votre recherche pour : <strong><?= echapper($mot_cle) ?></strong>.
                </p>
            <?php else: ?>
                <?php foreach ($resultats as $projet):
                    $image_projet = (!empty($projet['image'])) ? '../images/projets/' . $projet['image'] : '../images/tech-internet.png';
                ?>
                    <div class='carte-projet'>
                        <img src='<?= echapper($image_projet) ?>' alt='<?= echapper($projet["titre"]) ?>'>
                        <h3><?= echapper($projet['titre']) ?></h3>
                        <p><?= echapper($projet['description']) ?></p>
                        <div class='technologies'>
                            <span class='badge'><?= echapper($projet['technologies']) ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <?php require '../composants/pied-de-page.php'; ?>
</body>
</html>