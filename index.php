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

	$content = include_template( 'main.php', ['main_list' => queryProject($conn, $user), 'tasks' => queryTask($conn, $user), 'show_complete_tasks' => $show_complete_tasks] );
	echo include_template('layout.php', ['content' => $content, 'title' => $title, 'username' => $username]);

}



?>


