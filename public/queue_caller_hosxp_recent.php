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
$query_rs_last_q = "select last_queue,first_queue,c.depcode,c.q_dep_type from ".$database_kohrx.".kohrx_queue_caller_channel c left outer join ".$database_kohrx.".kohrx_hosxp_queue_caller_config h on h.depcode=c.depcode where c.ip='".$get_ip."'";
$rs_last_q = mysql_query($query_rs_last_q, $hos) or die(mysql_error());
$row_rs_last_q = mysql_fetch_assoc($rs_last_q);
$totalRows_rs_last_q = mysql_num_rows($rs_last_q);

if($row_rs_last_q['first_queue']<$row_rs_last_q['last_queue']){
	if($row_rs_last_q['q_dep_type']=="main_dep"){
		$next_queue=($row_rs_last_q['last_queue']+1);
		$recent_queue=$row_rs_last_q['last_queue'];
	}
	else if($row_rs_last_q['q_dep_type']=="cur_dep"){
		mysql_select_db($database_hos, $hos);
		$query_rs_q_last = "select main_dep_queue from ovst where cur_dep='".$row_rs_last_q['depcode']."' and vstdate=CURDATE() and main_dep_queue > '".$row_rs_last_q['last_queue']."' order by cur_dep_time ASC limit 1";
		$rs_q_last = mysql_query($query_rs_q_last, $hos) or die(mysql_error());
		$row_rs_q_last = mysql_fetch_assoc($rs_q_last);
		$totalRows_rs_q_last = mysql_num_rows($rs_q_last);

			$next_queue=$row_rs_q_last['main_dep_queue'];

		mysql_free_result($rs_q_last);		

		$recent_queue=$row_rs_last_q['last_queue'];	
	}
}
else if($row_rs_last_q['first_queue']>=$row_rs_last_q['last_queue']||$row_rs_last_q['last_queue']==NULL){
	if($row_rs_last_q['q_dep_type']=="main_dep"){
		$next_queue=$row_rs_last_q['first_queue']+1;
		$recent_queue=$row_rs_last_q['first_queue'];
	}
	else if($row_rs_last_q['q_dep_type']=="cur_dep"){
		mysql_select_db($database_hos, $hos);
		$query_rs_q_last = "select main_dep_queue from ovst where cur_dep='".$row_rs_last_q['depcode']."' and vstdate=CURDATE() and main_dep_queue > '".$row_rs_last_q['first_queue']."' order by cur_dep_time ASC limit 1";
		$rs_q_last = mysql_query($query_rs_q_last, $hos) or die(mysql_error());
		$row_rs_q_last = mysql_fetch_assoc($rs_q_last);
		$totalRows_rs_q_last = mysql_num_rows($rs_q_last);
			if($totalRows_rs_q_last<>0){
				$next_queue=$row_rs_q_last['main_dep_queue'];
			}
			else {
				$next_queue=0;
				}
		mysql_free_result($rs_q_last);		
		
		if($row_rs_last_q['last_queue']!=NULL||$row_rs_last_q['last_queue']!=0){
		$recent_queue=$row_rs_last_q['last_queue'];
		}
			else {
				$recent_queue=0;
				}
	}
}

mysql_select_db($database_hos, $hos);
$query_rs_last = "select * from ".$database_kohrx.".kohrx_hosxp_queue_caller where main_dep='".$row_rs_last_q['depcode']."' and substr(queue_date_call,1,10)=CURDATE() order by main_dep_queue DESC limit 1";

$rs_last = mysql_query($query_rs_last, $hos) or die(mysql_error());
$row_rs_last = mysql_fetch_assoc($rs_last);
$totalRows_rs_last = mysql_num_rows($rs_last);

$last_q=$row_rs_last['main_dep_queue'];

mysql_free_result($rs_last);


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
             <a href="javascript:valid()" id="next" onClick="queue_call('previous');config_q('<?php echo $last_q; ?>');" name="next" class=" btn btn-primary btn-lg"><i class="fas fa-angle-double-left" style="font-size:30px;"></i>&nbsp;</a>
           <a href="javascript:valid()" id="next" onClick="queue_call('recent');config_q('<?php echo $last_q; ?>');" name="next" class=" btn btn-primary btn-lg"><i class="fas fa-microphone-alt" style="font-size:30px;"></i>&nbsp;<?php echo $recent_queue; ?></a>
            <a href="javascript:valid()" id="next" onClick="queue_call('next');config_q('<?php echo ($last_q+1); ?>');" name="next" class=" btn btn-primary btn-lg"><i class="fas fa-angle-double-right" style="font-size:30px;"></i>&nbsp;</a>
            
</body>
</html>
<?php
mysql_free_result($rs_last_q);
?>
