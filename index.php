<?php
require_once("form/config.php");
require_once("form/database.php");

if (isset($_POST['create-itinerary'])) {
    $_SESSION['villeDp'] = $_POST['villeDp'];
    $_SESSION['villeAv'] = $_POST['villeAv'];
    $_SESSION['dateDepart'] = $_POST['dateDepart'];
    
    redirect("vol.php");
}
// isLoggedIn() ? die(isLoggedIn()) : die('ppp');
$volDispos = $pdo->query("SELECT v.id_vols AS id_vol, ville_depart, ville_arrivee, v.places_dispo, v.prix, date_depart, heure_depart, heure_arrivee, c.nom AS nom_compagnie, c.code_compagnie, ad.nom AS nom_aeroport_depart, ad.code_aeroport AS code_aeroport_depart, aa.nom AS nom_aeroport_arrivee, aa.code_aeroport AS code_aeroport_arrivee
                FROM vols v
                JOIN compagnie c ON v.id_compagnie = c.id_compagnie
                JOIN aeroport ad ON ad.id_aeroport = v.id_aeroport_depart
                JOIN aeroport aa ON aa.id_aeroport = v.id_aeroport_arrivee
                WHERE date_depart >= CURDATE()
                ORDER BY date_depart ASC, heure_depart ASC LIMIT 4")->fetchAll();
$pagestyle = true;
$heroPage = true;
$activepage = 'Accueil';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
        <?php include("header.php")?>
           <section class="hero pb-5">
            <div class="hero-bg"></div>
            <div class="hero-overlay"></div>
                <div class="hero-cont pt-4 hero-grid d-flex align-items-center gap-3">
                    <div class="hero-content">
                        <h1>Réservez votre vol en quelques clics.</h1>
                        <p>Trouvez le vol idéal, comparez les horaires et finalisez votre réservation rapidement avec ReservVols.</p>
                        <form method="POST" class="form-control bg-white d-flex align-items-center gap-2 w-90 py-3 mt-5">
                            <input type="text" name="villeDp" placeholder="Départ (ex: Cotonou)" class="search-input form-control rounded-pill p-3" id="fromInput" value="Cotonou">
                            <i class="fas fa-exchange-alt" style="color:#2c7da0;"></i>
                            <input type="text" name="villeAv" placeholder="Destination (ex: Paris)" class="search-input form-control rounded-pill p-3" id="toInput" value="Paris">
                            <button type="submit" name="create-itinerary" class="btn btn-primary rounded-pill d-flex align-items-center justify-content-center py-3" id="quickSearchBtn"><span>Rechercher vol</span> <i class="fas fa-arrow-right"></i></button>
                        </form>
                    </div>
                    <div class="hero-stats bg-white d-flex flex-column text-center shadow-sm" id="heroStats">
                        <div class="stat-number">+2500</div>
                        <p class="fs-5">vols réservés ce mois</p>
                        <div class="stat-number" style="font-size: 2rem;">4.9 ★</div>
                        <p class="fs-5">évaluations voyageurs</p>
                    </div>
                </div>
        </section>

        <section class="apropos" id="apropos">
             <div class="container features">
            <h2 class="section-title">Pourquoi ReserVols ?</h2>
            <p class="section-sub">Des outils puissants pour organiser le voyage parfait</p>
            <div class="features-grid">
                <div class="feature-card"><i class="fas fa-plane"></i><h4>Recherche rapide de vols</h4><p>Trouvez les meilleures offres au départ de votre ville.</p></div>
                <div class="feature-card"><i class="fas fa-lock"></i><h4>Paiement sécurisé</h4><p>Réglez en ligne avec un parcours fiable et protégé.</p></div>
                <div class="feature-card"><i class="fas fa-headset"></i><h4>Assistance 24/7</h4><p>Notre équipe est là pour vous accompagner avant, pendant et après la réservation.</p></div>
                <div class="feature-card"><i class="fas fa-ticket-alt"></i><h4>Gestion de réservation</h4><p>Consultez, modifiez ou annulez vos billets en quelques clics.</p></div>
                <div class="feature-card"><i class="fas fa-bell"></i><h4>Alertes de prix</h4><p>Recevez une notification dès qu’un vol correspond à votre budget.</p></div>
                <div class="feature-card"><i class="fas fa-globe-americas"></i><h4>Destinations populaires</h4><p>Comparez nos offres vers les villes les plus demandées.</p></div>
            </div>
        </div>
        </section>
        
         <section id="vols" class="mt-5">
            <div class="container">
                <div class="vol-header justify-content-between align-items-center">
                    <div class="gap-2 vol-head align-items-center">
                        <h2 class="section-title fs-2">✈️ Vols disponibles</h2>
                        <p class="badge rounded-pill mt-3 text-black" style="">Mise à jour en temps réel — 13/04/2026</p>
                    </div>
                    <div class="">
                        <a href="vol.php?donnee=default" class="text-primary d-flex text-decoration-none align-items-center" id="quickSearchBtn">Pacourez tous les vols <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                <p class="section-sub m-2">Parcourez les vols disponibles et choisissez votre convenance — 13 avril 2026</p>
                <div class="row flights-grid" id="flightsGrid">
                    <?php if ($volDispos) {
                        foreach ($volDispos as $vol) {
                    ?>
                        <div class="col-md-6">
                            <div class="flight-card">
                                <div class="flight-top align-items-center d-flex justify-content-between">
                                    <span class="flight-time"><?= date('H:i', strtotime($vol['heure_depart'])) ?></span>
                                    <span class="flight-airline" style="font-weight:600;"><?= htmlspecialchars($vol['nom_compagnie']) ?></span>
                                </div>
                                <div class="flight-body">
                                    <div class="route d-flex align-items-center justify-content-between mb-3">
                                        <span class="city fs-6"><?= htmlspecialchars($vol['ville_depart']) ?></span>
                                        <span class="duration rounded-pill"><i class="far fa-clock"></i> 1h20</span>
                                        <span class="city"><?= htmlspecialchars($vol['ville_arrivee']) ?></span>
                                    </div>
                                    <div class="flight-meta">
                                        <i class="fas fa-chair"></i> <?= $vol['places_dispo'] ?> places disponibles · <?= date('d/m/Y', strtotime($vol['date_depart'])) ?><br>
                                        <i class="fas fa-map-marker-alt"></i> Départ: <?= htmlspecialchars($vol['nom_aeroport_depart'] . ' ' . $vol['code_aeroport_depart']) ?>
                                    </div>
                                    <div class="price"><?= number_format($vol['prix'], 0, ',', ' ') ?> € <span style="font-size:0.9rem;">par personne</span></div>
                                    <div style="display: flex; gap: 12px; margin-top: 16px;">
                                        <a href="<?= isLoggedIn() ? 'reservation.php?id=' . $vol['id_vol'] : SITE_URL . '/form/login.php' ?>" class="btn btn-primary rounded-pill p-2 select-flight" style="flex:1;">Choisir</a>
                                        <a href="<?= isLoggedIn() ? 'detail.php?id=' . $vol['id_vol'] : SITE_URL . '/form/login.php' ?>" class="btn btn-outline-primary rounded-pill detail-flight" style="flex:1;">Plus de détail</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                        }
                    }else {
                    ?>
                        <div class="col-md-6">
                            <div class="flight-card">
                                <div class="flight-top align-items-center d-flex justify-content-between">
                                    <span class="flight-time">13:14</span>
                                    <span class="flight-airline" style="font-weight:600;">Bruxelles Airlines</span>
                                </div>
                                <div class="flight-body">
                                    <div class="route d-flex align-items-center justify-content-between mb-3">
                                        <span class="city fs-6">Cotonou</span>
                                        <span class="duration rounded-pill"><i class="far fa-clock"></i> 1h20</span>
                                        <span class="city">New York</span>
                                    </div>
                                    <div class="flight-meta">
                                        <i class="fas fa-chair"></i> 300 places disponibles · 13/04/26<br>
                                        <i class="fas fa-map-marker-alt"></i> Départ: Aéroport international de Cotonou (AAC)
                                    </div>
                                    <div class="price">300 € <span style="font-size:0.9rem;">par personne</span></div>
                                    <div style="display: flex; gap: 12px; margin-top: 16px;">
                                        <button class="btn btn-primary rounded-pill p-2 select-flight" data-price="${flight.price}" data-name="${flight.airline}" data-from="${flight.depCity}" data-to="${flight.arrCity}" style="flex:1;">Choisir</button>
                                        <button class="btn btn-outline-primary rounded-pill detail-flight" data-detail='${JSON.stringify(flight)}' style="flex:1;">Plus de détail</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="flight-card">
                                <div class="flight-top align-items-center d-flex justify-content-between">
                                    <span class="flight-time">8h:30</span>
                                    <span class="flight-airline" style="font-weight:600;">Air France</span>
                                </div>
                                <div class="flight-body">
                                    <div class="route d-flex align-items-center justify-content-between mb-3">
                                        <span class="city fs-6">Cotonou</span>
                                        <span class="duration rounded-pill"><i class="far fa-clock"></i> 1h20</span>
                                        <span class="city">New York</span>
                                    </div>
                                    <div class="flight-meta">
                                        <i class="fas fa-chair"></i> 200 places disponibles · 13/04/26<br>
                                        <i class="fas fa-map-marker-alt"></i> Départ: Aéroport international de Cotonou (AAC)
                                    </div>
                                    <div class="price">255 € <span style="font-size:0.9rem;">par personne</span></div>
                                    <div style="display: flex; gap: 12px; margin-top: 16px;">
                                        <button class="btn btn-primary rounded-pill p-2 select-flight" data-price="${flight.price}" data-name="${flight.airline}" data-from="${flight.depCity}" data-to="${flight.arrCity}" style="flex:1;">Choisir</button>
                                        <button class="btn btn-outline-primary rounded-pill detail-flight" data-detail='${JSON.stringify(flight)}' style="flex:1;">Plus de détail</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php 
                    } ?>
                </div>
            </div>
         </section>

    <!-- Destinations Section -->
        <section class="destinations mt-5" id="destinations">
            <div class="container">
                <div class="section-title">
                    <h2 class="section-title">Destinations populaires</h2>
                    <p class="section-sub">Découvrez nos destinations les plus prisées par nos voyageurs</p>
                </div>
                <div class="destinations-grid row">
                    <div class="col-md-4">
                        <div class="destination-card d-flex flex-column">
                            <div class="destination-img" style="background-image: url('images/avion.jpg');"></div>
                            <div class="destination-info">
                                <h3>Bali, Indonésie</h3>
                                <p>Vols avantageux vers Bali avec service à bord et escales optimisées.</p>
                                <div class="destination-price">À partir de 850€</div>
                            </div>
                        </div>
                    </div>  
                    <div class="col-md-4">
                        <div class="destination-card d-flex flex-column">
                            <div class="destination-img" style="background-image: url('images/av-Interjet.png');"></div>
                            <div class="destination-info">
                                <h3>Paris, France</h3>
                                <p>Réservez un aller simple ou un aller-retour vers la capitale.</p>
                                <div class="destination-price">À partir de 350€</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="destination-card d-flex flex-column">
                            <div class="destination-img"style="background-image: url('images/arrplanut.jpg');"></div>
                            <div class="destination-info">
                                <h3>Tokyo, Japon</h3>
                                <p>Offres vers Tokyo : vols directs et correspondances pratiques.</p>
                                <div class="destination-price">À partir de 1200€</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="destination-card d-flex flex-column">
                            <div class="destination-img"style="background-image: url('images/image2.jpg');"></div>
                            <div class="destination-info">
                                <h3>Los Angeles, USA</h3>
                                <p>Billets vers Los Angeles avec tarifs préférentiels et services inclus.</p>
                                <div class="destination-price">À partir de 1200€</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Planning Section -->
        <section class="mt-5" id="planning">
            <div class="planning-section container py-3 shadow-sm">
                <div class="section-title">
                    <h2 class="section-title">Planifiez votre voyage</h2>
                    <p class="section-sub">Créez votre itinéraire personnalisé en quelques étapes simples</p>
                </div>
                <div class="planning-steps row align-items-center justify-content-center">
                    <div class="col-md-3">
                        <div class="step">
                            <div class="number"><div class="step-number">1</div></div>
                            <h3>Recherchez votre vol</h3>
                            <p>Trouvez l’horaire qui correspond à votre planning.</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="step">
                            <div class="number"><div class="step-number">2</div></div>
                            <h3>Choisissez votre tarif</h3>
                            <p>Sélectionnez la classe et les options qui vous conviennent.</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="step">
                            <div class="number"><div class="step-number">3</div></div>
                            <h3>Entrez vos informations</h3>
                            <p>Complétez les données passager pour sécuriser la réservation.</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="step">
                            <div class="number"><div class="step-number">4</div></div>
                            <h3>Confirmez votre billet</h3>
                            <p>Paiement sécurisé et e-mail de confirmation instantané.</p>
                        </div>
                    </div>
                </div>
                <?php if (isset($_SESSION['user_id'])) {
                    
                ?>
                <div class="mt-4 planning-form">
                    <h3>Commencer la planification</h3>
                    <form id="travel-form" method="POST">
                        <div class="row d-flex justify-content-center">
                            <div class="form-group col-md-3">
                                <label for="destination">Ville de départ</label>
                                <input class="search-input form-control rounded-pill" name="villeDp" type="text" id="destination" placeholder="Ville de départ" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="destination">Destination</label>
                                <input class="search-input form-control rounded-pill" name="villeAv" type="text" id="destination" placeholder="Où allez-vous?" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="start-date">Date de départ</label>
                                <input class="search-input form-control rounded-pill" name="dateDepart" type="date" id="start-date" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="number">Nombre de personne</label>
                                <input class="search-input form-control rounded-pill" name="nbrper" type="number" id="start-date" required>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center align-items-center submit-div">
                            <button name="create-itinerary" type="submit" class="submit-btn btn btn-primary rounded-pill py-3 px-4 fw-bold">Commencer la planification</button>
                        </div>
                    </form>
                </div>
                <?php }else{?>
                <div class="my-4 planning-form">
                    <h3 class="text-center">Connectez-vous pour commencer la planification</h3>
                    <div class="d-flex justify-content-center mt-3">
                        <a href="form/login.php" class="btn btn-outline-primary btn-lg fs-3 rounded-pill">Se connecter</a>
                    </div>
                </div>
                <?php }?>
            </div>
        </section>

        <!-- Testimonials -->
        <!-- <section class="testimonials" id="avis">
            <div class="container">
                <div class="section-title">
                    <h2>Ce que disent nos voyageurs</h2>
                    <p>Découvrez les expériences de ceux qui ont utilisé VoyageCraft</p>
                </div>
                <div class="testimonials-grid">
                    <div class="testimonial-card">
                        <div class="testimonial-text">
                            "Grâce à VoyageCraft, j'ai organisé un voyage parfait en Italie. L'interface est intuitive et les suggestions d'activités étaient parfaites!"
                        </div>
                        <div class="testimonial-author">
                            <div class="author-avatar">MS</div>
                            <div>
                                <h4>MarieSimon</h4>
                                <p>Voyage en Italie, 2025</p>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-card">
                        <div class="testimonial-text">
                            "Je voyage souvent pour le travail, mais avec VoyageCraft j'ai enfin pu organiser un voyage personnel mémorable au Japon. Tout était parfaitement planifié."
                        </div>
                        <div class="testimonial-author">
                            <div class="author-avatar">TD</div>
                            <div>
                                <h4>Thomas Dubois</h4>
                                <p>Voyage au Japon, 2025</p>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-card">
                        <div class="testimonial-text">
                            "Nous avons utilisé VoyageCraft pour notre voyage de noces à Bali. La plateforme nous a permis de créer un itinéraire sur mesure qui correspondait parfaitement à nos attentes."
                        </div>
                        <div class="testimonial-author">
                            <div class="author-avatar">CL</div>
                            <div>
                                <h4>Chloé💕Lucas</h4>
                                <p>Voyage de noces à Bali, 2025</p>
                            </div>
                        </div>
                    </div>
                <div class="testimonial-card">
                    <div class="testimonial-text">
                        "C'est grâce a voyageCraft, que nous avons éffectués de bonnes vaccances pour notre famille."
                    </div>
                <div class="testimonial-author">
                    <div class="author-avatar">GE</div>
                        <div>
                            <h4>Goerges et Emma</h4>
                            <p>Voyage aux Etats-Unis, 2024</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </section> -->

        <section id="avis" class="testimonials mt-5">
            <div class="container">
                <h2 class="section-title">⭐ Ce que disent nos voyageurs</h2>
                <!-- <div class="testimonials d-flex justify-content-center gap-3">
                    <div class="testimonial-card"><i class="fas fa-quote-left fa-2x"></i><p>“Grâce à ReservVols, j’ai organisé un voyage parfait en Italie. Suggestions d’activités incroyables.”</p><strong>— Marie Simon</strong></div>
                    <div class="testimonial-card"><i class="fas fa-quote-left fa-2x"></i><p>“Organisation au top pour notre lune de miel à Bali. L’assistance réactive et les prix clairs.”</p><strong>— Chloé & Lucas</strong></div>
                    <div class="testimonial-card"><i class="fas fa-quote-left fa-2x"></i><p>“Voyage aux États-Unis réussi grâce à la carte interactive et aux réservations intégrées.”</p><strong>— Georges & Emma</strong></div>
                </div> -->
                <div class="swiper avis-swiper">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide active-slide">
                            <div class="testimonial-card"><i class="fas fa-quote-left fa-2x"></i><p>“Grâce à ReservVols, j’ai organisé un voyage parfait en Italie. Suggestions d’activités incroyables.”</p><strong>— Marie Simon</strong></div>    
                        </div>
                        <div class="swiper-slide">
                            <div class="testimonial-card"><i class="fas fa-quote-left fa-2x"></i><p>“Organisation au top pour notre lune de miel à Bali. L’assistance réactive et les prix clairs.”</p><strong>— Chloé & Lucas</strong></div>
                        </div>
                        <div class="swiper-slide">
                            <div class="testimonial-card"><i class="fas fa-quote-left fa-2x"></i><p>“Voyage aux États-Unis réussi grâce à la carte interactive et aux réservations intégrées.”</p><strong>— Georges & Emma</strong></div>
                        </div>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
        </div>
        </section>

        <?php require_once("footer.php"); ?>
        
    <script>
            const swiperAvis = new Swiper('.avis-swiper', {
            loop: true,
            centeredSlides: true,
            slidesPerView: 3,      // ← montre un peu des slides adjacentes
            spaceBetween: 24,
            speed: 700,
            autoplay: {
                delay: 3500,
                disableOnInteraction: false,
                pauseOnMouseEnter: true,
            },
            breakpoints: {
                640: {
                    slidesPerView: 1,
                    spaceBetween: 18,
                },
                1024: {
                    slidesPerView: 2,
                    spaceBetween: 24,
                },
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
                type: 'bullets',
                dynamicBullets: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });

        function hideStats() {
            const stats = document.getElementById('heroStats');
            if (!stats){
                return
            }else{
            console.log(stats);
            }

            if (window.innerWidth <= 1024) {
                // console.log(stats);
                stats.style.visibility = "hidden";
                stats.style.display = "none";
            }else{
                stats.style.visibility = "visible";
                stats.style.display = "block";
            }
            console.log(stats);
        }
        // console.log(stats);


        window.addEventListener("load", hideStats);
        window.addEventListener("resize", hideStats);
    </script>
    </body>
    </html>