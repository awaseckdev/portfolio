<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function champ_requis(string $valeur): bool {
    return !empty(trim($valeur));
}

function nettoyer(string $valeur): string {
    return trim($valeur);
}

function echapper(string $valeur): string {
    return htmlspecialchars($valeur, ENT_QUOTES, 'UTF-8');
}

function genererTokenCSRF(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifierTokenCSRF(string $tokenSoumis): bool {
    if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $tokenSoumis)) {
        die("Erreur de sécurité : Jeton CSRF invalide ou expiré.");
    }
    return true;
}

function enregistrerVisite($pdo) {
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $adresse_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $adresse_ip = $_SERVER['REMOTE_ADDR'];
    }

    $page = $_SERVER['PHP_SELF'];

    try {
        $requete = $pdo->prepare("INSERT INTO visites (adresse_ip, page) VALUES (:adresse_ip, :page)");
        $requete->execute([
            ':adresse_ip' => $adresse_ip,
            ':page'       => $page
        ]);
    } catch (PDOException $e) {
        error_log("Impossible d'enregistrer la visite : " . $e->getMessage());
    }
}