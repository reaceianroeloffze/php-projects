<?php

require_once 'functions.php';

$celsiusUnit = '&#176;C';
$fahrenheitUnit = '&#176;F';
$kelvinUnit = '&#176;K';

$temperature = 0;
$unit_from = '';
$unit_to = '';
$converted_temperature = 0;

if (!empty($_GET['temp']) && !empty($_GET['unit_from']) && !empty($_GET['unit_to'])) {
    $temperature = e($_GET['temp']);
    $unit_from = e($_GET['unit_from']);
    $unit_to = e($_GET['unit_to']);
}

switch ($unit_from . '_' . $unit_to) {

    case 'celsius' . '_' . 'kelvin' :
        $converted_temperature = convertToKelvinFromCelsius($temperature);
        break;

    case 'celsius' . '_' . 'fahrenheit' :
        $converted_temperature = convertToFahrenheitFromCelsius($temperature);;
        break;

    case 'fahrenheit' . '_' . 'kelvin' :
        $converted_temperature = convertToKelvinFromFahrenheit($temperature);
        break;

    case 'fahrenheit' . '_' . 'celsius' :
        $converted_temperature = convertToCelsiusFromFahrenheit($temperature);
        break;

    case 'kelvin' . '_' . 'celsius' :
        $converted_temperature = convertToCelsiusFromKelvin($temperature);
        break;

    case 'kelvin' . '_' . 'fahrenheit' :
        $converted_temperature = convertToFahrenheitFromKelvin($temperature);
        break;

    case 'celsius' . '_' . 'celsius'  || 'fahrenheit' . '_' . 'fahrenheit' || 'kelvin' . '_' . 'kelvin':
        $error = '<p>Temperature is already in ' . $unit_to . '</p>';;
        break;

    default:

        if (!is_numeric($temperature)) {
            $error = 'Invalid temperature value';
        }
}