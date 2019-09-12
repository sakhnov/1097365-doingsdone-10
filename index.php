<?php
include_once ('helpers.php');
include_once ('function.php');

$title = 'Список задач - Дела в Порядке';

// Подключение к Базе Данных
$conn = mysqli_connect('localhost', 'root', '', '1097365-doingsdone-10');

if ($conn == false) {

    echo 'Ошибка подключения: ' . mysqli_connect_error();

} else {

    $userInfo = getUserInfo($conn, intval($_SESSION['userId']));

    if ($userInfo) {

        $show_complete_tasks = 0;
        if ($_GET['show_completed']) {
            $show_complete_tasks = 1;
        }

        if ($_GET['complete_task'] == 1 && $_GET['task_id']) {
           compliteTask($conn, intval($_GET['task_id']));
        } elseif ($_GET['complete_task'] == 0 && $_GET['task_id']) {
           uncompliteTask($conn, $_GET['task_id']);
        }

        if ($_GET['delete_task']) {
            deleteTask($conn, intval($_GET['delete_task']));
        }

        if (isset($_GET["project"]) && is_numeric($_GET["project"]) && isUserProject($conn, $_GET["project"], intval($userInfo['id']))) {
            $idProject = $_GET["project"];
            $content = include_template( 'main.php', ['main_list' => getProjects($conn, intval($userInfo['id'])), 'tasks' => getTaskProject($conn, intval($userInfo['id']), $idProject), 'show_complete_tasks' => $show_complete_tasks, 'userInfo' => $userInfo]);
        } elseif (!isset($_GET['project']))  {
            $content = include_template( 'main.php', ['main_list' => getProjects($conn, intval($userInfo['id'])), 'tasks' => getTasks($conn, intval($userInfo['id']), $_GET['show_tasks']), 'show_complete_tasks' => $show_complete_tasks, 'userInfo' => $userInfo] );
        } else {
            http_response_code(404);
            die();
        }

        if ($_GET['q']) {
			$searchWord = clear($_GET['q']);
            $content = include_template( 'main.php', ['main_list' => getProjects($conn, intval($userInfo['id'])), 'tasks' => getTaskSearch($conn, intval($userInfo['id']), $searchWord), 'show_complete_tasks' => $show_complete_tasks, 'userInfo' => $userInfo, 'searchWord' => $searchWord]);
        }

        echo include_template('layout.php', ['main_list' => getProjects($conn, intval($userInfo['id'])), 'content' => $content, 'title' => $title, 'userInfo' => $userInfo]);

    } else {
        $guest = include_template( 'guest.php');
        echo include_template('layout.php', ['guest' => $guest, 'title' => $title, 'userInfo' => $userInfo]);
    }


}



?>


