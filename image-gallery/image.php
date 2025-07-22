<?php
require_once './inc/functions.inc.php';
require_once './inc/images.inc.php';

?>
<?php include './views/header.php'; ?>

<?php

// Check if the image key is not empty and exists in the given associative array
/*if (!empty($_GET['image']) && array_key_exists($_GET['image'], $imageTitles)) {
    $image = rawurldecode($_GET['image']);
    $title = $imageTitles[$image];
    $description = $imageDescriptions[$image];
    echo "<h1 class='img-title'>$title</h1>";
    echo "<img src='./images/$image' alt='$title'>";
    echo "<p>$description</p>";
}*/

?>

<!-- Alternative syntax -->
<?php if (!empty($_GET['image']) && array_key_exists($_GET['image'], $imageTitles)): ?>
    <?php
    $image = rawurldecode($_GET['image']);
    $title = $imageTitles[$image];
    $description = $imageDescriptions[$image];
    ?>
    <h1 class="img-title"><?php echo e($title); ?></h1>
    <img src="./images/<?php echo e($image); ?>" alt="<?php echo e($title); ?>">
    <p><?php echo e($description); ?></p>
<?php else: ?>
    <h1 class="img-title">Image not found</h1>
<?php endif; ?>

<a href="gallery.php">Back to Gallery</a>

<?php include './views/footer.php'; ?>
