<?php

/** =========================================
 * Controller logic for temperature converter
 * ==========================================*/

/**
 * This file handles all the submission and calculatory logic
 * for the temperature converter application.
 * */

// Require the functions.php file
require_once 'functions.php';

// Initialise Temperature units
$celsiusUnit = '&#176;C';
$fahrenheitUnit = '&#176;F';
$kelvinUnit = '&#176;K';

// Create an associative array of the units.
$units = [
    'celsius' => "Celsius ($celsiusUnit)",
    'fahrenheit' => "Fahrenheit ($fahrenheitUnit)",
    'kelvin' => "Kelvin ($kelvinUnit)",
];

// Initialise variables for storing submitted data
$temperature = 0;
$unit_from = '';
$unit_to = '';
$converted_temperature = '';
$error = '';

// If all the submitted data is obtained, store them in the initialised variables
if (!empty($_GET['temp']) && !empty($_GET['unit_from']) && !empty($_GET['unit_to'])) {
    $temperature = e($_GET['temp']);
    $unit_from = e($_GET['unit_from']);
    $unit_to = e($_GET['unit_to']);
}

// Convert the numerical value based on the from and to units submitted
switch ($unit_from . '_' . $unit_to) {

    // Celsius to Kelvin
    case 'celsius' . '_' . 'kelvin' :
        $converted_temperature = convertToKelvinFromCelsius($temperature) . ' ' . $kelvinUnit;
        break;

    // Celsius to Fahrenheit
    case 'celsius' . '_' . 'fahrenheit' :
        $converted_temperature = convertToFahrenheitFromCelsius($temperature) . ' ' . $fahrenheitUnit;
        break;

    // Fahrenheit to Kelvin
    case 'fahrenheit' . '_' . 'kelvin' :
        $converted_temperature = convertToKelvinFromFahrenheit($temperature) . ' ' . $kelvinUnit;
        break;

    // Fahrenheit to Celsius
    case 'fahrenheit' . '_' . 'celsius' :
        $converted_temperature = convertToCelsiusFromFahrenheit($temperature) . ' ' . $celsiusUnit;
        break;

    // Kelvin to Celsius
    case 'kelvin' . '_' . 'celsius' :
        $converted_temperature = convertToCelsiusFromKelvin($temperature) . ' ' . $celsiusUnit;
        break;

    // Kelvin to Fahrenheit
    case 'kelvin' . '_' . 'fahrenheit' :
        $converted_temperature = convertToFahrenheitFromKelvin($temperature) . ' ' . $fahrenheitUnit;
        break;

}
