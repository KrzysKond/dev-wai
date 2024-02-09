<!DOCTYPE html>
<html>
<head>
    <title>Usuwanie zdjęcia</title>
    <link rel="stylesheet" href="../web/static/css/styles.css"/>
</head>
<body>

<form method="post">
    Czy usunąć zdjęcie: <?= $picture['country'] ?>?

    <input type="hidden" name="id" value="<?= $picture['_id'] ?>">

    <div>
        <a href="/" class="cancel">Anuluj</a>
        <input type="submit" value="Potwierdź"/>
    </div>
</form>

</body>
</html>
