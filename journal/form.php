<?php

    /** ---------------------------------------------------
     * Controller logic for the Journal App Form submission
     * ---------------------------------------------------- */

    // Start a session
    session_start();

    // include the required files and libraries
    require_once __DIR__ . '/model/journal-model.php';
    require_once __DIR__ . '/library/functions.php';
    require_once __DIR__ . '/library/journal-db-connect.php';

    // print_r($_SERVER);
    /* echo '<pre>';
    print_r($_POST);
    echo '</pre>'; */

    // Get the form submission action
    $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    if ($action === NULL) {
        $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }
    
    if ($action === 'submit-entry') {
        // Filter and store the form input data
        // Entry title
        $title = (string) (trim(filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS)) ?? '');
        // The date the entry was created
        $created_on = (string) (trim(filter_input(INPUT_POST, 'created_on',  FILTER_SANITIZE_FULL_SPECIAL_CHARS)) ?? '');
        // The text content of the entry
        $body = (string) (trim(filter_input(INPUT_POST, 'body', FILTER_SANITIZE_FULL_SPECIAL_CHARS)) ?? '');
        
        /* Scale the uploaded image */
        $timestampedImage = null;
        
        /*
        Check if an image was uploaded successfully

        1. Check if the image key is set in the $_FILES array
        2. Check if the image is actually an uploaded file
        3. Ensure that there are no errors
        4. If the image is uploaded, ensure the file size isn't 0
        */
        if (
            isset($_FILES['image']) &&
            is_uploaded_file($_FILES['image']['tmp_name']) &&
            $_FILES['image']['error'] === UPLOAD_ERR_OK &&
            $_FILES['image']['size'] > 0
           ) {
            // Get the uploaded image file name
            $submittedImage = (string) $_FILES['image']['name'];

            // Get the temparary image file path/name
            $submittedImageTempName = $_FILES['image']['tmp_name'];

            // Get type of the uploaded image
            $imageType = exif_imagetype($submittedImageTempName);

            // Check if what was uploaded is actually an image
            if ($imageType === false) {
                $_SESSION['message'] = <<<'ERROR'
                <p class="error-message">
                    You have uploaded an incorrect file type. Please upload images only.
                </p>
                ERROR;
            }

            // Define allowed image upload types
            $allowedImageTypes = [
                IMAGETYPE_GIF,
                IMAGETYPE_JPEG,
                IMAGETYPE_PNG,
            ];

            // Reject all undesired image uploads
            if (!in_array($imageType, $allowedImageTypes)) {
                $_SESSION['message'] = <<<'ERROR_MSG'
                <p>
                    Please upload a jpg/jpeg, png, or gif image. No other image types supported.
                </p>
                ERROR_MSG;
            }

            // Remove the file extension from the image file name
            $imageNameWithoutExtension = getImageNameWithoutExtension($submittedImage);

            // Remove unwanted characters from the image file name
            $sanitisedImageName = preg_replace("/[^a-zA-Z0-9_-]/", "", $imageNameWithoutExtension);

            // Append the current timestamp and file extention to the sanitised image name
            $timestampedImage = match (true) {
                $imageType === IMAGETYPE_JPEG => appendTimestampAndJpgToImage($sanitisedImageName),
                $imageType === IMAGETYPE_PNG => appendTimestampAndPngToImage($sanitisedImageName),
                $imageType === IMAGETYPE_GIF => appendTimestampAndGifToImage($sanitisedImageName),
            };
            
            // Set the destination folder for the timestamped image
            $timeStampedImgDestination = __DIR__ . '/image_uploads/'  . $timestampedImage;

            // Get the width and height of the original submitted image
            [$originalImageWidth, $originalImageHeight] = getImageSize($submittedImageTempName);

            // Set the desired max dimensions of the image to scale
            $maxImageDimensions = (float) 500;

            // Scale the image
            ['scaled_width' => $scaledWidth, 'scaled_height' => $scaledHeight] = scaleDownImage($maxImageDimensions, $originalImageWidth, $originalImageHeight);

            /* 
            Initiate a variable to store a newly created image.
            If the image is a jpg, png, or gif, create a new image from that format.
            */
            $newImageFormat = match (true) {
                $imageType === IMAGETYPE_JPEG => imagecreatefromjpeg($submittedImageTempName),
                $imageType === IMAGETYPE_PNG  => imagecreatefrompng($submittedImageTempName),
                $imageType === IMAGETYPE_GIF  => imagecreatefromgif($submittedImageTempName),
                default => $_SESSION['message'] = <<<'ERROR_MSG'
                <p class="error-message">
                    Only jpg/jpeg, png, and gif image uploads are supported.
                </p>
                ERROR_MSG,
            };
            
            // Create a new image
            $image = imagecreatetruecolor( $scaledWidth,  $scaledHeight);

            // Copy and resample the new image
            $imageResampled = imagecopyresampled($image, $newImageFormat, 0, 0, 0, 0, $scaledWidth, $scaledHeight, $originalImageWidth, $originalImageHeight);

            // Output the image to the image_uploads file
            match (true) {
                $imageType === IMAGETYPE_JPEG => imagejpeg($image, $timeStampedImgDestination),
                $imageType === IMAGETYPE_PNG => imagepng($image, $timeStampedImgDestination),
                $imageType === IMAGETYPE_GIF => imagegif($image, $timeStampedImgDestination),
            };
        }
  
        // Check if the form was submitted
        if (!empty($title) && !empty($created_on) && !empty($body)) {
            // Insert the new journal entry into the database
            $newEntry = insertNewJournalEntry($title, $created_on, $body, $timestampedImage);
            // Check if the insertion was successful
            if ($newEntry) {
                // Display a success message
                $_SESSION['message'] = <<<'SUCCESS_MSG'
                <p class="success-message">
                    Your journal entry was added successfully!
                </p>;
                SUCCESS_MSG;
            } else {
                // Display an error message
                $_SESSION['message'] = <<<'ERROR_MSG'
                <p class="error-message">
                    Sorry, there was an error adding your journal entry. Please try again.
                </p>
                ERROR_MSG;
            }
            header('Location: index.php');
            exit;
        } else {
            // Display an error message if the form fields are empty
            $_SESSION['message'] = <<<'ERROR_MSG'
            <p class="error-message">
                Please fill in all fields before submitting your journal entry.
            </p>
            ERROR_MSG;
        }
    }

?>

    <!-- Include the header file -->
<?php include 'inc/header.inc.php'; ?>
    <!-- Content container -->
    <div class="container container--form">
        <h1 class="page-title">New Journal Entry</h1>
        <!-- Display success or error message -->
        <?php
            if (isset($_SESSION['message'])) {
                echo $_SESSION['message'];
                unset($_SESSION['message']);
            }
        ?>
        <!-- Journal entry submission form -->
        <form method="POST"
              class="site-form"
              enctype="multipart/form-data"
        >
            <!-- Entry title -->
            <label class="site-form__label">Entry Title:
                <input type="text"
                       class="site-form__input"
                       name="title"
                       title="Title of your new journal entry"
                >
            </label>
            <!-- Entry date -->
            <label class="site-form__label">Entry Date:
                <input type="date"
                       class="site-form__input"
                       name="created_on"
                       title="Date of your new journal entry"
                >
            </label>
            <!-- File upload -->
            <label class="site-form__label">Image:
                <input type="file"
                       class="site-form__input file-upload"
                       name="image"
                       title="upload an image"
                >
            </label>
            <!-- Entry body -->
            <label class="site-form__label">What's on your mind?
                <textarea name="body"
                          rows="10"
                          class="site-form__textarea"
                          title="Share whatever you like"
                ></textarea>
            </label>
            <!-- Form submit button -->
            <button class="site-form__submit-btn site-btn"
                    type="submit"
                    title="Submit a new journal entry"
            >
                📔 Add New Journal Entry
            </button>
            <!-- Add a hidden input -->
            <input type="hidden"
                   name="action"
                   value="submit-entry"
            >
        </form>
    </div>
    <!-- Include the footer file -->
<?php include 'inc/footer.inc.php'; ?>