<?php

namespace Radouane\Eligibility;
use Radouane\Eligibility\ApiConfig;
require_once('./config/ApiConfig.php');

class CountryCodesFetcher
{

    public function fetchCountryCodes()
    {

        $url = ApiConfig::getEndpointUrl();
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
            $codes[] = $code['iso_alpha2'];
        }

        return $codes;
    }
}

?>
