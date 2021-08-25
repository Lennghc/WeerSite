-- database backup - 2021-07-09 02:42:58
SET NAMES utf8;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';
SET foreign_key_checks = 0;
SET AUTOCOMMIT = 0;
START TRANSACTION;
DROP TABLE IF EXISTS `data`;

CREATE TABLE `data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plaats` varchar(50) DEFAULT NULL,
  `temp` int(11) DEFAULT NULL,
  `gtemp` int(11) DEFAULT NULL,
  `windr` int(11) DEFAULT NULL,
  `windms` int(11) DEFAULT NULL,
  `winds` int(11) DEFAULT NULL,
  `windbft` int(11) DEFAULT NULL,
  `windknp` int(11) DEFAULT NULL,
  `windk` int(11) DEFAULT NULL,
  `windkmh` int(11) DEFAULT NULL,
  `luchtd` int(11) DEFAULT NULL,
  `dauwp` int(11) DEFAULT NULL,
  `zicht` int(11) DEFAULT NULL,
  `d0tmax` int(11) DEFAULT NULL,
  `d0tmin` int(11) DEFAULT NULL,
  `d0neerslag` int(11) DEFAULT NULL,
  `sup` varchar(100) DEFAULT NULL,
  `sunder` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=164 DEFAULT CHARSET=utf8mb4;
INSERT INTO `data` VALUES('133','Amsterdam','18','16','0','4','3','3','7','7','14','1016','11','40','13','22','0','05:27','22:04');
INSERT INTO `data` VALUES('134','Friesland','15','15','0','0','1','1','0','0','0','1018','14','19','13','22','0','05:24','21:58');
INSERT INTO `data` VALUES('136','Utrecht','20','20','0','2','2','2','3','3','7','1016','13','33','13','22','0','05:26','22:02');
INSERT INTO `data` VALUES('137','Haarlem','18','16','0','4','3','3','7','7','14','1016','11','40','13','22','0','05:27','22:04');
INSERT INTO `data` VALUES('139','Oss','22','20','0','4','3','3','7','7','14','1016','12','40','13','23','0','05:27','21:57');
INSERT INTO `data` VALUES('155','Deventer','15','15','0','0','1','1','0','0','0','1018','14','14','12','22','0','05:20','21:59');
INSERT INTO `data` VALUES('156','Groningen','14','14','0','0','1','1','0','0','0','1018','13','28','12','22','8','05:15','22:02');
INSERT INTO `data` VALUES('161','Nieuwegein','13','13','0','0','1','1','0','0','0','1018','13','12','13','22','0','05:27','22:01');
INSERT INTO `data` VALUES('162','Apeldoorn','14','14','0','1','1','1','1','1','3','1018','13','10','13','22','0','05:25','21:58');
INSERT INTO `data` VALUES('163','Amsterdam','20','19','0','3','2','2','5','5','10','1020','13','40','23','12','0','','');


COMMIT;