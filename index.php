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
    if (($deadline) && (floor(time() - strtotime($deadline)) <= 24*60*60)) {

        return true; //'task--important';
    } else {
		
		return false;
	}
}


$title = 'Список задач - Дела в Порядке';
$username = 'Дмитрий';

$content = include_template( 'main.php', ['main_list' => $main_list, 'tasks' => $tasks, 'show_complete_tasks' => $show_complete_tasks] );
echo include_template('layout.php', ['content' => $content, 'title' => $title, 'username' => $username]);
?>


