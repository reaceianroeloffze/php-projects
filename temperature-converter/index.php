<?php

/** =================================
 * View for the temperature converter
 * ================================== */

/**
 * This file displays the form and outputs
 * the relevant data from the controller file
 */

// Require the functions.php and converter.php files
require_once 'functions.php';
require_once 'converter.php';

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
<!-- Form -->
<form method="GET">
    <label>
        <!-- Number input. Retain display after conversion -->
        <input type="number" name="temp" placeholder="Enter Temperature"
               value="<?php echo !empty($_GET['temp']) ? e($_GET['temp']) : ''; ?>">
    </label>
    <label>
        <!-- Convert from select -->
        <select name="unit_from">
            <option value="">Convert from:</option>
            <!-- Loop through an array of the various units and display them -->
            <?php foreach ($units as $unit => $unit_name) : ?>
                <option value="<?php echo e($unit); ?>" <?php setSelectedAttribute('unit_from', $unit); ?>>
                    <?php echo $unit_name; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>
    <label>
        <!-- Convert to select. All steps repeat in the convert from select -->
        <select name="unit_to">
            <option value="">Convert to:</option>
            <?php foreach ($units as $unit => $unit_name) : ?>
                <option value="<?php echo e($unit); ?>" <?php setSelectedAttribute('unit_to', $unit); ?>>
                    <?php echo $unit_name; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>
    <button type="submit">Convert</button>
</form>
<div>
    <!-- Display the converted number or a default of 0 -->
    <p><?php echo !empty($converted_temperature) ? $converted_temperature : 0 ?></p>
</div>
</body>
</html>