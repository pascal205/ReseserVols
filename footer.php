<footer id="contact" class="text-white mt-5 pt-5 mb-0  w-100">
    <div class="container">
        <div class="row g-4 pb-4">
            <!-- Colonne Marque -->
            <div class="col-12 col-md-6 col-lg-3">
                <h5 class="fw-bold mb-3">ReservVols</h5>
                <p class="text-white">Réservez vos vols en toute confiance, gérez facilement vos billets et recevez une assistance dédiée.</p>
                <div class="d-flex gap-3 mt-3">
                    <a href="#" class="reseau-link text-white text-decoration-none hover-primary" title="Facebook">
                        <i class="fab fa-facebook-f fa-lg"></i>
                    </a>
                    <a href="#" class="reseau-link text-white text-decoration-none hover-primary" title="Twitter">
                        <i class="fab fa-twitter fa-lg"></i>
                    </a>
                    <a href="#" class="reseau-link text-white text-decoration-none hover-primary" title="Instagram">
                        <i class="fab fa-instagram fa-lg"></i>
                    </a>
                    <a href="#" class="reseau-link text-white text-decoration-none hover-primary" title="LinkedIn">
                        <i class="fab fa-linkedin-in fa-lg"></i>
                    </a>
                </div>
            </div>

            <!-- Colonne Liens Rapides -->
            <div class="col-12 col-md-6 col-lg-3">
                <h5 class="fw-bold mb-3">Liens rapides</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="index.php" class="text-white text-decoration-none">Accueil</a></li>
                    <li class="mb-2"><a href="#destinations" class="text-white text-decoration-none">Destinations</a></li>
                    <li class="mb-2"><a href="planning.php" class="text-white text-decoration-none">Planification</a></li>
                    <li class="mb-2"><a href="#avis" class="text-white text-decoration-none">Avis</a></li>
                    <li class="mb-2"><a href="#contact" class="text-white text-decoration-none">Contact</a></li>
                </ul>
            </div>

            <!-- Colonne Services -->
            <div class="col-12 col-md-6 col-lg-3">
                <h5 class="fw-bold mb-3">Services</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Billets d'avion</a></li>
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Suivi de réservation</a></li>
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Support client</a></li>
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Alertes de prix</a></li>
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Offres spéciales</a></li>
                </ul>
            </div>

            <!-- Colonne Contact -->
            <div class="col-12 col-md-6 col-lg-3">
                <h5 class="fw-bold mb-3">Contact</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-map-marker-alt text-primary"></i>
                        <span class="ms-2 text-white">123 Avenue des Voyageurs, Paris</span>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-phone text-primary"></i>
                        <span class="ms-2 text-white">+229 60 17 80 32</span>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-envelope text-primary"></i>
                        <span class="ms-2 text-white">contact@RservVols.com</span>
                    </li>
                </ul>
                <a href="newsletter.php" class="btn btn-primary w-100">S'abonner</a>
            </div>
        </div>

        <!-- Divider -->
        <hr class="text-white">

        <!-- Footer Bottom -->
        <div class="row py-3">
            <div class="col-12 text-center">
                <p class="text-white mb-0">&copy; 2025 ReserVols. Tous droits réservés.</p>
            </div>
        </div>
    </div>
</footer>
<script src="<?= SITE_URL?>/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
<script>
    const nav = document.getElementById('navbar');
    <?php if($heroPage): ?>
    window.addEventListener("scroll", function () {
        if (window.scrollY > 40) {
            nav.classList.add('scrolled');
        }else{
            nav.classList.remove('scrolled');
        }
    });

    function navbarsolid(nav) {
        if (window.innerWidth <= 1024) {
            nav.classList.remove("hero-mode");

            nav.classList.add("solid-mode");
            nav.classList.add("position-fixed");
        }else{
            if (!document.querySelectorAll(".hero-mode")) {
                nav.classList.add("hero-mode");
            }
            nav.classList.remove("solid-mode");
            nav.classList.remove("position-fixed");
        }
        console.log(nav);
        
    }
    window.addEventListener("load", navbarsolid(nav));
    window.addEventListener("resize", navbarsolid(nav));

    <?php endif?>
    // window.addEventListener("load", navbarsolid(nav));
    // window.addEventListener("resize", navbarsolid(nav));
    
    // window.addEventListener('scroll', () => nav.classList.toggle('bg-white', window.scrollY > 40));
</script>