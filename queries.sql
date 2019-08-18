/* Добавление пользователей */
INSERT INTO user SET email = 'dm.sakhnov@gmail.com', name = 'Дмитрий', pass = 'Пароль'; /* id = 2 */
INSERT INTO user SET email = 'vasvas@gmail.com', name = 'Василий', pass = 'Пароль'; /* id = 2 */

/* Добавление проектов для пользователя с id = 1 */
INSERT INTO project SET id_user = 1, project_name = 'Входящие';  /* id = 1 */
INSERT INTO project SET id_user = 1, project_name = 'Учеба'; /* id = 2 */
INSERT INTO project SET id_user = 1, project_name = 'Работа'; /* id = 3 */
INSERT INTO project SET id_user = 1, project_name = 'Домашние дела'; /* id = 4 */
INSERT INTO project SET id_user = 1, project_name = 'Авто'; /* id = 5 */

/* Добавление проектов для пользователя с id = 2 */
INSERT INTO project SET id_user = 2, project_name = 'Входящие';  /* id = 1 */
INSERT INTO project SET id_user = 2, project_name = 'Учеба'; /* id = 2 */
INSERT INTO project SET id_user = 2, project_name = 'Работа'; /* id = 3 */
INSERT INTO project SET id_user = 2, project_name = 'Домашние дела'; /* id = 4 */
INSERT INTO project SET id_user = 2, project_name = 'Авто'; /* id = 5 */

/* Добавление задач для разных пользователей*/
INSERT INTO task SET id_project = 3, id_user = 1, status = false, title = 'Собеседование в IT компании', deadline = '01.12.2018'; /* id = 1 */
INSERT INTO task SET id_project = 3, id_user = 2, status = false, title = 'Выполнить тестовое задание', deadline = '25.12.2018'; /* id = 2 */
INSERT INTO task SET id_project = 2, id_user = 1, status = true, title = 'Сделать задание первого раздела', deadline = '21.12.2018'; /* id = 3 */
INSERT INTO task SET id_project = 1, id_user = 2, status = false, title = 'Встреча с другом', deadline = '16.08.2019'; /* id = 4 */
INSERT INTO task SET id_project = 4, id_user = 1, status = false, title = 'Купить корм для кота'; /* id = 5 */
INSERT INTO task SET id_project = 4, id_user = 2, status = false, title = 'Заказать пиццу'; /* id = 6 */

/* Выборка всех проектов для пользователя c id = 1 */
select project_name from project where id_user = 1;

/* Выборка всех проектов для пользователя c id = 2 */
select project_name from project where id_user = 2;

/* Выборка всех задач для пользователя c id = 1 и с проектом у которого id = 1 */
select * from task where id_project = 1 AND id_user = 1;

/* Выборка всех задач для пользователя c id = 1 и с проектом у которого id = 2 */
select * from task where id_project = 2 AND id_user = 1;

/* Выборка всех задач для пользователя c id = 1 и с проектом у которого id = 3 */
select * from task where id_project = 3 AND id_user = 1;

/* Выборка всех задач для пользователя c id = 1 и с проектом у которого id = 4 */
select * from task where id_project = 4 AND id_user = 1;

/* Выборка всех задач для пользователя c id = 1 и с проектом у которого id = 5 */
select * from task where id_project = 5 AND id_user = 1;

/* Установили для задачи с id = 2, что задача выполнена (status = true) */
update task set status = true where id = 2;

/* Обновили название задачи с id = 4 */
update task set title = 'Новое название задачи' where id = 4;
