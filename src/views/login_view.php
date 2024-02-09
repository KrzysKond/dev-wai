<!DOCTYPE html>
<html lang="pl">
<?php include "includes/head.inc.php"; ?>
<form name="login" method="post" action="/login">
    <fieldset>
        <legend>Logowanie</legend>
        <label for="username">Nazwa użytkownika:</label><br>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Hasło:</label><br>
        <input type="password" id="password" name="password" required><br>
        <input type="submit" value="Zaloguj"><br>
    </fieldset>
    <p style="color:red">
        <?= $result ?>
    </p>
</form>
<?php include "includes/footer.inc.php"; ?>
</html>