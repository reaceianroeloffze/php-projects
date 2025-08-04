<?php

    require_once __DIR__ . '/inc/functions.inc.php';
    require_once __DIR__ . '/inc/api.inc.php';

    $country = $_GET['country'] ?? '';
    $code = $_GET['code'] ?? '';

?>

<?php require_once __DIR__ . '/views/header.inc.php'; ?>

<?php require_once __DIR__ . '/views/footer.inc.php'; ?>
