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

if(($_GET['bw']!="")){
mysql_select_db($database_hos, $hos);
$query_rs_drug = "SELECT concat(d2.name,' ',d2.strength) as drugname,d.icode,d.dosage_min,d.dosage_max,d.dose_perunit FROM ".$database_kohrx.".kohrx_drugitems_calculate d left outer join drugitems d2 on d2.icode=d.icode  where d.icode='".$_GET['drug']."' ORDER BY d2.name ASC";
$rs_drug = mysql_query($query_rs_drug, $hos) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);
}
	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?php if(($_GET['bw']!="")){ ?>
<div class="card">
<div class="card-header">ผลการคำนวณ</div>
<div class="card-body">
<i class="fas fa-arrow-circle-down text-warning" style="font-size: 30px;"></i> Min dose = <?php echo $_GET['bw']; ?> x 
<?php echo $row_rs_drug['dosage_min']; ?> = <span class="big_red16"><?php echo number_format(($_GET['bw']*$row_rs_drug['dosage_min']),2); ?></span> mg. หรือ<span class="big_red16"> <?php echo number_format(($_GET['bw']*$row_rs_drug['dosage_min'])/($row_rs_drug['dose_perunit']*5),2); ?></span> ช้อนชา<br />
<br />
<i class="fas fa-arrow-circle-up text-danger" style="font-size: 30px;"></i> Max dose = <?php echo $_GET['bw']; ?> x <?php echo $row_rs_drug['dosage_max']; ?> = <span class="big_red16"><?php echo number_format(($_GET['bw']*$row_rs_drug['dosage_max']),2); ?> </span>mg. หรือ<span class="big_red16"> <?php echo number_format(($_GET['bw']*$row_rs_drug['dosage_max'])/($row_rs_drug['dose_perunit']*5),2); ?></span> ช้อนชา<br />
<br />
(dosage = <?php echo $row_rs_drug['dosage_min']; ?>-<?php echo $row_rs_drug['dosage_max']; ?>&nbsp;&nbsp;ความแรง&nbsp; <?php echo ($row_rs_drug['dose_perunit']*5)."/5 ml"; ?> )
</div>
	</div>
<?php } ?>
</body>
</html>
<?php
if(($_GET['bw']!="")){

mysql_free_result($rs_drug);
}
?>
