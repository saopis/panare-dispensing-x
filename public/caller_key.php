<?
ob_start();
session_start();
$hn=$_GET['hn'];
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
	//ค้นหา Q ก่อนหน้า
	mysql_select_db($database_hos, $hos);
	$query_rs_rx2 = "select queue from ".$database_kohrx.".kohrx_queued where hn='$hn' and substr(queue_datetime,1,10)=CURDATE() and room_id='4' and queue='".($_GET['queue']-1)."' and called_datetime is not NULL ";
	echo $query_rs_rx2;
	$rs_rx2 = mysql_query($query_rs_rx2, $hos) or die(mysql_error());
	$row_rs_rx2 = mysql_fetch_assoc($rs_rx2);
	$totalRows_rs_rx2 = mysql_num_rows($rs_rx2);
	
	if($totalRows_rs_rx2<>0){
		echo "คิว".($_GET['queue']-1)."ยังไม่ได้เรียก";
		exit();
	}
	
	mysql_free_result($rs_rx2);	
	
mysql_select_db($database_hos, $hos);
$query_rs_patient = "select hn,pname,fname,lname,concat(pname,fname,' ',lname) as patient_name from patient where hn='".$hn."'";
echo $query_rs_patient;
$rs_patient = mysql_query($query_rs_patient, $hos) or die(mysql_error());
$row_rs_patient = mysql_fetch_assoc($rs_patient);
$totalRows_rs_patient = mysql_num_rows($rs_patient);

$get_ip=$_SERVER["REMOTE_ADDR"];
mysql_select_db($database_hos, $hos);
$query_channel = "SELECT q.*,n.channel_name,n.id as channel_id,r.room_name from ".$database_kohrx.".kohrx_queue_caller_channel q left outer join ".$database_kohrx.".kohrx_queue_caller_channel_name n on n.id=q.channel left outer join ".$database_kohrx.".kohrx_queue_caller_room r on r.id=q.room_id WHERE ip='$get_ip'";
$rs_channel = mysql_query($query_channel, $hos) or die(mysql_error());
$row_rs_channel = mysql_fetch_assoc($rs_channel);
$totalRows_rs_channel = mysql_num_rows($rs_channel);

mysql_select_db($database_hos, $hos);
$query_rs_setting = "select * from ".$database_kohrx.".kohrx_dispensing_setting order by id ASC";
$rs_setting = mysql_query($query_rs_setting, $hos) or die(mysql_error());
$row_rs_setting = mysql_fetch_assoc($rs_setting);
$totalRows_rs_setting = mysql_num_rows($rs_setting);

$i=0; do{ $i++;
$row_setting[$i]=$row_rs_setting['value'];} 
while($row_rs_setting = mysql_fetch_assoc($rs_setting));
?>
<meta http-equiv="refresh" content="0;URL=queue.php?prefix=<?php echo $row_rs_patient['pname']; ?>&second=<?php echo $row_setting[19]; ?>&channel_name=<?php echo $row_rs_channel['channel_name']; ?>&subfix=<?php echo $row_setting[20]; ?>&channel=<?php echo $row_rs_channel['channel_id']; ?>&patient_name=<?php echo $row_rs_patient['patient_name']; ?>&room_id=<?php echo $row_rs_channel['room_id']; ?>&hn=<?php echo $row_rs_patient['hn']; ?>&fname=<?php echo $row_rs_patient['fname']; ?>&lname=<?php echo $row_rs_patient['lname']; ?>&call_server=<?php echo $row_rs_channel['call_server']; ?>&patient_type=OPD" />
<?php echo "<script>parent.firstFocus2('respondent');parent.$.fn.colorbox.close();</script>"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
</body>
</html>
<?php
mysql_free_result($rs_patient);

mysql_free_result($rs_channel);
mysql_free_result($rs_setting);
?>
