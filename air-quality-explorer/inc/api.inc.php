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

// Start a new Guzzle session with base uri
$client = new Client(['base_uri' => 'https://api.openaq.org']);

// Retrieve API country data
$response_countries = generateGetRequest($client, '/v3/countries?limit=1000');
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
