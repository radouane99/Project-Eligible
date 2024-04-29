<?php
require_once('../src/check_eligible_ByAdress.php');
require_once('../src/check_eligible_byNDI.php');

use Radouane\Eligibility\EligibilityChecker;
use Radouane\Eligibility\ApiDataFetcher;

// Vérifier si les paramètres requis sont présents dans $_GET
if (!isset($_GET['ndi']) ){
    die('Les paramètres ndi, ndi_status, idtown et street sont nécessaires. here');
}
// Assigner les valeurs de $_GET à des variables
$ndi = $_GET['ndi'];
$ndiStatus = $_GET['ndi_status'] ?? 'N/A';
$idTown = $_GET['idtown'] ?? 'N/A';
$street = $_GET['street'] ?? 'N/A';
$streetNumber = $_GET['street_number'] ?? 'N/A';
$latitude = $_GET['latitude'] ?? 'N/A';
$longitude = $_GET['longitude'] ?? 'N/A';
$hexacle = $_GET['hexacle'] ?? 'N/A';

// Créer une instance de EligibilityChecker
$eligibilityChecker = new EligibilityChecker($ndi, $ndiStatus, $idTown, $street, $streetNumber, $latitude, $longitude, $hexacle);
$apiFetcher =  new ApiDataFetcher($ndi); // Correction de la variable $ApiDataFetcher à $apiFetcher

try {
    // Obtenir le nombre total d'offres éligibles
    $totalOffers = $eligibilityChecker->getTotalOffers();
    $processedData = $eligibilityChecker->processData();
    $idOffersCount = $eligibilityChecker->countIdOffers($processedData);
    $sites = $eligibilityChecker->extractSite($processedData);
     $groupedData = $eligibilityChecker->groupDataBySection($processedData);
     //var_dump($groupedData);
    // ---------------------------------------------------------------
    $processDataUser = $apiFetcher->processData();
    $userInfo = json_decode($apiFetcher->getUserInfo($processDataUser), true);





} catch (Exception $e) {
    die('Une erreur est survenue : ' . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include_once('./Header/header.php'); ?>
</head>
<body>
<div class="container">
    <!-- Première ligne -->
    <div class="row">
        <div class="col-md-3">
            <strong>Eligibilité</strong>
        </div>
        <div class="col-md-5">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Egibilité</a></li>
                    <li class="breadcrumb-item"><a href="index.php">NDI DISPONIBLES</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Les offres éligibles</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Deuxième ligne -->
    <div class="row mb-3">
        <!-- Première colonne -->
        <div class="col-md-2 text-start">
            <a href="index.php" class="btn btn-primary">Accueil</a>
        </div>
        <div class="col-md-3">
            Les Offres Eligibles
            <a href="index.php" class="btn btn-primary">Retour</a>
        </div>
    </div>  
    <div class="row mb-4">
         <div class="col-md-3">
        </div>
        <div class="col-md-3 text-start">
            <strong>votre recherche</strong>
        <button type="button" class="btn btn-primary" ><i class="bi bi-pencil"></i></button>
        <br>
            <?php foreach ($userInfo['list'] as $element) : ?>
                <?php
                echo $element['street_number'] . " " . $element['street'] . ", " . $element['zip'] . " " . $element['city'] . "<br>";
                echo "NDI: " . $userInfo['ndi'] . "<br>";
                echo "GBS: " . $element['latitude'] . ", " . $element['longitude'] . "<br>";
                ?>
            <?php endforeach; ?>
    </div>

        <!-- Deuxième colonne -->
        <div class="col-md-2" id="maps">
            <script src="./js/script.js"></script>
            <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
        <script>
             // Coordonnées géographiques
             var latitude = <?php echo $element['latitude']; ?>;
             var longitude = <?php echo $element['longitude']; ?>;
            // Appeler la fonction pour initialiser la carte
            initializeMap(latitude, longitude);
            </script>
        </div>
        <!-- Troisième colonne -->
        <div class="col-md-2">
            <h3>Information technique</h3>
            <label for="site">Site: <strong><?php echo $sites; ?></strong></label>
        </div>
    </div>
    <!-- Troisième ligne -->
    <div class="container">
        <div class="row mb-3">
            <div class="col-md-3">
                <!-- Bouton de recherche avec une icône de recherche -->
                <button type="button" class="btn btn-primary" onclick="window.location.href = 'index.php';"><i class="bi bi-search"></i></button>
                <label>Nouvelle recherche</label>
            </div>
            <div class="col-md-6">
                <!-- Élément pour la promotion de migration vers la fibre -->
                <label for="fiber_promotion" style="color: green;">Promo Fermeture réseau cuivre : promotion de migration vers la fibre jusqu'au 31/12/2023</label>
            </div>
            <div class="col-md-3">
                <!-- Bouton pour réduire la div -->
                <!-- Label pour afficher le total des offres -->
                <label for="total_offers">Les offres éligibles: <span id="total_offers"><?php echo $totalOffers; ?></span></label>
                <!-- Utilisation de la balise span pour afficher le total -->
                <button type="button" class="btn btn-primary" id="toggleButton"><i class="fas fa-minus"></i></button>
            </div>
        </div>
    </div>
    <div class="table-container">
        <div class="row">
            <div class="col">
            <script src="./js/script.js"></script>
                <!-- Bouton pour Tout afficher -->
                <button class="btn btn-primary toggle-all" data-action="show">Tout reduire</button>
                <?php foreach ($groupedData as $section => $offres) : ?>
                    <!-- Affichage du titre de la section avec le bouton "Afficher" -->
                    <h2><?php echo $section; ?> (<?php echo count($offres); ?> offres)
                        <button class="btn btn-link toggle-section">Masquer</button>
                    </h2>
                    <!-- Tableau des offres pour cette section (affiché par défaut) -->
                    <table class="table table-bordered section-table">
                        <!-- En-tête du tableau -->
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">Opérateur</th>
                            <th scope="col">Offre</th>
                            <th scope="col">Abonnement mensuel</th>
                            <th scope="col">Débit</th>
                            <th scope="col">Engagement</th>
                            <th scope="col">FAS</th>
                            <th scope="col">Commander</th>
                        </tr>
                        </thead>
                        <!-- Corps du tableau -->
                        <tbody>
                        <?php foreach ($offres as $offre) : ?>
                            <tr>
                                <td><?php echo explode('|', $offre['operateur'])[0]; ?></td>
                                <td><?php echo $offre['offre']; ?></td>
                                <td><?php echo $offre['abonnement'] == '0' ? 'Sur devis' : $offre['abonnement'].'€'; ?></td>
                                <td><?php echo $offre['debit']; ?></td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input btn-orange" type="checkbox" value="1" <?php echo $offre['engagement'] == '1 an' ? 'checked' : 'disabled'; ?>>
                                        <label class="form-check-label">1 an</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input btn-orange" type="checkbox" value="2" <?php echo $offre['engagement'] == '2 ans' ? 'checked' : 'disabled'; ?>>
                                        <label class="form-check-label">2 ans</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input btn-orange" type="checkbox" value="3" <?php echo $offre['engagement'] == '3 ans' ? 'checked' : 'disabled'; ?>>
                                        <label class="form-check-label">3 ans</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input btn-orange" type="checkbox" value="0" <?php echo $offre['engagement'] == 'Sans engagement' ? 'checked' : 'disabled'; ?>>
                                        <label class="form-check-label">Sans engagement</label>
                                    </div>
                                    <!-- Ajoutez des options d'engagement supplémentaires ici -->
                                </td>
                                <td>
                                    <strong>sur devis</strong>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-orange">Commander</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- Inclure Bootstrap JS (si nécessaire) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
    // Sélectionnez tous les boutons "Afficher"
    var toggleButtons = document.querySelectorAll('.toggle-section');

    // Ajoutez un écouteur d'événements à chaque bouton
    toggleButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            // Trouvez la section parente du bouton
            var section = button.closest('h2').nextElementSibling;

            // Afficher ou masquer la section en fonction de son état actuel
            if (section.style.display === 'none') {
                section.style.display = 'table';
                button.textContent = 'Masquer';
            } else {
                section.style.display = 'none';
                button.textContent = 'Afficher';
            }
        });
    });

    // Sélectionnez le bouton "Tout afficher" et "Tout réduire"
    var toggleAllButtons = document.querySelectorAll('.toggle-all');
    // Ajoutez un écouteur d'événements à chaque bouton "Tout afficher" et "Tout réduire"
    toggleAllButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            // Obtenez l'action à effectuer (afficher ou masquer)
            var action = button.dataset.action;

            // Sélectionnez tous les tableaux de section
            var sectionTables = document.querySelectorAll('.section-table');

            // Parcourez chaque tableau de section et appliquez l'action appropriée
            sectionTables.forEach(function(table) {
                table.style.display = action === 'show' ? 'table' : 'none';
            });

            // Mettez à jour le texte des boutons
            toggleAllButtons.forEach(function(btn) {
                btn.textContent = action === 'show' ? 'Tout réduire' : 'Tout afficher';
                btn.dataset.action = action === 'show' ? 'hide' : 'show';
            });
        });
    });
</script>

</body>
</html>
