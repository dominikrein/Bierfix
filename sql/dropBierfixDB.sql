ALTER TABLE `bierfix`.`artikel` DROP FOREIGN KEY `artikel_fk0`;
ALTER TABLE `bierfix`.`bestellungen` DROP FOREIGN KEY `bestellungen_fk0`;
ALTER TABLE `bierfix`.`bestellte_artikel` DROP FOREIGN KEY `bestellte_artikel_fk0`;
ALTER TABLE `bierfix`.`bestellte_artikel` DROP FOREIGN KEY `bestellte_artikel_fk1`;

DROP TABLE IF EXISTS `bierfix`.`artikel`;
DROP TABLE IF EXISTS `bierfix`.`bedienungen`;
DROP TABLE IF EXISTS `bierfix`.`einstellungen`;
DROP TABLE IF EXISTS `bierfix`.`bestellungen`;
DROP TABLE IF EXISTS `bierfix`.`bestellte_artikel`;
DROP TABLE IF EXISTS `bierfix`.`artikel_typen`;
DROP DATABASE `bierfix`;