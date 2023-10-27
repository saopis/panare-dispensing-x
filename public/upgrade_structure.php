<?php require_once('Connections/hos.php'); 
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}
//ตรวจสอบตาราง kohrx_had_cause
mysql_select_db($database_hos, $hos);
$query_check = "SELECT COUNT(*) as ctable
FROM information_schema.tables 
WHERE table_schema = '".$database_kohrx."' 
AND table_name = 'kohrx_had_cause'";
$check = mysql_query($query_check, $hos) or die(mysql_error());
$row_check = mysql_fetch_assoc($check);
$totalRows_check = mysql_num_rows($check);

if($row_check['ctable']==0){
$table_build = "CREATE TABLE ".$database_kohrx.".`kohrx_had_cause` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `icode` char(10) DEFAULT NULL,
  `use_cause` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=tis620
";
$build = mysql_query($table_build, $hos) or die(mysql_error());	

}
mysql_free_result($check);
// สร้างตาราง kohrx_had_cause เสร็จ //////////////////////////

// ## เพิ่มฟิล์ดในตาราง kohrx_had_record
//======= 
$feild_array=array('use_cause_id','cause_note','remark');
$table='kohrx_had_record';

for($i=0;$i<count($feild_array);$i++){

	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE 
		TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = '".$table."' 
	AND COLUMN_NAME = '".$feild_array[$i]."'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	//use_cause_id
	if($row_check['ccolumn']==0&&$feild_array[$i]=="use_cause_id"){

			mysql_select_db($database_hos, $hos);
			$query_alter = "ALTER TABLE `".$table."`
ADD COLUMN `use_cause_id` int(11) DEFAULT NULL AFTER `icode`";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());

		}	
	//cause_note
	if($row_check['ccolumn']==0&&$feild_array[$i]=="cause_note"){

			mysql_select_db($database_hos, $hos);
			$query_alter = "ALTER TABLE `".$table."`
ADD COLUMN `cause_note` varchar(255) DEFAULT NULL AFTER `use_cause_id`";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
		}	
	
	//remark
	if($row_check['ccolumn']==0&&$feild_array[$i]=="remark"){

			mysql_select_db($database_hos, $hos);
			$query_alter = "ALTER TABLE `".$table."`
ADD COLUMN `remark` varchar(255) DEFAULT NULL AFTER `cause_note`";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());

		}	
		}
////////////////////////////////////////////

// ## เพิ่มฟิล์ดใน kohrx_queue_caller_pname
//======= 
$feild_array=array('year_old','parent_call');
$table='kohrx_queue_caller_pname';

for($i=0;$i<count($feild_array);$i++){

	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE 
		TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = '".$table."' 
	AND COLUMN_NAME = '".$feild_array[$i]."'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	//year_old
	if($row_check['ccolumn']==0&&$feild_array[$i]=="year_old"){

			mysql_select_db($database_hos, $hos);
			$query_alter = "ALTER TABLE `".$table."`
ADD COLUMN `year_old` int(3) DEFAULT NULL AFTER `monk`";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());

		}	
	//parent_call
	if($row_check['ccolumn']==0&&$feild_array[$i]=="parent_call"){

			mysql_select_db($database_hos, $hos);
			$query_alter = "ALTER TABLE `".$table."`
ADD COLUMN `parent_call` char(1) DEFAULT NULL AFTER `year_old`";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
		}	
	
		}

mysql_free_result($check);
		
//ตรวจสอบตาราง kohrx_login_check
mysql_select_db($database_hos, $hos);
$query_check = "SELECT COUNT(*) as ctable
FROM information_schema.tables 
WHERE table_schema = '".$database_kohrx."' 
AND table_name = 'kohrx_login_check'";
$check = mysql_query($query_check, $hos) or die(mysql_error());
$row_check = mysql_fetch_assoc($check);
$totalRows_check = mysql_num_rows($check);

if($row_check['ctable']==0){
$table_build = "CREATE TABLE ".$database_kohrx.".`kohrx_login_check` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login_name` char(100) DEFAULT NULL,
  `ipaddress` char(20) DEFAULT NULL,
  `last_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=tis620
";
$build = mysql_query($table_build, $hos) or die(mysql_error());	

}
mysql_free_result($check);

//ตรวจสอบตาราง kohrx_login_log
mysql_select_db($database_hos, $hos);
$query_check = "SELECT COUNT(*) as ctable
FROM information_schema.tables 
WHERE table_schema = '".$database_kohrx."' 
AND table_name = 'kohrx_login_log'";
$check = mysql_query($query_check, $hos) or die(mysql_error());
$row_check = mysql_fetch_assoc($check);
$totalRows_check = mysql_num_rows($check);

if($row_check['ctable']==0){
$table_build = "CREATE TABLE ".$database_kohrx.".`kohrx_login_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login_name` char(100) DEFAULT NULL,
  `ipaddress` char(20) DEFAULT NULL,
  `time_check` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=tis620
";
$build = mysql_query($table_build, $hos) or die(mysql_error());	

}
mysql_free_result($check);

//ตรวจสอบตาราง kohrx_login_log
mysql_select_db($database_hos, $hos);
$query_check = "SELECT COUNT(*) as ctable
FROM information_schema.tables 
WHERE table_schema = '".$database_kohrx."' 
AND table_name = 'kohrx_user_setting'";
$check = mysql_query($query_check, $hos) or die(mysql_error());
$row_check = mysql_fetch_assoc($check);
$totalRows_check = mysql_num_rows($check);

if($row_check['ctable']==0){
$table_build = "CREATE TABLE ".$database_kohrx.".`kohrx_user_setting` (
  `doctorcode` char(5) NOT NULL,
  `right_opd` char(1) DEFAULT NULL,
  `right_ipd` char(1) DEFAULT NULL,
  `right_admin` char(1) DEFAULT NULL,
  PRIMARY KEY (`doctorcode`)
) ENGINE=InnoDB DEFAULT CHARSET=tis620
";
$build = mysql_query($table_build, $hos) or die(mysql_error());	
}
mysql_free_result($check);

// ## เพิ่มฟิล์ดใน kohrx_elder_risk
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE 
		TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_drug_elder_risk' 
	AND COLUMN_NAME = 'file_link'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
		if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_drug_elder_risk`
ADD COLUMN `file_link` char(20) DEFAULT NULL AFTER `age_range2`";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
		}	
mysql_free_result($check);

// ## เพิ่มฟิล์ดใน kohrx_elder_risk_record
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE 
		TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_drug_elder_risk_record' 
	AND COLUMN_NAME = 'consult'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
		if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_drug_elder_risk_record`
ADD COLUMN `consult` int(1) DEFAULT NULL AFTER `doctorcode`";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
		}	
mysql_free_result($check);

// ## เพิ่มฟิล์ดใน kohrx_elder_risk_record
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE 
		TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_drug_elder_risk_record' 
	AND COLUMN_NAME = 'icode2'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
		if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_drug_elder_risk_record`
ADD COLUMN `icode2` char(10) DEFAULT NULL AFTER `consult`";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
		}	

mysql_free_result($check);

// ## เพิ่มฟิล์ดใน kohrx_queue_caller_channel
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE 
		TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_queue_caller_channel' 
	AND COLUMN_NAME = 'doctor_type'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_queue_caller_channel`
ADD COLUMN `doctor_type` int(1) DEFAULT NULL AFTER `call_server`";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
			}	
mysql_free_result($check);

// ## เพิ่มฟิล์ดใน med_error_indiv2
//======= 
/*
	mysql_select_db($database_mederror, $mederror);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE 
		TABLE_SCHEMA = '".$database_mederror."' 
	AND TABLE_NAME = 'med_error_indiv2' 
	AND COLUMN_NAME = 'time1'";
	$check = mysql_query($query_check, $mederror) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_mederror, $mederror);
			$query_alter = "ALTER TABLE `med_error_indiv2`
ADD COLUMN `time1` time DEFAULT NULL AFTER `lasagroup`";
			$alter = mysql_query($query_alter, $mederror) or die(mysql_error());
			}	

mysql_free_result($check);
*/
/////////////////////////////////

// ## เพิ่มฟิล์ดใน drugusage
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE 
		TABLE_SCHEMA = '".$database_hos."' 
	AND TABLE_NAME = 'drugusage' 
	AND COLUMN_NAME = 'ccperdose'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "ALTER TABLE `drugusage`
ADD COLUMN `ccperdose` double(5,2) DEFAULT NULL AFTER `interval6`";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
			}	
mysql_free_result($check);

/////////////////////////////////

// ## เพิ่มฟิล์ดใน rx_operator
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE 
		TABLE_SCHEMA = '".$database_hos."' 
	AND TABLE_NAME = 'rx_operator' 
	AND COLUMN_NAME = 'print_staff'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "ALTER TABLE `rx_operator`
ADD COLUMN `print_staff` char(10) DEFAULT NULL AFTER `rx_print`";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
			}	
mysql_free_result($check);

/////////////////////////////////

// ## สร้างตาราง income
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_income' ";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "CREATE TABLE ".$database_kohrx.".`kohrx_income` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vn` char(20) DEFAULT NULL,
  `payment` double(10,2) DEFAULT NULL,
  `accountant` char(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=tis620";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
			}	
mysql_free_result($check);

/////////////////////////////////
// ## เพิ่มฟิล์ดใน mederror
//======= 
/*	mysql_select_db($database_mederror, $mederror);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE 
		TABLE_SCHEMA = '".$database_mederror."' 
	AND TABLE_NAME = 'med_error_indiv2' 
	AND COLUMN_NAME = 'doctor_code'";
	$check = mysql_query($query_check, $mederror) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_mederror, $mederror);
			$query_alter = "ALTER TABLE `med_error_indiv2`
ADD COLUMN `doctor_code` char(10) DEFAULT NULL AFTER `person`";
			$alter = mysql_query($query_alter, $mederror) or die(mysql_error());
			}	
mysql_free_result($check);
*/
/////////////////////////////////
// ## เพิ่มฟิล์ดใน kohrx_user_setting
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE 
		TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_user_setting' 
	AND COLUMN_NAME = 'right_finance'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_user_setting`
ADD COLUMN `right_finance` char(1) DEFAULT NULL AFTER `right_ipd`";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
			}	
mysql_free_result($check);

/////////////////////////////////
// ## เพิ่มฟิล์ดใน kohrx_due_record
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE 
		TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_due_record' 
	AND COLUMN_NAME = 'an'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_due_record`
ADD COLUMN `an` char(20) DEFAULT NULL AFTER `vn`";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
			}	
mysql_free_result($check);

/////////////////////////////////
// ## เพิ่มฟิล์ดใน kohrx_had_record
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE 
		TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_had_record' 
	AND COLUMN_NAME = 'an'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_had_record`
ADD COLUMN `an` char(20) DEFAULT NULL AFTER `vn`";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
			}	
mysql_free_result($check);

/////////////////////////////////

// ## สร้างตาราง kohrx_drug_adherance
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_drug_adherance' ";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "CREATE TABLE ".$database_kohrx.".`kohrx_drug_adherance` (
  `icode` char(10) NOT NULL,
  PRIMARY KEY (`icode`)
) ENGINE=InnoDB DEFAULT CHARSET=tis620";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
			}	
mysql_free_result($check);

/////////////////////////////////

// ## ค้นหาว่า dispensing_setting มี important_data หรือเปล่า
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "select * from ".$database_kohrx.".kohrx_dispensing_setting where name = 'important_data' ";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($totalRows_check==0){
		mysql_select_db($database_hos, $hos);
		$query_alter = "insert into ".$database_kohrx.".kohrx_dispensing_setting (id,name,value) value ('29','important_data','Y')";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
			}
mysql_free_result($check);
	
/////////////////////////////////

// ## ค้นหาว่า dispensing_setting มี med_reconciliation_link หรือเปล่า  //======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "select * from ".$database_kohrx.".kohrx_dispensing_setting where name = 'med_reconciliation_link' ";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($totalRows_check==0){
		mysql_select_db($database_hos, $hos);
		$query_alter = "insert into ".$database_kohrx.".kohrx_dispensing_setting (id,name) value ('30','med_reconciliation_link')";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
			}	
mysql_free_result($check);

/////////////////////////////////

// ## ค้นหาว่า dispensing_setting มี med_reconciliation_link_blank //======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "select * from ".$database_kohrx.".kohrx_dispensing_setting where name = 'med_reconciliation_link_blank' ";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($totalRows_check==0){
		mysql_select_db($database_hos, $hos);
		$query_alter = "insert into ".$database_kohrx.".kohrx_dispensing_setting (id,name) value ('31','med_reconciliation_link_blank')";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
			}	
mysql_free_result($check);

/////////////////////////////////

// ## สร้างตาราง kohrx_medreconciliation_temp
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_medreconciliation_temp' ";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "CREATE TABLE ".$database_kohrx.".`kohrx_medreconciliation_temp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vn` varchar(20) DEFAULT NULL,
  `hn` varchar(10) DEFAULT NULL,
  `an` varchar(10) DEFAULT NULL,
  `pt_name` varchar(255) DEFAULT NULL,
  `age_y` int(11) DEFAULT NULL,
  `age_m` int(11) DEFAULT NULL,
  `weight` double(5,2) DEFAULT NULL,
  `regist_date` varchar(100) DEFAULT NULL,
  `doctor` varchar(255) DEFAULT NULL,
  `app_date` varchar(100) DEFAULT NULL,
  `alergy` varchar(255) DEFAULT NULL,
  `drug_name` varchar(255) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `drugusage` varchar(255) DEFAULT NULL,
  `rxdate` varchar(100) DEFAULT NULL,
  `hospital_src` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=tis620;";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
			}	
mysql_free_result($check);

/////////////////////////////////

// ## สร้างตาราง kohrx_pharmacist_note
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_pharmacist_note' ";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "CREATE TABLE ".$database_kohrx.".`kohrx_pharmacist_note` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_type` varchar(3) DEFAULT NULL,
  `hn` varchar(20) DEFAULT NULL,
  `an` varchar(20) DEFAULT NULL,
  `note_date` date DEFAULT NULL,
  `note_time` time DEFAULT NULL,
  `pharmacist_note` varchar(255) DEFAULT NULL,
  `pharmacist` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=tis620;

";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
			}	
mysql_free_result($check);

/////////////////////////////////

/////////////////////////////////

// ## สร้างตาราง kohrx_med_reconcile_src
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_med_reconcile_src' ";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "CREATE TABLE ".$database_kohrx.".`kohrx_med_reconcile_src` (
  `an` varchar(20) NOT NULL,
  `hosp_src` varchar(255) DEFAULT NULL,
  `doctorcode` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`an`)
) ENGINE=InnoDB DEFAULT CHARSET=tis620;
";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
			}
mysql_free_result($check);
	
/////////////////////////////////

/////////////////////////////////
// ## เพิ่มฟิล์ด units ใน kohrx_drug_insulin
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE 
		TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_drug_insulin' 
	AND COLUMN_NAME = 'units'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_drug_insulin`
ADD COLUMN `units` int(10) DEFAULT NULL AFTER `icode`";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
			}	
mysql_free_result($check);

/////////////////////////////////

/////////////////////////////////

// ## สร้างตาราง kohrx_emergency_drug
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_emergency_drug' ";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "CREATE TABLE ".$database_kohrx.".`kohrx_emergency_drug` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vn` varchar(20) DEFAULT NULL,
  `icode` varchar(7) DEFAULT NULL,
  `rxtime` time DEFAULT NULL,
  `reciever` int(11) DEFAULT NULL,
  `doctor` varchar(5) DEFAULT NULL,
  `dispen_date` date DEFAULT NULL,
  `dispen_time` time DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1125 DEFAULT CHARSET=tis620;
";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
			}	
mysql_free_result($check);

/////////////////////////////////
/////////////////////////////////
// ## เพิ่มฟิล์ด zero_check ใน kohrx_drugqty_check
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE 
		TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_drugqty_check' 
	AND COLUMN_NAME = 'zero_check'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_drugqty_check`
ADD COLUMN `zero_check` varchar(1) DEFAULT NULL AFTER `icode`";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
			}	
mysql_free_result($check);

/////////////////////////////////
/////////////////////////////////

// ## สร้างตาราง kohrx_iv_dilute
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_iv_dilute' ";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "CREATE TABLE ".$database_kohrx.".`kohrx_iv_dilute` (
  `icode` int(11) NOT NULL DEFAULT '0',
  `detail` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`icode`)
) ENGINE=InnoDB DEFAULT CHARSET=tis620;
";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
			}	
mysql_free_result($check);

/////////////////////////////////
/////////////////////////////////
// ## เพิ่มฟิล์ด score,step ใน kohrx_adr_check
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE 
		TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_adr_check' 
	AND COLUMN_NAME = 'step'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_adr_check`
ADD COLUMN `step` varchar(2) DEFAULT NULL AFTER `remark`";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
			$msg.="- สร้างฟิล์ด step เรียบร้อย <br/>";

			}	
mysql_free_result($check);

/////////////////////////////////
// ## เพิ่มฟิล์ด score,step ใน kohrx_adr_check
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE 
		TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_adr_check' 
	AND COLUMN_NAME = 'score'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_adr_check`
ADD COLUMN `score` int(3) DEFAULT NULL AFTER `step`";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
			$msg.="- สร้างฟิล์ด score เรียบร้อย <br/>";

			}	
mysql_free_result($check);

/////////////////////////////////

// ## ค้นหาว่า dispensing_setting มี important_data หรือเปล่า
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "select * from ".$database_kohrx.".kohrx_dispensing_setting where name = 'print_sticker_type' ";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($totalRows_check==0){
		mysql_select_db($database_hos, $hos);
		$query_alter = "insert into ".$database_kohrx.".kohrx_dispensing_setting (id,name,value) value ('32','print_sticker_type','1')";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
			}	
mysql_free_result($check);

/////////////////////////////////
/////////////////////////////////

// ## สร้างตาราง kohrx_queue_caller_server_check
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_queue_caller_server_check' ";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "CREATE TABLE ".$database_kohrx.".`kohrx_queue_caller_server_check` (
  `ip` varchar(20) NOT NULL,
  `time_update` time DEFAULT NULL,
  `date_update` date DEFAULT NULL,
  `room_id` int(3) DEFAULT NULL,
  PRIMARY KEY (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=tis620;
";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
			}	
mysql_free_result($check);

/////////////////////////////////

// ## สร้างตาราง kohrx_recent_media
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_recent_media' ";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "CREATE TABLE ".$database_kohrx.".`kohrx_recent_media` (
  `recent_media` varchar(10) NOT NULL,
  `channel` varchar(255) DEFAULT NULL,
  `istatus` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`recent_media`)
) ENGINE=InnoDB DEFAULT CHARSET=tis620;
";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
			
		mysql_select_db($database_hos, $hos);
		$query_alter = "INSERT INTO ".$database_kohrx.".`kohrx_recent_media` VALUES ('mv', NULL, NULL);";
		$alter = mysql_query($query_alter, $hos) or die(mysql_error());

		mysql_select_db($database_hos, $hos);
		$query_alter = "INSERT INTO ".$database_kohrx.".`kohrx_recent_media` VALUES ('sl', NULL, NULL);";
		$alter = mysql_query($query_alter, $hos) or die(mysql_error());

		mysql_select_db($database_hos, $hos);
		$query_alter = "INSERT INTO ".$database_kohrx.".`kohrx_recent_media` VALUES ('tv', NULL, NULL);";
		$alter = mysql_query($query_alter, $hos) or die(mysql_error());

		mysql_select_db($database_hos, $hos);
		$query_alter = "INSERT INTO ".$database_kohrx.".`kohrx_recent_media` VALUES ('yt', NULL, NULL);";
		$alter = mysql_query($query_alter, $hos) or die(mysql_error());

		mysql_select_db($database_hos, $hos);
		$query_alter = "INSERT INTO ".$database_kohrx.".`kohrx_recent_media` VALUES ('qu', NULL, 'Y');";
		$alter = mysql_query($query_alter, $hos) or die(mysql_error());

			$msg.="- สร้างราราง kohrx_recent_media เรียบร้อย <br/>";
			}
mysql_free_result($check);
	
/////////////////////////////////

// ## สร้างตาราง kohrx_recent_media2
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_recent_media2' ";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "CREATE TABLE ".$database_kohrx.".`kohrx_recent_media2` (
  `recent_media` varchar(10) NOT NULL,
  `channel` varchar(255) DEFAULT NULL,
  `istatus` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`recent_media`)
) ENGINE=InnoDB DEFAULT CHARSET=tis620;
";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
			
		
		mysql_select_db($database_hos, $hos);
		$query_alter = "INSERT INTO ".$database_kohrx.".`kohrx_recent_media2` VALUES ('mv', NULL, NULL);";
		$alter = mysql_query($query_alter, $hos) or die(mysql_error());

		mysql_select_db($database_hos, $hos);
		$query_alter = "INSERT INTO ".$database_kohrx.".`kohrx_recent_media2` VALUES ('sl', NULL, NULL);";
		$alter = mysql_query($query_alter, $hos) or die(mysql_error());

		mysql_select_db($database_hos, $hos);
		$query_alter = "INSERT INTO ".$database_kohrx.".`kohrx_recent_media2` VALUES ('tv', NULL, NULL);";
		$alter = mysql_query($query_alter, $hos) or die(mysql_error());

		mysql_select_db($database_hos, $hos);
		$query_alter = "INSERT INTO ".$database_kohrx.".`kohrx_recent_media2` VALUES ('yt', NULL, NULL);";
		$alter = mysql_query($query_alter, $hos) or die(mysql_error());


			$msg.="- สร้างราราง kohrx_recent_media2 เรียบร้อย <br/>";

			}	
mysql_free_result($check);

/////////////////////////////////

// ## สร้างตาราง kohrx_youtube_channel
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_youtube_channel' ";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "CREATE TABLE ".$database_kohrx.".`kohrx_youtube_channel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `channel` char(100) DEFAULT NULL,
  `channel_url` varchar(255) DEFAULT NULL,
  `channel_type` int(11) DEFAULT NULL,
  `thumb` varchar(255) DEFAULT NULL,
  `istatus` char(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=tis620;
";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
			$msg.="- สร้างราราง kohrx_youtube_channel เรียบร้อย <br/>";
			
			}
mysql_free_result($check);
	
/////////////////////////////////

// ## สร้างตาราง kohrx_queue_caller_time_left
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_queue_caller_time_left' ";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "CREATE TABLE ".$database_kohrx.".`kohrx_queue_caller_time_left` (
  `room_id` int(2) NOT NULL,
  `time_left` time DEFAULT NULL,
  PRIMARY KEY (`room_id`)
) ENGINE=InnoDB DEFAULT CHARSET=tis620;
";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
			$msg.="- สร้างราราง kohrx_queue_caller_time_left เรียบร้อย <br/>";
			
			}	
mysql_free_result($check);

/////////////////////////////////

// ## ค้นหาว่า dispensing_setting มี queue_display หรือเปล่า
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "select * from ".$database_kohrx.".kohrx_dispensing_setting where name = 'queue_display' ";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($totalRows_check==0){
		mysql_select_db($database_hos, $hos);
		$query_alter = "insert into ".$database_kohrx.".kohrx_dispensing_setting (id,name,value) value ('33','queue_display','Y')";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());

		mysql_select_db($database_hos, $hos);
		$query_alter = "insert into ".$database_kohrx.".kohrx_dispensing_setting (id,name,value) value ('34','upgrade_structure','')";
		$alter = mysql_query($query_alter, $hos) or die(mysql_error());

			$msg.="- เพิ่มข้อมูล queue_display ในตาราง kohrx_dispensing_setting เรียบร้อยแล้ว <br/>";
			$msg.="- เพิ่มข้อมูล upgrade_structure ในตาราง kohrx_dispensing_setting เรียบร้อยแล้ว <br/>";

			}	
mysql_free_result($check);

// ## ค้นหาว่า dispensing_setting มี upgrade_structure แ หรือเปล่า
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "select * from ".$database_kohrx.".kohrx_dispensing_setting where name = 'upgrade_structure' ";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($totalRows_check==0){

		mysql_select_db($database_hos, $hos);
		$query_alter = "insert into ".$database_kohrx.".kohrx_dispensing_setting (id,name,value) value ('34','upgrade_structure','')";
		$alter = mysql_query($query_alter, $hos) or die(mysql_error());

			$msg.="- เพิ่มข้อมูล upgrade_structure ในตาราง kohrx_dispensing_setting เรียบร้อยแล้ว <br/>";

			}	
mysql_free_result($check);
/////////////////////////////////

// ## ค้นหาว่า dispensing_setting มี doctor_code_number หรือเปล่า
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "select * from ".$database_kohrx.".kohrx_dispensing_setting where name = 'doctor_code_number' ";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($totalRows_check==0){

		mysql_select_db($database_hos, $hos);
		$query_alter = "insert into ".$database_kohrx.".kohrx_dispensing_setting (id,name,value) value ('35','doctor_code_number','Y')";
		$alter = mysql_query($query_alter, $hos) or die(mysql_error());

			$msg.="- เพิ่มข้อมูล doctor_code_number ในตาราง kohrx_dispensing_setting เรียบร้อยแล้ว <br/>";

			}	
mysql_free_result($check);
/////////////////////////////////

// ## ค้นหาว่า dispensing_setting มี auto logout หรือเปล่า
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "select * from ".$database_kohrx.".kohrx_dispensing_setting where name = 'auto_logout' ";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($totalRows_check==0){

		mysql_select_db($database_hos, $hos);
		$query_alter = "insert into ".$database_kohrx.".kohrx_dispensing_setting (id,name,value) value ('36','auto_logout','1800')";
		$alter = mysql_query($query_alter, $hos) or die(mysql_error());

			$msg.="- เพิ่มข้อมูล auto_logout ในตาราง kohrx_dispensing_setting เรียบร้อยแล้ว <br/>";

			}	
mysql_free_result($check);
/////////////////////////////////

// ## ตรวจสอบว่าการแสดงผล LCD เป็น media.php หรือเปล่า
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "select * from ".$database_kohrx.".kohrx_sub_menu where sub_menu_name='จอแสดงผลเรียกคิว' ";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['link']!="media.php"){

		mysql_select_db($database_hos, $hos);
		$query_alter = "update ".$database_kohrx.".kohrx_sub_menu set link='media.php' where sub_menu_name='จอแสดงผลเรียกคิว'";
		$alter = mysql_query($query_alter, $hos) or die(mysql_error());

			$msg.="- ปรับปรุงเมนู \"จอแสดงผลเรียกคิว เรียบร้อยแล้ว\" <br/>";

			}	
mysql_free_result($check);
/////////////////////////////////


// ## ค้นหาว่า dispensing_setting มี queue_list หรือเปล่า
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "select * from ".$database_kohrx.".kohrx_dispensing_setting where name = 'queue_list' ";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($totalRows_check==0){

		mysql_select_db($database_hos, $hos);
		$query_alter = "insert into ".$database_kohrx.".kohrx_dispensing_setting (id,name,value) value ('37','queue_list',NULL)";
		$alter = mysql_query($query_alter, $hos) or die(mysql_error());

			$msg.="- เพิ่มข้อมูล auto_logout ในตาราง kohrx_dispensing_setting เรียบร้อยแล้ว <br/>";

			}	
mysql_free_result($check);
/////////////////////////////////


// ## เพิ่มฟิล์ด cursor_possition ใน kohrx_queue_caller_channel
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE 
		TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_queue_caller_channel' 
	AND COLUMN_NAME = 'cursor_position'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_queue_caller_channel`
ADD COLUMN `cursor_position` varchar(20) DEFAULT NULL AFTER `doctor_type`";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ cursor_position ในตาราง kohrx_queue_caller_channel เรียบร้อยแล้ว <br/>";

			}	
mysql_free_result($check);

/////////////////////////////////

// ## เพิ่มฟิล์ด doctorcode ใน kohrx_queue_caller_list
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE 
		TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_queue_caller_list' 
	AND COLUMN_NAME = 'doctorcode'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_queue_caller_list`
ADD COLUMN `doctorcode` varchar(10) DEFAULT NULL AFTER `call_server`";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ doctorcode ในตาราง kohrx_queue_caller_list เรียบร้อยแล้ว <br/>";
			}	
mysql_free_result($check);

/////////////////////////////////
// ## สร้างตาราง kohrx_queue_caller_history
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_queue_caller_history' ";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "CREATE TABLE ".$database_kohrx.".`kohrx_queue_caller_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hn` char(20) DEFAULT NULL,
  `patient_name` char(200) DEFAULT NULL,
  `channel_id` int(3) DEFAULT NULL,
  `room_id` int(3) DEFAULT NULL,
  `patient_type` char(5) DEFAULT NULL,
  `dispensed` char(5) DEFAULT NULL,
  `called` char(5) DEFAULT NULL,
  `call_datetime` datetime DEFAULT NULL,
  `doctorcode` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=tis620;
";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
			$msg.="- สร้างราราง kohrx_queue_caller_history เรียบร้อย <br/>";
			
			}
mysql_free_result($check);
/////////////////////////////////////////////////

// ## เพิ่มฟิล์ด receiver ใน rx_operator
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE 
		TABLE_SCHEMA = '".$database_hos."' 
	AND TABLE_NAME = 'rx_operator' 
	AND COLUMN_NAME = 'receiver'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "ALTER TABLE `rx_operator`
ADD COLUMN `receiver` varchar(1) DEFAULT NULL AFTER `hos_guid`";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ receiver ในตาราง rx_operator เรียบร้อยแล้ว <br/>";
			}	
mysql_free_result($check);
//////////////////////////////////////////////

// ## เพิ่มฟิล์ด receiver_other ใน rx_operator
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE 
		TABLE_SCHEMA = '".$database_hos."' 
	AND TABLE_NAME = 'rx_operator' 
	AND COLUMN_NAME = 'receiver_other'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "ALTER TABLE `rx_operator`
ADD COLUMN `receiver_other` varchar(100) DEFAULT NULL AFTER `receiver`";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ receiver_other ในตาราง rx_operator เรียบร้อยแล้ว <br/>";
			}	
mysql_free_result($check);
//////////////////////////////////////////////

/////////////////////////////////
// ## สร้างตาราง kohrx_queued
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_queued' ";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "CREATE TABLE ".$database_kohrx.".`kohrx_queued` (
  `queue` int(11) NOT NULL,
  `queue_datetime` datetime NOT NULL,
  `hn` varchar(20) DEFAULT NULL,
  `room_name` varchar(50) DEFAULT NULL,
  `room_id` int(11) NOT NULL,
  `print_flag` varchar(1) DEFAULT NULL,
  `ptname` varchar(255) DEFAULT NULL,
  `q_express` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`queue`,`queue_datetime`,`room_id`)
) ENGINE=InnoDB DEFAULT CHARSET=tis620;
";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
			$msg.="- สร้างราราง kohrx_queued เรียบร้อย <br/>";
			
			}
mysql_free_result($check);
/////////////////////////////////////////////////

// ## เพิ่มฟิล์ด ptname ใน kohrx_queued
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE 
		TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_queued' 
	AND COLUMN_NAME = 'ptname'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_queued`
ADD COLUMN `ptname` varchar(255) DEFAULT NULL AFTER `print_flag`";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ ptname ในตาราง kohrx_queued เรียบร้อยแล้ว <br/>";
			}	
mysql_free_result($check);
//////////////////////////////////////////////

// ## เพิ่มฟิล์ด q_express ใน kohrx_queued
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE 
		TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_queued' 
	AND COLUMN_NAME = 'q_express'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_queued`
ADD COLUMN `q_express` varchar(1) DEFAULT NULL AFTER `ptname`";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ q_express ในตาราง kohrx_queued เรียบร้อยแล้ว <br/>";
			}	
mysql_free_result($check);
//////////////////////////////////////////////

// ## เพิ่มฟิล์ด q_show ใน kohrx_queue_caller_channel_name
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE 
		TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_queue_caller_channel_name' 
	AND COLUMN_NAME = 'q_show'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_queue_caller_channel_name`
ADD COLUMN `q_show` varchar(1) DEFAULT NULL AFTER `channel_name`";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ q_show ในตาราง kohrx_queue_caller_channel_name เรียบร้อยแล้ว <br/>";
			}	
mysql_free_result($check);
//////////////////////////////////////////////

// ## เพิ่มฟิล์ด payed ใน kohrx_queued
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE 
		TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_queued' 
	AND COLUMN_NAME = 'payed'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_queued`
ADD COLUMN `payed` varchar(1) DEFAULT NULL AFTER `q_express`";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ payed ในตาราง kohrx_queued เรียบร้อยแล้ว <br/>";
			}	
mysql_free_result($check);
//////////////////////////////////////////////
// ## เพิ่มฟิล์ด vn ใน kohrx_queued
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE 
		TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_queued' 
	AND COLUMN_NAME = 'vn'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_queued`
ADD COLUMN `vn` varchar(20) DEFAULT NULL AFTER `payed`";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ vn ในตาราง kohrx_queued เรียบร้อยแล้ว <br/>";
			}	
mysql_free_result($check);
//////////////////////////////////////////////
// ## เพิ่มฟิล์ด vn ใน kohrx_queued
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE 
		TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_queued' 
	AND COLUMN_NAME = 'q_delete'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_queued`
ADD COLUMN `q_delete` varchar(1) DEFAULT NULL AFTER `vn`";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ q_delete ในตาราง kohrx_queued เรียบร้อยแล้ว <br/>";
			}	
mysql_free_result($check);
//////////////////////////////////////////////
// ## เพิ่มฟิล์ด main_dep_queue ใน kohrx_queue_caller_list
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE 
		TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_queue_caller_list' 
	AND COLUMN_NAME = 'main_dep_queue'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_queue_caller_list`
ADD COLUMN `main_dep_queue` int(11) DEFAULT NULL AFTER `doctorcode`";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ main_dep_queue ในตาราง kohrx_queue_caller_list เรียบร้อยแล้ว <br/>";
			}	
mysql_free_result($check);
//////////////////////////////////////////////
// ## เพิ่มฟิล์ด main_dep ใน kohrx_queue_caller_list
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE 
		TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_queue_caller_list' 
	AND COLUMN_NAME = 'main_dep'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_queue_caller_list`
ADD COLUMN `main_dep` varchar(5) DEFAULT NULL AFTER `main_dep_queue`";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ main_dep ในตาราง kohrx_queue_caller_list เรียบร้อยแล้ว <br/>";
			}	
mysql_free_result($check);
//////////////////////////////////////////////
// ## เพิ่มฟิล์ด rx_queue ใน kohrx_queue_caller_list
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE 
		TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_queue_caller_list' 
	AND COLUMN_NAME = 'rx_queue'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_queue_caller_list`
ADD COLUMN `rx_queue` varchar(4) DEFAULT NULL AFTER `main_dep`";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ rx_queue ในตาราง kohrx_queue_caller_list เรียบร้อยแล้ว <br/>";
			}	
mysql_free_result($check);
//////////////////////////////////////////////

// ## สร้างตาราง kohrx_hosxp_queue_caller_config
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_hosxp_queue_caller_config' ";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "CREATE TABLE ".$database_kohrx.".`kohrx_hosxp_queue_caller_config` (
  `depcode` varchar(5) NOT NULL,
  `first_queue` int(11) DEFAULT NULL,
  `last_queue` int(11) DEFAULT NULL,
  `last_queue_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`depcode`)
) ENGINE=InnoDB DEFAULT CHARSET=tis620;
";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
			$msg.="- สร้างราราง kohrx_hosxp_queue_caller_config เรียบร้อย <br/>";
			
			}
mysql_free_result($check);
/////////////////////////////////////////////////
// ## เพิ่มฟิล์ด depcode  ใน kohrx_queue_caller_channel
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE 
		TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_queue_caller_channel' 
	AND COLUMN_NAME = 'depcode'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_queue_caller_channel`
ADD COLUMN `depcode` varchar(5) DEFAULT NULL AFTER `cursor_position`";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ depcode ในตาราง kohrx_queue_caller_channel เรียบร้อยแล้ว <br/>";
			}	
mysql_free_result($check);
/////////////////////////////////////////////////
// ## เพิ่มฟิล์ด  q_number ใน kohrx_queue_caller_channel
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE 
		TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_queue_caller_channel' 
	AND COLUMN_NAME = 'q_number'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_queue_caller_channel`
ADD COLUMN `q_number` varchar(1) DEFAULT NULL AFTER `depcode`";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ q_number ในตาราง kohrx_queue_caller_channel เรียบร้อยแล้ว <br/>";
			}	
mysql_free_result($check);
/////////////////////////////////////////////////
// ## เพิ่มฟิล์ด q_dep_type ใน kohrx_queue_caller_channel
//======= 
mysql_select_db($database_hos, $hos);
$query_check = "SELECT count(*) as ccolumn 
FROM information_schema.COLUMNS 
WHERE 
	TABLE_SCHEMA = '".$database_kohrx."' 
AND TABLE_NAME = 'kohrx_queue_caller_channel' 
AND COLUMN_NAME = 'q_dep_type'";
$check = mysql_query($query_check, $hos) or die(mysql_error());
$row_check = mysql_fetch_assoc($check);
$totalRows_check = mysql_num_rows($check);

		if($row_check['ccolumn']==0){
		mysql_select_db($database_hos, $hos);
		$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_queue_caller_channel`
ADD COLUMN `q_dep_type` varchar(10) DEFAULT NULL AFTER `q_number`";
		$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ q_dep_type ในตาราง kohrx_queue_caller_channel เรียบร้อยแล้ว <br/>";
		}	
mysql_free_result($check);
//////////////////////////////////////////////

// ## ค้นหาว่า dispensing_setting มี qrcode_ip หรือเปล่า
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "select * from ".$database_kohrx.".kohrx_dispensing_setting where name = 'qrcode_ip' ";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($totalRows_check==0){

		mysql_select_db($database_hos, $hos);
		$query_alter = "insert into ".$database_kohrx.".kohrx_dispensing_setting (id,name,value) value ('38','qrcode_ip',NULL)";
		$alter = mysql_query($query_alter, $hos) or die(mysql_error());

			$msg.="- เพิ่มข้อมูล qrcode_ip ในตาราง kohrx_dispensing_setting เรียบร้อยแล้ว <br/>";

			}	
mysql_free_result($check);
/////////////////////////////////

// ## เพิ่มฟิล์ด vn ใน kohrx_queued
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE 
		TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_queued' 
	AND COLUMN_NAME = 'token'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_queued`
ADD COLUMN `token` varchar(20) DEFAULT NULL AFTER `q_delete`";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ token ในตาราง kohrx_queued เรียบร้อยแล้ว <br/>";
			}	
mysql_free_result($check);
//////////////////////////////////////////////
// ## เพิ่มฟิล์ด qrcode ใน kohrx_queued
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE 
		TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_queued' 
	AND COLUMN_NAME = 'qrcode'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_queued`
ADD COLUMN `qrcode` blob  AFTER `token`";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ qrcode ในตาราง kohrx_queued เรียบร้อยแล้ว <br/>";
			}	
mysql_free_result($check);
///////////////////////////////////////////
// ## เพิ่มฟิล์ด record_time ใน kohrx_couselling
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE 
		TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_couselling' 
	AND COLUMN_NAME = 'record_time'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_couselling`
ADD COLUMN `record_time` time  AFTER `record_date`";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ record_time ในตาราง kohrx_couselling เรียบร้อยแล้ว <br/>";
			}	
mysql_free_result($check);
//////////////////////////////////////////////
// ## เพิ่มฟิล์ด called_channel ใน kohrx_queued
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE 
		TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_queued' 
	AND COLUMN_NAME = 'called_channel'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_queued`
ADD COLUMN `called_channel` int(3) DEFAULT NULL AFTER `qrcode`";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ called_channel ในตาราง kohrx_queued เรียบร้อยแล้ว <br/>";
			}	
mysql_free_result($check);
//////////////////////////////////////////////
// ## เพิ่มฟิล์ด called_datetime ใน kohrx_queued
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE 
		TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_queued' 
	AND COLUMN_NAME = 'called_datetime'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_queued`
ADD COLUMN `called_datetime` datetime DEFAULT NULL AFTER `called_channel`";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ called_datetime ในตาราง kohrx_queued เรียบร้อยแล้ว <br/>";
			}	
mysql_free_result($check);
//////////////////////////////////////////////
// ## สร้างตาราง kohrx_hosxp_queue_caller
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_hosxp_queue_caller' ";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "CREATE TABLE ".$database_kohrx.".`kohrx_hosxp_queue_caller` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `queue_date` date DEFAULT NULL,
  `queue_date_call` datetime DEFAULT NULL,
  `room_id` int(11) DEFAULT NULL,
  `main_dep` varchar(3) DEFAULT NULL,
  `main_dep_queue` int(11) DEFAULT NULL,
  `channel_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=159 DEFAULT CHARSET=tis620;
";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
			$msg.="- สร้างราราง kohrx_hosxp_queue_caller เรียบร้อย <br/>";
			
			}
mysql_free_result($check);
/////////////////////////////////////////////////
// ## เพิ่มฟิล์ด  time_per_case ใน kohrx_queue_caller_channel
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE 
		TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_queue_caller_channel' 
	AND COLUMN_NAME = 'time_per_case'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_queue_caller_channel`
ADD COLUMN `time_per_case` int(2) DEFAULT NULL AFTER `q_dep_type`";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ time_per_case ในตาราง kohrx_queue_caller_channel เรียบร้อยแล้ว <br/>";
			}	
mysql_free_result($check);
/////////////////////////////////////////////////
// ## เพิ่มฟิล์ด  caller_tv ใน kohrx_queue_caller_channel
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE 
		TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_queue_caller_channel' 
	AND COLUMN_NAME = 'caller_tv'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_queue_caller_channel`
ADD COLUMN `caller_tv` varchar(1) DEFAULT NULL AFTER `time_per_case`";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ caller_tv ในตาราง kohrx_queue_caller_channel เรียบร้อยแล้ว <br/>";
			}	
mysql_free_result($check);
/////////////////////////////////////////////////
// ## เพิ่มฟิล์ด  caller_method ใน kohrx_queue_caller_channel
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE 
		TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_queue_caller_channel' 
	AND COLUMN_NAME = 'caller_method'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_queue_caller_channel`
ADD COLUMN `caller_method` int(1) DEFAULT NULL AFTER `caller_tv`";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ caller_method ในตาราง kohrx_queue_caller_channel เรียบร้อยแล้ว <br/>";
			}	
mysql_free_result($check);
/////////////////////////////////////////////////
// ## เพิ่มฟิล์ด  patient_picture ใน kohrx_queue_caller_channel
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE 
		TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_queue_caller_channel' 
	AND COLUMN_NAME = 'patient_picture'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_queue_caller_channel`
ADD COLUMN `patient_picture` varchar(1) DEFAULT NULL AFTER `caller_method`";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ patient_picture ในตาราง kohrx_queue_caller_channel เรียบร้อยแล้ว <br/>";
			}	
mysql_free_result($check);

// ## เพิ่มฟิล์ด first_cur_dep_time ใน kohrx_hosxp_queue_config
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE 
		TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_hosxp_queue_caller_config' 
	AND COLUMN_NAME = 'first_cur_dep_time'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_hosxp_queue_caller_config`
ADD COLUMN `first_cur_dep_time` time DEFAULT NULL AFTER `first_queue`";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ first_cur_dep_time ในตาราง kohrx_hosxp_queue_caller_config เรียบร้อยแล้ว <br/>";
			}	
mysql_free_result($check);
//////////////////////////////////////////////
// ## เพิ่มฟิล์ด last_cur_dep_time ใน kohrx_hosxp_queue_config
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE 
		TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_hosxp_queue_caller_config' 
	AND COLUMN_NAME = 'last_cur_dep_time'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_hosxp_queue_caller_config`
ADD COLUMN `last_cur_dep_time` time DEFAULT NULL AFTER `last_queue`";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ last_cur_dep_time ในตาราง kohrx_hosxp_queue_caller_config เรียบร้อยแล้ว <br/>";
			}	
mysql_free_result($check);
//////////////////////////////////////////////
// ## เพิ่มฟิล์ด last_cur_dep_time ใน kohrx_hosxp_queue_caller
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE 
		TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_hosxp_queue_caller' 
	AND COLUMN_NAME = 'cur_dep_time'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_hosxp_queue_caller`
ADD COLUMN `cur_dep_time` time DEFAULT NULL AFTER `channel_id`";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ cur_dep_time ในตาราง kohrx_hosxp_queue_caller เรียบร้อยแล้ว <br/>";
			}	
mysql_free_result($check);
//////////////////////////////////////////////
// ## เพิ่มฟิล์ด  queue_method ใน kohrx_queue_caller_channel
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE 
		TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_queue_caller_channel' 
	AND COLUMN_NAME = 'queue_method'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_queue_caller_channel`
ADD COLUMN `queue_method` int(1) DEFAULT NULL AFTER `patient_picture`";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ queue_method ในตาราง kohrx_queue_caller_channel เรียบร้อยแล้ว <br/>";
			}	
mysql_free_result($check);
/////////////////////////////////////////////////
// ## สร้างตาราง kohrx_kiosk_queue_caller_config
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_kiosk_queue_caller_config' ";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "CREATE TABLE ".$database_kohrx.".`kohrx_kiosk_queue_caller_config` (
  `room_id` int(11) NOT NULL,
  `q_date` date DEFAULT NULL,
  `current_q` int(11) DEFAULT NULL,
  `current_q_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`room_id`)
) ENGINE=InnoDB DEFAULT CHARSET=tis620 ROW_FORMAT=COMPACT";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
			$msg.="- สร้างตาราง kohrx_kiosk_queue_caller_config เรียบร้อยแล้ว <br/>";

			}	
mysql_free_result($check);

/////////////////////////////////
// ## เพิ่มฟิล์ด  queue_method ใน kohrx_queue_caller_channel
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE 
		TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_queue_caller_channel' 
	AND COLUMN_NAME = 'queue_display'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_queue_caller_channel`
ADD COLUMN `queue_display` int(1) DEFAULT NULL AFTER `queue_method`";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ queue_display ในตาราง kohrx_queue_caller_channel เรียบร้อยแล้ว <br/>";
			}	
mysql_free_result($check);
/////////////////////////////////////////////////
/////////////////////////////////
// ## เพิ่มฟิล์ด  print_server ใน kohrx_queue_caller_room
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE 
		TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_queue_caller_room' 
	AND COLUMN_NAME = 'print_server'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_queue_caller_room`
ADD COLUMN `print_server` varchar(20) DEFAULT NULL AFTER `room_name`";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ print_server ในตาราง kohrx_queue_caller_room เรียบร้อยแล้ว <br/>";
			}	
mysql_free_result($check);
//////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////
// ## เพิ่มฟิล์ด  kskdepart ใน kohrx_queue_caller_channel
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE 
		TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_queue_caller_channel' 
	AND COLUMN_NAME = 'kskdepart'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_queue_caller_channel`
ADD COLUMN `kskdepart` varchar(5) DEFAULT NULL AFTER `queue_display`";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ kskdepart ในตาราง kohrx_queue_caller_channel เรียบร้อยแล้ว <br/>";
			}	
mysql_free_result($check);
//////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////
// ## เพิ่มฟิล์ด  outdepcode ใน kohrx_queue_caller_channel
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE 
		TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_queue_caller_channel' 
	AND COLUMN_NAME = 'outdepcode'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_queue_caller_channel`
ADD COLUMN `outdepcode` varchar(5) DEFAULT NULL AFTER `kskdepart`";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ outdepcode ในตาราง kohrx_queue_caller_channel เรียบร้อยแล้ว <br/>";
			}	
mysql_free_result($check);
//////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////
/////////////////////////////////////////////////
// ## เพิ่มฟิล์ด queue_list ใน kohrx_queue_caller_channel
//======= 
mysql_select_db($database_hos, $hos);
$query_check = "SELECT count(*) as ccolumn 
FROM information_schema.COLUMNS 
WHERE 
	TABLE_SCHEMA = '".$database_kohrx."' 
AND TABLE_NAME = 'kohrx_queue_caller_channel' 
AND COLUMN_NAME = 'queue_list'";
$check = mysql_query($query_check, $hos) or die(mysql_error());
$row_check = mysql_fetch_assoc($check);
$totalRows_check = mysql_num_rows($check);

		if($row_check['ccolumn']==0){
		mysql_select_db($database_hos, $hos);
		$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_queue_caller_channel`
ADD COLUMN `queue_list` int(1) DEFAULT NULL AFTER `outdepcode`";
		$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ queue_list ในตาราง kohrx_queue_caller_channel เรียบร้อยแล้ว <br/>";
		}	
mysql_free_result($check);
//////////////////////////////////////////////
// ## ค้นหาว่า dispensing_setting มี start_e_q หรือเปล่า
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "select * from ".$database_kohrx.".kohrx_dispensing_setting where name = 'start_e_q' ";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($totalRows_check==0){
		mysql_select_db($database_hos, $hos);
		$query_alter = "insert into ".$database_kohrx.".kohrx_dispensing_setting (id,name,value) value ('40','start_e_q','500')";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
			}
mysql_free_result($check);
	
/////////////////////////////////
// ## เพิ่มฟิล์ด not_response ใน kohrx_queue_caller_list
//======= 
mysql_select_db($database_hos, $hos);
$query_check = "SELECT count(*) as ccolumn 
FROM information_schema.COLUMNS 
WHERE 
	TABLE_SCHEMA = '".$database_kohrx."' 
AND TABLE_NAME = 'kohrx_queue_caller_list' 
AND COLUMN_NAME = 'not_response'";
$check = mysql_query($query_check, $hos) or die(mysql_error());
$row_check = mysql_fetch_assoc($check);
$totalRows_check = mysql_num_rows($check);

		if($row_check['ccolumn']==0){
		mysql_select_db($database_hos, $hos);
		$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_queue_caller_list`
ADD COLUMN `not_response` varchar(1) DEFAULT NULL AFTER `called`";
		$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ not_response ในตาราง kohrx_queue_caller_list เรียบร้อยแล้ว <br/>";
		}	
mysql_free_result($check);
//////////////////////////////////////////////
//========== 2/7/2562 ================//
/////////////////////////////////
// ## เพิ่มฟิล์ด recent_rx_queue ใน kohrx_queue_caller_channel
//======= 
mysql_select_db($database_hos, $hos);
$query_check = "SELECT count(*) as ccolumn 
FROM information_schema.COLUMNS 
WHERE 
	TABLE_SCHEMA = '".$database_kohrx."' 
AND TABLE_NAME = 'kohrx_queue_caller_channel' 
AND COLUMN_NAME = 'recent_rx_queue'";
$check = mysql_query($query_check, $hos) or die(mysql_error());
$row_check = mysql_fetch_assoc($check);
$totalRows_check = mysql_num_rows($check);

		if($row_check['ccolumn']==0){
		mysql_select_db($database_hos, $hos);
		$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_queue_caller_channel`
ADD COLUMN `recent_rx_queue` int(5) DEFAULT NULL AFTER `queue_list`";
		$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ recent_rx_queue ในตาราง kohrx_queue_caller_channel เรียบร้อยแล้ว <br/>";
		}	
mysql_free_result($check);
//////////////////////////////////////////////
//========== 2/7/2562 ================//
/////////////////////////////////
// ## เพิ่มฟิล์ด recent_rx_queue_datetime ใน kohrx_queue_caller_channel
//======= 
mysql_select_db($database_hos, $hos);
$query_check = "SELECT count(*) as ccolumn 
FROM information_schema.COLUMNS 
WHERE 
	TABLE_SCHEMA = '".$database_kohrx."' 
AND TABLE_NAME = 'kohrx_queue_caller_channel' 
AND COLUMN_NAME = 'recent_rx_queue_datetime'";
$check = mysql_query($query_check, $hos) or die(mysql_error());
$row_check = mysql_fetch_assoc($check);
$totalRows_check = mysql_num_rows($check);

		if($row_check['ccolumn']==0){
		mysql_select_db($database_hos, $hos);
		$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_queue_caller_channel`
ADD COLUMN `recent_rx_queue_datetime` datetime DEFAULT NULL AFTER `recent_rx_queue`";
		$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ recent_rx_queue_datetime ในตาราง kohrx_queue_caller_channel เรียบร้อยแล้ว <br/>";
		}	
mysql_free_result($check);

////////////////////////////////////////////////
/////////////////////////////////
// ## เพิ่มฟิล์ด risk_category ใน kohrx_drp_record
//======= 
mysql_select_db($database_hos, $hos);
$query_check = "SELECT count(*) as ccolumn 
FROM information_schema.COLUMNS 
WHERE 
	TABLE_SCHEMA = '".$database_kohrx."' 
AND TABLE_NAME = 'kohrx_drp_record' 
AND COLUMN_NAME = 'risk_category'";
$check = mysql_query($query_check, $hos) or die(mysql_error());
$row_check = mysql_fetch_assoc($check);
$totalRows_check = mysql_num_rows($check);

		if($row_check['ccolumn']==0){
		mysql_select_db($database_hos, $hos);
		$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_drp_record`
ADD COLUMN `risk_category` varchar(1) DEFAULT NULL AFTER `recorder`";
		$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ risk_category ในตาราง kohrx_drp_record เรียบร้อยแล้ว <br/>";
		}	
mysql_free_result($check);

////////////////////////////////////////////////
// ## เพิ่มฟิล์ด pttype ใน kohrx_drp_record
//======= 
mysql_select_db($database_hos, $hos);
$query_check = "SELECT count(*) as ccolumn 
FROM information_schema.COLUMNS 
WHERE 
	TABLE_SCHEMA = '".$database_kohrx."' 
AND TABLE_NAME = 'kohrx_drp_record' 
AND COLUMN_NAME = 'pttype'";
$check = mysql_query($query_check, $hos) or die(mysql_error());
$row_check = mysql_fetch_assoc($check);
$totalRows_check = mysql_num_rows($check);

		if($row_check['ccolumn']==0){
		mysql_select_db($database_hos, $hos);
		$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_drp_record`
ADD COLUMN `pttype` varchar(5) DEFAULT NULL AFTER `risk_category`";
		$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ pttype ในตาราง kohrx_drp_record เรียบร้อยแล้ว <br/>";
		}	
mysql_free_result($check);

// ## เพิ่มฟิล์ด full_screen ใน kohrx_queue_caller_channel
//======= 
mysql_select_db($database_hos, $hos);
$query_check = "SELECT count(*) as ccolumn 
FROM information_schema.COLUMNS 
WHERE 
	TABLE_SCHEMA = '".$database_kohrx."' 
AND TABLE_NAME = 'kohrx_queue_caller_channel' 
AND COLUMN_NAME = 'full_screen'";
$check = mysql_query($query_check, $hos) or die(mysql_error());
$row_check = mysql_fetch_assoc($check);
$totalRows_check = mysql_num_rows($check);

		if($row_check['ccolumn']==0){
		mysql_select_db($database_hos, $hos);
		$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_queue_caller_channel`
ADD COLUMN `full_screen` varchar(1) DEFAULT NULL AFTER `recent_rx_queue_datetime`";
		$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ full_screen ในตาราง kohrx_queue_caller_channel เรียบร้อยแล้ว <br/>";
		}	
mysql_free_result($check);

/////////////////////////////////////////////////////
// ## เปลี่ยนชนิดฟิล์ด respondent ใน kohrx_adr_check
//======= 
mysql_select_db($database_hos, $hos);
$query_check = "SELECT count(*) as ccolumn 
FROM information_schema.COLUMNS 
WHERE 
	TABLE_SCHEMA = '".$database_kohrx."' and
TABLE_NAME = 'kohrx_adr_check' AND COLUMN_NAME = 'respondent' and data_type='varchar'";
$check = mysql_query($query_check, $hos) or die(mysql_error());
$row_check = mysql_fetch_assoc($check);
$totalRows_check = mysql_num_rows($check);

		if($row_check['ccolumn']==0){
		mysql_select_db($database_hos, $hos);
		$query_alter = "ALTER TABLE ".$database_kohrx.".kohrx_adr_check MODIFY respondent VARCHAR(2)";
		$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- แก้ไขชนิดฟิลด์ respondent ในตาราง kohrx_adr_check เรียบร้อยแล้ว <br/>";
		}	
mysql_free_result($check);

/////////////////////////////////////////////////////


// ## เปลี่ยนชนิดฟิล์ด answer ใน kohrx_adr_check
//======= 
mysql_select_db($database_hos, $hos);
$query_check = "SELECT count(*) as ccolumn 
FROM information_schema.COLUMNS 
WHERE 
	TABLE_SCHEMA = '".$database_kohrx."' and
TABLE_NAME = 'kohrx_adr_check' AND COLUMN_NAME = 'answer' and data_type='varchar' and CHARACTER_MAXIMUM_LENGTH = '2'";
//echo $query_check;
$check = mysql_query($query_check, $hos) or die(mysql_error());
$row_check = mysql_fetch_assoc($check);
$totalRows_check = mysql_num_rows($check);

		if($row_check['ccolumn']==0){
		mysql_select_db($database_hos, $hos);
		$query_alter = "ALTER TABLE ".$database_kohrx.".kohrx_adr_check MODIFY answer VARCHAR(2)";
		$alter = mysql_query($query_alter, $hos) or die(mysql_error());

            $msg.="- แก้ไขชนิดฟิลด์ answer ในตาราง kohrx_adr_check เรียบร้อยแล้ว <br/>";
		}	
mysql_free_result($check);

// ## สร้างตาราง kohrx_rx_person
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_rx_person' ";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "CREATE TABLE ".$database_kohrx.".`kohrx_rx_person` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT ' ',
  `doctorcode` varchar(5) NOT NULL,
  `active` char(1) DEFAULT NULL,
  PRIMARY KEY (`id`,`doctorcode`)
) ENGINE=InnoDB DEFAULT CHARSET=tis620;";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
			
			if($alter){
				$msg.="- สร้างตาราง kohrx_rx_person เรียบร้อยแล้ว <br/>";
			}
		}	
mysql_free_result($check);

// ## สร้างตาราง kohrx_doctor_operation
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_doctor_operation' ";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "CREATE TABLE ".$database_kohrx.".`kohrx_doctor_operation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `doctorcode` varchar(255) DEFAULT NULL,
  `print_staff` varchar(10) DEFAULT NULL,
  `prepare_staff` varchar(10) DEFAULT NULL,
  `check_staff` varchar(10) DEFAULT NULL,
  `pay_staff` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=tis620;";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
			
			if($alter){
				$msg.="- สร้างตาราง kohrx_doctor_operation เรียบร้อยแล้ว <br/>";
			}
		}	
mysql_free_result($check);


/////////////////////////////////////////////////////
// ## สร้างตาราง kohrx_med_reconcile
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_med_reconcile' ";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "CREATE TABLE ".$database_kohrx.".`kohrx_med_reconcile` (
			  `id` int(10) NOT NULL AUTO_INCREMENT,
			  `hn` varchar(9) NOT NULL,
			  `vstdate` date DEFAULT NULL,
			  `vstdate2` date DEFAULT NULL,
			  `hos_guid` varchar(38) NOT NULL,
			  `icode` varchar(10) DEFAULT NULL,
			  `drug_name` varchar(250) NOT NULL,
			  `drugusage` varchar(255) NOT NULL,
			  `qty` int(10) DEFAULT NULL,
			  `remain` int(10) DEFAULT NULL,
			  `src_hospcode` varchar(100) DEFAULT NULL,
			  `appdate` date DEFAULT NULL,
			  `last_dose` varchar(255) DEFAULT NULL,
			  `remark` varchar(255) DEFAULT NULL,
			  PRIMARY KEY (`id`,`drug_name`,`drugusage`,`hn`) USING BTREE
			) ENGINE=InnoDB AUTO_INCREMENT=5081 DEFAULT CHARSET=tis620;";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
			
			if($alter){
				$msg.="- สร้างตาราง kohrx_med_reconcile เรียบร้อยแล้ว <br/>";
			}
		}	
mysql_free_result($check);

/////////////////////////////////////////////////////
// ## สร้างตาราง kohrx_drug_checked
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_drug_checked'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "CREATE TABLE ".$database_kohrx.".`kohrx_drug_checked` (
			  `vn` varchar(20) NOT NULL,
			  `hos_guid` varchar(100) NOT NULL,
			  `doctorcode` varchar(10) DEFAULT NULL,
			  PRIMARY KEY (`vn`,`hos_guid`) USING BTREE
			) ENGINE=InnoDB DEFAULT CHARSET=tis620;";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
			
			if($alter){
				$msg.="- สร้างตาราง kohrx_drug_checked เรียบร้อยแล้ว <br/>";
			}
		}	
mysql_free_result($check);
// ## เปลี่ยนชนิดฟิล์ด detail_drug_sound ใน kohrx_queue_caller_channel
//======= 
mysql_select_db($database_hos, $hos);
$query_check = "SELECT count(*) as ccolumn 
FROM information_schema.COLUMNS 
WHERE TABLE_SCHEMA = '".$database_kohrx."' and
TABLE_NAME = 'kohrx_queue_caller_channel' AND COLUMN_NAME = 'detail_drug_sound'";
$check = mysql_query($query_check, $hos) or die(mysql_error());
$row_check = mysql_fetch_assoc($check);
$totalRows_check = mysql_num_rows($check);

		if($row_check['ccolumn']==0){
		mysql_select_db($database_hos, $hos);
		$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_queue_caller_channel`
ADD COLUMN `detail_drug_sound` varchar(1) DEFAULT NULL AFTER `full_screen`";
		$alter = mysql_query($query_alter, $hos) or die(mysql_error());

            $msg.="- แก้ไขชนิดฟิลด์ detail_drug_sound ในตาราง kohrx_queue_caller_channel เรียบร้อยแล้ว <br/>";
		}	
mysql_free_result($check);
// ## สร้างตาราง kohrx_recent_payment
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_recent_payment'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "CREATE TABLE ".$database_kohrx.".`kohrx_recent_payment` (
			 `doctorcode` varchar(5) NOT NULL,
			  `print_staff` varchar(5) DEFAULT NULL,
			  `prepare_staff` varchar(5) DEFAULT NULL,
			  `check_staff` varchar(5) DEFAULT NULL,
			  `pay_staff` varchar(5) DEFAULT NULL,
			  `respondent` varchar(1) DEFAULT NULL,
			  `answer` varchar(1) DEFAULT NULL,
			  `update_datetime` datetime DEFAULT NULL,
			  PRIMARY KEY (`doctorcode`) USING BTREE
			) ENGINE=InnoDB DEFAULT CHARSET=tis620;";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
			
			if($alter){
				$msg.="- สร้างตาราง kohrx_recent_payment เรียบร้อยแล้ว <br/>";
			}
		}	
mysql_free_result($check);

// ## สร้างตาราง kohrx_drug_return
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_drug_return'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "CREATE TABLE ".$database_kohrx.".`kohrx_drug_return` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `icode` char(20) DEFAULT NULL,
                `qty` int(11) DEFAULT NULL,
                `recdate` date DEFAULT NULL,
                PRIMARY KEY (`id`) USING BTREE
                ) ENGINE=InnoDB AUTO_INCREMENT=5668 DEFAULT CHARSET=tis620;";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
			
			if($alter){
				$msg.="- สร้างตาราง kohrx_drug_return เรียบร้อยแล้ว <br/>";
			}
		}	
mysql_free_result($check);

  
/////////////////////////////////////////////////////
// ## สร้างตาราง kohrx_drug_monograph
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_drug_monograph'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "CREATE TABLE ".$database_kohrx.".`kohrx_drug_monograph` (
              `icode` varchar(10) NOT NULL,
              `monograph` text,
              `monograph_type` char(1) DEFAULT NULL,
              PRIMARY KEY (`icode`)
            ) ENGINE=InnoDB DEFAULT CHARSET=tis620;";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
			
			if($alter){
				$msg.="- สร้างตาราง kohrx_drug_monograph เรียบร้อยแล้ว <br/>";
			}
		}	
mysql_free_result($check);

  
/////////////////////////////////////////////////////
// ## สร้างตาราง kohrx_steroid_inhale_use
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_steroid_inhale_use'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "CREATE TABLE ".$database_kohrx.".`kohrx_steroid_inhale_use` (
			  `drugusage` varchar(10) NOT NULL,
			  `puff_per_day` int(3) DEFAULT NULL,
			  PRIMARY KEY (`drugusage`)
			) ENGINE=InnoDB DEFAULT CHARSET=tis620;";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
			
			if($alter){
				$msg.="- สร้างตาราง kohrx_steroid_inhale_use เรียบร้อยแล้ว <br/>";
			}
		}	
mysql_free_result($check);

// ## เพิ่มฟิล์ด needle ใน kohrx_insulin_syring
//======= 
mysql_select_db($database_hos, $hos);
$query_check = "SELECT count(*) as ccolumn 
FROM information_schema.COLUMNS 
WHERE 
	TABLE_SCHEMA = '".$database_kohrx."' 
AND TABLE_NAME = 'kohrx_insulin_syring' 
AND COLUMN_NAME = 'needle_type'";
$check = mysql_query($query_check, $hos) or die(mysql_error());
$row_check = mysql_fetch_assoc($check);
$totalRows_check = mysql_num_rows($check);

		if($row_check['ccolumn']==0){
		mysql_select_db($database_hos, $hos);
		$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_insulin_syring`
ADD COLUMN `needle_type` char(1) DEFAULT NULL AFTER `syring_type`";
		$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ needle_type ในตาราง kohrx_insulin_syring เรียบร้อยแล้ว <br/>";
		}	
mysql_free_result($check);

// ## เพิ่มฟิล์ด receive_time ใน kohrx_emergency_drug
//======= 
mysql_select_db($database_hos, $hos);
$query_check = "SELECT count(*) as ccolumn 
FROM information_schema.COLUMNS 
WHERE 
	TABLE_SCHEMA = '".$database_kohrx."' 
AND TABLE_NAME = 'kohrx_emergency_drug' 
AND COLUMN_NAME = 'receive_time'";
$check = mysql_query($query_check, $hos) or die(mysql_error());
$row_check = mysql_fetch_assoc($check);
$totalRows_check = mysql_num_rows($check);

		if($row_check['ccolumn']==0){
		mysql_select_db($database_hos, $hos);
		$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_emergency_drug`
ADD COLUMN `receive_time` time DEFAULT NULL AFTER `dispen_time`";
		$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ receive_time ในตาราง kohrx_emergency_drug เรียบร้อยแล้ว <br/>";
		}	
mysql_free_result($check);

// ## เพิ่มฟิล์ด is_error ใน kohrx_syr_dosing_record
//======= 
mysql_select_db($database_hos, $hos);
$query_check = "SELECT count(*) as ccolumn 
FROM information_schema.COLUMNS 
WHERE 
	TABLE_SCHEMA = '".$database_kohrx."' 
AND TABLE_NAME = 'kohrx_syr_dosing_record' 
AND COLUMN_NAME = 'is_error'";
$check = mysql_query($query_check, $hos) or die(mysql_error());
$row_check = mysql_fetch_assoc($check);
$totalRows_check = mysql_num_rows($check);

		if($row_check['ccolumn']==0){
		mysql_select_db($database_hos, $hos);
		$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_syr_dosing_record`
ADD COLUMN `is_error` char(1) DEFAULT NULL AFTER `daterecord`";
		$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ is_error ในตาราง kohrx_syr_dosing_record เรียบร้อยแล้ว <br/>";
		}
if($alter){
		mysql_select_db($database_hos, $hos);
		$query_update = "update ".$database_kohrx.".`kohrx_syr_dosing_record`
set `is_error` ='Y' where is_error is NULL";
		$update = mysql_query($query_update, $hos) or die(mysql_error());	
}
mysql_free_result($check);


// ## เพิ่มฟิล์ด an ใน kohrx_adr_check
//======= 
mysql_select_db($database_hos, $hos);
$query_check = "SELECT count(*) as ccolumn 
FROM information_schema.COLUMNS 
WHERE 
	TABLE_SCHEMA = '".$database_kohrx."' 
AND TABLE_NAME = 'kohrx_adr_check' 
AND COLUMN_NAME = 'an'";
$check = mysql_query($query_check, $hos) or die(mysql_error());
$row_check = mysql_fetch_assoc($check);
$totalRows_check = mysql_num_rows($check);

		if($row_check['ccolumn']==0){
		mysql_select_db($database_hos, $hos);
		$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_adr_check`
ADD COLUMN `an` varchar(20) DEFAULT NULL AFTER `vn`";
		$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ an ในตาราง kohrx_adr_check เรียบร้อยแล้ว <br/>";
		}	
mysql_free_result($check);
 // ## สร้างตาราง kohrx_med_reconcile_error
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_med_reconcile_error'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "CREATE TABLE ".$database_kohrx.".`kohrx_med_reconcile_error` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `med_reconcile_id` int(11) NOT NULL,
			  `error_type` int(2) DEFAULT NULL,
			  `error_cause` int(3) DEFAULT NULL,
			  `error_subtype` int(3) DEFAULT NULL,
			  `category` varchar(1) DEFAULT NULL,
			  `consult` int(1) DEFAULT NULL,
			  `detail` longtext,
			  `solv` longtext,
			  `reporter` varchar(10) DEFAULT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=tis620;";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
			
			if($alter){
				$msg.="- สร้างตาราง kohrx_med_reconcile_error เรียบร้อยแล้ว <br/>";
			}
		}	
mysql_free_result($check);

 // ## สร้างตาราง kohrx_med_reconcile_disease
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_med_reconcile_disease'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "
			CREATE TABLE ".$database_kohrx.".`kohrx_med_reconcile_disease` (
			  `hn` varchar(10) NOT NULL,
			  `vstdate` date NOT NULL,
			  `med_reconcile_disease_type` int(2) NOT NULL,
			  PRIMARY KEY (`hn`,`med_reconcile_disease_type`,`vstdate`) USING BTREE
			) ENGINE=InnoDB DEFAULT CHARSET=tis620;
			";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
			
			if($alter){
				$msg.="- สร้างตาราง kohrx_med_reconcile_disease เรียบร้อยแล้ว <br/>";
			}
		}	
mysql_free_result($check);

// ## สร้างตาราง kohrx_med_reconcile_disease
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_med_reconcile_disease'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "
			CREATE TABLE ".$database_kohrx.".`kohrx_med_reconcile_disease` (
			  `hn` varchar(10) NOT NULL,
			  `vstdate` date NOT NULL,
			  `med_reconcile_disease_type` int(2) NOT NULL,
			  PRIMARY KEY (`hn`,`med_reconcile_disease_type`,`vstdate`) USING BTREE
			) ENGINE=InnoDB DEFAULT CHARSET=tis620;
			";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
			
			if($alter){
				$msg.="- สร้างตาราง kohrx_med_reconcile_disease เรียบร้อยแล้ว <br/>";
			}
		}	
mysql_free_result($check);

// ## สร้างตาราง kohrx_med_reconcile_header
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_med_reconcile_header'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "
			CREATE TABLE ".$database_kohrx.".`kohrx_med_reconcile_header` (
              `hn` varchar(20) NOT NULL,
              `vstdate` date NOT NULL,
              `create_time` time DEFAULT NULL,
              `creator` varchar(10) DEFAULT NULL,
              PRIMARY KEY (`hn`,`vstdate`)
            ) ENGINE=InnoDB DEFAULT CHARSET=tis620;
			";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
			
			if($alter){
				$msg.="- สร้างตาราง kohrx_med_reconcile_header เรียบร้อยแล้ว <br/>";
			}
		}	
mysql_free_result($check);
 // ## สร้างตาราง kohrx_med_reconcile_disease_type
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_med_reconcile_disease_type'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "
			CREATE TABLE ".$database_kohrx.".`kohrx_med_reconcile_disease_type` (
			  `med_reconcile_disease_type` int(11) NOT NULL,
			  `med_reconcile_disease_name` varchar(255) DEFAULT NULL,
			  PRIMARY KEY (`med_reconcile_disease_type`) USING BTREE
			) ENGINE=InnoDB DEFAULT CHARSET=tis620;			
			";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
			
			if($alter){
			mysql_select_db($database_hos, $hos);
			$query_insert = "INSERT INTO ".$database_kohrx.".`kohrx_med_reconcile_disease_type` VALUES (1, 'เบาหวาน')";
			$insert = mysql_query($query_insert, $hos) or die(mysql_error());

			mysql_select_db($database_hos, $hos);
			$query_insert = "INSERT INTO ".$database_kohrx.".`kohrx_med_reconcile_disease_type` VALUES (2, 'ความดัน')";
			$insert = mysql_query($query_insert, $hos) or die(mysql_error());

			mysql_select_db($database_hos, $hos);
			$query_insert = "INSERT INTO ".$database_kohrx.".`kohrx_med_reconcile_disease_type` VALUES (3, 'ไขมัน')";
			$insert = mysql_query($query_insert, $hos) or die(mysql_error());
				
			mysql_select_db($database_hos, $hos);
			$query_insert = "INSERT INTO ".$database_kohrx.".`kohrx_med_reconcile_disease_type` VALUES (4, 'หัวใจ')";
			$insert = mysql_query($query_insert, $hos) or die(mysql_error());
				
			mysql_select_db($database_hos, $hos);
			$query_insert = "INSERT INTO ".$database_kohrx.".`kohrx_med_reconcile_disease_type` VALUES (5, 'หอบหืด')";
			$insert = mysql_query($query_insert, $hos) or die(mysql_error());

			mysql_select_db($database_hos, $hos);
			$query_insert = "INSERT INTO ".$database_kohrx.".`kohrx_med_reconcile_disease_type` VALUES (6, 'COPD')";
			$insert = mysql_query($query_insert, $hos) or die(mysql_error());

			mysql_select_db($database_hos, $hos);
			$query_insert = "INSERT INTO ".$database_kohrx.".`kohrx_med_reconcile_disease_type` VALUES (7, 'ไทรอยด์')";
			$insert = mysql_query($query_insert, $hos) or die(mysql_error());
				
			mysql_select_db($database_hos, $hos);
			$query_insert = "INSERT INTO ".$database_kohrx.".`kohrx_med_reconcile_disease_type` VALUES (8, 'จิตเวช')";
			$insert = mysql_query($query_insert, $hos) or die(mysql_error());

			mysql_select_db($database_hos, $hos);
			$query_insert = "INSERT INTO ".$database_kohrx.".`kohrx_med_reconcile_disease_type` VALUES (9, 'เก๊าท์')";
			$insert = mysql_query($query_insert, $hos) or die(mysql_error());

			mysql_select_db($database_hos, $hos);
			$query_insert = "INSERT INTO ".$database_kohrx.".`kohrx_med_reconcile_disease_type` VALUES (10, 'ลมชัก')";
			$insert = mysql_query($query_insert, $hos) or die(mysql_error());

			mysql_select_db($database_hos, $hos);
			$query_insert = "INSERT INTO ".$database_kohrx.".`kohrx_med_reconcile_disease_type` VALUES (11, 'ไต')";
			$insert = mysql_query($query_insert, $hos) or die(mysql_error());

			mysql_select_db($database_hos, $hos);
			$query_insert = "INSERT INTO ".$database_kohrx.".`kohrx_med_reconcile_disease_type` VALUES (12, 'โลหิตจาง')";
			$insert = mysql_query($query_insert, $hos) or die(mysql_error());
				
				$msg.="- สร้างตาราง kohrx_med_reconcile_disease_type เรียบร้อยแล้ว <br/>";
			}
		}	
mysql_free_result($check);
// ## เพิ่มฟิล์ด mr ใน kohrx_med_error_error_type
//======= 
$table="kohrx_med_error_error_type";
$feild="mr";
$type="varchar(1)";
$after="type_eng";

mysql_select_db($database_hos, $hos);
$query_check = "SELECT count(*) as ccolumn 
FROM information_schema.COLUMNS 
WHERE 
	TABLE_SCHEMA = '".$database_kohrx."' 
AND TABLE_NAME = '".$table."' 
AND COLUMN_NAME = '".$feild."'";
$check = mysql_query($query_check, $hos) or die(mysql_error());
$row_check = mysql_fetch_assoc($check);
$totalRows_check = mysql_num_rows($check);

		if($row_check['ccolumn']==0){
		mysql_select_db($database_hos, $hos);
		$query_alter = "ALTER TABLE ".$database_kohrx.".`".$table."`
ADD COLUMN `".$feild."` ".$type." DEFAULT NULL AFTER `".$after."`";
		//echo $query_alter;
		$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ ".$feild." ในตาราง ".$table." เรียบร้อยแล้ว <br/>";
		}	
mysql_free_result($check);

// ## เพิ่มฟิล์ด mr ใน kohrx_med_error_error_cause
//======= 
$table="kohrx_med_error_error_cause";
$feild="mr";
$type="varchar(1)";
$after="name";

mysql_select_db($database_hos, $hos);
$query_check = "SELECT count(*) as ccolumn 
FROM information_schema.COLUMNS 
WHERE 
	TABLE_SCHEMA = '".$database_kohrx."' 
AND TABLE_NAME = '".$table."' 
AND COLUMN_NAME = '".$feild."'";
$check = mysql_query($query_check, $hos) or die(mysql_error());
$row_check = mysql_fetch_assoc($check);
$totalRows_check = mysql_num_rows($check);

		if($row_check['ccolumn']==0){
		mysql_select_db($database_hos, $hos);
		$query_alter = "ALTER TABLE ".$database_kohrx.".`".$table."`
ADD COLUMN `".$feild."` ".$type." DEFAULT NULL AFTER `".$after."`";
		//echo $query_alter;
		$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ ".$feild." ในตาราง ".$table." เรียบร้อยแล้ว <br/>";
		}	
mysql_free_result($check);

// ## เพิ่มฟิล์ด mr ใน kohrx_med_error_error_sub_cause
//======= 
$table="kohrx_med_error_error_sub_cause";
$feild="mr";
$type="varchar(1)";
$after="sub_name";

mysql_select_db($database_hos, $hos);
$query_check = "SELECT count(*) as ccolumn 
FROM information_schema.COLUMNS 
WHERE 
	TABLE_SCHEMA = '".$database_kohrx."' 
AND TABLE_NAME = '".$table."' 
AND COLUMN_NAME = '".$feild."'";
$check = mysql_query($query_check, $hos) or die(mysql_error());
$row_check = mysql_fetch_assoc($check);
$totalRows_check = mysql_num_rows($check);

		if($row_check['ccolumn']==0){
		mysql_select_db($database_hos, $hos);
		$query_alter = "ALTER TABLE ".$database_kohrx.".`".$table."`
ADD COLUMN `".$feild."` ".$type." DEFAULT NULL AFTER `".$after."`";
		//echo $query_alter;
		$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ ".$feild." ในตาราง ".$table." เรียบร้อยแล้ว <br/>";
		}	
mysql_free_result($check);

// ## เพิ่มฟิล์ด drug_type ใน kohrx_med_reconcile_error
//======= 
$table="kohrx_med_reconcile_error";
$feild="drug_type";
$type="varchar(1)";
$after="reporter";

mysql_select_db($database_hos, $hos);
$query_check = "SELECT count(*) as ccolumn 
FROM information_schema.COLUMNS 
WHERE 
	TABLE_SCHEMA = '".$database_kohrx."' 
AND TABLE_NAME = '".$table."' 
AND COLUMN_NAME = '".$feild."'";
$check = mysql_query($query_check, $hos) or die(mysql_error());
$row_check = mysql_fetch_assoc($check);
$totalRows_check = mysql_num_rows($check);

		if($row_check['ccolumn']==0){
		mysql_select_db($database_hos, $hos);
		$query_alter = "ALTER TABLE ".$database_kohrx.".`".$table."`
ADD COLUMN `".$feild."` ".$type." DEFAULT NULL AFTER `".$after."`";
		//echo $query_alter;
		$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ ".$feild." ในตาราง ".$table." เรียบร้อยแล้ว <br/>";
		}	
mysql_free_result($check);

// ## เพิ่มฟิล์ด create_time ใน kohrx_med_reconcile
//======= 
$table="kohrx_med_reconcile";
$feild="create_time";
$type="time";
$after="vstdate2";

mysql_select_db($database_hos, $hos);
$query_check = "SELECT count(*) as ccolumn 
FROM information_schema.COLUMNS 
WHERE 
	TABLE_SCHEMA = '".$database_kohrx."' 
AND TABLE_NAME = '".$table."' 
AND COLUMN_NAME = '".$feild."'";
$check = mysql_query($query_check, $hos) or die(mysql_error());
$row_check = mysql_fetch_assoc($check);
$totalRows_check = mysql_num_rows($check);

		if($row_check['ccolumn']==0){
		mysql_select_db($database_hos, $hos);
		$query_alter = "ALTER TABLE ".$database_kohrx.".`".$table."`
ADD COLUMN `".$feild."` ".$type." DEFAULT NULL AFTER `".$after."`";
		//echo $query_alter;
		$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ ".$feild." ในตาราง ".$table." เรียบร้อยแล้ว <br/>";
		}	
mysql_free_result($check);
// ## เพิ่มฟิล์ด room_id ใน kohrx_med_error_report
//======= 
$table="kohrx_med_error_report";
$feild="room_id";
$type="varchar(1)";
$after="error_other";

mysql_select_db($database_hos, $hos);
$query_check = "SELECT count(*) as ccolumn 
FROM information_schema.COLUMNS 
WHERE 
	TABLE_SCHEMA = '".$database_kohrx."' 
AND TABLE_NAME = '".$table."' 
AND COLUMN_NAME = '".$feild."'";
$check = mysql_query($query_check, $hos) or die(mysql_error());
$row_check = mysql_fetch_assoc($check);
$totalRows_check = mysql_num_rows($check);

		if($row_check['ccolumn']==0){
		mysql_select_db($database_hos, $hos);
		$query_alter = "ALTER TABLE ".$database_kohrx.".`".$table."`
ADD COLUMN `".$feild."` ".$type." DEFAULT NULL AFTER `".$after."`";
		//echo $query_alter;
		$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ ".$feild." ในตาราง ".$table." เรียบร้อยแล้ว <br/>";
		}	
mysql_free_result($check);

// ## เพิ่มฟิล์ด room_id ใน kohrx_med_error_indiv2
//======= 
$table="kohrx_med_error_indiv2";
$feild="room_id";
$type="varchar(1)";
$after="time1";

mysql_select_db($database_hos, $hos);
$query_check = "SELECT count(*) as ccolumn 
FROM information_schema.COLUMNS 
WHERE 
	TABLE_SCHEMA = '".$database_kohrx."' 
AND TABLE_NAME = '".$table."' 
AND COLUMN_NAME = '".$feild."'";
$check = mysql_query($query_check, $hos) or die(mysql_error());
$row_check = mysql_fetch_assoc($check);
$totalRows_check = mysql_num_rows($check);

		if($row_check['ccolumn']==0){
		mysql_select_db($database_hos, $hos);
		$query_alter = "ALTER TABLE ".$database_kohrx.".`".$table."`
ADD COLUMN `".$feild."` ".$type." DEFAULT NULL AFTER `".$after."`";
		//echo $query_alter;
		$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ ".$feild." ในตาราง ".$table." เรียบร้อยแล้ว <br/>";
		}	
mysql_free_result($check);
// ## เพิ่มฟิล์ด an ใน kohrx_med_reconcile
//======= 
$table="kohrx_med_reconcile";
$feild="an";
$type="varchar(20)";
$after="hn";

mysql_select_db($database_hos, $hos);
$query_check = "SELECT count(*) as ccolumn 
FROM information_schema.COLUMNS 
WHERE 
	TABLE_SCHEMA = '".$database_kohrx."' 
AND TABLE_NAME = '".$table."' 
AND COLUMN_NAME = '".$feild."'";
$check = mysql_query($query_check, $hos) or die(mysql_error());
$row_check = mysql_fetch_assoc($check);
$totalRows_check = mysql_num_rows($check);

		if($row_check['ccolumn']==0){
		mysql_select_db($database_hos, $hos);
		$query_alter = "ALTER TABLE ".$database_kohrx.".`".$table."`
ADD COLUMN `".$feild."` ".$type." DEFAULT NULL AFTER `".$after."`";
		//echo $query_alter;
		$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ ".$feild." ในตาราง ".$table." เรียบร้อยแล้ว <br/>";
		}	
mysql_free_result($check);
 // ## สร้างตาราง kohrx_med_reconcile_medplan
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_med_reconcile_medplan'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "
			CREATE TABLE ".$database_kohrx.".`kohrx_med_reconcile_medplan` (
			  `icode` varchar(10) NOT NULL,
			  `drugusage` varchar(10) DEFAULT NULL,
			  `med_plan_type` int(1) DEFAULT NULL,
			  PRIMARY KEY (`icode`) USING BTREE
			) ENGINE=InnoDB DEFAULT CHARSET=tis620;";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
							
				$msg.="- สร้างตาราง kohrx_med_reconcile_medplan เรียบร้อยแล้ว <br/>";
			}
			
mysql_free_result($check);
 // ## สร้างตาราง kohrx_ipd_profile_check
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_ipd_profile_check'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "
			CREATE TABLE ".$database_kohrx.".`kohrx_ipd_profile_check` (
            `med_plan_number` int(11) NOT NULL,
            `hos_guid` varchar(200) NOT NULL,
			`order_date` date NOT NULL,
            `check_date` datetime NOT NULL,
            `check_qty` int(11) DEFAULT NULL,
            `check_staff` varchar(20) DEFAULT NULL,
			`check_type` int(1) DEFAULT NULL,
            PRIMARY KEY (`med_plan_number`,`hos_guid`,`check_date`,`order_date`)
            ) ENGINE=InnoDB DEFAULT CHARSET=tis620;";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
							
				$msg.="- สร้างตาราง kohrx_ipd_profile_check เรียบร้อยแล้ว <br/>";
			}
			
mysql_free_result($check);
 // ## สร้างตาราง kohrx_ipd_order_image
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_ipd_order_image'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "
			CREATE TABLE ".$database_kohrx.".`kohrx_ipd_order_image` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `an` varchar(10) DEFAULT NULL,
            `order_date` date DEFAULT NULL,
            `order_time` time DEFAULT NULL,
            `capture_date` datetime DEFAULT NULL,
            `image_name` varchar(250) DEFAULT NULL,
            `remark` varchar(255) DEFAULT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=164 DEFAULT CHARSET=tis620;";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
							
				$msg.="- สร้างตาราง kohrx_ipd_order_image เรียบร้อยแล้ว <br/>";
			}
			
mysql_free_result($check);

// ## เพิ่มฟิล์ด room_id ใน kohrx_med_reconcile
//======= 
$table="kohrx_med_reconcile";
$feild="med_plan_type";
$type="varchar(1)";
$after="last_dose";

mysql_select_db($database_hos, $hos);
$query_check = "SELECT count(*) as ccolumn 
FROM information_schema.COLUMNS 
WHERE 
	TABLE_SCHEMA = '".$database_kohrx."' 
AND TABLE_NAME = '".$table."' 
AND COLUMN_NAME = '".$feild."'";
$check = mysql_query($query_check, $hos) or die(mysql_error());
$row_check = mysql_fetch_assoc($check);
$totalRows_check = mysql_num_rows($check);

		if($row_check['ccolumn']==0){
		mysql_select_db($database_hos, $hos);
		$query_alter = "ALTER TABLE ".$database_kohrx.".`".$table."`
ADD COLUMN `".$feild."` ".$type." DEFAULT NULL AFTER `".$after."`";
		//echo $query_alter;
		$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ ".$feild." ในตาราง ".$table." เรียบร้อยแล้ว <br/>";
		}	
mysql_free_result($check);
// ## เพิ่มฟิล์ด an ใน kohrx_med_reconcile
//======= 
$table="kohrx_med_reconcile";
$feild="an";
$type="varchar(20)";
$after="hn";

mysql_select_db($database_hos, $hos);
$query_check = "SELECT count(*) as ccolumn 
FROM information_schema.COLUMNS 
WHERE 
	TABLE_SCHEMA = '".$database_kohrx."' 
AND TABLE_NAME = '".$table."' 
AND COLUMN_NAME = '".$feild."'";
$check = mysql_query($query_check, $hos) or die(mysql_error());
$row_check = mysql_fetch_assoc($check);
$totalRows_check = mysql_num_rows($check);

		if($row_check['ccolumn']==0){
		mysql_select_db($database_hos, $hos);
		$query_alter = "ALTER TABLE ".$database_kohrx.".`".$table."`
ADD COLUMN `".$feild."` ".$type." DEFAULT NULL AFTER `".$after."`";
		//echo $query_alter;
		$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ ".$feild." ในตาราง ".$table." เรียบร้อยแล้ว <br/>";
		}	
mysql_free_result($check);

/////////////////////////////////////////////////////

// ## เพิ่มฟิล์ด checked ใน kohrx_ipd_order_image
//======= 
$table="kohrx_ipd_order_image";
$feild="checked";
$type="varchar(1)";
$after="remark";

mysql_select_db($database_hos, $hos);
$query_check = "SELECT count(*) as ccolumn 
FROM information_schema.COLUMNS 
WHERE 
	TABLE_SCHEMA = '".$database_kohrx."' 
AND TABLE_NAME = '".$table."' 
AND COLUMN_NAME = '".$feild."'";
$check = mysql_query($query_check, $hos) or die(mysql_error());
$row_check = mysql_fetch_assoc($check);
$totalRows_check = mysql_num_rows($check);

		if($row_check['ccolumn']==0){
		mysql_select_db($database_hos, $hos);
		$query_alter = "ALTER TABLE ".$database_kohrx.".`".$table."`
ADD COLUMN `".$feild."` ".$type." DEFAULT NULL AFTER `".$after."`";
		//echo $query_alter;
		$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ ".$feild." ในตาราง ".$table." เรียบร้อยแล้ว <br/>";
		}	
mysql_free_result($check);

/////////////////////////////////////////////////////
// ## เพิ่มฟิล์ด checked_date ใน kohrx_ipd_order_image
//======= 
$table="kohrx_ipd_order_image";
$feild="checked_date";
$type="datetime";
$after="checked";

mysql_select_db($database_hos, $hos);
$query_check = "SELECT count(*) as ccolumn 
FROM information_schema.COLUMNS 
WHERE 
	TABLE_SCHEMA = '".$database_kohrx."' 
AND TABLE_NAME = '".$table."' 
AND COLUMN_NAME = '".$feild."'";
$check = mysql_query($query_check, $hos) or die(mysql_error());
$row_check = mysql_fetch_assoc($check);
$totalRows_check = mysql_num_rows($check);

		if($row_check['ccolumn']==0){
		mysql_select_db($database_hos, $hos);
		$query_alter = "ALTER TABLE ".$database_kohrx.".`".$table."`
ADD COLUMN `".$feild."` ".$type." DEFAULT NULL AFTER `".$after."`";
		//echo $query_alter;
		$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ ".$feild." ในตาราง ".$table." เรียบร้อยแล้ว <br/>";
		}	
mysql_free_result($check);

/////////////////////////////////////////////////////
// ## เพิ่มฟิล์ด ip_addr ใน kohrx_ipd_order_image
//======= 
$table="kohrx_ipd_order_image";
$feild="ip_addr";
$type="varchar(1)";
$after="remark";

mysql_select_db($database_hos, $hos);
$query_check = "SELECT count(*) as ccolumn 
FROM information_schema.COLUMNS 
WHERE 
	TABLE_SCHEMA = '".$database_kohrx."' 
AND TABLE_NAME = '".$table."' 
AND COLUMN_NAME = '".$feild."'";
$check = mysql_query($query_check, $hos) or die(mysql_error());
$row_check = mysql_fetch_assoc($check);
$totalRows_check = mysql_num_rows($check);

		if($row_check['ccolumn']==0){
		mysql_select_db($database_hos, $hos);
		$query_alter = "ALTER TABLE ".$database_kohrx.".`".$table."`
ADD COLUMN `".$feild."` ".$type." DEFAULT NULL AFTER `".$after."`";
		//echo $query_alter;
		$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ ".$feild." ในตาราง ".$table." เรียบร้อยแล้ว <br/>";
		}	
mysql_free_result($check);

/////////////////////////////////////////////////////
// ## เพิ่มฟิล์ด pt_type ใน kohrx_ipd_order_image
//======= 
$table="kohrx_ipd_order_image";
$feild="pt_type";
$type="varchar(1)";
$after="ip_addr";

mysql_select_db($database_hos, $hos);
$query_check = "SELECT count(*) as ccolumn 
FROM information_schema.COLUMNS 
WHERE 
	TABLE_SCHEMA = '".$database_kohrx."' 
AND TABLE_NAME = '".$table."' 
AND COLUMN_NAME = '".$feild."'";
$check = mysql_query($query_check, $hos) or die(mysql_error());
$row_check = mysql_fetch_assoc($check);
$totalRows_check = mysql_num_rows($check);

		if($row_check['ccolumn']==0){
		mysql_select_db($database_hos, $hos);
		$query_alter = "ALTER TABLE ".$database_kohrx.".`".$table."`
ADD COLUMN `".$feild."` ".$type." DEFAULT NULL AFTER `".$after."`";
		//echo $query_alter;
		$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ ".$feild." ในตาราง ".$table." เรียบร้อยแล้ว <br/>";
		}	
mysql_free_result($check);

/////////////////////////////////////////////////////

// ## เพิ่มฟิล์ด d_update ใน kohrx_med_error_report_drug
//======= 
$table="kohrx_med_error_report_drug";
$feild="d_update";
$type="datetime";
$after="stamp";

mysql_select_db($database_hos, $hos);
$query_check = "SELECT count(*) as ccolumn 
FROM information_schema.COLUMNS 
WHERE 
	TABLE_SCHEMA = '".$database_kohrx."' 
AND TABLE_NAME = '".$table."' 
AND COLUMN_NAME = '".$feild."'";
$check = mysql_query($query_check, $hos) or die(mysql_error());
$row_check = mysql_fetch_assoc($check);
$totalRows_check = mysql_num_rows($check);

		if($row_check['ccolumn']==0){
		mysql_select_db($database_hos, $hos);
		$query_alter = "ALTER TABLE ".$database_kohrx.".`".$table."`
ADD COLUMN `".$feild."` ".$type." DEFAULT NULL AFTER `".$after."`";
		//echo $query_alter;
		$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ ".$feild." ในตาราง ".$table." เรียบร้อยแล้ว <br/>";
		}	
mysql_free_result($check);
/////////////////////////////////////////////////////
// ## เพิ่มฟิล์ด hos_guild ใน kohrx_ipd_profile_check
//======= 
$table="kohrx_ipd_profile_check";
$feild="hos_guid";
$type="varchar(200)";
$after="med_plan_number";

mysql_select_db($database_hos, $hos);
$query_check = "SELECT count(*) as ccolumn 
FROM information_schema.COLUMNS 
WHERE 
	TABLE_SCHEMA = '".$database_kohrx."' 
AND TABLE_NAME = '".$table."' 
AND COLUMN_NAME = '".$feild."'";
$check = mysql_query($query_check, $hos) or die(mysql_error());
$row_check = mysql_fetch_assoc($check);
$totalRows_check = mysql_num_rows($check);

		if($row_check['ccolumn']==0){
		mysql_select_db($database_hos, $hos);
		$query_alter = "ALTER TABLE ".$database_kohrx.".`".$table."`
ADD COLUMN `".$feild."` ".$type." DEFAULT NULL AFTER `".$after."`";
		//echo $query_alter;
		$alter = mysql_query($query_alter, $hos) or die(mysql_error());
		
		mysql_select_db($database_hos, $hos);
		$query_alter = "ALTER TABLE ".$database_kohrx.".`".$table."`
ADD PRIMARY KEY (`".$feild."`)";
		//echo $query_alter;
		$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ ".$feild." ในตาราง ".$table." เรียบร้อยแล้ว <br/>";
		}	
mysql_free_result($check);

/////////////////////////////////////////////////////
 // ## สร้างตาราง kohrx_favipiravir_use
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_favipiravir_use'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "
			CREATE TABLE ".$database_kohrx.".`kohrx_favipiravir_use` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `hn` varchar(9) DEFAULT NULL,
			  `use_date` date DEFAULT NULL,
			  `use_time` time DEFAULT NULL,
			  `qty` int(11) DEFAULT NULL,
			  `in_out_method` int(11) DEFAULT NULL,
			  `doctor` varchar(10) DEFAULT NULL,
  			  `remark` varchar(255) DEFAULT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=tis620;";

			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
							
				$msg.="- สร้างตาราง kohrx_favipiravir_use เรียบร้อยแล้ว <br/>";
			}
			
mysql_free_result($check);

////////////////////////////////////////////////////
 // ## สร้างตาราง kohrx_label_icon_patient
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_label_icon_patient'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "
				CREATE TABLE ".$database_kohrx.".`kohrx_label_icon_patient` (
			      `hn` varchar(255) NOT NULL,
				  `label_id` int(11) NOT NULL,
				  `label_comment` varchar(20) DEFAULT NULL,
				  `doctor` varchar(10) DEFAULT NULL,
				  PRIMARY KEY (`hn`,`label_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=tis620;";

			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
							
				$msg.="- สร้างตาราง kohrx_label_icon_patient เรียบร้อยแล้ว <br/>";
			}
			
mysql_free_result($check);

////////////////////////////////////////////////////
 // ## สร้างตาราง kohrx_label_icon
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_label_icon'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "
				CREATE TABLE ".$database_kohrx.".`kohrx_label_icon` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `icon` varchar(50) DEFAULT NULL,
				  `icon_html` varchar(50) DEFAULT NULL,
				  `icon_name` varchar(50) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=tis620;";

			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
							
				$msg.="- สร้างตาราง kohrx_label_icon เรียบร้อยแล้ว <br/>";
			}
			
mysql_free_result($check);

////////////////////////////////////////////////////
// ## สร้างตาราง kohrx_label_icon_list
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_label_icon_list'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "
				CREATE TABLE ".$database_kohrx.".`kohrx_label_icon_list` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `label_icon_id` int(11) DEFAULT NULL,
				  `label_name` varchar(20) DEFAULT NULL,
				  `label_color` varchar(10) DEFAULT NULL,
				   PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=tis620;";

			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
							
				$msg.="- สร้างตาราง kohrx_label_icon_list เรียบร้อยแล้ว <br/>";
			}
			
mysql_free_result($check);

////////////////////////////////////////////////////
// ## สร้างตาราง kohrx_drug_trigger
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_drug_trigger'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "
			CREATE TABLE ".$database_kohrx.".`kohrx_drug_trigger` (
			  `icode` varchar(7) NOT NULL,
			  `drug_prefix` varchar(5) DEFAULT NULL,
			  `trigger_color` varchar(20) DEFAULT NULL,
			  PRIMARY KEY (`icode`)
			) ENGINE=InnoDB DEFAULT CHARSET=tis620;";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());							
			$msg.="- สร้างตาราง kohrx_drug_trigger เรียบร้อยแล้ว <br/>";
			}
			
mysql_free_result($check);

////////////////////////////////////////////////////
// ## สร้างตาราง kohrx_had_use
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_had_use'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "
			CREATE TABLE ".$database_kohrx.".`kohrx_had_use` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `icode` varchar(10) DEFAULT NULL,
			  `hn` varchar(9) DEFAULT NULL,
			  `use_date` date DEFAULT NULL,
			  `use_time` time DEFAULT NULL,
			  `qty` int(11) DEFAULT NULL,
			  `in_out_method` int(11) DEFAULT NULL,
			  `doctor` varchar(10) DEFAULT NULL,
			  `department` varchar(10) DEFAULT NULL,
			  `remark` varchar(255) DEFAULT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=tis620;";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());							
			$msg.="- สร้างตาราง kohrx_had_use เรียบร้อยแล้ว <br/>";
			}
			
mysql_free_result($check);

// ## เพิ่มฟิล์ด caller_default ใน kohrx_queue_caller_channel
//======= 
$table="kohrx_queue_caller_channel";
$feild="caller_default";
$type="char(1)";
$after="detail_drug_sound";

mysql_select_db($database_hos, $hos);
$query_check = "SELECT count(*) as ccolumn 
FROM information_schema.COLUMNS 
WHERE 
	TABLE_SCHEMA = '".$database_kohrx."' 
AND TABLE_NAME = '".$table."' 
AND COLUMN_NAME = '".$feild."'";
$check = mysql_query($query_check, $hos) or die(mysql_error());
$row_check = mysql_fetch_assoc($check);
$totalRows_check = mysql_num_rows($check);

		if($row_check['ccolumn']==0){
		mysql_select_db($database_hos, $hos);
		$query_alter = "ALTER TABLE ".$database_kohrx.".`".$table."`
ADD COLUMN `".$feild."` ".$type." DEFAULT NULL AFTER `".$after."`";
		//echo $query_alter;
		$alter = mysql_query($query_alter, $hos) or die(mysql_error());

$msg.="- เพิ่มฟิลด์ ".$feild." ในตาราง ".$table." เรียบร้อยแล้ว <br/>";
		}	
mysql_free_result($check);
 // ## สร้างตาราง kohrx_dispen_note_template
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "SELECT count(*) as ccolumn 
	FROM information_schema.COLUMNS 
	WHERE TABLE_SCHEMA = '".$database_kohrx."' 
	AND TABLE_NAME = 'kohrx_dispen_note_template'";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
			if($row_check['ccolumn']==0){
			mysql_select_db($database_hos, $hos);
			$query_alter = "
			CREATE TABLE ".$database_kohrx.".`kohrx_dispen_note_template` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `note` char(100) DEFAULT NULL,
			  `doctorcode` char(10) DEFAULT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=tis620;";
			$alter = mysql_query($query_alter, $hos) or die(mysql_error());
							
				$msg.="- สร้างตาราง kohrx_dispen_note_template เรียบร้อยแล้ว <br/>";
			}
			
mysql_free_result($check);

////////////////////////////////////////////////////

		mysql_select_db($database_hos, $hos);
		$query_update = "update ".$database_kohrx.".kohrx_dispensing_setting set value='Y' where name='upgrade_structure'";
		$update = mysql_query($query_update, $hos) or die(mysql_error());

		if($update){
			$myfile = fopen("version.txt", "r+") or die("กรุณากำหนด permission ไฟล์ version.txt เป็น 777 ด้วยครับ");		
			$version= explode('|',fgets($myfile));
			if($version[3]==""){
				$txt = "|Y|";
				fwrite($myfile, $txt);
			}
		}
		fclose($myfile);
if($alter){
	$msg.="";
	}
/////////////////////////////////

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php include('java_css_online.php'); ?>
<!-- kohrx -->
<link rel="stylesheet" href="include/kohrx/css/kohrx.css"/>

<style type="text/css">
body {
	margin-left: 20px;
	margin-top: 20px;
	margin-right: 20px;
	margin-bottom: 20px;
}
</style>
</head>

<body  >
<?php if($msg){ ?>
<div class="alert alert-success" role="alert">
<h4 class="card-title">upgrade structure เสร็จเรียบร้อยแล้ว</h4>
</div>
<div class="card">
	<div class="card-body bg-light"><?php echo $msg; ?></div>
</div>
<?php } else { ?>
<div class="alert alert-success" role="alert">
<h4 class="card-title">ไม่มีปรับปรุงตารางเพิ่มเติม</h4>
</div>
<?php
}?>
</body>
</html>