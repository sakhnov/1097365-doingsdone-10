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
        $errors = errorsFormAuth($conn, $_POST);
        if (count($errors)) {
            echo include_template('auth.php', ['title' => $title, 'errors' => $errors]);
        } else {

            $userFormEmail = mysqli_real_escape_string($conn, $_POST['email']);
            $userFormPass = $_POST['password'];
            $userAuth = userAuth($conn, $userFormEmail, $userFormPass);

            if ($userAuth) {
                header('Location: /');
                exit();
            } else {
                $errors['password'] = 'Пароль не подходит';
                echo  include_template('auth.php', ['title' => $title, 'errors' => $errors]);
            }

        }
    } else {

        echo  include_template('auth.php', ['title' => $title ]);
    }
}

