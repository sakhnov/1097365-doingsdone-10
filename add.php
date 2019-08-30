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

    if ($_POST) {
        $errors = errorsForm($conn, $_POST, $user);

        if (count($errors)) {
            echo include_template('form-task.php', ['main_list' => getProjects($conn, $user), 'title' => $title, 'username' => $username, 'errors' => $errors]);
        } else {

            $taskName = mysqli_real_escape_string($conn, $_POST['name']);
            $taskProject = intval($_POST['project']);
            $taskDate = $_POST['date'];

            if (isset($_FILES['file'])) {
                $file_name = $_FILES['file']['name'];
                $file_path =__DIR__ . '/';
                $file_url = '/' . $file_name;
                move_uploaded_file($_FILES['file']['tmp_name'], $file_path . $file_name);

                $sql = "INSERT INTO task SET id_project = ?, status = false, title = ?, deadline = ?, file = ?";
                $stmt = db_get_prepare_stmt($conn, $sql, array($taskProject, $taskName, $taskDate, $file_url));
            } else {
                $sql = "INSERT INTO task SET id_project = ?, status = false, title = ?, deadline = ?";
                $stmt = db_get_prepare_stmt($conn, $sql, array($taskProject, $taskName, $taskDate));

            }

            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);
            header('Location: http://1097365-doingsdone-10/');
        }
    } else {
        echo include_template('form-task.php', ['main_list' => getProjects($conn, $user), 'title' => $title, 'username' => $username]);
    }
}




?>
