<?php
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
        $title = (string) (trim(filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS)) ?? '');
        $created_on = (string) (trim(filter_input(INPUT_POST, 'created_on', FILTER_SANITIZE_FULL_SPECIAL_CHARS)) ?? '');
        $body = (string) (trim(filter_input(INPUT_POST, 'body', FILTER_SANITIZE_FULL_SPECIAL_CHARS)) ?? '');

        // Check if the form was submitted
        if (!empty($title) && !empty($created_on) && !empty($body)) {
            // Insert the new journal entry into the database
            $newEntry = insertNewJournalEntry($title, $created_on, $body);
            // Check if the insertion was successful
            if ($newEntry) {
                // Display a success message
                $_SESSION['message'] = '<p class="success-message">Your journal entry was added successfully!</p>';
            } else {
                // Display an error message
                $_SESSION['message'] = '<p class="error-message">Sorry, there was an error adding your journal entry. Please try again.</p>';
            }
            header('Location: index.php');
            exit;
        } else {
            // Display an error message if the form fields are empty
            $_SESSION['message'] = '<p class="error-message">Please fill in all fields before submitting your journal entry.</p>';
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
        >
            <label class="site-form__label">Entry Title:
                <input type="text"
                       class="site-form__input"
                       name="title"
                       title="Title of your new journal entry"
                >
            </label>
            <label class="site-form__label">Entry Date:
                <input type="date"
                       class="site-form__input"
                       name="created_on"
                       title="Date of your new journal entry"
                >
            </label>
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