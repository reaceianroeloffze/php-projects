<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/inc/functions.inc.php';

$location_id = null;
$location_name = null;
if (!empty($_GET['location_id'])) {
    $location_id = $_GET['location_id'];
    $location_name = $_GET['location_name'];
}

// Start a new Guzzle Client
$client = startNewGuzzleClient();


$limit = 1000;

// Request sensors from OpenAQ
$response_sensors = generateGetRequest($client, "/v3/locations/$location_id/sensors?limit=$limit");
$responseArraySensors = generateResponseBody($response_sensors);
$sensors = $responseArraySensors['results'] ?? [];
/*echo '<pre>';
print_r($sensors);
echo '</pre>';*/

// Define parameters to display
$parameters = ['pm25', 'pm10'];

// Create an array to store sensor IDs
$sensor_ids = [];

// Loop through sensors and extract relevant sensor IDs
foreach ($sensors as $sensor) {
    if (!in_array($sensor['parameter']['name'], $parameters)) {
        continue;
    }
    $sensor_ids[] = $sensor['id'];
}

/*echo '<pre>';
print_r($sensor_ids);
echo '</pre>';
die();*/

// Store parameter measurements
$measurements = [];

// Store the current year
$currentYear = date('Y');

// Loop through sensors and extract measurements for specified parameters
foreach ($sensor_ids as $sensor_id) {

    // Request measurements from hour to month from OpenAQ
    $response_measurements = generateGetRequest($client, "/v3/sensors/$sensor_id/days/monthly?limit=$limit&date_from=$currentYear-01-01&date_to=$currentYear-12-31");
    $responseArrayMeasurements = generateResponseBody($response_measurements);
    $monthlyMeasurements = $responseArrayMeasurements['results'] ?? [];
    /*    echo '<pre>';
        print_r($monthlyMeasurements);
        echo '</pre>';*/


    // Loop through monthly measurements and store them
    foreach ($monthlyMeasurements as $measurement) {
        // Format the date to YYYY-MM
        $dateFormat = substr($measurement['period']['datetimeFrom']['local'], 0, 7);
        $measurementValue = $measurement['value'] ?? null; // Extract the measurement value
        $parameter = $measurement['parameter']['name']; // Extract the parameter name
        $measurementUnits = $measurement['parameter']['units'] ?? null; // Extract the measurement units
        // if there is no array with the date format as a key set for the date format, create one
        if (!isset($measurements[$dateFormat])) {
            $measurements[$dateFormat] = [];
        }
        $measurements[$dateFormat][$parameter] = [];
        $measurements[$dateFormat][$parameter]['measurementValue'] = $measurementValue;
        $measurements[$dateFormat][$parameter]['measurementUnits'] = $measurementUnits;
        // Check if all parameters have been extracted and break the loop if true
        /* if (count(array_intersect(array_keys($measurements), $parameters)) === count($parameters)) {
             break;
         }*/
    }
}

$parameter_names = [];

foreach ($measurements as $month => $monthMeasurements) {
    foreach ($monthMeasurements as $parameter => $measurement) {
        $parameter_names[] = $parameter;
    }
}

$parameter_names = array_unique($parameter_names);

/*echo '<pre>';
print_r($measurements);
echo '</pre>';*/

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="styles/simple.css">
    <title><?php echo $location_name ?> | Air Quality Explorer</title>
</head>
<body>

<?php require_once __DIR__ . '/views/header.inc.php'; ?>

<!-- If the location has measurements, display them in a table -->
<?php if (!empty($measurements)) { ?>
<h2>Measurements for <?php echo e($location_name); ?></h2>
<table style="width: 100%">
    <thead>
    <tr>
        <th>Month</th>
        <?php foreach ($parameter_names as $parameter) { ?>
            <th><?php echo e($parameter . ' Concentration'); ?></th>
        <?php } ?>
    </tr>
    </thead>
    <tbody>
    <!-- Loop through measurements and display them -->
    <?php foreach ($measurements as $month => $monthMeasurements) { ?>
        <tr>
            <td><?php echo e($month); ?></td>
            <?php foreach ($monthMeasurements as $parameter => $measurement) { ?>
                <td>
                    <?php echo e($measurement['measurementValue'] . ' ' . $measurement['measurementUnits']); ?>
                </td>
            <?php } ?>
        </tr>
    <?php } ?>
    <?php } else { ?>
        <h2>No measurements found for <?php echo e($location_name); ?></h2>
    <?php } ?>

    <?php require_once __DIR__ . '/views/footer.inc.php'; ?>
</body>
</html>
