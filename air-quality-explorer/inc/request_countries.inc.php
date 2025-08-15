<?php

/** ============================================
 * The API handler file for Air Quality Explorer
 * ============================================= */

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/functions.inc.php';
require_once __DIR__ . '/data_cache.inc.php';

use GuzzleHttp\Client;

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

// Store required country data in sqlite database
if (!empty($responseArrayCountries) && isset($database)) {
    $stmt = $database->prepare('
        INSERT INTO countries (id, name, code) 
            VALUES (:id, :name, :code)
            ON CONFLICT(id) DO UPDATE SET
                name = excluded.name,
                code = excluded.code
    ');

    // Loop through the response array and assign the necessary values to it
    foreach ($responseArrayCountries as $country) {
        $stmt->execute([
            ':id' => $country['id'],
            ':name' => $country['name'],
            ':code' => $country['code'],
        ]);
    }

    $stmt->closeCursor();

    echo '<h1>Country data successfully inserted into table</h1>';
} else {
    echo '<h1>No country data uploaded</h1>';
}

// Provide an array of specific countries
/*$SelectedCountries = [
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
//print_r($filteredCountriesResponseArray);

// Alternate syntax for filtering. Using for reference

$filteredResponseArray = array_filter($responseArray['results'], function ($country) use ($SelectedCountries) {
    return in_array($country['name'], $SelectedCountries);
});


// Store the id of each country in the multidimensional array in a variable
$country_ids = [];
foreach ($filteredCountriesResponseArray as $country) {
    $country_ids[$country['name']] = $country['id'];
}

$country_ids = array_values($country_ids);
$country_id_string = implode(',', $country_ids);*/