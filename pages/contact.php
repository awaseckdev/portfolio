<?php 
require_once '../fonctions.php'; 

$erreurs = [];
$erreurs_devis = [];
$success = false;
$recapitulatif = null;

$nom_contact = $email_contact = $message_contact = '';
$nom_devis = $email_devis = $budget_devis = ''; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (isset($_POST['send_message'])) {
        $nom_contact = nettoyer($_POST['name'] ?? '');
        $email_contact = nettoyer($_POST['email'] ?? '');
        $message_contact = nettoyer($_POST['message'] ?? '');

        if (!champ_requis($nom_contact)) $erreurs['nom'] = 'Le nom est obligatoire.';
        if (!filter_var($email_contact, FILTER_VALIDATE_EMAIL)) $erreurs['email'] = 'L\'adresse e-mail est invalide.';
        if (!champ_requis($message_contact)) $erreurs['message'] = 'Le message ne peut pas être vide.';

        if (empty($erreurs)) {
            $success = true;
            $nom_contact = $email_contact = $message_contact = '';
        }
    }

    if (isset($_POST['request_quote'])) {
        $demande = [
            'nom'    => nettoyer($_POST['name_devis'] ?? ''),
            'email'  => nettoyer($_POST['email_devis'] ?? ''),
            'type'   => nettoyer($_POST['project-type'] ?? ''),
            'budget' => nettoyer($_POST['budget'] ?? '')
        ];

        if (!champ_requis($demande['nom'])) $erreurs_devis['nom'] = 'Le nom est requis.';
        if (!filter_var($demande['email'], FILTER_VALIDATE_EMAIL)) $erreurs_devis['email'] = 'Email invalide.';
        if (!champ_requis($demande['budget'])) $erreurs_devis['budget'] = 'Veuillez indiquer un budget.';

        if (empty($erreurs_devis)) {
            $recapitulatif = $demande;
            $nom_devis = $email_devis = $budget_devis = ''; 
        } else {
            $nom_devis = $demande['nom'];
            $email_devis = $demande['email'];
            $budget_devis = $demande['budget'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio | Me Contacter</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>

    <?php require '../composants/navigation.php'; ?>

    <main class="contact-container">
        <h1>Me <span class="highlight">Contacter</span></h1>
        
        <?php if ($success): ?>
            <div style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; text-align: center; margin-bottom: 20px; border: 1px solid #c3e6cb;">
                Merci pour votre message, je vous répondrai bientôt !
            </div>
        <?php endif; ?> 

        <section class="contact-content">
            <h2>📧 Envoyez un message</h2>
            <form class="contact-form" action="#" method="POST">
                <div class="form-group">
                    <label for="name">Nom complet</label>
                    <input type="text" id="name" name="name" placeholder="Votre nom" value="<?php echo $nom_contact; ?>" >
                    <?php if (isset($erreurs['nom'])): ?>
                        <span style="color:#f8d7da; font-size: 0.9em; display: block; margin-top: 5px;"><?php echo $erreurs['nom']; ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="email">Adresse Email</label>
                    <input type="email" id="email" name="email" placeholder="votre@email.com" value="<?php echo $email_contact; ?>" >
                    <?php if (isset($erreurs['email'])): ?>
                        <span style="color:#f8d7da; font-size: 0.9em; display: block; margin-top: 5px;"><?php echo $erreurs['email']; ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="5" placeholder="Votre message ici..."><?php echo $message_contact; ?></textarea>
                    <?php if (isset($erreurs['message'])): ?>
                        <span style="color:#f8d7da; font-size: 0.9em; display: block; margin-top: 5px;"><?php echo $erreurs['message']; ?></span>
                    <?php endif; ?>
                </div>

                <button type="submit" name="send_message" class="btn">Envoyer le message</button>
            </form>
        </section>

        <section class="contact-content">
            <h2>💼 Demander un devis</h2>
            
            <?php if ($recapitulatif): ?>
                <div style="background-color: #1a252f; border: 1px solid #ffcc00; padding: 15px; border-radius: 5px; margin-bottom: 20px; color: white;">
                    <p style="color: #ffcc00; font-weight: bold; margin-top: 0;">✅ Récapitulatif :</p>
                    Nom : <?php echo $recapitulatif['nom']; ?><br>
                    Projet : <?php echo $recapitulatif['type']; ?><br>
                    Budget : <?php echo $recapitulatif['budget']; ?> FCFA
                </div>
            <?php endif; ?>

            <form class="contact-form" action="#" method="POST">
                <div class="form-group">
                    <label>Nom complet</label>
                    <input type="text" name="name_devis" value="<?php echo $nom_devis; ?>">
                    <?php if (isset($erreurs_devis['nom'])): ?>
                        <span style="color:#f8d7da; font-size: 0.8em;"><?php echo $erreurs_devis['nom']; ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label>Adresse Email</label>
                    <input type="email" name="email_devis" value="<?php echo $email_devis; ?>">
                    <?php if (isset($erreurs_devis['email'])): ?>
                        <span style="color:#f8d7da; font-size: 0.8em;"><?php echo $erreurs_devis['email']; ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="project-type">Type de projet</label>
                    <select id="project-type" name="project-type">
                        <option value="web">Développement Site Web</option>
                        <option value="c-lang">Application en Langage C</option>
                        <option value="network">Infrastructure Réseau</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="budget">Budget estimé (FCFA)</label>
                    <input type="text" id="budget" name="budget" placeholder="Ex: 50 000" value="<?php echo $budget_devis; ?>">
                    <?php if (isset($erreurs_devis['budget'])): ?>
                        <span style="color:#f8d7da; font-size: 0.8em;"><?php echo $erreurs_devis['budget']; ?></span>
                    <?php endif; ?>
                </div>

                <button type="submit" name="request_quote" class="btn">Demander l'estimation</button>
            </form>
        </section>
    </main>

    <?php require '../composants/pied-de-page.php'; ?>

</body>
</html>