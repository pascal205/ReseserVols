<?php
require_once('form/config.php');
require_once("form/database.php");


$pagestyle = false;
$infolder = false;

$idvol = (int)($_GET['id'] ?? 0);

if (!$idvol) {
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
$villeDep = $vol['ville_depart'];
$villeAv = $vol['ville_arrivee'];

$stmt = $pdo->prepare("SELECT * FROM aeroport WHERE ville = ?");
$stmt->execute([$villeDep]);
$aeroport_departs = $stmt->fetchAll();

$stmt = $pdo->prepare("SELECT * FROM aeroport WHERE ville = ?");
$stmt->execute([$villeAv]);
$aeroport_arrivee = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReserVols | Détail du vol</title>
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
                    <li class="breadcrumb-item active">Détail du vol</li>
                </ol>
            </nav>
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-6 fw-bold mb-3">Vérifiez les détails de votre vol</h1>
                    <p class="lead mb-0">Consultez les horaires, l’aéroport de départ et d’arrivée, puis réservez votre billet sans perdre de temps.</p>
                </div>
                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                    <span class="badge rounded-pill bg-white text-dark px-3 py-2 fs-6">Places disponibles : <?= (int)$vol['places_dispo'] ?></span>
                </div>
            </div>
        </div>
    </section>

    <div class="container py-5">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="hero-card p-4 p-lg-5">
                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
                        <div>
                            <h2 class="fw-bold mb-1"><?= htmlspecialchars($vol['ville_depart']) ?> → <?= htmlspecialchars($vol['ville_arrivee']) ?></h2>
                            <p class="text-muted mb-0">Vol proposé par <?= htmlspecialchars($vol['nom_compagnie']) ?> · Code <?= htmlspecialchars($vol['code_compagnie']) ?></p>
                        </div>
                        <span class="badge rounded-pill info-pill px-3 py-2 fw-semibold">Direct</span>
                    </div>

                    <div class="row align-items-center text-center text-md-start g-3 mb-4">
                        <div class="col-md-4">
                            <div class="fs-2 fw-bold"><?= date('H:i', strtotime($vol['heure_depart'])) ?></div>
                            <div class="text-muted">Départ · <?= htmlspecialchars($vol['ville_depart']) ?></div>
                        </div>
                        <div class="col-md-4">
                            <div class="route-line my-3"></div>
                            <div class="small text-muted">Durée estimée : 1h20</div>
                        </div>
                        <div class="col-md-4">
                            <div class="fs-2 fw-bold"><?= date('H:i', strtotime($vol['heure_arrivee'])) ?></div>
                            <div class="text-muted">Arrivée · <?= htmlspecialchars($vol['ville_arrivee']) ?></div>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="p-3 rounded-4 bg-light">
                                <div class="small text-muted">Date</div>
                                <div class="fw-semibold"><?= date('d/m/Y', strtotime($vol['date_depart'])) ?></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 rounded-4 bg-light">
                                <div class="small text-muted">Prix</div>
                                <div class="fw-semibold text-accent"><?= number_format((float)$vol['prix'], 0, ',', ' ') ?> €</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 rounded-4 bg-light">
                                <div class="small text-muted">Nombre de personnes</div>
                                <select class="form-select mt-2">
                                    <option value="1">1 personne</option>
                                    <option value="2">2 personnes</option>
                                    <option value="3">3 personnes</option>
                                    <option value="4">4 personnes</option>
                                    <option value="5">5 personnes</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="border rounded-4 p-4 bg-light">
                        <h4 class="fw-bold mb-4">Aéroports du trajet</h4>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="p-3 rounded-4 bg-white border h-100">
                                    <div class="small text-muted mb-2">Départ</div>
                                    <div class="fw-semibold">
                                        <?= htmlspecialchars(!empty($aeroport_departs) ? $aeroport_departs[0]['nom'] . ' (' . $aeroport_departs[0]['code_aeroport'] . ')' : 'Aéroport non renseigné') ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 rounded-4 bg-white border h-100">
                                    <div class="small text-muted mb-2">Arrivée</div>
                                    <div class="fw-semibold">
                                        <?= htmlspecialchars(!empty($aeroport_arrivee) ? $aeroport_arrivee[0]['nom'] . ' (' . $aeroport_arrivee[0]['code_aeroport'] . ')' : 'Aéroport non renseigné') ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="summary-card p-4 sticky-top" style="top: 90px;">
                    <h4 class="fw-bold mb-3">Résumé du vol</h4>
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
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-muted">Prix</span>
                            <span class="fw-semibold text-accent"><?= number_format((float)$vol['prix'], 0, ',', ' ') ?> €</span>
                        </li>
                    </ul>
                    <div class="d-grid gap-2">
                        <a href="reservation.php?id=<?= $idvol ?>" class="btn btn-primary-custom btn-lg rounded-pill">Réserver maintenant</a>
                        <a href="vol.php" class="btn btn-outline-custom rounded-pill">Retour aux vols</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once("footer.php") ?>
</body>
</html>