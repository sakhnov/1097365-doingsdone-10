<?php
require 'vendor/autoload.php';
include_once ('helpers.php');
include_once ('function.php');

$conn = mysqli_connect('localhost', 'root', '', '1097365-doingsdone-10');

if ($conn == false) {

    echo 'Ошибка подключения: ' . mysqli_connect_error();

} else {
    $sqlTask = 'SELECT task.deadline, task.title, task.status, user.email, user.name, user.id from task join project on task.id_project = project.id join user ON project.id_user = user.id where STR_TO_DATE(task.deadline, "%Y-%m-%d") = CURDATE() and task.status = 0 order By user.id ASC';
    $result = mysqli_query($conn, $sqlTask);
    if ($result) {
        $resultTask = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    $sqlUser = 'SELECT user.email, user.name, user.id from task join project on task.id_project = project.id join user ON project.id_user = user.id where STR_TO_DATE(task.deadline, "%Y-%m-%d") = CURDATE() and task.status = 0 group by user.id';
    $resultUser = mysqli_query($conn, $sqlUser);
    if ($resultUser) {
        $resultUser = mysqli_fetch_all($resultUser, MYSQLI_ASSOC);
    }

    foreach ($resultUser as $valueUser) {
        $bodyTitle = 'Уважаемый, ' . $valueUser['name'];
        $bodyContent = '';
        foreach ($resultTask as $value) {
            if ($valueUser['id'] == $value['id']) {
                $bodyContent .= 'У вас запланирована задача ' . $value['name'] . ' на ' . $value['deadline'];
            }
         $message = $bodyTitle ."". $bodyContent;
        }
        sendemail($valueUser['email'], $valueUser['name'], $message);
    }
}


?>
