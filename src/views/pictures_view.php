<!DOCTYPE html>
<html>
<head>
    <title>zdjecia z wakacji</title>
    <?php include "includes/head.inc.php"; ?>
</head>
<body>
<h1>Zdjęcia z podróży</h1>
<form action="choice/add" method="post" class="wide" data-role="choice_form">
    <table>
        <thead>
        <tr>
            <th>Kraj</th>
            <th>Autor</th>
            <th>Zdjęcie</th>
            <th>Operacje</th>
        </tr>
        </thead>

        <tbody>
        <?php if (count($pictures)): ?>
            <?php foreach ($pictures as $picture): ?>
                <tr>
                    <td>
                        <a href="view?id=<?= $picture['_id'] ?>"><?= $picture['country'] ?></a>
                    </td>
                    <td><?= $picture['author'] ?></td>
                    <td>
                        <a href="images/watermarked_<?= basename($picture['image']) ?>" target="_blank">
                             <img src="images/thumbnail_<?= basename($picture['image']) ?>" alt="<?= $picture['country'] ?>">
                        </a>
                    </td>
                    <td>
                        <a href="edit?id=<?= $picture['_id'] ?>">Edytuj</a> |
                        <a href="delete?id=<?= $picture['_id'] ?>">Usuń</a>
                        <br>
                        <br>
                        <?php
                        $isChecked = false;
                        if (isset($_SESSION['choice']) && is_array($_SESSION['choice'])) {
                            $keyToCheck = (string) $picture['_id'];
                            $isChecked = isset($_SESSION['choice'][$keyToCheck]);
                        }
                        ?>
                        <input type="checkbox" name="selected_images[]" value="<?= $picture['_id'] ?>" <?= $isChecked ? 'checked' : '' ?>>
                        <input type="hidden" name="id" value="<?= $picture['_id'] ?>"/>
                    </td>
                </tr>
            <?php endforeach ?>
        <?php else: ?>
            <tr>
                <td colspan="4">Brak zdjęć</td>
            </tr>
        <?php endif ?>
        </tbody>

        <tfoot>
        <tr>
            <td colspan="2">Łącznie: <?= count($pictures) ?></td>
            <td>
                <a href="edit">nowe zdjęcie</a>
            </td>
        </tr>
        </tfoot>
    </table>
    <div class="pagination">
        <?php if ($model['totalPages'] > 1): ?>
            <?php for ($i = 1; $i <= $model['totalPages']; $i++): ?>
                <?php if ($i == $model['currentPage']): ?>
                    <span><?= $i ?></span>
                <?php else: ?>
                    <a href="?page=<?= $i ?>"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>
        <?php endif; ?>
    </div>
    <input type="submit" name="add_to_choice" value="Zapamietaj wybrane">
</form>
<?php dispatch($routing, '/choice') ?>

<?php include "includes/footer.inc.php"; ?>
<script>
    $(function () {
        $('form[data-role=choice_form]').unbind('submit').submit(function (e) {
            e.preventDefault();

            $('#choice').ajaxMask();

            $.post($(this).attr('action'), $(this).serialize(),
                function (response) {
                    $('#choice').replaceWith(response);
                });
        });
    });
</script>
</body>
</html>
