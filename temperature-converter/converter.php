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
$kelvinUnit = 'K';

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

// Validate input
if (isset($_GET['temp'], $_GET['unit_from'], $_GET['unit_to'])) {

    // Trim input to avoid accidental whitespace
    $temp_input = trim($_GET['temp']);

    // Display error messages for invalid input
    if ($temp_input === '' || !is_numeric($temp_input)) {
        $error = 'Please enter a number to convert.';
    } else if ($_GET['unit_from'] === '' || $_GET['unit_to'] === '') {
        $error = 'Select both units to convert the temperature.';
    } else if ($_GET['unit_from'] === $_GET['unit_to']) {
        $error = "Can't convert from and to the same unit.";
    } else {
        // Process submitted data if no errors
        $temperature = (float)e($temp_input);
        $unit_from = e($_GET['unit_from']);
        $unit_to = e($_GET['unit_to']);

        // Convert temperature based on from and to units
        switch ($unit_from . '_' . $unit_to) {
            // Celsius to Kelvin
            case 'celsius_kelvin':
                $converted_temperature = convertToKelvinFromCelsius($temperature) . ' ' . $kelvinUnit;
                break;
            // Celsius to Fahrenheit
            case 'celsius_fahrenheit':
                $converted_temperature = convertToFahrenheitFromCelsius($temperature) . ' ' . $fahrenheitUnit;
                break;
            // Fahrenheit to Kelvin
            case 'fahrenheit_kelvin':
                $converted_temperature = convertToKelvinFromFahrenheit($temperature) . ' ' . $kelvinUnit;
                break;
            // Fahrenheit to Celsius
            case 'fahrenheit_celsius':
                $converted_temperature = convertToCelsiusFromFahrenheit($temperature) . ' ' . $celsiusUnit;
                break;
            // Kelvin to Celsius
            case 'kelvin_celsius':
                $converted_temperature = convertToCelsiusFromKelvin($temperature) . ' ' . $celsiusUnit;
                break;
            // Kelvin to Fahrenheit
            case 'kelvin_fahrenheit':
                $converted_temperature = convertToFahrenheitFromKelvin($temperature) . ' ' . $fahrenheitUnit;
                break;

            default:
                $error = 'Invalid unit conversion selected.';
                break;
        }
    }
}