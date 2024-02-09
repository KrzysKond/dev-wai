<div id="choice">

    <h2>Wybrane zdjęcia</h2>
    <form action="choice/clear" method="post" class="inline">
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
        <?php if (!empty($choice)): ?>
            <?php foreach ($choice as $id => $picture): ?>
                <tr>
                    <td>
                        <?= $picture['country'] ?>
                    </td>
                    <td><?= $picture['author'] ?></td>
                    <td><img src="images/thumbnail_<?= basename($picture['image']) ?>" alt="<?= $picture['country']?>"></td>
                    <td>
                        <input type="checkbox" id="checkboxButton" name="checkboxButton[<?= $id ?>]">

                    </td>
                </tr>
            <?php endforeach ?>
        <?php else: ?>
            <tr>
                <td colspan="4">Brak wybranych zdjęć</td>
            </tr>
        <?php endif ?>
        </tbody>

        <tfoot>
        <tr>
            <td>Łącznie pozycji: <?= count($choice) ?></td>
            <td>
                <input type="submit" value="Usuń zaznaczone" name="clear_choice"/>
            </td>
        </tr>
        </tfoot>
    </table>
    </form>
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
</div>
