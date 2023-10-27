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
$query_rs_drug = "SELECT generic_name,breast_feeding_alert_text FROM drugitems WHERE show_breast_feeding_alert ='Y' ORDER BY generic_name ASC ";
$rs_drug = mysql_query($query_rs_drug, $hos) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);

mysql_select_db($database_hos, $hos);
$query_rs_config = "select hospitalname from opdconfig";
$rs_config = mysql_query($query_rs_config, $hos) or die(mysql_error());
$row_rs_config = mysql_fetch_assoc($rs_config);
$totalRows_rs_config = mysql_num_rows($rs_config);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php include('java_css_file.php'); ?>
<style>
html,body{overflow-y:hidden; }
	::-webkit-scrollbar {
    width: 10px;
}
    ::-webkit-scrollbar-track {
    box-shadow: inset 0 0 10px 10px #E6E6E6;
    border: solid 3px transparent;
}

::-webkit-scrollbar-thumb {
    box-shadow: inset 0 0 10px 10px #696969;
    border: solid 3px transparent;
}

</style>
</head>

<body>
<nav class="navbar navbar-dark bg-success text-white " >
  <!-- Navbar content -->
  <span class="card-title font_bord"  >&ensp;รายการยาที่ควรระมัดระวังในผู้ป่วยที่ให้นมบุตร&nbsp; <?php echo $row_rs_config['hospitalname']; ?></span>
</nav>
<div style="overflow:scroll;overflow-x:hidden;overflow-y:auto; margin-right: 10px; ">   
<table width="100%" class="table table-bordered">
<thead class="text-center">
    <tr >
    <th width="8%" height="28" align="center">ลำดับ</th>
    <th width="33%" align="center">รายการยา</th>
    <th width="59%" align="center">รายละเอียด</th>
  </tr>
    </thead>
    </table>
</div>
<div style="overflow:scroll;overflow-x:hidden;overflow-y:auto; height: 85vh;  margin-top: -17px; ">   
<table width="100%" class="table table-bordered table-striped">
<tbody >

<?php $i=0; do { $i++;

 ?>  
<tr >
    
    <td align="center" width="8%" valign="top" ><?php echo $i; ?></td>
    <td align="left" width="33%" valign="top"  style="padding-left:10px"><?php echo $row_rs_drug['generic_name']; ?></td>
    <td align="left" width="59%"  style="padding-left:10px"><?php echo $row_rs_drug['breast_feeding_alert_text']; ?></td> 
  </tr> <?php } while ($row_rs_drug = mysql_fetch_assoc($rs_drug)); ?>
    </tbody>
    </table>
</div>
</body>
</html>
<?php
mysql_free_result($rs_drug);

mysql_free_result($rs_config);
?>
