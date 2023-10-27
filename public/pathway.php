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
$query_pathway = "select d.outdate,d.intime,d.outtime,k1.department as from_department,k2.department as to_department,o.name as staff_name  from ptdepart d  left outer join kskdepartment k1 on k1.depcode = d.depcode  left outer join kskdepartment k2 on k2.depcode = d.outdepcode  left outer join opduser o on o.loginname = d.staff  where d.vn = '".$_GET['vn']."'  order by d.intime";
$pathway = mysql_query($query_pathway, $hos) or die(mysql_error());
$row_pathway = mysql_fetch_assoc($pathway);
$totalRows_pathway = mysql_num_rows($pathway);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="include/kohrx/css/kohrx.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="100%" class="table display">
  <tr>
    <td  align="center">&nbsp;</td>
    <td  align="center" ><span class="badge badge-dark font16">INTIME</span></td>
    <td  align="center"><span class="badge badge-dark font16">OUTTIME</span></td>
    <td align="center"><span class="badge badge-danger text-white font16">จุดบริการต้นทาง</span></td>
    <td  align="center"><i class="fas fa-long-arrow-alt-right font20"></i></td>
    <td  align="center"><span class="badge badge-primary font16">จุดบริการปลายทาง</span></td>
    <td align="center"><span class="badge badge-dark font16">เจ้าหน้าที่</span></td>
  </tr>
      <?php $i=0; do { $i++;
	    if($bgcolor=="#FFFFFF") { $bgcolor="#E1E1E1"; $font="#FFFFFF"; } else { $bgcolor="#FFFFFF"; $font="#999999";  }

	   ?>
<tr class="grid2">
      <td align="center" bgcolor="<?php echo $bgcolor; ?>" class="rounded_top_left rounded_bottom_left"><span class="badge badge-info font20" style="width: 50px;"><?php echo $i; ?></span></td>
      <td align="center" bgcolor="<?php echo $bgcolor; ?>" ><?php echo "$row_pathway[intime]"; ?></td>
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo "$row_pathway[outtime]"; ?></td>
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><span class="table_head_small_bord"><?php echo "$row_pathway[from_department]"; ?></span></td>
      <td align="left" bgcolor="<?php echo $bgcolor; ?>" class="rounded_top_right rounded_bottom_right"><i class="fas fa-long-arrow-alt-right font20"></i></td>
      <td align="center" bgcolor="<?php echo $bgcolor; ?>" class="rounded_top_right rounded_bottom_right"><span class="small_red_bord">&nbsp; <?php echo "$row_pathway[to_department]"; ?></span></td>
      <td align="left" bgcolor="<?php echo $bgcolor; ?>" class="rounded_top_right rounded_bottom_right"><?php echo "$row_pathway[staff_name]"; ?></td>
  </tr>      <?php } while ($row_pathway = mysql_fetch_assoc($pathway)); ?>

</table>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($pathway);
?>
