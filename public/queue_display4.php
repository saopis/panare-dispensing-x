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
$query_rs_list = "SELECT t1.patient_name,t1.hn,t1.patient_name,t1.call_datetime,q.queue,n.channel_name,n.q_show,t1.main_dep_queue
FROM ".$database_kohrx.".kohrx_queue_caller_list t1

left outer join ".$database_kohrx.".kohrx_queued q on q.hn=t1.hn and substr(q.queue_datetime,1,10)= CURDATE()
left outer join ".$database_kohrx.".kohrx_queue_caller_channel_name n on n.id=t1.channel_id
where t1.room_id='".$_GET['room_id']."' and TIME_TO_SEC(TIMEDIFF(CURTIME(),SUBSTR(t1.call_datetime,11,9))) <1800 and t1.dispensed is NULL and SUBSTR(t1.call_datetime,1,10)=CURDATE() and t1.hn != 
(

SELECT t2.hn
FROM ".$database_kohrx.".kohrx_queue_caller_list t2

left outer join ".$database_kohrx.".kohrx_queued q2 on q2.hn=t2.hn and substr(q2.queue_datetime,1,10)= CURDATE()
where t2.room_id='".$_GET['room_id']."' and TIME_TO_SEC(TIMEDIFF(CURTIME(),SUBSTR(t2.call_datetime,11,9))) <1800 and t2.dispensed is NULL and SUBSTR(t2.call_datetime,1,10)=CURDATE() order by t2.call_datetime DESC limit 1

) 
group by t1.hn order by t1.call_datetime DESC limit 9";
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
html,body{ overflow-x: hidden;}
.img-circle {
    border-radius: 50%;
}
.container2 {
   height: auto;
   overflow: hidden;
}

.left2 {
    width: 220px;
    float: left;
	height:118px;
    background: #64B5F6;
}

.right2 {
    float: none; /* not needed, just for clarification */
    background: #ECEFF1;
    /* the next props are meant to keep this block independent from the other floated one */
    width: auto;
	height:118px;
    overflow: hidden;
}​​
.container3 {
   height: auto;
   overflow: hidden;
}

.left3 {
    width: 111px;
    float: left;
	height:118px;
}

.right3 {
    float: none; /* not needed, just for clarification */
    /* the next props are meant to keep this block independent from the other floated one */
    width: auto;
	height:118px;
    overflow: hidden;
}​​
.container4 {
   height: auto;
   overflow: hidden;
}

.left4 {
    width: 450px;
    float: left;
	height:296px;
	background-color:#1565C0;
}

.right4 {
    float: none; /* not needed, just for clarification */
    /* the next props are meant to keep this block independent from the other floated one */
    width: auto;
	height:296px;
    overflow: hidden;
}​​
.container5 {
   height: auto;
   overflow: hidden;
}

.left5 {
    width: 277px;
    float: left;
	height:296px;
}

.right5 {
    float: none; /* not needed, just for clarification */
    /* the next props are meant to keep this block independent from the other floated one */
    width: auto;
	height:296px;
    overflow: hidden;
	background-color:#FFF;
}​​
.container6 {
   height: auto;
   overflow: hidden;
}

.left6 {
    width: 500px;
    float: left;
	height:118px;
}

.right6 {
    float: none; /* not needed, just for clarification */
    /* the next props are meant to keep this block independent from the other floated one */
    width: auto;
	height:118px;
    overflow: hidden;
	background-color: #CFDA94;
	color: #000;
}​​

</style>
</head>

<body>
<table width="100%" border="0" cellpadding="5" cellspacing="0">
  <tr style="background-color: #000000">
    <td width="20%" align="right" style="color:#FFFFFF; font-size:50px; height:75px;">คิว</td>
    <td width="80%" align="center" style="color:#FFFFFF; font-size:30px;">เวลาที่เรียก</td>
  </tr>
<?php 
do{
	?>
    <tr>
    	<td align="right"><div style="color: #FF0; font-size:120px; margin-top:-52px; margin-bottom:-24px;" class=" thsan-bold"><?php echo $row_rs_list['main_dep_queue']; ?></div></td>
        <td align="left"><div style="color:#FFFFFF; font-size:80px; margin-top:-50px; margin-bottom:-20px;" class=" thsan-light">--&gt; <?php 
 echo substr($row_rs_list['call_datetime'],10,6); ?></div></td>
    </tr>
	<?
	}
while ($row_rs_list = mysql_fetch_assoc($rs_list));

?>
</table>
</body>
</html>
<?php
mysql_free_result($rs_list);
?>
