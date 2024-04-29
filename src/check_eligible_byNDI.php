<?php

namespace Radouane\Eligibility;
use Radouane\Eligibility\ApiConfig;
require_once('./config/ApiConfig.php');

class ApiDataFetcher {
    private $ndi;

    public function __construct($ndi) {
        $this->ndi = $ndi;
    }

    public function fetchDataFromApi() {
        $url = ApiConfig::getRetailer_Repackaging_byNdi($this->ndi);
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
            throw new \Exception('Erreur lors de la récupération des données de l\'API');
        }

        $data = json_decode($response, true);

        if (empty($data) || !isset($data['response']) || !isset($data['data'])) {
            throw new \Exception('Aucune Data trouvée');
        }

        return $data;
    }

    public function processData() {
        $jsonData = $this->fetchDataFromApi();
        return $jsonData;
    }

    public function getUserInfo($processedData) {
        try {
            $donnees = [
                'ndi' => $processedData['data']['ndi'],
                'ndi_status' => $processedData['data']['ndi_status'],
                'list' => []
            ];

            foreach ($processedData['data']['list'] as $list) {
                $donnees['list'][] = [
                    'name' => $list['name'],
                    'idtown' => $list['idtown'],
                    'street_number' => $list['street_number'],
                    'street' => $list['street'],
                    'zip' => $list['zip'],
                    'city' => $list['city'],
                    'latitude' => $list['latitude'],
                    'longitude' => $list['longitude']
                ];
            }

            return json_encode($donnees);
        } catch (\Exception $e) {
            return json_encode(['error' => $e->getMessage()]);
        }
    }
}
