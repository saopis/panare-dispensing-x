/*
MySQL Data Transfer
Source Host: localhost
Source Database: hos
Target Host: localhost
Target Database: hos
Date: 29/5/2008 11:46:47
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for hospital_department
-- ----------------------------
CREATE TABLE `hospital_department` (
  `id` int(11) NOT NULL default '0',
  `name` varchar(200) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=tis620;

-- ----------------------------
-- Records 
-- ----------------------------
INSERT INTO `hospital_department` VALUES ('1', 'เวชระเบียน');
INSERT INTO `hospital_department` VALUES ('2', 'OPD');
INSERT INTO `hospital_department` VALUES ('3', 'องค์กรแพทย์');
INSERT INTO `hospital_department` VALUES ('4', 'กลุ่มงานการพยาบาล');
INSERT INTO `hospital_department` VALUES ('5', 'เอ็กเรย์');
INSERT INTO `hospital_department` VALUES ('6', 'การเงิน');
INSERT INTO `hospital_department` VALUES ('7', 'ห้องยา');
INSERT INTO `hospital_department` VALUES ('8', 'ชันสูตร');
INSERT INTO `hospital_department` VALUES ('9', 'อุบัติเหตุฉุกเฉิน');
INSERT INTO `hospital_department` VALUES ('10', 'ทันตกรรม');
INSERT INTO `hospital_department` VALUES ('11', 'OR-LR');
INSERT INTO `hospital_department` VALUES ('12', 'IPD');
INSERT INTO `hospital_department` VALUES ('13', 'โรงครัว');
INSERT INTO `hospital_department` VALUES ('14', 'โรงรถ');
INSERT INTO `hospital_department` VALUES ('15', 'คลังเวชภัณฑ์');
INSERT INTO `hospital_department` VALUES ('16', 'สารเสพติด');
INSERT INTO `hospital_department` VALUES ('17', 'สมุนไพร');
INSERT INTO `hospital_department` VALUES ('18', 'ซ่อมบำรุง');
INSERT INTO `hospital_department` VALUES ('19', 'ประกัน');
INSERT INTO `hospital_department` VALUES ('20', 'บริหารงานทั่วไป');
INSERT INTO `hospital_department` VALUES ('21', 'IC');
INSERT INTO `hospital_department` VALUES ('22', 'IT');
INSERT INTO `hospital_department` VALUES ('23', 'PCU ใน รพ.');
