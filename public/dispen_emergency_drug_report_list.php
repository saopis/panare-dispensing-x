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

mysql_select_db($database_hos, $hos);
$query_rs_setting = "select * from ".$database_kohrx.".kohrx_dispensing_setting order by id ASC";
$rs_setting = mysql_query($query_rs_setting, $hos) or die(mysql_error());
$row_rs_setting = mysql_fetch_assoc($rs_setting);
$totalRows_rs_setting = mysql_num_rows($rs_setting);

$i=0; do{ $i++;
$row_setting[$i]=$row_rs_setting['value'];} 
while($row_rs_setting = mysql_fetch_assoc($rs_setting));

if($_GET['do']=="search"){
if($_GET['doctor']!=""){
	$condition.=" and c.doctor='".$_GET['doctor']."'";
}
if($_GET['hn']!=""){
	$condition.=" and v.hn=LPAD('".$_GET['hn']."','".$row_setting[24]."','0') ";
}	
if($_GET['drug']!=""){
	$condition.=" and c.icode='".$_GET['drug']."'";
}	
mysql_select_db($database_hos, $hos);
$query_rs_cr = "select dispen_date,dispen_time,c.receive_time,c.rxtime,concat(d.name,d.strength) as drugname,v.hn,c.doctor,c.reciever from ".$database_kohrx.".kohrx_emergency_drug c left outer join drugitems d on d.icode=c.icode left outer join vn_stat v on v.vn=c.vn where vstdate between '".$_GET['datestart']."' and '".$_GET['dateend']."' ".$condition;
$rs_cr = mysql_query($query_rs_cr, $hos) or die(mysql_error());
$row_rs_cr = mysql_fetch_assoc($rs_cr);
$totalRows_rs_cr = mysql_num_rows($rs_cr);

mysql_select_db($database_hos, $hos);
$query_rs_cr2 = "select count(*) as countcr from ".$database_kohrx.".kohrx_emergency_drug c left outer join vn_stat v on v.vn=c.vn where vstdate between '".$_GET['datestart']."' and '".$_GET['dateend']."' ".$condition;
$rs_cr2 = mysql_query($query_rs_cr2, $hos) or die(mysql_error());
$row_rs_cr2 = mysql_fetch_assoc($rs_cr2);
$totalRows_rs_cr2 = mysql_num_rows($rs_cr2);

	include('include/function_sql.php');

}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Emergency Drug Report</title>
<?php include('include/bootstrap/datatable_report.php'); ?>

</head>

<body>
<?php if($totalRows_rs_cr<>0){ ?>
<span class="head_small_gray">พบทั้งหมด &nbsp; </span><span class="small_red_bord"><?php echo $row_rs_cr2['countcr']; ?></span><span class="head_small_gray">&nbsp; ครั้ง</span>
<table id="example" class="table table-striped table-bordered table-hover table-sm " style="width:100%; font-size:14px">
 <thead>
	<tr >
    <td width="2%" height="22" align="center">no.</td>
    <td width="6%" align="center">วันที่</td>
    <td width="6%" align="center">เวลาสั่ง</td>
    <td width="6%" align="center">เวลารับ</td>
    <td width="6%" align="center">เวลาจ่าย</td>
    <td width="7%" align="center">รอคอย(นาที)</td>
    <td width="7%" align="center">HN</td>
    <td width="10%" align="center">ชื่อผู้ป่วย</td>
    <td width="19%" align="center">ชื่อยา</td>
    <td width="19%" align="center">ผู้จ่าย</td>
	<td width="10%" class="notexport" align="center">ผู้รับ</td>

  </tr>
</thead>
	<tbody>
	<?php $i=0;$sum=0; do { $i++;    ?>
	<?php
			if($row_rs_cr['receive_time']!=""&&$row_rs_cr['dispen_time']!=""){ $sum+=timediff($row_rs_cr['receive_time'],$row_rs_cr['dispen_time']); $total++;  }
	?>
		<tr >
   
      <td align="center" valign="top"><?php echo $i; ?></td>
      <td align="center" valign="top"><?php echo date_db2th($row_rs_cr['dispen_date']); ?></td>
      <td align="center" valign="top"><?php echo time4digit($row_rs_cr['rxtime']); ?></td>
      <td align="center" valign="top"><?php echo time4digit($row_rs_cr['receive_time']); ?></td>
      <td align="center" valign="top"><?php echo time4digit($row_rs_cr['dispen_time']); ?></td>
      <td align="center" valign="top"><?php if($row_rs_cr['receive_time']!=""&&$row_rs_cr['dispen_time']!=""){ echo timediff($row_rs_cr['receive_time'],$row_rs_cr['dispen_time']); } ?></td>
      <td align="center" valign="top"><?php echo $row_rs_cr['hn']; ?></td>
      <td align="center" valign="top"><?php echo ptname($row_rs_cr['hn']); ?></td>
      <td align="center" valign="top"><?php echo $row_rs_cr['drugname']; ?></td>
      <td align="center" valign="top"><?php  echo doctorname($row_rs_cr['doctor']); ?></td>
      <td align="center" valign="top"><?php  echo respondentname($row_rs_cr['reciever']); ?></td>
      </tr> 
  <?php } while ($row_rs_cr = mysql_fetch_assoc($rs_cr)); ?>
		</tbody>
	<tfoot>
		<tr>
		<th colspan='11' class="text-center" style="font-size: 18px">
		<?php if($sum!=0){ echo "เวลารอคอยรับยาเฉลี่ย <span class=' font-weight-bord text-danger'>". number_format2($sum/$total)."</span>&nbsp;นาที"; } ?>
		</th>
		</tr>
	</tfoot>
</table>
<?php } else { echo nodata(); } ?>
</body>
</html>
<?php
if($_GET['do']=="search"){

mysql_free_result($rs_cr);
mysql_free_result($rs_cr2);
mysql_free_result($rs_setting);
}
?>
