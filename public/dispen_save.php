<?php ob_start();?>
<?php session_start();?>
<?php require_once('Connections/hos.php'); ?>
<?php

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


$get_ip=$_SERVER["REMOTE_ADDR"];
// check การ login
mysql_select_db($database_hos, $hos);
$query_delete = "delete from ".$database_kohrx.".kohrx_login_check where last_time < CURDATE()";
$delete = mysql_query($query_delete, $hos) or die(mysql_error());

if($_SESSION["username_log"]==""){
header("location: login.php"); //ไม่ถูกต้องให้กับไปหน้าเดิม
}
else{
mysql_select_db($database_hos, $hos);
$query_login_check = "SELECT format((TIMESTAMPDIFF(SECOND,last_time, NOW())/60),2) as timediff from ".$database_kohrx.".kohrx_login_check where login_name='".$_SESSION["username_log"]."' and ipaddress='".$get_ip."'";
$login_check = mysql_query($query_login_check, $hos) or die(mysql_error());
$row_login_check = mysql_fetch_assoc($login_check);
$totalRows_login_check = mysql_num_rows($login_check);

if($totalRows_login_check<>0){
//ถ้าเกิน 1 ชั่วโมงให้ logout
if($row_login_check['timediff']>=60){
echo "<meta http-equiv=\"refresh\" content=\"0\" />";
	}
}
else{
	echo "<meta http-equiv=\"refresh\" content=\"0\" />";
	}
mysql_free_result($login_check);
}

$drug_vn=$_GET['vn'];
$rx_print=$_GET['rx_print'];

//ค้นหา hn
mysql_select_db($database_hos, $hos);
$query_get_hn = "select hn,vstdate from vn_stat where vn='".$drug_vn."'";
$get_hn = mysql_query($query_get_hn, $hos) or die(mysql_error());
$row_get_hn = mysql_fetch_assoc($get_hn);
$totalRows_get_hn = mysql_num_rows($get_hn);

	$hn=$row_get_hn['hn'];
	$vstdate=$row_get_hn['vstdate'];

mysql_free_result($get_hn);

if(isset($_GET['notime'])&&$_GET['notime']=="Y"){
	$note="'ไม่คิดเวลา'";
    $note2="\'ไม่คิดเวลา\'";
}
else {
    if($_GET['note']!=""){
        $note="'".$_GET['note']."'";
        $note2="\'".$_GET['note']."\'";
    }
    else {
        $note="NULL";
        $note2="NULL";
    }
}
//ค้นหา id ของ rx_operator_log_id ของ serial
mysql_select_db($database_hos, $hos);
$query_rs_serial = "select count(*) as cc from serial where name='rx_operator_log_id' ";
$rs_serial = mysql_query($query_rs_serial, $hos) or die(mysql_error());
$row_rs_serial = mysql_fetch_assoc($rs_serial);
$totalRows_rs_serial = mysql_num_rows($rs_serial);

//get_serial
mysql_select_db($database_hos, $hos);
$query_rs_get_serial = "select get_serialnumber('rx_operator_log_id') as cc ";
$rs_get_serial = mysql_query($query_rs_get_serial, $hos) or die(mysql_error());
$row_rs_get_serial = mysql_fetch_assoc($rs_get_serial);
$totalRows_rs_get_serial = mysql_num_rows($rs_get_serial);
$rx_operator_log_id=$row_rs_get_serial['cc'];


//update serial ใน replicate_log
mysql_select_db($database_hos, $hos);
$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update serial set serial_no = \'".$row_rs_get_serial['cc']."\' where name =\'rx_operator_log_id\'')

";
$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());

//ค้นหาว่ามีการบันทึกใน rx_operator รืยัง
mysql_select_db($database_hos, $hos);
$query_rs_search1 = "select rx_operator_id from rx_operator where vn='".$drug_vn."'";
$rs_search1 = mysql_query($query_rs_search1, $hos) or die(mysql_error());
$row_rs_search1 = mysql_fetch_assoc($rs_search1);
$totalRows_rs_search1 = mysql_num_rows($rs_search1);
//==========เก็บตัวแปล rx_operator_id===============//
$rx_operator_id=$row_rs_search1['rx_operator_id'];
//===============================================//
//เก็บค่าเวลาปัจจบัน
mysql_select_db($database_hos, $hos);
$query_rx_timenow = "select time(now())as Timenow";
$rx_timenow = mysql_query($query_rx_timenow, $hos) or die(mysql_error());
$row_rx_timenow = mysql_fetch_assoc($rx_timenow);
$totalRows_rx_timenow = mysql_num_rows($rx_timenow);
if($totalRows_rs_search1==0){
//ถ้ายังไม่มีการบันทึก กรณีรายใหม่
//เก็บค่า rx_operator_id
mysql_select_db($database_hos, $hos);
$query_rs_get_serial = "select get_serialnumber('rx_operator_id') as cc";
$rs_get_serial = mysql_query($query_rs_get_serial, $hos) or die(mysql_error());
$row_rs_get_serial = mysql_fetch_assoc($rs_get_serial);
$totalRows_rs_get_serial = mysql_num_rows($rs_get_serial);
	
//insert rx_operator_id ใน replicate_log
mysql_select_db($database_hos, $hos);
$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update serial set serial_no =  \'".$row_rs_get_serial['cc']."\' where name =\'rx_operator_id\'')
";
$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());

//บันทึกข้อมูลใน rx_operator
if($_GET['respondent2']!=""){
	$receiver="'".$_GET['respondent2']."'";
	$receiver2="\'".$_GET['respondent2']."\'";
	}
else{ $receiver="NULL";}
if($_GET['respondent2_other']!=""){
	$receiver_other="'".$_GET['respondent2_other']."'";
	$receiver_other2="\'".$_GET['respondent2_other']."\'";
	}
else{	$receiver_other="NULL";}

if(isset($_GET['lock'])&&($_GET['lock']=="Y")){
	$lock="'Y'";
	}
else{
	$lock="NULL";
	}


mysql_select_db($database_hos, $hos);
$insert_rx = "INSERT INTO rx_operator (rx_operator_id,vn,lock_order,login,rx_time,pay,rx_print,print_staff,check_staff,pay_staff,print_count,confirm_staff,note,prepare_rx,order_date,prepare_staff,prepare_date_time,print_rcpt,allow_rx_edit,rx_depcode,pay_depcode,print_depcode,call_time,call_pay,hos_guid,receiver,receiver_other) VALUES ('".$row_rs_get_serial['cc']."','".$drug_vn."','".$lock."','','".$row_rx_timenow['Timenow']."','Y',NULL,'".$rx_print."','".$_GET['prepare']."','".$_GET['dispen']."',NULL,'".$_GET['check']."',".$note.",NULL,NULL,NULL,NULL,NULL,NULL,NULL,'".$_GET['depcode1']."',NULL,NULL,NULL,NULL,".$receiver.",".$receiver_other.")
";
$rs_insert_rx = mysql_query($insert_rx, $hos) or die(mysql_error());

// เขียนข้อมูล การบันทึก rx_operator ใน replicate_log
mysql_select_db($database_hos, $hos);
$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','INSERT INTO rx_operator (rx_operator_id,vn,lock_order,login,rx_time,pay,rx_print,print_staff,check_staff,pay_staff,print_count,confirm_staff,note,prepare_rx,order_date,prepare_staff,prepare_date_time,print_rcpt,allow_rx_edit,rx_depcode,pay_depcode,print_depcode,call_time,call_pay,hos_guid,receiver,receiver_other) VALUES (\'".$row_rs_get_serial['cc']."\',\'".$drug_vn."\',\'".$lock."\',\'\',\'".$row_rx_timenow['Timenow']."\',\'Y\',NULL,\'".$rx_print."\',\'".$_GET['prepare']."\',\'".$_GET['dispen']."\',NULL,\'".$_GET['check']."\',".$note2.",NULL,NULL,NULL,NULL,NULL,NULL,NULL,\'".$_GET['depcode1']."\',NULL,NULL,NULL,NULL,".$receiver2.",".$receiver_other2.")')
";
$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());

//ค้นหาใน rx_stat
mysql_select_db($database_hos, $hos);
$query_rx_stat = "select vn from rx_stat where vn='".$drug_vn."'";
$rx_stat = mysql_query($query_rx_stat, $hos) or die(mysql_error());
$row_rx_stat = mysql_fetch_assoc($rx_stat);
$totalRows_rx_stat = mysql_num_rows($rx_stat);

if($totalRows_rx_stat==0){
	mysql_select_db($database_hos, $hos);
	$insert_rx = "INSERT INTO rx_stat (vn,dispense_staff,dispense_datetime) values('".$drug_vn."','".$_SESSION["username_log"]."',NOW())";
	$rs_insert_rx = mysql_query($insert_rx, $hos) or die(mysql_error());

// เขียนข้อมูล การบันทึก rx_stat ใน replicate_log
mysql_select_db($database_hos, $hos);
$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','INSERT INTO rx_stat (vn,dispense_staff,dispense_datetime) values(\'".$drug_vn."\',\'".$_SESSION["username_log"]."\',NOW())')";
$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());
		
	}
else{
	mysql_select_db($database_hos, $hos);
	$insert_rx = "update rx_stat set dispense_staff='".$_SESSION["username_log"]."',dispense_datetime=NOW() where vn='".$drug_vn."'";
	$rs_insert_rx = mysql_query($insert_rx, $hos) or die(mysql_error());	

// เขียนข้อมูล การบันทึก rx_stat ใน replicate_log
mysql_select_db($database_hos, $hos);
$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update rx_stat set dispense_staff=\'".$_SESSION["username_log"]."\',dispense_datetime=NOW() where vn=\'".$drug_vn."\'')";
$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());

	}

mysql_free_result($rx_stat);

// ค้นหา room_id
$get_ip=$_SERVER["REMOTE_ADDR"];

mysql_select_db($database_hos, $hos);
$query_channel = "SELECT q.room_id,r.room_name,kskdepart from ".$database_kohrx.".kohrx_queue_caller_channel q left outer join ".$database_kohrx.".kohrx_queue_caller_room r on r.id=q.room_id WHERE ip='".$get_ip."'";
$rs_channel = mysql_query($query_channel, $hos) or die(mysql_error());
$row_rs_channel = mysql_fetch_assoc($rs_channel);
$totalRows_rs_channel = mysql_num_rows($rs_channel);


// บันทึกใน kohrx_dispen_staff_operation
mysql_select_db($database_hos, $hos);
$insert_rx = "INSERT INTO ".$database_kohrx.".kohrx_dispen_staff_operation (print_staff,vn,room_id) VALUES ('".$rx_print."','".$drug_vn."','".$row_rs_channel['room_id']."')
";
$rs_insert_rx = mysql_query($insert_rx, $hos) or die(mysql_error());

//บันทึกข้อมูการบันทึก kohrx_dispen_staff_operation ใน replicate_log
mysql_select_db($database_hos, $hos);
$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','INSERT INTO ".$database_kohrx.".kohrx_dispen_staff_operation (print_staff,vn,room_id) VALUES (\'".$rx_print."\',\'".$drug_vn."\',\'".$row_rs_channel['room_id']."\')')";
$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());

mysql_free_result($rs_channel);

//บันทึกใน kohrx_queued
mysql_select_db($database_hos, $hos);
$update_queued = "update ".$database_kohrx.".kohrx_queued set payed='Y' where hn='".$hn."' and room_id='".$row_rs_channel['room_id']."' and substr(queue_datetime,1,10)='".$vstdate."'";
$rs_update_queued = mysql_query($update_queued, $hos) or die(mysql_error());

//บันทึกข้อมูลใน rx_operator_log
mysql_select_db($database_hos, $hos);
$insert_rx = "INSERT INTO rx_operator_log (log_datetime,vn,log_staff,check_staff,pay_staff,confirm_staff,prepare_staff,log_type,entry_staff,rx_operator_log_id) VALUES (current_timestamp,'".$drug_vn."',NULL,'".$_GET['prepare']."','".$_GET['dispen']."','".$_GET['check']."',NULL,'New','".$_GET['user1']."','".$rx_operator_log_id."')

";
$rs_insert_rx = mysql_query($insert_rx, $hos) or die(mysql_error());



//บันทึกข้อมูการบันทึก rx_operator_log ใน replicate_log
mysql_select_db($database_hos, $hos);
$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','INSERT INTO rx_operator_log (log_datetime,vn,log_staff,check_staff,pay_staff,confirm_staff,prepare_staff,log_type,entry_staff,rx_operator_log_id) VALUES (current_timestamp,\'".$drug_vn."\',NULL,\'".$_GET['prepare']."\',\'".$_GET['dispen']."\',\'".$_GET['check']."\',NULL,\'New\',\'".$_GET['user1']."\',\'".$rx_operator_log_id."\')
')";
$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());

mysql_free_result($rs_get_serial);
//เสร็จสิ้นกรณีบันทึกข้อมูลใหม่
}

//=====================================================//
//===========ถ้ามีการบันทึกแล้ว=============================//
//====================================================//
if($totalRows_rs_search1<>0){

if($_GET['respondent2']!=""){
	$receiver="'".$_GET['respondent2']."'";
	$receiver2="\'".$_GET['respondent2']."\'";
	}
else{ $receiver="NULL";}
if($_GET['respondent2_other']!=""){
	$receiver_other="'".$_GET['respondent2_other']."'";
	$receiver_other2="\'".$_GEt['respondent2_other']."\'";
	}
else{	$receiver_other="NULL";}

if(isset($_GET['lock'])&&($_GET['lock']=="Y")){
	$lock="'Y'";
	}
else{
	$lock="NULL";
	}

//ถ้ามีการบันทึกแล้ว
	//บันทึกข้อมูลใน rx_operator
	mysql_select_db($database_hos, $hos);
	$insert_rx = "UPDATE rx_operator set print_staff='".$rx_print."',lock_order=".$lock.",rx_time='".$row_rx_timenow['Timenow']."',check_staff='".$_GET['prepare']."',pay_staff='".$_GET['dispen']."',confirm_staff='".$_GET['check']."',note=".$note.",pay_depcode='".$_GET['depcode1']."',pay='Y',receiver=".$receiver.",receiver_other=".$receiver_other." where rx_operator_id='".$rx_operator_id."' and vn='".$drug_vn."'";
	$rs_insert_rx = mysql_query($insert_rx, $hos) or die(mysql_error());

// เขียนข้อมูล การบันทึก rx_operator ใน replicate_log
mysql_select_db($database_hos, $hos);
$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','UPDATE rx_operator set print_staff=\'".$rx_print."\',rx_time=\'".$row_rx_timenow['Timenow']."\',check_staff=\'".$_GET['prepare']."\',pay_staff=\'".$_GET['dispen']."\',confirm_staff=\'".$_GET['check']."\',pay=\'Y\',note=".$note2.",pay_depcode=\'".$_GET['depcode1']."\',receiver=".$receiver2.",receiver_other=".$receiver_other2." where rx_operator_id=\'".$rx_operator_id."\' and vn=\'".$drug_vn."\'')
";
$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());

//ค้นหาใน rx_stat
mysql_select_db($database_hos, $hos);
$query_rx_stat = "select vn from rx_stat where vn='".$drug_vn."'";
$rx_stat = mysql_query($query_rx_stat, $hos) or die(mysql_error());
$row_rx_stat = mysql_fetch_assoc($rx_stat);
$totalRows_rx_stat = mysql_num_rows($rx_stat);

if($totalRows_rx_stat==0){
	mysql_select_db($database_hos, $hos);
	$insert_rx = "INSERT INTO rx_stat (vn,dispense_staff,dispense_datetime) values('".$drug_vn."','".$_SESSION["username_log"]."',NOW())";
	$rs_insert_rx = mysql_query($insert_rx, $hos) or die(mysql_error());

// เขียนข้อมูล การบันทึก rx_stat ใน replicate_log
mysql_select_db($database_hos, $hos);
$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','INSERT INTO rx_stat (vn,dispense_staff,dispense_datetime) values(\'".$drug_vn."\',\'".$_SESSION["username_log"]."\',NOW())')";
$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());
		
	}
else{
	mysql_select_db($database_hos, $hos);
	$insert_rx = "update rx_stat set dispense_staff='".$_SESSION["username_log"]."',dispense_datetime=NOW() where vn='".$drug_vn."'";
	$rs_insert_rx = mysql_query($insert_rx, $hos) or die(mysql_error());	

// เขียนข้อมูล การบันทึก rx_stat ใน replicate_log
mysql_select_db($database_hos, $hos);
$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update rx_stat set dispense_staff=\'".$_SESSION["username_log"]."\',dispense_datetime=NOW() where vn=\'".$drug_vn."\'')";
$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());

	}

mysql_free_result($rx_stat);

////////// สิ้นสุดการค้นหา rx_stat ////////////////

$get_ip=$_SERVER["REMOTE_ADDR"];

mysql_select_db($database_hos, $hos);
$query_channel = "SELECT q.room_id,r.room_name,kskdepart from ".$database_kohrx.".kohrx_queue_caller_channel q left outer join ".$database_kohrx.".kohrx_queue_caller_room r on r.id=q.room_id WHERE ip='".$get_ip."'";
$rs_channel = mysql_query($query_channel, $hos) or die(mysql_error());
$row_rs_channel = mysql_fetch_assoc($rs_channel);
$totalRows_rs_channel = mysql_num_rows($rs_channel);

//บันทึกใน kohrx_queued
mysql_select_db($database_hos, $hos);
$update_queued = "update ".$database_kohrx.".kohrx_queued set payed='Y' where hn='".$hn."' and room_id='".$row_rs_channel['room_id']."' and substr(queue_datetime,1,10)='".$vstdate."'";
$rs_update_queued = mysql_query($update_queued, $hos) or die(mysql_error());


mysql_select_db($database_hos, $hos);
$query_search_rx = "SELECT id from ".$database_kohrx.".kohrx_dispen_staff_operation WHERE vn='".$drug_vn."' and print_staff='".$rx_print."' and room_id='".$row_rs_channel['room_id']."'";
$search_rx = mysql_query($query_search_rx, $hos) or die(mysql_error());
$row_search_rx = mysql_fetch_assoc($search_rx);
$totalRows_search_rx = mysql_num_rows($search_rx);

if($totalRows_search_rx==0){
// บันทึกใน kohrx_dispen_staff_operation
mysql_select_db($database_hos, $hos);
$insert_rx = "INSERT INTO ".$database_kohrx.".kohrx_dispen_staff_operation (print_staff,vn,room_id) VALUES ('".$rx_print."','".$drug_vn."','".$row_rs_channel['room_id']."')
";
$rs_insert_rx = mysql_query($insert_rx, $hos) or die(mysql_error());

//บันทึกข้อมูการบันทึก kohrx_dispen_staff_operation ใน replicate_log
mysql_select_db($database_hos, $hos);
$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','INSERT INTO ".$database_kohrx.".kohrx_dispen_staff_operation (print_staff,vn,room_id) VALUES (\'".$rx_print."\',\'".$drug_vn."\',\'".$row_rs_channel['room_id']."\')')";
$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());

//update แผนกที่ส่งไปล่าสุด
mysql_select_db($database_hos, $hos);
$insert_rx = "UPDATE ".$database_kohrx.".kohrx_queue_caller_channel SET outdepcode='".$_GET['cur_dep']."' where ip='".$get_ip."' ";
$rs_insert_rx = mysql_query($insert_rx, $hos) or die(mysql_error());

//update ptdepart
mysql_select_db($database_hos, $hos);
$query_ptdepart = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','UPDATE ".$database_kohrx.".kohrx_queue_caller_channel SET outdepcode=\'".$_GET['cur_dep']."\' where ip=\'".$get_ip."\'')";
$ptdepart = mysql_query($query_ptdepart, $hos) or die(mysql_error());

//ค้นหา ptdepart
mysql_select_db($database_hos, $hos);
$query_get_depart = "select d.* from ptdepart d where vn='".$drug_vn."' and depcode='".$row_rs_channel['kskdepart']."'  order by d.intime DESC limit 1";
$get_depart = mysql_query($query_get_depart, $hos) or die(mysql_error());
$row_get_depart = mysql_fetch_assoc($get_depart);
$totalRows_get_depart = mysql_num_rows($get_depart);

if($totalRows_get_depart<>0){

	mysql_select_db($database_hos, $hos);
	$insert_rx = "UPDATE ptdepart SET outdepcode='".$_GET['cur_dep']."',outtime=CURTIME() WHERE vn='".$drug_vn."' AND depcode='".$_GET['depcode1']."' AND hn='".$hn."' AND intime='".$row_get_depart['intime']."'  AND outdepcode='' AND outtime='".$row_get_depart['outtime']."' AND status IS NULL AND staff='".$row_get_depart['staff']."' AND outdate='".$row_get_depart['outdate']."' AND hos_guid IS NULL AND hos_guid_ext IS NULL ";
	$rs_insert_rx = mysql_query($insert_rx, $hos) or die(mysql_error());
	
	//update ptdepart
	mysql_select_db($database_hos, $hos);
	$query_ptdepart = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','UPDATE ptdepart SET outdepcode=\'".$_GET['cur_dep']."\',outtime=CURTIME() WHERE vn=\'".$drug_vn."\' AND depcode=\'".$_GET['depcode1']."\' AND hn=\'".$hn."\' AND intime=\'".$row_get_depart['intime']."\'  AND outdepcode=\'\' AND outtime=\'".$row_get_depart['outtime']."\' AND status IS NULL AND staff=\'".$row_get_depart['staff']."\' AND outdate=\'".$row_get_depart['outdate']."\' AND hos_guid IS NULL AND hos_guid_ext IS NULL ')";
	$ptdepart = mysql_query($query_ptdepart, $hos) or die(mysql_error());
}

mysql_free_result($get_depart);

}
else{
	mysql_select_db($database_hos, $hos);
	$update_rx = "update ".$database_kohrx.".kohrx_dispen_staff_operation set print_staff='".$rx_print."',room_id='".$row_rs_channel['room_id']."' where id='".$row_search_rx['id']."'";
	$rs_update_rx = mysql_query($update_rx, $hos) or die(mysql_error());
	
	//บันทึกข้อมูการบันทึก kohrx_dispen_staff_operation ใน replicate_log
	mysql_select_db($database_hos, $hos);
	$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_dispen_staff_operation set print_staff=\'".$rx_print."\',room_id=\'".$row_rs_channel['room_id']."\' where id=\'".$row_search_rx['id']."\')')";
	$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());	

	//update แผนกที่ส่งไปล่าสุด
	mysql_select_db($database_hos, $hos);
	$insert_rx = "UPDATE ".$database_kohrx.".kohrx_queue_caller_channel SET outdepcode='".$_GET['cur_dep']."' where ip='".$get_ip."' ";
	$rs_insert_rx = mysql_query($insert_rx, $hos) or die(mysql_error());
	
	//update ptdepart
	mysql_select_db($database_hos, $hos);
	$query_ptdepart = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','UPDATE ".$database_kohrx.".kohrx_queue_caller_channel SET outdepcode=\'".$_GET['cur_dep']."\' where ip=\'".$get_ip."\'')";
	$ptdepart = mysql_query($query_ptdepart, $hos) or die(mysql_error());

//ค้นหา ptdepart
mysql_select_db($database_hos, $hos);
$query_get_depart = "select d.* from ptdepart d where vn='".$drug_vn."' and depcode='".$row_rs_channel['kskdepart']."' order by d.intime DESC limit 1";
$get_depart = mysql_query($query_get_depart, $hos) or die(mysql_error());
$row_get_depart = mysql_fetch_assoc($get_depart);
$totalRows_get_depart = mysql_num_rows($get_depart);

if($totalRows_get_depart<>0){
	mysql_select_db($database_hos, $hos);
	$insert_rx = "UPDATE ptdepart SET outdepcode='".$_GET['cur_dep']."',outtime=CURTIME() WHERE vn='".$drug_vn."' AND depcode='".$row_get_depart['depcode']."' AND hn='".$hn."' AND intime='".$row_get_depart['intime']."'  AND outdepcode='' AND outtime='".$row_get_depart['outtime']."' AND status IS NULL AND staff='".$row_get_depart['staff']."' AND outdate='".$row_get_depart['outdate']."' AND hos_guid IS NULL AND hos_guid_ext IS NULL ";
	$rs_insert_rx = mysql_query($insert_rx, $hos) or die(mysql_error());
	
	//update ptdepart
	mysql_select_db($database_hos, $hos);
	$query_ptdepart = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','UPDATE ptdepart SET outdepcode=\'".$_GET['cur_dep']."\',outtime=CURTIME() WHERE vn=\'".$drug_vn."\' AND depcode=\'".$_GET['depcode1']."\' AND hn=\'".$hn."\' AND intime=\'".$row_get_depart['intime']."\'  AND outdepcode=\'\' AND outtime=\'".$row_get_depart['outtime']."\' AND status IS NULL AND staff=\'".$row_get_depart['staff']."\' AND outdate=\'".$row_get_depart['outdate']."\' AND hos_guid IS NULL AND hos_guid_ext IS NULL ')";
	$ptdepart = mysql_query($query_ptdepart, $hos) or die(mysql_error());
}

mysql_free_result($get_depart);

	}
mysql_free_result($search_rx);

mysql_free_result($rs_channel);

//บันทึกข้อมูลใน rx_operator_log
mysql_select_db($database_hos, $hos);
$insert_rx = "UPDATE rx_operator_log SET  log_datetime=current_timestamp,check_staff='".$_GET['prepare']."',pay_staff='".$_GET['dispen']."',confirm_staff='".$_GET['check']."',log_type='Edit',entry_staff='$user1' WHERE vn='$drug_vn'";
$rs_insert_rx = mysql_query($insert_rx, $hos) or die(mysql_error());

//บันทึกข้อมูการบันทึก rx_operator_log ใน replicate_log
mysql_select_db($database_hos, $hos);
$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','UPDATE rx_operator_log SET  log_datetime=current_timestamp,check_staff=\'".$prepare."\',pay_staff=\'".$dispen."\',confirm_staff=\'".$check."\',log_type=\'Edit\',entry_staff=\'".$user1."\' WHERE vn=\'".$drug_vn."\'')";
$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());
	}
//ค้นหาข้อมูลใน patient_rx_note
/*mysql_select_db($database_hos, $hos);
$query_rs_patient_note = "select * from patient_rx_note where hn = '$drug_hn'";
$rs_patient_note = mysql_query($query_rs_patient_note, $hos) or die(mysql_error());
$row_rs_patient_note = mysql_fetch_assoc($rs_patient_note);
$totalRows_rs_patient_note = mysql_num_rows($rs_patient_note);
*/
//เก็บข้อมูลเวลาในการจ่าย
mysql_select_db($database_hos, $hos);
$query_rs_rx_time = "select rx_time from rx_operator where vn = '".$drug_vn."' and pay = 'Y' and rx_time is not null order by rx_operator_id limit 1";
$rs_rx_time = mysql_query($query_rs_rx_time, $hos) or die(mysql_error());
$row_rs_rx_time = mysql_fetch_assoc($rs_rx_time);
$totalRows_rs_rx_time = mysql_num_rows($rs_rx_time);


//สร้างตัวแปลเก็บเวลา---------------------//
$rx_time=$row_rs_rx_time['rx_time'];
//-----------------------------------//


//ค้นหาข้อมูลใน service_time
/*mysql_select_db($database_hos, $hos);
$query_rs_service_time = "select * from service_time where vn='$drug_vn'";
$rs_service_time = mysql_query($query_rs_service_time, $hos) or die(mysql_error());
$row_rs_service_time = mysql_fetch_assoc($rs_service_time);
$totalRows_rs_service_time = mysql_num_rows($rs_service_time);
*/

//ค้นหาข้อมูลวันที่และเวลาใน ovst
mysql_select_db($database_hos, $hos);
$query_rs_vstdatetime = "select hn,vstdate,vsttime from ovst where vn='".$drug_vn."'";
$rs_vstdatetime = mysql_query($query_rs_vstdatetime, $hos) or die(mysql_error());
$row_rs_vstdatetime = mysql_fetch_assoc($rs_vstdatetime);
$totalRows_rs_vstdatetime = mysql_num_rows($rs_vstdatetime);


//update ข้อมูลใน service_time
//service16 =เวลารับยา
//service12=เวลาสั่งยา
//vsttime=เวลามา
mysql_select_db($database_hos, $hos);
$query_update = "UPDATE service_time SET staff='".$_GET['user1']."',service16='".$rx_time."',last_send_time='current_timestamp',service16_dep='".$_GET['depcode1']."' WHERE vn='$drug_vn'";
$update = mysql_query($query_update, $hos) or die(mysql_error());

//บันทึกข้อมูการบันทึก service_time ใน replicate_log
mysql_select_db($database_hos, $hos);
$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','UPDATE service_time SET staff=\'".$user1."\',service16=\'".$rx_time."\',last_send_time=current_timestamp,service16_dep=\'".$_GET['depcode1']."\' WHERE vn=\'".$drug_vn."\'')";
$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());

//หาตัวแปลเก็บค่า hos_guid
mysql_select_db($database_hos, $hos);
$query_rs_hos_guid = "select upper(concat('{',uuid(),'}')) as cc";
$rs_hos_guid = mysql_query($query_rs_hos_guid, $hos) or die(mysql_error());
$row_rs_hos_guid = mysql_fetch_assoc($rs_hos_guid);
$totalRows_rs_hos_guid = mysql_num_rows($rs_hos_guid);

//-------------------------------//สร้างตัวแปล
$hos_guid=$row_rs_hos_guid['cc'];
//--------------------------------//

//บันทึกข้อมูลใน opitemrece_pay
mysql_select_db($database_hos, $hos);
$insert_rx = "INSERT INTO opitemrece_pay (hos_guid,pay_staff,pay_datetime,vn) VALUES ('".$hos_guid."','".$_GET['dispen']."',current_timestamp,'".$drug_vn."')";
$rs_insert_rx = mysql_query($insert_rx, $hos) or die(mysql_error());

//บันทึกข้อมูการบันทึก opitemrece_pay ใน replicate_log
mysql_select_db($database_hos, $hos);
$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','INSERT INTO opitemrece_pay (hos_guid,pay_staff,pay_datetime,vn) VALUES (\'".$hos_guid."\',\'".$_GET['dispen']."\',current_timestamp,\'".$drug_vn."\')')";
$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());


//ถ้ามีตัวเลือกส่งไปยังแผนกให้เปิดใช้งาน
//ดึงข้อมูล hos_guid ของ ovst
	
	mysql_select_db($database_hos, $hos);
	$query_rs_ovst_hos_guid = "select hos_guid from ovst where vn='".$drug_vn."'";
	$rs_ovst_hos_guid = mysql_query($query_rs_ovst_hos_guid, $hos) or die(mysql_error());
	$row_rs_ovst_hos_guid = mysql_fetch_assoc($rs_ovst_hos_guid);
	$totalRows_rs_ovst_hos_guid = mysql_num_rows($rs_ovst_hos_guid);
	//-----------เก็บเป็นตัวแปล hos_guid-----------------//
	$ovst_hos_guid=$row_rs_ovst_hos_guid['hos_guid'];
	//----------------------------------------------//
	
	// update ovst
	mysql_select_db($database_hos, $hos);
	$query_update = "UPDATE ovst SET cur_dep='".$_GET['cur_dep']."' WHERE hos_guid='$ovst_hos_guid'";
	$update = mysql_query($query_update, $hos) or die(mysql_error());
	
	//บันทึกข้อมูการบันทึก ovst ใน replicate_log
	mysql_select_db($database_hos, $hos);
	$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','UPDATE ovst SET cur_dep=\'".$_GET['cur_dep']."\' WHERE hos_guid=\'".$ovst_hos_guid."\'')";
	$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());

/// ค้นหาข้อมูลผู้ป่วยว่ามีการถูกเรียกชื่อหรือไม่
	//ค้นหาชื่อ
	mysql_select_db($database_hos, $hos);
	$query_rs_patient = "select l.hn,SUBSTR(call_datetime,1,10) as call_date from ".$database_kohrx.".kohrx_queue_caller_list l left outer join vn_stat v on v.hn=l.hn where vn='".$drug_vn."' and SUBSTR(call_datetime,1,10)=CURDATE()";
	$rs_patient = mysql_query($query_rs_patient, $hos) or die(mysql_error());
	$row_rs_patient = mysql_fetch_assoc($rs_patient);
	$totalRows_rs_patient = mysql_num_rows($rs_patient);
if($totalRows_rs_patient<>0){
	mysql_select_db($database_hos, $hos);
	$query_update = "UPDATE ".$database_kohrx.".kohrx_queue_caller_list SET dispensed='Y',not_response=NULL WHERE hn='".$row_rs_patient['hn']."' and SUBSTR(call_datetime,1,10)='".$row_rs_patient['call_date']."'";
	$update = mysql_query($query_update, $hos) or die(mysql_error());
	
	//บันทึกข้อมูการบันทึก ovst ใน replicate_log
	mysql_select_db($database_hos, $hos);
	$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','UPDATE ".$database_kohrx.".kohrx_queue_caller_list SET dispensed=\'Y\',not_response=NULL WHERE hn=\'".$row_rs_patient['hn']."\' and SUBSTR(call_datetime,1,9)=\'".$row_rs_patient['call_date']."\'')";
	$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());

	//ค้นหาเวลาล่าสุดของการเรียก queue
	mysql_select_db($database_hos, $hos);
	$query_rs_lastcall = "select call_datetime,l.hn from ".$database_kohrx.".kohrx_queue_caller_list l left outer join vn_stat v on v.hn=l.hn where vn='".$drug_vn."' and SUBSTR(call_datetime,1,10)=CURDATE()";
	$rs_lastcall = mysql_query($query_rs_lastcall, $hos) or die(mysql_error());
	$row_rs_lastcall = mysql_fetch_assoc($rs_lastcall);

	mysql_select_db($database_hos, $hos);
	$query_update = "UPDATE ".$database_kohrx.".kohrx_queue_caller_history SET dispensed='Y' WHERE hn='".$row_rs_lastcall['hn']."' and call_datetime='".$row_rs_lastcall['call_datetime']."'";
	$update = mysql_query($query_update, $hos) or die(mysql_error());
	
	mysql_free_result($rs_lastcall);
	}


//ค้นหาข้อมูลการ login
mysql_select_db($database_hos, $hos);
$query_rs_login2 = "select * from ".$database_kohrx.".kohrx_login_check where login_name='".$_SESSION['username_log']."' and ipaddress='".$get_ip."' and substr(last_time,1,10)=CURDATE()";
$rs_login2 = mysql_query($query_rs_login2, $hos) or die(mysql_error());
$row_rs_login2 = mysql_fetch_assoc($rs_login2);
$totalRows_rs_login2 = mysql_num_rows($rs_login2);
//ถ้าพบ
	if($totalRows_rs_login2<>0){
	mysql_select_db($database_hos, $hos);
	$update = "update ".$database_kohrx.".kohrx_login_check set last_time=NOW() where login_name='".$_SESSION['username_log']."' and substr(last_time,1,10)=CURDATE()";
	$rs_update = mysql_query($update, $hos) or die(mysql_error());
	
		//บันทึกลง log
		mysql_select_db($database_hos, $hos);
		$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_login_check set last_time=NOW() where login_name=\'".$_SESSION['username_log']."\' and substr(last_time,1,10)=CURDATE()')";
		$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());

	}

mysql_free_result($rs_login2);


///ค้นหาการถามแพ้ยา
mysql_select_db($database_hos, $hos);
$query_rs_search = "select * from ".$database_kohrx.".kohrx_adr_check where vn='".$drug_vn."'";
$rs_search = mysql_query($query_rs_search, $hos) or die(mysql_error());
$row_rs_search = mysql_fetch_assoc($rs_search);
$totalRows_rs_search = mysql_num_rows($rs_search);

mysql_select_db($database_hos, $hos);
$query_rs_search_adr = "select * from ".$database_kohrx.".kohrx_adr_check where hn='".$hn."' and respondent='".$_GET['respondent']."' and vn<'".$_GET['drug_vn']."' order by vn DESC limit 1";
$rs_search_adr = mysql_query($query_rs_search_adr, $hos) or die(mysql_error());
$row_rs_search_adr = mysql_fetch_assoc($rs_search_adr);
$totalRows_rs_search_adr = mysql_num_rows($rs_search_adr);


	if($totalRows_rs_search_adr<>0){
		if($_GET['answer']==$row_rs_search_adr['answer']){
			$step="'0'";
			$step2="0";
			}
		if($row_rs_search_adr['answer']!=2&&$_GET['answer']==2){
			$step="'-1'";
			$step2="-1";

			}
		if($row_rs_search_adr['answer']>2&&$_GET['answer']==1){
			$step="'-1'";
			$step2="-1";
			}
		if($row_rs_search_adr['answer']>3&&$_GET['answer']==3){
			$step="'-1'";
			$step2="-1";
			}
		if($row_rs_search_adr['answer']==1&&$_GET['answer']>2){
			$step="'1'";
			$step2="1";
			}
		if($row_rs_search_adr['answer']==3&&$_GET['answer']>3){
			$step="'1'";
			$step2="1";
			}
		if($row_rs_search_adr['answer']==3&&$_GET['answer']<3){
			$step="'-1'";
			$step2="-1";
			}
		if($row_rs_search_adr['answer']==4&&$_GET['answer']<4){
			$step="'-1'";
			$step2="-1";
			}
		if($row_rs_search_adr['answer']==2&&$_GET['answer']!=2){
			$step="'1'";
			$step2="1";
			}

		
	}
	else{
		$step="NULL";
		}
	if($totalRows_rs_search<>0){
	//บันทึกการซักแพ้ยา
	mysql_select_db($database_hos, $hos);
	$query_insert = "update ".$database_kohrx.".kohrx_adr_check  set respondent='".$_GET['respondent']."',doctorcode='".$_GET['dispen']."',answer='".$_GET['answer']."',remark='".$_GET['remark']."',hn='".$hn."',step=".$step." where vn='".$drug_vn."'";
	$insert = mysql_query($query_insert, $hos) or die(mysql_error());

	//update replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_adr_check  set respondent=\'".$_GET['respondent']."\',answer=\'".$_GET['answer']."\',remark=\'".$_GET['remark']."\',doctorcode=\'".$_GET['dispen']."\',hn=\'".$hn."\',step=\'".$step2."\'  where vn=\'".$drug_vn."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

	//เสร็จบันทึกข้อมูล adr

	}
	else {
//บันทึกการซักแพ้ยา
if($_GET['respondent']!=""&&$_GET['answer']!=""){
	mysql_select_db($database_hos, $hos);
	$query_insert = "insert into ".$database_kohrx.".kohrx_adr_check (vn,hn,check_date,respondent,answer,remark,doctorcode,step) value ('".$drug_vn."','".$hn."',NOW(),'".$_GET['respondent']."','".$_GET['answer']."','".$_GET['remark']."','".$_GET['dispen']."',".$step.")";
	$insert = mysql_query($query_insert, $hos) or die(mysql_error());

	//update replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_adr_check (vn,hn,check_date,respondent,answer,remark,doctorcode,step) value (\'".$drug_vn."\',\'".$hn."\',NOW(),\'".$_GET['respondent']."\',\'".$_GET['answer']."\',\'".$_GET['remark']."\',\'".$_GET['dispen']."\',\'".$step2."\')')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

}
//เสร็จบันทึกข้อมูล adr
	//บันทึกใน ovst
mysql_select_db($database_hos, $hos);
$update_queued = "update ovst set ovstost='99' where vn='".$drug_vn."'";
$rs_update_queued = mysql_query($update_queued, $hos) or die(mysql_error());

//บันทึกข้อมูการบันทึก ovst ใน replicate_log
mysql_select_db($database_hos, $hos);
$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ovst set ovstost=\'99\' where vn=\'".$drug_vn."\'')";
$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());
	
	}

$today=substr((date('Y')+543),2,2).date('md');
//ค้นหาทั้งหมด
mysql_select_db($database_hos, $hos);
$query_rs_pay = "select (select count(*) as sum_pay from rx_operator r  left outer join ".$database_kohrx.".kohrx_dispen_staff_operation k on k.vn=r.vn   where r.pay='Y' and substr(r.vn,1,6) = '".$today."' and k.room_id='".$row_rs_channel['room_id']."' and r.pay='Y') as sum_pay2,(select count(*) as sum_pay from rx_operator r  left outer join ".$database_kohrx.".kohrx_dispen_staff_operation k on k.vn=r.vn   where r.pay='Y' and substr(r.vn,1,6) = '".$today."'  and r.pay_staff='".$_SESSION['doctorcode']."' and r.pay='Y') as sum_pay3 ";
$rs_pay = mysql_query($query_rs_pay, $hos) or die(mysql_error());
$row_rs_pay = mysql_fetch_assoc($rs_pay);
$totalRows_rs_pay = mysql_num_rows($rs_pay);

//ค้นหาการจ่ายยาในตาราง kohrx_recent_payment
mysql_select_db($database_hos, $hos);
$query_rs_payment = "select doctorcode from ".$database_kohrx.".kohrx_recent_payment  where doctorcode='".$_SESSION['doctorcode']."'";
$rs_payment = mysql_query($query_rs_payment, $hos) or die(mysql_error());
$row_rs_payment = mysql_fetch_assoc($rs_payment);
$totalRows_rs_payment = mysql_num_rows($rs_payment);
	if($totalRows_rs_payment<>0){
	//ถ้าไม่ว่างให้ update

	if($_GET['respondent']!=""||$_GET['answer']!=""){
	mysql_select_db($database_hos, $hos);
	$update_queued = "update ".$database_kohrx.".kohrx_recent_payment set respondent='".$_GET['respondent']."',answer='".$_GET['answer']."',update_datetime=NOW() where doctorcode='".$_SESSION['doctorcode']."'";
	$rs_update_queued = mysql_query($update_queued, $hos) or die(mysql_error());

	//บันทึกข้อมูการบันทึก kohrx_recent_payment ใน replicate_log
mysql_select_db($database_hos, $hos);
$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_recent_payment set respondent=\'".$_GET['respondent']."\',answer=\'".$_GET['answer']."\',update_datetime=NOW() where doctorcode=\'".$_SESSION['doctorcode']."\'')";
$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());
		
		}
	if($rx_print!=""||$_GET['prepare']!=""||$_GET['check']!=""||$_GET['dispen']!=""){
	mysql_select_db($database_hos, $hos);
	$update_queued = "update ".$database_kohrx.".kohrx_recent_payment set print_staff='".$rx_print."',prepare_staff='".$_GET['prepare']."',check_staff='".$_GET['check']."',pay_staff='".$_GET['dispen']."',update_datetime=NOW() where doctorcode='".$_SESSION['doctorcode']."'";
	$rs_update_queued = mysql_query($update_queued, $hos) or die(mysql_error());
	
	//บันทึกข้อมูการบันทึก kohrx_recent_payment ใน replicate_log
mysql_select_db($database_hos, $hos);
$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_recent_payment set print_staff=\'".$rx_print."\',prepare_staff=\'".$_GET['prepare']."\',check_staff=\'".$_GET['check']."\',pay_staff=\'".$_GET['dispen']."\',update_datetime=NOW() where doctorcode=\'".$_SESSION['doctorcode']."\'')";
$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());

	}
	
	
	}
	else {
	//ถ้าว่างให้ insert
	mysql_select_db($database_hos, $hos);
	$update_insert = "insert into ".$database_kohrx.".kohrx_recent_payment value ('".$_SESSION['doctorcode']."','".$rx_print."','".$_GET['prepare']."','".$_GET['check']."','".$_GET['dispen']."','".$_GET['respondent']."','".$_GET['answer']."',NOW())";
	$rs_update_insert = mysql_query($update_insert, $hos) or die(mysql_error());

	//บันทึกข้อมูการบันทึก kohrx_recent_payment ใน replicate_log
mysql_select_db($database_hos, $hos);
$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_recent_payment value (\'".$_SESSION['doctorcode']."\',\'".$rx_print."\',\'".$_GET['prepare']."\',\'".$_GET['check']."\',\'".$_GET['dispen']."\',\'".$_GET['respondent']."\',\'".$_GET['answer']."\',NOW())')";
$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());
	
	}

mysql_free_result($rs_payment);

if($_GET['cursor_position']=="queue"){ $cursor="queue"; } else if($_GET['cursor_position']=="hn") {$cursor="hn";} else if($_GET['cursor_position']=="an") {$cursor="an";} else {$cursor="hn"; } 
echo "<script>$('#".$cursor."').val('');$('#".$cursor."').focus();</script>";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php //include('java_css_file.php'); ?>

<title>Untitled Document</title>


<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<script>
$(document).ready(function(){
    setTimeout(function(){ $('.success_msg').fadeOut(1000); }, 2000);
    
});
</script>
</head>
<div class="position-absolute" style=" top: 50%;
  left: 50%;
  -ms-transform: translate(-50%, -50%);
  transform: translate(-50%, -50%);">
<div class="alert alert-primary thfont success_msg text-center " style="width:500px; font-size:30px; padding-top:30px; "  role="alert">
<i class="fas fa-check-circle" style="font-size:30px;" aria-hidden="true"></i>        บันทึกข้อมูลเรียบร้อยแล้ว 
    </p>
</div> 
</div>
    
<div style="margin-top:20px; padding-left:5px; color:#06C" class="thfont font_bord font19">
สรุปการจ่ายยาของคุณ
</div>
<div style="padding:5px;" class="thfont">จำนวนจ่ายยาทั้งหมดของ <span  class="font_bord"><?php echo $row_rs_channel['room_name']; ?>=</span> <span class="big_red16"><?php echo $row_rs_pay['sum_pay2']; ?></span>&nbsp;ราย</div>
<div style="padding:5px;" class="thfont">จำนวนที่คุณจ่ายทั้งหมด = <span class="big_red16"><?php echo $row_rs_pay['sum_pay3']; ?></span> ราย</div>

</body>
</html>
<?php
mysql_free_result($rs_serial);

mysql_free_result($rs_search1);
//ปิด sql
mysql_free_result($rx_timenow);

//mysql_free_result($rs_patient_note);

mysql_free_result($rs_rx_time);

//mysql_free_result($rs_service_time);

mysql_free_result($rs_vstdatetime);

mysql_free_result($rs_hos_guid);

mysql_free_result($rs_patient);

mysql_free_result($rs_pay);


?>
