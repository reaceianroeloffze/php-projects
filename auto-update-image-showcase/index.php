<?php

    /** =========================
     * Auto-update image showcase
     * ========================== */

    function e($string): string {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Locate file paths
     * @var string $pathToFiles: The path to the directory containing the image and text files.
     * @method pathinfo(): Specifies the path to a file and returns information about the file path.
     * @flag PATHINFO_DIRNAME: Returns the directory name component of the path.
     * @return string
     * */
    $pathToFiles = pathinfo(__DIR__ . '/images/txt', PATHINFO_DIRNAME);

    /**
     * Get the files in the directory
     * @method is_dir(): Determines whether the filename is a directory. @return bool
     * @method exit: Output a message and terminate the current script @return void
     * */
    if (!is_dir($pathToFiles)) {
        echo '<h2>The specified directory does not exist.</h2>';
        exit;
    }

    /**
     * Scan the directory for files
     * @method scandir(): Scans a directory and returns an array of its contents.
     * @return array
     * */ 
    $files = scandir($pathToFiles);

    /**
     * Remove the '.' and '..' entries
     * 
     * @method array_diff(): Takes two or more arrays and returns an array containing all the
     * values from the first array that are not present in any of the other arrays.
     * @return array
     * */
    $imageFiles = array_diff($files, ['.', '..', 'file.txt']);

    /**
     * Store desired file extensions in an array
     * @method glob(): Finds file paths matching a pattern
     * @flag GLOB_BRACE: Expands {a,b,c} to match 'a', 'b', or 'c'
     * @return array
     * */
    $desiredFileExtensions = glob('./images/*.{jpg,png,jpeg}', GLOB_BRACE);

    /**
     * Extract the file extensions from the file paths
     * @method array_map(): Iterates over each element of an array, applies a user-defined 
     * function to each element and returns a new array containing the results.
     * The array returned is always the same length as the input array(s).
     * @return array
     * */
    $fileExtensions = array_map(fn($filePath) => pathinfo($filePath, PATHINFO_EXTENSION), $desiredFileExtensions);
    // Remove duplicate file extension entries
    $fileExtensions = array_unique($fileExtensions);

    // Create an array to store the file contents
    $fileContent = [];

    // Loop through the image files
    foreach ($imageFiles AS $imageFile) {
        // Skip if it's not a file
        if (!is_file($pathToFiles . '/' . $imageFile)) {
            continue;
        }
        // Get the file extension
        $fileExtension = pathinfo($imageFile, PATHINFO_EXTENSION);
        // Check if the file extension is in the desired file extensions array
        if (!in_array($fileExtension, $fileExtensions)) {
            continue;
        }
        // Get the file name without the extension and attach .txt to it
        $filename = pathinfo($imageFile, PATHINFO_FILENAME) . '.txt';
    
        /**
         * Check if the corresponding text file exists
         *
         * */
        $textFilePath = $pathToFiles . '/' . $filename;

        $textContent = [];

        // If the text file exists, read and store its contents
        if (file_exists($textFilePath)) {
            /**
             * Read the text file and split it into lines
             * @method file(): Reads an entire file into an array, with each line
             * as an array element.
             * @flag FILE_IGNORE_NEW_LINES: Do not include the newline character
             * at the end of each line.
             * @flag FILE_SKIP_EMPTY_LINES: Skip empty lines in the file.
             * @return array
             * 
             * @var array $textContent: The content of the text file
             * @method array_slice(): Takes a given array and returns a new array containing
             * a portion of the original array.
             * @return array
             * */
            $textContent = file($textFilePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $textContent = [
                'title' => $textContent[0] ?? 'Untitled Image',
                'description' => array_slice($textContent, 1) ?: ['No description'],
            ];
        }

        // Store the image file and its corresponding text content in the array
        $fileContent[] = [
            'image' => $imageFile,
            'title' => $textContent['title'] ?? 'Untitled Image',
            'description' => $textContent['description'] ?? ['No description'],
        ];
    }

    /** 
     * Sort the file content array by title
     * @method usort(): Sorts an array by values using a user-defined comparison function.
     * @method strcmp(): Compares two strings (case-sensitive).
     * @return void
     * */
    usort($fileContent, fn($a, $b) => strcmp($a['title'], $b['title']));

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=yes, initial-scale=1.0">
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
            <!-- Check if there are images to display -->
            <?php if (!empty($fileContent)): ?>
                <div class="image-gallery">
                    <?php foreach ($fileContent AS $file): ?>
                        <figure class="image-item">
                            <!-- Display the title of the image -->
                            <h2><?php echo e($file['title']); ?></h2>
                            <!-- Display the image itself -->
                            <img src="<?php echo './images/' . rawurldecode($file['image']); ?>" alt="<?php echo e($file['title']); ?>">
                            <!-- Display the description of the image -->
                            <figcaption>
                                <?php foreach ($file['description'] AS $paragraph): ?>
                                    <p><?php echo e($paragraph); ?></p>
                                <?php endforeach; ?>
                            </figcaption>
                        </figure>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <h2 class="error-msg">No images found in the specified directory.</h2>
            <?php endif; ?>
        </main>
        <footer>

        </footer>
    </body>
</html>
