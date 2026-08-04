<?php
require "require.php";
$activemenu = 'reservation';

$reservationstmt = $pdo->prepare("SELECT r.id_reservation AS idreserv, v.date_depart, v.date_arrivee, v.heure_depart, v.heure_arrivee, v.ville_depart, v.ville_arrivee, v.prix, c.nom AS nom_compagnie, c.code_compagnie, ad.nom AS nom_adepart, ad.code_aeroport AS code_ad, aa.nom AS nom_aarivee, aa.code_aeroport AS code_aa, r.nb_personnes, r.reference, r.date_reservation, r.statut
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
function isfavoris(int $idreserv, int $userId, $pdo) : bool {
    $favstmt = $pdo->prepare("SELECT * FROM favoris WHERE id_user = :iduser AND id_reserv = :idreserv");
    $favstmt->execute([
        ':iduser' => $userId,
        ':idreserv' => $idreserv
    ]);
    $favReservations = $favstmt->fetchAll(PDO::FETCH_COLUMN);
    if ($favReservations) {
        return true;
    }else {
        return false;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes réservations</title>
    <link rel="stylesheet" href="../styl.css">
    <style>
        .reservation-card{
            position: relative;
        }
        .entete-reserv{
            visibility: visible;
            margin-top: -20px;
            transition: 3s;
            animation: anim 2s forwards;

        }
        .favoris a{
            color: #6c757d;
        }
        .fav-active a{
            color: #ef4444;
        }
        .favoris a:hover{
            color: #ef4444;
        }
    </style>
</head>
<body>
    <?php require_once('../header.php'); ?>

    <div class="container py-4 pt-5 mt-8">
        <div class="row g-4 align-items-start">
            <div class="col-lg-auto">
                <?php require "slidebar.php"; ?>
            </div>
            <div class="col-lg-9 col-xl-7">
            <?php foreach ($inforeserv as $reservation): ?>
                <div class="reservation-card hero-card p-md-4 mb-4">
                    <div class="d-flex align-items-center justify-content-end entete-reserv mb-3 mt-1">
                        <?php //$isFav = in_array($reservation['idreserv'], $favReservations, true); ?>
                        <?php if (isfavoris((int) $reservation['idreserv'], $userId, $pdo)): ?>
                            <h6 class="fw-bold favoris fav-active"><a href="favoris_action.php?id=<?= $reservation['idreserv'] ?>" class="text-decoration-none"><i class="fas fa-heart"></i> Ajoutée aux favoris</a></h6>
                        <?php else: ?>
                            <h6 class="fw-bold favoris"><a href="favoris_action.php?id=<?= $reservation['idreserv'] ?>" class="text-decoration-none"><i class="fas fa-heart"></i> Ajouter aux favoris</a></h6>
                        <?php endif ?>
                    </div>
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
        <?php endforeach; ?>
            </div>
    </div>
    <script>
    //    document.addEventListener('DOMContentLoaded', () => {
    //     document.querySelectorAll(".reservation-card").forEach((card) => {
    //         const fav = document.querySelector('.entete-reserv');
    //         if (!fav) return;

    //         card.addEventListener('mouseenter', ()=> favvisible(fav));
    //         card.addEventListener('mouseleave', ()=> favhide(fav));
    //     });
    //    })
    //    function favvisible(element) {
    //     element.style.visibility = 'visible';
    //    }
    //    function favhide(element) {
    //     element.style.visibility = 'hidden';
    //    }
    </script>
</body>
</html>