<?php require_once('Connections/hos.php'); ?>
<?php $get_ip=$_SERVER["REMOTE_ADDR"]; 
	
    include('include/function.php');
	date_default_timezone_set('Asia/Bangkok');
	mysql_select_db($database_hos, $hos);
	$query_rs_room = "select room_name,r.id,c.caller_method from ".$database_kohrx.".kohrx_queue_caller_channel c left outer join ".$database_kohrx.".kohrx_queue_caller_room r on r.id=c.room_id where ip='".$get_ip."'";
	$rs_room = mysql_query($query_rs_room, $hos) or die(mysql_error());
	$row_rs_room = mysql_fetch_assoc($rs_room);
	$totalRows_rs_room = mysql_num_rows($rs_room);

	/// clear list
	if(isset($_GET['do'])&&($_GET['do']=="clear")){
		mysql_select_db($database_hos, $hos);
		$query_delete = "delete from ".$database_kohrx.".kohrx_queue_caller_list where room_id='".$row_rs_room['id']."'";
		$rs_delete = mysql_query($query_delete, $hos) or die(mysql_error());
	
	}


	mysql_select_db($database_hos, $hos);
	$query_rs_room2 = "select * from ".$database_kohrx.".kohrx_queue_caller_server_check where ip='".$get_ip."'";
	$rs_room2 = mysql_query($query_rs_room2, $hos) or die(mysql_error());
	$row_rs_room2 = mysql_fetch_assoc($rs_room2);
	$totalRows_rs_room2 = mysql_num_rows($rs_room2);

	if($totalRows_rs_room2<>0){
	mysql_select_db($database_hos, $hos);
	$query_update = "update ".$database_kohrx.".kohrx_queue_caller_server_check set time_update=NOW(),date_update=NOW(),room_id='".$row_rs_room['id']."' where ip='".$get_ip."'";
	$update = mysql_query($query_update, $hos) or die(mysql_error());
	}
	else{
	mysql_select_db($database_hos, $hos);
	$query_insert = "insert into ".$database_kohrx.".kohrx_queue_caller_server_check (ip,time_update,date_update,room_id) values ('".$get_ip."',NOW(),NOW(),'".$row_rs_room['id']."')";
	$insert = mysql_query($query_insert, $hos) or die(mysql_error());		
	}
	//ลบ server ที่มีผลต่างเวลาน้อยกว่า 30 วินาที
	mysql_select_db($database_hos, $hos);
	$query_rs_delete = "delete from ".$database_kohrx.".kohrx_queue_caller_server_check where TIME_TO_SEC(TIMEDIFF(NOW(),concat(date_update,' ',time_update)))>=30 ";
	$rs_delete = mysql_query($query_rs_delete, $hos) or die(mysql_error());

	
	
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

//แสดงรายชื่อต่อไป
mysql_select_db($database_hos, $hos);
$query_rs_detect2 = "select l.id,l.hn,l.patient_name,c.channel,l.call_datetime,n.channel_name,n.q_show from ".$database_kohrx.".kohrx_queue_caller_list l left outer join ".$database_kohrx.".kohrx_queue_caller_channel c on c.id=l.channel_id left outer join ".$database_kohrx.".kohrx_queue_caller_channel_name n on n.id=l.channel_id where called is NULL and l.room_id='".$row_rs_room['id']."' and l.call_server='Y' group by l.hn,l.call_datetime order by call_datetime ASC";
$rs_detect2 = mysql_query($query_rs_detect2, $hos) or die(mysql_error());
$row_rs_detect2 = mysql_fetch_assoc($rs_detect2);
$totalRows_rs_detect2 = mysql_num_rows($rs_detect2);

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
<div style="overflow:scroll;overflow-x:hidden;overflow-y:auto; height:50vh;" >
	<?php if ($totalRows_rs_detect2 > 0) { // Show if recordset not empty ?>
  
  <table width="100%" class="table" style="font-size: 11px;">
      <tr>
            <td width="8%" height="27" align="center" class="rounded_top_left">ลำดับ</td>
            <td width="13%" align="center">HN</td>
            <td width="36%" align="center">ชื่อผู้ป่วย</td>
            <td width="18%" align="center" >ช่องบริการ</td>
            <td width="25%" align="center" class="rounded_top_right">เวลาเรียก</td>
          </tr>
          <?php $i=0; do { $i++; ?>
          <tr style="font-size: 11px;">  
            <td height="29" align="center"  ><?php echo $i; ?></td>
            <td align="center" ><?php echo "$row_rs_detect2[hn]"; ?></td>
            <td align="left" ><?php echo "$row_rs_detect2[patient_name]"; ?></td>
            <td align="center" ><?php echo "$row_rs_detect2[channel_name]"; ?></td>
            <td align="center"><?php echo dateThai("$row_rs_detect2[call_datetime]"); ?></td>
            
            </tr>
          <?php } while ($row_rs_detect2 = mysql_fetch_assoc($rs_detect2)); ?>
        </table>
    <?php } else { ?><div class="text-center"><h5 class="text-danger">ไม่มีผู้ที่รอเรียกชื่อ</h3></div><? } ?>

    </div>
</body>
</html>
<?php
mysql_free_result($rs_detect2);

?>
