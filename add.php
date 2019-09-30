<?php
include_once('helpers.php');
include_once('function.php');

$title = 'Список задач - Дела в Порядке';

// Подключение к Базе Данных
$conn = mysqli_connect('localhost', 'root', '', '1097365-doingsdone-10');

if ($conn === false) {

    echo 'Ошибка подключения: ' . mysqli_connect_error();

} else {
    $userInfo = [];
    if (isset($_SESSION['userId'])) {
        $userInfo = getUserInfo($conn, intval($_SESSION['userId']));
    }

    if (empty($userInfo)) {
            header('Location: /');
    }

    $errors = [];
    $content = '';
    if ($_POST) {
        $errors = errorsFormTask($conn, $_POST, intval($userInfo['id']));

        if (count($errors)) {
            $content =  include_template('form-task.php', ['main_list' => getProjects($conn, intval($userInfo['id'])), 'title' => $title, 'errors' => $errors]);
        } else {
            $taskName = mysqli_real_escape_string($conn, $_POST['name']);
            $taskProject = intval($_POST['project']);
            $taskDate = $_POST['date'] ?? "";

            addTask($conn, $taskProject, $taskName, $taskDate, $_FILES);
            header('Location: /');
        }
    } else {

        $content =  include_template('form-task.php', ['main_list' => getProjects($conn, intval($userInfo['id'])), 'title' => $title ]);
    }

    echo include_template('layout.php', ['main_list' => getProjects($conn, intval($userInfo['id'])), 'content' => $content, 'title' => $title, 'userInfo' => $userInfo]);
}



