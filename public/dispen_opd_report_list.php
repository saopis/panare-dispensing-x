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

include('include/function.php');


if($_GET['export']=="Y"){
$strExcelFileName="dispen_report.xls";
header("Content-Type: application/x-msexcel; name=\"$strExcelFileName\"");
header("Content-Disposition: inline; filename=\"$strExcelFileName\"");
header("Pragma:no-cache");
}
mysql_select_db($database_hos, $hos);
$query_rs_setting = "select * from ".$database_kohrx.".kohrx_dispensing_setting order by id ASC";
$rs_setting = mysql_query($query_rs_setting, $hos) or die(mysql_error());
$row_rs_setting = mysql_fetch_assoc($rs_setting);
$totalRows_rs_setting = mysql_num_rows($rs_setting);

$i=0; do{ $i++;
$row_setting[$i]=$row_rs_setting['value'];} 
while($row_rs_setting = mysql_fetch_assoc($rs_setting));

if(isset($_POST['do'])&&($_POST['do']!="")){
$do=$_POST['do'];
}
if(isset($_GET['do'])&&($_GET['do']!="")){
$do=$_GET['do'];
}
	 
if($do=="search"){

if(isset($_POST['datestart'])&&($_POST['datestart']!="")){
$date1=$_POST['datestart'];
}
if(isset($_GET['datestart'])&&($_GET['datestart']!="")){
$date1=$_GET['datestart'];
}
if(isset($_POST['dateend'])&&($_POST['dateend']!="")){
$date2=$_POST['dateend'];
}
if(isset($_GET['dateend'])&&($_GET['dateend']!="")){
$date2=$_GET['dateend'];
}
		$date11=explode("-",$date1);
		$esdate1=substr(($date11[0]+543),2,4).$date11[1].$date11[2];
		
		$date11=explode("-",$date2);
		$esdate2=substr(($date11[0]+543),2,4).$date11[1].$date11[2];	


if(isset($_POST['time1'])&&($_POST['time1']!="")){
$time1=$_POST['time1'];
}
if(isset($_GET['time1'])&&($_GET['time1']!="")){
$time1=$_GET['time1'];
}
if(isset($_POST['time2'])&&($_POST['time2']!="")){
$time2=$_POST['time2'];
}
if(isset($_GET['time2'])&&($_GET['time2']!="")){
$time2=$_GET['time2'];
}
if(isset($_POST['person0'])&&($_POST['person0']!="")){
	$condition=" and k.pay_staff2= '".$_POST['person0']."'";
	}		
if(isset($_GET['person0'])&&($_GET['person0']!="")){
	$condition=" and k.pay_staff2= '".$_GET['person0']."'";
	}		
if(isset($_POST['person1'])&&($_POST['person1']!="")){
	$condition.=" and r.check_staff= '".$_POST['person1']."'";
	}		
if(isset($_GET['person1'])&&($_GET['person1']!="")){
	$condition.=" and r.check_staff= '".$_GET['person1']."'";
	}		
if(isset($_POST['person2'])&&($_POST['person2']!="")){
	$condition.=" and r.confirm_staff= '".$_POST['person2']."'";
	}		
if(isset($_GET['person2'])&&($_GET['person2']!="")){
	$condition.=" and r.confirm_staff= '".$_GET['person2']."'";
	}		
if(isset($_POST['person3'])&&($_POST['person3']!="")){
	$condition.=" and r.pay_staff= '".$_POST['person3']."'";
	}		
if(isset($_GET['person3'])&&($_GET['person3']!="")){
	$condition.=" and r.pay_staff= '".$_GET['person3']."'";
	}		

if(isset($_POST['room'])&&($_POST['room']!="")){
	if($room!=""){
	$condition.=" and k.room_id='".$_POST['room']."'";
	}
	else{
	$condition.="";
	}	
	}
if(isset($_GET['room'])&&($_GET['room']!="")){
	if($room!=""){
	$condition.=" and k.room_id='".$_GET['room']."'";
	}	
	else{
	$condition.="";
	}	
	}

if(isset($_POST['hn'])&&($_POST['hn']!="")){	
$shn=$_POST['hn'];
		$condition.=" and v.hn=LPAD('".$shn."','".$row_setting[24]."','0')";
}
if(isset($_GET['hn'])&&($_GET['hn']!="")){	
$shn=$_GET['hn'];
		$condition.=" and v.hn=LPAD('".$shn."','".$row_setting[24]."','0')";
}

if(isset($_GET['note'])&&($_GET['note']!="")){	
	if($_GET['nohave']==1){
		$condition.=" and locate('".$_GET['note']."',r.note)";
	}
	if($_GET['nohave']==2){
		$condition.=" and (r.note not like '%".$_GET['note']."%' or r.note='' or r.note is NULL) ";
	}
	
}

if(isset($_GET['receiver'])&&($_GET['receiver']!="")){	
$receiver=$_GET['receiver'];
$condition.=" and r.receiver ='".$receiver."'";}
//tambon
if(isset($_GET['tambon'])&&($_GET['tambon']!="")){	
$tambon=$_GET['tambon'];
$condition.=" and concat(p.chwpart,p.amppart,p.tmbpart) ='".$tambon."'";
}
//amp
if(isset($_GET['amp'])&&($_GET['amp']!="")&&($_GET['tambon']=="")){	
$amp=$_GET['amp'];
$condition.=" and concat(p.chwpart,p.amppart) ='".$amp."'";
}
//chw
if(isset($_GET['chw'])&&($_GET['chw']!="")&&($_GET['amp']=="")&&($_GET['tambon']=="")){	
$chw=$_GET['chw'];
$condition.=" and p.chwpart ='".$chw."'";
}    

    
mysql_select_db($database_hos, $hos);
$query_rs_dispen = "select v.vstdate,v.hn,v.oqueue,concat(p.pname,p.fname,' ',p.lname) as ptname , v.vsttime,r.rx_time,t.service6,service3, sec_to_time(time_to_sec(r.rx_time)-time_to_sec(t.service6)) as wait_time, o0.name as print_staff_name,o1.name as check_staff_name,o2.name as confirm_staff_name,o3.name as pay_staff_name, o4.name as rx_operator_staff_name,r.note,date_format(t.vstdate,'%d/%m/%Y') as visitdate,k.print_staff as print_staff2,k.room_id  
from rx_operator r 
left outer join ".$database_kohrx.".kohrx_dispen_staff_operation k on k.vn=r.vn 
left outer join ovst v on v.vn=r.vn 
left outer join patient p on p.hn=v.hn 
left outer join service_time t on t.vn=r.vn 
left outer join doctor o0 on o0.code = k.print_staff 
left outer join doctor o1 on o1.code = r.check_staff 
left outer join doctor o2 on o2.code = r.confirm_staff  
left outer join doctor o3 on o3.code = r.pay_staff 
left outer join ovst_seq oq on oq.vn = v.vn 
left outer join opduser o4 on o4.loginname = oq.last_rx_operator_staff  where r.pay='Y' and substring(r.vn,1,6) between '".$esdate1."' and '".$esdate2."' ".$condition." and t.vsttime between '".$time1."' and '".$time2."' order by v.vstdate,t.vsttime ASC ";
//echo $query_rs_dispen;
$rs_dispen = mysql_query($query_rs_dispen, $hos) or die(mysql_error());
$row_rs_dispen = mysql_fetch_assoc($rs_dispen);
$totalRows_rs_dispen = mysql_num_rows($rs_dispen);
    
mysql_select_db($database_hos, $hos);
$query_rs_dispen1 = "select count(distinct o.vn) as cc
from opitemrece o left outer join drugitems d on o.icode=d.icode
 where o.vstdate between '".$date1."' and '".$date2."'    and (o.an is null or length(o.an)=0)  and d.icode is not null ";
$rs_dispen1 = mysql_query($query_rs_dispen1, $hos) or die(mysql_error());
$row_rs_dispen1 = mysql_fetch_assoc($rs_dispen1);
$totalRows_rs_dispen1 = mysql_num_rows($rs_dispen1);

    $totalrx=$row_rs_dispen1['cc'];

mysql_free_result($rs_dispen1);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php include('include/bootstrap/datatable_report.php'); ?>
</head>

<body>
<?php if($totalRows_rs_dispen<>0){ ?>
<table id="tables" class="table table-striped table-bordered table-hover display nowrap " style="width:100%; font-size:14px" >  
	<thead>
    <tr class="thfont">
    <th align="center" valign="top" ><strong>no.</strong></th>
    <th align="center" valign="top" ><strong>hn</strong></th>
    <th align="center" valign="top" ><strong>ชื่อผู้ป่วย</strong></th>
    <th align="center" valign="top" ><strong>วันที่</strong></th>
    <th align="center" valign="top" ><strong>เวลามา</strong></th>
    <th align="center" valign="top" ><strong>เวลาพิมพ์</strong></th>
    <th align="center" valign="top" ><strong>เวลาจ่าย</strong></th>
    <th align="center" valign="top" ><strong>เวลารอ</strong></th>
    <th align="center" valign="top" ><strong>ผู้พิมพ์</strong></th>
    <th align="center" valign="top" ><strong>ผู้จัด</strong></th>
    <th align="center" valign="top" ><strong>ผู้ตรวจสอบ</strong></th>
    <th align="center" valign="top" ><strong>ผู้จ่าย</strong></th>
    <th align="center" valign="top" >note</th>
  </tr>
</thead>
<tbody>
   <?php $i=0; do { $i++; ?>
    <tr >
     <td align="center"  class="thfont">
      <?php print $i; ?>
     </td>
   
      <td align="center"  class="thfont">
      <?php print $row_rs_dispen['hn']; ?>
      </td>
      <td align="center"  class="thfont">
      <?php print $row_rs_dispen['ptname']; ?>
      </td>
      <td align="center"  class="thfont">
      <?php print $row_rs_dispen['visitdate']; ?>
      </td>
      <td align="center"  class="thfont">
      <?php print $row_rs_dispen['vsttime']; ?>
      </td>
      <td align="center"  class="thfont">
      <?php if($_GET['print_type']==1){$printtime=$row_rs_dispen['service6'];}else {$printtime=$row_rs_dispen['service3'];} echo  $printtime; ?>
      </td>
      <td align="center"  class="thfont">
      <?php print $row_rs_dispen['rx_time']; ?>
      </td>
      <td align="center"  class="thfont">
      <?php if($_GET['print_type']==1){$printtime=$row_rs_dispen['service6'];}else {$printtime=$row_rs_dispen['service3'];} if($printtime==""){ echo 0; } else { echo  TimeDiff(substr($printtime,0,5),substr($row_rs_dispen['rx_time'],0,5)); } ?>
      </td>
      <td align="center"  class="thfont">
      <?php print $row_rs_dispen['print_staff_name']; ?>
      </td>
      <td align="center"  class="thfont">
      <?php print $row_rs_dispen['check_staff_name']; ?>
      </td>
      <td align="center"  class="thfont">
      <?php print $row_rs_dispen['confirm_staff_name']; ?>
      </td>
      <td align="center"  class="thfont">
      <?php print $row_rs_dispen['pay_staff_name']; ?>
      </td>
      <td align="center"  class="thfont">
      <?php print $row_rs_dispen['note']; ?>
      </td>
      <? 	  if($printtime==""){$sum_time+=$sumtime; } else {$sum_time=(TimeDiff(substr($printtime,0,5),substr($row_rs_dispen['rx_time'],0,5)))+$sum_time; } ?>
  </tr> <?php } while ($row_rs_dispen = mysql_fetch_assoc($rs_dispen)); ?>
   <tr class="table_head_small">
     <td height="30" colspan="13" align="center" class="big_black16" style="border-top:solid 1px #999999">เวลาเฉลี่ยประมาณ   <span class="big_red16">&nbsp; 
     <?php if($totalRows_rs_dispen<>0){ echo round($sum_time/$totalRows_rs_dispen); } ?>&nbsp;&nbsp;</span> นาที</td>
   </tr>
 </tbody>
</table>
<?php } else { echo nodata(); } ?>
</body>
</html>
<?php
mysql_free_result($rs_dispen);
?>
