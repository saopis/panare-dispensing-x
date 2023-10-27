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

mysql_select_db($database_hos, $hos);
$query_rs_channel = "select l.room_id,room_name,channel,patient_name,call_datetime,l.hn,p.sex,cl.channel_name from ".$database_kohrx.".kohrx_queue_caller_list l left outer join ".$database_kohrx.".kohrx_queue_caller_channel c on c.id=l.channel_id left outer join ".$database_kohrx.".kohrx_queue_caller_room r on r.id=l.room_id  left outer join patient p on p.hn=l.hn left outer join ".$database_kohrx.".kohrx_queue_caller_channel_name cl on cl.id=l.channel_id where l.room_id='".$_POST['room']."' and TIME_TO_SEC(TIMEDIFF(CURTIME(),SUBSTR(call_datetime,11,9))) <1800 and dispensed is null and SUBSTR(call_datetime,1,10)=CURDATE() order by call_datetime DESC limit 1
";
$rs_channel = mysql_query($query_rs_channel, $hos) or die(mysql_error());
$row_rs_channel = mysql_fetch_assoc($rs_channel);
$totalRows_rs_channel = mysql_num_rows($rs_channel);

mysql_select_db($database_hos, $hos);
$query_rs_channel1 = "select l.room_id,room_name,channel,patient_name,call_datetime,cl.channel_name from ".$database_kohrx.".kohrx_queue_caller_list l left outer join ".$database_kohrx.".kohrx_queue_caller_channel c on c.id=l.channel_id left outer join ".$database_kohrx.".kohrx_queue_caller_room r on r.id=l.room_id left outer join ".$database_kohrx.".kohrx_queue_caller_channel_name cl on cl.id=l.channel_id where l.room_id='$room' and TIME_TO_SEC(TIMEDIFF(CURTIME(),SUBSTR(call_datetime,11,9))) <1800 and dispensed is null and SUBSTR(call_datetime,1,10)=CURDATE() order by call_datetime DESC limit 1,3";
$rs_channel1 = mysql_query($query_rs_channel1, $hos) or die(mysql_error());
$row_rs_channel1 = mysql_fetch_assoc($rs_channel1);
$totalRows_rs_channel1 = mysql_num_rows($rs_channel1);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
</style>
</head>

<body>
<?php if ($totalRows_rs_channel > 0) { // Show if recordset not empty ?>
  <table width="100%" border="0" cellspacing="0" cellpadding="5" height="590">
    <tr>
      <td height="56" align="center" class=" red" style=" font:Arial, Helvetica, sans-serif; font-size:60px"><?php echo $row_rs_channel['channel_name']; ?></td>
    </tr>
    <tr>
      <td align="center"><p><br />
  <br />
  <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
      <td align="center"><?
	mysql_select_db($database_hos, $hos);
 	$query_selpic = "select count(*) as cc from patient_image where hn='".$row_rs_channel['hn']."' ";
	$selpic = mysql_query($query_selpic, $hos) or die(mysql_error());
	$row_selpic = mysql_fetch_assoc($selpic);
	$totalRows_selpic = mysql_num_rows($selpic);
					if($row_selpic['cc']>0){
				mysql_select_db($database_hos, $hos);
				$query = "SELECT image as blob_img FROM patient_image where hn='".$row_rs_channel['hn']."' "; 
				$result = mysql_query($query, $hos) or die(mysql_error()); 
				$row = mysql_fetch_array($result); 
				$jpg = $row["blob_img"]; 

							?>
							<img src="data:image/jpeg;base64,<?php echo base64_encode($jpg); ?> "  width="280" height="320" vlign="middle" border="0"> <?php 
						}
						else {
							echo "<img src=\"images/noimage.png\" width=\"280\" height=\"320\" />";
							}
							?>
        </td>
      </tr>
    </table>
        <br />
        <span class="table_head_small_white" style="font-size:50px; font-weight: bolder"><?php echo $row_rs_channel['patient_name']; ?><br />
        <font style="font-size:20px; color: #FF0">เวลา :<?php echo substr($row_rs_channel['call_datetime'],11,8); ?></font>        </span>
        </p></td>
    </tr>
  </table>
  <?php } // Show if recordset not empty ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="270">
    <tr>
      <td height="195" valign="bottom"><?php if ($totalRows_rs_channel1 > 0) { // Show if recordset not empty ?>
  <table width="100%" border="0" align="center" cellpadding="10" cellspacing="0" >
    <?php do { ?>
      <tr class="black1">
        <td width="340" height="65" style="font:Arial, Helvetica, sans-serif; font-size:40px; color: #FFFFEA; padding-left:30px" ><?php echo $row_rs_channel1['patient_name']; ?><br />
          <font style="font-size:20px;color: #FF0">เวลาเรียก <?php echo substr($row_rs_channel1['call_datetime'],11,8); ?></font></td>
        <td width="140" align="center" class=" blue2" style=" font:Arial, Helvetica, sans-serif; font-size:30px; color:#EAF4FF; font-weight:bolder "><?php echo "$row_rs_channel1[channel_name]"; ?></td>
      </tr>
      <?php } while ($row_rs_channel1 = mysql_fetch_assoc($rs_channel1)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
</td>
    </tr>
  </table>
</body>
</html>
<?php
mysql_free_result($rs_channel);

mysql_free_result($rs_channel1);
?>
