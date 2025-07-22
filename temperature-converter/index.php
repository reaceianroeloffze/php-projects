<?php

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

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <title>Temperature Converter</title>
</head>
<body>
<h1>Temperature Converter</h1>
<form method="GET" action="index.php">
    <label>
        <input type="number" name="temp" placeholder="Enter Temperature">
    </label>
    <label>
        <select name="unit_from">
            <option value="">Convert from:</option>
            <option value="celsius">Celsius (&#176;C)</option>
            <option value="fahrenheit">Fahrenheit (&#176;F)</option>
            <option value="kelvin">Kelvin (&#176;K)</option>
        </select>
    </label>
    <label>
        <select name="unit_to">
            <option value="">Convert to:</option>
            <option value="celsius">Celsius (&#176;C)</option>
            <option value="fahrenheit">Fahrenheit (&#176;F)</option>
            <option value="kelvin">Kelvin (&#176;K)</option>
        </select>
    </label>
    <button>Convert</button>
</form>
<div>
    <p>Result: </p>
</div>
</body>
</html>