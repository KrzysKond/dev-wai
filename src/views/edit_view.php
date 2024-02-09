<!DOCTYPE html>
<html>
<head>
    <title>Edycja</title>
    <?php include "includes/head.inc.php"; ?>
</head>
<body>
<?php if (!empty($errors)): ?>
    <ul class="errors">
        <?php foreach ($errors as $error): ?>
            <li><?= $error ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
    <label>
        <span>Zdjecie:</span>
        <input type="file" name="image" id="image" accept="image/png, image/jpg" required><br>
    </label>
    <label>
        <span>Autor:</span>
        <input type="text" name="author" value="<?= $picture['author'] ?>" required/>
    </label>
    <label>
        <span>Kraj:</span>
        <input type="text" name="country" value="<?= $picture['country'] ?>" required/>
    </label>
    <label>
        <span>Watermark:</span>
        <input type="text" name="watermark" value="<?= $picture['watermark'] ?>" required/>
    </label>
    <input type="hidden" name="id" value="<?= $picture['_id'] ?>">
    <div>
        <a href="/" class="cancel">Anuluj</a>
        <input type="submit" value="Zapisz"/>
    </div>
</form>

<br>

<?php include "includes/footer.inc.php"; ?>

</body>
</html>
