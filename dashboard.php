<?php
require_once('form/config.php');
require_once("form/database.php");


$pagestyle = false;

$userId = isset($_GET['id']) ? (int)$_GET['id'] : null;

if ($userId === null || $userId <= 0) {
    $userId = $_SESSION['user_id'] ?? null;
}

if (!$userId) {
    header('Location: form/login.php');
    exit;
}

$_SESSION['user_id'] = $userId;

$stmt = $pdo->prepare("SELECT * FROM user WHERE id = :id");
$stmt->execute([':id' => $userId]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: form/login.php');
    exit;
}

$_SESSION['prenom'] = $user['prenom'] ?? '';
$_SESSION['nom'] = $user['nom'] ?? '';
$_SESSION['email'] = $user['email'] ?? '';
$_SESSION['telephone'] = $user['telephone'] ?? '';
$_SESSION['type'] = $user['type'] ?? '';

if (isset($_POST['changeInfo'])) {
    $newNom = trim($_POST['nom'] ?? '');
    $newPrenom = trim($_POST['prenom'] ?? '');
    $newEmail = trim($_POST['email'] ?? '');
    $newTel = trim($_POST['telephone'] ?? '');

    $updateStmt = $pdo->prepare("UPDATE user SET nom = :nom, prenom = :prenom, email = :email, telephone = :telephone WHERE id = :id");
    $updated = $updateStmt->execute([
        ':nom' => $newNom,
        ':prenom' => $newPrenom,
        ':email' => $newEmail,
        ':telephone' => $newTel,
        ':id' => $userId
    ]);

    if ($updated) {
        $_SESSION['prenom'] = $newPrenom;
        $_SESSION['nom'] = $newNom;
        $_SESSION['email'] = $newEmail;
        $_SESSION['telephone'] = $newTel;

        header('Location: dashboard.php?message=succes');
        exit;
    }

    header('Location: dashboard.php?message=error');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReserVols | Mon espace</title>
    <link rel="stylesheet" href="styl.css">
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
    <?php require_once('header.php') ?>

    <section class="hero py-5">
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
                    <a href="form/logout.php" class="btn btn-outline-light rounded-pill px-4">Déconnexion</a>
                </div>
            </div>
        </div>
    </section>

    <div class="container py-5">
        <div class="row g-4">
            <div class="col-lg-5">
                <div class="hero-card p-4 h-100">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center" style="width: 50px; height: 50px; font-size: 1.2rem;">
                            <?= strtoupper(substr($_SESSION['prenom'] ?? 'U', 0, 1)) ?>
                        </div>
                        <div class="ms-3">
                            <h5 class="fw-bold mb-0"><?= htmlspecialchars($_SESSION['prenom'] ?? '') ?> <?= htmlspecialchars($_SESSION['nom'] ?? '') ?></h5>
                            <div class="text-muted small"><?= htmlspecialchars($_SESSION['email'] ?? '') ?></div>
                        </div>
                        <?php if ($_SESSION['type'] === 'admin') {
                        ?>
                            <div class="mx-auto d-flex justify-content-end">
                                <a href="admin.php" class="btn btn-outline-primary rounded-pill">administrateur</a>
                            </div>
                        <?php }?>
                    </div>
                    <hr>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><i class="fas fa-user-circle me-2 text-primary"></i> Profil</li>
                        <li class="mb-2"><i class="fas fa-plane me-2 text-primary"></i> Réservations</li>
                        <li class="mb-2"><i class="fas fa-heart me-2 text-primary"></i> Favoris</li>
                        <li><i class="fas fa-cog me-2 text-primary"></i> Paramètres</li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="hero-card p-4 h-100">
                            <h5 class="fw-bold mb-3"><i class="fas fa-plane text-primary me-2"></i> Mes réservations</h5>
                            <p class="text-muted mb-0">Vous n’avez pas encore de réservation.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="hero-card p-4 h-100">
                            <h5 class="fw-bold mb-3"><i class="fas fa-heart text-primary me-2"></i> Mes favoris</h5>
                            <p class="text-muted mb-0">Ajoutez vos vols préférés pour les retrouver rapidement.</p>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="hero-card p-4">
                            <div class="d-flex gap-3 align-items-center border-bottom border-2 mt-3 mb-4">
                                <button class="bttn rounded-0 fw-bold btn-active" id="info" onclick="showtab('infos', this)"><i class="fas fa-user-edit text-primary me-2"></i> Informations personnelles</button>
                                <button class="bttn rounded-0 fw-bold text-secondary" id="mdp" onclick="showtab('mdp', this)"><i class="fas fa-lock me-1"></i>mot de passe</button>
                            </div>
                            <div id="tab-infos">
                                <form method="post">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Prénom</label>
                                            <input type="text" name="prenom" id="prenom" class="form-control" value="<?= htmlspecialchars($_SESSION['prenom'] ?? '') ?>" disabled>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Nom</label>
                                            <input type="text" name="nom" id="nom" class="form-control" value="<?= htmlspecialchars($_SESSION['nom'] ?? '') ?>" disabled>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($_SESSION['email'] ?? '') ?>" disabled>
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
                                            <input type="text" name="ancmdp" id="prenom" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Nouveau mot de passe</label>
                                            <input type="text" name="newmdp" id="nom" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Confirmer</label>
                                            <input type="email" name="confmdp" id="email" class="form-control">
                                        </div>
                                    </div>
                                    <button type="button" name="" class="px-3 py-2 btn btn-primary my-4 infos fs-6 fw-semibold">Changer le mot de passe</button>
                                </form>
                            </div>
                            <?php
                                $message = $_GET['message'] ?? '';
                                if ($message === 'succes') {
                            ?>
                                <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                                    ✅ Modifications enregistrées avec succès.
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php
                                } elseif ($message === 'error') {
                            ?>
                                <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                                    ⚠️ Une erreur est survenue lors de l’enregistrement.
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once('footer.php') ?>
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
