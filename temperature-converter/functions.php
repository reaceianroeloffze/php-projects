<?php

/** ===============================
 * Temperature conversion functions
 * ================================ */

function e($value) : string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function convertToCelsiusFromKelvin($value): int|float
{
    return $value - 273.15;
}

function convertToCelsiusFromFahrenheit($value): int|float
{
    return ($value - 32) * 5 / 9;
}

function convertToKelvinFromCelsius($value): float|int
{
    return $value + 273.15;
}

function convertToKelvinFromFahrenheit($value): float|int
{
    return ($value + 459.67) * 5 / 9;
}

function convertToFahrenheitFromCelsius($value): float|int
{
    return $value * 9 / 5 + 32;
}

function convertToFahrenheitFromKelvin($value): float|int
{
    return ($value - 273.15) * 9 / 5 + 32;
}

?>
