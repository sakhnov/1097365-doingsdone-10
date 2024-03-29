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
        $errors = errorsFormProject($conn, $_POST, intval($userInfo['id']));

        if (count($errors)) {
            $content =  include_template('form-project.php', ['main_list' => getProjects($conn, intval($userInfo['id'])), 'title' => $title, 'errors' => $errors]);
        } else {
            $projectName = mysqli_real_escape_string($conn, $_POST['name']);

            addProject($conn, $projectName, intval($userInfo['id']));
            $content =  include_template('form-project.php', ['main_list' => getProjects($conn, intval($userInfo['id'])), 'title' => $title ]);
        }
    } else {

        $content =  include_template('form-project.php', ['main_list' => getProjects($conn, intval($userInfo['id'])), 'title' => $title ]);
    }

echo include_template('layout.php', ['main_list' => getProjects($conn, intval($userInfo['id'])), 'content' => $content, 'title' => $title, 'userInfo' => $userInfo]);
}



