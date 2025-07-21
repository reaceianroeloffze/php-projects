<?php
require_once './inc/functions.inc.php';
require_once './inc/images.inc.php';

?>
<?php include './views/header.php'; ?>

<?php

if (!empty($_GET['image'])) {
    $image = $_GET['image'];
    $title = $imageTitles[$image];
    $description = $imageDescriptions[$image];
    echo "<h1 class='img-title'>$title</h1>";
    echo "<img src='./images/$image' alt='$title'>";
    echo "<p>$description</p>";
}

?>

<a href="gallery.php">Back to Gallery</a>

<?php include './views/footer.php'; ?>
