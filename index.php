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

    if (isset($_GET["project"]) && is_numeric($_GET["project"]) && isProject($conn, $_GET["project"], $user)) {
        $id_project = $_GET["project"];
        $content = include_template( 'main.php', ['main_list' => queryProject($conn, $user), 'tasks' => queryTask($conn, $user, $id_project), 'show_complete_tasks' => $show_complete_tasks] );
    } elseif (!isset($_GET['project']))  {
        $content = include_template( 'main.php', ['main_list' => queryProject($conn, $user), 'tasks' => queryTask($conn, $user), 'show_complete_tasks' => $show_complete_tasks] );
    } else {
        http_response_code(404);
        die();
    }

	echo include_template('layout.php', ['content' => $content, 'title' => $title, 'username' => $username]);

}



?>


