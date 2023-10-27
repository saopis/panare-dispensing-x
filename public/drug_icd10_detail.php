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
$query_rs_detail = "select k.*,concat(d.name,' ',d.strength) as name from ".$database_kohrx.".kohrx_drug_icd10 k left outer join drugitems d on d.icode=k.icode where k.id='$_GET[id]'";
$rs_detail = mysql_query($query_rs_detail, $hos) or die(mysql_error());
$row_rs_detail = mysql_fetch_assoc($rs_detail);
$totalRows_rs_detail = mysql_num_rows($rs_detail);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
</head>

<body>
<p class="big_red16">:: ระบบเตือนยาที่ห้ามสั่ง/ระมัดระวังในผู้ป่วยที่ถูกวินิจฉัยในช่วงที่กำหนด ::</p>
<p class="big_red16"><br />
  <?php echo $row_rs_detail['name']; ?></p>
<p class="table_head_small">ช่วง ICD10 ที่ห้าม/ระมัดระวังในการสั่งใช้&nbsp; <span class="button blue"><?php echo $row_rs_detail['icd101']; ?></span> <img src="images/11949844622098606321arrow1_sergio_luiz_arauj_01.svg.med.png" width="41" height="24" align="absmiddle" /><span class="button blue"><?php echo $row_rs_detail['icd102']; ?></span></p>
<p class="table_head_small"><span class="table_head_small_bord">รายละเอียด</span>
</p>
<table width="100%" border="0" cellspacing="0" cellpadding="10" style="border:solid 1px #CCCCCC" class="rounded_bottom rounded_top">
  <tr>
    <td><span class="table_head_small"><?php echo $row_rs_detail['detail']; ?></span></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($rs_detail);
?>
