<!DOCTYPE html>
<html lang="pl">
<?php
include 'includes/head.inc.php';
?>
<form name="register" method="post" action="/register">
    <fieldset>
        <legend>Rejestracja</legend>
        <label for="email_addr">Adres email:</label><br>
        <input type="email" name="email_addr" id="email_addr" required><br>
        <label for="username">Nazwa użytkownika:</label><br>
        <input type="text" name="username" id="username" required><br>
        <label for="password">Hasło:</label><br>
        <input type="password" id="password" name="password" required><br>
        <label for="rep_password">Powtórz hasło:</label><br>
        <input type="password" id="rep_password" name="rep_password" required><br>
        <input type="submit" value="Zarejestruj"><br>
    </fieldset>
    <p style="color: red;">
        <?= $result ?>
    </p>
</form>
<?php
include 'includes/footer.inc.php';
?>
</html>