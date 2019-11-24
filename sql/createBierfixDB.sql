CREATE DATABASE `bierfix` CHARACTER SET utf8 COLLATE utf8_unicode_ci;
CREATE TABLE `bierfix`.`artikel` (
	`id` int NOT NULL AUTO_INCREMENT,
	`bezeichnung` varchar(255) NOT NULL,
	`details` varchar(255),
	`typ` int NOT NULL,
	`preis` DECIMAL(10,2) NOT NULL,
	`farbe` varchar(255) NOT NULL,
	PRIMARY KEY (`id`)
)ENGINE = InnoDB;

CREATE TABLE `bierfix`.`bedienungen` (
	`id` int NOT NULL AUTO_INCREMENT,
	`name` varchar(255) NOT NULL,
	`ip` varchar(255) NOT NULL,
	`mac` varchar(255) NOT NULL,
	PRIMARY KEY (`id`)
)ENGINE = InnoDB;

CREATE TABLE `bierfix`.`einstellungen` (
	`key` varchar(255) NOT NULL,
	`value` varchar(255),
	PRIMARY KEY (`key`)
)ENGINE = InnoDB;

CREATE TABLE `bierfix`.`bestellungen` (
	`id` int NOT NULL AUTO_INCREMENT,
	`tischnummer` varchar(255) NOT NULL,
	`bediener_id` int NOT NULL,
	`zeitstempel` DATETIME NOT NULL,
	PRIMARY KEY (`id`)
)ENGINE = InnoDB;

CREATE TABLE `bierfix`.`bestellte_artikel` (
	`bestellung_id` int NOT NULL,
	`artikel_id` int NOT NULL,
	`bestellte_anzahl` int NOT NULL
)ENGINE = InnoDB;

CREATE TABLE `bierfix`.`artikel_typen` (
	`id` int NOT NULL AUTO_INCREMENT,
	`bezeichnung` varchar(255) NOT NULL,
	PRIMARY KEY (`id`)
)ENGINE = InnoDB;

ALTER TABLE `bierfix`.`artikel` ADD CONSTRAINT `artikel_fk0` FOREIGN KEY (`typ`) REFERENCES `bierfix`.`artikel_typen`(`id`);

ALTER TABLE `bierfix`.`bestellungen` ADD CONSTRAINT `bestellungen_fk0` FOREIGN KEY (`bediener_id`) REFERENCES `bierfix`.`bedienungen`(`id`);

ALTER TABLE `bierfix`.`bestellte_artikel` ADD CONSTRAINT `bestellte_artikel_fk0` FOREIGN KEY (`bestellung_id`) REFERENCES `bierfix`.`bestellungen`(`id`);

ALTER TABLE `bierfix`.`bestellte_artikel` ADD CONSTRAINT `bestellte_artikel_fk1` FOREIGN KEY (`artikel_id`) REFERENCES `bierfix`.`artikel`(`id`);