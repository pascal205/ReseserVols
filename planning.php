<?php
require_once("form/config.php");
require_once("form/database.php");


if (isset($_SESSION['villeDp'])) {

    $VilleDp = $_SESSION['villeDp'];
    $villeAv = $_SESSION['villeAv'];
    $dateDepart = $_SESSION['dateDepart'];

    $stmt = $pdo->prepare("SELECT v.id_vols AS id_vols, ville_depart, ville_arrivee, v.places_dispo, v.prix, v.date_depart, v.heure_depart, v.heure_arrivee, c.nom AS nom_compagnie
    FROM vols v, compagnie c
    WHERE v.id_compagnie=c.id_compagnie
    AND ville_depart = ? 
    AND ville_arrivee = ? 
    AND date_depart = ?");
    $stmt->execute([$VilleDp, $villeAv, $dateDepart]);
    $results= $stmt->fetchAll();
}

if (isset($_POST['detail'])) {
    $_SESSION['vols'] = $results;
    header("Location: detail.php");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vols</title>
    <link rel="stylesheet" href="bootstrap-5.3.8-dist/css/bootstrap.css">
    <link rel="stylesheet" href="styl.css">
</head>
<body>
    <?php include("header.php")?>
    <div class="hero p-3 rounded-bottom-4 pt-5 ps-5">
        <div class="mt-5 p-3">
            <nav aria-label="breadcrumb" class="mb-2">
                <ol class="breadcrumb mb-0" style="font-size: 1rem">
                    <li class="breadcrumb-item"><a href="<?= SITE_URL ?>/index.php">Accueil</a></li>
                    <li class="breadcrumb-item active">Newsletter</li>
                </ol>
            </nav>
            <h1 class="text-white fw-bold">Vols disponibles</h1>
            <p>Pacourez les vols disponibles et choisissez votre convenance</p>
        </div>
    </div>
    
    <div class="container mt-5 mb-3">
    </div>
     <!-- <div class="card mb-3 shadow-sm">
        <div class="card-body">
            <div class="row align-items-center">
                
                <div class="col-md-8">
                    <div class="row align-items-center text-center text-md-start mb-3">
                        <div class="col-4">
                            <div class="fs-4 fw-bold">08:30</div>
                            <div class="text-muted small">Paris</div>
                        </div>
                        
                        <div class="col-4">
                            <div class="border-top border-2 position-relative">
                                <span class="badge bg-light text-muted position-absolute top-0 start-50 translate-middle">1h20</span>
                            </div>
                        </div>
                        
                        <div class="col-4">
                            <div class="fs-4 fw-bold">09:50</div>
                            <div class="text-muted small">Lyon</div>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-3 text-muted small">
                        <span>Air France</span>
                        <span>•</span>
                        <span>Vol AF1234</span>
                        <span>•</span>
                        <span>15/04/2026</span>
                    </div>
                </div>
                
                
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <div class="fs-2 fw-bold text-success">120 €</div>
                    <div class="text-muted small mb-2">par personne</div>
                    <a href="reservation.php?id=1" class="btn btn-primary px-4">Choisir</a>
                </div>
            </div>
        </div>
    </div> -->
    <?php if ($results) {
    foreach ($results as $vol) {
    ?>
    <div class="card mx-5 shadow-sm my-4">
        <div class="card-body">
            <div class="row align-items-center">
                <!-- Infos vol -->
                <div class="col-md-8">
                    <div class="row align-items-center text-center text-md-start mb-3">
                        <div class="col-4 text-left">
                            <div class="fs-4 fw-bold"><?= date('H:i', strtotime($vol['heure_depart'])) ?></div>
                            <div class="text-muted small"><?= htmlspecialchars($vol['ville_depart'])?></div>
                        </div>
                        
                        <div class="col-4">
                            <div class="border-top border-2 position-relative">
                                <span class="badge bg-light text-muted position-absolute top-0 start-50 translate-middle">1h20</span>
                            </div>
                        </div>
                        
                        <div class="col-4 text-left">
                            <div class="fs-4 fw-bold"><?= date('H:i', strtotime($vol['heure_arrivee'])) ?></div>
                            <div class="text-muted small"><?= htmlspecialchars($vol['ville_arrivee'])?></div>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-3 text-muted small">
                        <span><?= htmlspecialchars($vol['nom_compagnie']); ?></span>
                        <span>•</span>
                        <span><?= htmlspecialchars($vol['places_dispo']) ?> Places disponibles</span>
                        <span>•</span>
                        <span>Vol prévu pour le <?= date('d/m/y', strtotime($vol['date_depart'])) ?></span>
                        <span>•</span>
                    </div>
                </div>
                
                <!-- Prix + bouton -->
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <div class="fs-2 fw-bold text-success"><?= htmlspecialchars($vol['prix']); ?> €</div>
                    <div class="text-muted small mb-2">par personne</div>
                    <div class="d-flex gap-2 justify-content-end">
                        <button href="reservation.php?id=1" class="btn btn-primary px-4">Choisir</button>
                        <!-- <form action="" method="post"><button type="submit" name="detail" class="btn btn-outline-primary px-4">Plus de détail</button></form> -->
                         <a href="<?= SITE_URL ?>/detail.php?id=<?= $vol['id_vols'] ?>" class="btn btn-outline-primary px-4">Plus de detail</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php }}else{?>
    <div style="height:30vh;" class="container bg-white d-flex justify-content-center align-items-center shadow-sm rounded-4 p-4 mt-5">
        <h1>Aucun vols disponibles ✈️.</h1>
    </div>
    <?php }?>
    <div style="height:20vh"></div>

    <?php require_once("footer.php"); ?>
</body>
</html>