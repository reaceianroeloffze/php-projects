<?php

    require_once __DIR__ . '/vendor/autoload.php';
    require_once __DIR__ . '/inc/functions.inc.php';

    // Enable Guzzle error handling
    use GuzzleHttp\Exception\BadResponseException;

    // Start a new Guzzle Client
    $client = startNewGuzzleClient();

    // Set the limit for the number of countries to retrieve
    $limit = 1000;

    // Initialise an empty array to store the countries
    $countries = NULL;

    $error = NULL;

    // Request countries from OpenAQ
    try {
        $response_countries = generateGetRequest($client, "/v3/countries?limit=$limit");
        // Convert JSON data to a PHP associative array
        $responseArrayCountries = generateResponseBody($response_countries);
        // Store the results
        $countries = $responseArrayCountries['results'] ?? [];
    } catch (badResponseException $e) {
        $error = $e->getMessage();
    }


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
<!-- Display the header -->
<?php require __DIR__ . '/views/header.inc.php'; ?>

<!-- If there are countries to display, display them -->
<?php if (!empty($countries) && is_array($countries)) : ?>
    <h2>Browse Air Quality Indexes</h2> <!-- Page title -->
    <p>Select a country to view its air quality data:</p> <!-- Page description -->
    <!-- Store the countries in an unordered list -->
    <ul style="list-style-type: none; margin: 0; padding: 0;"> <!-- Remove default list styling -->
        <!-- Skip countries that are not in the selected countries array -->
        <?php foreach (($countries) as $country) : ?>
            <?php if (!in_array($country['name'], $SelectedCountries)) {
                continue;
            } ?>
            <!-- in each list element, display the country name and code in a link -->
            <li>
                <!-- build a query string to pass the country name, code, and id to the
                country.php page -->
                <a href="country.php?<?php echo http_build_query(data: [
                    'country' => $country['name'],
                    'code' => $country['code'],
                    'id' => $country['id'],
                ]); ?>">
                    <!-- Display the country name and code -->
                    <?php echo e($country['name']) ?>
                    (<?php echo e($country['code']); ?>)
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else : ?>
    <!-- If no countries are found, display a message indicating such -->
    <h1>No data to load</h1>
    <?php echo "<p>$error</p>"; ?>
<?php endif ?>

<!-- Display the footer -->
<?php require __DIR__ . '/views/footer.inc.php'; ?>
