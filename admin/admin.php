<?php
require_once("../form/config.php");
require_once("../form/database.php");


if (isset($_POST['insert'])) {
        $compagnie = $_POST['compagnie'];
        $ville_depart = $_POST['ville_depart'];
        $ville_arrivee = $_POST['ville_arrivee'];
        $aeroportD = $_POST['aeroport_depart'];
        $aeroportA = $_POST['aeroport_arrivee'];
        $date_depart = $_POST['date_depart'];
        $date_retour = $_POST['date_retour'] ?? null;
        $heure_depart = $_POST['heure_depart'];
        $heure_arrivee = $_POST['heure_arrivee'];
        $places_dispo = $_POST['places_dispo'];
        $prix = $_POST['prix'];
        $description = $_POST['description'] ?? '';
        $statut = isset($_POST['statut']) ? 1 : 0;

        $stmt = $pdo->prepare("INSERT INTO vols(id_aeroport_depart, id_aeroport_arrivee, date_depart, date_arrivee, heure_depart, heure_arrivee, ville_depart, ville_arrivee, places_dispo, prix, id_compagnie) VALUES(:aeroportD, :aeroportA, :dateD, :dateA, :heuD, :heuA, :villeD, :villeA, :place, :prix, :compagnie)");
        $success = $stmt->execute([
            ':compagnie' => $compagnie,
            ':aeroportD' => $aeroportD,
            ':aeroportA' => $aeroportA,
            ':dateD' => $date_depart,
            ':dateA' => $date_retour,
            ':heuD' => $heure_depart,
            ':heuA' => $heure_arrivee,
            ':villeD' => $ville_depart,
            ':villeA' => $ville_arrivee,
            ':place' => $places_dispo,
            ':prix' => $prix
        ]);
        
        if ($success) {
            header("Location: admin.php?message=succes");
        } else {
            header("Location: admin.php?message=error");
        }
        
    }
    $comps = $pdo->query("SELECT id_compagnie AS id, nom, code_compagnie FROM compagnie")->fetchAll();
$pagestyle = false;
$infolder = false;
$activepage = ' ';
?>
<?php require  '../header.php'; ?>

<main style="margin-top: 8rem;" class="container">
    <section class="row align-items-center gy-5">
        <div class="col-lg-7">
            <div class="bg-white rounded-4 shadow-sm p-5">
                <span class="badge bg-primary rounded-pill mb-3">Espace administrateur</span>
                <h1 class="display-6 fw-bold">Ajouter un vol</h1>
                <p class="lead text-muted">Utilisez ce formulaire pour préparer l’ajout d’un nouveau vol dans votre base de données.</p>

                <form method="post" class="mt-4">
                    <?php if (isset($_GET['message']) && $_GET['message']=="succes") {
                    ?>

                    <div class="w-100 bg-primary p-3 rounded-pill mb-3 text-white"><B>Succès !</B> Données enregistrées avec Succès</div>

                    <?php }else if(isset($_GET['message']) && $_GET['message']=="error") {
                        
                     ?>

                    <div class="w-100 bg-danger p-3 rounded-pill mb-3 text-white"><B>Erreur !</B> Données non enregistrées</div>
                    <?php }?>

                    <div class="row g-3">
                        <div class="col-md-12">
                            <div class="form-floating">
                                <select name="compagnie" class="form-control" id="">
                                    <?php foreach ($comps as $comp) {
                                        
                                    ?>
                                    <option value="<?= $comp['id']?>"><?= htmlspecialchars($comp['nom']) . $comp['id'] ?> [<?= htmlspecialchars($comp['code_compagnie']) ?>]</option>
                                    <?php } ?>
                                </select>
                                <label for="compagnie">Compagnie</label>                
                            </div>
                        </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="ville_depart" name="ville_depart" placeholder="Ville de départ" required>
                            <label for="ville_depart">Ville de départ</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select name="aeroport_depart" id="aeroport_depart" class="form-control" placeholder="Sélectionnez un aéroport" required>
                                <option value="">-- Sélectionnez une ville d'abord --</option>
                            </select>
                            <label for="aeroport_depart">Aéroport de départ</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="ville_arrivee" name="ville_arrivee" placeholder="Ville d'arrivée" required>
                            <label for="ville_arrivee">Ville d'arrivée</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select name="aeroport_arrivee" id="aeroport_arrivee" class="form-control" placeholder="Sélectionnez un aéroport" required>
                                <option value="">-- Sélectionnez une ville d'abord --</option>
                            </select>
                            <label for="aeroport_arrivee">Aéroport d'arrivée</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="date" class="form-control" id="date_depart" name="date_depart" placeholder="Date de départ" required>
                            <label for="date_depart">Date de départ</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="date" class="form-control" id="date_retour" name="date_retour" placeholder="Date de retour">
                            <label for="date_retour">Date de retour (facultatif)</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="time" class="form-control" id="heure_depart" name="heure_depart" placeholder="Heure de départ" required>
                            <label for="heure_depart">Heure de départ</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="time" class="form-control" id="heure_arrivee" name="heure_arrivee" placeholder="Heure d'arrivée" required>
                            <label for="heure_arrivee">Heure d'arrivée</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="number" class="form-control" id="places_dispo" name="places_dispo" placeholder="Places disponibles" min="0" required>
                            <label for="places_dispo">Places disponibles</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="number" class="form-control" id="prix" name="prix" placeholder="Prix" min="0" step="0.01" required>
                            <label for="prix">Prix (€)</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-floating">
                            <textarea class="form-control" placeholder="Description du vol" id="description" name="description" style="height: 120px;"></textarea>
                            <label for="description">Description du vol</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="statut" name="statut">
                            <label class="form-check-label" for="statut">Vol actif</label>
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex flex-column flex-sm-row gap-3">
                    <button type="submit" class="btn btn-primary btn-lg" name="insert">Enregistrer le vol</button>
                    <a href="index.php" class="btn btn-outline-secondary btn-lg">Retour à l'accueil</a>
                </div>
                </form>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="bg-primary text-white rounded-4 shadow-sm p-5 h-100">
                <h2 class="fw-bold">Résumé du vol</h2>
                <p>Ce panneau vous permet de visualiser rapidement les informations essentielles avant l’ajout.</p>
                <div class="mt-4">
                    <div class="mb-4">
                        <h6 class="text-uppercase small text-white-50">Itinéraire</h6>
                        <p class="fs-5">Départ → Arrivée</p>
                    </div>
                    <div class="mb-4">
                        <h6 class="text-uppercase small text-white-50">Dates & horaires</h6>
                        <p class="fs-6">Date de départ / Date de retour<br>Heure de départ / Heure d’arrivée</p>
                    </div>
                    <div class="mb-4">
                        <h6 class="text-uppercase small text-white-50">Capacité</h6>
                        <p class="fs-6">Places disponibles et prix par billet</p>
                    </div>
                    <div class="mb-4">
                        <h6 class="text-uppercase small text-white-50">Statut</h6>
                        <span class="badge bg-success">Actif</span>
                    </div>
                    <div class="mt-5">
                        <h6 class="text-uppercase small text-white-50">Notes</h6>
                        <p class="small">Complétez les champs ci-dessus pour préparer une entrée de vol complète.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
const airportsUrl = '<?= SITE_URL ?>/get_airports.php';

// Fonction pour récupérer les aéroports et remplir le select
async function loadAirports(inputId, selectId) {
    const inputElement = document.getElementById(inputId);
    const selectElement = document.getElementById(selectId);
    
    if (!inputElement || !selectElement) return;
    
    inputElement.addEventListener('input', async function(e) {
        const ville = this.value.trim();
        
        if (ville.length === 0) {
            selectElement.innerHTML = '<option value="">-- Sélectionnez une ville d\'abord --</option>';
            return;
        }
        
        selectElement.innerHTML = '<option value="">Aucun aéroport trouvé</option>';

        try {
            const response = await fetch(`${airportsUrl}?ville=${encodeURIComponent(ville)}`);
            const airports = await response.json();
            
            if (Array.isArray(airports) && airports.length > 0) {
                let html = '<option value="">-- Sélectionnez un aéroport --</option>';
                airports.forEach(airport => {
                    html += `<option value="${airport.id_aeroport}">${airport.nom} (${airport.code_aeroport})</option>`;
                });
                selectElement.innerHTML = html;
            } else {
                selectElement.innerHTML = '<option value="">Aucun aéroport trouvé</option>';
            }
        } catch (error) {
            console.error('Erreur lors du chargement des aéroports:', error);
            selectElement.innerHTML = '<option value="">Erreur de chargement</option>';
        }
    });
}

// Initialiser au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    loadAirports('ville_depart', 'aeroport_depart');
    loadAirports('ville_arrivee', 'aeroport_arrivee');
});
</script>

<?php require   '../footer.php'; ?>
</body>
</html>
