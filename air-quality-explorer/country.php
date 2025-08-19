<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/inc/functions.inc.php';

$country = null;
$country_id = null;

if (!empty($_GET['country']) && !empty($_GET['id'])) {
    $country = $_GET['country'];
    $country_id = $_GET['id'];
}

$limit = 1000;

// Start a new Guzzle Client
$client = startNewGuzzleClient();

// Request locations from OpenAQ
$response_locations = generateGetRequest($client, "/v3/locations?countries_id={$country_id}&limit={$limit}");
// Convert the JSON data to a PHP associative array
$responseArrayLocations = generateResponseBody($response_locations);
// Store the results
$locations = $responseArrayLocations['results'] ?? [];
/*
echo '<pre>';
print_r($locations);
echo '</pre>';
*/

$currentDate = trim(date('Y-m-d'));

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo e($country); ?> | Air Quality Index Explorer</title>
</head>
<body>
<?php require_once __DIR__ . '/views/header.inc.php'; ?>

<section class="locations-container">
    <?php if (!empty($locations) && is_array($locations)) { ?>
        <h2>Air Quality Monitoring Stations in <?php echo e($country); ?></h2>
        <div class="locations"
             style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); grid-gap: 1rem; align-items: center;">
            <?php foreach ($locations as $location) { ?>
                <?php if (!str_contains($location['datetimeLast']['local'] ?? '', $currentDate)) {
                    continue;
                } ?>
                <p class="location-name" style="margin: 0.5rem 0;">
                    <a href="location_data.php?<?php echo http_build_query([
                                    'location_id' => $location['id'],
                                    'location_name' => $location['name'],
                            ]
                    ) ?>" target="_blank" rel="noopener noreferrer">
                        <?php echo (!empty($location['locality'])) ? e($location['name']) . ', ' . e($location['locality']) : e($location['name']) ?>
                    </a>
                </p>
            <?php } ?>
        </div>
    <?php } else { ?>
        <h2>No data to load</h2>
    <?php } ?>

</section>

<?php require_once __DIR__ . '/views/footer.inc.php'; ?>
</body>
</html>
