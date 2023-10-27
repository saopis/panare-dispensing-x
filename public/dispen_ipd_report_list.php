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
//time function

function doctorname($doctor){
    global $hos;
    mysql_select_db($database_hos, $hos);
    $query_rs_doctor = "SELECT name from doctor where code='".$doctor."'";
    $rs_doctor = mysql_query($query_rs_doctor, $hos) or die(mysql_error());
    $row_rs_doctor = mysql_fetch_assoc($rs_doctor);    
    
    $doctorname= $row_rs_doctor['name'];
    
    mysql_free_result($rs_doctor);
    return $doctorname;
}
function doctorcode2username($doctor){
    global $hos;
    mysql_select_db($database_hos, $hos);
    $query_rs_doctor = "SELECT loginname from opduser where doctorcode='".$doctor."'";
    $rs_doctor = mysql_query($query_rs_doctor, $hos) or die(mysql_error());
    $row_rs_doctor = mysql_fetch_assoc($rs_doctor);    
    
    $doctorname= $row_rs_doctor['loginname'];
    
    mysql_free_result($rs_doctor);
    return $doctorname;
}
function username2doctorcode($doctor){
    global $hos;
    mysql_select_db($database_hos, $hos);
    $query_rs_doctor = "SELECT doctorcode from opduser where loginname='".$doctor."'";
    $rs_doctor = mysql_query($query_rs_doctor, $hos) or die(mysql_error());
    $row_rs_doctor = mysql_fetch_assoc($rs_doctor);    
    
    $doctorname= $row_rs_doctor['doctorcode'];
    
    mysql_free_result($rs_doctor);
    return $doctorname;
}

if($_GET['export']=="Y"){
$strExcelFileName="dispen_report_ipd.xls";
header("Content-Type: application/x-msexcel; name=\"$strExcelFileName\"");
header("Content-Disposition: inline; filename=\"$strExcelFileName\"");
header("Pragma:no-cache");
}

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
		$esdate1=substr($date11[0],2,4).$date11[1].$date11[2];
		
		$date11=explode("-",$date2);
		$esdate2=substr($date11[0],2,4).$date11[1].$date11[2];	
		
mysql_select_db($database_hos, $hos);
$query_rs_setting = "select * from ".$database_kohrx.".kohrx_dispensing_setting order by id ASC";
$rs_setting = mysql_query($query_rs_setting, $hos) or die(mysql_error());
$row_rs_setting = mysql_fetch_assoc($rs_setting);
$totalRows_rs_setting = mysql_num_rows($rs_setting);

$i=0; do{ $i++;
$row_setting[$i]=$row_rs_setting['value'];} 
while($row_rs_setting = mysql_fetch_assoc($rs_setting));

if(isset($_POST['person0'])&&($_POST['person0']!="")){
	$condition.=" and i.entry_staff= '".doctorcode2username($_POST['person0'])."'";
	}		
if(isset($_GET['person0'])&&($_GET['person0']!="")){
	$condition.=" and i.entry_staff= '".doctorcode2username($_GET['person0'])."'";
	}		
if(isset($_POST['person1'])&&($_POST['person1']!="")){
	$condition.=" and pp.prepare_staff= '".doctorcode2username($_POST['person1'])."'";
	}		
if(isset($_GET['person1'])&&($_GET['person1']!="")){
	$condition.=" and pp.prepare_staff= '".doctorcode2username($_GET['person1'])."'";
	}		
if(isset($_POST['person3'])&&($_POST['person3']!="")){
	$condition.=" and p.pay_staff= '".doctorcode2username($_POST['person3'])."'";
	}		
if(isset($_GET['person3'])&&($_GET['person3']!="")){
	$condition.=" and p.pay_staff= '".doctorcode2username($_GET['person3'])."'";
	}		
	
if(isset($_POST['an'])&&($_POST['an']!="")){	
	$condition.=" and i.an='".$_POST['an']."'";
	$condition.="";
	}
if(isset($_POST['an'])&&($_POST['an']=="")){	
	$condition.="";
	$condition.=" and i.rxdate between '".$date1."' and '".$edate2."'";
	}
if(isset($_GET['an'])&&($_GET['an']!="")){	
	$condition.=" and i.an='".$_GET['an']."'";
	$condition.="";
	}
if(isset($_GET['an'])&&($_GET['an']=="")){	
	$condition.="";
	$condition.=" and i.rxdate between '".$date1."' and '".$date2."'";
	}

if(isset($_POST['patient_type'])&&($_POST['patient_type']!="")){	
$patient_type=$_POST['patient_type'];
if($_POST['patient_type']==1){
	$ipt_type="IRx";
	}
if($_POST['patient_type']==2){
	$ipt_type="Hme";
	}
}

if(isset($_GET['patient_type'])&&($_GET['patient_type']!="")){	
$patient_type=$_GET['patient_type'];
if($_GET['patient_type']==1){
	$ipt_type="IRx";
	}
if($_GET['patient_type']==2){
	$ipt_type="Hme";
	}
}

mysql_select_db($database_hos, $hos);
$query_admit = "SELECT o.name,o.doctorcode,i.an,i.order_no,i.rxdate,p.pay_staff,i.rxtime,substring(p.pay_datetime,11,19) as paytime,pp.prepare_staff,concat(pt.pname,pt.fname,'  ',pt.lname) as ptname,ipt.regtime FROM ipt_order_no i left outer join opduser o on o.loginname=i.entry_staff  left outer join doctor d on d.code=o.doctorcode left outer join ipt_dispense_pay p on p.order_no=i.order_no left outer join ipt_dispense_prepare pp on pp.order_no=i.order_no left outer join ipt ipt on ipt.an=i.an left outer join patient pt on pt.hn=ipt.hn WHERE d.code in (select doctorcode from ".$database_kohrx.".kohrx_rx_person) and i.order_type='".$ipt_type."' and p.pay_staff !=''".$condition." group by i.order_no order by i.order_no";
//echo $query_admit;
$admit = mysql_query($query_admit, $hos) or die(mysql_error());
$row_admit = mysql_fetch_assoc($admit);
$totalRows_admit = mysql_num_rows($admit);

mysql_select_db($database_hos, $hos);
$query_rs_dispen1 = "SELECT Count(DISTINCT o.order_no) AS cc
FROM opitemrece o
  LEFT OUTER JOIN drugitems d ON o.icode = d.icode
  LEFT OUTER JOIN ipt_order_no i ON o.an = i.an
  left outer join ipt a on o.an=a.an
WHERE a.dchdate BETWEEN '".$date1."' AND '".$date2."' AND (o.an IS NOT NULL OR
    Length(o.an) = 9) AND d.icode IS NOT NULL";
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
<?php include('include/bootstrap/datatable_report.php'); ?>
</head>

<body>
<?php if($totalRows_admit<>0){ ?>
<? if(!isset($_GET['export'])&&($_GET['export']!="Y")){ ?>จำนวนวันนอน <?php echo number_format($totalrx); ?> วัน
<?php } ?>

<table  id="example" class="table table-striped table-bordered table-hover table-sm  " style="width:100%; font-size:14px" >  
	<thead>
	<tr>
    <th ><strong>no.</strong></th>
    <th ><strong>an</strong></th>
    <th ><strong>ชื่อผู้ป่วย</strong></th>
    <th ><strong>วันที่</strong></th>
    <th ><strong>เวลา</strong></th>
    <th ><strong>เวลาบันทึก</strong></th>
    <th ><strong>เวลาจ่าย</strong></th>
    <th ><strong>เวลารอ</strong></th>
    <th ><strong>ผู้บันทึก</strong></th>
    <th ><strong>ผู้จัด</strong></th>
    <th ><strong>ผู้จ่าย</strong></th>
  </tr>
</thead>
<tbody>
  <?php $i=0; do { $i++;   ?>
  <tr class="thfont font12">
    <td align="center" ><?=$i; ?></td>
    <td align="center" ><?=$row_admit['an']; ?></td>
    <td align="center" ><?=$row_admit['ptname']; ?></td>
    <td align="center" ><?=date_db2th($row_admit['rxdate']); ?></td>
    <td align="center" ><?=$row_admit['regtime']; ?></td>
    <td align="center" ><?=$row_admit['rxtime']; ?></td>
    <td align="center" ><?=$row_admit['paytime']; ?></td>
    <td align="center" ><?=number_format(TimeDiff($row_admit['rxtime'],$row_admit['paytime'])); ?></td>
    <td align="center" ><?=$row_admit['name']; ?></td>
    <td align="center" ><?=doctorname(username2doctorcode($row_admit['prepare_staff'])); ?></td>
    <td align="center" ><?=doctorname(username2doctorcode($row_admit['pay_staff'])); ?></td>
    <? 	  $sum_time=(number_format(TimeDiff($row_admit['rxtime'],$row_admit['paytime'])))+$sum_time; ?>
  </tr>
  <?php 

  } while ($row_admit = mysql_fetch_assoc($admit)); ?>
</tbody>
<tfoot>
  <tr class="table_head_small">
    <td height="30" colspan="11" align="center" class="big_black16" style="border-top:solid 1px #999999">เวลาเฉลี่ยประมาณ <span class="big_red16">&nbsp;
      <?=round($sum_time/$totalRows_admit); ?>
      &nbsp;&nbsp;</span> นาที</td>
  </tr>
</tfoot>
</table>
<?php } else{ echo nodata(); } ?>
</body>
</html>