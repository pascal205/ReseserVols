<?php
require_once('form/config.php');
require_once("form/database.php");

if (!function_exists('redirect')) {
    function redirect($url) {
        header('Location: ' . $url);
        exit;
    }
}

$pagestyle = false;
$infolder = false;

$confirmation = $_SESSION['confirmation'] ?? null;
unset($_SESSION['confirmation']);

if (!$confirmation) {
    redirect("planning.php");
}

$stmt = $pdo->prepare("SELECT *, c.nom AS nom_compagnie, c.code_compagnie AS code_compagnie
FROM vols v
JOIN compagnie c ON v.id_compagnie = c.id_compagnie
WHERE v.id_vols = ?");
$stmt->execute([$confirmation['id_vols']]);
$vol = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReserVols | Confirmation de réservation</title>
    <link rel="stylesheet" href="styl.css">
</head>
<body>
    <?php require_once('header.php') ?>

    <section class="hero py-5">
        <div class="container py-4 text-center">
            <span class="badge rounded-pill bg-white text-dark px-3 py-2 fs-6 mb-3">Paiement accepté</span>
            <h1 class="display-6 fw-bold mb-3">Réservation confirmée !</h1>
            <p class="lead mb-0">Merci <?= htmlspecialchars($confirmation['prenom']) ?>, votre paiement (simulé) a bien été traité.</p>
        </div>
    </section>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="hero-card p-4 p-lg-5 text-center mb-4">
                    <div class="fs-1 mb-2">✅</div>
                    <h4 class="fw-bold mb-1">Référence de réservation</h4>
                    <div class="fs-3 fw-bold text-accent mb-4"><?= htmlspecialchars($confirmation['reference']) ?></div>
                    <p class="text-muted mb-0">
                        Un e-mail de confirmation a été envoyé (simulation) à
                        <strong><?= htmlspecialchars($confirmation['email']) ?></strong>.
                    </p>
                </div>

                <?php if ($vol): ?>
                <div class="summary-card p-4">
                    <h4 class="fw-bold mb-3">Détails de votre vol</h4>
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
                            <span class="text-muted">Horaires</span>
                            <span class="fw-semibold"><?= date('H:i', strtotime($vol['heure_depart'])) ?> → <?= date('H:i', strtotime($vol['heure_arrivee'])) ?></span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Compagnie</span>
                            <span class="fw-semibold"><?= htmlspecialchars($vol['nom_compagnie']) ?></span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Passager</span>
                            <span class="fw-semibold"><?= htmlspecialchars($confirmation['prenom'] . ' ' . $confirmation['nom']) ?></span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Nombre de personnes</span>
                            <span class="fw-semibold"><?= (int)$confirmation['nb_personnes'] ?></span>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-muted">Montant payé</span>
                            <span class="fw-semibold text-accent fs-5"><?= number_format((float)$confirmation['montant_total'], 0, ',', ' ') ?> €</span>
                        </li>
                    </ul>
                </div>
                <?php endif; ?>

                <div class="d-grid gap-2 mt-4">
                    <a href="vol.php" class="btn btn-primary-custom btn-lg rounded-pill">Réserver un autre vol</a>
                    <a href="index.php" class="btn btn-outline-custom rounded-pill">Retour à l'accueil</a>
                </div>
            </div>
        </div>
    </div>

    <?php require_once("footer.php") ?>
</body>
</html>
