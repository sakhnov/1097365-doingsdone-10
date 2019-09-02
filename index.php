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

    if (isset($_GET["project"]) && is_numeric($_GET["project"]) && isUserProject($conn, $_GET["project"], $user)) {
        $idProject = $_GET["project"];
        $content = include_template( 'main.php', ['main_list' => getProjects($conn, $user), 'tasks' => getTaskProject($conn, $user, $idProject), 'show_complete_tasks' => $show_complete_tasks] );
    } elseif (!isset($_GET['project']))  {
        $content = include_template( 'main.php', ['main_list' => getProjects($conn, $user), 'tasks' => getTasks($conn, $user), 'show_complete_tasks' => $show_complete_tasks] );
    } else {
        http_response_code(404);
        die();
    }

	echo include_template('layout.php', ['main_list' => getProjects($conn, $user), 'content' => $content, 'title' => $title, 'username' => $username]);

}



?>


