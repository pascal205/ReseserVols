<?php
require_once("form/config.php");
require_once("form/database.php");
// die(isLoggedIn());
$donne = $_GET['donnee'] ?? "";
if (empty($donne)) {
    $VilleDp = $_SESSION['villeDp'] ?? "";
    $villeAv = $_SESSION['villeAv'] ?? "";
    $dateDepart = $_SESSION['dateDepart'] ?? "";
}else{
    $VilleDp = "";
    $villeAv = "";
    $dateDepart = "";
}
if (empty($donne) && empty($VilleDp)) {
    redirect('index.php');
}

if($VilleDp && $dateDepart) {
    $a = "date";
}else if($VilleDp && empty($dateDepart)){
    $a = "nodate";
}else{
    $a = '';
}

if($donne === "default")            $rows = $pdo->query("SELECT v.id_vols AS id_vols, ville_depart, ville_arrivee, v.places_dispo, v.prix, date_depart, heure_depart, heure_arrivee, c.nom AS nom_compagnie, c.code_compagnie AS code
                                    FROM vols v, compagnie c
                                    WHERE v.id_compagnie=c.id_compagnie
                                    AND date_depart >= CURDATE()
                                    ORDER BY date_depart ASC, heure_depart ASC")->fetchAll();
if($a === "date"){           $sql = "SELECT v.id_vols AS id_vols, ville_depart, ville_arrivee, v.places_dispo, v.prix, date_depart, heure_depart, heure_arrivee, c.nom AS nom_compagnie, c.code_compagnie AS code
                                    FROM vols v, compagnie c
                                    WHERE v.id_compagnie=c.id_compagnie
                                    AND ville_depart LIKE :villeD
                                    AND ville_arrivee LIKE :villeA
                                    AND date_depart = :dateDepart";
                            $execute = [':villeD' => '%' . $VilleDp . '%', ':villeA' =>'%' . $villeAv . '%', ':dateDepart' => $dateDepart];
}else if($a === "nodate"){   $sql = "SELECT v.id_vols AS id_vols, ville_depart, ville_arrivee, v.places_dispo, v.prix, date_depart, heure_depart, heure_arrivee, c.nom AS nom_compagnie, c.code_compagnie AS code
                                    FROM vols v, compagnie c
                                    WHERE v.id_compagnie=c.id_compagnie
                                    AND ville_depart LIKE :villeD
                                    AND ville_arrivee LIKE :villeA";
                            $execute = [':villeD' =>'%' . $VilleDp . '%', ':villeA' => '%' . $villeAv . '%'];
}else {
    $sql = '';
}
if ($sql) {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($execute);
    $rows= $stmt->fetchAll();
}

if (isset($_POST['search'])) {
    $VilleD = $_POST['villeDep'];
    $villeA = $_POST['villeAv'];
    $date = $_POST['date'];

    $stmt = $pdo->prepare("SELECT v.id_vols AS id_vols, ville_depart, ville_arrivee, v.places_dispo, v.prix, date_depart, heure_depart, heure_arrivee, c.nom AS nom_compagnie, c.code_compagnie AS code
    FROM vols v, compagnie c
    WHERE v.id_compagnie=c.id_compagnie
    AND ville_depart = ? 
    AND ville_arrivee = ? 
    AND date_depart = ?");
    $stmt->execute([$VilleD, $villeA, $date]);
    $rows= $stmt->fetchAll();

}

$pagestyle = false;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReserVols | vols disponibles</title>
    <style>
        .hero{
            background-image: url(images/arrplanut.png);
            background-size: cover;
            background-position: center;
            padding: 6rem 2rem 8rem;
            position: relative;
            overflow: hidden;
        }
        .hero h1{
            font-size: clamp(2rem, 4vw, 4.2rem);
            font-weight: 800;
            letter-spacing: -1px;
            line-height: 1.1;
            color: #0b2b38;
        }
        .hero p {
            font-size: clamp(1rem, 2vw, 1.5rem);
            font-weight: 300;
            color: #3e5a6b;
        }
        .form-group{
            display: block;
        }
        .form-group input,
        .form-group select {
            padding: 1rem 1.2rem;
            border: 2px solid #e8e8f0;
            border-radius: 12px;
            font-family: 'Inter', sans-serif;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            background: #fafafa;
        }
        .form-group label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6a6a8a;
            margin-bottom: 8px;
        }
        .btn-search{
            border-radius: 20px;
        }
        .search-grid{
            margin-top: -120px;
            z-index: 10;
            background: white;
            border-radius: 24px;
            box-shadow: 0 25px 60px rgba(10, 36, 99, 0.15);
            padding: 2rem;
            flex-wrap: wrap;
            width: 100%;
        }
        .search-grid > .form-group,
        .search-grid > .d-flex {
            flex: 1 1 220px;
            min-width: 220px;
        }
        .section-header h2 {
            font-size: 2rem;
            font-weight: 700;
        }
        .section-header p {
            font-size: 1.1rem;
        }
        .flight-card{
            background: white;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            padding: 1.5rem 2rem;
            z-index: 2;
            gap: 1rem;
        }
        .flight-route {
            flex-wrap: wrap;
            gap: 1rem;
        }
        .promo-badge {
            position: absolute;
            padding: 0.3rem 1rem;
            top: -8px;
            right: 2rem;
            background: linear-gradient(135deg, #fb5607, #ff006e);
            color: white;
            font-size: 0.75rem;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(251, 86, 7, 0.3);
            z-index: 1;
        }
        .airline-logo {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            font-size: 1.5rem;
            font-weight: 700;
        }
        .airline-logo.air-france { background: linear-gradient(135deg, #002157, #003580); color: white; }
        .airline-logo.emirate { background: linear-gradient(135deg, #006241, #00a859); color: white; }
        .airline-logo.bruxelle { background: linear-gradient(135deg, #e30a17, #cc0000); color: white; }
        /* .airline-logo.royal-maroc { background: linear-gradient(135deg, #006233, #009a44); color: white; } */
        .airline-name {
            font-weight: 600;
            font-size: 1.1rem;
        }
        .route-line .duration {
            font-size: 0.9rem;
            font-weight: 500;
        }
        .route-line .line {
            width: 90px;
            height: 2px;
            background: linear-gradient(90deg, #ddd, #3a86ff, #ddd);
            position: relative;
        }
        .route-line .line::after {
            content: '✈';
            position: absolute;
            right: -8px;
            top: -10px;
            font-size: 0.9rem;
            color: #3a86ff;
        }
        .route-point .time {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1a1a2e;
        }
        .route-point .city {
            font-size: 1rem;
            font-weight: 500;
        }
        .flight-stops .stops-count {
            font-size: 0.85rem;
            font-weight: 500;
            padding: 0.3rem 0.8rem;
            background: #f0f2f5;
            border-radius: 50px;
        }

        .flight-stops .stops-count.direct {
            background: #e6f7e6;
            color: #00a859;
        }

        .flight-price .price {
            font-size: 1.8rem;
            font-weight: 800;
            color: #0a2463;
        }

        .flight-price .price small {
            font-size: 0.9rem;
            font-weight: 400;
        }

        .flight-price .per-person {
            font-size: 0.8rem;
        }
        .col-md, .col-md-3, .col-md-5{
            margin: 20px 0;
        }
         .btn-book {
            padding: 0.7rem 1.8rem;
            border: none;
            border-radius: 10px;
            background: #0a2463;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Inter', sans-serif;
        }

        @media (max-width: 768px) {
            .search-card {
                padding: 1.2rem !important;
            }
            .search-grid {
                margin-top: -80px;
                padding: 1rem;
            }
            .section-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.4rem;
            }
            .flight-card {
                padding: 1.2rem;
            }
            .flight-airline,
            .flight-route,
            .flight-stops,
            .flight-price {
                justify-content: center;
                text-align: center;
            }
            .flight-route {
                flex-direction: column;
            }
            .route-line {
                flex-direction: column;
            }
            .route-line .line {
                width: 2px;
                height: 40px;
                background: linear-gradient(180deg, #ddd, #3a86ff, #ddd);
            }
            .route-line .line::after {
                top: auto;
                bottom: -8px;
                right: -10px;
            }
        }

        @media (min-width: 1024px){
            .search-grid {
                width: 75%;
            }
        }
        @media (max-width: 576px) {
            .hero {
                padding: 4rem 1rem 6rem;
            }
            .hero-content {
                padding-left: 0.5rem !important;
            }
            .search-grid {
                margin-top: -8
                0px;
            }
            .btn-book,
            .btn-outline-primary {
                width: 100%;
            }
            .flight-card {
                text-align: center;
            }
            .flight-airline {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <?php include("header.php") ?>

    <section class="hero">
        <div class="hero-content text-start ps-4">
            <h1>Voyagez plus loin,<br>payez moins cher</h1>
            <p class="mt-3">Comparez les meilleurs tarifs aériens et réservez en toute simplicité<br/> vers +500 destinations dans le monde.</p>
        </div>
    </section>

    <div class="search-card p-5 d-flex justify-content-center">
            <form class="search-grid d-flex gap-3" method="post">
                <div class="form-group">
                    <label>Départ</label>
                    <input type="text" name="villeDep" placeholder="Ville ou aéroport" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Arrivée</label>
                    <input type="text" name="villeAv" placeholder="Ville ou aéroport" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Date</label>
                    <input type="date" name="date" value="2026-06-15" class="form-control" required>
                </div>
                <div class="d-flex align-items-center justify-content-center mt-3">
                    <button class="btn-search btn btn-primary p-3 text-white" type="submit" name="search">🔍 Rechercher</button>
                </div>
            </form>
    </div>
    
    <section class="container my-5">
        <div class="section-header d-flex justify-content-between">
            <h2>✈ Vols disponibles</h2>
            <p class="text-secondary">23 vols trouvés • 04/05/2026</p>
        </div>

        <?php if ($rows) {
            // foreach (((isset($rows)) ? $rows : $results) as  $vol) {
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
        <?php  
            }}else{
        ?>
        <div style="height:30vh;" class="m-3 container bg-white d-flex justify-content-center align-items-center shadow-sm rounded-4 p-4 mt-5">
            <h1>Aucun vols disponibles ✈️.</h1>
        </div>
        <?php }?>
    </section>

    <?php include("footer.php") ?>
</body>
</html>