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
INSERT INTO `hospital_department` VALUES ('1', '�Ǫ����¹');
INSERT INTO `hospital_department` VALUES ('2', 'OPD');
INSERT INTO `hospital_department` VALUES ('3', 'ͧ���ᾷ��');
INSERT INTO `hospital_department` VALUES ('4', '������ҹ��þ�Һ��');
INSERT INTO `hospital_department` VALUES ('5', '�������');
INSERT INTO `hospital_department` VALUES ('6', '����Թ');
INSERT INTO `hospital_department` VALUES ('7', '��ͧ��');
INSERT INTO `hospital_department` VALUES ('8', '�ѹ�ٵ�');
INSERT INTO `hospital_department` VALUES ('9', '�غѵ��˵ةء�Թ');
INSERT INTO `hospital_department` VALUES ('10', '�ѹ�����');
INSERT INTO `hospital_department` VALUES ('11', 'OR-LR');
INSERT INTO `hospital_department` VALUES ('12', 'IPD');
INSERT INTO `hospital_department` VALUES ('13', '�ç����');
INSERT INTO `hospital_department` VALUES ('14', '�çö');
INSERT INTO `hospital_department` VALUES ('15', '��ѧ�Ǫ�ѳ��');
INSERT INTO `hospital_department` VALUES ('16', '����ʾ�Դ');
INSERT INTO `hospital_department` VALUES ('17', '��ع��');
INSERT INTO `hospital_department` VALUES ('18', '�������ا');
INSERT INTO `hospital_department` VALUES ('19', '��Сѹ');
INSERT INTO `hospital_department` VALUES ('20', '�����çҹ�����');
INSERT INTO `hospital_department` VALUES ('21', 'IC');
INSERT INTO `hospital_department` VALUES ('22', 'IT');
INSERT INTO `hospital_department` VALUES ('23', 'PCU � þ.');
