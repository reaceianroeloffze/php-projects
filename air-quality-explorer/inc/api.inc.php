<?php

/** ============================================
 * The API handler file for Air Quality Explorer
 * ============================================= */

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/functions.inc.php';

use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Uri;

$limit = 1000;
$page = 1;
$offset = 0;

// Start a new Guzzle session with base uri
$client = new Client(['base_uri' => 'https://api.openaq.org']);

// Retrieve API country data
$response_countries = generateGetRequest($client, "/v3/countries?limit={$limit}");
// Decode the response and turn it into a multidimensional array
$responseArrayCountries = generateResponseBody($response_countries);
$responseArrayCountries = $responseArrayCountries['results'];

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

// Filter the response multidimensional array to contain only the selected countries
$filteredCountriesResponseArray = array_filter($responseArrayCountries, fn($country) => in_array($country['name'], $SelectedCountries));

// Alternate syntax for filtering. Using for reference
/*
$filteredResponseArray = array_filter($responseArray['results'], function ($country) use ($SelectedCountries) {
    return in_array($country['name'], $SelectedCountries);
});
*/

// Store the id of each country in the multidimensional array in a variable
$country_ids = [];
foreach ($filteredCountriesResponseArray as $country) {
    $country_ids[$country['name']] = $country['id'];
}
$country_ids = array_values($country_ids);
$country_id_string = implode(',', $country_ids);
// print_r($country_ids);

// Request the locations of air quality stations based on the country's id
$locations = [];
for ($page = 1; $page <= 5; $page++) {
    $response_locations = generateGetRequest($client, "/v3/locations?countries_id={$country_id_string}&limit={$limit}&page={$page}");
    // Convert response to a multidimensional array
    $responseArrayLocations = generateResponseBody($response_locations);
    $responseArrayLocations = $responseArrayLocations['results'];
    $locations = array_merge($locations, $responseArrayLocations);
}


$parameters = [
    'pm25',
    'pm10',
];

// Set a maximum number of locations to display per country
$maxLocationsPerCountry = 10;
$locationsGroupedByCountry = filterLocationsByCountry($responseArrayLocations, $parameters, $maxLocationsPerCountry);
print_r($locationsGroupedByCountry);

/*
// Store the id of each sensor in a variable
$sensor_id = '';
foreach ($responseArraySensors['results'] AS $sensor) {
    $sensor_id = $sensor['id'];
}

// Get air quality measurements based on sensor id
$response_measurements = $client->get("/v3/sensors/{$sensor_id}/hours", generateHeaders());
*/