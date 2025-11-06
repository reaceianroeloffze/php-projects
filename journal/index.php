<?php
    // Start a session
    session_start();
    /* 
    echo '<pre>';
    print_r($_SESSION);
    echo '</pre>';
    */
    // include the required files and libraries
    require_once __DIR__ . '/model/journal-model.php';
    require_once __DIR__ . '/library/functions.php';
    require_once __DIR__ . '/library/journal-db-connect.php';

    // Set the default timezone
    date_default_timezone_set('Africa/Johannesburg');

    // Define how many entries to display per page
    $entriesPerPage = 3;

    // Get the current page number from the URL, default to 1 if not set
    $currentPage = (INT) (filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?? 1);

    // Calculate the offest for the SQL LIMIT clause
    //How pagination works
    $offset = ($currentPage - 1) * $entriesPerPage;

    // Retrieve the journal entries from the database/model file
    $journalEntries = displayJournalEntries($entriesPerPage, $offset);
    echo '<pre>';
    print_r($journalEntries);
    echo '</pre>';

    // Get the total number of journal entries for pagination
    $entryCount = countAllJournalEntries();

    // Calculate the total number of pages based on the number of journal entries
    $numPages = (INT) ceil($entryCount / $entriesPerPage);
    
    /* echo '<pre>';
    print_r($journalEntries);
    echo '</pre>'; */
?>

    <!-- Include the header file -->
<?php include 'inc/header.inc.php'; ?>
    <!-- Page title -->
    <h1 class="page-title">Journal Entries</h1>
    <!-- Contain the entry cards -->
    <?php
        if (isset($_SESSION['message'])) {
            echo $_SESSION['message'];
            unset($_SESSION['message']);
        }
    ?>
    <div class="container entries-container">
        <!-- Section the entry cards -->
        <?php foreach ($journalEntries as $journalEntry): ?>
            <div class="entry-card">
                <!-- Divide the entry card into 2 sections -->
                <!-- Section 1: Image container and image -->
                <!-- If an image is uploaded, display it. -->
                <?php if (!empty($journalEntry['image'])): ?>
                    <div class="entry-card__image-container">
                        <img src="image_uploads/<?php echo $journalEntry['image'] ?>"
                            alt="ElePHPant by Ben Griffiths on Unsplash"
                            class="entry-card__image"
                        >
                    </div>
                <?php endif; ?>
                <!-- Section 2: Entry card heading and content -->
                <section class="entry-card__content">
                    <!-- Timestamp -->
                    <?php 
                        // Explode the created_on date string from the database into an array
                        $dateComponents = explode('-', $journalEntry['created_on']);
                        // Turn the components into variables using destructuring
                        [$year, $month, $day] = $dateComponents;
                        // Turn the date components into a timestamp
                        $timestamp = mktime (0, 0, 0, (INT) $month, (INT) $day, (INT) $year);
                    ?>
                    <!-- Convert the generated timestamp into a human-readable date -->
                    <div class="entry-card__time"><?php echo e(date('Y/m/d', $timestamp)) ?></div>
                    <!-- Entry Title -->
                    <h2 class="entry-card__title"><?php echo e($journalEntry['title']) ?></h2>
                    <!-- Entry paragraph(s) -->
                    <p class="entry-card__paragraph"><?php echo nl2br(e($journalEntry['body'])) ?></p>
                </section>
            </div>
        <?php endforeach; ?>
        <!-- Subsequent entry cards -->
        <!-- Add pagination if there is more than 1 page -->
        <?php if ($numPages > 1): ?>
            <ul class="pagination">
                <!-- Display the left page arrows only when not on the first page -->
                <?php if ($currentPage > 1): ?>
                    <!-- Page jump left arrow -->
                    <?php if ($numPages > 5): ?>
                        <li class="pagination__page-item--page-jump-left-arrow">
                            <a href="index.php?<?php echo http_build_query(['page' => $currentPage - 5]) ?>" class="pagination__page-link">&Ll;</a>
                        </li>
                    <?php endif; ?>
                    <!-- First page arrow -->
                    <li class="pagination__page-item--first-page-arrow">
                        <a href="index.php?<?php echo http_build_query(['page' => 1]) ?>"
                        class="pagination__page-link"
                        >
                        &ll;
                        </a>
                    </li>
                    <!-- Previous page arrow -->
                    <li class="pagination__page-item--previous-page-arrow">
                        <a href="index.php?<?php echo http_build_query(['page' => $currentPage - 1]) ?>"
                        class="pagination__page-link"
                        >
                            &lt;
                        </a>
                    </li>
                <?php endif; ?>
                <!-- Page numbers -->
                <?php for ($page = 1; $page <= $numPages; $page++): ?>
                    <li class="pagination__page-item">
                        <?php if ($page !== $currentPage): ?>
                            <a href="index.php?<?php echo http_build_query(['page' => $page]) ?>"
                            class="pagination__page-link"
                            >
                                <?php echo $page ?>
                            </a>
                        <?php else: ?>
                            <span class="pagination__page-link--disabled">
                                <?php echo $page ?>
                            </span>
                        <?php endif; ?>
                    </li>
                    <?php endfor; ?>
                <!-- Only display the right page arrows when not on the last page -->
                <?php if ($currentPage < $numPages): ?>
                    <!-- Next page arrow -->
                    <li class="pagination__page-item--next-page-arrow">
                        <a href="index.php?<?php echo http_build_query(['page' => $currentPage + 1]) ?>" class="pagination__page-link">&gt;</a>
                    </li>
                    <!-- Last page arrow -->
                    <li class="pagination__page-item--last-page-arrow">
                        <a href="index.php?<?php echo http_build_query(['page' => $numPages]) ?>" class="pagination__page-link">&gg;</a>
                    </li>
                    <!-- Page jump right arrow -->
                    <?php if ($numPages > 5): ?>
                        <li class="pagination__page-item--page-jump-right-arrow">
                            <a href="index.php?<?php echo http_build_query(['page' => $currentPage + 5]) ?>"
                               class="pagination__page-link"
                            >
                               &Gg;
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
        <?php endif; ?>
    </div>
    <!-- Include the footer file -->
<?php include 'inc/footer.inc.php'; ?>