<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/inc/functions.inc.php';

// Start a new Guzzle Client
$client = startNewGuzzleClient();

$limit = 1000;

$countries = null;

// Request countries from OpenAQ
$response_countries = generateGetRequest($client, "/v3/countries?limit={$limit}");
// Convert JSON data to a PHP associative array
$responseArrayCountries = generateResponseBody($response_countries);
// Store the results
$countries = $responseArrayCountries['results'] ?? [];

// Provide an array of specific countries to display on the homepage
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

?>

<?php require __DIR__ . '/views/header.inc.php'; ?>
<?php if (!empty($countries) && is_array($countries)) { ?>
    <h2>Browse Air Quality Indexes</h2>
    <p>Select a country to view its air quality data:</p>
    <ul style="list-style-type: none; margin: 0; padding: 0;">
        <?php foreach (($countries) as $country) { ?>
            <?php if (!in_array($country['name'], $SelectedCountries)) {
                continue;
            } ?>
            <li>
                <a href="country.php?<?php echo http_build_query(data: [
                    'country' => $country['name'],
                    'code' => $country['code'],
                    'id' => $country['id'],
                ]); ?>">
                    <?php echo e($country['name']) ?>
                    (<?php echo e($country['code']); ?>)
                </a>
            </li>
        <?php } ?>
    </ul>
<?php } else { ?>
    <h1>No data to load</h1>
<?php } ?>

<?php require __DIR__ . '/views/footer.inc.php'; ?>