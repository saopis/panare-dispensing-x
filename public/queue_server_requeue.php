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

include('include/function.php');

   //ค้นหาห้อง
    $get_ip=$_SERVER["REMOTE_ADDR"]; 
	
	mysql_select_db($database_hos, $hos);
$query_rs_room = "select room_name,r.id,c.ip,c.caller_default,c.channel from ".$database_kohrx.".kohrx_queue_caller_channel c left outer join ".$database_kohrx.".kohrx_queue_caller_room r on r.id=c.room_id where ip='".$get_ip."'";
$rs_room = mysql_query($query_rs_room, $hos) or die(mysql_error());
$row_rs_room = mysql_fetch_assoc($rs_room);
$totalRows_rs_room = mysql_num_rows($rs_room);

mysql_select_db($database_hos, $hos);
$query_rs_time = "delete from ".$database_kohrx.".kohrx_queue_caller_time_left where room_id='".$row_rs_room['id']."'";
$rs_time = mysql_query($query_rs_time, $hos) or die(mysql_error());

mysql_select_db($database_hos, $hos);
$query_del = "delete from ".$database_kohrx.".kohrx_queue_caller_list where substr(call_datetime,1,10)!=CURDATE()";
$rs_del = mysql_query($query_del, $hos) or die(mysql_error());


	//check ip
	mysql_select_db($database_hos, $hos);
	$query_rs_roomcheck = "select concat(q.date_update,' ',q.time_update) as timenow,q.ip,n.channel_name from ".$database_kohrx.".kohrx_queue_caller_server_check q left outer join ".$database_kohrx.".kohrx_queue_caller_channel c on c.ip=q.ip left outer join ".$database_kohrx.".kohrx_queue_caller_channel_name n on n.id=c.channel where q.room_id='".$row_rs_room['id']."' and q.date_update=CURDATE()";
	$rs_roomcheck = mysql_query($query_rs_roomcheck, $hos) or die(mysql_error());
	$row_rs_roomcheck = mysql_fetch_assoc($rs_roomcheck);
	$totalRows_rs_roomcheck = mysql_num_rows($rs_roomcheck);

	mysql_select_db($database_hos, $hos);
	$query_channel = "SELECT channel_name,cursor_position,r.room_name,r.id as room_id,q.queue_list,q.kskdepart,q.ip from ".$database_kohrx.".kohrx_queue_caller_channel q left outer join ".$database_kohrx.".kohrx_queue_caller_channel_name n on n.id=q.channel left outer join ".$database_kohrx.".kohrx_queue_caller_room r on r.id=q.room_id WHERE r.id='".$row_rs_room['id']."' and caller_default='Y'";
	// $query_channel = "SELECT channel_name,cursor_position,r.room_name,r.id as room_id,q.queue_list,q.kskdepart,q.caller_default,q.ip from ".$database_kohrx.".kohrx_queue_caller_channel q left outer join ".$database_kohrx.".kohrx_queue_caller_channel_name n on n.id=q.channel left outer join ".$database_kohrx.".kohrx_queue_caller_room r on r.id=q.room_id WHERE r.id='".$row_rs_room['id']."' and caller_default='Y'";
	$channel = mysql_query($query_channel, $hos) or die(mysql_error());
	$row_channel = mysql_fetch_assoc($channel);
	$totalRows_channel = mysql_num_rows($channel);
		
		if($totalRows_channel<>0){
			$ip=$row_channel['ip'];
			$caller_default='Y';
		}

	mysql_free_result($channel);
		
	mysql_select_db($database_hos, $hos);
	$query_rs_now = "select NOW() as timenow";
	$rs_now = mysql_query($query_rs_now, $hos) or die(mysql_error());
	$row_rs_now = mysql_fetch_assoc($rs_now);
	$totalRows_rs_now = mysql_num_rows($rs_now);

	$diff = strtotime($row_rs_now['timenow']) - strtotime($row_rs_roomcheck['timenow']);
	
	if($diff<=2){		
		if($ip!=$row_rs_room['ip']){
			//echo console_log($ip);
			//echo "<script>modalCallerCheck();</script>";
			echo "<script>parent.modalCallerCheck('caller server ถูกเปิดแล้วจาก".$row_rs_roomcheck['channel_name']." เลข ip : ".$row_rs_roomcheck['ip']."');parent.callerAutoClose();exit();</script>";				
			/*
			echo "<script>alert('caller server ถูกเปิดแล้วจาก".$row_rs_roomcheck['channel_name']." เลข ip : ".$row_rs_roomcheck['ip']."');parent.callerAutoClose();exit();</script>";
			*/
			}

		}
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php include('java_css_file.php'); ?>
<link rel="shortcut icon" href="images/favicon2.ico" />
<script>
$(document).ready(function() {
    $('#displayDiv').load('queue_server.php');
setInterval(reloadChat, 2000);  
setInterval(reloadList, 2000);      
});
function reloadChat () {
     $('#server_check').load('caller_server_check.php');
}
function reloadList () {
     $('#queue_caller_list').load('queue_caller_list.php');
}
 
</script>
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
html,body { overflow: hidden; }

::-webkit-scrollbar { width: 15px; }

::-webkit-scrollbar-track {
    box-shadow: inset 0 0 10px 10px #E6E6E6;
    border: solid 3px transparent;
}

::-webkit-scrollbar-thumb {
    box-shadow: inset 0 0 10px 10px #CCCCCC;
    border: solid 3px transparent;
}
</style>
</head>

<body >
<div style="position:absolute" id="server_check"></div>
<form id="form1" name="form1" method="post" action="">
  <table width="100%" border="0" cellspacing="0" cellpadding="5">
    <tr class="" style="background-color: #B4B1B1">
      <td class="text-dark p-3">ระบบเรียกชื่อผู้ป่วย :&nbsp;<?php echo $row_rs_room['room_name']; ?><input type="button" name="clear" id="clear" value="ลบทั้งหมด" class="thfont btn btn-danger btn-sm" style=" position: absolute; right: 10px;" onclick="if(confirm('ต้องการลบคิวรอเรียกทั้งหมดจริงหรือไม่')==true){$('#recheck').load('queue_caller_recheck.php?do=clear'); }"/></td>
    </tr>
  </table>
<div id="displayDiv" class="displayIndiv" >&nbsp;</div>
<div class="card" style="margin: 5px; height: 165px;">
    <div class="card-body p-0">
        <div id="queue_caller_list" ></div>
    </div>
</div>
</form>
</body>
</html>
<?php mysql_free_result($rs_room); 
mysql_free_result($rs_roomcheck);

mysql_free_result($rs_now);

?>


