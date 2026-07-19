<?php
require_once("../form/config.php");
require_once("../form/database.php");

$pagestyle = false;
$infolder = true;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styl.css">
</head>
<body>
    <?php require_once('../header.php')?>
    <div class="row g-4 ms-auto pt-3">
        <?php require_once('slidebar.php')?>
        <div class="col-lg">
            <?php if ($rows) {
                foreach ($rows as  $vol) {
            ?>
            <div class="flights-grid">
                <div class="flight-card position-relative row justify-content-between align-items-center px-3 py-4 my-5">
                    <div class="promo-badge rounded-pill w-auto">−35%</div>
                    <div class="flight-airline col-md-3 d-flex align-items-center ">
                        <div class="airline-logo<?php if($vol['nom_compagnie']=='Air France'){echo ' air-france';}elseif($vol['nom_compagnie']=='emirate'){echo ' emirate';}elseif($vol['nom_compagnie']=='Bruxelle'){echo ' bruxelle';}else{echo ' air-france';} ?> d-flex justify-content-center align-items-center me-3"><?php
                        $parts = array_values(array_filter(explode(' ', trim($vol['nom_compagnie']))));
                        echo count($parts) > 1 ? strtoupper(($parts[0][0] ?? '') . ($parts[count($parts) - 1][0] ?? '')) : strtoupper(($parts[0][0] ?? '') . ($parts[0][1] ?? ''));?></div>
                        <div>
                            <div class="airline-name"><?= htmlspecialchars($vol['nom_compagnie']) ?></div>
                            <div style="font-size:0.9rem;color:#8a8aa0;"><?= htmlspecialchars($vol['code']) ?></div>
                        </div>
                    </div>
                    <div class="flight-route col-md-5 d-flex justify-content-center gap-4">
                        <div class="route-point d-flex flex-column">
                            <div class="time"><?= date('H:i', strtotime($vol['heure_depart'])) ?></div>
                            <div class="city text-secondary"><?= htmlspecialchars($vol['ville_depart']) ?></div>
                        </div>
                        <div class="route-line d-flex gap-2 align-items-center">
                            <span class="duration text-secondary">7h 30min</span>
                            <div class="line"></div>
                        </div>
                        <div class="route-point d-flex flex-column">
                            <div class="time"><?= date('H:i', strtotime($vol['heure_arrivee'])) ?></div>
                            <div class="city text-secondary"><?= htmlspecialchars($vol['ville_arrivee']) ?></div>
                        </div>
                    </div>
                    <div class="flight-stops col-md d-flex justify-content-center">
                        <span class="stops-count direct text-secondary">✈ Direct</span>
                    </div>
                    <div class="col-md text-end d-flex flex-column justify-content-center">
                        <div class="flight-price">
                            <div class="price"><?= htmlspecialchars($vol['prix']) ?><small>€</small></div>
                            <div class="per-person text-secondary">par personne</div>
                        </div>
                        <div class="d-flex flex-column gap-2 mt-3">
                            <a href="<?= isLoggedIn() ? 'detail.php?id=' . $vol['id_vols'] : SITE_URL . '/form/login.php' ?>" class="fw-bold py-2 btn btn-outline-primary btn-sm rounded-pill">Plus de détails</a>
                            <a href="<?= isLoggedIn() ? 'reservation.php?id=' .$vol['id_vols'] : SITE_URL . '/form/login.php' ?>" class="fw-bold py-2 btn btn-success btn-sm rounded-pill">Réserver</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php }}?>
        </div>
    </div>
</body>
</html>