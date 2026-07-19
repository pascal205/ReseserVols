
<div class="col-lg-auto">
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
                <div class="ms-3 d-flex justify-content-end">
                    <a href="admin.php" class="btn btn-outline-primary rounded-pill">administrateur</a>
                </div>
            <?php }?>
        </div>
        <hr>
        <ul class="list-unstyled mb-0">
            <li class="mb-3"><a href="<?= SITE_URL?>/profil/dashboard.php?id=<?= $userId?>" class="btn text-black border border-0 btn<?= ($activemenu ?? '')==='profil' ? '' : '-outline'?>-info fs-5 rounded-3 px-3 py-2 w-75 text-start"><i class="fas fa-user-circle me-2 text-primary"></i> Profil</a></li>
            <li class="mb-3"><a href="#" class="btn text-black border border-0 btn<?= ($activemenu ?? '')==='reservation' ? '' : '-outline'?>-info fs-5 rounded-3 px-3 py-2 w-75 text-start"><i class="fas fa-plane me-2 text-primary"></i> Réservations</a></li>
            <li class="mb-3"><a href="#" class="btn text-black border border-0 btn<?= ($activemenu ?? '')==='favoris' ? '' : '-outline'?>-info fs-5 rounded-3 px-3 py-2 w-75 text-start"><i class="fas fa-heart me-2 text-primary"></i> Favoris</li></a>
            <li class="mb-3"><a href="#" class="btn text-black border border-0 btn<?= ($activemenu ?? '')==='parametre' ? '' : '-outline'?>-info fs-5 rounded-3 px-3 py-2 w-75 text-start"><i class="fas fa-cog me-2 text-primary"></i> Paramètres</a></li>
        </ul>
    </div>
    <div class="hero-card p-4">
        <h6></h6>
        <h6></h6>
        <h6></h6>
    </div>
</div>