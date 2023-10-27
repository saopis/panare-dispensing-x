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
mysql_free_result($rs_setting);

if($_GET['do']=="search"){

if($_GET['hn']!=""){
    $condition.=" and r.hn=LPAD('".$_GET['hn']."','".$row_setting[24]."','0')";
}
mysql_select_db($database_hos, $hos);
$query_rs_patient = "select r.*,concat(p.pname,p.fname,' ',p.lname) as ptname from ".$database_kohrx.".kohrx_patient_warfarin r left outer join patient p on p.hn=r.hn where record_date between '".$_GET['datestart']."' and '".$_GET['dateend']."' ".$condition." order by r.record_date ASC";
//echo $query_rs_patient;
$rs_patient = mysql_query($query_rs_patient, $hos) or die(mysql_error());
$row_rs_patient = mysql_fetch_assoc($rs_patient);
$totalRows_rs_patient = mysql_num_rows($rs_patient);

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>รายงานผู้ป่วยใช้ยา warfarin</title>
<?php include('include/bootstrap/datatable_report.php'); ?>

	
</head>

<body>
<?php if ($totalRows_rs_patient <> 0) { 
// Show if recordset not empty ?>
<table id="example" class="table table-striped table-bordered table-hover table-sm display " style="width:100%; font-size:14px">
				<thead>
					<tr>
						<th>ลำดับ</th>
						<th>hn</th>
						<th>ชื่อ นามสกุล</th>
						<th>วันที่ลงทะเบียน</th>
					</tr>
				</thead>
				<tbody>
					<?php $i=0; do { $i++; ?>
					<tr>
						<td><?php echo $i; ?></td>
						<td><?php echo $row_rs_patient['hn']; ?></td>
						<td><?php echo $row_rs_patient['ptname']; ?></td>
						<td><?php echo dateThai($row_rs_patient['record_date']); ?></td>
					</tr>
					<?php }while($row_rs_patient = mysql_fetch_assoc($rs_patient)); ?>
				</tbody>
			</table>
  <?php }else {echo nodata(); } // Show if recordset not empty ?>
</body>
</html>
<?php
if($_GET['do']=="search"){
mysql_free_result($rs_patient);
}
?>
