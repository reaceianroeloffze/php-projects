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
        $title = (trim(filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS)) ?? '');
        // The date the entry was created
        $created_on = (trim(filter_input(INPUT_POST, 'created_on', FILTER_SANITIZE_FULL_SPECIAL_CHARS)) ?? '');
        // The text content of the entry
        $body = (trim(filter_input(INPUT_POST, 'body', FILTER_SANITIZE_FULL_SPECIAL_CHARS)) ?? '');

        /* Scale the uploaded image */
        $timestampedImage = NULL; // Default value if no image is uploaded

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

            // Get the temporary image file path/name
            $submittedImageTempName = (string) $_FILES['image']['tmp_name'];

            // Get the info of the uploaded image
            $uploadedImageInfo = @getimagesize($submittedImageTempName);

            // Ensure the uploaded image is an image
            if ($uploadedImageInfo === FALSE) {
                $_SESSION['message'] =
                    <<<'ERROR_MSG'
                    <p class="error-message">
                        Please upload an image file (jpg, png, GIF)
                    </p>
                    ERROR_MSG;
            } else {

                // Get and store the image type
                $imageType = $uploadedImageInfo[2];

                // Specify which images are allowed to be uploaded
                $allowedImageTypes = [
                    IMAGETYPE_JPEG,
                    IMAGETYPE_PNG,
                    IMAGETYPE_GIF
                ];

                // Ensure the uploaded image is an allowed image type
                if (!in_array($imageType, $allowedImageTypes)) {
                    $_SESSION['message'] =
                        <<<'ERROR_MSG'
                    <p class="error-message">
                        Please upload either a jpg, png, or a GIF image.
                    </p>
                    ERROR_MSG;
                }

                // Sanitise the uploaded image filename
                $uploadedImageName = preg_replace('/[^a-zA-Z0-9\-.]/', '', pathinfo($submittedImage, PATHINFO_FILENAME));

                // Append the current timestamp and the image extension to the uploaded image name
                $timestampedImage = match ($imageType) {
                    IMAGETYPE_JPEG => appendTimestampAndJpgToImage($uploadedImageName),
                    IMAGETYPE_PNG => appendTimestampAndPngToImage($uploadedImageName),
                    IMAGETYPE_GIF => appendTimestampAndGifToImage($uploadedImageName),
                };

                // Set the destination folder for the uploaded image
                $uploadedImageDestinationFolder = __DIR__ . '/image_uploads/' . $timestampedImage;

                // Set the dimensions of the uploaded image
                $imageDimensions = 500;

                // Resize and save the uploaded image
                [$originalImageWidth, $originalImageHeight] = $uploadedImageInfo;
                ['scaled_width' => $width, 'scaled_height' => $height,] = scaleDownImage($imageDimensions, $originalImageWidth, $originalImageHeight);

                // create a new image resource from the uploaded image
                $resizedUploadedImage = match ($imageType) {
                    IMAGETYPE_JPEG => imagecreatefromjpeg($submittedImageTempName),
                    IMAGETYPE_PNG => imagecreatefrompng($submittedImageTempName),
                    IMAGETYPE_GIF => imagecreatefromgif($submittedImageTempName),
                };

                // Create the resized image
                $finalUploadedImage = imagecreatetruecolor($width, $height);

                // Resample the uploaded image onto the final image
                imagecopyresized($finalUploadedImage, $resizedUploadedImage, 0, 0, 0, 0, $width, $height, $originalImageWidth, $originalImageHeight);

                // Save the resized image to the destination folder
                match ($imageType) {
                    IMAGETYPE_JPEG => imagejpeg($finalUploadedImage, $uploadedImageDestinationFolder),
                    IMAGETYPE_PNG => imagepng($finalUploadedImage, $uploadedImageDestinationFolder),
                    IMAGETYPE_GIF => imagegif($finalUploadedImage, $uploadedImageDestinationFolder),
                };

                // Destroy the uploaded image resource
                imagedestroy($resizedUploadedImage);
                imagedestroy($finalUploadedImage);
            }

            // Make sure that all required form fields are filled out
            if (empty($title) || empty($body) || empty($created_on)) {
                $_SESSION['message'] =
                    <<<'ERROR_MSG'
                    <p class="error-message">
                        Please fill in all required fields.
                    </p>
                    ERROR_MSG;
            }
        }

        // Add the new journal entry to the database
        $newJournalEntry = insertNewJournalEntry($title, $created_on, $body, $timestampedImage);

        // Set a success message if the entry was successfully added to the database
        $_SESSION['message'] = $newJournalEntry
            ? '<p class="success-message">Journal entry added successfully!</p>'
            : '<p class="error-message">There was an error adding your journal entry. Please try again.</p>';

        // Redirect the user back to the homepage
        header('Location: index.php');
        exit;
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
                    <?php if (isset($title)) echo "value='$title'"; ?>
                >
            </label>
            <!-- Entry date -->
            <label class="site-form__label">Entry Date:
                <input type="date"
                       class="site-form__input"
                       name="created_on"
                       title="Date of your new journal entry"
                    <?php if (isset($created_on)) echo "value='$created_on'"; ?>
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
                ><?php if (isset($body)) echo $body; ?></textarea>
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