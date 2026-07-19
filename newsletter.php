<?php
require_once('form/config.php');
require_once("form/database.php");

$pagestyle = false;
$infolder = false;

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReserVols | Newsletter</title>
    <link rel="stylesheet" href="styl.css">
</head>
<body>
    <?php require_once('header.php') ?>

    <section class="hero py-5">
        <div class="container py-5">
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= SITE_URL ?>/index.php">Accueil</a></li>
                    <li class="breadcrumb-item active">Newsletter</li>
                </ol>
            </nav>
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <h1 class="display-6 fw-bold mb-3">Abonnez-vous à notre newsletter</h1>
                    <p class="lead mb-0">Recevez les meilleures offres de vols, astuces de voyage et promotions exclusives directement dans votre boîte mail.</p>
                </div>
                <div class="col-lg-5 mt-4 mt-lg-0">
                    <div class="hero-card p-4 rounded-4">
                        <h4 class="fw-bold mb-3">Inscription rapide</h4>
                        <form>
                            <div class="mb-3">
                                <label class="form-label">Nom complet</label>
                                <input type="text" class="form-control" placeholder="Votre nom complet">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" placeholder="exemple@email.com">
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="agree">
                                <label class="form-check-label" for="agree">
                                    J’accepte de recevoir les offres et actualités de ReserVols.
                                </label>
                            </div>
                            <button type="submit" class="btn btn-primary-custom w-100 rounded-pill">S’abonner</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container py-5">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="hero-card p-4 h-100">
                    <h5 class="fw-bold mb-2">Offres exclusives</h5>
                    <p class="text-muted mb-0">Profitez de réductions réservées aux abonnés.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="hero-card p-4 h-100">
                    <h5 class="fw-bold mb-2">Conseils voyage</h5>
                    <p class="text-muted mb-0">Recevez des astuces pour préparer vos prochains voyages.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="hero-card p-4 h-100">
                    <h5 class="fw-bold mb-2">Actualités aériennes</h5>
                    <p class="text-muted mb-0">Soyez informé des nouveautés et des changements.</p>
                </div>
            </div>
        </div>
    </div>

    <?php require_once('footer.php') ?>
</body>
</html>
