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

if($_GET['do']=="search"){
mysql_select_db($database_hos, $hos);
$query_rs_cr = "select vstdate,concat(d.name,d.strength) as drugname,du.shortlist,c.cr,c.crcl,c.detail,c.hn from ".$database_kohrx.".kohrx_drug_creatinine_record c left outer join drugitems d on d.icode=c.icode left outer join vn_stat v on v.vn=c.vn left outer join drugusage du on du.drugusage=c.drugusage where vstdate between '".$_GET['datestart']."' and '".$_GET['dateend']."'";
$rs_cr = mysql_query($query_rs_cr, $hos) or die(mysql_error());
$row_rs_cr = mysql_fetch_assoc($rs_cr);
$totalRows_rs_cr = mysql_num_rows($rs_cr);

mysql_select_db($database_hos, $hos);
$query_rs_cr2 = "select count(*) as countcr from ".$database_kohrx.".kohrx_drug_creatinine_incedent c left outer join vn_stat v on v.vn=c.vn where vstdate between '".$_GET['datestart']."' and '".$_GET['dateend']."'";
$rs_cr2 = mysql_query($query_rs_cr2, $hos) or die(mysql_error());
$row_rs_cr2 = mysql_fetch_assoc($rs_cr2);
$totalRows_rs_cr2 = mysql_num_rows($rs_cr2);
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
<?php if($totalRows_rs_cr<>0){ ?>
<span class="head_small_gray">พบอุบัติการณ์ทั้งหมด &nbsp; </span><span class="small_red_bord"><?php echo $row_rs_cr2['countcr']; ?></span><span class="head_small_gray">&nbsp; ครั้ง&nbsp;/ แก้ไข &nbsp; </span><span class="small_red_bord"><?php echo $totalRows_rs_cr; ?></span><span class="head_small_gray"> รายการ</span><br />
<table id="example" class="table table-striped table-bordered table-hover table-sm " style="width:100%; font-size:14px">
 <thead>
	<tr >
    <td width="2%" height="22" align="center">no.</td>
    <td width="6%" align="center">วันที่</td>
    <td width="7%" align="center">HN</td>
    <td width="10%" align="center">ชื่อยา</td>
    <td width="19%" align="center">Drugusage</td>
    <td width="8%" align="center">Cr</td>
    <td width="5%" align="center">CrCl</td>
    <td width="11%" align="center">รายละเอียด</td>
  </tr>
</thead>
	<tbody>
	<?php $i=0; do { $i++;    ?>
		<tr >
   
      <td align="center" valign="top"><?php echo $i; ?></td>
      <td align="center" valign="top"><?php echo "$row_rs_cr[vstdate]"; ?></td>
      <td align="center" valign="top"><?php echo "$row_rs_cr[hn]"; ?></td>
      <td align="center" valign="top"><?php echo "$row_rs_cr[drugname]"; ?></td>
      <td align="center" valign="top"><?php echo "$row_rs_cr[shortlist]"; ?></td>
      <td align="center" valign="top"><?php  echo "$row_rs_cr[cr]"; ?></td>
      <td align="center" valign="top"><?php  echo "$row_rs_cr[crcl]"; ?></td>
      <td align="center" valign="top"><?php echo "$row_rs_cr[detail]"; ?></td>
      </tr> 
  <?php } while ($row_rs_cr = mysql_fetch_assoc($rs_cr)); ?>
		</tbody>
</table>
<?php } else { echo nodata(); } ?>
</body>
</html>
<?php
if($_GET['do']=="search"){

mysql_free_result($rs_cr);
mysql_free_result($rs_cr2);

}
?>
