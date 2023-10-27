<?php session_start();?>
<?php
$get_ip=$_SERVER["REMOTE_ADDR"];
?>
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
if(is_numeric($_GET['recent_queue'])>0){
	
	mysql_select_db($database_hos, $hos);
	$query_update = "update ".$database_kohrx.".kohrx_queue_caller_channel set recent_rx_queue='".$_GET['recent_queue']."', recent_rx_queue_datetime=NOW() where ip='".$get_ip."'";
	$rs_update = mysql_query($query_update, $hos) or die(mysql_error());

}
else{
	
	mysql_select_db($database_hos, $hos);
	$query_update = "update ".$database_kohrx.".kohrx_queue_caller_channel set recent_rx_queue=NULL, recent_rx_queue_datetime=NULL where ip='".$get_ip."'";
	$rs_update = mysql_query($query_update, $hos) or die(mysql_error());
	
	}
mysql_select_db($database_hos, $hos);
$query_rs_login = "select *,NOW() as timenow,TIME_TO_SEC(TIMEDIFF( NOW(),last_time)) as diff from ".$database_kohrx.".kohrx_login_check where login_name='".$_SESSION['username_log']."' and ipaddress='".$get_ip."' and substr(last_time,1,10)=CURDATE()";
$rs_login = mysql_query($query_rs_login, $hos) or die(mysql_error());
$row_rs_login = mysql_fetch_assoc($rs_login);
$totalRows_rs_login = mysql_num_rows($rs_login);

mysql_select_db($database_hos, $hos);
$query_rs_setting = "select * from ".$database_kohrx.".kohrx_dispensing_setting order by id ASC";
$rs_setting = mysql_query($query_rs_setting, $hos) or die(mysql_error());
$row_rs_setting = mysql_fetch_assoc($rs_setting);
$totalRows_rs_setting = mysql_num_rows($rs_setting);

$i=0; do{ $i++;
$row_setting[$i]=$row_rs_setting['value'];} 
while($row_rs_setting = mysql_fetch_assoc($rs_setting));

if($row_setting['36']!=""){
if($row_rs_login['diff']>=$row_setting['36']){
	echo "<script>window.location='logout.php';</script>";
	}
}

 ob_start();?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
</body>
</html>
<?php
mysql_free_result($rs_login);
mysql_free_result($rs_setting);

?>
