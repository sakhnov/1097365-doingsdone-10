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
    if (!empty($userInfo)) {

        $show_complete_tasks = 0;
        if (!empty($_GET['show_completed'])) {
            $show_complete_tasks = 1;
        }

        if (isset($_GET['complete_task']) && $_GET['complete_task'] === '1' && !empty($_GET['task_id']) && is_numeric
            ($_GET['task_id'])) {
           compliteTask($conn, $_GET['task_id']);
        }
        if (isset($_GET['complete_task']) && $_GET['complete_task'] === '0' && !empty($_GET['task_id']) && is_numeric
            ($_GET['task_id'])) {
           uncompliteTask($conn, $_GET['task_id']);
        }
        if (!empty($_GET['delete_task']) && is_numeric($_GET['delete_task'])) {
            deleteTask($conn, $_GET['delete_task']);
        }

        if (!empty($_GET["project"]) && is_numeric($_GET["project"]) && isUserProject($conn, $_GET["project"], intval($userInfo['id']))) {
            $idProject = $_GET["project"];
            $content = include_template( 'main.php', ['main_list' => getProjects($conn, intval($userInfo['id'])), 'tasks' => getTaskProject($conn, intval($userInfo['id']), $idProject), 'show_complete_tasks' => $show_complete_tasks, 'userInfo' => $userInfo]);
        } elseif (empty($_GET['project']))  {
            $show_tasks = $_GET['show_tasks'] ?? '';
            $content = include_template( 'main.php', ['main_list' => getProjects($conn, intval($userInfo['id'])), 'tasks' => getTasks($conn, intval($userInfo['id']), $show_tasks), 'show_complete_tasks' => $show_complete_tasks, 'userInfo' => $userInfo] );
        } else {
            http_response_code(404);
            die();
        }

        if (!empty($_GET['q'])) {
			$searchWord = clear($_GET['q']);
            $content = include_template( 'main.php', ['main_list' => getProjects($conn, intval($userInfo['id'])), 'tasks' => getTaskSearch($conn, intval($userInfo['id']), $searchWord), 'show_complete_tasks' => $show_complete_tasks, 'userInfo' => $userInfo, 'searchWord' => $searchWord]);
        }

        echo include_template('layout.php', ['main_list' => getProjects($conn, intval($userInfo['id'])), 'content' => $content, 'title' => $title, 'userInfo' => $userInfo]);

    } else {
        $guest = include_template( 'guest.php');
        echo include_template('layout.php', ['guest' => $guest, 'title' => $title, 'userInfo' => $userInfo]);
    }


}
