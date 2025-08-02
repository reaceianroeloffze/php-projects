<?php


/** ============================================
 * The API handler file for Air Quality Explorer
 * ============================================= */

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/functions.inc.php';
require_once __DIR__ . '/api-key.inc.php'; // Create file and store api key there

// Start a new Guzzle session
$client = new GuzzleHttp\Client();

// Retrieve API data
$response = $client->get('https://api.openaq.org/v3/countries', [
    'headers' => [
        'Content-Type' => 'application/json',
        'X-API-KEY' => $apiKey, // Use own api key
    ]
]);

$responseArray = json_decode($response->getBody(), true);
//print_r($response);

$SelectedCountries = [
    'Germany',
    'South Africa',
    'United Kingdom',
    'Japan',
    'Canada',
    'New Zealand',
    'Costa Rica',
    'Italy',
];
/*
$filteredResponseArray = array_filter($responseArray['results'], function ($country) use ($SelectedCountries) {
    return in_array($country['name'], $SelectedCountries);
});
*/

$filteredResponseArray = array_filter($responseArray['results'], fn ($country) => in_array($country['name'], $SelectedCountries));
