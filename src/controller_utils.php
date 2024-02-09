<?php

function &get_choice()
{
    if (!isset($_SESSION['choice'])) {
        $_SESSION['choice'] = [];
    }

    return $_SESSION['choice'];
}
