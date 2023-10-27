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
$query_rs_recent = "select main_dep_queue,n.channel_name,call_datetime from ".$database_kohrx.".kohrx_queue_caller_list l left outer join ".$database_kohrx.".kohrx_queue_caller_channel_name n on n.id=l.channel_id where hn='' and room_id='".$_GET['room_id']."' order by call_datetime DESC limit 1";
$rs_recent = mysql_query($query_rs_recent, $hos) or die(mysql_error());
$row_rs_recent = mysql_fetch_assoc($rs_recent);
$totalRows_rs_recent = mysql_num_rows($rs_recent);


mysql_select_db($database_hos, $hos);
$query_rs_last = "select main_dep_queue,call_datetime from ".$database_kohrx.".kohrx_queue_caller_list l where hn='' and main_dep_queue !=(select main_dep_queue from ".$database_kohrx.".kohrx_queue_caller_list l where hn='' and room_id='".$_GET['room_id']."' order by call_datetime DESC limit 1) and room_id='".$_GET['room_id']."' group by main_dep_queue order by main_dep_queue DESC limit 6";
$rs_last = mysql_query($query_rs_last, $hos) or die(mysql_error());
$row_rs_last = mysql_fetch_assoc($rs_last);
$totalRows_rs_last = mysql_num_rows($rs_last);
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<style>
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	overflow:hidden;
}

.container1 {
   height: auto;
   overflow: hidden;
}

.left1 {
    width: 850px;
    float: left;
	height:880px;
    background: #0072BC;
}

.right1 {
    float: none; /* not needed, just for clarification */
    background: #0072BC;
    /* the next props are meant to keep this block independent from the other floated one */
    width: auto;
	height:880px;
    overflow: hidden;
}​​
.container2 {
   height: auto;
   overflow: hidden;
}

.left2 {
    width: 220px;
    float: left;
	height:60px;
	color:#FFFFFF;
	font-size:50px;
}

.right2 {
    float: none; /* not needed, just for clarification */
    /* the next props are meant to keep this block independent from the other floated one */
    width: auto;
	height:60px;
    overflow: hidden;
		color:#FFFFFF;
	font-size:50px;

}​​
.container3 {
   height: auto;
   overflow: hidden;
}

.left3 {
    width: 220px;
    float: left;
	height:135px;
	color:#FFFFFF;
	font-size:50px;
}

.right3 {
    float: none; /* not needed, just for clarification */
    /* the next props are meant to keep this block independent from the other floated one */
    width: auto;
	height:135px;
    overflow: hidden;
	color:#FFFFFF;
	font-size:50px;
}​​
</style>
<link href="css/kohrx.css" rel="stylesheet" type="text/css">
</head>

<body>
  <div class="container1" >
    <div class="left1" style="border-right:3px #FFFFFF solid;">
      <div style="padding:10px; padding-right:40px;">
        <div style="border:solid 4px #FFFFFF; padding:10px; font-size:80px; font-weight:bolder; color:#FFFFFF;" align="center"  class=" thsan-bold">คิวที่เรียกล่าสุด</div> 
       <? if($totalRows_rs_recent<>0){ ?>
	    <div style="color:#FFF; font-size:60px; margin-top:0px;" class="thsan-light">หมายเลขคิว</div> 
        <div class=" thfont" style=" font-weight:bolder;font-size:380px; color:#FFFFFF; margin-top:-180px; margin-bottom:-250px;" align="center"><?php echo $row_rs_recent['main_dep_queue']; ?></div>
        <div style="color:#FFF; font-size:60px; margin-top:10px;" class="thsan-light">จุดที่เรียก</div> 
        <div style="color:#FFF; font-size:130px; margin-top:-40px; margin-bottom:-40px; padding-left:50px;" class="thsan-light" align="left"><?php echo $row_rs_recent['channel_name']; ?></div> 
       <div style="color:#FFF; font-size:60px; margin-top:10px;" class="thsan-light">เวลาที่เรียก</div> 
      <div style="color:#FFF; font-size:120px; margin-top:-50px; margin-bottom:-40px;padding-left:50px;" class="thsan-light" align="left"><?php echo substr($row_rs_recent['call_datetime'],10,6); ?></div> 
      <?php } ?>

      </div>
    </div>
    <div class="right1">
      <div align="center" style="font-size:80px; margin-bottom:-10px; color:#FFF" class="thsan-bold">คิวที่เรียกแล้ว</div>
      <div class="container2">
        <div class="left2 thsan-light" align="center" style="margin-bottom:-30px;">หมายเลขคิว</div>
        <div class="right2 thsan-light" align="center" style="margin-bottom:-30px;">เวลาที่เรียก</div>
        
        </div>
     <?php do { ?> 
      <div class="container3">
        <div class="left3 thsan-light" align="center" style="margin-bottom:-10px; margin-top:-55px; margin-left:0px; margin-right:0px; font-size:180px;"><?php echo $row_rs_last['main_dep_queue']; ?></div>
        <div class="right3 thsan-light" style="font-size:120px;  margin-top:-10px;" align="center"><?php echo substr($row_rs_last['call_datetime'],10,6); ?></div>
        
        </div>
  <?php } while ($row_rs_last = mysql_fetch_assoc($rs_last)); ?>
      </div>
  </div>
</body>
</html>
<?php
mysql_free_result($rs_recent);

mysql_free_result($rs_last);
?>
