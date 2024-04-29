<?php

namespace Radouane\Eligibility;
use Radouane\Eligibility\ApiConfig;
require_once('./config/ApiConfig.php');

class StreetFetcher
{
    public function fetchStreet($idtown, $street)
    {
        $url = ApiConfig::getStreetpointUrl($idtown, $street);
        $credentials = ApiConfig::getCredentials();
        $username = $credentials['login'];
        $password = $credentials['password'];

        $options = [
            'http' => [
                'header'  => "Content-Type: application/json\r\n" .
                             "Authorization: Basic " . base64_encode("$username:$password"),
                'method'  => 'GET',
            ],
        ];

        $context  = stream_context_create($options);
        $response = file_get_contents($url, false, $context);

        if ($response === false) {
            return []; // Retourne un tableau vide si la requête API échoue
        }

        $data = json_decode($response, true);

        if (empty($data)) {
            return []; // Retourne un tableau vide si aucune donnée n'est retournée de l'API
        }

        $codes = [];
        foreach ($data as $code) {
            $codes[] = $code['street_official'];
        }

        return $codes;
    }
}

// Instance de la classe StreetFetcher
$streetFetcher = new StreetFetcher();

// Vérifie si les paramètres codePostal et nomVoie sont fournis dans la requête
if (isset($_GET['codePostal']) && isset($_GET['nomVoie'])) {
    $idtown = $_GET['codePostal'];
    $street = $_GET['nomVoie'];
    $streetNames = $streetFetcher->fetchStreet($idtown, $street);
    echo json_encode($streetNames); // Convertis le tableau en JSON pour l'affichage
}
