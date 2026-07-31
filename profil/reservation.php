<?php
require "require.php";
$activemenu = 'reservation';

$reservationstmt = $pdo->prepare("SELECT v.date_depart, v.date_arrivee, v.heure_depart, v.heure_arrivee, v.ville_depart, v.ville_arrivee, v.prix, c.nom AS nom_compagnie, c.code_compagnie, ad.nom AS nom_adepart, ad.code_aeroport AS code_ad, aa.nom AS nom_aarivee, aa.code_aeroport AS code_aa, r.nb_personnes, r.reference, r.date_reservation, r.statut
                                    FROM vols v, compagnie c, aeroport ad, aeroport aa, reservations r, user
                                    WHERE r.id_user = user.id
                                    AND r.id_vols = v.id_vols
                                    AND v.id_compagnie = c.id_compagnie
                                    AND v.id_aeroport_depart = ad.id_aeroport
                                    AND v.id_aeroport_arrivee = aa.id_aeroport
                                    AND user.id = ?");
$reservationstmt->execute([$userId]);
$inforeserv = $reservationstmt->fetchAll();
if (!$inforeserv) {
    redirect('profil/dashboard.php');
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes réservations</title>
    <link rel="stylesheet" href="../styl.css">
</head>
<body>
    <?php require_once('../header.php'); ?>

    <div class="container py-4 pt-5 mt-8">
        <div class="row g-4 align-items-start">
            <div class="col-lg-auto">
                <?php require "slidebar.php"; ?>
            </div>
            <?php foreach ($inforeserv as $reservation): ?>
            <div class="col-lg-9 col-xl-7">
                <div class="reservation-card hero-card p-4 p-md-5">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
                        <div>
                            <p class="text-muted mb-1">Référence de réservation</p>
                            <h3 class="fw-bold text-accent mb-0"><?= htmlspecialchars($reservation['reference']); ?></h3>
                        </div>
                        <span class="reservation-badge"><?= ($reservation['statut'] === "payee") ? "Confirmée" : "non confirmée" ?></span>
                    </div>

                    <div class="reservation-summary mb-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <p class="reservation-label">Compagnie</p>
                                <p class="fw-semibold mb-0"><?= htmlspecialchars($reservation['nom_compagnie']) ?></p>
                            </div>
                            <div class="col-md-4">
                                <p class="reservation-label">Trajet</p>
                                <p class="fw-semibold mb-0"><?= htmlspecialchars($reservation['ville_depart'] . " → " . $reservation['ville_arrivee']) ?></p>
                            </div>
                            <div class="col-md-4">
                                <p class="reservation-label">Date du vol</p>
                                <p class="fw-semibold mb-0"><?= date('d/m/Y', strtotime($reservation['date_depart'])) ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="reservation-detail-card">
                                <p class="reservation-label">Départ</p>
                                <p class="fw-semibold mb-2"><?= htmlspecialchars($reservation['nom_adepart']) ?></p>
                                <p class="text-muted mb-0"><?= date('H:i', strtotime($reservation['heure_depart'])) ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="reservation-detail-card">
                                <p class="reservation-label">Arrivée</p>
                                <p class="fw-semibold mb-2"><?= htmlspecialchars($reservation['nom_aarivee']) ?></p>
                                <p class="text-muted mb-0"><?= date('H:i', strtotime($reservation['heure_arrivee'])) ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                        <div class="d-flex flex-wrap gap-2">
                            <span class="info-pill px-3 py-2 rounded-pill"><?= htmlspecialchars($reservation['nb_personnes']) ?> <?= ($reservation['nb_personnes'] > 1 ) ? "passagers" : "passager" ?></span>
                            <span class="info-pill px-3 py-2 rounded-pill">Classe économique</span>
                            <span class="info-pill px-3 py-2 rounded-pill">Siège 12A</span>
                        </div>
                        <button class="btn btn-outline-custom">Voir les détails</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <script>
        
    </script>
</body>
</html>