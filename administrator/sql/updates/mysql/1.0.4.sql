UPDATE `#__estaciones` SET `variables`='T,H,LL' WHERE `variables`='T H LL';
UPDATE `#__estaciones` SET `variables`='T,H,V,LL' WHERE `variables`='T H V LL';
UPDATE `#__estaciones` SET `variables`='T,H,V,LL,P' WHERE `variables`='T H V LL P';
UPDATE `#__estaciones` SET `variables`='T,H,V,LL,P,R' WHERE `variables`='T H V LL P R';
UPDATE `#__estaciones` SET `variables`='T,H,V,LL,P,R,I' WHERE `variables`='T H V LL P R I';
UPDATE `#__estaciones` SET `tipo_estacion`='SEACV2' WHERE `tipo_estacion`='SEAC';
UPDATE `#__estaciones` SET `tipo_estacion`='RSD' WHERE `tipo_estacion`='RADAR';

DROP TABLE IF EXISTS `#__fotos`;

ALTER TABLE `#__estaciones` ADD `comentarios` VARCHAR(250) AFTER `geografica`;
ALTER TABLE `#__estaciones` ADD `ficherote` TEXT AFTER `comentarios`;

ALTER TABLE `#__mantenimientos` ADD `ficherote` TEXT AFTER `ultimo`;