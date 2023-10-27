<?php require_once('Connections/dis.php'); ?>
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

mysql_select_db($database_dis, $dis);
$query_rs_drug = "select p.icode,concat(d.name,' ',d.strength) as drugname,p.detail from ".$database_kohrx.".kohrx_drug_g6pd p left outer join s_drugitems d on d.icode=p.icode ORDER BY name ASC";
$rs_drug = mysql_query($query_rs_drug, $dis) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);

mysql_select_db($database_dis, $dis);
$query_rs_config = "select hospitalname from opdconfig";
$rs_config = mysql_query($query_rs_config, $dis) or die(mysql_error());
$row_rs_config = mysql_fetch_assoc($rs_config);
$totalRows_rs_config = mysql_num_rows($rs_config);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../css/kohrx.css" rel="stylesheet" type="text/css" />
</head>

<body>
<p class="big_black16">รายการยาที่ควรระมัดระวังในผู้ป่วยที่เป็น G6PD&nbsp;<?php echo $row_rs_config['hospitalname']; ?></p>
<table width="100%" border="0" cellspacing="1" cellpadding="3" bgcolor="#CCCCCC">
  <tr class="table_head_small_bord gray">
    <td width="8%" height="28" align="center">ลำดับ</td>
    <td width="33%" align="center">รายการยา</td>
    <td width="59%" align="center">รายละเอียด</td>
  </tr>
<?php $i=0; do { $i++;
  if($bgcolor=="#FFFFFF") { $bgcolor="#F8F8F8"; $font="#FFFFFF"; } else { $bgcolor="#FFFFFF"; $font="#999999";  }

 ?>  
<tr class="table_head_small grid">
    
    <td align="center" valign="top" bgcolor="<?php echo $bgcolor; ?>"><?php echo $i; ?></td>
    <td align="left" valign="top" bgcolor="<?php echo $bgcolor; ?>" style="padding-left:10px"><?php echo $row_rs_drug['drugname']; ?></td>
    <td align="left" bgcolor="<?php echo $bgcolor; ?>" style="padding-left:10px"><?php echo $row_rs_drug['detail']; ?></td> 
  </tr> <?php } while ($row_rs_drug = mysql_fetch_assoc($rs_drug)); ?>
</table>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rs_drug);

mysql_free_result($rs_config);
?>
