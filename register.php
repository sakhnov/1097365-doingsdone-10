<?php
include_once ('helpers.php');
include_once ('function.php');
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

// Задаем id текущего пользователя
$user = 1;
$title = 'Список задач - Дела в Порядке';
$username = 'Дмитрий';


// Подключение к Базе Данных
$conn = mysqli_connect('localhost', 'root', '', '1097365-doingsdone-10');

if ($conn == false) {

    echo 'Ошибка подключения: ' . mysqli_connect_error();

} else {

    if ($_POST) {
        $errors = errorsFormRegister($conn, $_POST);

        if (count($errors)) {
            echo include_template('register.php', ['main_list' => getProjects($conn, $user), 'content' => $content, 'title' => $title, 'username' => $username, 'errors' => $errors]);

        } else {
            $userName = mysqli_real_escape_string($conn, $_POST['name']);
            $userEmail = $_POST['email'];
            $userPassword = $_POST['password'];

            addUser($conn, $userName, $userEmail, $userPassword);
            header('Location: /');
        }
    } else {

        echo include_template('register.php', ['main_list' => getProjects($conn, $user), 'content' => $content, 'title' => $title, 'username' => $username]);
    }
}
?>
