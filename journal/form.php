<?php

    /* include the required files and libraries */
    require_once 'model/journal-model.php';
    require_once 'library/functions.php';
    require_once 'library/journal-db-connect.php';

?>

    <!-- Include the header file -->
<?php include 'inc/header.inc.php'; ?>
    <!-- Content container -->
    <div class="container container--form">
        <h1 class="page-title">New Journal Entry</h1>
        <form method="POST"
              action="form.php"
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
                       name="date"
                       title="Date of your new journal entry"
                >
            </label>
            <label class="site-form__label">What's on your mind?
                <textarea name="content"
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
        </form>
    </div>
    <!-- Include the footer file -->
<?php include 'inc/footer.inc.php'; ?>