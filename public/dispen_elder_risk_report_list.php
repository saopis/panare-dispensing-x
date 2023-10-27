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
if($_GET['icode']!=""){
	$condition.=" and d.icode ='".$_GET['icode']."'";
	}
if($_GET['severity']!=""){
	$condition.=" and severity ='".$_GET['severity']."'";
	}
if($_GET['consult']!=""){
	$condition.=" and consult ='".$_GET['consult']."'";
	}
if($_GET['hn']!=""){
	$condition.=" and p.hn =LPAD('".$_GET['hn']."','".$row_setting[24]."','0')";
	}

mysql_select_db($database_hos, $hos);
$query_rs_usage = "select d.id,concat(p.pname,p.fname,'  ',p.lname) as patientname,p.hn,c.name,concat(i.name,' ',i.strength) as drugname,v.vstdate,d.age,d.severity,d.consult,concat(d2.name,' ',d2.strength) as drugname1 from ".$database_kohrx.".kohrx_drug_elder_risk_record d left outer join vn_stat v on v.vn=d.vn left outer join patient p on p.hn=v.hn left outer join doctor c on c.code=d.doctorcode left outer join drugitems i on i.icode=d.icode left outer join drugitems d2 on d2.icode=d.icode2 where v.vstdate between '".$_GET['datestart']."' and '".$_GET['dateend']."' ".$condition;
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
<?php if ($totalRows_rs_usage > 0) { // Show if recordset not empty ?>
<table id="example" class="table table-striped table-bordered table-hover table-sm display nowrap" style="width:100%; font-size:14px">
	<thead>
    <tr>
      <th align="center" >ลำดับ</th>
      <th align="center" >วันที่</th>
      <th align="center" >HN</th>
      <th align="center" >ชื่อ</th>
      <th align="center" >ชื่อยา</th>
      <th align="center" >อายุ</th>
      <th align="center" >Severity</th>
      <th align="center" >ผู้สั่ง</th>
      <th align="center" >consult</th>
    </tr>
	</thead>
	<tbody>
    <?php if($do=="search"){ $i=0; do { $i++;
	  	switch($row_rs_usage['consult']){
		case "";
		$consult1="";
		break;
		case "1";
		$consult1="แพทย์และเภสัชกรสั่งและจ่ายยาระยะสั้น";
		break;
		case "2";
		$consult1="เลี่ยงไปใช้ทางเลือกอื่น";
		break;
		case "3";
		$consult1="ให้ใช้ต่อและนัดติดตามอาการ";
		break;
		case "4";
		$consult1="ให้ใช้ต่อและไม่ติดตาม";
		break;
		}
		

	  ?> 
    <tr >
      <td align="center" valign="top" ><?php echo $i; ?></td>
      <td align="center" valign="top" ><?php echo date_db2th($row_rs_usage['vstdate']); ?></td>
      <td align="center" valign="top" ><?php echo $row_rs_usage['hn']; ?></td>
      <td align="center" valign="top" ><?php echo $row_rs_usage['patientname']; ?></td>
      <td align="center" valign="top" ><?php echo $row_rs_usage['drugname']; ?></td>
      <td align="center" valign="top" ><?php echo $row_rs_usage['age']; ?></td>
      <td align="center" valign="top" ><?php echo "$row_rs_usage[severity]"; ?></td>
      <td align="center" valign="top" ><?php echo $row_rs_usage['name']; ?></td>
      <td align="center" valign="top" ><?php echo $consult1; if($row_rs_usage['drugname1']!=""){ echo "<br><span style=\"color:red\">".$row_rs_usage['drugname1']."</span>"; } ?></td>
    </tr>
  <?php } while ($row_rs_usage = mysql_fetch_assoc($rs_usage)); } ?>
	</tbody>
  </table>
  <?php } else { echo nodata(); } // Show if recordset not empty ?>
</body>
</html>
<?php
if($_GET['do']=="search"){
mysql_free_result($rs_usage);
}
?>
