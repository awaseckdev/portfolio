<?php require_once '../fonctions.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio | Compétences</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>

    <?php require '../composants/navigation.php'; ?>

    <main class="skills-container">
        <h1>Mes <span class="highlight">Compétences</span></h1>
        
        <div class="skills-grid">
            <div class="skill-card">
                <h3>HTML5</h3>
                <p>Structure de sites web sémantique et propre.</p>
            </div>
            <div class="skill-card">
                <h3>CSS3</h3>
                <p>Mise en page moderne avec Flexbox et Grid.</p>
            </div>
            <div class="skill-card">
                <h3>PHP</h3>
                <p>Développement de fonctionnalités côté serveur.</p>
            </div>
            <div class="skill-card">
                <h3>MySQL</h3>
                <p>Gestion et manipulation de bases de données.</p>
            </div>
        </div>

        <section class="soft-skills">
            <h2>Ma progression</h2>
            <p>Actuellement en formation avec Monsieur Diouf, j'apprends à coder avec rigueur et méthode[cite: 4, 67].</p>
        </section>
    </main>

    <?php require '../composants/pied-de-page.php'; ?>
    

</body>
</html>