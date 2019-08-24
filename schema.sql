CREATE DATABASE `1097365-doingsdone-10`;

USE `1097365-doingsdone-10`;

CREATE TABLE `1097365-doingsdone-10`.`project` (
	`id` INT NOT NULL AUTO_INCREMENT ,
	`id_user` INT NOT NULL ,
	`project_name` VARCHAR(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
	PRIMARY KEY (`id`)
);

CREATE TABLE `1097365-doingsdone-10`.`task` (
	`id` INT NOT NULL AUTO_INCREMENT ,
	`id_project` INT NOT NULL ,
	`create_task` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
	`status` BOOLEAN NOT NULL ,
	`title` VARCHAR(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
	`file` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL ,
	`deadline` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL ,
	PRIMARY KEY (`id`)
);

CREATE TABLE `1097365-doingsdone-10`.`user` (
	 `id` INT NOT NULL AUTO_INCREMENT ,
	 `create_user` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
	 `email` VARCHAR(250) NOT NULL UNIQUE,
	 `name` TEXT NOT NULL ,
	 `pass` TEXT NOT NULL ,
	 PRIMARY KEY (`id`)
);

/* Создание индекса для email. Так как будет выборка по email при авторизации полльзователя */
/* CREATE UNIQUE INDEX email ON user(email); */

/* Создание индекса для id_user. Выборка задач для определенного пользователя */
CREATE INDEX id_user ON project(id_user);

/* Создание индекса для project_name. Выборка для меню с сортировкой по названию проекта. */
CREATE INDEX project_name ON project(project_name);

/* Создание индекса для id_project. Выборка задач для определеного проекта */
CREATE INDEX id_project ON task(id_project);

/* Создание индекса для title.  Выборка и списка задач и возможная сортировка по названию. */
CREATE INDEX title ON task(title);
