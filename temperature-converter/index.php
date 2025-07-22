<?php
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
<form method="GET">
    <label>
        <input type="number" name="temp" placeholder="Enter Temperature"
               value="<?php echo !empty($_GET['temp']) ? e($_GET['temp']) : ''; ?>">
    </label>
    <label>
        <select name="unit_from">
            <option value="">Convert from:</option>
            <?php foreach ($units as $unit => $unit_name) : ?>
                <option value="<?php echo e($unit); ?>" <?php setSelectedAttribute('unit_from', $unit); ?>>
                    <?php echo $unit_name; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>
    <label>
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
    <?php
    if (!empty($converted_temperature)) echo $converted_temperature;
    else if (!empty($error)) echo $error;
    ?>
</div>
</body>
</html>