<?php
namespace Radouane\Eligibility;
use Radouane\Eligibility\CountryCodesFetcher;
require_once('./config/ApiConfig.php');
// Inclure les classes nécessaires
require_once '../src/get_endpoint_country.php';
require_once '../src/get_ville_ByCode.php';


// Initialiser les variables
$codePostal = "";
// Créer une instance de la classe CountryCodesFetcher pour récupérer les codes de pays
$countryCodesFetcher = new CountryCodesFetcher();
$countryCodes = $countryCodesFetcher->fetchCountryCodes();
sort($countryCodes);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<?php include_once('./Header/header.php'); ?>
</head>
<body class="bg-body-tertiary">
    <div class="container">
        <main>
          <div class="row g-5">
          <div class="col-sm-3 left">
                <strong>Egilibité</strong>
            </div>
            <div class="col-sm-3">
                <!-- Navigation -->
            <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" class="text-dark text-decoration-none" >Commandes</a></li>
                <li class="breadcrumb-item active" aria-current="page"><i class="bi bi-chevron-right text-danger"></i> Eligibilité</li>
              </ol>
            </nav>
            </div>
          </div>
            <!-- Navigation -->
            <div class="col-sm-3">
                <ul class="list-inline">
                    <li class="list-inline-item"><a href="#"><i class="bi bi-box"></i></a></li>
                    <li class="list-inline-item">Accueil</li>
                </ul>
            </div>
            <!-- Formulaire de recherche rapide -->
        <div class="row g-5 row-gap">
                <h4>Rechercher Rapide</h4>
          </div>
          <div class="row g-5 row-gap">
          <form class="card p-1" id="myForm" method="GET" action="./eligible.php" method="GET">
    <!-- Contenu du formulaire -->
    <div class="needs-validation">
        <div class="row g-3">
            <!-- Première colonne -->
            <div class="col-sm-4">
                <label for="address" class="form-label">Votre adresse postale</label>
                <input type="text" class="form-control" id="address" name="address" placeholder="Ex: 60 Avenue Montaigne, Paris, France" disabled>
            </div>
            <!-- Deuxième colonne -->
            <div class="col-sm-4">
                <label for="tel" class="form-label">Votre numéro de téléphone</label>
                <div class="input-group ">
                    <select class="form-select form-select-sm" id="countryCodes">
                        <?php foreach ($countryCodes as $code): ?>
                            <option value="<?php echo $code; ?>"><?php echo $code; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="ndi" class="form-control" id="ndi" name="ndi" placeholder="Enter telephone number" required>
                </div>
            </div>
            <div class="col-sm-2">
                <label for="codeImmeuble" class="form-label">Code Immeuble</label>
                <input type="text" class="form-control" id="codeImmeuble" name="codeImmeuble" placeholder="Enter building code" disabled>
            </div>
            <div class="col-sm-2">
                 <label></label>
                 <label></label>
                <button type="submit" class="btn btn-primary" id="submitBtn">Valider</button>
            </div>
        </div>
    </div>
</form>



        </div>

        <div class="row g-5 row-gap">
            <div class="col-md-4">
            <div class="card">
                  <div class="card-body">
                      <h5 class="card-title">Paramétrages <i class="bi bi-gear"></i></h5>
                      <!-- Ajoutez ici le contenu des paramétrages -->
                  </div>
              </div>
            </div>

            <!-- Colonne du centre -->
            <div class="col-md-4">
                <!-- Formulaire d'adresse -->
                <form class="card p-2" action="./Listes_Adress.php" method="GET">
                    <!-- Contenu du formulaire -->
                    <div class="needs-validation">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="codePostal" class="form-label">Code Postal</label>
                                <input type="text" class="form-control" id="codePostal" name="codePostal" required>
                                <div id="cityList"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="ville" class="form-label">Ville</label>
                                <select class="form-select" id="ville" name="ville" required>
                                    <option value="">Sélectionnez une ville</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="nomVoie" class="form-label">Nom de Voie</label>
                                <input type="text" class="form-control" id="nomVoie" name="nomVoie" required>
                                <!-- Liste déroulante pour afficher les suggestions de voie -->
                                <select id="streetSuggestions" class="form-select">
                                    <option value="">Sélectionnez une rue</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="numVoie" class="form-label">N° Voie</label>
                                <input type="text" class="form-control" id="numVoie" name="numVoie" required>
                                <!-- Liste déroulante pour afficher les suggestions de voie -->
                                <select id="numVoieSuggestions" class="form-select">
                                    <option value="">Sélectionnez une N ° voie</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <strong>OU</strong>
                            </div>
                            <div class="col-md-6">
                                <label for="latitude" class="form-label">Latitude</label>
                                <input type="text" class="form-control" id="latitude" name="latitude">
                            </div>
                            <div class="col-md-6">
                                <label for="longitude" class="form-label">Longitude</label>
                                <input type="text" class="form-control" id="longitude" name="longitude">
                            </div>
                            <div class="col-md-12 mt-3">
                                <button type="submit" class="btn btn-primary">lancer la recherche</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- Colonne de droite -->
            <div class="col-md-4">
                <!-- Carte ou contenu supplémentaire -->
                <div id="map"></div>
            </div>
            </div>
        </main>
    </div>
    <!-- Inclure les scripts JavaScript à la fin du corps de la page -->
    <script src="./js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet@1.7.1/dist/leaflet.js"></script>   
</body>
</html>
