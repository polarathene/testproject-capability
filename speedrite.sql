/*
Navicat MySQL Data Transfer

Source Server         : scotchBox
Source Server Version : 50543
Source Host           : localhost:3306
Source Database       : speedrite

Target Server Type    : MYSQL
Target Server Version : 50543
File Encoding         : 65001

Date: 2015-06-21 18:36:41
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for main
-- ----------------------------
DROP TABLE IF EXISTS `main`;
CREATE TABLE `main` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `entry_code` varchar(20) NOT NULL COMMENT 'Length and format not clear, assuming string',
  `name` varchar(255) NOT NULL COMMENT 'Single field for customers name, due to brief separate table is not required',
  `email` varchar(254) NOT NULL COMMENT 'Email length of 254 as per RFC3696 Errata ID 1690: http://www.rfc-editor.org/errata_search.php?rfc=3696',
  `phone` bigint(15) NOT NULL COMMENT 'Based on E.164: https://en.wikipedia.org/?title=E.164 ,extensions not supported',
  `store` varchar(255) NOT NULL COMMENT 'Not clear if specific stores(FK INT) or variety(VARCHAR)',
  `model_purchased` int(11) unsigned NOT NULL COMMENT 'References model table id field',
  `serial_number` varchar(255) NOT NULL COMMENT 'Length and format not clear, assuming string',
  `nplate_guess` char(3) NOT NULL COMMENT 'a99 input mask',
  PRIMARY KEY (`id`),
  UNIQUE KEY `u_entry_code` (`entry_code`) USING BTREE,
  KEY `fk_model_id` (`model_purchased`),
  CONSTRAINT `fk_model_id` FOREIGN KEY (`model_purchased`) REFERENCES `model` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for model
-- ----------------------------
DROP TABLE IF EXISTS `model`;
CREATE TABLE `model` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT 'Model name',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
