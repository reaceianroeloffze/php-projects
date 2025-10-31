<?php
    // Start a session
    session_start();

    // include the required files and libraries
    require_once __DIR__ . '/model/journal-model.php';
    require_once __DIR__ . '/library/functions.php';
    require_once __DIR__ . '/library/journal-db-connect.php';

    // Define how many entries to display per page
    $entriesPerPage = 3;

    // Retrieve the journal entries from the database/model file
    $journalEntries = displayJournalEntries($entriesPerPage);
    
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
                <div class="entry-card__image-container">
                    <img src="images/elePHPant.jpg"
                         alt="ElePHPant by Ben Griffiths on Unsplash"
                         class="entry-card__image"
                    >
                </div>
                <!-- Section 2: Entry card heading and content -->
                <section class="entry-card__content">
                    <!-- Time stamp -->
                    <div class="entry-card__time"><?php echo e($journalEntry['created_on']) ?></div>
                    <!-- Entry Title -->
                    <h2 class="entry-card__title"><?php echo e($journalEntry['title']) ?></h2>
                    <!-- Entry paragraph(s) -->
                    <p class="entry-card__paragraph"><?php echo nl2br(e($journalEntry['body'])) ?></p>
                </section>
            </div>
        <?php endforeach; ?>
        <!-- Subsequent entry cards -->
        <!-- Add pagination -->
        <ul class="pagination">
            <!-- Page jump left arrow -->
            <li class="pagination__page-item--page-jump-left-arrow">
                <a href="#" class="pagination__page-link">&Ll;</a>
            </li>
            <!-- First page arrow -->
            <li class="pagination__page-item--first-page-arrow">
                <a href="#" class="pagination__page-link">&ll;</a>
            </li>
            <!-- Previous page arrow -->
            <li class="pagination__page-item--previous-page-arrow">
                <a href="#" class="pagination__page-link">&lt;</a>
            </li>
            <!-- Page numbers -->
            <li class="pagination__page-item">
                <a href="#" class="pagination__page-link">1</a>
            </li>
            <li class="pagination__page-item">
                <a href="#" class="pagination__page-link">2</a>
            </li>
            <li class="pagination__page-item">
                <a href="#" class="pagination__page-link">3</a>
            </li>
            <li class="pagination__page-item">
                <a href="#" class="pagination__page-link">4</a>
            </li>
            <li class="pagination__page-item">
                <a href="#" class="pagination__page-link">5</a>
            </li>
            <li class="pagination__page-item">
                <a href="#" class="pagination__page-link">6</a>
            </li>
            <!-- Next page arrow -->
            <li class="pagination__page-item--next-page-arrow">
                <a href="#" class="pagination__page-link">&gt;</a>
            </li>
            <!-- Last page arrow -->
            <li class="pagination__page-item--last-page-arrow">
                <a href="#" class="pagination__page-link">&gg;</a>
            </li>
            <!-- Page jump right arrow -->
            <li class="pagination__page-item--page-jump-right-arrow">
                <a href="#" class="pagination__page-link">&Gg;</a>
            </li>
        </ul>
    </div>
    <!-- Include the footer file -->
<?php include 'inc/footer.inc.php'; ?>