<?php
/**
 * Функция queryProject - выбирает проекты пользователя
 *
 * @param int $user id пользователя, чьи проекты необходимо выбрать
 * @return array
 */

function getProjects(mysqli $conn, int $user):array {

    $queryProject = 'select count(task.id) AS count_task, project.id, project.project_name from project left join task on id_project = project.id where project.id_user = ' . $user . ' group by project.project_name';
    $resultProject = mysqli_query($conn, $queryProject);
    if ($resultProject) {
        $resultProject = mysqli_fetch_all($resultProject, MYSQLI_ASSOC);
    }

    return $resultProject;
}


/**
 * Функция queryTask - выбирает задачи пользователя
 *
 * @param int $user id пользователя, чьи задачи необходимо выбрать
 * @return array
 */

function getTasks(mysqli $conn, int $user):array {

    $queryTask = 'select id_project, create_task, status, title, file, deadline from task join project on id_project = project.id where project.id_user = ' . $user;

    $resultTask = mysqli_query($conn, $queryTask);
    if ($resultTask) {
        $resultTask = mysqli_fetch_all($resultTask, MYSQLI_ASSOC);
    }

    return $resultTask;
}

/**
 * Функция getTaskProject - выбирает задачи определнного проекта
 *
 * @param int $user id пользователя, чьи задачи необходимо выбрать
 * @param int $idProject id проекта, для выборки задач только из этого проекта
 * @return array
 */

function getTaskProject(mysqli $conn, int $user, int $idProject):array {

    $queryTask = 'select id_project, create_task, status, title, file, deadline from task join project on id_project = project.id where project.id_user = ' . $user . ' and project.id = ' . $idProject;

    $resultTask = mysqli_query($conn, $queryTask);
    if ($resultTask) {
        $resultTask = mysqli_fetch_all($resultTask, MYSQLI_ASSOC);
    }

    return $resultTask;
}




/**
 * Функция isUserProject - определяет является ли id действующим проектом для данного пользователя
 *
 * @param int $idProject id проверяемого проекта
 * @param int $user id пользователя
 * @return boolean
 */

function isUserProject(mysqli $conn, int $idProject, int $user): bool {

    $queryProject = 'select count(*) from project where id_user = ' . $user .' and id = ' . $idProject;
    $resultProject = mysqli_query($conn, $queryProject);

    if ($resultProject) {
        $resultProject = mysqli_fetch_all($resultProject, MYSQLI_NUM);
    }

    return $resultProject[0][0];
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
