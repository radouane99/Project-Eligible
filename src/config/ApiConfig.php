<?php
namespace Radouane\Eligibility;
class ApiConfig {
    // URL de base de l'API
    const API_BASE_URL = 'https://demo-xvno.ema.expert/ema/api/v1/';
    // Identifiants de connexion
    const LOGIN = '14484';
    const PASSWORD = '3fca31f5bee9e79e6951a1192c991e1fb05fdf9ebe912e4fbdfb6d5feb79663e525e7d36bd10ae271425f232b55b15e8e166bcc65f5d350509d9f5c4e5f8cad5';

    // Méthode statique pour obtenir l'URL de vérification d'éligibilité du détaillant
    public static function getRetailerEligibilityUrl() {
        return self::API_BASE_URL . 'retailer_eligibility';
    }
    // Méthode statique pour obtenir l'URL de l'endpoint
    public static function getEndpointUrl() {
        return self::API_BASE_URL . 'endpoint/country/';
    }
    // Méthode statique pour obtenir l'URL de l'endpoint ndi_by_address
    public static function getNdiByAddressUrl($idtown, $idway) {
        return self::API_BASE_URL . 'endpoint/ndi_by_address/?idtown=' . urlencode($idtown) . '&idway=' . urlencode($idway);
    } 
    public static function getRetailer_Repackaging_byNdi($tel) {
        return self::API_BASE_URL . 'endpoint/ndi/?ndi=' . urlencode($tel);
    } 
    public static function getRetailer_Repackaging($ndi, $ndi_status, $idtown,$street,$number,$latitude,$longitude,$hexacle) {
        return self::API_BASE_URL . 'retailer_eligibility/?ndi=' . urlencode($ndi) . '&ndi_status=' . urlencode($ndi_status) . '&idtown=' . urlencode($idtown). '&street='  . urlencode($street). 'number=' . urlencode($number). 'latitude=' . urlencode($latitude). 'longitude=' . urlencode($longitude). 'hexacle=' . urlencode($hexacle);
    }
    // Méthode statique pour obtenir les en-têtes d'authentification
    public static function getAuthHeaders() {
        $login = self::LOGIN;
        $password = self::PASSWORD;
        $credentials = base64_encode("$login:$password");
        return [
            'Authorization: Basic ' . $credentials
        ];
    }
    // Méthode statique pour obtenir l'URL de base de l'API
    public static function getBaseUrl() {
        return self::API_BASE_URL;
    }
    public static function getTownEndpointUrl($codePostal) {
        return self::API_BASE_URL . 'endpoint/town/?zip=' . urlencode($codePostal);
    } public static function getStreetpointUrl($idtown,$street) {
        return self::API_BASE_URL . 'endpoint/street/?idtown=' . urlencode($idtown). '&street=' .urlencode($street);
    }

    // Méthode statique pour obtenir les identifiants de connexion
    public static function getCredentials() {
        return [
            'login' => self::LOGIN,
            'password' => self::PASSWORD
        ];
    }
}
