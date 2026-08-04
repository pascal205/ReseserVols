<?php
require_once('form/config.php');
require_once("form/database.php");

$pagestyle = false;
$infolder = false;

$idvol = (int)($_GET['id'] ?? 0);

if (!$idvol) {
    redirect("vol.php"); 
}

$stmt = $pdo->prepare("SELECT *, c.nom AS nom_compagnie, c.code_compagnie AS code_compagnie
FROM vols v
JOIN compagnie c ON v.id_compagnie = c.id_compagnie
WHERE v.id_vols = ?");
$stmt->execute([$idvol]);
$vol = $stmt->fetch();

if (!$vol) {
    redirect("vol.php");
}

$hasProfile = !empty($_SESSION['prenom']) || !empty($_SESSION['nom']) || !empty($_SESSION['email']) || !empty($_SESSION['telephone']);
$iduser = $_SESSION['user_id'] ?? 0;
$prenom = $_SESSION['prenom'] ?? '';
$nom = $_SESSION['nom'] ?? '';
$email = $_SESSION['email'] ?? '';
$telephone = $_SESSION['telephone'] ?? '';

$activepage = ' ';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReserVols | Réservation</title>
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
                    <li class="breadcrumb-item active">Réservation</li>
                </ol>
            </nav>
            <h1 class="display-6 fw-bold mb-2">Finalisez votre réservation</h1>
            <p class="lead mb-0">Nous utiliserons vos informations de profil déjà enregistrées pour cette réservation.</p>
        </div>
    </section>

    <div class="container py-5">
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="hero-card p-4 p-lg-5">
                    <h3 class="fw-bold mb-4">Informations du voyageur</h3>
                    <form action="paiement.php" method="post">
                        <input type="hidden" name="id_vols" value="<?= (int)$idvol ?>">
                        <input type="hidden" name="id_user" value="<?= (int)$iduser ?>">
                        <input type="hidden" name="nb_personnes" value="1">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Prénom</label>
                                <input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($prenom) ?>" <?= $hasProfile ? 'readonly' : '' ?>>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nom</label>
                                <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($nom) ?>" <?= $hasProfile ? 'readonly' : '' ?>>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>" <?= $hasProfile ? 'readonly' : '' ?>>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Téléphone</label>
                                <input type="tel" name="telephone" class="form-control" value="<?= htmlspecialchars($telephone) ?>" <?= $hasProfile ? 'readonly' : '' ?>>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Adresse</label>
                                <input type="text" class="form-control" placeholder="Votre adresse">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Passeport / CIN</label>
                                <input type="text" class="form-control" placeholder="Numéro d'identité">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Classe</label>
                                <select class="form-select">
                                    <option>Économie</option>
                                    <option>Affaires</option>
                                    <option>Première</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary-custom px-4">Poursuivre vers le paiement</button>
                            <a href="detail.php?id=<?= $idvol ?>" class="btn btn-outline-custom px-4">Retour</a>
                        </div>
                        <div class="alert alert-info mb-0 rounded-4 mt-4">
                            <i class="fas fa-info-circle me-2"></i> Assurez-vous que toutes les informations sont correctes avant de confirmer.
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="summary-card p-4 pb-2">
                    <h4 class="fw-bold mb-3">Votre sélection</h4>
                    <div class="p-3 rounded-4 bg-light mb-3">
                        <div class="fw-semibold fs-5"><?= htmlspecialchars($vol['ville_depart']) ?> → <?= htmlspecialchars($vol['ville_arrivee']) ?></div>
                        <div class="text-muted small mt-1"><?= htmlspecialchars($vol['nom_compagnie']) ?> · <?= htmlspecialchars($vol['code_compagnie']) ?></div>
                    </div>
                    <ul class="list-unstyled mb-4">
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Date</span>
                            <span class="fw-semibold"><?= date('d/m/Y', strtotime($vol['date_depart'])) ?></span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Heure</span>
                            <span class="fw-semibold"><?= date('H:i', strtotime($vol['heure_depart'])) ?></span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Places dispo.</span>
                            <span class="fw-semibold"><?= (int)$vol['places_dispo'] ?></span>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-muted">Prix</span>
                            <span class="fw-semibold text-accent"><?= number_format((float)$vol['prix'], 0, ',', ' ') ?> €</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <?php require_once("footer.php") ?>
</body>
</html>
