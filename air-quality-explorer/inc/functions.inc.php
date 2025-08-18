<?php

function e($value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// Create a function to generate header content
function generateHeaders(): array
{
    return ['headers' => [
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
        'X-API-KEY' => getenv('OpenAQ_API_KEY'),
    ]];
}

function generateGetRequest($client, $path)
{
    return $client->get($path, generateHeaders());
}

function generateResponseBody($response): array
{
    return json_decode($response->getBody(), true);
}

/**
 * Filter & group air quality locations by country
 *
 * @param array $locations All location data from the API
 * @param array $parameters Sensor parameter names to keep (lowercase)
 * @param int $maxLocationsPerCountry Maximum locations to display per country
 *
 * @return array Grouped and filtered array
 */

function filterLocationsByCountry(array $locations, array $parameters, int $maxLocationsPerCountry): array
{
    $locationsGroupedByCountry = [];

    foreach ($locations as $location) {
        $requiredSensors = array_values(array_filter(
            $location['sensors'],
            fn($sensor) => in_array(strtolower($sensor['parameter']['name']), $parameters)
        ));

        // Skip locations that do not have the required sensors
        if (empty($requiredSensors)) {
            continue;
        }

        $country = $location['country']['name'];

        // Initialise a country array if not set
        if (!isset($locationsGroupedByCountry[$country])) {
            $locationsGroupedByCountry[$country] = [];
        }

        // Append country and locations based on maximum locations set
        if (count($locationsGroupedByCountry[$country]) < $maxLocationsPerCountry) {
            $location['sensors'] = $requiredSensors;
            $locationsGroupedByCountry[$country][] = $location;
        }
    }

    // Sort by country name
    ksort($locationsGroupedByCountry);

    // return the filtered array
    return $locationsGroupedByCountry;
}

function startNewGuzzleClient(): GuzzleHttp\Client
{
    return new GuzzleHttp\Client([
        'base_uri' => 'https://api.openaq.org/v1/',
    ]);
}