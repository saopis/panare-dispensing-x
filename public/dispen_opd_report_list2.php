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
	 function TimeDiff($strTime1,$strTime2)
	 {
				return (strtotime($strTime2) - strtotime($strTime1))/  ( 60 ); // 1 Hour =  60*60
	 }

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
	$condition.=" and r.note='".$_GET['note']."'";
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
$query_rs_dispen = "select v.vstdate,v.hn,concat(p.pname,p.fname,' ',p.lname) as ptname , v.vsttime,r.rx_time,date_format(t.vstdate,'%d/%m/%Y') as visitdate,p.addrpart,p.moopart,a.full_name,c.name as pcuname
from rx_operator r 
left outer join dispensing.kohrx_dispen_staff_operation k on k.vn=r.vn 
left outer join ovst v on v.vn=r.vn 
left outer join patient p on p.hn=v.hn 
left outer join thaiaddress a on a.addressid=p.addressid
left outer join hospcode_cup c on c.chwpart=p.chwpart and c.amppart=p.amppart and c.tmbpart=p.tmbpart and LPAD(c.moopart,3,0)=LPAD(p.moopart,3,0)
left outer join service_time t on t.vn=r.vn 
left outer join ovst_seq oq on oq.vn = v.vn  where r.pay='Y' and substring(r.vn,1,6) between '".$esdate1."' and '".$esdate2."'  ".$condition." and t.vsttime between '".$time1."' and '".$time2."' order by pcuname,p.moopart ASC ";
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


<style type="text/css">
.thfont {   font-family: th_saraban;
}
table.table_bord1 {border-collapse:collapse; border-left:0px;}
</style>
</head>

<body>
<?php if($totalRows_rs_dispen<>0){ ?>
<table width="100%" border="0" cellpadding="3" cellspacing="0" class="table_bord1">
  <tr class="thfont head">
    <td align="center" bgcolor="#E8E8E8"><strong>no.</strong></td>
    <td align="center" bgcolor="#E8E8E8"><strong>hn</strong></td>
    <td align="center" bgcolor="#E8E8E8"><strong>ชื่อผู้ป่วย</strong></td>
    <td align="center" bgcolor="#E8E8E8"><strong>วันที่สั่งยา</strong></td>
    <td align="center" bgcolor="#E8E8E8">บ้านเลขที่</td>
    <td align="center" bgcolor="#E8E8E8">หมู่</td>
    <td align="center" bgcolor="#E8E8E8">ที่อยู่</td>
    <td align="center" bgcolor="#E8E8E8">รพ.สต.</td>
    <td align="center" bgcolor="#E8E8E8">คลินิค</td>
    <td align="center" bgcolor="#E8E8E8">ผู้ส่ง</td>
    <td align="center" bgcolor="#E8E8E8">ผู้รับ</td>
    <td align="center" bgcolor="#E8E8E8">วันที่</td>
    <td align="center" bgcolor="#E8E8E8">เวลารับ</td>
  </tr>
  <?php $i=0; do { $i++;
    mysql_select_db($database_hos, $hos);
    $query_rs_clinic = "select c.name from clinicmember m left outer join clinic c on c.clinic=m.clinic where hn='".$row_rs_dispen['hn']."' order by location ASC limit 1";
    $rs_clinic = mysql_query($query_rs_clinic, $hos) or die(mysql_error());
    $row_rs_clinic = mysql_fetch_assoc($rs_clinic);
    $totalRows_rs_clinic = mysql_num_rows($rs_clinic);

    ?>
  <tr class="table_head_small grid">
    <? if($bgcolor=="#FFFFFF") { $bgcolor="#F2F2F2"; $font="#FFFFFF"; } else { $bgcolor="#FFFFFF"; $font="#999999"; } ?>
    <td align="center" bgcolor="<?=$bgcolor; ?>" class="thfont"><?=$i; ?></td>
    <td align="center" bgcolor="<?=$bgcolor; ?>" class="thfont"><?=$row_rs_dispen['hn']; ?></td>
    <td align="left" bgcolor="<?=$bgcolor; ?>" class="thfont"><?=$row_rs_dispen['ptname']; ?></td>
    <td align="center" bgcolor="<?=$bgcolor; ?>" class="thfont"><?=$row_rs_dispen['visitdate']; ?></td>
    <td align="center" bgcolor="<?=$bgcolor; ?>" class="thfont"><?=$row_rs_dispen['addrpart']; ?></td>
    <td align="center" bgcolor="<?=$bgcolor; ?>" class="thfont"><?=$row_rs_dispen['moopart']; ?></td>
    <td align="left" bgcolor="<?=$bgcolor; ?>" class="thfont"><?=$row_rs_dispen['full_name']; ?></td>
    <td align="center" bgcolor="<?=$bgcolor; ?>" class="thfont"><?=$row_rs_dispen['pcuname']; ?></td>
    <td align="left" bgcolor="<?=$bgcolor; ?>" class="thfont"><?php if($totalRows_rs_clinic<>0){ do {  echo $row_rs_clinic['name']; }while($row_rs_clinic = mysql_fetch_assoc($rs_clinic)); } ?></td>
    <td align="center" bgcolor="<?=$bgcolor; ?>" class="thfont">&nbsp;</td>
    <td align="center" bgcolor="<?=$bgcolor; ?>" class="thfont">&nbsp;</td>
    <td align="center" bgcolor="<?=$bgcolor; ?>" class="thfont">&nbsp;</td>
    <td align="center" bgcolor="<?=$bgcolor; ?>" class="thfont">&nbsp;</td>
    <? 	  if($printtime==""){$sum_time+=$sumtime; } else {$sum_time=(TimeDiff(substr($printtime,0,5),substr($row_rs_dispen['rx_time'],0,5)))+$sum_time; } ?>
  </tr>
  <?php mysql_free_result($rs_clinic); } while ($row_rs_dispen = mysql_fetch_assoc($rs_dispen)); ?>
</table>
<?php } else { echo nodata(); } ?>
</body>
</html>
<?php
mysql_free_result($rs_dispen);
?>
