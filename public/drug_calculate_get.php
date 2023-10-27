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
if(isset($_POST['do'])&&$_POST['do']=="add"){
mysql_select_db($database_hos, $hos);
$query_insert = "insert into ".$database_kohrx.".kohrx_drugitems_calculate (icode,dosage_min,dosage_max,dose_perunit) value ('$drug','$dosage_min','$dosage_max','$dose_perunit')";
$rs_insert = mysql_query($query_insert, $hos) or die(mysql_error());
	}
if(isset($_POST['do'])&&$_POST['do']=="edit"){
mysql_select_db($database_hos, $hos);
$query_update = "update ".$database_kohrx.".kohrx_drugitems_calculate set dosage_min='$dosage_min',dosage_max='$dosage_max',dose_perunit='$dose_perunit' where icode='$id'";
$rs_update = mysql_query($query_update, $hos) or die(mysql_error());
	}
if(isset($_POST['do'])&&$_POST['do']=="delete"){
mysql_select_db($database_hos, $hos);
$query_delete = "delete from ".$database_kohrx.".kohrx_drugitems_calculate where icode='$id'";
$rs_delete = mysql_query($query_delete, $hos) or die(mysql_error());
	}
	
mysql_select_db($database_hos, $hos);
$query_rs_drug = "SELECT concat(d2.name,' ',d2.strength) as drugname,d.icode,d.dosage_min,d.dosage_max,d.dose_perunit FROM ".$database_kohrx.".kohrx_drugitems_calculate d left outer join drugitems d2 on d2.icode=d.icode order by d2.name ASC";
$rs_drug = mysql_query($query_rs_drug, $hos) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);mysql_select_db($database_hos, $hos);
$query_rs_drug = "SELECT concat(d2.name,' ',d2.strength) as drugname,d.icode,d.dosage_min,d.dosage_max,d.dose_perunit FROM ".$database_kohrx.".kohrx_drugitems_calculate d left outer join drugitems d2 on d2.icode=d.icode ORDER BY d2.name ASC";
$rs_drug = mysql_query($query_rs_drug, $hos) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
</head>

<body>
  <table width="100%" border="0" cellpadding="3" cellspacing="0"  id="table" class="table table-striped table-bordered row-border hover " style="width:100%; ">
	<thead>
    <tr>
    <td height="24" align="center" class="bar1 table_head_small_bord thfont">ลำดับ</td>
    <td align="center" class="bar1 table_head_small_bord thfont">รายการยา</td>
    <td align="center" class="bar1 table_head_small_bord thfont">min/dose</td>
    <td align="center" class="bar1 table_head_small_bord thfont">max/dose</td>
    <td align="center" class="bar1 table_head_small_bord thfont">ขนาดยา/cc</td>
  </tr>
  </thead>
  <tbody>
   <?php $i=0; do { $i++;
     if($bgcolor=="#FFFFFF") { $bgcolor="#CFE1E7"; $font="#FFFFFF"; } else { $bgcolor="#FFFFFF"; $font="#999999";  }

    ?><tr class="grid2 table_head_small thfont">
   
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo $i; ?></td>
      <td align="left" bgcolor="<?php echo $bgcolor; ?>"><a href="#" onclick="formedit('<?php echo $row_rs_drug['icode']; ?>','<?php echo $row_rs_drug['dosage_min']; ?>','<?php echo $row_rs_drug['dosage_max']; ?>','<?php echo $row_rs_drug['dose_perunit']; ?>')" class="table_head_small" style="padding-left:10px" ><?php echo "$row_rs_drug[drugname]"; ?></a></td>
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo "$row_rs_drug[dosage_min]"; ?></td>
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo "$row_rs_drug[dosage_max]"; ?></td>
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo "$row_rs_drug[dose_perunit]"; ?></td>
     
  </tr> <?php } while ($row_rs_drug = mysql_fetch_assoc($rs_drug)); ?>
</tbody>
</table>

</body>
</html>
<?php
mysql_free_result($rs_drug);
?>
