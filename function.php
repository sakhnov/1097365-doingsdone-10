<?php
session_start();

/**
 * Функция queryProject - выбирает проекты пользователя
 *
 * @param int $user id пользователя, чьи проекты необходимо выбрать
 * @return array
 */

function getProjects(mysqli $conn, int $user):array
{
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
 * @param string $showTask для вЫборки задач
 * @return array
 */

function getTasks(mysqli $conn, int $user, string $showTask = null): array
{
    switch ($showTask) {
        case 'today':
            $queryTask = 'select task.id, id_project, create_task, status, title, file, deadline from task join project on id_project = project.id where project.id_user = ' . $user . ' and STR_TO_DATE(deadline, "%Y-%m-%d") = CURDATE()';
            break;
        case 'tomorrow':
            $queryTask = 'select task.id, id_project, create_task, status, title, file, deadline from task join project on id_project = project.id where project.id_user = ' . $user . ' and STR_TO_DATE(deadline, "%Y-%m-%d") = CURDATE() + INTERVAL 1 DAY';
            break;
        case 'overdue':
            $queryTask = 'select task.id, id_project, create_task, status, title, file, deadline from task join project on id_project = project.id where project.id_user = ' . $user . ' and STR_TO_DATE(deadline, "%Y-%m-%d") < CURDATE()';
            break;
        default:
            $queryTask = 'select task.id, id_project, create_task, status, title, file, deadline from task join project on id_project = project.id where project.id_user = ' . $user;
            break;
    }

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

function getTaskProject(mysqli $conn, int $user, int $idProject):array
{
    $queryTask = 'select task.id, id_project, create_task, status, title, file, deadline from task join project on id_project = project.id where project.id_user = ' . $user . ' and project.id = ' . $idProject;

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

function isUserProject(mysqli $conn, int $idProject, int $user): bool
{
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

function isDeadlineClose(string $deadline): bool
{

    return ($deadline && (floor(strtotime($deadline) - time()) <= 24*60*60));
}


/**
 * Функция getPostVal - возвращает значение поля формы
 *
 * @param string $name имя поля формы
 * @return string
 */

function getPostVal($name):string
{

    return $_POST[$name] ?? "";
}


/**
 * Функция errorsFormTask - валидация формы создания задачи
 *
 * @param mysqli $conn подключение к БД
 * @param array $post массив $_POST передаваемый из формы
 * @param int $user id пользователя, чьи задачи необходимо выбрать
 * @return array
 */

function errorsFormTask(mysqli $conn, array $post, int $user): array
{
    $errors = [];
    if (!$post) {

		return $errors;
	}

    if (empty($post['name'])) {
        $errors['name'] = 'Напишите название задачи';
    }

    if (!empty($post['date'])) {

        if (!is_date_valid($post['date'])) {
            $errors['date'] = 'Не верный формат даты';
        } elseif ($post['date'] < date('Y-m-d')) {
            $errors['date'] = 'Дата выполнения должна быть из будущего';
        }
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

function addTask(mysqli $conn, int $taskProject, string $taskName, string $taskDate, array $file): bool
{
    $file_url = false;
    if (!empty($file['file']['name'])) {
        $pathinfo = pathinfo($file['file']['name']);
        $extension = $pathinfo['extension'];
        $file_name = uniqid(). '.' . $extension;
        $file_path = __DIR__ . '/';
        $file_url = '/' . $file_name;
        move_uploaded_file($file['file']['tmp_name'], $file_path . $file_name);
    }

    $taskName = mysqli_real_escape_string($conn, $taskName);
    $taskDate = mysqli_real_escape_string($conn, $taskDate);

    if ($file_url) {
        $sql = 'INSERT INTO task SET id_project = ?, status = false, title = ?, deadline = ?, file = ?';
        $stmt = db_get_prepare_stmt($conn, $sql, array($taskProject, $taskName, $taskDate, $file_url));
    } else {
        $sql = 'INSERT INTO task SET id_project = ?, status = false, title = ?, deadline = ?';
        $stmt = db_get_prepare_stmt($conn, $sql, array($taskProject, $taskName, $taskDate));
    }

    return mysqli_stmt_execute($stmt);;
}


/**
 * Функция errorsFormRegister - валидация формы создания задачи
 *
 * @param mysqli $conn подключение к БД
 * @param array $post массив $_POST передаваемый из формы
 * @return array
 */

function errorsFormRegister(mysqli $conn, array $post): array
{
    $errors = [];
    if (!$post) { return $errors; }

    if (empty($post['name'])) {
        $errors['name'] = 'Напишите ваше имя';
    }

    if (empty($post['email'])) {
        $errors['email'] = 'Введите Email';
    } elseif (!filter_var($post['email'], FILTER_VALIDATE_EMAIL)){
        $errors['email'] = 'Введите корректный Email';
    } elseif (checkUserEmail($conn, $post['email'])) {
        $errors['email'] = 'Email уже существует!';
    }

    if (empty($post['password'])) {
        $errors['password'] = 'Придумайте пароль';
    }

    return $errors;
}

/**
 * Функция checkUserEmail - Проверка email на существование в базе
 *
 * @param mysqli $conn подключение к БД
 * @param string $email регистрируемый email пользователя
 * @return boolean
 */

function checkUserEmail(mysqli $conn, string $email): bool
{
	$result = [];
    $email = mysqli_real_escape_string($conn, $email);
    $sql = 'SELECT count(*) FROM user WHERE email = "' . $email .'"';
    $result1 = mysqli_query($conn, $sql);

    if ($result1) {
        $result = mysqli_fetch_all($result1, MYSQLI_NUM);
    }

    return $result[0][0];
}

/**
 * Функция addUser - Регистрация пользователя
 *
 * @param mysqli $conn подключение к БД
 * @param string $userName Имя пользователя
 * @param string $userEmail Email пользователя
 * @param string $userPassword пароль
 * @return boolean
 */

function addUser(mysqli $conn, string $userName, string $userEmail, string $userPassword): bool
{
        $userName = mysqli_real_escape_string($conn, $userName);
        $userEmail = mysqli_real_escape_string($conn, $userEmail);
        $userPassword = mysqli_real_escape_string($conn, $userPassword);

        $sql = 'INSERT INTO user SET name = ?, email = ?, pass = ?';
        $stmt = db_get_prepare_stmt($conn, $sql, array($userName, $userEmail, password_hash($userPassword, PASSWORD_DEFAULT)));

    return mysqli_stmt_execute($stmt);
}

/**
 * Функция getUserInfo возвращает данныу текущего пользователя
 *
 * @param mysqli $conn
 * @param int|null $user_id
 * @return array
 */

function getUserInfo(mysqli $conn, int $userId = null):array
{
    $result = [];
    if (isset($userId) && !empty($userId)) {
        $sql = 'select id, email, name, pass from user where id = ' . $userId;
        $resultSql = mysqli_query($conn, $sql);
        if ($resultSql->num_rows) {
            $result = mysqli_fetch_array($resultSql, MYSQLI_ASSOC);
        }
    }
    return $result;
}


/**
 * Функция errorsFormAuth - валидация формы аутентификации
 *
 * @param mysqli $conn подключение к БД
 * @param array $post массив $_POST передаваемый из формы
 * @return array
 */

function errorsFormAuth(mysqli $conn, array $post): array
{
    $errors = [];
    if (!$post) { return $errors; }

    if (empty($post['email'])) {
        $errors['email'] = 'Введите Email';
    } elseif (!filter_var($post['email'], FILTER_VALIDATE_EMAIL)){
        $errors['email'] = 'Введите корректный Email';
    }

    if (empty($post['password'])) {
        $errors['password'] = 'Не верный пароль';
    }

    return $errors;
}

/**
 * Функция userAuth авторизация пользователя
 *
 * @param mysqli $conn
 * @param string $userFormEmail введеный Email
 * @param string $userFormPass введеный пароль
 * @return boolean
 */

function userAuth($conn, string $userFormEmail, string $userFormPass): bool
{
        $userFormEmail = mysqli_real_escape_string($conn, $userFormEmail);
        $userFormPass = mysqli_real_escape_string($conn, $userFormPass);

        $sql = 'select id, pass from user where email = "' . $userFormEmail . '"';
        $result = mysqli_query($conn, $sql);

		if (!$result->num_rows) {
			return false;
		}

		$result = mysqli_fetch_array($result, MYSQLI_ASSOC);
		if (password_verify($userFormPass, $result['pass'])) {
			$_SESSION['userId'] = $result['id'];

			return true;
		}

		return false;
}


/**
 * Функция errorsFormProject - валидация формы добавления проекта
 *
 * @param mysqli $conn подключение к БД
 * @param array $post массив $_POST передаваемый из формы
 * @param int $userId id пользователя
 * @return array
 */

function errorsFormProject(mysqli $conn, array $post, int $userId): array
{
    $errors = [];
    if (!$post) { return $errors; }

    if (empty($post['name'])) {
        $errors['name'] = 'Введите название проекта';
    } else {
        $sql = 'select id from project where id_user = "' . $userId . '" and project_name = "' . $post['name'] .'"';
        $result = mysqli_query($conn, $sql);

        if ($result->num_rows > 0) {
            $errors['name'] = 'Такой проект уже существует';
        }

    }

    return $errors;
}

/**
 * Функция addProject - Добавление задачи в БД
 *
 * @param mysqli $conn подключение к БД
 * @param string $projectName название проекта
 * @param int $userId id пользователя который добавляет проект
 * @return boolean
 */

function addProject(mysqli $conn, string $projectName, int $userId): bool
{
    $projectName = mysqli_real_escape_string($conn, $projectName);

    $sql = 'INSERT INTO project SET project_name = ?, id_user = ?';
    $stmt = db_get_prepare_stmt($conn, $sql, array($projectName, $userId));

    return mysqli_stmt_execute($stmt);
}


/**
 * Функция logoutUser - Выход из учетной записи. Разлогинивание пользователя.
 */

function logoutUser():void
{
    if (isset($_SESSION['userId']) && !empty($_SESSION['userId'])) {
        $_SESSION['userId'] = null;
        header('Location: /');
    }
}

/**
 * Функция compliteTask - Выставляет статус задачи - выполнена
 *
 * @param mysqli $conn подключение к БД
 * @param int $taskId id задачи
 * @return boolean
 */
function compliteTask(mysqli $conn, int $taskId): bool
{
    $sql = 'UPDATE task SET status = ? WHERE id = ?';
    $stmt = db_get_prepare_stmt($conn, $sql, array(1, $taskId));

    return mysqli_stmt_execute($stmt);
}

/**
 * Функция uncompliteTask - Выставляет статус задачи - не выполнена
 *
 * @param mysqli $conn подключение к БД
 * @param int $taskId id задачи
 * @return boolean
 */
function uncompliteTask(mysqli $conn, int $taskId): bool
{
    $sql = 'UPDATE task SET status = ? WHERE id = ?';
    $stmt = db_get_prepare_stmt($conn, $sql, array(0, $taskId));

    return mysqli_stmt_execute($stmt);
}

/**
 * Функция deleteTask - Удаляет задачу
 *
 * @param mysqli $conn подключение к БД
 * @param int $taskId id задачи
 * @return boolean
 */
function deleteTask(mysqli $conn, int $taskId): bool
{
    $sql = 'DELETE FROM task WHERE id = ?';
    $stmt = db_get_prepare_stmt($conn, $sql, array($taskId));

    return mysqli_stmt_execute($stmt);
}

/**
 * Функция getTaskSearch - выбирает задачи в соответствии с поиском
 *
 * @param int $user id пользователя, чьи задачи необходимо выбрать
 * @return array
 */

function getTaskSearch(mysqli $conn, int $user, string $searchWord):array
{
    $returnTask = [];
    $searchWord = mysqli_real_escape_string($conn, $searchWord);
    $queryTask = 'select task.id, id_project, create_task, status, title, file, deadline from task join project on id_project = project.id where project.id_user = ' . $user .' and MATCH(title) AGAINST("' . $searchWord . '")';

    $resultTask = mysqli_query($conn, $queryTask);
    if ($resultTask) {
        $returnTask = mysqli_fetch_all($resultTask, MYSQLI_ASSOC);
    }

    return $returnTask;
}

/**
 * Функция clear - очистка ввода от тегов, спецсимволов
 *
 * @param string $var строка которую надо очистить
 * @return string
 */

function clear(string $var): string
{
    $var =  htmlspecialchars(trim(strip_tags($var)), ENT_QUOTES, 'UTF-8');

    return $var;
}

/**
 * Функция sendemail - отправка писем
 *
 * @param string $email email пользователя
 * @param string $name имя пользователя
 * @param string $body сообщение.
 */

function sendemail(string $email, string $name, string $body): void
{
    $transport = new Swift_SmtpTransport('phpdemo.ru', 25);
    $transport->setUsername('keks@phpdemo.ru');
    $transport->setPassword('htmlacademy');
// Формирование сообщения
    $message = new Swift_Message("Уведомление от сервиса «Дела в порядке»");
    $message->setFrom(['keks@phpdemo.ru' => 'Keks']);
    $message->setTo([$email => $name]);
    $message->setBody($body, 'text/plain');

// Отправка сообщения
    $mailer = new Swift_Mailer($transport);
    $mailer->send($message);
}
