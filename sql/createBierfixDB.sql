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

CREATE TABLE `bierfix`.`einstellungen` (
	`key` varchar(255) NOT NULL,
	`value` varchar(255),
	PRIMARY KEY (`key`)
)ENGINE = InnoDB;

CREATE TABLE `bierfix`.`bestellungen` (
	`id` int NOT NULL AUTO_INCREMENT,
	`tischnummer` varchar(255) NOT NULL,
	`bediener_name` varchar(255) NOT NULL,
	`zeitstempel` DATETIME NOT NULL,
	`bon` varchar(255),
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

CREATE TABLE `bierfix`.`bondrucker` (
	`id` int NOT NULL AUTO_INCREMENT,
	`bezeichnung` varchar(255) NOT NULL,
	`ipaddr` varchar(255) NOT NULL,
	`device_id` varchar(255) NOT NULL,
	PRIMARY KEY (`id`)
)ENGINE = InnoDB;

CREATE TABLE `bierfix`.`bondrucker_typen` (
	`bondrucker_id` int NOT NULL,
	`artikeltyp_id` int NOT NULL
)ENGINE = InnoDB;

ALTER TABLE `bierfix`.`artikel` ADD CONSTRAINT `artikel_fk0` FOREIGN KEY (`typ`) REFERENCES `bierfix`.`artikel_typen`(`id`);

ALTER TABLE `bierfix`.`bestellte_artikel` ADD CONSTRAINT `bestellte_artikel_fk0` FOREIGN KEY (`bestellung_id`) REFERENCES `bierfix`.`bestellungen`(`id`);

ALTER TABLE `bierfix`.`bestellte_artikel` ADD CONSTRAINT `bestellte_artikel_fk1` FOREIGN KEY (`artikel_id`) REFERENCES `bierfix`.`artikel`(`id`);

ALTER TABLE `bierfix`.`bondrucker_typen` ADD CONSTRAINT `bondrucker_typen_fk0` FOREIGN KEY (`bondrucker_id`) REFERENCES `bierfix`.`bondrucker`(`id`);

ALTER TABLE `bierfix`.`bondrucker_typen` ADD CONSTRAINT `bondrucker_typen_fk1` FOREIGN KEY (`artikeltyp_id`) REFERENCES `bierfix`.`artikel_typen`(`id`);