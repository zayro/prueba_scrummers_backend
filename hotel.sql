/*
 Navicat Premium Data Transfer

 Source Server         : mysql_localhost
 Source Server Type    : MySQL
 Source Server Version : 80016
 Source Host           : localhost:3306
 Source Schema         : hotel

 Target Server Type    : MySQL
 Target Server Version : 80016
 File Encoding         : 65001

 Date: 17/09/2020 02:30:48
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for reservation
-- ----------------------------
DROP TABLE IF EXISTS `reservation`;
CREATE TABLE `reservation`  (
  `id_reservation` int(11) NOT NULL AUTO_INCREMENT,
  `checkin` date NULL DEFAULT NULL,
  `checkout` date NULL DEFAULT NULL,
  `id_room` int(11) NOT NULL,
  PRIMARY KEY (`id_reservation`, `id_room`) USING BTREE,
  INDEX `id_room`(`id_room`) USING BTREE,
  CONSTRAINT `reservation_ibfk_1` FOREIGN KEY (`id_room`) REFERENCES `room` (`id_room`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of reservation
-- ----------------------------
INSERT INTO `reservation` VALUES (5, '2020-09-13', '2020-09-25', 3);
INSERT INTO `reservation` VALUES (6, '2020-09-15', '2020-09-25', 2);
INSERT INTO `reservation` VALUES (9, '2020-09-07', '2020-09-17', 2);
INSERT INTO `reservation` VALUES (10, '2020-09-16', '2020-10-01', 3);
INSERT INTO `reservation` VALUES (13, '2020-09-21', '2020-09-30', 2);
INSERT INTO `reservation` VALUES (14, '2020-09-22', '2020-10-01', 3);
INSERT INTO `reservation` VALUES (15, '2020-09-07', '2020-09-23', 2);

-- ----------------------------
-- Table structure for room
-- ----------------------------
DROP TABLE IF EXISTS `room`;
CREATE TABLE `room`  (
  `id_room` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  PRIMARY KEY (`id_room`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of room
-- ----------------------------
INSERT INTO `room` VALUES (1, 'familiar');
INSERT INTO `room` VALUES (2, 'doubles');
INSERT INTO `room` VALUES (3, 'singles');

-- ----------------------------
-- View structure for view_reservation
-- ----------------------------
DROP VIEW IF EXISTS `view_reservation`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `view_reservation` AS select `reservation`.`id_reservation` AS `id_reservation`,date_format(`reservation`.`checkin`,'%m/%d/%Y') AS `checkin`,date_format(`reservation`.`checkout`,'%m/%d/%Y') AS `checkout`,(to_days(`reservation`.`checkout`) - to_days(`reservation`.`checkin`)) AS `days`,`room`.`id_room` AS `id_room`,`room`.`name` AS `name` from (`reservation` join `room` on((`reservation`.`id_room` = `room`.`id_room`))) order by `reservation`.`id_reservation` desc;

SET FOREIGN_KEY_CHECKS = 1;
