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
$query_delete = "delete from  ".$database_kohrx.".kohrx_drugusage_check_record where id='".$_GET['id']."'";
$delete = mysql_query($query_delete, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','delete from  ".$database_kohrx.".kohrx_drugusage_check_record where id=\'".$_GET['id']."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

}

if($_GET['do']=="search"){
	
mysql_select_db($database_hos, $hos);
$query_rs_usage = "select d.id,concat(p.pname,p.fname,'  ',p.lname) as patientname,d.hn,v.vstdate,d.drugusage,c.name,concat(i.name,' ',i.strength) as drugname from  ".$database_kohrx.".kohrx_drugusage_check_record d left outer join vn_stat v on v.vn=d.vn left outer join patient p on p.hn=d.hn left outer join doctor c on c.code=d.doctorcode left outer join drugitems i on i.icode=d.icode where v.vstdate between '".$_GET['datestart']."' and '".$_GET['dateend']."'";
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
<table id="example" class="table table-striped table-bordered table-hover table-sm display " style="width:100%; font-size:14px">
    <thead>
    <tr>
      <th align="center" >ลำดับ</th>
      <th align="center" >วันที่</th>
      <th align="center" >HN</th>
      <th align="center" >ชื่อ</th>
      <th align="center" >ชื่อยา</th>
      <th align="center" >วิธีใช้ (code)</th>
      <th align="center" >ผู้สั่งใช้</th>
      <th align="center" >&nbsp;</th>
    </tr>
    </thead>
    <?php if($do=="search"){ $i=0; do { $i++;	  ?> 
     
    <tr class="grid2">
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo $i; ?></td>
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo date_db2th($row_rs_usage['vstdate']); ?></td>
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo $row_rs_usage['hn']; ?></td>
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo $row_rs_usage['patientname']; ?></td>
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo $row_rs_usage['drugname']; ?></td>
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo $row_rs_usage['drugusage']; ?></td>
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo $row_rs_usage['name']; ?></td>
<?php if(!isset($_GET['export'])&&($_GET['export']!="Y")){ ?>      
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><a href="JavaScript:if(confirm('ต้องการลบข้อมูลนี้จริงหรือไม่?')==true){deleteform('<?php echo $row_rs_usage["id"]; ?>');}"><i class="fas fa-eraser font20 text-dark"></i></a></td>
<?php } ?>
    </tr>
  <?php } while ($row_rs_usage = mysql_fetch_assoc($rs_usage)); } ?>
  </table>
<?php } else { echo nodata(); } ?>
</body>
</html>
<?php
mysql_free_result($rs_usage);
?>
