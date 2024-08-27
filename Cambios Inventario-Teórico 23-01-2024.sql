CREATE TABLE `tipos_zona_plantas` (
  `idtipozonaplanta` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(50) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`idtipozonaplanta`));
  
INSERT INTO `tipos_zona_plantas` (`idtipozonaplanta`, `nombre`) VALUES ('1', 'NO APLICA');
INSERT INTO `tipos_zona_plantas` (`idtipozonaplanta`, `nombre`) VALUES ('2', 'PLANTA');
INSERT INTO `tipos_zona_plantas` (`idtipozonaplanta`, `nombre`) VALUES ('3', 'SUCURSAL');

ALTER TABLE `zonas` 
ADD COLUMN `tipo_zona_planta_id` INT NOT NULL DEFAULT 1 AFTER `tipo_zona_id`,
ADD INDEX `tipo_zona_planta_idFK_idx` (`tipo_zona_planta_id` ASC);
;
ALTER TABLE `zonas` 
ADD CONSTRAINT `tipo_zona_planta_idFK`
  FOREIGN KEY (`tipo_zona_planta_id`)
  REFERENCES `tipos_zona_plantas` (`idtipozonaplanta`)
  ON DELETE RESTRICT
  ON UPDATE CASCADE;
  
UPDATE `zonas` SET `tipo_zona_planta_id` = '2' WHERE (`idzona` = '3');
UPDATE `zonas` SET `tipo_zona_planta_id` = '2' WHERE (`idzona` = '8');
UPDATE `zonas` SET `tipo_zona_planta_id` = '2' WHERE (`idzona` = '5');
UPDATE `zonas` SET `tipo_zona_planta_id` = '2' WHERE (`idzona` = '13');
UPDATE `zonas` SET `tipo_zona_planta_id` = '3' WHERE (`idzona` = '24');
UPDATE `zonas` SET `tipo_zona_planta_id` = '3' WHERE (`idzona` = '19');
UPDATE `zonas` SET `tipo_zona_planta_id` = '3' WHERE (`idzona` = '7');
UPDATE `zonas` SET `tipo_zona_planta_id` = '3' WHERE (`idzona` = '6');
UPDATE `zonas` SET `tipo_zona_planta_id` = '3' WHERE (`idzona` = '2');
UPDATE `zonas` SET `tipo_zona_planta_id` = '3' WHERE (`idzona` = '26');
UPDATE `zonas` SET `tipo_zona_planta_id` = '3' WHERE (`idzona` = '1');

