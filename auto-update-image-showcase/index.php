<?php

    // Locate file paths
    $filePaths = pathinfo(__DIR__ . '/images/file.txt', PATHINFO_DIRNAME);

    // Locate file paths
    $filePaths = pathinfo(__DIR__ . '/images/file.txt', PATHINFO_DIRNAME);

    // Get the image files
    $files = scandir($filePaths);

    // Remove the '.' and '..' entries
    $imageFiles = array_diff($files, ['.', '..', 'file.txt']);




?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="author" content="Reace Ian Roeloffze">
        <meta name="description" content="Auto-update image showcase">
        <link rel="stylesheet" type="text/css" href="./styles/simple.css">
        <title>Image Showcase</title>
    </head>
    <body>
        <header>
            <h1>Image Showcase</h1>
        </header>
        <main>
            <!-- Display images and their corresponding descriptions -->
        </main>
        <footer>

        </footer>
    </body>
</html>
