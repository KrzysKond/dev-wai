<?php
require_once 'business.php';
require_once 'controller_utils.php';
require_once 'functions.php';


function pictures(&$model)
{
    $pictures = get_pictures();
    $picturesPerPage = 2;
    $totalPictures = count($pictures);
    $totalPages = ceil($totalPictures / $picturesPerPage);

    if (!isset($_GET['page'])) {
        $currentPage = 1;
    } else {
        $currentPage = $_GET['page'];
    }

    $start = ($currentPage - 1) * $picturesPerPage;
    $end = $start + $picturesPerPage;

    $picturesToShow = array_slice($pictures, $start, $picturesPerPage);

    $model['pictures'] = $picturesToShow;
    $model['currentPage'] = $currentPage;
    $model['totalPages'] = $totalPages;

    return 'pictures_view';
}


function picture(&$model)
{
    if (!empty($_GET['id'])) {
        $id = $_GET['id'];

        if ($picture = get_picture($id)) {
            $model['picture'] = $picture;

            return 'picture_view';
        }
    }

    http_response_code(404);
    exit;
}
function delete(&$model)
{
    if (!empty($_REQUEST['id'])) {
        $id = $_REQUEST['id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            delete_picture($id);
            return 'redirect:pictures';

        } else {
            if ($picture = get_picture($id)) {
                $model['picture'] = $picture;
                return 'delete_view';
            }
        }
    }

    http_response_code(404);
    exit;
}

function edit(&$model)
{
    $db = get_db();
    $picture = [
        'image' => null,
        'author' => null,
        'country' => null,
        'watermark' => null,
        'thumbnail' => null,
        '_id' => null
    ];

    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $allowedFormats = ['image/jpeg', 'image/png'];
        $maxFileSize = 1000000;

        if (!empty($_FILES['image']['tmp_name'])) {
            $uploadDir = 'images/';
            $uploadFile = $uploadDir . basename($_FILES['image']['name']);
            $fileType = $_FILES['image']['type'];
            $fileSize = $_FILES['image']['size'];


            if (!in_array($fileType, $allowedFormats)) {
                $errors[] = "Niedozwolony format pliku. Dozwolone formaty: JPG, PNG.";
            }


            if ($fileSize > $maxFileSize) {
                $errors[] = "Plik przekracza dozwolony rozmiar (1 MB).";
            }

            if (empty($errors)) {
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                    $originalImagePath = $uploadFile;
                    $watermarkedImagePath = 'images/watermarked_' . basename($_FILES['image']['name']);
                    $thumbnailImagePath = 'images/thumbnail_' . basename($_FILES['image']['name']);

                    applyWatermark($originalImagePath, $_POST['watermark'], $watermarkedImagePath);
                    createThumbnail($originalImagePath, $thumbnailImagePath);
                } else {
                    $errors[] = "Błąd podczas przesyłania pliku!";
                }
            }
        }

        if (!empty($_POST['author']) &&
            !empty($_POST['country']) &&
            !empty($_POST['watermark'])
        ) {
            if (empty($errors)) {
                $picture = [
                    'country' => $_POST['country'],
                    'author' => $_POST['author'],
                    'watermark' => $_POST['watermark']
                ];
                $id = isset($_POST['id']) ? $_POST['id'] : null;
                if (!empty($uploadFile)) {
                    $picture['image'] = $uploadFile;
                }
                if (save_picture($id, $picture)) {
                    return 'redirect:pictures';
                }
            }
        }
    } else {
        if (!empty($_GET['id'])) {
            $picture = get_picture($_GET['id']);
        }
    }
    $model['picture'] = $picture;
    $model['errors'] = $errors;
    return 'edit_view';
}



function register(&$model) {
    $model['result'] = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['rep_password']) && isset($_POST['email_addr'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $rep_password = $_POST['rep_password'];
            $email = $_POST['email_addr'];
            $free = isUsernameFree($username);
            if ($free === true) {
                if ($password === $rep_password) {
                    if (addUser($email, $username, $password) === 0) {
                        return 'register_success';
                    } else {
                        $model['result'] = 'Wystąpił nieznany problem. Proszę spróbować później.';
                        return 'register_view';
                    }
                } else {
                    $model['result'] = 'Hasła nie są jednakowe.';
                    return 'register_view';
                }
            } elseif ($free === false) {
                $model['result'] = 'Nazwa użytkownika jest zajęta.';
                return 'register_view';
            } else {
                $model['result'] = 'Wystąpił nieznany problem. Proszę spróbować później.';
                return 'register_view';
            }
        } else {
            return 'redirect:/register';
        }
    } else {
        return 'register_view';
    }
}

function logout(&$model) {
    session_destroy();
    session_unset();
    session_start();

    return 'logout_view';
}

function login(&$model) {
    $model['result'] = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            if (readUser($username, $password)) {
                $model['result'] = 'Zalogowano pomyślnie';
                return 'login_success';
            } else {
                $model['result'] = 'Nie znaleziono użytkownika o podanych danych.';
                return 'login_view';
            }
        } else {
            return 'redirect:/login';
        }
    } else {
        return 'login_view';
    }
}

function choice(&$model)
{
    $model['choice'] = get_choice();
    return 'partial/choice_view';
}

function add_to_choice(&$model)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_images'])) {
        $selected_images = $_POST['selected_images'];

        foreach ($selected_images as $id) {
            $picture = get_picture($id);
            $choice = &get_choice();
            $choice[$id] = [
                'country' => $picture['country'],
                'author' => $picture['author'],
                'image' => $picture['image']
            ];
        }

        return is_ajax() ? choice($model) : 'redirect:' . $_SERVER['HTTP_REFERER'];
    }
}

function clear_choice(&$model)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if(isset($_POST['checkboxButton']) && is_array($_POST['checkboxButton'])) {
            foreach($_POST['checkboxButton'] as $key => $value) {
                unset($_SESSION['choice'][$key]);
            }
        }

        return is_ajax() ? choice($model) : 'redirect:' . $_SERVER['HTTP_REFERER'];
    }
}
