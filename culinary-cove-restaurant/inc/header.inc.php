<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/simple.css"/>
    <link rel="stylesheet" href="../styles/custom.css"/>
    <!-- Dynamically add the page currently on to title. -->
    <title>Culinary Cove &bull; <?php echo !empty($pageTitle) ? $pageTitle : 'Home'; ?></title>
</head>
<body>
<header class="header-with-background" style="background-image: url('<?php echo $headerImg; ?>'); ">
    <h1>Culinary Cove</h1>
    <p>Your sanctuary for exceptional flavours</p>
    <nav>
        <!-- Add active class when on a specific page -->
        <a href="./" <?php if ($pageKey === 'mission'): ?> class="active" <?php endif; ?> >Our mission</a>
        <a href="./ingredients.php" <?php if ($pageKey === 'ingredients'): ?> class="active" <?php endif; ?> >Ingredients</a>
        <a href="./menu.php" <?php if ($pageKey === 'menu'): ?> class="active" <?php endif; ?> >Menu</a>
    </nav>
</header>

<main>