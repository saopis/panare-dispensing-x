/*
 Navicat Premium Data Transfer

 Source Server         : hos
 Source Server Type    : MySQL
 Source Server Version : 50562
 Source Host           : 192.168.10.11:3306
 Source Schema         : dispensing_test

 Target Server Type    : MySQL
 Target Server Version : 50562
 File Encoding         : 65001

 Date: 24/02/2021 15:12:17
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for kohrx_med_error_category
-- ----------------------------
DROP TABLE IF EXISTS `kohrx_med_error_category`;
CREATE TABLE `kohrx_med_error_category`  (
  `id` int(1) NOT NULL DEFAULT 0,
  `category` text CHARACTER SET tis620 COLLATE tis620_thai_ci,
  `detail` varchar(255) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = tis620 COLLATE = tis620_thai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of kohrx_med_error_category
-- ----------------------------
INSERT INTO `kohrx_med_error_category` VALUES (1, 'A', '');
INSERT INTO `kohrx_med_error_category` VALUES (2, 'B', '');
INSERT INTO `kohrx_med_error_category` VALUES (3, 'C', '');
INSERT INTO `kohrx_med_error_category` VALUES (4, 'D', '');
INSERT INTO `kohrx_med_error_category` VALUES (5, 'E', '');
INSERT INTO `kohrx_med_error_category` VALUES (6, 'F', '');
INSERT INTO `kohrx_med_error_category` VALUES (7, 'G', '');
INSERT INTO `kohrx_med_error_category` VALUES (8, 'H', '');
INSERT INTO `kohrx_med_error_category` VALUES (9, 'I', '');

-- ----------------------------
-- Table structure for kohrx_med_error_cause
-- ----------------------------
DROP TABLE IF EXISTS `kohrx_med_error_cause`;
CREATE TABLE `kohrx_med_error_cause`  (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `rid` int(5) DEFAULT NULL,
  `cause_id` text CHARACTER SET tis620 COLLATE tis620_thai_ci,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = tis620 COLLATE = tis620_thai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for kohrx_med_error_drug_drug_error
-- ----------------------------
DROP TABLE IF EXISTS `kohrx_med_error_drug_drug_error`;
CREATE TABLE `kohrx_med_error_drug_drug_error`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `drug1` char(20) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  `drug2` char(20) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  `errordate` char(20) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  `drug1_drug2` char(40) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = tis620 COLLATE = tis620_thai_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Table structure for kohrx_med_error_drug_option
-- ----------------------------
DROP TABLE IF EXISTS `kohrx_med_error_drug_option`;
CREATE TABLE `kohrx_med_error_drug_option`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `drug_option_name` char(100) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 7 CHARACTER SET = tis620 COLLATE = tis620_thai_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of kohrx_med_error_drug_option
-- ----------------------------
INSERT INTO `kohrx_med_error_drug_option` VALUES (1, 'ยาที่ถูก');
INSERT INTO `kohrx_med_error_drug_option` VALUES (2, 'ยาที่ผิด');
INSERT INTO `kohrx_med_error_drug_option` VALUES (3, 'ยาที่เกี่ยวข้อง');

-- ----------------------------
-- Table structure for kohrx_med_error_error_cause
-- ----------------------------
DROP TABLE IF EXISTS `kohrx_med_error_error_cause`;
CREATE TABLE `kohrx_med_error_error_cause`  (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) DEFAULT NULL,
  `name` varchar(255) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  `order_cause` int(2) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 112 CHARACTER SET = tis620 COLLATE = tis620_thai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of kohrx_med_error_error_cause
-- ----------------------------
INSERT INTO `kohrx_med_error_error_cause` VALUES (14, 1, 'สั่งจ่ายยาผิดคน', 2, 2);
INSERT INTO `kohrx_med_error_error_cause` VALUES (15, 1, 'สั่งใช้ยาผิดชนิดยา', 5, 2);
INSERT INTO `kohrx_med_error_error_cause` VALUES (16, 1, 'สั่งใช้ยาที่ผู้ป่วยมีประวัติแพ้', 5, 2);
INSERT INTO `kohrx_med_error_error_cause` VALUES (17, 1, 'สั่งใช้ยาผิดขนาดการรักษา', 5, 2);
INSERT INTO `kohrx_med_error_error_cause` VALUES (18, 1, 'คำสั่งใช้ยาไม่ครบถ้วน หรือไม่ระบุ', 5, 2);
INSERT INTO `kohrx_med_error_error_cause` VALUES (19, 1, 'สั่งจ่ายยาผิดความแรง/ผิดขนาดการรักษา', 5, 2);
INSERT INTO `kohrx_med_error_error_cause` VALUES (20, 4, 'คัดลอกหรือรับคำสั่งใช้ยาผิดคน', 2, 1);
INSERT INTO `kohrx_med_error_error_cause` VALUES (21, 4, 'คัดลอกหรือรับคำสั่งใช้ยาผิดชนิด', 3, 1);
INSERT INTO `kohrx_med_error_error_cause` VALUES (22, 4, 'คัดลอกหรือรับคำสั่งใช้ยาที่ผู้ป่วยมีประวัติแพ้', 5, 1);
INSERT INTO `kohrx_med_error_error_cause` VALUES (23, 4, 'คัดลอกหรือรับคำสั่งใช้ยาผิดขนาด/ความแรง', 5, 1);
INSERT INTO `kohrx_med_error_error_cause` VALUES (24, 4, 'คัดลอกหรือรับคำสั่งใช้ยาไม่ครบถ้วน  หรือไม่ระบุ  ดังต่อไปนี้', 2, 1);
INSERT INTO `kohrx_med_error_error_cause` VALUES (25, 3, 'ผิดจำนวน(wrong amount)(มากไป/น้อยไป)', 5, 1);
INSERT INTO `kohrx_med_error_error_cause` VALUES (26, 3, 'ผิดชนิดยา (wrong medication)', 2, 1);
INSERT INTO `kohrx_med_error_error_cause` VALUES (27, 3, 'ผิดจำนวนรายการยา (จ่ายเกิน/ลืมจ่าย)', 5, 1);
INSERT INTO `kohrx_med_error_error_cause` VALUES (28, 3, 'ผิดคน (wrong patient)', 5, 1);
INSERT INTO `kohrx_med_error_error_cause` VALUES (29, 3, 'ผิดวิธีทางการให้ยา (wrong route)', 5, 1);
INSERT INTO `kohrx_med_error_error_cause` VALUES (30, 3, 'ผิดรูปแบบยา (wrong dosage from)', 5, 1);
INSERT INTO `kohrx_med_error_error_cause` VALUES (31, 3, 'ผิดความแรง (wrong strength)', 5, 1);
INSERT INTO `kohrx_med_error_error_cause` VALUES (32, 3, 'ผิดขนาดการรักษา (wrong dosage regimen)', 5, 1);
INSERT INTO `kohrx_med_error_error_cause` VALUES (33, 5, 'ข้อมูลผู้ป่วยผิด (HN / ชื่อ - สกุล)', 5, 1);
INSERT INTO `kohrx_med_error_error_cause` VALUES (34, 5, 'ผิดคน (wrong patient)', 5, 1);
INSERT INTO `kohrx_med_error_error_cause` VALUES (35, 5, 'ผิดชนิดยา (wrong medication)', 2, 1);
INSERT INTO `kohrx_med_error_error_cause` VALUES (36, 5, 'ผิดขนาดการรักษา (wrong dosage regimen)', 5, 1);
INSERT INTO `kohrx_med_error_error_cause` VALUES (37, 5, 'ผิดรูปแบบยา (wrong dosage from)', 5, 1);
INSERT INTO `kohrx_med_error_error_cause` VALUES (38, 5, 'ผิดจำนวนรายการยา (เตรียมเกิน/ขาด)', 5, 1);
INSERT INTO `kohrx_med_error_error_cause` VALUES (39, 5, 'ผิดความแรง (wrong strength)', 5, 1);
INSERT INTO `kohrx_med_error_error_cause` VALUES (40, 6, 'ผิดคน (wrong patient)', 2, 1);
INSERT INTO `kohrx_med_error_error_cause` VALUES (41, 6, 'ผิดชนิดยา (wrong medication)', 5, 1);
INSERT INTO `kohrx_med_error_error_cause` VALUES (42, 6, 'ผิดขนาดการรักษา (wrong dosage regimen)', 2, 1);
INSERT INTO `kohrx_med_error_error_cause` VALUES (43, 6, 'ผิดรูปแบบยา (wrong dosage from)', 5, 1);
INSERT INTO `kohrx_med_error_error_cause` VALUES (44, 6, 'ผิดจำนวนรายการยา (ไม่ครบจำนวน/ขาด)', 5, 1);
INSERT INTO `kohrx_med_error_error_cause` VALUES (45, 6, 'ให้ยาไม่ทันเวลา / ผิดเวลา(wrong time)', 5, 1);
INSERT INTO `kohrx_med_error_error_cause` VALUES (46, 6, 'ผิดความแรง (wrong strength)', 5, 1);
INSERT INTO `kohrx_med_error_error_cause` VALUES (75, 1, 'สั่งใช้ที่ไม่มีข้อบ่งใช้หรือมีข้อห้ามใช้ กับผู้ป่วย', 1, 2);
INSERT INTO `kohrx_med_error_error_cause` VALUES (57, 2, 'ผิดจำนวน (Wrong amount)(มากไป/น้อยไป)', 2, 0);
INSERT INTO `kohrx_med_error_error_cause` VALUES (58, 2, 'ผิดชนิดยา (Wrong medication)', 5, 0);
INSERT INTO `kohrx_med_error_error_cause` VALUES (59, 2, 'ผิดจำนวนรายการยา', 5, 0);
INSERT INTO `kohrx_med_error_error_cause` VALUES (60, 2, 'ผิดคน (Wrong patient)', 5, 0);
INSERT INTO `kohrx_med_error_error_cause` VALUES (61, 2, 'ผิดรูปแบบยา (Wrong dosage form)', 5, 0);
INSERT INTO `kohrx_med_error_error_cause` VALUES (62, 2, 'ผิดความแรง (Wrong strength)', 5, 0);
INSERT INTO `kohrx_med_error_error_cause` VALUES (63, 2, 'ผิดขนาดการรักษา (Wrong dosage regimen)', 5, 0);
INSERT INTO `kohrx_med_error_error_cause` VALUES (64, 2, 'จัดยาซ้ำคน', 2, 0);
INSERT INTO `kohrx_med_error_error_cause` VALUES (65, 1, 'อื่นๆ โปรดระบุ', 23, 1);
INSERT INTO `kohrx_med_error_error_cause` VALUES (66, 4, 'อื่นๆ โปรดระบุ', 5, 2);
INSERT INTO `kohrx_med_error_error_cause` VALUES (67, 2, 'อื่นๆ โปรดระบุ', 5, 0);
INSERT INTO `kohrx_med_error_error_cause` VALUES (68, 3, 'อื่นๆ โปรดระบุ', 2, 0);
INSERT INTO `kohrx_med_error_error_cause` VALUES (69, 5, 'อื่นๆ โปรดระบุ', 2, 0);
INSERT INTO `kohrx_med_error_error_cause` VALUES (70, 6, 'อื่นๆ โปรดระบุ', 5, 0);
INSERT INTO `kohrx_med_error_error_cause` VALUES (72, 1, 'สั่งจ่ายผิดจำนวน(มากไป/น้อยไป)', 5, 2);
INSERT INTO `kohrx_med_error_error_cause` VALUES (73, 1, 'สั่งจ่ายยาผิดรูปแบบการใช้', 5, 2);
INSERT INTO `kohrx_med_error_error_cause` VALUES (74, 1, 'สั่งใช้ยาซ้ำซ้อน', 5, 2);
INSERT INTO `kohrx_med_error_error_cause` VALUES (89, 1, 'สั่งใช้ยาที่มีปฏิกิริยาระหว่างกัน', 5, 2);
INSERT INTO `kohrx_med_error_error_cause` VALUES (90, 1, 'สั่งจ่ายยาผิดรูปแบบชนิดยา', 5, 2);
INSERT INTO `kohrx_med_error_error_cause` VALUES (93, 4, 'คัดลอกหรือรับคำสั่งผิดรูปแบบการใช้', 4, 0);
INSERT INTO `kohrx_med_error_error_cause` VALUES (94, 4, 'คัดลอกหรือรับคำสั่งผิดรูปแบบชนิดยา', 6, 0);
INSERT INTO `kohrx_med_error_error_cause` VALUES (95, 2, 'จัดยาที่หมดอายุ / เสื่อมสภาพ', 6, 0);
INSERT INTO `kohrx_med_error_error_cause` VALUES (97, 2, 'พิมพ์ฉลากไม่ถูกต้อง(ผิดชนิด/ผิดตัว/ผิดขนาด/วิธีใช้/ผิดจำนวน)', 7, 0);
INSERT INTO `kohrx_med_error_error_cause` VALUES (98, 2, 'ติดฉลากผิด / สลับตัวยา', 8, 0);
INSERT INTO `kohrx_med_error_error_cause` VALUES (99, 3, 'จ่ายยาหมดอายุ / เสื่อมสภาพ', 6, 0);
INSERT INTO `kohrx_med_error_error_cause` VALUES (100, 3, 'จ่ายยาที่ติดฉลากยาดลาดเคลื่อน', 7, 0);
INSERT INTO `kohrx_med_error_error_cause` VALUES (101, 6, 'ผิดวิถีทาง/ตำแหน่ง', 6, 0);
INSERT INTO `kohrx_med_error_error_cause` VALUES (102, 6, 'ผิดอัตราการให้(เร็ว/ช้า)', 7, 0);
INSERT INTO `kohrx_med_error_error_cause` VALUES (103, 6, 'ผิดเทคนิคการให้', 8, 0);
INSERT INTO `kohrx_med_error_error_cause` VALUES (104, 5, 'ผิดเทคนิคการให้', 6, 0);
INSERT INTO `kohrx_med_error_error_cause` VALUES (105, 1, 'สั่งผิดวิธีการให้', 24, 2);
INSERT INTO `kohrx_med_error_error_cause` VALUES (106, 1, 'สั่งผิดจำนวนรายการ/ลืมสั่ง', 25, 2);
INSERT INTO `kohrx_med_error_error_cause` VALUES (107, 4, 'คัดลอกหรือรับคำสั่งยาผิดจำนวน', 7, 0);
INSERT INTO `kohrx_med_error_error_cause` VALUES (108, 4, 'คัดลอกหรือรับคำสั่งยาผิดจำนวนรายการ', 8, 0);
INSERT INTO `kohrx_med_error_error_cause` VALUES (109, 4, 'คัดลอกหรือรับคำสั่งผิดวิธีการให้', 9, 0);
INSERT INTO `kohrx_med_error_error_cause` VALUES (110, 2, 'ผิดตะกร้า/ภาชนะ', 9, 0);
INSERT INTO `kohrx_med_error_error_cause` VALUES (111, 2, 'ผิดชนิดซอง/ภาชนะบรรจุ', 10, 0);

-- ----------------------------
-- Table structure for kohrx_med_error_error_sub_cause
-- ----------------------------
DROP TABLE IF EXISTS `kohrx_med_error_error_sub_cause`;
CREATE TABLE `kohrx_med_error_error_sub_cause`  (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `cause_id` int(3) DEFAULT NULL,
  `sub_name` varchar(255) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  `input` int(1) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 32 CHARACTER SET = tis620 COLLATE = tis620_thai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of kohrx_med_error_error_sub_cause
-- ----------------------------
INSERT INTO `kohrx_med_error_error_sub_cause` VALUES (9, 18, 'ชื่อยา', 0, 1);
INSERT INTO `kohrx_med_error_error_sub_cause` VALUES (10, 18, 'รูปแบบยา', 0, 1);
INSERT INTO `kohrx_med_error_error_sub_cause` VALUES (11, 18, 'ความแรง', 0, 1);
INSERT INTO `kohrx_med_error_error_sub_cause` VALUES (12, 18, 'วิธีการใช้ยา', 0, 1);
INSERT INTO `kohrx_med_error_error_sub_cause` VALUES (13, 18, 'จำนวน', 0, 1);
INSERT INTO `kohrx_med_error_error_sub_cause` VALUES (14, 24, 'ชื่อยา', 0, 1);
INSERT INTO `kohrx_med_error_error_sub_cause` VALUES (15, 24, 'รูปแบบยา', 0, 1);
INSERT INTO `kohrx_med_error_error_sub_cause` VALUES (16, 24, 'ความแรง', 0, 1);
INSERT INTO `kohrx_med_error_error_sub_cause` VALUES (17, 24, 'วิธีการให้ยา', 0, 1);
INSERT INTO `kohrx_med_error_error_sub_cause` VALUES (18, 24, 'จำนวน', 0, 1);
INSERT INTO `kohrx_med_error_error_sub_cause` VALUES (27, 16, 'กลุ่มเดียวกัน', 0, 1);
INSERT INTO `kohrx_med_error_error_sub_cause` VALUES (28, 16, 'generic เดียวกัน', 0, 1);
INSERT INTO `kohrx_med_error_error_sub_cause` VALUES (29, 22, 'กลุ่มเดียวกัน', 0, 1);
INSERT INTO `kohrx_med_error_error_sub_cause` VALUES (30, 22, 'gerneric เดียวกัน', 0, 1);
INSERT INTO `kohrx_med_error_error_sub_cause` VALUES (31, 40, '', 0, 0);

-- ----------------------------
-- Table structure for kohrx_med_error_error_type
-- ----------------------------
DROP TABLE IF EXISTS `kohrx_med_error_error_type`;
CREATE TABLE `kohrx_med_error_error_type`  (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `type_thai` char(100) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  `type_eng` char(100) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  `order_type` int(1) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 18 CHARACTER SET = tis620 COLLATE = tis620_thai_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of kohrx_med_error_error_type
-- ----------------------------
INSERT INTO `kohrx_med_error_error_type` VALUES (1, 'ความคลาดเคลื่อนจากการสั่งจ่ายยา', 'PRESCRIBING ERROR', 1, 1);
INSERT INTO `kohrx_med_error_error_type` VALUES (2, 'ความคลาดเคลื่อนจากการจัดยา', 'PREDISPENSING ERROR', 2, 1);
INSERT INTO `kohrx_med_error_error_type` VALUES (3, 'ความคลาดเคลื่อนจากการจ่ายยา', 'DISPENSING ERROR', 4, 1);
INSERT INTO `kohrx_med_error_error_type` VALUES (4, 'ความคลาดเคลื่อนจากการคัดลอกคำสั่งแพทย์', 'TRANSCRIBTION ERROR', 3, 1);
INSERT INTO `kohrx_med_error_error_type` VALUES (5, 'ความคลาดเคลื่อนก่อนบริหารยา', 'PREADMINISTRATION ERROR', 4, 1);
INSERT INTO `kohrx_med_error_error_type` VALUES (6, 'ความคลาดเคลื่อนจากการบริหารยา', 'ADMINISTRATION ERROR', 6, 1);
INSERT INTO `kohrx_med_error_error_type` VALUES (7, 'ความคลาดเคลื่อนจากสาเหตุอื่นๆ', 'OTHER ERROR', 7, 1);

-- ----------------------------
-- Table structure for kohrx_med_error_indiv
-- ----------------------------
DROP TABLE IF EXISTS `kohrx_med_error_indiv`;
CREATE TABLE `kohrx_med_error_indiv`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_error` char(10) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  `date_error2` char(2) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  `name1` int(11) DEFAULT NULL,
  `name2` int(11) DEFAULT NULL,
  `name3` int(11) DEFAULT NULL,
  `name4` int(11) DEFAULT NULL,
  `name5` int(11) DEFAULT NULL,
  `name6` int(11) DEFAULT NULL,
  `name7` int(11) DEFAULT NULL,
  `name8` int(11) DEFAULT NULL,
  `name9` int(11) DEFAULT NULL,
  `name10` int(11) DEFAULT NULL,
  `name11` int(11) DEFAULT NULL,
  `name12` int(11) DEFAULT NULL,
  `name13` int(11) DEFAULT NULL,
  `name14` int(11) DEFAULT NULL,
  `name15` int(11) DEFAULT NULL,
  `name16` int(11) DEFAULT NULL,
  `name17` int(11) DEFAULT NULL,
  `name18` int(11) DEFAULT NULL,
  `name19` int(11) DEFAULT NULL,
  `name20` int(11) DEFAULT NULL,
  `name21` int(11) DEFAULT NULL,
  `name22` int(11) DEFAULT NULL,
  `name23` int(11) DEFAULT NULL,
  `name24` int(11) DEFAULT NULL,
  `name25` int(11) DEFAULT NULL,
  `name26` int(11) DEFAULT NULL,
  `name27` int(11) DEFAULT NULL,
  `name28` int(11) DEFAULT NULL,
  `name29` int(11) DEFAULT NULL,
  `name30` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = tis620 COLLATE = tis620_thai_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Table structure for kohrx_med_error_indiv2
-- ----------------------------
DROP TABLE IF EXISTS `kohrx_med_error_indiv2`;
CREATE TABLE `kohrx_med_error_indiv2`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_error` date DEFAULT NULL,
  `person` int(11) DEFAULT NULL,
  `doctor_code` char(10) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  `error_type` int(11) DEFAULT NULL,
  `drug1` int(11) DEFAULT NULL,
  `drug2` int(11) DEFAULT NULL,
  `pttype` char(5) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  `lasagroup` char(20) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  `time1` time DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = tis620 COLLATE = tis620_thai_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Table structure for kohrx_med_error_input_type
-- ----------------------------
DROP TABLE IF EXISTS `kohrx_med_error_input_type`;
CREATE TABLE `kohrx_med_error_input_type`  (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `input_name` char(255) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 4 CHARACTER SET = tis620 COLLATE = tis620_thai_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of kohrx_med_error_input_type
-- ----------------------------
INSERT INTO `kohrx_med_error_input_type` VALUES (1, 'checkbox');
INSERT INTO `kohrx_med_error_input_type` VALUES (2, 'radio');
INSERT INTO `kohrx_med_error_input_type` VALUES (3, 'text');

-- ----------------------------
-- Table structure for kohrx_med_error_lasa
-- ----------------------------
DROP TABLE IF EXISTS `kohrx_med_error_lasa`;
CREATE TABLE `kohrx_med_error_lasa`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_error` char(255) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  `doctor` char(255) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  `drug1` int(11) DEFAULT NULL,
  `drug2` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = tis620 COLLATE = tis620_thai_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Table structure for kohrx_med_error_other_note
-- ----------------------------
DROP TABLE IF EXISTS `kohrx_med_error_other_note`;
CREATE TABLE `kohrx_med_error_other_note`  (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `med_id` int(11) DEFAULT NULL,
  `note` varchar(255) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = tis620 COLLATE = tis620_thai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for kohrx_med_error_person
-- ----------------------------
DROP TABLE IF EXISTS `kohrx_med_error_person`;
CREATE TABLE `kohrx_med_error_person`  (
  `id` int(11) NOT NULL DEFAULT 0,
  `person` varchar(255) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  `person_order` int(11) DEFAULT NULL,
  `person_status` char(1) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  `doctor_code` char(5) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = tis620 COLLATE = tis620_thai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for kohrx_med_error_predis_type
-- ----------------------------
DROP TABLE IF EXISTS `kohrx_med_error_predis_type`;
CREATE TABLE `kohrx_med_error_predis_type`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `predis_type` varchar(255) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 7 CHARACTER SET = tis620 COLLATE = tis620_thai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of kohrx_med_error_predis_type
-- ----------------------------
INSERT INTO `kohrx_med_error_predis_type` VALUES (1, 'ผิดตัวยา');
INSERT INTO `kohrx_med_error_predis_type` VALUES (2, 'ผิดความแรง');
INSERT INTO `kohrx_med_error_predis_type` VALUES (3, 'ผิดตะกร้า');
INSERT INTO `kohrx_med_error_predis_type` VALUES (4, 'ผิดจำนวน');
INSERT INTO `kohrx_med_error_predis_type` VALUES (5, 'ไม่ครบรายการ');
INSERT INTO `kohrx_med_error_predis_type` VALUES (6, 'ผิดชนิดซอง');

-- ----------------------------
-- Table structure for kohrx_med_error_report
-- ----------------------------
DROP TABLE IF EXISTS `kohrx_med_error_report`;
CREATE TABLE `kohrx_med_error_report`  (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `reporter` char(10) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `detail` varchar(255) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  `hn` text CHARACTER SET tis620 COLLATE tis620_thai_ci,
  `patient_name` varchar(255) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  `age` int(2) DEFAULT NULL,
  `dep_report` int(2) DEFAULT NULL,
  `dep_error` int(2) DEFAULT NULL,
  `category` text CHARACTER SET tis620 COLLATE tis620_thai_ci,
  `suggest` varchar(255) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  `drug1` char(24) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  `drug2` char(24) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  `reciew` text CHARACTER SET tis620 COLLATE tis620_thai_ci,
  `drugtype` text CHARACTER SET tis620 COLLATE tis620_thai_ci,
  `dispensing_error_type` text CHARACTER SET tis620 COLLATE tis620_thai_ci,
  `pharmacist` char(100) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  `ptype` char(10) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  `error_person` char(10) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  `error_type` char(3) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  `error_cause` char(3) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  `error_subtype` char(3) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  `error_other` varchar(255) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = tis620 COLLATE = tis620_thai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for kohrx_med_error_report_category
-- ----------------------------
DROP TABLE IF EXISTS `kohrx_med_error_report_category`;
CREATE TABLE `kohrx_med_error_report_category`  (
  `cat` char(1) CHARACTER SET tis620 COLLATE tis620_thai_ci NOT NULL DEFAULT '',
  `count_c` int(11) DEFAULT NULL
) ENGINE = MyISAM CHARACTER SET = tis620 COLLATE = tis620_thai_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Table structure for kohrx_med_error_report_dep_error
-- ----------------------------
DROP TABLE IF EXISTS `kohrx_med_error_report_dep_error`;
CREATE TABLE `kohrx_med_error_report_dep_error`  (
  `dep_e` varchar(255) CHARACTER SET tis620 COLLATE tis620_thai_ci NOT NULL DEFAULT '',
  `count_e` int(11) DEFAULT NULL,
  PRIMARY KEY (`dep_e`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = tis620 COLLATE = tis620_thai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for kohrx_med_error_report_dep_report
-- ----------------------------
DROP TABLE IF EXISTS `kohrx_med_error_report_dep_report`;
CREATE TABLE `kohrx_med_error_report_dep_report`  (
  `dep_r` varchar(255) CHARACTER SET tis620 COLLATE tis620_thai_ci NOT NULL DEFAULT '',
  `count_r` int(11) DEFAULT NULL,
  PRIMARY KEY (`dep_r`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = tis620 COLLATE = tis620_thai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for kohrx_med_error_report_doctor_report
-- ----------------------------
DROP TABLE IF EXISTS `kohrx_med_error_report_doctor_report`;
CREATE TABLE `kohrx_med_error_report_doctor_report`  (
  `doctor` varchar(255) CHARACTER SET tis620 COLLATE tis620_thai_ci NOT NULL DEFAULT '',
  `count_d` int(11) DEFAULT NULL,
  PRIMARY KEY (`doctor`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = tis620 COLLATE = tis620_thai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for kohrx_med_error_report_drug
-- ----------------------------
DROP TABLE IF EXISTS `kohrx_med_error_report_drug`;
CREATE TABLE `kohrx_med_error_report_drug`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) DEFAULT NULL,
  `icode` char(20) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  `did` char(30) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  `drug_option` int(1) DEFAULT NULL,
  `drug_edit` char(1) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  `stamp` char(50) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  `d_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = tis620 COLLATE = tis620_thai_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Table structure for kohrx_med_error_subtype
-- ----------------------------
DROP TABLE IF EXISTS `kohrx_med_error_subtype`;
CREATE TABLE `kohrx_med_error_subtype`  (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `med_id` int(5) DEFAULT NULL,
  `sub_id` int(3) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = tis620 COLLATE = tis620_thai_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Table structure for kohrx_med_error_sum_indiv_dispen
-- ----------------------------
DROP TABLE IF EXISTS `kohrx_med_error_sum_indiv_dispen`;
CREATE TABLE `kohrx_med_error_sum_indiv_dispen`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `process_date` char(6) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  `dispen1` int(11) DEFAULT NULL,
  `dispen2` int(11) DEFAULT NULL,
  `dispen3` int(11) DEFAULT NULL,
  `dispen4` int(11) DEFAULT NULL,
  `dispen5` int(11) DEFAULT NULL,
  `dispen6` int(11) DEFAULT NULL,
  `dispen7` int(11) DEFAULT NULL,
  `dispen8` int(11) DEFAULT NULL,
  `dispen9` int(11) DEFAULT NULL,
  `dispen10` int(11) DEFAULT NULL,
  `dispen11` int(11) DEFAULT NULL,
  `dispen12` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = tis620 COLLATE = tis620_thai_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Table structure for kohrx_med_error_sum_indiv_error
-- ----------------------------
DROP TABLE IF EXISTS `kohrx_med_error_sum_indiv_error`;
CREATE TABLE `kohrx_med_error_sum_indiv_error`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `process_date` char(6) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  `error1` int(11) DEFAULT NULL,
  `error2` int(11) DEFAULT NULL,
  `error3` int(11) DEFAULT NULL,
  `error4` int(11) DEFAULT NULL,
  `error5` int(11) DEFAULT NULL,
  `error6` int(11) DEFAULT NULL,
  `error7` int(11) DEFAULT NULL,
  `error8` int(11) DEFAULT NULL,
  `error9` int(11) DEFAULT NULL,
  `error10` int(11) DEFAULT NULL,
  `error11` int(11) DEFAULT NULL,
  `error12` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = tis620 COLLATE = tis620_thai_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of kohrx_med_error_sum_indiv_error
-- ----------------------------
INSERT INTO `kohrx_med_error_sum_indiv_error` VALUES (1, '255001', 2, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- ----------------------------
-- Table structure for kohrx_med_error_sum_indiv_process
-- ----------------------------
DROP TABLE IF EXISTS `kohrx_med_error_sum_indiv_process`;
CREATE TABLE `kohrx_med_error_sum_indiv_process`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `process_date` char(6) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  `among1` int(11) DEFAULT NULL,
  `among2` int(11) DEFAULT NULL,
  `among3` int(11) DEFAULT NULL,
  `among4` int(11) DEFAULT NULL,
  `among5` int(11) DEFAULT NULL,
  `among6` int(11) DEFAULT NULL,
  `among7` int(11) DEFAULT NULL,
  `among8` int(11) DEFAULT NULL,
  `among9` int(11) DEFAULT NULL,
  `among10` int(11) DEFAULT NULL,
  `among11` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 5 CHARACTER SET = tis620 COLLATE = tis620_thai_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of kohrx_med_error_sum_indiv_process
-- ----------------------------
INSERT INTO `kohrx_med_error_sum_indiv_process` VALUES (4, '255001', 400, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- ----------------------------
-- Table structure for kohrx_med_error_sum_indiv_work
-- ----------------------------
DROP TABLE IF EXISTS `kohrx_med_error_sum_indiv_work`;
CREATE TABLE `kohrx_med_error_sum_indiv_work`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `process_date` char(6) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  `work1` int(11) DEFAULT NULL,
  `work2` int(11) DEFAULT NULL,
  `work3` int(11) DEFAULT NULL,
  `work4` int(11) DEFAULT NULL,
  `work5` int(11) DEFAULT NULL,
  `work6` int(11) DEFAULT NULL,
  `work7` int(11) DEFAULT NULL,
  `work8` int(11) DEFAULT NULL,
  `work9` int(11) DEFAULT NULL,
  `work10` int(11) DEFAULT NULL,
  `work11` int(11) DEFAULT NULL,
  `work12` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = tis620 COLLATE = tis620_thai_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Table structure for kohrx_med_error_temp
-- ----------------------------
DROP TABLE IF EXISTS `kohrx_med_error_temp`;
CREATE TABLE `kohrx_med_error_temp`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name1` varchar(255) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  `name2` varchar(255) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  `name3` varchar(255) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  `name4` varchar(255) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  `name5` varchar(255) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  `name6` varchar(255) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  `name7` varchar(255) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  `name8` varchar(255) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  `name9` varchar(255) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  `name10` varchar(255) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = tis620 COLLATE = tis620_thai_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
