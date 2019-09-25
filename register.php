<?php
include_once ('helpers.php');
include_once ('function.php');

$title = 'Список задач - Дела в Порядке';

// Подключение к Базе Данных
$conn = mysqli_connect('localhost', 'root', '', '1097365-doingsdone-10');

if ($conn == false) {

    echo 'Ошибка подключения: ' . mysqli_connect_error();

} else {

    if ($_POST) {
        $errors = errorsFormRegister($conn, $_POST);

        if (count($errors)) {
            echo include_template('register.php', ['title' => $title, 'errors' => $errors]);
        } else {
            $userName = mysqli_real_escape_string($conn, $_POST['name']);
            $userEmail = $_POST['email'];
            $userPassword = $_POST['password'];

            addUser($conn, $userName, $userEmail, $userPassword);
            header('Location: /');
        }
    } else {
        echo include_template('register.php', ['title' => $title]);
    }
}

