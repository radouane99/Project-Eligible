<?php
namespace Radouane\Eligibility;
use Radouane\Eligibility\ApiConfig;
require_once('./config/ApiConfig.php');
class TownFetcher
{  
    // Déclaration des propriétés de classe
    private $street;
    private $codePostal;

    // Constructeur de la classe
    public function __construct()
    {
        // Initialisation des propriétés de classe
        $this->street = isset($_GET['street']) ? $_GET['street'] : '';
        $this->codePostal = isset($_GET['codePostal']) ? $_GET['codePostal'] : '';
    }

    public function getVille($codePostal)
    {
        // Check if the codePostal parameter is set
        if (!isset($codePostal) || empty($codePostal)) {
            return  $codePostal;
        }
        $url = ApiConfig::getTownEndpointUrl($codePostal);
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
    
    
        // Check if the API request was successful
        if ($response === FALSE) {
            return json_encode(array('error' => 'Erreur lors de la récupération des données de l\'API'));
        }
    
        // Decode the JSON response
        $data = json_decode($response, true);
    
        // Check if the data is empty
        if (empty($data)) {
            return json_encode(array('error' => 'Aucune ville trouvée pour ce code postal'));
        }
    
        // Extract the city names into an array
        $cities = array();
        foreach ($data as $ville) {
            $cities[] = $ville['town'];
        }
    
        // Return the list of cities as JSON
        return json_encode($cities);
    }
    // Méthodes pour récupérer les valeurs des propriétés de classe
    public function getStreet()
    {
        return $this->street;
    }

    public function getCodePostal()
    {
        return $this->codePostal;
    }
    public function fetchStreet($idtown, $street)
    {
        $url = ApiConfig::getStreetpointUrl($idtown,$street);
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
            return array(); // Return an empty array if the API request fails
        }

        $data = json_decode($response, true);

        if (empty($data)) {
            return array(); // Return an empty array if no data is returned from the API
        }
        $codes = array();
        foreach ($data as $code) {
            $codes[]['street'] = $code['street_official'];
            $codes[]['idway'] = $code['idway'];
        }
        return json_encode($codes);
    }

    
}

// Instantiation de la classe TownFetcher
$townFetcher = new TownFetcher();
$codePostal =  isset($_GET['codePostal'])? isset($_GET['codePostal']) : '';
$street = isset($_GET['street']) ? isset($_GET['street']): '';
// Appel des méthodes appropriées en fonction des paramètres de la requête GET
$street = $townFetcher->getStreet();
$codePostal = $townFetcher->getCodePostal();

// Vérifier si la clé "street" existe et n'est pas vide
if (!empty($street)) {
    // Si la clé "street" existe et n'est pas vide, afficher la ville et la rue
    echo $townFetcher->fetchStreet($codePostal, $street);
} else {
    // Si la clé "street" n'est pas définie ou est vide, afficher uniquement la ville
    echo $townFetcher->getVille($codePostal);
}

?>