<?php

require_once 'functions.php';

$celsiusUnit = '&#176;C';
$fahrenheitUnit = '&#176;F';
$kelvinUnit = '&#176;K';

$units = [
    'celsius' => "Celsius ($celsiusUnit)",
    'fahrenheit' => "Fahrenheit ($fahrenheitUnit)",
    'kelvin' => "Kelvin ($kelvinUnit)",
];

$temperature = 0;
$unit_from = '';
$unit_to = '';
$converted_temperature = 0;
$error = '';

if ($_SERVER['REQUEST_METHOD'] !== 'GET' && !empty($_GET['submit'])) {
    $temperature = e($_GET['temp']);
    $unit_from = e($_GET['unit_from']);
    $unit_to = e($_GET['unit_to']);


switch ($unit_from . '_' . $unit_to) {

    case 'celsius' . '_' . 'kelvin' :
        $converted_temperature = convertToKelvinFromCelsius($temperature) . ' ' . $kelvinUnit;
        break;

    case 'celsius' . '_' . 'fahrenheit' :
        $converted_temperature = convertToFahrenheitFromCelsius($temperature) . ' ' . $fahrenheitUnit;
        break;

    case 'fahrenheit' . '_' . 'kelvin' :
        $converted_temperature = convertToKelvinFromFahrenheit($temperature) . ' ' . $kelvinUnit;
        break;

    case 'fahrenheit' . '_' . 'celsius' :
        $converted_temperature = convertToCelsiusFromFahrenheit($temperature) . ' ' . $celsiusUnit;
        break;

    case 'kelvin' . '_' . 'celsius' :
        $converted_temperature = convertToCelsiusFromKelvin($temperature) . ' ' . $celsiusUnit;
        break;

    case 'kelvin' . '_' . 'fahrenheit' :
        $converted_temperature = convertToFahrenheitFromKelvin($temperature) . ' ' . $fahrenheitUnit;
        break;

    default:
        if (!empty($temperature)) {
            if (!is_numeric($temperature)) {
                $error = 'Invalid temperature value';
                break;
            } else if ($unit_from === $unit_to) {
                $error = 'Convert from and to units are same';
                break;
            }

        }
}