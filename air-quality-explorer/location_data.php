<?php

    require_once __DIR__ . '/vendor/autoload.php';
    require_once __DIR__ . '/inc/functions.inc.php';

    $location_id = NULL;
    $location_name = NULL;
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
    // $parameters = ['pm25', 'pm10'];

    // Create an array to store sensor IDs
    $sensor_ids = [];

    // Loop through sensors and extract relevant sensor IDs
    foreach ($sensors as $sensor) {
        /*if (!in_array($sensor['parameter']['name'], $parameters)) {
            continue;
        }*/
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

    // initialise the date format
    $dateFormat = NULL;

    // Loop through sensors and extract measurements for specified parameters
    foreach ($sensor_ids as $sensor_id) {
        // Request measurements from hour to month from OpenAQ
        $response_measurements = generateGetRequest($client, "/v3/sensors/$sensor_id/days/monthly?limit=$limit&date_from=$currentYear-01-01&date_to=$currentYear-12-31");
        $responseArrayMeasurements = generateResponseBody($response_measurements);
        $monthlyMeasurements = $responseArrayMeasurements['results'] ?? [];
        /*  echo '<pre>';
            print_r($monthlyMeasurements);
            echo '</pre>';*/

        // Loop through monthly measurements and store them
        foreach ($monthlyMeasurements as $measurement) {
            // Format the date to YYYY-MM
            $dateFormat = substr($measurement['period']['datetimeFrom']['local'], 0, 7);
            $measurementValue = $measurement['value'] ?? NULL; // Extract the measurement value
            $parameter = $measurement['parameter']['name']; // Extract the parameter name
            $measurementUnits = $measurement['parameter']['units'] ?? NULL; // Extract the measurement units
            // if there is no array with the date format as a key set for the date format, create one
            if (!isset($measurements[$dateFormat])) {
                $measurements[$dateFormat] = [];
            }
            // Only append measurements for specified parameters
            if ($parameter === 'pm25' || $parameter === 'pm10') {
                // Create an array for the parameter
                $measurements[$dateFormat][$parameter] = [];
                // Append the measurement value and units to the parameter array
                $measurements[$dateFormat][$parameter]['measurementValue'] = $measurementValue;
                $measurements[$dateFormat][$parameter]['measurementUnits'] = $measurementUnits;
            }
        }
    }

    // Extract and store parameter names
    $parameter_names = [];

    // Loop through measurements and extract parameter names
    foreach ($measurements as $month => $monthMeasurements) {
        foreach ($monthMeasurements as $parameter => $paramName) {
            $parameter_names[] = $parameter;
        }
    }

    // Remove duplicate parameter names
    $parameter_names = array_unique($parameter_names);

    /*	echo '<pre>';
        print_r($parameter_names);
        echo '</pre>';
        die();*/


    /*echo '<pre>';
    print_r($measurements);
    echo '</pre>';
    die();*/

?>

<?php require_once __DIR__ . '/views/header.inc.php'; ?>

<!-- If the location has measurements, display them in a table -->
<?php if (!empty($measurements[$dateFormat])) : ?>
    <h2>Measurements for <?php echo e($location_name); ?></h2>
    <table style="width: 100%">
        <thead>
            <tr>
                <th>Month</th> <!-- display the month as a table header -->
                <!-- Loop through parameter names and display them as table headers -->
                <?php foreach ($parameter_names as $parameter) : ?>
                    <th><?php echo e($parameter . ' Concentration'); ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <!-- Loop through measurements and display them -->
            <?php foreach ($measurements as $month => $monthMeasurements) : ?>
                <tr>
                    <!-- Only display the month and its measurements if that month has any measurements -->
                    <?php if (!empty($monthMeasurements)) : ?>
                        <td><?php echo e($month); ?></td>
                        <!-- Display the measurement values for each specified parameter -->
                        <?php foreach ($monthMeasurements as $parameter => $measurement) : ?>
                            <td>
                                <?php if (in_array($parameter, $parameter_names)) : ?>
                                    <?php echo e($measurement['measurementValue'] . ' ' . $measurement['measurementUnits']); ?>
                                <?php endif; ?>
                            </td>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else : ?>
    <!-- If no measurements are found, display a message -->
    <h2>No measurements found for <?php echo e($location_name); ?></h2>
<?php endif; ?>

<?php require_once __DIR__ . '/views/footer.inc.php'; ?>

