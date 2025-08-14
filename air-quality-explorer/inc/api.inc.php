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

// Request the locations of air quality stations based on the country's id
$response_locations = generateGetRequest($client, "/v3/locations?countries_id={$country_id_string}&limit=1000");

// Convert response to a multidimensional array
$responseArrayLocations = generateResponseBody($response_locations);
$responseArrayLocations = $responseArrayLocations['results'];
//print_r($responseArrayLocations);

// Filter air quality stations which only have the desired measurements
$parameters = [
    'pm25',
    'pm10',
];

// Filter the sensors with the parameter names in the parameters array
$filteredLocationsResponseArray = array_filter($responseArrayLocations, function ($location) use ($parameters) {
    return count(array_filter(
            $location['sensors'],
            fn($sensor) => in_array(strtolower($sensor['parameter']['name']), $parameters)
        )) > 0;
});

// Filter locations to those with sensors that have the required parameter names
foreach ($filteredLocationsResponseArray as &$location) {
    $location['sensors'] = array_values(array_filter($location['sensors'], fn($sensor) => in_array(strtolower($sensor['parameter']['name']), $parameters)
    ));
}


unset($location);

/* Get the first 20 locations from each country */

// Group locations by country
$locationsGroupedByCountry = [];

// Set a maximum number of locations to display per country
$maxLocationsPerCountry = 20;

// Loop through the filtered locations array
foreach ($filteredLocationsResponseArray as $location) {
    $country = $location['country']['name'];

    // If that country is not set, create that country with an empty array inside it
    if (!isset($locationsGroupedByCountry[$country])) {
        $locationsGroupedByCountry[$country] = [];
    }

    // count all array elements and if they are less than the given max, append them
    if (count($locationsGroupedByCountry[$country]) < $maxLocationsPerCountry) {
        $locationsGroupedByCountry[$country][] = $location;
    }
}
print_r($locationsGroupedByCountry);

//$locations = [];

/*
// Get air quality sensors based on the location's id
foreach ($responseArrayLocations['results'] AS $location) {
    $response_sensors = generateGetRequest($client, "/v3/locations/{$location['id']}/sensors");
    $locations[] = $responseArraySensors = generateResponseBody($response_sensors);
}
*/

/*
// Store the id of each sensor in a variable
$sensor_id = '';
foreach ($responseArraySensors['results'] AS $sensor) {
    $sensor_id = $sensor['id'];
}

// Get air quality measurements based on sensor id
$response_measurements = $client->get("/v3/sensors/{$sensor_id}/hours", generateHeaders());
*/