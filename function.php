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

    return ($deadline && (floor(strtotime($deadline) - time()) <= 24*60*60));
}


function getPostVal($name) {

    return $_POST[$name] ?? "";
}


/**
 * Функция errorsForm - валидация формы
 *
 * @param mysqli $conn подключение к БД
 * @param array $post массив $_POST передаваемый из формы
 * @param int $user id пользователя, чьи задачи необходимо выбрать
 * @return array
 */

function errorsForm(mysqli $conn, array $post, int $user): array {

    $errors = [];
    if (!$post) { return $errors; }

    if (empty($post['name'])) {
        $errors['name'] = 'Напишите название задачи';
    }

    if (empty($post['date'])) {
        $errors['date'] = 'Выберите дату выполнения ';
    } elseif (!is_date_valid ($post['date'])) {
        $errors['date'] = 'Не верный формат даты';
    } elseif ($post['date'] < date("Y-m-d")) {
        $errors['date'] = 'Дата выполнения должна быть из будущего';
    }

    if (empty($post['project'])) {
        $errors['project'] = 'Выберите проект';
    }
    if (!isUserProject($conn, intval($post['project']), $user)) {
        $errors['project'] = 'Выберите проект из выпадающего списка';
    }

    return $errors;
}

/**
 * Функция addTask - Добавление задачи в БД
 *
 * @param mysqli $conn подключение к БД
 * @param int $taskProject id  проекта куда надо добавить задачу
 * @param string $taskName id название задачи
 * @param string $taskDate id дата выполнения задачи
 * @param string $file_url путь к файлу, если файл добавлен.
 * @return boolean
 */

function addTask(mysqli $conn, int $taskProject, string $taskName, string $taskDate, array $file): bool {

    $file_url = false;
    if (!empty($file['file']['name'])) {
        $file_name = $file['file']['name'];
        $file_path = __DIR__ . '/';
        $file_url = '/' . $file_name;
        move_uploaded_file($file['file']['tmp_name'], $file_path . $file_name);
    }

    if ($file_url) {
        $sql = "INSERT INTO task SET id_project = ?, status = false, title = ?, deadline = ?, file = ?";
        $stmt = db_get_prepare_stmt($conn, $sql, array($taskProject, $taskName, $taskDate, $file_url));
    } else {
        $sql = "INSERT INTO task SET id_project = ?, status = false, title = ?, deadline = ?";
        $stmt = db_get_prepare_stmt($conn, $sql, array($taskProject, $taskName, $taskDate));
    }

    return mysqli_stmt_execute($stmt);;
}
