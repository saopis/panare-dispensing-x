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
ob_start();
session_start();

$get_ip=$_SERVER["REMOTE_ADDR"];

mysql_select_db($database_hos, $hos);
$query_rs_login = "select o.*,k.right_opd,k.right_ipd,k.right_admin,k.right_finance,d.name as deptname,doc.position_id from opduser o left outer join hospital_department d on d.id=o.hospital_department_id left outer join ".$database_kohrx.".kohrx_user_setting k on k.doctorcode=o.doctorcode left outer join doctor doc on doc.code=o.doctorcode where o.loginname='".$_POST['username_log']."' and o.passweb='".md5($_POST['password_log'])."'";
$rs_login = mysql_query($query_rs_login, $hos) or die(mysql_error());
$row_rs_login = mysql_fetch_assoc($rs_login);
$totalRows_rs_login = mysql_num_rows($rs_login);

if($totalRows_rs_login<>0){
if($_POST['chk'] == "on") { // ถ้าติ๊กถูก Login ตลอดไป ให้ทำการสร้าง cookie
setcookie("username_log",$row_rs_login['loginname'],time()+3600*24*356);
setcookie("password_log",$row_rs_login['passweb'],time()+3600*24*356);

if(strpos($row_rs_login['accessright'],'ADMIN') == true){
setcookie("member_status",'admin',time()+3600*24*356);
}
else {
setcookie("member_status",'user',time()+3600*24*356);
}
setcookie("nameuser",$row_rs_login['name'],time()+3600*24*356);
setcookie("deptname",$row_rs_login['deptname'],time()+3600*24*356);
setcookie("deptid",$row_rs_login['hospital_department_id'],time()+3600*24*356);

header("location:index.php"); 
//ไปไปตามหน้าที่คุณต้องการ
} 
else {
mysql_select_db($database_hos, $hos);
$query_update = "update ".$database_kohrx.".kohrx_queue_caller_channel set doctor_type='".$_POST['doctor_type']."',cursor_position='".$_POST['cursor']."' where ip='".$get_ip."'";
$rs_update = mysql_query($query_update, $hos) or die(mysql_error());

// ถ้าไม่ติ๊กถูก Login
$_SESSION['doctor_type']=$_POST['doctor_type'];
$_SESSION["doctorcode"]=$row_rs_login['doctorcode'];
$_SESSION["position_id"]=$row_rs_login['position_id'];
$_SESSION["username_log"]=$row_rs_login['loginname'];
$_SESSION["password_log"]=$row_rs_login['passweb'];
$_SESSION["r_opd"]=$row_rs_login['right_opd'];
$_SESSION["r_ipd"]=$row_rs_login['right_ipd'];
$_SESSION["r_admin"]=$row_rs_login['right_admin'];
$_SESSION["r_finance"]=$row_rs_login['right_finance'];

if(strpos($row_rs_login['accessright'],'ADMIN') == true){
$_SESSION["member_status"]="admin";
}
else{
$_SESSION["member_status"]="user";
}

$_SESSION["nameuser"]=$row_rs_login['name'];
$_SESSION["deptname"]=$row_rs_login['deptname'];
$_SESSION["deptid"]=$row_rs_login['hospital_department_id'];

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
//ถ้าไม่พบ
	else{
	mysql_select_db($database_hos, $hos);
	$insert = "insert into ".$database_kohrx.".kohrx_login_check (login_name,ipaddress,last_time) value ('".$_SESSION['username_log']."','".$get_ip."',NOW())";
	$rs_insert = mysql_query($insert, $hos) or die(mysql_error());
	
		//บันทึกลง log
		mysql_select_db($database_hos, $hos);
		$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_login_check (login_name,ipaddress,last_time) value (\'".$_SESSION['username_log']."\',\'".$get_ip."\',NOW())')";
		$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());
	}

mysql_free_result($rs_login2);

//บนทึกข้อมูลลงบน kohrx_login_log
	mysql_select_db($database_hos, $hos);
	$insert2 = "insert into ".$database_kohrx.".kohrx_login_log (login_name,ipaddress,time_check) value ('".$_SESSION['username_log']."','".$get_ip."',NOW())";
	$rs_insert2 = mysql_query($insert2, $hos) or die(mysql_error());

		//บันทึกลง log
		mysql_select_db($database_hos, $hos);
		$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_login_log (login_name,ipaddress,time_check) value (\'".$_SESSION['username_log']."\',\'".$get_ip."\',NOW())')";
		$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());

session_write_close(); 
header("location:index.php"); //ไปไปตามหน้าที่คุณต้องการ
}
} 
else {
header("location:login.php"); //ไม่ถูกต้องให้กับไปหน้าเดิม
}

?>
<?php
mysql_free_result($rs_login);
?>
