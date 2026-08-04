<?php
require_once('form/config.php');
require_once("form/database.php");

if (!function_exists('redirect')) {
    function redirect($url) {
        header('Location: ' . $url);
        exit;
    }
}

/*
 * SIMULATION DE PAIEMENT
 * -----------------------
 * Aucune vraie transaction bancaire n'est effectuée ici. On valide juste
 * le format des champs, puis on "simule" un résultat de paiement.
 *
 * Hypothèse de schéma (à adapter selon ta base) :
 *
 * CREATE TABLE reservations (
 *   id_reservation INT AUTO_INCREMENT PRIMARY KEY,
 *   id_vols        INT NOT NULL,
 *   nom            VARCHAR(60) NOT NULL,
 *   prenom         VARCHAR(60) NOT NULL,
 *   email          VARCHAR(120) NOT NULL,
 *   nb_personnes   INT NOT NULL,
 *   montant_total  DECIMAL(10,2) NOT NULL,
 *   reference      VARCHAR(20) NOT NULL,
 *   statut         VARCHAR(20) NOT NULL DEFAULT 'payee',
 *   date_reservation DATETIME NOT NULL,
 *   FOREIGN KEY (id_vols) REFERENCES vols(id_vols)
 * );
 *
 * Si ta table s'appelle différemment, adapte simplement la requête INSERT
 * plus bas.
 */

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect("planning.php");
}

$idvol        = (int)($_POST['id_vols'] ?? 0);
$iduser       = (int)($_POST['id_user'] ?? 0);
$nbPersonnes  = max(1, min(5, (int)($_POST['nb_personnes'] ?? 1)));
$montantTotal = (float)($_POST['montant_total'] ?? 0);

if ($montantTotal <= 0 && $idvol) {
    $stmt = $pdo->prepare("SELECT prix FROM vols WHERE id_vols = ?");
    $stmt->execute([$idvol]);
    $prixVol = $stmt->fetchColumn();
    if ($prixVol !== false) {
        $montantTotal = (float)$prixVol * $nbPersonnes;
    }
}

$nom       = trim($_POST['nom'] ?? '');
$prenom    = trim($_POST['prenom'] ?? '');
$email     = trim($_POST['email'] ?? '');
$nomCarte  = trim($_POST['nom_carte'] ?? '');
$numCarte  = preg_replace('/\s+/', '', $_POST['numero_carte'] ?? '');
$expiration = trim($_POST['expiration'] ?? '');
$cvv       = trim($_POST['cvv'] ?? '');

$erreurs = [];

if (!$idvol && !$iduser) {
    $erreurs[] = "Vol introuvable.";
}
if ($nom === '' || $prenom === '') {
    $erreurs[] = "Le nom et le prénom sont obligatoires.";
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $erreurs[] = "L'adresse e-mail est invalide.";
}
if ($nomCarte === '') {
    $erreurs[] = "Le nom sur la carte est obligatoire.";
}
if (!preg_match('/^[0-9]{13,19}$/', $numCarte)) {
    $erreurs[] = "Le numéro de carte est invalide.";
}
if (!preg_match('/^(0[1-9]|1[0-2])\/[0-9]{2}$/', $expiration)) {
    $erreurs[] = "La date d'expiration est invalide (format MM/AA).";
} else {
    // Vérifie que la carte simulée n'est pas expirée
    [$mois, $annee] = explode('/', $expiration);
    $anneeComplete = 2000 + (int)$annee;
    $finValidite = mktime(0, 0, 0, (int)$mois + 1, 1, $anneeComplete) - 1;
    if ($finValidite < time()) {
        $erreurs[] = "La carte a expiré.";
    }
}
if (!preg_match('/^[0-9]{3,4}$/', $cvv)) {
    $erreurs[] = "Le CVV est invalide.";
}

if (!empty($erreurs)) {
    $_SESSION['paiement_erreur'] = implode(' ', $erreurs);
    redirect("paiement.php?id=" . $idvol . "&nb=" . $nbPersonnes);
}

// Vérifie que le vol existe toujours et récupère le nombre de places
$stmt = $pdo->prepare("SELECT * FROM vols WHERE id_vols = ?");
$stmt->execute([$idvol]);
$vol = $stmt->fetch();

if (!$vol) {
    $_SESSION['paiement_erreur'] = "Ce vol n'existe plus.";
    redirect("planning.php");
}

if ((int)$vol['places_dispo'] < $nbPersonnes) {
    $_SESSION['paiement_erreur'] = "Il ne reste plus assez de places disponibles pour ce vol.";
    redirect("detail.php?id=" . $idvol);
}

/*
 * SIMULATION DU RÉSULTAT DE PAIEMENT
 * Règle simple pour pouvoir tester les deux scénarios :
 * une carte se terminant par "0000" est refusée, toutes les autres sont acceptées.
 */
$paiementAccepte = substr($numCarte, -4) !== '0000';

if (!$paiementAccepte) {
    $_SESSION['paiement_erreur'] = "Le paiement a été refusé par la banque. Vérifiez vos informations ou essayez une autre carte.";
    redirect("paiement.php?id=" . $idvol . "&nb=" . $nbPersonnes);
}

// Génère une référence de réservation simulée
$reference = 'RV-' . strtoupper(bin2hex(random_bytes(4)));

try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS reservations (
        id_reservation INT AUTO_INCREMENT PRIMARY KEY,
        id_vols INT NOT NULL,
        nom VARCHAR(60) NOT NULL,
        prenom VARCHAR(60) NOT NULL,
        email VARCHAR(120) NOT NULL,
        nb_personnes INT NOT NULL,
        montant_total DECIMAL(10,2) NOT NULL,
        reference VARCHAR(30) NOT NULL,
        statut VARCHAR(20) NOT NULL DEFAULT 'payee',
        date_reservation DATETIME NOT NULL,
        KEY idx_id_vols (id_vols)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $pdo->beginTransaction();

    $stmt = $pdo->prepare("INSERT INTO reservations
        (id_vols, id_user, nom, prenom, email, nb_personnes, montant_total, reference, statut, date_reservation)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'payee', NOW())");
    $stmt->execute([$idvol, $iduser, $nom, $prenom, $email, $nbPersonnes, $montantTotal, $reference]);

    $stmt = $pdo->prepare("UPDATE vols SET places_dispo = places_dispo - ? WHERE id_vols = ?");
    $stmt->execute([$nbPersonnes, $idvol]);

    $pdo->commit();
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    $_SESSION['paiement_erreur'] = "Erreur technique : " . $e->getMessage();
    redirect("paiement.php?id=" . $idvol . "&nb=" . $nbPersonnes);
}

// Stocke le récapitulatif pour la page de confirmation
$_SESSION['confirmation'] = [
    'reference'     => $reference,
    'nom'           => $nom,
    'prenom'        => $prenom,
    'email'         => $email,
    'nb_personnes'  => $nbPersonnes,
    'montant_total' => $montantTotal,
    'id_vols'       => $idvol,
    'id_user'       => $iduser,
];

redirect("confirmation.php");
