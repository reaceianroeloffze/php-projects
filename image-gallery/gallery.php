<?php
require_once './inc/functions.inc.php';
require_once './inc/images.inc.php';

?>
<?php require_once './views/header.php'; ?>
<div class="img-container">
    <figure class="figure">
        <?php foreach($imageTitles AS $filename => $title) : ?>
        <img src="images/<?php echo $filename; ?>" alt="<?php echo $title; ?>" class="figure-img img-fluid rounded">
        <figcaption class="figure-caption"><?php echo $title; ?></figcaption>
        <?php endforeach; ?>
    </figure>
</div>

<?php include './views/footer.php'; ?>
