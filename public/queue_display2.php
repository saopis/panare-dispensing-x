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
$query_channel = "SELECT patient_picture from ".$database_kohrx.".kohrx_queue_caller_channel q left outer join ".$database_kohrx.".kohrx_queue_caller_channel_name n on n.id=q.channel WHERE ip='".$get_ip."'";
$channel = mysql_query($query_channel, $hos) or die(mysql_error());
$row_channel = mysql_fetch_assoc($channel);
$totalRows_channel = mysql_num_rows($channel);

$pt_show=$row_channel['patient_picture'];

mysql_free_result($channel);

mysql_select_db($database_hos, $hos);
$query_rs_list = "SELECT t1.patient_name,t1.hn,t1.patient_name,t1.call_datetime,q.queue,n.channel_name,n.q_show
FROM ".$database_kohrx.".kohrx_queue_caller_list t1
INNER JOIN
(
    SELECT channel_id, MAX(call_datetime) AS max_date
    FROM ".$database_kohrx.".kohrx_queue_caller_list  
    GROUP BY channel_id
) t2
    ON t1.channel_id = t2.channel_id AND t1.call_datetime = t2.max_date
left outer join ".$database_kohrx.".kohrx_queued q on q.hn=t1.hn and substr(q.queue_datetime,1,10)= CURDATE() and q.room_id=t1.room_id
left outer join ".$database_kohrx.".kohrx_queue_caller_channel_name n on n.id=t1.channel_id
where t1.room_id='".$_GET['room_id']."' and TIME_TO_SEC(TIMEDIFF(CURTIME(),SUBSTR(t1.call_datetime,11,9))) <18000 and t1.dispensed is NULL and SUBSTR(t1.call_datetime,1,10)=CURDATE() group by t1.hn order by t1.call_datetime DESC limit 4";
//echo $query_rs_list;
$rs_list = mysql_query($query_rs_list, $hos) or die(mysql_error());
$row_rs_list = mysql_fetch_assoc($rs_list);
$totalRows_rs_list = mysql_num_rows($rs_list);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
.img-circle {
    border-radius: 5%;
}
</style>
</head>

<body>
<?php
echo"<table border=\"0\"  cellspacing=\"0\" cellpadding=\"0\" width=\"1146px\" style=\"border-collapse: collapse\" style=\"background-color:#000000;\" ><tr>";
		$intRows = 0;
		do{
			echo "<td style=\" height=\"482\" style=\"background-color:#FFF;\">"; 
			$intRows++;
			
			if($totalRows_rs_list<>0){?>
            <div align="center" style="border-right:1px #000000 solid; width:576px; height:200px; padding-top:20px; font-size:30px; border-top:0px #000000 solid; background-color:#FFFFFF;" class="thfont font_border"><?php if($row_rs_list['q_show']=='Y'){ if($row_rs_list['queue']!=""){ if($pt_show=="Y"){?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>หมายเลข<?php } } ?></div>       
            <div style="width:576px; height:250px; font-size:200px; padding-top:20px; border:1px #000000 solid; border-bottom:0px; border-top:0px; border-left:0px; margin-top:-250px; " align="center" class="thfont font_border" >		
	<?
	if($pt_show=="Y"){
	mysql_select_db($database_hos, $hos);
 	$query_selpic = "select count(*) as cc from patient_image where hn='".$row_rs_list['hn']."' ";
	$selpic = mysql_query($query_selpic, $hos) or die(mysql_error());
	$row_selpic = mysql_fetch_assoc($selpic);
	$totalRows_selpic = mysql_num_rows($selpic);
					if($row_selpic['cc']>0){
				mysql_select_db($database_hos, $hos);
				$query = "SELECT image as blob_img FROM patient_image where hn='".$row_rs_list['hn']."' "; 
				$result = mysql_query($query, $hos) or die(mysql_error()); 
				$row = mysql_fetch_array($result); 
				$jpg = $row["blob_img"]; 

							?>
							<img src="data:image/jpeg;base64,<?php echo base64_encode($jpg); ?> "  width="165" height="200" vlign="middle" border="0" style="margin-top:100px;" class="img-circle" > <?php 
						}
						else {
							echo "<img src=\"images/noimage.png\" width=\"165\" height=\"200\" style=\"margin-top:100px;\"  class=\"img-circle\" />";
							}
	}
							?>
<?php if($row_rs_list['q_show']=='Y'){ echo $row_rs_list['queue']; } ?></div>
  <div style="font-size:<?php if($row_rs_list['q_show']=='Y'){ echo "30"; } else { echo "50"; } ?>px; width:576px; height:153px; padding-top:10px; border-left:0px #000000 solid; border-right:1px #000000 solid; padding-top:<?php if($row_rs_list['q_show']=='Y'){ echo "100"; } else { echo "80"; } ?>px;background-color:#FFFFFF; " align="center" class="thfont font_border"><?php echo $row_rs_list['patient_name']; ?></div>

<div style="font-size:80px; color:#C30; width:576px; height:130px; border:1px #000000 solid; border-top:0px;  border-left:0px;background-color:#FFFFFF; " align="center" class="thfont font_border"><?php echo $row_rs_list['channel_name']; ?></div>
			<?
			}
		echo"</td>";
			if(($intRows)%2==0)
			{
				echo"</tr>";
			}
		}while($row_rs_list = mysql_fetch_assoc($rs_list));
		echo"</tr></table>";

?></body>
</html>
<?php
mysql_free_result($rs_list);
?>
