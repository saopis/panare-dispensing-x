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
if($_GET['pttype']==1){
	$condition1.=" vn_stat v on v.vn=r.vn ";
	$condate="v.vstdate";
	}
if($_GET['pttype']==2){
	$condition1.=" an_stat v on v.an=r.an ";
	$condate="v.regdate";	
	}

mysql_select_db($database_hos, $hos);
$query_rs_report = "
select v.hn,concat(d.name,d.strength) as drugname,cc.use_cause,r.remark,c.name,".$condate." as vstdate from  ".$database_kohrx.".kohrx_due_record r left outer join ".$condition1." left outer join doctor c on c.code=r.doctor left outer join drugitems d on d.icode=r.icode left outer join ".$database_kohrx.".kohrx_due_cause cc on cc.id=r.use_cause_id where ".$condate." between '".$_GET['datestart']."' and '".$_GET['dateend']."' ".$condition;
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
  <tr class="table_head_small_bord">
    <td width="3%" align="center" >ลำดับ</td>
    <td width="12%" align="center" >วันที่</td>
    <td width="9%" align="center" >HN</td>
    <td width="20%" align="center" >รายการยา</td>
    <td width="21%" align="center" >สาเหตุการใช้</td>
    <td width="26%" align="center" >หมายเหตุ</td>
    <td width="9%" align="center" >แพทย์ผู้สั่ง</td>
  </tr>
    </thead>
    <tbody>
     <?php $i=0; do {$i++; ?>
 <tr class="grid">
      <td align="center" valign="top" ><?php echo $i; ?></td>
      <td align="center" valign="top" ><?php echo $row_rs_report['vstdate']; ?></td>
    <td align="center" valign="top" ><?php echo $row_rs_report['hn']; ?></td>
      <td align="left" valign="top" ><?php echo $row_rs_report['drugname']; ?></td>
    <td align="left" valign="top" ><?php echo $row_rs_report['use_cause']; ?></td>
    <td align="left" valign="top" ><?php echo $row_rs_report['remark']; ?></td>
      <td align="center" valign="top" ><?php echo $row_rs_report['name']; ?></td>
  </tr>      <?php } while ($row_rs_report = mysql_fetch_assoc($rs_report)); ?>
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
