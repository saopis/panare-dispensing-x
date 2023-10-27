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
if($_GET['keyword']!=""){
	$condition.=" and (locate('".$_GET['keyword']."',note) or locate('".$_GET['keyword']."',agent) or locate('".$_GET['keyword']."',symptom))";
	}
if($_GET['relation']!=""){
	$condition.=" and a.allergy_relation_id ='".$_GET['relation']."'";
	}
if($_GET['hn']!=""){
	$condition.=" and p.hn =LPAD('".$_GET['hn']."','".$row_setting[24]."','0')";
	}

mysql_select_db($database_hos, $hos);
$query_allergy = "select a.hn,concat(p.pname,p.fname,'  ',p.lname) as ptname,p.moopart,a.report_date,a.agent,a.symptom,a.reporter,a.relation_level,a.note,a.begin_date,s.seiousness_name,r.result_name,r2.relation_name,a.department,a.entry_datetime,a.naranjo_result_id,t.opd_allergy_alert_type_name,p.addrpart,p.moopart,ta.full_name from opd_allergy a left outer join allergy_seriousness s on s.seriousness_id=a.seriousness_id left outer join allergy_result r on r.allergy_result_id=a.allergy_result_id left outer join allergy_relation r2 on r2.allergy_relation_id=a.allergy_relation_id left outer join opd_allergy_alert_type t on t.opd_allergy_alert_type_id=a.opd_allergy_alert_type_id left outer join patient p on p.hn=a.hn left outer join thaiaddress ta on ta.chwpart=p.chwpart and ta.amppart=p.amppart and ta.tmbpart=p.tmbpart where report_date between '".$_GET['datestart']."' and '".$_GET['dateend']."' ".$condition;
$allergy = mysql_query($query_allergy, $hos) or die(mysql_error());
$row_allergy = mysql_fetch_assoc($allergy);
$totalRows_allergy = mysql_num_rows($allergy);
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
<?php if ($totalRows_allergy > 0) { // Show if recordset not empty ?>
<table id="example" class="table table-striped table-bordered table-hover table-sm display " style="width:100%; font-size:14px">
	<thead>
  <tr>
    <th  align="center" ><span ><?php echo 'ลำดับ'; ?></span></th>
    <th  align="center" ><span >HN</span></th>
    <th  align="center" ><span ><?php echo 'ชื่อ-นามสกุล'; ?></span></th>
	<th  align="center" ><span ><?php echo 'หมู่'; ?></span></th>
	<th  align="center" ><span ><?php echo 'ที่อยู่'; ?></span></th>
    <th  align="center" ><span ><?php echo 'ยาที่แพ้'; ?></span></th>
    <th  align="center" ><span ><?php echo 'อาการ'; ?></span></th>
    <th  align="center" ><span ><?php echo 'วันที่รายงาน'; ?></span></th>
    <th  align="center" ><span >note</span></th>
    <th align="center" ><span ><?php echo 'ความรุนแรง'; ?></span></th>
    <th  align="center" ><span >relation<br>
    name</span></th>
    <th  align="center" ><span >type</span></th>
  </tr>
	</thead>
	<tbody>
    <?php  $i=0; do { $i++; 	?>
	<tr>
  
      <td align="center" valign="top"><? echo $i; ?></td>
      <td align="center" valign="top"><?php echo $row_allergy['hn']; ?></td>
      <td valign="top"><?php echo $row_allergy['ptname']; ?></td>
      <td valign="top"><?php echo $row_allergy['moopart']; ?></td>
      <td valign="top"><?php echo $row_allergy['full_name']; ?></td>
      <td align="center" valign="top"><?php echo $row_allergy['agent']; ?></td>
      <td valign="top"><?php echo $row_allergy['symptom']; ?></td>
      <td align="center" valign="top"><?php echo date_db2th($row_allergy['report_date']); ?></td>
      <td valign="top"><?php echo $row_allergy['note']; ?></td>
      <td align="center" valign="top"><?php echo $row_allergy['seiousness_name']; ?></td>
      <td align="center" valign="top"><?php echo $row_allergy['relation_name']; ?></td>
      <td align="center" valign="top"><?php echo $row_allergy['department']; ?></td>
  </tr><?php } while ($row_allergy = mysql_fetch_assoc($allergy)); ?>
</tbody>
	</table>
  <?php } else { echo nodata(); } // Show if recordset not empty ?>
</body>
</html>
<?php
if($_GET['do']=="search"){
mysql_free_result($allergy);
}
?>
