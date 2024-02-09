<link rel="icon" href="data:;base64,iVBORw0KGgo=">

<link rel="stylesheet" href="static/css/styles.css"/>

<script src="static/js/jquery-1.11.3.min.js"></script>
<script src="static/js/main.js"></script>
<?php
if (empty($_SESSION['user_id'])) {
    echo '<a href="/login">Zaloguj się</a> ';
    echo '<a href="/register">Zarejestruj się</a>';
} else {;
    echo $_SESSION['username'];
    echo '<a href="/logout">          Wyloguj</a>';
}
?>