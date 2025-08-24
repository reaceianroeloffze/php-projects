<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
        <meta name="author" content="Reace Ian Roeloffze"/>
        <meta name="description" content="Air Quality Index Explorer">
        <link rel="stylesheet" type="text/css" href="./styles/simple.css"/>
        <link rel="stylesheet" type="text/css" href="./styles/custom.css"/>
        <link rel="apple-touch-icon" sizes="180x180" href="./favicon_io/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="./favicon_io/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="./favicon_io/favicon-16x16.png">
        <link rel="manifest" href="./favicon_io/site.webmanifest">
        <!-- Display the country name -->
        <?php if (isset($country)) : ?>
            <title><?php echo e($country); ?> | Air Quality Index Explorer</title>
        <?php elseif (isset($location_name)) : ?>
            <!-- Display the location name in the title if it exists -->
            <title><?php echo e($location_name); ?> | Air Quality Index Explorer</title>
        <?php else : ?>
            <title>Home | Air Quality Index Explorer</title>
        <?php endif; ?>
    </head>
    <body>
        <header>
            <h1>AQI-Explorer</h1>
            <nav>
                <a href="index.php">Overview</a>
                <!-- If we are viewing a location and its data, create a link to go back to the
                country page where each location is listed -->
                <?php if (isset($location_name)) : ?>
                    <!-- Use JavaScript to go back to the previous page -->
                    <a href="javascript:history.back()">Back to Country Locations</a>
                <?php endif; ?>
            </nav>
        </header>
        <main style="text-align: center;">