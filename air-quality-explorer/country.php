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

$parameters = [
    'pm25',
    'pm10',
];

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $country; ?> | Air Quality Index Explorer</title>
</head>
<body>
<?php require_once __DIR__ . '/views/header.inc.php'; ?>

<section class="locations-container">
    <?php if (empty($country)) { ?>
        <h1>No country data to load</h1>
    <?php } else { ?>
        <h1>Air Quality Monitoring Stations in <?php echo $country; ?></h1>
    <?php } ?>
    <div class="locations" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); grid-gap: 1rem; align-items: center;">
        <?php if (!empty($country)) { ?>
            <?php foreach ($locations as $location) { ?>

                <p class="location-name" style="margin: 0.5rem 0;">
                    <a href="location_data.php?<?php echo http_build_query([
                            'location_id' => $location['id'],
                        ]
                    ) ?>">
                        <?php echo (in_array($location['locality'], $locations)) ? $location['name'] . ', ' . $location['locality'] : $location['name'] ?>
                    </a>
                </p>

            <?php } ?>
        <?php } ?>
    </div>
</section>

<?php require_once __DIR__ . '/views/footer.inc.php'; ?>
</body>
</html>
