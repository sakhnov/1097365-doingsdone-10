<?php
include_once ('helpers.php');
include_once ('function.php');
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

// Задаем id текущего пользователя

$title = 'Список задач - Дела в Порядке';
$username = 'Дмитрий';


// Подключение к Базе Данных
$conn = mysqli_connect('localhost', 'root', '', '1097365-doingsdone-10');

if ($conn == false) {

    echo 'Ошибка подключения: ' . mysqli_connect_error();

} else {

    $userInfo = getUserInfo($conn, intval($_SESSION['userId']));

    if ($userInfo) {

        if (isset($_GET["project"]) && is_numeric($_GET["project"]) && isUserProject($conn, $_GET["project"], intval($userInfo['id']))) {
            $idProject = $_GET["project"];
            $content = include_template( 'main.php', ['main_list' => getProjects($conn, intval($userInfo['id'])), 'tasks' => getTaskProject($conn, intval($userInfo['id']), $idProject), 'show_complete_tasks' => $show_complete_tasks, 'userInfo' => $userInfo]);
        } elseif (!isset($_GET['project']))  {
            $content = include_template( 'main.php', ['main_list' => getProjects($conn, intval($userInfo['id'])), 'tasks' => getTasks($conn, intval($userInfo['id'])), 'show_complete_tasks' => $show_complete_tasks, 'userInfo' => $userInfo] );
        } else {
            http_response_code(404);
            die();
        }
        echo include_template('layout.php', ['main_list' => getProjects($conn, intval($userInfo['id'])), 'content' => $content, 'title' => $title, 'userInfo' => $userInfo]);

    } else {
        $guest = include_template( 'guest.php');
        echo include_template('layout.php', ['guest' => $guest, 'title' => $title, 'userInfo' => $userInfo]);
    }


}



?>


