<?php
require "require.php";
$activemenu = 'profil';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReserVols | Mon espace</title>
    <link rel="stylesheet" href="../styl.css">
    <style>
        .btn-mod{
            transition: 0.2s !important;
        }
        .btn-mod:hover{
            letter-spacing: 3px;
        }
        .bttn{ 
            background: transparent;
            border: none;
            border: 2px solid transparent;
            margin-bottom: -2px;
            padding: 0.375rem 0.75rem;
            transition: all .2s
        }
        .btn-active{
            color: #2b7ca0;
            border-bottom-color: #2b7ca0;
        }
        form{
            font-weight: 600;
            padding-right: 20px;
        }
    </style>
</head>
<body>
    <?php require_once('../header.php') ?>

    <section class="hero w-100 py-5 mt-7">
        <div class="container py-5">
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="index.php">Accueil</a></li>
                    <li class="breadcrumb-item active">Mon espace</li>
                </ol>
            </nav>
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-6 fw-bold mb-3">Bienvenue, <?= htmlspecialchars($_SESSION['prenom'] ?? 'Utilisateur') ?> 👋</h1>
                    <p class="lead mb-0">Gérez vos réservations, vos favoris et vos informations personnelles depuis votre espace personnel.</p>
                </div>
                <div class="col-lg-4 mt-4 mt-lg-0 text-lg-end">
                    <a href="<?= SITE_URL ?>/form/logout.php" class="btn btn-outline-light rounded-pill px-4">Déconnexion</a>
                </div>
            </div>
        </div>
    </section>

    <div class="container py-5">
        <div class="row g-4 d-flex justify-content-center">
            <div class="col-lg-auto">
                <?php require_once('slidebar.php') ?>
            </div>
            <div class="col-lg-7">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="hero-card p-4 h-100">
                            <h5 class="fw-bold mb-3 text-center"><i class="fas fa-plane text-primary me-2"></i> Mes réservations</h5>
                            <?php if ($nbreserv) {
                            ?>
                                <p class="fs-1 fw-semibold text-center"><span class="text-primary"><?= htmlspecialchars($nbreserv) ?></span></p>
                            <?php }else {
                            ?>
                                <p class="text-muted mb-0">Vous n’avez pas encore de réservation.</p>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="hero-card p-4 h-100">
                            <h5 class="fw-bold mb-3 text-center"><i class="fas fa-heart text-primary me-2"></i> Mes favoris</h5>
                            <p class="text-muted mb-0">Ajoutez vos vols préférés pour les retrouver rapidement.</p>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="hero-card p-4">
                            <div class="d-flex gap-3 align-items-center border-bottom border-2 mt-3 mb-4">
                                <button class="bttn rounded-0 fw-bold btn-active" id="info" onclick="showtab('infos', this)"><i class="fas fa-user-edit me-2"></i> Informations personnelles</button>
                                <button class="bttn rounded-0 fw-bold text-secondary" id="mdp" onclick="showtab('mdp', this)"><i class="fas fa-lock me-2"></i>mot de passe</button>
                            </div>
                            <?php
                                if ($succes) {
                            ?>
                                <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                                    ✅ <?= htmlspecialchars($succes) ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php
                                } elseif ($error) {
                            ?>
                                <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                                    ⚠️ <?= htmlspecialchars($error) ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php
                                }
                            ?>
                            <div id="tab-infos">
                                <form method="post">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Prénom</label>
                                            <input type="text" name="prenom" id="prenom" class="form-control" value="<?= htmlspecialchars($_SESSION['prenom'] ?? '') ?>" disabled required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Nom</label>
                                            <input type="text" name="nom" id="nom" class="form-control" value="<?= htmlspecialchars($_SESSION['nom'] ?? '') ?>" disabled required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($_SESSION['email'] ?? '') ?>" disabled required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Téléphone</label>
                                            <input type="text" name="telephone" id="telephone" class="form-control" value="<?= htmlspecialchars($_SESSION['telephone'] ?? '') ?>" placeholder="<?= (!$_SESSION['telephone']) ? 'À compléter' :'' ?>" disabled>
                                        </div>
                                    </div>
                                    <button type="button" name="" id="btn-mod" class="px-2 py-2 btn btn-outline-primary my-4 fw-semibold fs-5 btn-mod" onclick="return modif();">Modifier</button>
                                </form>
                            </div>
                            <div id="tab-mdp" style="display: none;">
                                <form method="post">
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <label class="form-label">Ancien mot de passe</label>
                                            <input type="password" name="ancmdp" class="form-control" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Nouveau mot de passe</label>
                                            <input type="password" name="newmdp" class="form-control" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Confirmer</label>
                                            <input type="password" name="confmdp" class="form-control" required>
                                        </div>
                                    </div>
                                    <button type="submit" name="changemdp" class="px-3 py-2 btn btn-primary my-4 infos fs-6 fw-semibold">Changer le mot de passe</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once('../footer.php') ?>
    <script>
        function modif(){
            const prenom = document.getElementById('prenom');
            const nom = document.getElementById('nom');
            const email = document.getElementById('email');
            const telephone = document.getElementById('telephone');
            const btnModif = document.getElementById('btn-mod');
            const inputs = [prenom, nom, email, telephone];

            if (btnModif && btnModif.textContent.trim() === 'Modifier') {
                inputs.forEach(input => {
                    if (input) {
                        input.disabled = false;
                    }
                });

                btnModif.textContent = 'Enregistrer';
                btnModif.type = 'button';
                // btnModif.name = 'changeInfo';
                return false;
            }

            btnModif.type = 'submit';
            btnModif.name = 'changeInfo';
            return true;
        }
        function showtab(name, btn) {
            document.getElementById('tab-infos').style.display = 'none';
            document.getElementById('tab-mdp').style.display = 'none';

            document.getElementById('tab-' + name).style.display = 'block';
            document.querySelectorAll('.bttn').forEach(t =>{t.classList.remove('btn-active'); t.classList.add('text-secondary')});
            btn.classList.add('btn-active');
            btn.classList.remove('text-secondary');

            
        }
    </script>
</body>
</html>
