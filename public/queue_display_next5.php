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
FROM ".$database_kohrx.".kohrx_queue_caller_list t1 left outer join ".$database_kohrx.".kohrx_queued q on q.hn=t1.hn and substr(q.queue_datetime,1,10)= CURDATE() 
left outer join ".$database_kohrx.".kohrx_queue_caller_channel_name n on n.id=t1.channel_id 
where t1.room_id='".$_GET['room_id']."' and SUBSTR(t1.call_datetime,1,10)=CURDATE() 
and t1.not_response = 'Y' and t1.dispensed is NULL
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
<?php if($totalRows_rs_list<>0){ ?>
<table width="100%" border="0" cellpadding="5" cellspacing="0">
  <tr style="background-color: #000000">
    <td width="39%" align="right" style="color:#FFFFFF; font-size:40px; height:75px;">หมายเลขคิว</td>
    <td width="61%" align="center" style="color:#FFFFFF; font-size:30px;">ช่องบริการ/<span style="font-size:20px">เวลาที่เรียก</span></td>
  </tr>
<?php 
do{
	
	$i++;
if($i%2==0)
{
$bg = "#FFFFFF";
}
else
{
$bg = "#F9F9F9";
}
	?>
    <tr bgcolor="<?php echo $bg; ?>" >
    	<td align="center" style="border-bottom:solid  #F1F1F1 1px;"><div style="color: #F63; font-size:120px; margin-top:-52px; margin-bottom:-24px;" class=" thsan-bold"><?php echo $row_rs_list['queue']; ?></div></td>
        <td align="center" style="border-bottom:solid #F1F1F1 1px;"><div style="color: #666; font-size:60px; margin-top:-30px; margin-bottom:-20px;" class=" thsan-light"><?php 
 echo $row_rs_list['channel_name']; ?>&nbsp;<span style="font-size:30px; color:#09F" class=" thfont font_bord"><?php echo substr($row_rs_list['call_datetime'],10,6); ?></span></div></td>
    </tr>
	<?
	}
while ($row_rs_list = mysql_fetch_assoc($rs_list));

?>
</table>
<?php } ?>
</body>
</html>
<?php
mysql_free_result($rs_list);
?>
