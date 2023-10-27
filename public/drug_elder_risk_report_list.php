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
if($_POST['do']=="search"){

		$date11=explode("/",$date1);
		$edate1=($date11[2]-543)."-".$date11[1]."-".$date11[0];
		$esdate1=substr($date11[2],2,4).$date11[1].$date11[0];
		
		$date11=explode("/",$date2);
		$edate2=($date11[2]-543)."-".$date11[1]."-".$date11[0];
		$esdate2=substr($date11[2],2,4).$date11[1].$date11[0];	

$condition="";
if($_POST['drug']!=""){
	$condition.=" and d.icode ='".$_POST['drug']."'";
	}
if($_POST['severity']!=""){
	$condition.=" and severity ='".$_POST['severity']."'";
	}
if($_POST['consult']!=""){
	$condition.=" and consult ='".$_POST['consult']."'";
	}


mysql_select_db($database_hos, $hos);
$query_rs_usage = "select d.id,concat(p.pname,p.fname,'  ',p.lname) as patientname,p.hn,c.name,concat(i.name,' ',i.strength) as drugname,v.vstdate,d.age,d.severity,d.consult,concat(d2.name,' ',d2.strength) as drugname1 from ".$database_kohrx.".kohrx_drug_elder_risk_record d left outer join vn_stat v on v.vn=d.vn left outer join patient p on p.hn=v.hn left outer join doctor c on c.code=d.doctorcode left outer join drugitems i on i.icode=d.icode left outer join drugitems d2 on d2.icode=d.icode2 where v.vstdate between '$edate1' and '$edate2' ".$condition." ";
$rs_usage = mysql_query($query_rs_usage, $hos) or die(mysql_error());
$row_rs_usage = mysql_fetch_assoc($rs_usage);
$totalRows_rs_usage = mysql_num_rows($rs_usage);
}

if($_POST['do']=="delete"){

mysql_select_db($database_hos, $hos);
$query_delete = "delete from ".$database_kohrx.".kohrx_drug_elder_risk_record where id='$id'";
$delete = mysql_query($query_delete, $hos) or die(mysql_error());
echo "<script> formSubmit('search','displayDiv','indicator'); </script>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php if ($totalRows_rs_usage > 0) { // Show if recordset not empty ?>
  <table width="100%" border="0" cellpadding="3" cellspacing="1" class="head_small_gray" style="border-bottom:solid 1px #999999">
    <tr>
      <td align="center" bgcolor="#999999">ลำดับ</td>
      <td align="center" bgcolor="#999999">วันที่</td>
      <td align="center" bgcolor="#999999">HN</td>
      <td align="center" bgcolor="#999999">ชื่อ</td>
      <td align="center" bgcolor="#999999">ชื่อยา</td>
      <td align="center" bgcolor="#999999">อายุ</td>
      <td align="center" bgcolor="#999999">Severity</td>
      <td align="center" bgcolor="#999999">ผู้สั่ง</td>
      <td align="center" bgcolor="#999999">consult</td>
      <td align="center" bgcolor="#999999">วันที่</td>
    </tr>
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
		
		if($bgcolor=="#FFFFFF"){ $bgcolor="#F2F2F2"; $font="#FFFFFF"; } else { $bgcolor="#FFFFFF"; $font="#999999"; } 

	  ?> 
    <tr class="grid2">
      <td align="center" valign="top" bgcolor="<?php echo $bgcolor; ?>"><?php echo $i; ?></td>
      <td align="center" valign="top" bgcolor="<?php echo $bgcolor; ?>"><?php echo $row_rs_usage['vstdate']; ?></td>
      <td align="center" valign="top" bgcolor="<?php echo $bgcolor; ?>"><?php echo $row_rs_usage['hn']; ?></td>
      <td align="center" valign="top" bgcolor="<?php echo $bgcolor; ?>"><?php echo $row_rs_usage['patientname']; ?></td>
      <td align="center" valign="top" bgcolor="<?php echo $bgcolor; ?>"><?php echo $row_rs_usage['drugname']; ?></td>
      <td align="center" valign="top" bgcolor="<?php echo $bgcolor; ?>"><?php echo $row_rs_usage['age']; ?></td>
      <td align="center" valign="top" bgcolor="<?php echo $bgcolor; ?>"><?php echo "$row_rs_usage[severity]"; ?></td>
      <td align="center" valign="top" bgcolor="<?php echo $bgcolor; ?>"><?php echo $row_rs_usage['name']; ?></td>
      <td align="center" valign="top" bgcolor="<?php echo $bgcolor; ?>"><?php echo $consult1; if($row_rs_usage['drugname1']!=""){ echo "<br><span style=\"color:red\">".$row_rs_usage['drugname1']."</span>"; } ?></td>
      <td align="center" valign="top" bgcolor="<?php echo $bgcolor; ?>"><?php echo $row_rs_usage['vstdate']; ?></td>
    </tr>
  <?php } while ($row_rs_usage = mysql_fetch_assoc($rs_usage)); } ?>
  </table>
  <?php } // Show if recordset not empty ?>
</body>
</html>
<?php
if($do=="search"){
mysql_free_result($rs_usage);
}
?>
