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
$currentDate = date('Y-m-d');

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
    if (!isset($measurements)) {

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
    <title></title>
</head>
<body>

<?php require_once __DIR__ . '/views/header.inc.php'; ?>

<?php /*if (!empty($sensors) && is_array($sensors)) { */?><!--
    <h2>Air Quality Measurements for <?php /*echo e($location_name); */?></h2>
    <table>
        <?php /*foreach ($sensors as $sensor) { */?>
            <tr>
                <td><?php /*echo $sensor['name']; */?></td>
            </tr>
        <?php /*} */?>
    </table>
<?php /*} else { */?>
    <h2>No measurements found</h2>
--><?php /*} */?>


<?php require_once __DIR__ . '/views/footer.inc.php'; ?>
</body>
</html>
