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
date_default_timezone_set("Asia/Bangkok");

$get_ip=$_SERVER["REMOTE_ADDR"];

mysql_select_db($database_hos, $hos);
$query_channel = "SELECT depcode,time_per_case,q_dep_type,room_id from ".$database_kohrx.".kohrx_queue_caller_channel q  WHERE ip='".$get_ip."'";
$channel = mysql_query($query_channel, $hos) or die(mysql_error());
$row_channel = mysql_fetch_assoc($channel);
$totalRows_channel = mysql_num_rows($channel);

mysql_select_db($database_hos, $hos);
$query_rs_max = "select q.queue,l.call_datetime from ".$database_kohrx.".kohrx_queue_caller_list l left outer join ".$database_kohrx.".kohrx_queued q on q.hn=l.hn and q.room_id=l.room_id and substr(queue_datetime,1,10)=CURDATE() where l.room_id='".$_GET['room_id']."' and substr(l.call_datetime,1,10)=CURDATE() and l.called ='Y' order by q.queue DESC,l.main_dep_queue DESC,l.call_datetime DESC limit 1 ";
$rs_max = mysql_query($query_rs_max, $hos) or die(mysql_error());
$row_rs_max = mysql_fetch_assoc($rs_max);
$totalRows_rs_max = mysql_num_rows($rs_max);

mysql_select_db($database_hos, $hos);
$query_rs_max2 = "select max(main_dep_queue) as max_q from ovst where main_dep='".$row_channel['main_dep']."' ";
$rs_max2 = mysql_query($query_rs_max2, $hos) or die(mysql_error());
$row_rs_max2 = mysql_fetch_assoc($rs_max2);
$totalRows_rs_max2 = mysql_num_rows($rs_max2);

if($totalRows_rs_max<>0){
$selectedTime = substr($row_rs_max['call_datetime'],10,9);
}
else{
$selectedTime=date('H:i:s');
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
tr:nth-child(even) {background: #4D5C62}
tr:nth-child(odd) {background: #566169}
</style>
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="100%" border="0" cellpadding="5" cellspacing="0">
  <tr style="background-color: #000000">
    <td width="20%" align="right" style="color:#FFFFFF; font-size:50px; height:75px;">คิว</td>
    <td width="80%" align="center" style="color:#FFFFFF; font-size:30px;">เวลาให้บริการ(ประมาณ)</td>
  </tr>
  <?php 
  $time=$selectedTime;

  	mysql_select_db($database_hos, $hos);
	$query_rs_q_order = "select queue from ".$database_kohrx.".kohrx_queued where substr(queue_datetime,1,10)=CURDATE() and room_id='".$row_channel['room_id']."' and queue > '".$row_rs_max['queue']."' order by queue ASC limit 9";
  $rs_q_order = mysql_query($query_rs_q_order, $hos) or die(mysql_error());
	$row_rs_q_order = mysql_fetch_assoc($rs_q_order);
	$totalRows_rs_q_order = mysql_num_rows($rs_q_order);
	if($totalRows_rs_q_order<>0){
	do{
	 $endTime = strtotime("+".$row_channel['time_per_case']." minutes", strtotime($time));
	?>
  <tr >
    <td align="right" ><div style="color: #FF0; font-size:120px; margin-top:-52px; margin-bottom:-24px;" class=" thsan-bold"><?php echo $row_rs_q_order['queue']; ?></div></td>
    <td align="left"><div style="color:#FFFFFF; font-size:80px; margin-top:-50px; margin-bottom:-20px;" class=" thsan-light">--&gt; <?php 
 echo date('H:i', $endTime); ?></div></td>
  </tr>
    
    <?	
		  $time=date('H:i:00', $endTime);
	
	}while($row_rs_q_order = mysql_fetch_assoc($rs_q_order));
	}
	mysql_free_result($rs_q_order);
	 
		
  
   ?>
</table>
</body>
</html>
<?php
mysql_free_result($channel);
mysql_free_result($rs_max);
mysql_free_result($rs_max2);


?>
