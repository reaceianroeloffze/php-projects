<?php

    require_once __DIR__ . '/vendor/autoload.php';
    require_once __DIR__ . '/inc/functions.inc.php';

    // Initialise variables
    $country = NULL;
    $country_id = NULL;

    // Get the country name and ID from the query string
    if (!empty($_GET['country']) && !empty($_GET['id'])) {
        $country = $_GET['country'];
        $country_id = $_GET['id'];
    }

    // Set the limit for the number of locations to return
    $limit = 1000;

    // Start a new Guzzle Client
    $client = startNewGuzzleClient();

    // Request locations from OpenAQ
    $response_locations = generateGetRequest($client, "/v3/locations?countries_id=$country_id&limit=$limit");
    // Convert the JSON data to a PHP associative array
    $responseArrayLocations = generateResponseBody($response_locations);
    // Store the results
    $locations = $responseArrayLocations['results'] ?? [];
    /*
    echo '<pre>';
    print_r($locations);
    echo '</pre>';
    */

    // Store the current date
    $currentDate = date('Y-m-d');

?>

<?php require_once __DIR__ . '/views/header.inc.php'; ?>

<!-- Create a section for the locations -->
<section class="locations-container">
    <!-- If there are locations, display them -->
    <?php if (!empty($locations) && is_array($locations)) : ?>
        <!-- Display the country name in a heading -->
        <h2>Air Quality Monitoring Stations in <?php echo e($country); ?></h2>
        <p>
            Select a location to view an average of hourly pm2.5/25 and/or pm10 particle measurements over the period
            of each month for the current year.
        </p>
        <!-- Store the locations in a div grid -->
        <div class="locations"
             style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); grid-gap: 1rem; align-items: center;">
            <!-- Apply grid layout -->
            <!-- Loop through the locations -->
            <?php foreach ($locations as $location) : ?>
                <!-- Skip locations that do not have today as the last measurement taken -->
                <?php if (!str_contains($location['datetimeLast']['local'] ?? '', $currentDate)) {
                    continue;
                } ?>
                <!-- Store the location name in a paragraph as a link -->
                <p class="location-name" style="margin: 0.5rem 0;">
                    <!-- Build a query string to pass the location ID and name to the
                    location_data.php file -->
                    <a href="location_data.php?<?php echo http_build_query([
                            'location_id' => $location['id'],
                            'location_name' => $location['name'],
                        ]
                    ) ?>" rel="noopener noreferrer"> <!-- Don't open the link in a new tab -->
                        <!-- If the location has a locality, display it as well -->
                        <?php echo (!empty($location['locality'])) ?
                            e($location['name']) . ', ' . e($location['locality']) :
                            e($location['name']) ?>
                    </a>
                </p>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <h2>No data to load</h2>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/views/footer.inc.php'; ?>
