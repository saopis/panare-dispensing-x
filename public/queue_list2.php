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
mysql_select_db($database_hos, $hos);
$query_rs_setting = "select * from ".$database_kohrx.".kohrx_dispensing_setting order by id ASC";
$rs_setting = mysql_query($query_rs_setting, $hos) or die(mysql_error());
$row_rs_setting = mysql_fetch_assoc($rs_setting);
$totalRows_rs_setting = mysql_num_rows($rs_setting);

$i=0; do{ $i++;
$row_setting[$i]=$row_rs_setting['value'];} 
while($row_rs_setting = mysql_fetch_assoc($rs_setting));

mysql_select_db($database_hos, $hos);
$query_channel = "SELECT channel_name,kskdepart from ".$database_kohrx.".kohrx_queue_caller_channel q left outer join ".$database_kohrx.".kohrx_queue_caller_channel_name n on n.id=q.channel WHERE ip='$get_ip'";
$channel = mysql_query($query_channel, $hos) or die(mysql_error());
$row_channel = mysql_fetch_assoc($channel);
$totalRows_channel = mysql_num_rows($channel);

$date_now=substr((date('Y')+543),2,2).date('md');

if(isset($_GET['pay'])&&($_GET['pay']!="")){
	$pay=$_GET['pay'];
	}
else{
	$pay=1;
	}
	
mysql_select_db($database_hos, $hos);
$query_rs_setting = "select * from ".$database_kohrx.".kohrx_dispensing_setting order by id ASC";
$rs_setting = mysql_query($query_rs_setting, $hos) or die(mysql_error());
$row_rs_setting = mysql_fetch_assoc($rs_setting);
$totalRows_rs_setting = mysql_num_rows($rs_setting);

$i=0; do{ $i++;
$row_setting[$i]=$row_rs_setting['value'];} 
while($row_rs_setting = mysql_fetch_assoc($rs_setting));
mysql_free_result($rs_setting);

if(isset($_GET['hn'])&&($_GET['hn']!="")){

$hn=sprintf("%".$row_setting[24]."d", $_GET['hn']);
$condition=" and hn='".$hn."'";
}
mysql_select_db($database_hos, $hos);
if($pay==1){
$query_rs_q_list = "select v.vn,v.rx_queue,v.hn,v.pt_priority,pty.pcode,v.oqueue,(vns.rcpt_money-vns.paid_money) as pmoney, v.cur_dep_time,q2.stock_department_queue_no,count(s.vn) as svn_count,
count(r.vn) as rx_count,count(r1.vn) as pay_count  , count(t.vn) as finance_count,count(r2.vn) as rx_print_count   , k.department  , concat(p.pname,p.fname,'  ',p.lname) as ptname  
from ovst v left outer join patient p on p.hn=v.hn  left outer join pq_doctor s on s.vn=v.vn  left outer join rx_operator r on r.vn=v.vn  left outer join rcpt_print t on t.vn=v.vn  
left outer join pttype pty on pty.pttype = v.pttype  left outer join kskdepartment k on k.depcode=v.last_dep  left outer join rx_operator r1 on r1.vn=v.vn and r1.pay='Y' 
left outer join rx_operator r2 on r2.vn=v.vn and r2.rx_print='Y'  left outer join vn_stat vns on vns.vn = v.vn left outer join opitemrece rc on rc.vn=v.vn left outer join ovst_seq q2 on q2.vn = v.vn and q2.stock_department_id = 0 
where v.vstdate=CURDATE()  and v.vn in (select vn from ovst where vn like '".$date_now."%' and cur_dep='".$row_channel['kskdepart']."' ".$condition.")  and v.vn not in (select r.vn from rx_operator r left outer join vn_stat v on v.vn=r.vn where r.pay='Y' and r.vn like '".$date_now."%' ".$condition.") 
and v.cur_dep = '".$row_channel['kskdepart']."' and rc.icode like '1%' group by v.vn,v.rx_queue,v.hn,v.pt_priority,pty.pcode,v.oqueue,v.cur_dep_time,p.pname,p.fname,p.lname ,k.department,q2.stock_department_queue_no,vns.rcpt_money,vns.paid_money 
order by v.pt_priority desc,v.rx_queue,v.cur_dep_time";

}
else if($pay==2){
$query_rs_q_list="
select v.vn,v.rx_queue,v.hn,v.pt_priority,pty.pcode,v.oqueue,v.cur_dep_time,q2.stock_department_queue_no,(vns.rcpt_money-vns.paid_money) as pmoney,count(s.vn) as svn_count,  count(r.vn) as rx_count,
count(r1.vn) as pay_count  , count(t.vn) as finance_count,count(r2.vn) as rx_print_count   , k.department , concat(p.pname,p.fname,'  ',p.lname) as ptname  
from ovst v left outer join patient p on p.hn=v.hn  left outer join pq_doctor s on s.vn=v.vn  left outer join rx_operator r on r.vn=v.vn  left outer join rcpt_print t on t.vn=v.vn  
left outer join pttype pty on pty.pttype = v.pttype  left outer join kskdepartment k on k.depcode=v.last_dep  left outer join rx_operator r1 on r1.vn=v.vn and r1.pay='Y' 
left outer join rx_operator r2 on r2.vn=v.vn and r2.rx_print='Y'  left outer join vn_stat vns on vns.vn = v.vn left outer join opitemrece rc on rc.vn=v.vn  left outer join ovst_seq q2 on q2.vn = v.vn and q2.stock_department_id = 0 
where v.vstdate=CURDATE()  and v.vn in (select r.vn from rx_operator r,ovst o where o.vstdate = CURDATE() and o.vn = r.vn and r.pay='Y' ".$condition.") and rc.icode like '1%' 
group by v.vn,v.rx_queue,v.hn,v.pt_priority,pty.pcode,v.oqueue,v.cur_dep_time,p.pname,p.fname,p.lname ,k.department,q2.stock_department_queue_no,vns.rcpt_money,vns.paid_money order by v.pt_priority desc,v.rx_queue,v.cur_dep_time
";

}
$rs_q_list = mysql_query($query_rs_q_list, $hos) or die(mysql_error());
$row_rs_q_list = mysql_fetch_assoc($rs_q_list);
$totalRows_rs_q_list = mysql_num_rows($rs_q_list);

$curdate=date('d')."/".date('m')."/".(date('Y')+543);

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
			$str.= $dayFix." วัน ";
		if($hourFix > 0)
			$str.= $hourFix." ชม. ";
		if($minFix > 0)
			$str.= $minFix." น. ";
		if($secFix > 0)
			$str.= $secFix." วิ. ";
		return $str;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />

</head>

<body>
<form id="form4">

<table width="1000" border="0" cellpadding="3" cellspacing="0" class="thfont font14 " style="border-bottom:solid 1px #999999" >
  <tr class="font_border" >
    <td  align="center" bgcolor="#F4F4F4">ลำดับ</td>
    <td  align="center" bgcolor="#F4F4F4">oQ</td>
    <td  align="center" bgcolor="#F4F4F4">RxQ</td>
    <td  align="center" bgcolor="#F4F4F4">HN</td>
    <td  align="center" bgcolor="#F4F4F4">ชื่อผู้ป่วย</td>
    <td  align="center" bgcolor="#F4F4F4">จากแผนก</td>
    <td  align="center" bgcolor="#F4F4F4">Rx</td>
    <td  align="center" bgcolor="#F4F4F4">Fi</td>
    <td  align="center" bgcolor="#F4F4F4">สิทธิ์</td>
    <td  align="center" bgcolor="#F4F4F4">เวลารอ</td>
    <td  align="center" bgcolor="#F4F4F4">เงิน</td>
    </tr>
  <?php $i=1; do { 
  	$date1 = date('Y-m-d')." ".$row_rs_q_list['cur_dep_time'];
	
	$then = $date1;
	$now = time();


  ?>
  <tr onclick="detail_load('<?php echo $row_rs_q_list['hn']; ?>','<?php echo $curdate; ?>','hn'); q_list('<?php echo $row_rs_q_list['hn']; ?>','<?php echo $curdate; ?>')" style="cursor:pointer" class="grid4">
    <td align="center"><?php echo $i; ?></td>
    <td align="center"><?php echo $row_rs_q_list['oqueue']; ?></td>
    <td align="center"><?php echo $row_rs_q_list['rx_queue']; ?></td>
    <td align="center"><?php echo $row_rs_q_list['hn']; ?></td>
    <td align="center"><?php echo $row_rs_q_list['ptname']; ?></td>
    <td align="center"><?php echo $row_rs_q_list['department']; ?></td>
    <td align="center"><?php if($pay==2){ echo "<img src=\"images/11949844622098606321arrow1_sergio_luiz_arauj_01.svg.med.png\" width=\"19\" height=\"19\" />"; } else if($row_rs_q_list['rx_count']==1){ echo "<img src=\"images/right_icon3.png\" width=\"18\" height=\"14\" />"; } ?></td>
    <td align="center"><?php if($row_rs_q_list['finance_count']!=0){ echo "<img src=\"images/right_icon2.png\" width=\"19\" height=\"15\" />"; }; ?></td>
    <td align="center"><?php echo $row_rs_q_list['pcode']; ?></td>
    <td align="center"><?php echo dateDifference($date1, date('Y-m-d H:i:s')); ?></td>
    <td align="center"><?php echo $row_rs_q_list['pmoney']; ?></td>
    </tr>
  <?php
  $i++;

   } while ($row_rs_q_list = mysql_fetch_assoc($rs_q_list)); ?>
</table>
<p>
  <input type="hidden" name="do4" id="do4" />
  <input type="hidden" name="id4" id="id4" />
</p>
</form>
</body>
</html>
<?php
mysql_free_result($rs_q_list);
mysql_free_result($channel);

?>
