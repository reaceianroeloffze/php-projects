<?php
require_once './inc/functions.inc.php';
require_once './inc/images.inc.php';

$increment = 0;

?>
<?php require_once './views/header.php'; ?>
<div class="img-container">
    <?php foreach ($imageTitles as $filename => $title) : ?>
        <figure class="figure image">
            <a href="./image.php">
                <img src="images/<?php echo rawurldecode($filename); ?>" alt="<?php echo e($title); ?>"
                     class="figure-img <?php echo 'img' . ++$increment; ?> ">
            </a>
            <figcaption class="image-caption"><a
                        href=" ./image.php?image=<?= rawurldecode($filename) ?>"><?php echo e($title); ?></a>
            </figcaption>
        </figure>
    <?php endforeach; ?>
</div>

<?php include './views/footer.php'; ?>
