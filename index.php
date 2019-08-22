<?php
include_once ('helpers.php');
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

$main_list = ['Входящие', 'Учеба', 'Работа', 'Домашние дела', 'Авто'];

$tasks = [
    [
		'name' => 'Собеседование в IT компании',
		'deadline' => '01.12.2018',
		'type' => 'Работа',
		'done' => 'Нет'
	],
    [
		'name' => 'Выполнить тестовое задание',
		'deadline' => '25.12.2018',
		'type' => 'Работа',
		'done' => 'Нет'
	],
    [
		'name' => 'Сделать задание первого раздела',
		'deadline' => '21.12.2018',
		'type' => 'Учеба',
		'done' => 'Да'
	],
    [
		'name' => 'Встреча с другом',
		'deadline' => '16.08.2019',
		'type' => 'Входящие',
		'done' => 'Нет'
	],
    [
		'name' => 'Купить корм для кота',
		'deadline' => false,
		'type' => 'Домашние дела',
		'done' => 'Нет'
	],
    [
		'name' => 'Заказать пиццу',
		'deadline' => false,
		'type' => 'Домашние дела',
		'done' => 'Нет'
	]
];

// Задаем id текущего пользователя
$user = 1;

// Подключение к Базе Данных
$conn = mysqli_connect('localhost', 'root', '', '1097365-doingsdone-10');

if ($conn == false) {
    echo 'Ошибка подключения: ' . mysqli_connect_error();
} else {

    // Выбираем все проекты пользователя с id = $user и подсчитываем кол-во задач в проектах
    $query_project = 'select count(task.id) AS count_task, project.project_name from project left join task on id_project = project.id where project.id_user = ' . $user . ' group by project.project_name';
    $result_project = mysqli_query($conn, $query_project);
    if ($result_project) {
        $result_project = mysqli_fetch_all($result_project, MYSQLI_ASSOC);
    }
    // Выбираем все задачи пользователя с id = $user
    $query_task = 'select id_project, create_task, status, title, file, deadline from task join project on id_project = project.id where project.id_user = ' . $user;
    $result_task = mysqli_query($conn, $query_task);
    if ($result_task) {
        $result_task = mysqli_fetch_all($result_task, MYSQLI_ASSOC);
    }
}


/**
* Функция taskCount - высчитывает кол-во заданий одного типа
*
* @param array $tasks_list Массив с задачами
* @param string $nametask Название типа задачи
* @return int
*/

function tasksCount(array $tasks_list, string $nametask): int  {
    $i = 0;
    foreach ($tasks_list as $value) {
        if ($value['type'] == $nametask) {
			$i++;
		}
    }

    return $i;
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


$title = 'Список задач - Дела в Порядке';
$username = 'Дмитрий';

$content = include_template( 'main.php', ['main_list' => $result_project, 'tasks' => $result_task, 'show_complete_tasks' => $show_complete_tasks] );
echo include_template('layout.php', ['content' => $content, 'title' => $title, 'username' => $username]);
?>


