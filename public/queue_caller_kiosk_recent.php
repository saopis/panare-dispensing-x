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
mysql_select_db($database_hos, $hos);
$query_rs_search = "select * from ".$database_kohrx.".kohrx_queue_caller_channel where ip='".$get_ip."'";
$rs_search = mysql_query($query_rs_search, $hos) or die(mysql_error());
$row_rs_search = mysql_fetch_assoc($rs_search);
$totalRows_rs_search = mysql_num_rows($rs_search);

	$room_id=$row_rs_search['room_id'];

mysql_free_result($rs_search);

mysql_select_db($database_hos, $hos);
$query_rs_last_q = "select current_q from ".$database_kohrx.".kohrx_kiosk_queue_caller_config where room_id='".$room_id."'";
$rs_last_q = mysql_query($query_rs_last_q, $hos) or die(mysql_error());
$row_rs_last_q = mysql_fetch_assoc($rs_last_q);
$totalRows_rs_last_q = mysql_num_rows($rs_last_q);

	if($totalRows_rs_last_q<>0){
	$recent_queue=$row_rs_last_q['current_q'];
	$next_queue=($row_rs_last_q['current_q']+1);
	}
	else{
	$recent_queue=0;
	$next_queue=1;
	}
	


?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
<div style="font-size:20px; color: #666;" >คิวปัจจุบัน</div>
<div style="font-size:100px; margin-top:-20px; margin-bottom:-20px; color:#F00 ">
<?php echo $recent_queue; ?>
</div>
             <a href="javascript:valid()" id="next" onClick="queue_call('previous');config_q('<?php echo $recent_queue; ?>');" name="next" class=" btn btn-primary btn-lg"><i class="fas fa-angle-double-left" style="font-size:30px;"></i>&nbsp;</a>
           <a href="javascript:valid()" id="next" onClick="queue_call('recent');config_q('<?php echo $recent_queue; ?>');" name="next" class=" btn btn-primary btn-lg"><i class="fas fa-microphone-alt" style="font-size:30px;"></i>&nbsp;<?php echo $recent_queue; ?></a>
            <a href="javascript:valid()" id="next" onClick="queue_call('next');config_q('<?php echo $next_queue; ?>');" name="next" class=" btn btn-primary btn-lg"><i class="fas fa-angle-double-right" style="font-size:30px;"></i>&nbsp;</a>
            
</body>
</html>
<?php
mysql_free_result($rs_last_q);
?>
