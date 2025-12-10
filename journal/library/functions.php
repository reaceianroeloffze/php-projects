<?php

    /**
     * Escape HTML special characters in a string
     * to prevent XSS attacks.
     *
     * @param string $value <p>
     * The string to be escaped
     * </p>
     * @return string <p>
     * The newly converted string
     * </p>
     * */
    function e($value): string {
        return htmlspecialchars($value, ENT_QUOTES);
    }

    /**
     * Get the name of an image file without the file extension.
     *
     * @param string $imageFileName <p>
     * The full name of the image file including the extension
     * </p>
     * @return string <p>
     * The name of the image file without the extension
     * </p>
     * */
    function getImageNameWithoutExtension(string $imageFileName): string {
        $imageNameWithoutExtension = pathinfo($imageFileName, PATHINFO_FILENAME);
        return $imageNameWithoutExtension;
    }

    /**
     * Append the current timestamp and the jpg file extension to an image
     * name
     *
     * @param string $imageName <p>
     * The name of the image
     * </p>
     * @return string <p>
     * The newly created image name with timestamp and jpg file extension
     * </p>
     * */
    function appendTimestampAndJpgToImage($imageName): string {
        return $imageName . '-' . time() . '.jpg';
    }

    /**
     * Append the current timestamp and the png file extension to an image
     * name
     *
     * @param string $imageName <p>
     * The name of the image
     * </p>
     * @return string <p>
     * The newly created image name with timestamp and png file extension
     * </p>
     * */
    function appendTimestampAndPngToImage($imageName): string {
        return $imageName . '-' . time() . '.png';
    }

    /**
     * Append the current timestamp and the gif file extension to an image
     * name
     *
     * @param string $imageName <p>
     * The name of the image
     * </p>
     * @return string <p>
     * The newly created image name with timestamp and gif file extension
     * </p>
     * */
    function appendTimestampAndGifToImage($imageName): string {
        return $imageName . '-' . time() . '.gif';
    }

    /**
     * Scale an image down from a given width and height
     *
     * @param int|float $maxDimensions <p>
     * The maximum width and height to scale down to
     * </p>
     * @param int|float $width <p>
     * The original width of the image
     * </p>
     * @param int|float $height </p>
     * The original height of the image
     * </p>
     * @return array <p>
     * An array of the newly scaled image width and height
     * </p>
     * */
    function scaleDownImage(
        int|float $maxDimensions,
        int|float $width,
        int|float $height
    ): array {
        // Get scale factor to scale image down from
        $scaleFactor = $maxDimensions / max($width, $height);
        // Scale the width and height down based on the scale factor
        $scaledWidth  = $width * $scaleFactor;
        $scaledHeight = $height * $scaleFactor;
        return [
            'scaled_width' => (int)round($scaledWidth),
            'scaled_height' => (int)round($scaledHeight),
        ];
    }
