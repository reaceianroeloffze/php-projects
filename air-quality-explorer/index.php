<?php
require_once __DIR__ . '/inc/functions.inc.php';
require_once __DIR__ . '/inc/api.inc.php';

?>

<?php require __DIR__ . '/views/header.inc.php'; ?>

    <ul>
        <?php foreach (($filteredResponseArray ?? '') as $country) { ?>
            <li>
                <?php echo e($country['name']) ?>
                (<?php echo e($country['code']); ?>)
            </li>
        <?php } ?>
    </ul>

<?php require __DIR__ . '/views/footer.inc.php'; ?>