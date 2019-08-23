<?php
/**
 * Функция queryProject - выбирает проекты пользователя
 *
 * @param int $user id пользователя, чьи проекты необходимо выбрать
 * @return array
 */

function queryProject(mysqli $conn, int $user):array {

    $query_project = 'select count(task.id) AS count_task, project.project_name from project left join task on id_project = project.id where project.id_user = ' . $user . ' group by project.project_name';
    $result_project = mysqli_query($conn, $query_project);
    if ($result_project) {
        $result_project = mysqli_fetch_all($result_project, MYSQLI_ASSOC);
    }

    return $result_project;
}


/**
 * Функция queryTask - выбирает задачи пользователя
 *
 * @param int $user id пользователя, чьи задачи необходимо выбрать
 * @return array
 */

function queryTask(mysqli $conn, int $user):array {

    $query_task = 'select id_project, create_task, status, title, file, deadline from task join project on id_project = project.id where project.id_user = ' . $user;
    $result_task = mysqli_query($conn, $query_task);
    if ($result_task) {
        $result_task = mysqli_fetch_all($result_task, MYSQLI_ASSOC);
    }

    return $result_task;
}


/**
 * Функция isDeadlineClose - определяет осталось ли до дедлайна меньше суток
 *
 * @param string $deadline дата завешения исполнения задачи.
 * @return boolean
 */

function isDeadlineClose(string $deadline): bool {

    return ($deadline && (floor(time() - strtotime($deadline)) <= 24*60*60));
}
