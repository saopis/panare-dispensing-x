<?php require_once('Connections/hos.php'); ?>

<?php
ob_start();
session_start();
?>
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
echo "asdfsd";
$get_ip=$_SERVER["REMOTE_ADDR"];
// check การ login
mysql_select_db($database_hos, $hos);
$query_delete = "delete from ".$database_kohrx.".kohrx_login_check where last_time < CURDATE()";
$delete = mysql_query($query_delete, $hos) or die(mysql_error());

if($_SESSION["username_log"]==""){
header("location: login.php"); //ไม่ถูกต้องให้กับไปหน้าเดิม
exit();
}
else{
mysql_select_db($database_hos, $hos);
$query_login_check = "SELECT *,format((TIMESTAMPDIFF(SECOND,last_time, NOW())/60),2) as timediff from ".$database_kohrx.".kohrx_login_check where login_name='".$_SESSION["username_log"]."' and ipaddress='".$get_ip."'";
$login_check = mysql_query($query_login_check, $hos) or die(mysql_error());
$row_login_check = mysql_fetch_assoc($login_check);
$totalRows_login_check = mysql_num_rows($login_check);

if($totalRows_login_check<>0){
//ถ้าเกิน 1 ชั่วโมงให้ logout
if($row_login_check['timediff']>=60){
echo "<script>parent.location='login.php';</script>";
exit();
	}
}
else {
	header("parent.location: login.php");	
	}
mysql_free_result($login_check);
}

function dateDifference($date1, $date2)
	{		
		$date1=strtotime($date1);
		$date2=strtotime($date2); 
		$diff = abs($date1 - $date2);
		
		$day = $diff/(60*60*24); // in day
		$dayFix = floor($day);
		$dayPen = $day - $dayFix;
		if($dayPen > 0)
		{
			$hour = $dayPen*(24); // in hour (1 day = 24 hour)
			$hourFix = floor($hour);
			$hourPen = $hour - $hourFix;
			if($hourPen > 0)
			{
				$min = $hourPen*(60); // in hour (1 hour = 60 min)
				$minFix = floor($min);
				$minPen = $min - $minFix;
				if($minPen > 0)
				{
					$sec = $minPen*(60); // in sec (1 min = 60 sec)
					$secFix = floor($sec);
				}
			}
		}
		$str = "";
		if($dayFix > 0)
			$str+= $dayFix*24*60*60;
		if($hourFix > 0)
			$str+= $hourFix*60*60;
		if($minFix > 0)
			$str+= $minFix*60;
		if($secFix > -30)
			$str+= $secFix;
		return $str;
	}
	

	
mysql_select_db($database_hos, $hos);
$query_rs_setting = "select * from ".$database_kohrx.".kohrx_dispensing_setting order by id ASC";
$rs_setting = mysql_query($query_rs_setting, $hos) or die(mysql_error());
$row_rs_setting = mysql_fetch_assoc($rs_setting);
$totalRows_rs_setting = mysql_num_rows($rs_setting);

$i=0; do{ $i++;
$row_setting[$i]=$row_rs_setting['value'];} 
while($row_rs_setting = mysql_fetch_assoc($rs_setting));

mysql_select_db($database_hos, $hos);
$query_channel = "SELECT n.channel_name,q.room_id,NOW() as datenow from ".$database_kohrx.".kohrx_queue_caller_channel q left outer join ".$database_kohrx.".kohrx_queue_caller_channel_name n on n.id=q.channel WHERE ip='".$get_ip."'";
$channel = mysql_query($query_channel, $hos) or die(mysql_error());
$row_channel = mysql_fetch_assoc($channel);
$totalRows_channel = mysql_num_rows($channel);

if(isset($_GET['do'])&&($_GET['do']=="delete")){
mysql_select_db($database_hos, $hos);
$query_rs_update = "update ".$database_kohrx.".kohrx_queued set q_delete = 'Y' where queue='".$_GET['queue']."' and room_id='".$row_channel['room_id']."' and vn='".$_GET['vn']."'";
$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());
	}
if(isset($_GET['do'])&&($_GET['do']=="cancel")){
mysql_select_db($database_hos, $hos);
$query_rs_update = "update ".$database_kohrx.".kohrx_queued set q_delete = NULL where queue='".$_GET['queue']."' and room_id='".$row_channel['room_id']."' and vn='".$_GET['vn']."'";
$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());
	}
if(isset($_GET['do'])&&($_GET['do']=="delete2")){
mysql_select_db($database_hos, $hos);
$query_rs_update = "delete from ".$database_kohrx.".kohrx_queued where queue='".$_GET['queue']."' and vn='".$_GET['vn']."' and room_id='".$row_channel['room_id']."'";
$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());
	}

if(isset($_GET['pay'])&&($_GET['pay']!="")){
	$pay=$_GET['pay'];
	}
else{
	$pay=1;
	}
$hn=sprintf("%".$row_setting[24]."d", $_GET['hn']);
if(isset($_GET['hn'])&&($_GET['hn']!="")){
	$search_hn=" and p.hn='".$hn."'";
	}
	
if($pay==1||$pay==""){
	$condition2=" and payed is NULL and not_response is NULL ";
	}
else if($pay==2) {
	$condition2="and payed is not NULL";
	}
else if($pay==3) {
	$condition2="and not_response = 'Y' ";
	}
	
mysql_select_db($database_hos, $hos);
$query_rs_q_list = "select q.*,concat(p.pname,p.fname,' ',p.lname) as ptname,n.channel_name,c.recent_rx_queue_datetime from ".$database_kohrx.".kohrx_queued q 
left outer join patient p on p.hn=q.hn 
left outer join ".$database_kohrx.".kohrx_queue_caller_list l on l.rx_queue=q.queue and l.hn=q.hn and l.room_id=q.room_id 
left outer join ".$database_kohrx.".kohrx_queue_caller_channel c on c.room_id=q.room_id and q.queue=c.recent_rx_queue and substr(c.recent_rx_queue_datetime,1,10)=substr(q.queue_datetime,1,10)
left outer join ".$database_kohrx.".kohrx_queue_caller_channel_name n on n.id=c.channel where q.room_id='".$row_channel['room_id']."' ".$condition2.$search_hn." and   substr(queue_datetime,1,10)=CURDATE() group by vn order by queue ASC";
$rs_q_list = mysql_query($query_rs_q_list, $hos) or die(mysql_error());
$row_rs_q_list = mysql_fetch_assoc($rs_q_list);
$totalRows_rs_q_list = mysql_num_rows($rs_q_list);

$curdate=date('d')."/".date('m')."/".(date('Y')+543);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>

<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<style>
.donate-now {
     list-style-type:none;
     margin:0 0 0 0;
     padding:0;
	 
}

.donate-now li {
     float:left;
     margin:0 10px 0 0;
    width:100px;
    height:30px;
    position:relative;
	
}

.donate-now label, .donate-now input {
    display:block;
    position:absolute;
    top:0;
    left:0;
    right:0;
    bottom:0;
		border-radius: 20px;

	
}

.donate-now input[type="radio"] {
    opacity:0.011;
    z-index:100;
}

.donate-now input[type="radio"]:checked + label {
    background:#C91203;
	color:#FFFFFF;
	font-weight:bold;
	font-size:14px;
		border-radius: 20px;

	
}

.donate-now label {
     padding:2px;
	 background-color:#009999; 
     cursor:pointer;
    z-index:90;
	color: #FFFFFF;
		font-size:14px;


}

.donate-now label:hover {
     background: #327062;
	 color:#FFFFFF;
}

</style>

</head>

<body>
<?php if ($totalRows_rs_q_list > 0) { // Show if recordset not empty ?>
  <table width="1000" border="0" cellpadding="3" cellspacing="0" class="thfont font14 " style="border-bottom:solid 1px #999999" >
    <tr class="font_border" >
      <td width="69" align="center" bgcolor="#F4F4F4">Q</td>
      <td width="253" align="center" bgcolor="#F4F4F4">HN</td>
      <td width="281" align="center" bgcolor="#F4F4F4">ชื่อผู้ป่วย</td>
      <td width="147" align="center" bgcolor="#F4F4F4">เวลาวางบัตร</td>
      <td width="253" align="center" bgcolor="#F4F4F4">ช่องบริการปัจจุบัน</td>
      <td width="147" align="center" bgcolor="#F4F4F4">ยกเลิก</td>
      <td width="147" align="center" bgcolor="#F4F4F4">&nbsp;</td>
    </tr>
    <?php  do {
	$i++;
	if($i%2==0)
	{
	$bg = "#F9F9F9";
	}
	else
	{
	$bg = "#FFFFFF";
	}
  ?>
      <tr  style="cursor:pointer" class="grid4" bgcolor="<?php echo $bg; ?>">
        <td onclick="formSubmit4('HN','displayIndiv','indicator','?date1=<?php echo $curdate; ?>&hn_search=<?php echo $row_rs_q_list['hn']; ?>&esp_drug=show&vn2=<?php echo $row_rs_q_list['vn']; ?>','<?php echo $curdate; ?>'); q_list('<?php echo $row_rs_q_list['hn']; ?>','<?php echo $curdate; ?>')" align="center" class="thfont font16 font_bord"><?php echo $row_rs_q_list['queue']; ?></td>
        <td onclick="formSubmit4('HN','displayIndiv','indicator','?date1=<?php echo $curdate; ?>&hn_search=<?php echo $row_rs_q_list['hn']; ?>&esp_drug=show&vn2=<?php echo $row_rs_q_list['vn']; ?>','<?php echo $curdate; ?>'); q_list('<?php echo $row_rs_q_list['hn']; ?>','<?php echo $curdate; ?>')" align="center"><?php echo $row_rs_q_list['hn']; ?></td>
        <td align="center" bgcolor="<?=$bg;?>" onclick="formSubmit4('HN','displayIndiv','indicator','?date1=<?php echo $curdate; ?>&hn_search=<?php echo $row_rs_q_list['hn']; ?>&esp_drug=show&vn2=<?php echo $row_rs_q_list['vn']; ?>','<?php echo $curdate; ?>'); q_list('<?php echo $row_rs_q_list['hn']; ?>','<?php echo $curdate; ?>')"><?php echo $row_rs_q_list['ptname']; ?></td>
        <td onclick="formSubmit4('HN','displayIndiv','indicator','?date1=<?php echo $curdate; ?>&hn_search=<?php echo $row_rs_q_list['hn']; ?>&esp_drug=show&vn2=<?php echo $row_rs_q_list['vn']; ?>','<?php echo $curdate; ?>'); q_list('<?php echo $row_rs_q_list['hn']; ?>','<?php echo $curdate; ?>')" align="center"><?php echo substr($row_rs_q_list['queue_datetime'],10,9); ?></td>
        <td onclick="formSubmit4('HN','displayIndiv','indicator','?date1=<?php echo $curdate; ?>&amp;hn_search=<?php echo $row_rs_q_list['hn']; ?>&amp;esp_drug=show&amp;vn2=<?php echo $row_rs_q_list['vn']; ?>','<?php echo $curdate; ?>'); q_list('<?php echo $row_rs_q_list['hn']; ?>','<?php echo $curdate; ?>')" align="center" style="color:#FF0000; font-size:18px;" class="font_bord"><?php if(dateDifference($row_rs_q_list['recent_rx_queue_datetime'], $row_channel['datenow'])<=30){ echo $row_rs_q_list['channel_name']; } ?></td>
        <td align="center" ><?php if($row_rs_q_list['q_delete']!='Y'){ ?><img onclick="if(confirm('ต้องการลบคิวนี้ใช่ไหม')==true){page_load('displayIndiv','queue_list.php?queue=<?php echo $row_rs_q_list['queue']; ?>&vn=<?php echo $row_rs_q_list['vn']; ?>&do=delete'); }" src="images/delete.png" width="27" height="26" /><?php } else { echo "<img src=\"images/right_icon.png\" width=\"34\" height=\"34\" onclick=\"if(confirm('ต้องการลบคิวนี้ใช่ไหม')==true){page_load('displayIndiv','queue_list.php?queue=".$row_rs_q_list['queue']."&vn=".$row_rs_q_list['vn']."&do=cancel'); }\" />"; } ?></td>
        <td align="center" ><input name="q_delete" type="button" onclick="if(confirm('ต้องการลบคิวนี้ใช่ไหม')==true){page_load('displayIndiv','queue_list.php?queue=<?php echo $row_rs_q_list['queue']; ?>&vn=<?php echo $row_rs_q_list['vn']; ?>&do=delete2'); }" class="button_red thfont" style="height:30px; cursor:pointer" value="ลบคิว" /></td>
      </tr>
      <?php

   } while ($row_rs_q_list = mysql_fetch_assoc($rs_q_list)); ?>
  </table>
 <?php } // Show if recordset not empty ?>
<p>
  <input type="hidden" name="do4" id="do4" />
  <input type="hidden" name="id4" id="id4" />
</p>

<?php if ($totalRows_rs_q_list == 0) { // Show if recordset empty ?>
  <div align="center" class="thfont font_bord" style="width:1000px;"><h3>ไม่พบข้อมูล</h3></div>
  <?php } // Show if recordset empty ?>
</body>
</html>
<?php
mysql_free_result($rs_q_list);
?>
