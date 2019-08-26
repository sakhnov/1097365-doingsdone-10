<?php
/**
 * Функция queryProject - выбирает проекты пользователя
 *
 * @param int $user id пользователя, чьи проекты необходимо выбрать
 * @return array
 */

function queryProject(mysqli $conn, int $user):array {

    $query_project = 'select count(task.id) AS count_task, project.id, project.project_name from project left join task on id_project = project.id where project.id_user = ' . $user . ' group by project.project_name';
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
 * @param int $id_project id проекта, для выборки задач только из этого проекта
 * @return array
 */

function queryTask(mysqli $conn, int $user, int $id_project = 0):array {

    if (empty($id_project)) {
        $query_task = 'select id_project, create_task, status, title, file, deadline from task join project on id_project = project.id where project.id_user = ' . $user;
    } else {
        $query_task = 'select id_project, create_task, status, title, file, deadline from task join project on id_project = project.id where project.id_user = ' . $user . ' and project.id = ' . $id_project;
    }
    $result_task = mysqli_query($conn, $query_task);
    if ($result_task) {
        $result_task = mysqli_fetch_all($result_task, MYSQLI_ASSOC);
    }

    return $result_task;
}

/**
 * Функция isProject - определяет является ли id действующим проектом для данного пользователя
 *
 * @param int $id_project id проверяемого проекта
 * @param int $user id пользователя
 * @return boolean
 */

function isProject(mysqli $conn, int $id_project, int $user): bool {

    $query_project = 'select count(*) from project where id_user = ' . $user .' and id = ' . $id_project;
    $result_project = mysqli_query($conn, $query_project);

    if ($result_project) {
        $result_project = mysqli_fetch_all($result_project, MYSQLI_NUM);
    }

    return $result_project[0][0];
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
