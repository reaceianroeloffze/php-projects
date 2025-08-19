<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/inc/functions.inc.php';

$location_id = null;
$location_name = null;
if (!empty($_GET['location_id'])) {
    $location_id = $_GET['location_id'];
    $location_name = $_GET['location_name'];
}

$client = startNewGuzzleClient();

$limit = 1000;

$response_sensors = generateGetRequest($client, "/v3/locations/{$location_id}/sensors?limit={$limit}");
$responseArraySensors = generateResponseBody($response_sensors);
$sensors = $responseArraySensors['results'] ?? [];
echo '<pre>';
print_r($sensors);
echo '</pre>';

$parameters = ['pm25', 'pm10'];

$measurements = [];

foreach ($sensors as $sensor) {
    if (!in_array($sensor['parameter']['name'], $parameters)) {
        continue;
    }

    $parameter = $sensor['parameter']['name'];
    $latestMeasurement = $sensor['latest']['value'] ?? null;
    $parameterUnits = $sensor['parameter']['units'] ?? null;
    $parameterDisplayName = $sensor['parameter']['displayName'] ?? null;

    if (!isset($measurements[$parameter])) {
        $measurements[$parameter] = [];
        $measurements[$parameter]['displayName'] = $parameterDisplayName;
        $measurements[$parameter]['measurementUnits'] = $parameterUnits;
        $measurements[$parameter]['latestMeasurementValue'] = $latestMeasurement;
    }

    if (count(array_intersect(array_keys($measurements), $parameters)) === count($parameters)) {
        break;
    }
}

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

<?php if (!empty($measurements)) { ?>
    <h2>Measurements for <?php echo e($location_name); ?></h2>
    <table style="width: 100%">
        <thead>
        <tr>
            <th>Particle Concentration</th>
            <th>Latest Measurement Value</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($measurements as $parameter => $measurement) { ?>
            <?php if (!empty($measurement['latestMeasurementValue'])) { ?>
                <tr>
                    <td><?php echo e($measurement['displayName']); ?></td>
                    <td><?php echo e(round($measurement['latestMeasurementValue'], 2)) . ' ' . $measurement['measurementUnits']; ?></td>
                </tr>
            <?php } ?>
        <?php } ?>
        </tbody>
    </table>
<?php } else { ?>
    <h2>No new measurements found for <?php echo e($location_name); ?></h2>
<?php } ?>


<?php require_once __DIR__ . '/views/footer.inc.php'; ?>
</body>
</html>
