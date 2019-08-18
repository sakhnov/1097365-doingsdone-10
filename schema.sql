CREATE DATABASE `1097365-doingsdone-10`;

USE `1097365-doingsdone-10`;

CREATE TABLE `1097365-doingsdone-10`.`project` (
	`id` INT NOT NULL AUTO_INCREMENT ,
	`id_user` INT NOT NULL ,
	`project_name` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
	PRIMARY KEY (`id`)
);

CREATE TABLE `1097365-doingsdone-10`.`task` (
	`id` INT NOT NULL AUTO_INCREMENT ,
	`id_project` INT NOT NULL ,
	`id_user` INT NOT NULL ,
	`create_task` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
	`status` BOOLEAN NOT NULL ,
	`title` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
	`file` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL ,
	`deadline` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL ,
	PRIMARY KEY (`id`)
);

CREATE TABLE `1097365-doingsdone-10`.`user` (
	 `id` INT NOT NULL AUTO_INCREMENT ,
	 `create_user` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
	 `email` TEXT NOT NULL ,
	 `name` TEXT NOT NULL ,
	 `pass` TEXT NOT NULL ,
	 PRIMARY KEY (`id`)
);
