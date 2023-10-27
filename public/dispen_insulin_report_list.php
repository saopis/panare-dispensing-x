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

if($_GET['do']=="search"){
	if($_GET['syring_type']!=""){
		$condition=" where s.syring_type='".$_GET['syring_type']."'";
	}
	else{
		$condition="";
	}
	mysql_select_db($database_hos, $hos);
$query_rs_insulin = "select concat(p.pname,p.fname,'  ',p.lname) as patientname,p.hn,case when s.syring_type ='1' then 'Syring' else 'Pen fill' end as syringtype,case when s.needle_type ='0' then '5mm' when s.needle_type='1' then '6mm' when s.needle_type='2' then '8mm' else '' end as needletype from ".$database_kohrx.".kohrx_insulin_syring s left outer join patient p on p.hn=s.hn ".$condition;
$rs_insulin = mysql_query($query_rs_insulin, $hos) or die(mysql_error());
$row_rs_insulin = mysql_fetch_assoc($rs_insulin);
$totalRows_rs_insulin = mysql_num_rows($rs_insulin);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="include/kohrx/css/kohrx.css" rel="stylesheet" type="text/css" />
<?php include('include/bootstrap/datatable_report.php'); ?>
</head>

<body>
<table id="example" class="table table-striped table-bordered table-hover table-sm " style="width:100%; font-size:14px">
 <thead>
	<tr >
      <td  >ลำดับ</td>
      <td  align="center" >HN</td>
      <td align="center" >ชื่อ</td>
      <td align="center" >ชนิด syring</td>
      <td align="center" >ชนิดเข็ม</td>
  </tr>
</thead>
	<tbody>
	<?php $i=0; do { $i++;    ?><tr>
   
        <td ><?php echo $i; ?></td>
        <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo $row_rs_insulin['hn']; ?></td>
        <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo $row_rs_insulin['patientname']; ?></td>
        <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo $row_rs_insulin['syringtype']; ?></td>
        <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo $row_rs_insulin['needletype']; ?></td>
      </tr> 
  <?php } while ($row_rs_insulin = mysql_fetch_assoc($rs_insulin)); ?>
		</tbody>
</table>
</body>
</html>
<?php
if($_GET['do']=="search"){

mysql_free_result($rs_insulin);

}
?>
