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
$query_rs_drug = "SELECT r.drugname,l.link
from ".$database_kohrx.".kohrx_drug_lexi_link l left OUTER JOIN drugitems d on d.icode=l.icode 
left OUTER JOIN drugitems_register r on r.std_code=d.did 
where drugname !=''
group by drugname ORDER BY drugname asc";
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

<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
</style>
</head>

<body>
<nav class="navbar navbar-dark bg-success text-white " >
  <!-- Navbar content -->
  <span class="card-title font_bord"  >&ensp;Drug Monograph</span>
</nav>
<table width="100%" border="0" class="table table-bordered">
  <thead>
	<tr>
    <td width="89%" height="28" align="center">รายการยา</td>
  </tr>
</thead>
<tbody>
<?php $i=0; do { $i++; ?>  
<tr >   
    <td align="left" valign="top"  style="padding-left:10px"><a href="<?php echo $row_rs_drug['link']; ?>" target="mainFrame" ><?php echo $row_rs_drug['drugname']; ?></a></td>
  </tr> <?php } while ($row_rs_drug = mysql_fetch_assoc($rs_drug)); ?>

</table>
</body>
</html>
<?php
mysql_free_result($rs_drug);
?>
