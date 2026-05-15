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
    <?php 
    require_once '../fonctions.php'; 

    $projets = [
        [
            'titre' => 'EcoPack Sénégal',
            'description' => 'Entrepreneuriat : solutions d\'emballages écologiques pour le Sénégal.',
            'technologies' => ['Business Plan'],
            'image' => '../images/ecopack.jpeg'
        ],
        [
            'titre' => 'Répertoire Téléphonique',
            'description' => 'Gestion de contacts avec type CONTACT et base SQL.',
            'technologies' => ['Langage C', 'SQL'],
            'image' => '../images/projet-c.png'
        ],
        [
            'titre' => 'Routage IPv4',
            'description' => 'Configuration de routes statiques récursives et par défaut.',
            'technologies' => ['Réseau'],
            'image' => '../images/tech-internet.png'
        ]
    ];

    $mot_cle = nettoyer($_GET['q'] ?? '');

    $resultats = [];
    if ($mot_cle !== '') {
        foreach ($projets as $projet) {
            if (stripos($projet['titre'], $mot_cle) !== false || 
                stripos($projet['description'], $mot_cle) !== false ||
                stripos($projet['technologies'][0], $mot_cle) !== false) {
                $resultats[] = $projet;
            }
        }
    } else {
        $resultats = $projets;
    }
    ?>

    <?php require '../composants/navigation.php'; ?>

    <main class="projects-container">
        <h1>Mes <span class="highlight">Projets</span></h1>

        <section class="search-section">
            <form action="projets.php" method="GET" class="search-form">
                <label for="search-input">Filtrer les projets :</label>
                <div class="search-box">
                    <input type="search" id="search-input" name="q" placeholder="Ex: PHP, C, Réseau..." value="<?= htmlspecialchars($mot_cle) ?>">
                    <button type="submit" class="btn-search">Rechercher</button>
                </div>
            </form>
        </section>
        
        <div class="projects-grid">
            <?php foreach ($resultats as $projet) : ?>
                <div class='carte-projet'>
                    <img src='<?= htmlspecialchars($projet["image"]) ?>' 
                         alt='<?= htmlspecialchars($projet["titre"]) ?>'>
                    
                    <h3><?= htmlspecialchars($projet['titre']) ?></h3>
                    
                    <p><?= htmlspecialchars($projet['description']) ?></p>
                    
                    <div class='technologies'>
                        <?php foreach ($projet['technologies'] as $tech) : ?>
                            <span class='badge'><?= htmlspecialchars($tech) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (empty($resultats)) : ?>
                <p>Aucun projet ne correspond à ta recherche.</p>
            <?php endif; ?>
        </div>
    </main>

    <?php require '../composants/pied-de-page.php'; ?>
</body>
</html>