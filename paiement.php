<?php
require_once('form/config.php');
require_once("form/database.php");


$pagestyle = false;
$infolder = false;

$idvol = (int)($_GET['id'] ?? $_POST['id_vols'] ?? 0);
$iduser = (int)($_POST['id_user']);
$nbPersonnes = max(1, min(5, (int)($_GET['nb'] ?? $_POST['nb_personnes'] ?? 1)));

if (!$idvol && !$iduser) {
    redirect("planning.php");
}

$stmt = $pdo->prepare("SELECT *, c.nom AS nom_compagnie, c.code_compagnie AS code_compagnie
FROM vols v
JOIN compagnie c ON v.id_compagnie = c.id_compagnie
WHERE v.id_vols = ?");
$stmt->execute([$idvol]);
$vols = $stmt->fetchAll();

if (!$vols) {
    redirect("planning.php");
}

$vol = $vols[0];
$prixUnitaire = (float)$vol['prix'];
$montantTotal = $prixUnitaire * $nbPersonnes;

// Erreur éventuelle transmise par traitement_paiement.php en cas d'échec simulé
$erreur = $_SESSION['paiement_erreur'] ?? null;
unset($_SESSION['paiement_erreur']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReserVols | Paiement</title>
    <link rel="stylesheet" href="styl.css">
</head>
<body>
    <?php require_once('header.php') ?>

    <section class="hero py-5">
        <div class="container py-4">
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= SITE_URL ?>/index.php">Accueil</a></li>
                    <li class="breadcrumb-item"><a href="<?= SITE_URL ?>/vol.php">Vols</a></li>
                    <li class="breadcrumb-item"><a href="<?= SITE_URL ?>/detail.php?id=<?= $idvol ?>">Détail du vol</a></li>
                    <li class="breadcrumb-item active">Paiement</li>
                </ol>
            </nav>
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-6 fw-bold mb-3">Paiement de votre réservation</h1>
                    <p class="lead mb-0">Simulation de paiement sécurisé — aucune transaction réelle n'est effectuée.</p>
                </div>
            </div>
        </div>
    </section>

    <div class="container py-5">

        <?php if ($erreur): ?>
        <div class="alert alert-danger rounded-4 mb-4" role="alert">
            <?= htmlspecialchars($erreur) ?>
        </div>
        <?php endif; ?>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="hero-card p-4 p-lg-5">
                    <h4 class="fw-bold mb-4">Informations de paiement</h4>

                    <div class="alert alert-info rounded-4 mb-4">
                        Ceci est une <strong>simulation</strong> de paiement à des fins de démonstration.
                        N'entrez jamais de véritables données de carte bancaire ici.
                    </div>

                    <form action="traitement_paiement.php" method="POST" id="formPaiement" novalidate>
                        <input type="hidden" name="id_vols" value="<?= $idvol ?>">
                        <input type="hidden" name="id_user" value="<?= $iduser ?>">
                        <input type="hidden" name="nb_personnes" value="<?= $nbPersonnes ?>">
                        <input type="hidden" name="montant_total" value="<?= htmlspecialchars($montantTotal) ?>">

                        <h6 class="fw-bold mb-3">Titulaire de la réservation</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label small text-muted">Nom</label>
                                <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($_POST['nom'] ?? $_SESSION['nom'] ?? '') ?>" required maxlength="60">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-muted">Prénom</label>
                                <input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($_POST['prenom'] ?? $_SESSION['prenom'] ?? '') ?>" required maxlength="60">
                            </div>
                            <div class="col-12">
                                <label class="form-label small text-muted">Adresse e-mail</label>
                                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($_POST['email'] ?? $_SESSION['email'] ?? '') ?>" required maxlength="120">
                            </div>
                        </div>

                        <h6 class="fw-bold mb-3">Carte bancaire (simulation)</h6>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label small text-muted">Nom sur la carte</label>
                                <input type="text" name="nom_carte" class="form-control" required maxlength="80">
                            </div>
                            <div class="col-12">
                                <label class="form-label small text-muted">Numéro de carte</label>
                                <input type="text" name="numero_carte" class="form-control" required
                                       inputmode="numeric" autocomplete="off"
                                       placeholder="4242 4242 4242 4242" maxlength="19" pattern="[0-9 ]{13,19}">
                            </div>
                            <div class="col-6 col-md-4">
                                <label class="form-label small text-muted">Expiration (MM/AA)</label>
                                <input type="text" name="expiration" class="form-control" required
                                       placeholder="MM/AA" maxlength="5" pattern="(0[1-9]|1[0-2])\/[0-9]{2}">
                            </div>
                            <div class="col-6 col-md-4">
                                <label class="form-label small text-muted">CVV</label>
                                <input type="text" name="cvv" class="form-control" required
                                       inputmode="numeric" autocomplete="off" maxlength="4" pattern="[0-9]{3,4}">
                            </div>
                        </div>

                        <div class="form-check mt-4 mb-4">
                            <input class="form-check-input" type="checkbox" id="cgv" required>
                            <label class="form-check-label small" for="cgv">
                                J'accepte les conditions générales de vente
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary-custom btn-lg rounded-pill w-100">
                            Payer <?= number_format($montantTotal, 0, ',', ' ') ?> €
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="summary-card p-4 sticky-top" style="top: 90px;">
                    <h4 class="fw-bold mb-3">Récapitulatif</h4>
                    <ul class="list-unstyled mb-4">
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Trajet</span>
                            <span class="fw-semibold"><?= htmlspecialchars($vol['ville_depart']) ?> → <?= htmlspecialchars($vol['ville_arrivee']) ?></span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Date</span>
                            <span class="fw-semibold"><?= date('d/m/Y', strtotime($vol['date_depart'])) ?></span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Compagnie</span>
                            <span class="fw-semibold"><?= htmlspecialchars($vol['nom_compagnie']) ?></span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Personnes</span>
                            <span class="fw-semibold"><?= $nbPersonnes ?></span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Prix unitaire</span>
                            <span class="fw-semibold"><?= $prixUnitaire?> €</span>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-muted">Total</span>
                            <span class="fw-semibold text-accent fs-5"><?= number_format($montantTotal, 0, ',', ' ') ?> €</span>
                        </li>
                    </ul>
                    <a href="detail.php?id=<?= $idvol ?>" class="btn btn-outline-custom rounded-pill w-100">Retour au détail du vol</a>
                </div>
            </div>
        </div>
    </div>

    <?php require_once("footer.php") ?>
</body>
</html>
