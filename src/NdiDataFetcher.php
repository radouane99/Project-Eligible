<?php

namespace Radouane\Eligibility;
use Radouane\Eligibility\ApiConfig;
require_once('./config/ApiConfig.php');

class NdiDataFetcher
{
    private $codePostal;
    private $numVoie;
    private $url;
    private $credentials;

    public function __construct($codePostal, $numVoie)
    {
        $this->codePostal = $codePostal;
        $this->numVoie = $numVoie;
        $this->url = ApiConfig::getNdiByAddressUrl($codePostal, $numVoie);
        $this->credentials = ApiConfig::getCredentials();
    }

    public function fetchData()
    {
        $options = [
            'http' => [
                'header'  => "Content-Type: application/json\r\n" .
                             "Authorization: Basic " . base64_encode("{$this->credentials['login']}:{$this->credentials['password']}"),
                'method'  => 'GET',
            ],
        ];

        $context  = stream_context_create($options);
        $response = file_get_contents($this->url, false, $context);

        if ($response === false) {
            $error = error_get_last();
            throw new \Exception('Erreur lors de la récupération des données de l\'API: ' . $error['message']);
        }

        $data = json_decode($response, true);

        if (empty($data) || !isset($data['response']) || !isset($data['data'])) {
            throw new \Exception('Aucune NDI trouvé');
        }

        return $data['data']['list'];
    }
}
