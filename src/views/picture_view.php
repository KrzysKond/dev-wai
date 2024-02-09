<!DOCTYPE html>
<html>
<head>
    <title>Produkt</title>
    <?php include "includes/head.inc.php"; ?>
    <link rel="stylesheet" href="../web/static/css/styles.css"/>
</head>
<body>

<h1><?= $picture['country'] ?></h1>

<p>Autor: <?= $picture['author'] ?></p>

<img src="images/watermarked_<?= basename($picture['image']) ?>" alt="<?= $picture['title']?>">
<br>

<?php include "includes/footer.inc.php"; ?>

</body>
</html>
