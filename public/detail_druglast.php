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

if($_GET['vstdate']!=''){
$condition = " and vstdate = '".$_GET['vstdate']."' and o.rxdate='".$_GET['vstdate']."' and hn='".$_GET['hn']."' ";
}
if(!isset($_GET['vstdate'])) {
$condition = " and o.vstdate='".$_GET['vstdate']."' and o.rxdate='".$_GET['vstdate']."' and o.hn='".$_GET['hn']."' ";
	
}

mysql_select_db($database_hos, $hos);
$query_rs_druglast = "select concat(d.name,' ',d.strength) as drugname,u.code,o.qty,o.an,o.item_type from opitemrece o left outer join drugitems d on d.icode=o.icode left outer join drugusage u on u.drugusage=o.drugusage  where o.icode like '1%'  ".$condition."  and ifnull(item_type,'')!='P' order by o.item_no";
//echo $query_rs_druglast;
$rs_druglast = mysql_query($query_rs_druglast, $hos) or die(mysql_error());
$row_rs_druglast = mysql_fetch_assoc($rs_druglast);
$totalRows_rs_druglast = mysql_num_rows($rs_druglast);

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<?php //include('java_css_file.php'); ?>

</head>

<body>
<table border="0" class=" table table-sm table-hover font12" style="color:#000;width:100%; font-size:12px; ">
<?php $i=0; do { $i++;
  if($bgcolor=="#DCFFD5") { $bgcolor="#FFFFFF";} else { $bgcolor="#DCFFD5"; } 
    ?>
    <tr >
      <td  align="center" bgcolor="<?php echo $bgcolor; ?>" ><?php echo $i; ?></td>
      <td  bgcolor="<?php echo $bgcolor; ?>" style="font-size:11px;" ><strong><?php echo $row_rs_druglast['drugname']; ?></strong><div class="font12" style="padding-left: 20px;"><?php print $row_rs_druglast['code']; ?></div></td>
      <td  bgcolor="<?php echo $bgcolor; ?>" class="thsan-semibold font14" ><?php print $row_rs_druglast['qty']; ?></td>
    </tr>
    <?php } while ($row_rs_druglast = mysql_fetch_assoc($rs_druglast)); ?>
 </table>
</body>
</html>
<?php
mysql_free_result($rs_druglast);
?>