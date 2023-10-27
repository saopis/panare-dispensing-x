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

if($_GET['action']=="delete"){
mysql_select_db($database_hos, $hos);
$query_delete = "delete from ".$database_kohrx.".kohrx_drug_refuse where id='".$_GET['id']."'";
$delete = mysql_query($query_delete, $hos) or die(mysql_error());
}

if(isset($_GET['do'])&&($_GET['do']!="")){
$do=$_GET['do'];
}

if($do=="search"){
if(isset($_GET['datestart'])&&($_GET['datestart']!="")){
$datestart=$_GET['datestart'];
}
if(isset($_GET['dateend'])&&($_GET['dateend']!="")){
$dateend=$_GET['dateend'];
}
if(isset($_GET['refuse_check'])&&($_GET['refuse_check']!="0")){
	$refuse_check=$_GET['refuse_check'];
	$condition=" and refuse_check='".$_GET['refuse_check']."'";
	}
if(isset($_GET['icode'])&&($_GET['icode']!="")){
	$icode=$_GET['icode'];
	$condition=" and d.icode='".$icode."'";
	}

mysql_select_db($database_hos, $hos);
$query_rs_usage = "select d.*,concat(p.pname,p.fname,'  ',p.lname) as patientname,v.vstdate,concat(i.name,' ',i.strength) as drugname,i.unitcost from ".$database_kohrx.".kohrx_drug_refuse d left outer join vn_stat v on v.vn=d.vn left outer join patient p on p.hn=d.hn left outer join drugitems i on i.icode=d.icode where v.vstdate between '".$datestart."' and '".$dateend."'".$condition;
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
<table  id="example" class="table table-striped table-bordered table-hover table-sm  " style="width:100%; font-size:14px" >  
	<thead>
	<tr >
      <td rowspan="2" align="center">ลำดับ</td>
      <td rowspan="2" align="center">วันที่</td>
      <td rowspan="2" align="center">HN</td>
      <td rowspan="2" align="center">ชื่อ</td>
      <td rowspan="2" align="center">ชื่อยา</td>
      <td colspan="2" align="center">จำนวน</td>
      <td rowspan="2" align="center">ประหยัด<br />
        (บาท)</td>
      <td rowspan="2" align="center">เหตุผลการ<br />
        ปฏิเสธ/ไม่ได้รับ</td>
      <td rowspan="2" align="center">อื่นๆ</td>

<td rowspan="2" class="notexport" align="center">&nbsp;</td>
		</tr>
    <tr >
      <td align="center">แพทย์สั่ง</td>
      <td align="center">ได้จริง</td>
    </tr>
	</thead>
	<tbody>
    <?php if($do=="search"){ $i=0; $totalcost=0; do { $i++;
	$costsave=($row_rs_usage['qty_rcv']-$row_rs_usage['qty'])*$row_rs_usage['unitcost']; $totalcost+=$costsave;
	  ?> 
    <tr >
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo $i; ?></td>
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo date_db2th($row_rs_usage['vstdate']); ?></td>
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo $row_rs_usage['hn']; ?></td>
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo $row_rs_usage['patientname']; ?></td>
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo $row_rs_usage['drugname']; ?></td>
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo $row_rs_usage['qty_rcv']; ?></td>
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo $row_rs_usage['qty']; ?></td>
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo $costsave; ?></td>
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo $row_rs_usage['refuse_check']; ?></td>
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php if($row_rs_usage['remark']!=""){ ?><a href="javascript:alert('<?php echo $row_rs_usage['remark']; ?>');"><img src="images/Icon-Document03-Blue.png" width="21" height="21" border="0" /></a><?php } ?></td>
     <?php if(!isset($_GET['export'])&&($_GET['export']!="Y")){ ?>
 <td align="center" bgcolor="<?php echo $bgcolor; ?>"><a href="JavaScript:if(confirm('ต้องการลบข้อมูลนี้จริงหรือไม่?')==true){delete_list('<?php echo $row_rs_usage["id"]; ?>');}"><i class="fas fa-eraser font20"></i></a></td><?php } ?>
    </tr>
  <?php } while ($row_rs_usage = mysql_fetch_assoc($rs_usage)); } ?>
	</tbody>
  </table>
  <span class="table_head_small">ยอดการประหยัดยา =</span> <span class="big_red16"><?php echo number_format2($totalcost); ?> บาท</span><br />
  <span class="table_head_small_bord">เหตุผลการปฏิเสธ/ไม่ได้รับ</span><span class="head_small_gray"><br />
  1. ปฏิเสธโดยผู้ป่วยเนื่องจากผู้ป่วยมียาเหลือ<br />
  2. ปฏิเสธโดยผู้ป่วยเนื่องจากไม่มีอาการ/ข้อบ่งใช้   ไม่มีความจำเป็นต้องใช้<br />
  3. ปฏิเสธโดยเภสัชกร/เจ้าหน้าที่ เนื่องจากพิจารณาแล้วว่าผู้ป่วยมียาเหลือ หรือไม่จำเป็นต้องใช้<br />
  4. ปฏิเสธเนื่องจากเกิดอาการแพ้หรือเกิดผลข้างเคียงจากยา<br />
  5. อื่นๆ</span>
  <?php } else { echo nodata(); } ?> 
</body>
</html>
<?php
if($_GET['do']=="search"){
mysql_free_result($rs_usage);
}
?>
