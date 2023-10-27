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
$condition="";
if($_GET['recorder']!=""){
	$condition.=" and doctorcode ='".$_GET['recorder']."'";
	}
if($_GET['drug']!=""){
	$condition.=" and d.icode ='".$_GET['drug']."'";
	}
if($_GET['hn']!=""){
	$condition.=" and v.hn =LPAD('".$_GET['hn']."','".$row_setting[24]."','0')";
	}

mysql_select_db($database_hos, $hos);
$query_rs_report = "select concat(DATE_FORMAT(v.vstdate,'%d/%m/'),(substring(v.vstdate,1,4)+543)) as date1, concat(d.name,' ',d.strength) as name,c.name as doctorname,v.hn,k.*,i.name as icdname from ".$database_kohrx.".kohrx_drug_icd10_record k left outer join drugitems d on d.icode=k.icode left outer join doctor c on c.code=k.doctorcode left outer join vn_stat v on v.vn=k.vn left outer join icd101 i on i.code=k.icd10 where vstdate between '".$_GET['datestart']."' and '".$_GET['dateend']."' ".$condition." order by vstdate asc";
//echo $query_rs_report;
$rs_report = mysql_query($query_rs_report, $hos) or die(mysql_error());
$row_rs_report = mysql_fetch_assoc($rs_report);
$totalRows_rs_report = mysql_num_rows($rs_report);

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
<?php if ($totalRows_rs_report > 0) { // Show if recordset not empty ?>
<table id="example" class="table table-striped table-bordered table-hover table-sm display " style="width:100%; font-size:14px">
<thead>  
<tr >
    <td height="24" align="center" class="rounded_top_left">ลำดับ</td>
    <td align="center">วันที่เกิดอุบัติการณ์</td>
    <td align="center">HN</td>
    <td align="center">รายการยา</td>
    <td align="center">Diag</td>
    <td align="center" class="rounded_top_right">ผู้สั่งยา</td>
  </tr>
</thead>
<tbody>
  <?php $i=0; do { $i++;   ?>
	<tr >
    <td align="center" ><?php echo $i; ?></td>
    <td align="center" ><?php print $row_rs_report['date1']; ?></td>
    <td align="center" ><?php print $row_rs_report['hn']; ?></td>
    <td align="center" ><?php print $row_rs_report['name']; ?></td>
    <td align="left" ><?php print $row_rs_report['icdname']; ?></td>
    <td align="center" ><?php print $row_rs_report['doctorname']; ?></td>
  </tr><?php } while($row_rs_report = mysql_fetch_assoc($rs_report)); ?>
	</tbody>
	</table>
  <?php } else { echo nodata(); } // Show if recordset not empty ?>
</body>
</html>
<?php
if($_GET['do']=="search"){
mysql_free_result($rs_report);
	
}
?>
