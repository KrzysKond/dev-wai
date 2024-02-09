<?php


use MongoDB\BSON\ObjectID;


function get_db()
{
    $mongo = new MongoDB\Client(
        "mongodb://localhost:27017/wai",
        [
            'username' => 'wai_web',
            'password' => 'w@i_w3b',
        ]);

    $db = $mongo->wai;

    return $db;
}

function get_pictures()
{
    $db = get_db();
    return $db->pictures->find()->toArray();
}

function readUser($username, $password) {
    try {
        $db = get_db();
        $user = $db->users->findOne(['username' => $username]);
        if ($user !== null && password_verify($password, $user['password'])) {
            session_regenerate_id();
            $_SESSION['user_id'] = $user['_id'];
            $_SESSION['username'] =$user['username'];
            return true;
        } else return false;
    } catch (Exception $e) {
        return $e;
    }
}
function isUsernameFree($username) {
    try {
        $db = get_db();
        $user = $db->users->findOne(['username' => $username]);
        if ($user === null) {
            return true;
        } else {
            return false;
        }
    } catch (Exception $e) {
        return $e;
    }
}

function addUser($email, $username, $password) {
    try {
        $db = get_db();
        $user = $db->users->insertOne([
            'email' => $email,
            'username' => $username,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);
        return 0;
    } catch (Exception $e) {
        return $e;
    }
}
function get_picture($id)
{
    $db = get_db();
    return $db->pictures->findOne(['_id' => new ObjectID($id)]);
}

function save_picture($id, $picture)
{
    $db = get_db();

    if ($id == null) {
        $db->pictures->insertOne($picture);
    } else {
        $db->pictures->replaceOne(['_id' => new ObjectID($id)], $picture);
    }

    return true;
}

function delete_picture($id)
{
    $db = get_db();
    $db->pictures->deleteOne(['_id' => new ObjectID($id)]);
}
