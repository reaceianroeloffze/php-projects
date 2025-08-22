<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="./styles/simple.css"/>
        <title>Document</title>
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