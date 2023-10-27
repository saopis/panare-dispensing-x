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
$query_rs_di = "select * from drug_interaction_incident where vn='$vn'";
$rs_di = mysql_query($query_rs_di, $hos) or die(mysql_error());
$row_rs_di = mysql_fetch_assoc($rs_di);
$totalRows_rs_di = mysql_num_rows($rs_di);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="include/kohrx/css/kohrx.css" rel="stylesheet" type="text/css" />
<?php //include('java_css_online.php'); ?>
</head>

<body>
<table width="100%" class="display table table-striped">
    <thead class="bg-dark text-white">
  <tr>
    <td height="26" align="center" style="border-top: 0px" >no</td>
    <td align="center" style="border-top: 0px">ยา 1</td>
    <td align="center" style="border-top: 0px">ยา 2</td>
    <td align="center" style="border-top: 0px">ผลการเกิด DI</td>
    <td align="center" style="border-top: 0px">&nbsp;</td>
  </tr>
    </thead>
    <tbody>
      <?php $i=0; do { $i++; 

	  ?><tr class="grid2">
        <td width="18" align="center" valign="top" ><?php echo $i; ?></td>

      <td width="157" align="center" valign="top" ><span class="big_red16"><?php echo $row_rs_di['drugname1']; ?></span>&nbsp;&nbsp;</td>
      <td width="157" align="center" valign="top" ><span class="big_red16"><?php echo $row_rs_di['drugname2']; ?></span></td>
      <td width="354" valign="top"  class="table_head_small"><?php echo $row_rs_di['note']; ?></td>
      <td width="84" align="center" valign="top"  class="table_head_small">&nbsp;</td>
        </tr>
      <?php } while ($row_rs_di = mysql_fetch_assoc($rs_di)); ?>
</tbody>
</table>
</body>
</html>
<?php
mysql_free_result($rs_di);
?>
