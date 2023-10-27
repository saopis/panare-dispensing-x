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

if($_GET['op']==1){
		$condition=" and date_pay is null";
		}
if($_GET['op']==2){
		$condition=" and date_pay!=''";
		}
if($_GET['hn']!=""){
    $condition.=" and d.hn=LPAD('".$_GET['hn']."','".$row_setting[24]."','0')";
}
mysql_select_db($database_hos, $hos);
$query_rs_usage = "select d.id,concat(p.pname,p.fname,'  ',p.lname) as patientname,d.hn,c.name,concat(i.name,' ',i.strength) as drugname,d.among,d.date_payable,d.date_pay from ".$database_kohrx.".kohrx_payable d left outer join patient p on p.hn=d.hn left outer join doctor c on c.code=d.doctor left outer join drugitems i on i.icode=d.drug where date_payable between '".$_GET['datestart']."' and '".$_GET['dateend']."'".$condition;
//echo $query_rs_usage;
$rs_usage = mysql_query($query_rs_usage, $hos) or die(mysql_error());
$row_rs_usage = mysql_fetch_assoc($rs_usage);
$totalRows_rs_usage = mysql_num_rows($rs_usage);
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
<?php if ($totalRows_rs_usage <> 0) { 
// Show if recordset not empty ?>
<table  id="example" class="table table-striped table-bordered table-hover table-sm " style="width:100%; font-size:14px" >  
    <thead>
    <tr>
      <td align="center" >ลำดับ</td>
      <td align="center" >วันที่</td>
      <td align="center" >HN</td>
      <td align="center" >ชื่อ</td>
      <td align="center" >ชื่อยา</td>
      <td align="center" >จำนวนค้าง</td>
      <td align="center" >ผู้บันทึก</td>
      <td align="center" >วันที่ได้รับครบ</td>
    </tr>
    </thead>
    <tbody>
    <?php  $i=0; do { $i++;	  ?> 
    <tr class="grid2">
      <td align="center" ><?php echo $i; ?></td>
      <td align="center" ><?php echo date_db2th($row_rs_usage['date_payable']); ?></td>
      <td align="center" ><?php echo $row_rs_usage['hn']; ?></td>
      <td align="center" ><?php echo $row_rs_usage['patientname']; ?></td>
      <td align="center" ><?php echo $row_rs_usage['drugname']; ?></td>
      <td align="center" ><?php echo $row_rs_usage['among']; ?></td>
      <td align="center" ><?php echo $row_rs_usage['name']; ?></td>
      <td align="center" ><?php echo dateThai($row_rs_usage['date_pay']); ?></td>
    </tr>
  <?php } while ($row_rs_usage = mysql_fetch_assoc($rs_usage)); ?>
    </tbody>
  </table>
  <?php }else {echo nodata(); } // Show if recordset not empty ?>
</body>
</html>
<?php
if($_GET['do']=="search"){
mysql_free_result($rs_usage);
}
?>
