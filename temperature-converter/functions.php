<?php

/** ===============================
 * Temperature conversion functions
 * ================================ */

/**
 * This file contains all the functions that
 * the temperature converter app will use.
 * */

// Security
function e($value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// Kelvin to Celsius
function convertToCelsiusFromKelvin($value): int|float
{
    return round($value - 273.15, 2);
}

// Fahrenheit to Celsius
function convertToCelsiusFromFahrenheit($value): int|float
{
    return round(($value - 32) * 5 / 9, 2);
}

// Celsius to Kelvin
function convertToKelvinFromCelsius($value): float|int
{
    return round($value + 273.15, 2);
}

// Fahrenheit to Kelvin
function convertToKelvinFromFahrenheit($value): float|int
{
    return round(($value + 459.67) * 5 / 9, 2);
}

// Celsius to Fahrenheit
function convertToFahrenheitFromCelsius($value): float|int
{
    return round($value * 9 / 5 + 32, 2);
}

// Kelvin to Fahrenheit
function convertToFahrenheitFromKelvin($value): float|int
{
    return round(($value - 273.15) * 9 / 5 + 32, 2);
}

// Display the selected attribute for when the current values are submitted
function setSelectedAttribute($name, $value): void
{
    echo !empty($_GET[$name]) && $_GET[$name] === e($value) ? 'selected' : '';
}
