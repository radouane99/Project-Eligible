<?php
namespace Radouane\Eligibility;
use Radouane\Eligibility\ApiConfig;
require_once('./config/ApiConfig.php');

class EligibilityChecker {
    // Properties
    private $ndi;
    private $ndiStatus;
    private $idTown;
    private $street;
    private $streetNumber;
    private $latitude;
    private $longitude;
    private $hexacle;
    private $processDataGroup = null;

    // Constructor
    public function __construct($ndi, $ndiStatus, $idTown, $street, $streetNumber, $latitude, $longitude, $hexacle) {
        $this->ndi = $ndi;
        $this->ndiStatus = $ndiStatus;
        $this->idTown = $idTown;
        $this->street = $street;
        $this->streetNumber = $streetNumber;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->hexacle = $hexacle;
    }

    // Method to fetch data from the API
    public function fetchDataFromApi() {
        $url = ApiConfig::getRetailer_Repackaging($this->ndi, $this->ndiStatus, $this->idTown, $this->street, $this->streetNumber, $this->latitude, $this->longitude, $this->hexacle);
        $credentials = ApiConfig::getCredentials();
        $username = $credentials['login'];
        $password = $credentials['password'];

        $options = array(
            'http' => array(
                'header'  => "Content-Type: application/json\r\n" .
                             "Authorization: Basic " . base64_encode("$username:$password"),
                'method'  => 'GET',
            ),
        );

        $context  = stream_context_create($options);
        $response = file_get_contents($url, false, $context);

        if ($response === FALSE) {
            throw new \Exception('Erreur lors de la récupération des données de l\'API');
        }

        $data = json_decode($response, true);

        if (empty($data) || !isset($data['response']) || !isset($data['data'])) {
            throw new \Exception('Aucune Data trouvée');
        }

        return $data;
    }

    // Method to process data
    public function processData() { 
        $jsonData = $this->fetchDataFromApi();
        return $jsonData;
    }  
    public function groupDataBySection($processedData)
    {
        // Initialisation du tableau des données groupées par section
        $donnees_par_section = [];
        // Parcours de toutes les offres disponibles
        foreach ($processedData['data'] as $offer) {
            foreach ($offer['list'] as $section) {
                foreach ($section['list'] as $key => $list) {
                    $operateur = $list['providers'];
                    foreach ($list['list'] as $key => $item) {
                        $offre = $item['offer'];
                        $abonnement = $item['amt_recur'];
                        $debit = $item['debit'];
                        $engagement = $item['engagement'];
                        $section = $item['section'];

                        // Ajout des données de cette offre dans le tableau des données groupées par section
                        $donnees_par_section[$section][] = [
                            'operateur' => $operateur,
                            'offre' => $offre,
                            'abonnement' => $abonnement,
                            'debit' => $debit,
                            'engagement' => $engagement,
                            'section' => $section
                        ];
                    }
                }
            }
        }
        return $donnees_par_section;
    }


    // Method to extract site information
    public function extractSite($processedData) {
        $site = "Information non disponible";
        if (isset($processedData['info']['site'])) {
            $site = $processedData['info']['site'];
        }

        return $site;
    }
    // Method to get the total number of offers
    public function getTotalOffers() {
        $processedData = $this->fetchDataFromApi();
        return $this->countIdOffers($processedData);
    }

    // Recursive method to count occurrences of 'idoffer'
    public function countIdOffers($processedData) {
        $count = 0;
        if (is_array($processedData)) {
            foreach ($processedData as $key => $value) {
                if (is_array($value)) {
                    $count += $this->countIdOffers($value);
                } else {
                    if ($key === 'idoffer') {
                        $count++;
                    }
                }
            }
        }
        return $count;
    }
}
?>