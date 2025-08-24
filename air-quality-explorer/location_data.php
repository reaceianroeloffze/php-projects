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

    // Create an array to store sensor IDs
    $sensor_ids = [];

    // Loop through sensors and extract relevant sensor IDs
    foreach ($sensors as $sensor) {
        /*if (!in_array($sensor['parameter']['name'], $parameters)) {
            continue;
        }*/
        $sensor_ids[] = $sensor['id'];
    }

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

            // Create an array for the parameter
            $measurements[$dateFormat][$parameter] = [];
            // Append the measurement value and units to the parameter array
            $measurements[$dateFormat][$parameter]['measurementValue'] = $measurementValue;
            $measurements[$dateFormat][$parameter]['measurementUnits'] = $measurementUnits;
        }
    }

    // Store the parameter names in an array
    $parameterNames = array_keys($measurements[$dateFormat]);

    // Store the desired parameters in an array
    $desiredParameters = ['pm25', 'pm10'];

    // Create a table header array
    $tableHeaders = ['Month'];
    foreach ($desiredParameters as $parameter) {
        $tableHeaders[] = strtoupper($parameter) . ' Concentration';
    }

    // Create an array to store the table data
    $tableData = [$tableHeaders];

    // Loop through the measurements and create a table row for each month
    // according to the desired parameters
    foreach ($measurements as $month => $parameters) {
        // Skip the month if it doesn't have measurements for all desired parameters
        if (empty($parameters[$desiredParameters[0]]['measurementValue']) && empty($parameters[$desiredParameters[1]]['measurementValue'])) {
            continue;
        }
        $tableRow = [$month];
        // Loop through the desired parameters and store their respective values and units
        foreach ($desiredParameters as $parameter) {
            $value = $parameters[$parameter]['measurementValue'] ?? NULL;
            $units = $parameters[$parameter]['measurementUnits'] ?? NULL;
            // Format the value and units for display. Display 'N/A' if there's no value or units.
            $tableRow[] = $value !== NULL && $units !== NULL && $value > 0 ? $value . ' ' . $units : 'N/A';
        }
        // Add the table row to the table data array
        $tableData[] = $tableRow;
    }

    // Sort the table data by date in chronological order
    usort($tableData, fn($a, $b) => strtotime($a[0]) - strtotime($b[0]));

    // Extract the months to be used as labels in the graph
    $labels = array_column($tableData, 0);
    // Remove the first element (Month) from the label array
    array_shift($labels);

    // Extract the PM2.5 concentration data from the table data
    $pm25Data = array_column($tableData, 1);
    // Remove the heading 'PM2.5 Concentration' from the array
    array_shift($pm25Data);
    // Convert the PM2.5 concentration data to floats and replace 'N/A' values with 0
    $pm25Data = array_map(fn($value) => str_contains($value, 'N/A') ? 0 : floatval($value), $pm25Data);

    // Extract the PM10 concentration data from the table data
    $pm10Data = array_column($tableData, 2);
    // Remove the heading 'PM10 Concentration' from the array
    array_shift($pm10Data);
    // Convert the PM10 concentration data to floats and replace 'N/A' values with 0
    $pm10Data = array_map(fn($value) => str_contains($value, 'N/A') ? 0 : floatval($value), $pm10Data);

    // Create an array to store the graph data
    $graphData = [];

    if (array_sum($pm25Data) > 0) {
        $graphData[] = [
            // pm2.5 data
            'label' => 'PM2.5 in' . $measurements[$dateFormat]['pm25']['measurementUnits'],
            'data' => $pm25Data,
            'fill' => FALSE,
            'borderColor' => 'rgb(255, 99, 132)',
            'tension' => 0.1
        ];
    }

    if (array_sum($pm10Data) > 0) {
        $graphData[] = [
            'label' => 'PM10 in ' . $measurements[$dateFormat]['pm10']['measurementUnits'],
            'data' => $pm10Data,
            'fill' => FALSE,
            'borderColor' => 'rgb(53, 162, 235)',
            'tension' => 0.1
        ];
    }

?>

    <!-- Display the header -->
<?php require_once __DIR__ . '/views/header.inc.php'; ?>

    <!-- If the location has measurements and the right parameters, display them in a table -->
<?php if (!empty($measurements[$dateFormat]) && (in_array($desiredParameters[0], $parameterNames) || in_array($desiredParameters[1], $parameterNames))) : ?>
    <h2>Measurements for <?php echo e($location_name); ?></h2>
    <!-- Load the graphing library -->
    <script src="./scripts/chart.umd.js"></script>
    <!-- Display a graph of the measurements in a canvas -->
    <div class="canvas-container">
        <canvas class="aqi aqi_graph"></canvas>
    </div>
    <!-- Use JavaScript to create a graph of the measurements -->
    <script>
        // Retrieve the canvas element to draw the graph in
        const ctx = document.querySelector('.aqi_graph');
        // Create a new instance of a chart using the canvas element and the data
        const chart = new Chart(ctx, {
            type: 'line', // Specify the type of chart (line, bar, etc.)
            options: {
                responsive: true, // Enable responsive layout
                maintainAspectRatio: false, // Disable aspect ratio
                scales: {
                    y: {
                        beginAtZero: true, // Start the y-axis at 0
                        ticks: {
                            stepSize: 10,
                        }
                    },
                }
            },
            // Provide the data for the chart
            data: {
                // Provide the labels for the graph
                labels: <?php echo json_encode($labels); ?>,
                // Provide the data for the graph
                datasets: <?php echo json_encode($graphData); ?>
            }
        });
    </script>
    <!-- Build and populate a table using the table data array -->
    <table>
        <thead>
            <tr>
                <!-- display the month as a table header -->
                <!-- Loop through parameter names and display them as table headers -->
                <!-- Only add the parameter heading if it contains data for at least one month -->
                <?php foreach ($tableData[0] as $parameter) : ?>
                    <th><?php echo e($parameter); ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <!-- Loop through the table data and output each row -->
            <?php for ($i = 1; $i < count($tableData); $i++) : ?>
                <tr>
                    <!-- Loop through the values in each row and output them -->
                    <?php for ($j = 0; $j < count($tableData[$i]); $j++) : ?>
                        <td><?php echo e($tableData[$i][$j]); ?></td>
                    <?php endfor; ?>
                </tr>
            <?php endfor; ?>
        </tbody>
    </table>
<?php else : ?>
    <!-- If no measurements are found, display a message -->
    <h2>No <?php echo $desiredParameters[0]; ?> or <?php echo $desiredParameters[1] ?> measurements found
        for <?php echo e($location_name); ?></h2>
<?php endif; ?>

    <!-- Display the footer -->
<?php require_once __DIR__ . '/views/footer.inc.php'; ?>