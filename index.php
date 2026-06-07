<?php
require_once 'config/connexion.php';
require_once 'fonctions.php';
enregistrerVisite($pdo);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio | Awa Seck</title>
    
    <link rel="stylesheet" href="css/style.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php require 'composants/navigation.php'; ?>

    <main>
        <section class="hero">
            <div class="hero-text">
                <h1>Salut, je suis <span class="highlight">Awa Seck</span></h1>
                <p class="subtitle">Développeuse Fullstack en formation</p>
                <div class="cta">
                    <a href="pages/projets.php" class="btn">Découvrir mes projets</a>
                </div>
            </div>

            <div class="hero-image">
                <img src="images/ma-photo.png" alt="Portrait Awa Seck">
            </div>
        </section>
        <section class="recherche-projets">
    <div class="container">
        <h2>Rechercher un projet</h2>
        <form action="pages/projets.php" method="GET" class="search-form">
            <input type="text" name="q" placeholder="Rechercher par titre ou description..." required>
            <button type="submit" class="btn">Rechercher</button>
        </form>
    </div>
</section>

        <section class="a-propos">
            <div class="about-content">
                <h2>À Propos de Moi</h2>
                <p>
                    Titulaire d'un <strong>Baccalauréat Scientifique en 2024</strong>, je suis actuellement étudiante en Licence 2 en <strong>Génie Logiciel et Administration Réseau</strong> à l'<strong>ESTM Dakar</strong>.
                </p>
                <p>
                    Je développe des compétences en <strong>Java, Réseau, Architecture</strong> et développement web (PHP & MySQL) pour concevoir des solutions numériques performantes.
                </p>
                
                <div style="margin-top: 30px;">
                    <a href="Mon_CV_professionnel_.pdf" target="_blank" class="btn-cv">
                        📄 Télécharger mon CV (PDF)
                    </a>
                </div>
            </div>
        </section>
    </main>

    <?php require 'composants/pied-de-page.php'; ?>


    

</body>
</html>