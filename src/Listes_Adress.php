<?php

namespace Radouane\Eligibility;

require_once('./config/ApiConfig.php');
require_once('../src/NdiDataFetcher.php');

use Radouane\Eligibility\NdiDataFetcher;

// Check if the idtown and idway parameters are set
if (!isset($_GET['codePostal']) && !isset($_GET['numVoie'])  ) {
    die('Les paramètres codePostal et num voie sont nécessaires.');
}

// Assign the values from $_GET to variables
$idtown = $_GET['codePostal'];
$idway = $_GET['numVoie'];

try {
    // Create an instance of NdiDataFetcher
    $ndiDataFetcher = new NdiDataFetcher($idtown, $idway);
    // Fetch data from the API
    $sortedResults = $ndiDataFetcher->fetchData();
    // Sort the results based on ndi_status
    usort($sortedResults, function($a, $b) {
        return $a['ndi_status'] <=> $b['ndi_status'];
    });
} catch (\Exception $e) {
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
<div class="row mb-3">
    <div class="col-md-1 text-center">
        <a href="template.php" class="btn btn-primary mx-auto">Retour</a>
    </div>
    <div class="col-md-3 text-center">
       <h3><strong>NDI Disponible</strong></h3> 
    </div>
</div>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">État</th>
                <th scope="col">Numéro</th>
                <th scope="col">Titulaire/Prédécesseur</th>
                <th scope="col">N° Voie</th>
                <th scope="col">Voie</th>
                <th scope="col">Commune</th>
                <th scope="col">Code Postal</th>
                <th scope="col">Résidence</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($sortedResults as $resultat):?>
            <tr>
                <td>
                    <?php if ($resultat['ndi_status'] === 'active'): ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-check-circle text-success" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 1.5a6.5 6.5 0 1 0 0 13 6.5 6.5 0 0 0 0-13zm4.97 4.97a.75.75 0 0 1 1.06 1.06l-7 7a.75.75 0 0 1-1.06 0l-3.5-3.5a.75.75 0 1 1 1.06-1.06L7 10.94l6.97-6.97a.75.75 0 0 1 1.06 0z"/>
                    </svg>
                    <span class="text-success ms-2">Active</span>
                    <?php else: ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-x-circle text-danger" viewBox="0 0 16 16">
                         <path fill-rule="evenodd" d="M8 1.5a6.5 6.5 0 1 0 0 13 6.5 6.5 0 0 0 0-13zm0 1a5.5 5.5 0 1 1 0 11 5.5 5.5 0 0 1 0-11zM5.354 5.354a.5.5 0 0 1 .708 0L8 7.293l2.938-2.939a.5.5 0 0 1 .707.708L8.707 8l2.938 2.938a.5.5 0 0 1-.708.708L8 8.707l-2.938 2.938a.5.5 0 1 1-.708-.708L7.293 8 4.354 5.062a.5.5 0 0 1 0-.708z"/>
                        </svg><span class="text-danger ms-2">Inactive</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php 
                    $latitude = $resultat['latitude'];
                    $status = $resultat['ndi_status'];
                    $idtown = $resultat['idtown'];
                    $street = $resultat['street'];
                    $longitude = $resultat['longitude'];
                    $street_number = $resultat['street_number'];
                    $zip = $resultat['zip'];
                    $city = $resultat['city'];
                    ?>
                    <a class="lienEligible" href="/src/eligible.php?ndi=<?php echo urlencode($resultat['ndi']); ?>&ndi_status=<?php echo urlencode($status); ?>&idtown=<?php echo $idtown; ?>&street_number=<?php echo $street_number; ?>&street=<?php echo $street; ?>&city=<?php echo $city; ?>&zip=<?php echo $zip; ?>&number=<?php echo $street_number; ?>&latitude=<?php echo $latitude; ?>&longitude=<?php echo $longitude; ?>">
                        <?php echo $resultat['ndi']; ?>
                    </a>
                </td>
                <td><?php echo $resultat['name']; ?></td>
                <td><?php echo $resultat['rivoli_number']; ?></td>
                <td><?php echo $resultat['street']; ?></td>
                <td><?php echo $resultat['city']; ?></td>
                <td><?php echo $resultat['zip']; ?></td>
                <td><?php echo $resultat['set']; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<!-- Inclure Bootstrap JS (optionnel, seulement si vous avez besoin de fonctionnalités JS de Bootstrap) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>


</body>
</html>
