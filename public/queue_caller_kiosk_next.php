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

/// ค้นหาข้อมูลเครื่อง //////////
	mysql_select_db($database_hos, $hos);
$query_rs_search = "select * from ".$database_kohrx.".kohrx_queue_caller_channel where ip='".$get_ip."'";
$rs_search = mysql_query($query_rs_search, $hos) or die(mysql_error());
$row_rs_search = mysql_fetch_assoc($rs_search);
$totalRows_rs_search = mysql_num_rows($rs_search);

mysql_select_db($database_hos, $hos);
$query_rs_q_config = "select * from ".$database_kohrx.".kohrx_kiosk_queue_caller_config where room_id='".$row_rs_search['room_id']."' and q_date=CURDATE()";
$rs_q_config = mysql_query($query_rs_q_config, $hos) or die(mysql_error());
$row_rs_q_config = mysql_fetch_assoc($rs_q_config);
$totalRows_rs_q_config = mysql_num_rows($rs_q_config);
?>
<?php
if(isset($_GET['action'])){
if($_GET['action']=="next"){
	$queue=$row_rs_q_config['current_q']+1;
}
if($_GET['action']=="previous"){
	$queue=$row_rs_q_config['current_q']-1;
	}
	
if($_GET['action']=="recent"){
	$queue=$row_rs_q_config['current_q'];
	}

if($_GET['action']=="manual"){
	
	$queue=$_GET['input_q'];
	
	if($queue==0){
		echo "<script>alert('กรุณาเลือกคิวที่ไม่เท่ากับ 0');</script>";	
		exit();
	}
	
	mysql_select_db($database_hos, $hos);
	$query_rs_config = "select * from ".$database_kohrx.".kohrx_kiosk_queue_caller_config  where room_id='".$row_rs_search['room_id']."' ";
	$rs_config = mysql_query($query_rs_config, $hos) or die(mysql_error());
	$row_rs_config = mysql_fetch_assoc($rs_config);
	$totalRows_rs_config = mysql_num_rows($rs_config);	
	
	if($row_rs_config['current_q']<$queue){
	mysql_select_db($database_hos, $hos);
	$query_rs_update = "update ".$database_kohrx.".kohrx_kiosk_queue_caller_config set current_q=".$queue.",current_q_datetime=NOW() where room_id='".$row_rs_search['room_id']."'";
	$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());
		
	}
	
	
	mysql_free_result($rs_config);

	}


if($row_rs_search['queue_method']==1){
//ค้นหาชื่อผู้ป่วย
mysql_select_db($database_hos, $hos);
$query_rs_pt = "select p.hn,concat(p.pname,p.fname,' ',p.lname) as ptname from patient p left outer join ".$database_kohrx.".kohrx_queued q on q.hn=p.hn where queue='".$queue."' and substr(queue_datetime,1,10)=CURDATE() and room_id='".$row_rs_search['room_id']."' and q_delete !='Y'";
$rs_pt = mysql_query($query_rs_pt, $hos) or die(mysql_error());
$row_rs_pt = mysql_fetch_assoc($rs_pt);
$totalRows_rs_pt = mysql_num_rows($rs_pt);

$hn=$row_rs_pt['hn'];
$ptname=$row_rs_pt['ptname'];
mysql_free_result($rs_pt);

}

if($row_rs_search['queue_method']==1){
	if($totalRows_rs_pt<>0){	
		if(($_GET['action']=="next")||($_GET['action']=="recent")){
		mysql_select_db($database_hos, $hos);
		$query_rs_update = "update ".$database_kohrx.".kohrx_kiosk_queue_caller_config set current_q=".$queue.",current_q_datetime=NOW() where room_id='".$row_rs_search['room_id']."'";
		$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());
		}
	
	mysql_select_db($database_hos, $hos);
	$query_rs_insert = "insert into ".$database_kohrx.".kohrx_queue_caller_list (hn,patient_name,channel_id,room_id,patient_type,call_server,call_datetime) value ('".$hn."','".$ptname."','".$row_rs_search['channel']."','".$row_rs_search['room_id']."','OPD','Y',NOW())";
	$rs_insert = mysql_query($query_rs_insert, $hos) or die(mysql_error());
}
	else if($totalRows_rs_pt==0){	

		echo "<script>config_q('".($queue-1)."');alert('ไม่มีคิวนี้ในระบบ');</script>";
		}

	}
}
if($row_rs_search['queue_method']==2){
		if(($_GET['action']=="next")||($_GET['action']=="recent")){
		mysql_select_db($database_hos, $hos);
		$query_rs_update = "update ".$database_kohrx.".kohrx_kiosk_queue_caller_config set current_q=".$queue.",current_q_datetime=NOW() where room_id='".$row_rs_search['room_id']."'";
		$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());
		}
	
	mysql_select_db($database_hos, $hos);
	$query_rs_insert = "insert into ".$database_kohrx.".kohrx_queue_caller_list (hn,patient_name,channel_id,room_id,patient_type,call_server,call_datetime,main_dep_queue) value ('".$hn."','".$ptname."','".$row_rs_search['channel']."','".$row_rs_search['room_id']."','OPD','Y',NOW(),".$queue.")";
	$rs_insert = mysql_query($query_rs_insert, $hos) or die(mysql_error());
}
?>
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
mysql_free_result($rs_search);

mysql_free_result($rs_q_config);


?>