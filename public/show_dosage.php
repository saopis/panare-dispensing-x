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

mysql_select_db($database_hos, $hos);
$query_rs_drug = "SELECT concat(d2.name,' ',d2.strength) as drugname,d.icode,d.dosage_min,d.dosage_max,d.dose_perunit FROM ".$database_kohrx.".kohrx_drugitems_calculate d left outer join drugitems d2 on d2.icode=d.icode where d.icode='$icode'";
$rs_drug = mysql_query($query_rs_drug, $hos) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php include('java_css_file.php'); ?>
<style>html,body{overflow:hidden; }</style>
</head>

<body>
<nav class="navbar navbar-dark bg-info text-white fixed-top p-2">
  <!-- Navbar content -->
    <span class="font16"><i class="fas fa-prescription-bottle font20"></i>&ensp;ขนาดยา <span class=" font20 font_bord text-dark"><?php echo $row_rs_drug['drugname']; ?></span> ที่ผู้ป่วยควรได้รับต่อครั้ง</span>
</nav>
<div style="margin-top:50px; padding:20px;">
<p class="font20 font_bord">= น้ำหนัก(kg.) x ปริมาณยา(ต่อ dose)</p>
<p>ปริมาณที่<span class="big_red16"> น้อย </span>ที่สุด คือ <?php echo number_format($bw,2); ?> x <?php echo $row_rs_drug['dosage_min']; ?>&nbsp; = <span class="big_red16"><?php echo number_format(($bw*$row_rs_drug['dosage_min']),2); ?> </span>mg. หรือ<span class="big_red16"> <?php echo number_format(($bw*$row_rs_drug['dosage_min'])/($row_rs_drug['dose_perunit']*5),2); ?> </span>ช้อนชา<br />
ปริมาณที่<span class="big_red16"> มาก</span> ที่สุด คือ <?php echo number_format($bw,2); ?> x <?php echo $row_rs_drug['dosage_max']; ?>&nbsp; =<span class="big_red16"> <?php echo number_format(($bw*$row_rs_drug['dosage_max']),2); ?> </span>mg. หรือ<span class="big_red16"> <?php echo number_format(($bw*$row_rs_drug['dosage_max'])/($row_rs_drug['dose_perunit']*5),2); ?> </span>ช้อนชา</p>
<p>* 1 ช้อนชา ( 5 ml) มีตัวยา <?php echo $row_rs_drug['dose_perunit']*5; ?> mg.</p>
</div>
</body>
</html>
<?php
mysql_free_result($rs_drug);
?>
