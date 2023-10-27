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
$query_rs_lab_order = "select lab_items_name,lab_order_result,lab_items_unit,lab_items_normal_value,range_check_min,range_check_max from lab_order o left join lab_items l on l.lab_items_code=o.lab_items_code where o.lab_order_number='".$_GET['order_number']."' order by lab_items_name ASC";
$rs_lab_order = mysql_query($query_rs_lab_order, $hos) or die(mysql_error());
$row_rs_lab_order = mysql_fetch_assoc($rs_lab_order);
$totalRows_rs_lab_order = mysql_num_rows($rs_lab_order);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link rel="stylesheet" href="include/bootstrap/css/bootstrap.min.css"/>

</head>

<body>
<div class="card mt-2">
<div class="card-body">
<div class="table-responsive-sm">
<table width="100%" border="0" align="center"  cellspacing="0" class="table table-sm table-hover">
	<thead class=" table-light text-center">
  <tr >
    <th scope="col"  align="center">no.</th>
    <th scope="col" align="center">ชื่อการตรวจ</th>
    <th scope="col" align="center">ผล</th>
    <th scope="col" align="center">หน่วย</th>
    <th scope="col" align="center">ค่ามาตรฐาน</th>
  </tr>
  </thead>
  <tbody>
  <?php $i=0; do { $i++; 

  ?>
  <tr>
    <td align="center"><?php echo $i; ?></td>
    <td align="center"><?php echo $row_rs_lab_order['lab_items_name']; ?></td>
    <td align="center" class="<?php if(( $row_rs_lab_order['lab_order_result']<$row_rs_lab_order['range_check_min']) or ($row_rs_lab_order['lab_order_result']>$row_rs_lab_order['range_check_max'])){ echo "small_red";} ?>"><strong><?php echo $row_rs_lab_order['lab_order_result']; ?></strong></td>
    <td align="center" class=""><?php echo $row_rs_lab_order['lab_items_unit']; ?></td>
    <td align="center"><?php echo $row_rs_lab_order['lab_items_normal_value']; ?></td>
  </tr>
  <?php } while ($row_rs_lab_order = mysql_fetch_assoc($rs_lab_order)); ?>
  </tbody>
</table>
</div>
</div>
</div>
</body>
</html>
<?php mysql_free_result($rs_lab_order); ?>