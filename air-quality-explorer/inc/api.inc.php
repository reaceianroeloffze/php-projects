<?php


/** ============================================
 * The API handler file for Air Quality Explorer
 * ============================================= */

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/functions.inc.php';

// Start a new Guzzle session with base uri
$client = new GuzzleHttp\Client(['base_uri' =>'https://api.openaq.org']);

// Retrieve API country data
$response_countries = $client->get('/v3/countries?limit=1000', [
    'headers' => [
        'Content-Type' => 'application/json',
        'X-API-KEY' => getenv('OpenAQ_API_KEY'), // Use your own api key
    ]
]);

// Decode the response and turn it into a multidimensional array
$responseArrayCountries = json_decode($response_countries->getBody(), true);

// Provide an array of specific countries
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

// Filter the response multidimensional to contain only the selected countries
$filteredResponseArray = array_filter($responseArrayCountries['results'], fn ($country) => in_array($country['name'], $SelectedCountries));
print_r($filteredResponseArray);

// Alternate syntax for filtering. Using for reference
/*
$filteredResponseArray = array_filter($responseArray['results'], function ($country) use ($SelectedCountries) {
    return in_array($country['name'], $SelectedCountries);
});
*/

// Store the id of each country in the multidimensional array in a variable
$country_id = '';
foreach ($filteredResponseArray AS $country) {
    $country_id = $country['id'];
}

// Request the locations of air quality stations based on the country's id
$response_locations = $client->get("/v3/locations?countries_id={$country_id}&limit=1000", [
    'headers' => [
        'Content-Type' => 'application/json',
        'X-API-KEY' => getenv('OpenAQ_API_KEY'),
    ]
]);

// Convert response to a multidimensional array
$responseArrayLocations = json_decode($response_locations->getBody(), true);
print_r($responseArrayLocations);

// Store the id of each location in a variable
$location_id = '';
foreach ($responseArrayLocations['results'] AS $location) {
    $location_id = $location['id'];
}

// Get air quality sensors based on the location's id
$response_sensors = $client->get("/v3/location/{$location_id}/sensors", []);
$responseArraySensors = json_decode($response_sensors->getBody(), true);

