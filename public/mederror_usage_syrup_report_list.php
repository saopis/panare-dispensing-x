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
$query_delete = "delete from  ".$database_kohrx.".kohrx_syr_dosing_record where id='".$_GET['id']."'";
$delete = mysql_query($query_delete, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','delete from  ".$database_kohrx.".kohrx_drugusage_check_record where id=\'".$_GET['id']."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

}

if($_GET['do']=="search"){
	
mysql_select_db($database_hos, $hos);
$query_rs_usage = "select d.id,concat(p.pname,p.fname,'  ',p.lname) as patientname,d.hn,v.vstdate,d.drugusage,c.name,concat(i.name,' ',i.strength) as drugname,bw from ".$database_kohrx.".kohrx_syr_dosing_record d left outer join vn_stat v on v.vn=d.vn left outer join patient p on p.hn=d.hn left outer join doctor c on c.code=d.doctorcode left outer join drugitems i on i.icode=d.icode where v.vstdate between '".$_GET['datestart']."' and '".$_GET['dateend']."' and is_error='Y' ";
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
<table  border="0" cellspacing="0" cellpadding="2" style="border-bottom:solid 1px #666666" class="table table-striped table-bordered" id="example">
	<thead>
    <tr>
      <th align="center" >ลำดับ</th>
      <th align="center" >วันที่</th>
      <th align="center" >HN</th>
      <th align="center" >ชื่อ</th>
      <th align="center" >ชื่อยา</th>
      <th align="center" >วิธีใช้ (code)</th>
      <th align="center" >น้ำหนัก</th>
      <th align="center" >ผู้สั่งใช้</th>

    </tr>
    </thead>
    <tbody>
    <?php if($do=="search"){ $i=0; do { $i++;	  ?> 
    <tr class="grid2">
      <td align="center" ><?php echo $i; ?></td>
      <td align="center" ><?php echo date_db2th($row_rs_usage['vstdate']); ?></td>
      <td align="center" ><?php echo $row_rs_usage['hn']; ?></td>
      <td align="center" ><?php echo $row_rs_usage['patientname']; ?></td>
      <td align="center" ><?php echo $row_rs_usage['drugname']; ?></td>
      <td align="center" ><?php echo $row_rs_usage['drugusage']; ?></td>
      <td align="center" ><?php echo $row_rs_usage['bw']; ?></td>
      <td align="center" ><?php echo $row_rs_usage['name']; ?></td>
    </tr>
  <?php } while ($row_rs_usage = mysql_fetch_assoc($rs_usage)); } ?>
  </tbody>
  </table>
<?php } else { echo nodata(); }?>
</body>
</html>
<?php
mysql_free_result($rs_usage);
?>
