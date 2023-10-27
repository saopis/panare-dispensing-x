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

if(isset($_GET['vstdate'])){ $vstdate=date_th2db($_GET['vstdate']); }
if(isset($_POST['vstdate'])){ $vstdate=date_th2db($_POST['vstdate']);
 }
if(isset($_GET['hn'])){ $hn_search=$_GET['hn']; }
if(isset($_POST['hn'])){ $hn_search=$_POST['hn']; }

if(isset($_GET['q'])){ 
    mysql_select_db($database_hos, $hos);
    $query_rs_hn = "select hn from ovst where vstdate='".$vstdate."' and oqueue='".$_GET['q']."'";
    $rs_hn = mysql_query($query_rs_hn, $hos) or die(mysql_error());
    $row_rs_hn = mysql_fetch_assoc($rs_hn);
    $totalRows_rs_hn = mysql_num_rows($rs_hn);

    $hn_search=$row_rs_hn['hn']; 

    mysql_free_result($rs_hn);
    
    }

if(isset($_POST['q'])){ 
    mysql_select_db($database_hos, $hos);
    $query_rs_hn = "select hn from ovst where vstdate='".$vstdate."' and oqueue='".$_POST['q']."'";
    $rs_hn = mysql_query($query_rs_hn, $hos) or die(mysql_error());
    $row_rs_hn = mysql_fetch_assoc($rs_hn);
    $totalRows_rs_hn = mysql_num_rows($rs_hn);

    $hn_search=$row_rs_hn['hn']; 

    mysql_free_result($rs_hn);

    }

mysql_select_db($database_hos, $hos);
$query_rs_setting = "select * from ".$database_kohrx.".kohrx_dispensing_setting order by id ASC";
$rs_setting = mysql_query($query_rs_setting, $hos) or die(mysql_error());
$row_rs_setting = mysql_fetch_assoc($rs_setting);
$totalRows_rs_setting = mysql_num_rows($rs_setting);

$i=0; do{ $i++;
$row_setting[$i]=$row_rs_setting['value'];} 
while($row_rs_setting = mysql_fetch_assoc($rs_setting));

mysql_free_result($rs_setting);

$hn=sprintf("%".$row_setting[24]."d", $hn_search);

if($_GET['action']=="delete"){

mysql_select_db($database_hos, $hos);
$query_delete = "delete from ".$database_kohrx.".kohrx_drug_refuse where id='".$_GET['id']."'";
$delete = mysql_query($query_delete, $hos) or die(mysql_error());
}
mysql_select_db($database_hos, $hos);
$query_rs_usage = "select d.*,concat(p.pname,p.fname,'  ',p.lname) as patientname,v.vstdate,concat(i.name,' ',i.strength) as drugname,i.unitcost from ".$database_kohrx.".kohrx_drug_refuse d left outer join vn_stat v on v.vn=d.vn left outer join patient p on p.hn=d.hn left outer join drugitems i on i.icode=d.icode where v.vstdate ='".$vstdate."' and d.hn='".$hn."'";
$rs_usage = mysql_query($query_rs_usage, $hos) or die(mysql_error());
$row_rs_usage = mysql_fetch_assoc($rs_usage);
$totalRows_rs_usage = mysql_num_rows($rs_usage);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php //include('java_css_file.php'); ?>

</head>

<body>

<?php if ($totalRows_rs_usage > 0) { // Show if recordset not empty ?>
<div class="card">
<div class="card-header"><i class="fas fa-user-times font20"></i>&ensp;รายการผู้ป่วยปฏิเสธรับยา/เจ้าหน้าที่พิจารณาลดยา/งดจ่าย</div>
<div class="card-body" style="padding: 0px;">
    <table width="100%" border="0" cellpadding="3" cellspacing="0" class="table table-striped table-hover head_small_gray " >
    <thead class="thfont font13 font_bord">
    <tr class="head">
      <td align="center" scope="col">#</td>
      <td align="center" scope="col">วันที่</td>
      <td align="center" scope="col">HN</td>
      <td align="center" scope="col">ชื่อยา</td>
      <td align="center" scope="col">จำนวนที่สั่ง</td>
      <td align="center" scope="col">จำนวนที่ได้จริง</td>
      <td align="center" scope="col">เหตุผล</td>
      <td align="center">อื่นๆ</td>
<td align="center">&nbsp;</td>
    </tr>
    </thead>
    <tbody class="thfont font12">
    <?php $i=0; do { $i++; ?>
    <tr class="grid2">
      <td align="center" ><?php echo $i; ?></td>
      <td align="center" ><?php echo dateThai($row_rs_usage['vstdate']); ?></td>
      <td align="center" ><?php echo $row_rs_usage['hn']; ?></td>
      <td align="center" ><?php echo $row_rs_usage['drugname']; ?></td>
      <td align="center" ><?php echo $row_rs_usage['qty_rcv']; ?></td>
      <td align="center" ><?php echo $row_rs_usage['qty']; ?></td>
      <td align="center" ><?php echo $row_rs_usage['refuse_check']; ?></td>
      <td align="center" ><?php if($row_rs_usage['remark']!=""){ ?><a href="javascript:alert('<?php echo $row_rs_usage['remark']; ?>');"><img src="images/Icon-Document03-Blue.png" width="21" height="21" border="0" /></a><?php } ?></td>
     <td align="center" ><a href="JavaScript:valid(); " onclick="if(confirm('ต้องการลบข้อมูลนี้จริงหรือไม่?')==true){refuse_load('<?php echo $hn; ?>','<?php echo $_GET['vstdate']; ?>','delete','<?php echo $row_rs_usage["id"]; ?>');}"><img src="images/bin.png" width="16" height="16" border="0" align="absmiddle" /></a></td>
    </tr>
  <?php } while ($row_rs_usage = mysql_fetch_assoc($rs_usage));  ?>
  </tbody>
    </table>
    </div>
    </div>
    <?php } ?>
</body>
</html>
<?php
mysql_free_result($rs_usage);
?>
