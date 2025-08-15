<?php




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