<?php 
session_start();
?>
<?php require_once('Connections/hos.php'); ?>
<?
	mysql_select_db($database_hos, $hos);
	$delete = "delete from ".$database_kohrx.".kohrx_login_check where login_name='".$_SESSION['username_log']."'";
	$rs_delete = mysql_query($delete, $hos) or die(mysql_error());
	
		//บันทึกลง log
		mysql_select_db($database_hos, $hos);
		$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','delete from ".$database_kohrx.".kohrx_login_check where login_name=\'".$_SESSION['username_log']."\'')";
		$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());

setcookie("username_log","",time()-3600*24*356);
setcookie("password_log","",time()-3600*24*356);
setcookie("member_status","",time()-3600*24*356);
setcookie("hospcode","",time()-3600*24*356);
session_destroy();
session_write_close();
header("location:login.php"); //ไปไปตามหน้าที่คุณต้องการ
?>