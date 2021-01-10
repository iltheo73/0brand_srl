CREATE TABLE `node_tree` (
	`idNode` INT(32) NOT NULL AUTO_INCREMENT,
	`level` INT(5) NULL DEFAULT NULL,
	`iLeft` INT(5) NULL DEFAULT NULL,
	`iRight` INT(5) NULL DEFAULT NULL,
	PRIMARY KEY (`idNode`)
)
COLLATE='utf8_general_ci';

CREATE TABLE `node_tree_names` (
	`idNode` INT(32) NOT NULL AUTO_INCREMENT,
	`language` VARCHAR(50) NULL DEFAULT NULL,
	`NodeName` VARCHAR(100) NULL DEFAULT NULL,
	FOREIGN KEY (`idNode`) REFERENCES node_tree(`idNode`)
)
COLLATE='utf8_general_ci';
