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
		'deadline' => '22.12.2018',
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

function tasksCount(array $tasks_list, $nametask) {
    $i = 0;
    foreach ($tasks_list as $value) {
        if ($value['type'] == $nametask) {
			$i++;
		}
    }

    return $i;
}

$title = 'Список задач - Дела в Порядке';
$username = 'Дмитрий';

$content = include_template( 'main.php', ['main_list' => $main_list, 'tasks' => $tasks, 'show_complete_tasks' => $show_complete_tasks] );
print ( include_template('layout.php', ['content' => $content, 'title' => $title, 'username' => $username]) );
?>


