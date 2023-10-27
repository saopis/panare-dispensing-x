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
if($_GET['respondent']!=""){
	$condition.=" and a.respondent ='".$_GET['respondent']."'";
	}
if($_GET['answer']!=""){
	$condition.=" and a.answer ='".$_GET['answer']."'";
	}
if($_GET['hn']!=""){
	$condition.=" and p.hn =LPAD('".$_GET['hn']."','".$row_setting[24]."','0')";
	}

mysql_select_db($database_hos, $hos);
$query_allergy = "select a.hn,a.an,a.check_date,d.`name` as doctorname,concat(DATE_FORMAT(check_date,'%d/%m/'),(DATE_FORMAT(check_date,'%Y'))+543) as check_date,a.remark,r.respondent,aa.answer,concat(p.pname,p.fname,' ',p.lname) as ptname from ".$database_kohrx.".kohrx_adr_check a left outer join doctor d on d.`code`=a.doctorcode left outer join ".$database_kohrx.".kohrx_adr_check_respondent r on r.id=a.respondent left outer join ".$database_kohrx.".kohrx_adr_check_answer aa on aa.id=a.answer left outer join patient p on p.hn=a.hn  where check_date between '".$_GET['datestart']."' and '".$_GET['dateend']."' ".$condition;
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
    <th  >ลำดับ</th>
    <th  >HN</th>
    <th  >ชื่อ-นามสกุล</th>
	<th  >ผู้ตอบ</th>
	<th  >คำตอบ</th>
	<th  >ประเภทผู้ป่วย</th>
    <th  >ผู้ซักถาม</th>
  </tr>
	</thead>
	<tbody>
    <?php  $i=0; do { $i++; 	?>
	<tr>
  
      <td ><? echo $i; ?></td>
      <td ><?php echo $row_allergy['hn']; ?></td>
      <td ><?php echo $row_allergy['ptname']; ?></td>
      <td ><?php echo $row_allergy['respondent']; ?></td>
      <td ><?php echo $row_allergy['answer']; ?></td>
      <td ><?php if($row_allergy['an']!=""){ echo "IPD"; } else { echo "OPD"; } ?></td>
      <td ><?php echo $row_allergy['doctorname']; ?></td>
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
