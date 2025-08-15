<?php

/** ============================================
 * The API handler file for Air Quality Explorer
 * ============================================= */

set_time_limit(0);
ini_set('memory_limit', '1024M');

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/functions.inc.php';
require_once __DIR__ . '/data_cache.inc.php';

//require_once __DIR__ . '/request_countries.inc.php';

use GuzzleHttp\Client;

$client = new Client(['base_uri' => 'https://api.openaq.org']);

$limit = 1000;
$page = 1;

$response_locations = generateGetRequest($client, "/v3/locations?limit={$limit}&page={$page}");
// Convert response to a multidimensional array
$responseArrayLocations = generateResponseBody($response_locations);
$responseArrayLocations = $responseArrayLocations['results'];
echo '<pre>';
print_r($responseArrayLocations);
echo '</pre>';

// Request the locations of air quality stations
if (isset($database)) {

    $database->beginTransaction();

    while (true) {

        $response_locations = generateGetRequest($client, "/v3/locations?limit={$limit}&page={$page}");
        // Convert response to a multidimensional array
        $responseArrayLocations = generateResponseBody($response_locations);
        $locations = $responseArrayLocations['results'] ?? [];

        if (empty($locations)) {
            break;
        }
        $stmt = $database->prepare('
                INSERT INTO locations (id, country_id, name, latitude, longitude, owner, provider)
                    VALUES (:id, :country_id, :name, :latitude, :longitude, :owner, :provider)
                    ON CONFLICT(id) DO UPDATE SET 
                        name = :name,
                        latitude = excluded.latitude,
                        longitude = excluded.longitude
                ');

        foreach ($locations as $location) {
            $stmt->execute([
                ':id' => $location['id'],
                ':country_id' => $location['country']['id'],
                ':name' => $location['name'] ?? 'N/A',
                ':latitude' => $location['coordinates']['latitude'] ?? NULL,
                ':longitude' => $location['coordinates']['longitude'] ?? NULL,
                ':owner' => $location['owner']['name'],
                ':provider' => $location['provider']['name'],
            ]);
        }

        $stmt->closeCursor();
        unset($stmt);
        // unset($locations);

        echo '<h1>Page ' . $page . ' Completed</h1>';

        $page++;
    }

    echo '<h1>Location data successfully inserted into table</h1>';

    $database->commit();
}

/*$parameters = [
    'pm25',
    'pm10',
];

// Set a maximum number of locations to display per country
$maxLocationsPerCountry = 10;
// Group locations by country
$locationsGroupedByCountry = filterLocationsByCountry($responseArrayLocations, $parameters, $maxLocationsPerCountry);

$countryLocations = $locationsGroupedByCountry;
//print_r($countryLocations);

/*
 * // Extract and store sensor IDs from filtered locations
$sensor_ids = []; // Store sensor IDs here

foreach ($locationsGroupedByCountry AS $country => $locations) {
    foreach ($locations AS $location) {
        foreach ($location['sensors'] as $sensor) {
            if (in_array($sensor['parameter']['name'], $parameters)) {
                $sensor_ids[] = $sensor['id'];
            }
        }
    }
}

// Remove duplicate sensors
$sensor_ids = array_unique($sensor_ids);
//print_r($sensor_ids);
*/