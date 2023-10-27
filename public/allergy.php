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
$query_rs_patient = "select cid from patient where hn='".$_GET['hn']."'";
$rs_patient = mysql_query($query_rs_patient, $hos) or die(mysql_error());
$row_rs_patient = mysql_fetch_assoc($rs_patient);
$totalRows_rs_patient = mysql_num_rows($rs_patient);
$cid=$row_rs_patient['cid'];
mysql_select_db($database_hos, $hos);
if($patient_type=="ipd"){
$query_rs_allergy = "select a.agent,a.symptom,a.report_date,a.reporter,a.relation_level,a.note,s.seiousness_name,r2.relation_name,d.name from opd_allergy a left outer join allergy_seriousness s on s.seriousness_id=a.seriousness_id left outer join allergy_relation r2 on r2.allergy_relation_id=a.allergy_relation_id left outer join opduser d on d.loginname=a.reporter where hn='".$_GET['hn']."' ";
	}
else {
	if(isset($_GET['queue'])&&$_GET['queue']!=""){
$query_rs_allergy = "
select a.agent,a.symptom,a.reporter,a.report_date,a.relation_level,a.note,s.seiousness_name,r2.relation_name,d.name from opd_allergy a left outer join allergy_seriousness s on s.seriousness_id=a.seriousness_id left outer join allergy_relation r2 on r2.allergy_relation_id=a.allergy_relation_id left outer join ovst o on o.hn=a.hn left outer join opduser d on d.loginname=a.reporter where o.vstdate ='$curdate' and o.oqueue='".$_GET['queue']."'";
    }
	if(isset($_GET['hn'])&&$_GET['hn']!=""){
$query_rs_allergy = "select a.agent,a.symptom,a.reporter,a.report_date,a.relation_level,a.note,s.seiousness_name,r2.relation_name,d.name from opd_allergy a left outer join allergy_seriousness s on s.seriousness_id=a.seriousness_id left outer join allergy_relation r2 on r2.allergy_relation_id=a.allergy_relation_id left outer join opduser d on d.loginname=a.reporter where hn='".$_GET['hn']."'";	}


	}
$rs_allergy = mysql_query($query_rs_allergy, $hos) or die(mysql_error());
$row_rs_allergy = mysql_fetch_assoc($rs_allergy);
$totalRows_rs_allergy = mysql_num_rows($rs_allergy);
	
	if(isset($queue)&&$queue!=""){
	$cid=$row_rs_allergy['cid'];
	}

//ตรวจสอบตาราง kohrx_had_cause
mysql_select_db($database_hos, $hos);
$query_check = "SELECT COUNT(*) as ctable
FROM information_schema.tables 
WHERE table_schema = '".$database_hos."' 
AND table_name = 'drug_allergy'";
$check = mysql_query($query_check, $hos) or die(mysql_error());
$row_check = mysql_fetch_assoc($check);
$totalRows_check = mysql_num_rows($check);

if($row_check['ctable']!=0){
mysql_select_db($database_hos, $hos);
$query_opd_allergy = "select * from drug_allergy where patient_cid='".$cid."'";
$opd_allergy = mysql_query($query_opd_allergy, $hos) or die(mysql_error());
$row_opd_allergy = mysql_fetch_assoc($opd_allergy);
$totalRows_opd_allergy = mysql_num_rows($opd_allergy);
}
mysql_free_result($check);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
    <?php include('java_css_file.php'); ?>

<style type="text/css">
body {
	background-color: #C00;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
</style>
</head>

<body>
<nav class="navbar navbar-dark text-white" style="background-color:#A90205">
  <!-- Navbar content -->
    <span class="font18"><i class="fas fa-allergies font20"></i>&ensp;<strong>รายการยาที่ผู้ป่วยรายนี้แพ้ </strong></span>
</nav>
<table width="100%" border="0" class="table table-striped font12">
  <thead class=" bg-dark text-white text-center">
    <tr>
      <th style="border-bottom: 0px;">ชื่อยา</th>
      <th style="border-bottom: 0px;">อาการที่แพ้</th>
    <th style="border-bottom: 0px;">วันที่รายงาน </th>     
      <th style="border-bottom: 0px;">Relation level</th>
      <th style="border-bottom: 0px;">note</th>
      <th style="border-bottom: 0px;">ความรุนแรง</th>
      <th style="border-bottom: 0px;">Relation Name</th>
      <th style="border-bottom: 0px;">ผู้บันทึก</th>
    </tr>
     </thead>
    <tbody class="text-center text-white ">
    <?php do{ ?>
    <tr bgcolor="<? echo $bgcolor ?>" class="normal table_bord1">
      <td align="center" valign="top"><?php echo $row_rs_allergy['agent']; ?></td>
      <td valign="top"><?php echo $row_rs_allergy['symptom']; ?></td>
      <td align="center" valign="top"><?php echo dateThai($row_rs_allergy['report_date']); ?></td>
      <td align="center" valign="top"><?php echo $row_rs_allergy['relation_level']; ?></td>
      <td valign="top"><?php echo $row_rs_allergy['note']; ?></td>
      <td align="center" valign="top"><?php echo $row_rs_allergy['seiousness_name']; ?></td>
      <td align="center" valign="top"><?php echo $row_rs_allergy['relation_name']; ?></td>
      <td align="center" valign="top"><?php echo $row_rs_allergy['name']; ?></td>
    </tr>
    <?php } while($row_rs_allergy = mysql_fetch_assoc($rs_allergy)); ?>
  </tbody>
</table>

</body>
</html>
<?php
mysql_free_result($rs_patient);

mysql_free_result($rs_allergy);
?>
