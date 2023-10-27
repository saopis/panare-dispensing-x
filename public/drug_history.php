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
$query_drug_list = "
SELECT count(o.icode) as countdrug,concat(d.name,' ',d.strength) as drugname,o.icode FROM (select icode from opitemrece WHERE hn='".$_GET['hn']."' and icode like '1%' union all select icode from opitemrece_arc WHERE hn='".$_GET['hn']."' and icode like '1%') as o left outer join drugitems d on d.icode=o.icode  GROUP BY o.icode ORDER BY d.name";
$drug_list = mysql_query($query_drug_list, $hos) or die(mysql_error());
$row_drug_list = mysql_fetch_assoc($drug_list);
$totalRows_drug_list = mysql_num_rows($drug_list);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php include('java_css_file.php'); ?>

<style type="text/css">
.white {	color:#FFFFFF;
	font-size:12px;
	font-weight:bolder;
}
tr.grid:hover {
    background-color: #FC3;
}

tr.grid:hover td {
    background-color: transparent; /* or #000 */
}
tr.grid2:hover {
    background-color:#D6EAEF;
}

tr.grid2:hover td {
    background-color: transparent; /* or #000 */
}
html,body{overflow-y:hidden; }
	::-webkit-scrollbar {
    width: 10px;
}

::-webkit-scrollbar-track {
    box-shadow: inset 0 0 10px 10px #E6E6E6;
    border: solid 3px transparent;
}

::-webkit-scrollbar-thumb {
    box-shadow: inset 0 0 10px 10px #CCCCCC;
    border: solid 3px transparent;
}

</style>
<script>
$(document).ready(function() {
    $('#item-history').hid();
});

function drug_history(hn,icode){
	alert();
    $('#item-history').show();
	
	}
</script>
</head>

<body >
<nav class="navbar navbar-dark bg-info text-white fixed-top">
  <!-- Navbar content -->
    <span class="font18"><i class="fas fa-history font20"></i>&ensp;รายการยาที่เคยใช้ทั้งหมด : <span class="font_bord"><?php echo $row_rs_pt['patient_name']; ?></span></span>
</nav><?php if ($totalRows_drug_list > 0) { // Show if recordset not empty ?>
  <table width="600" border="0" cellpadding="3" cellspacing="0" class="table" style="margin-top:40px;">
  <thead>
    <tr class="table_head_small">
      <th width="9%" height="22" align="center" bgcolor="#A9DAED" >ลำดับ</th>
      <th width="77%" align="left" bgcolor="#A9DAED" >รายการยา</th>
      <th width="14%" align="center" bgcolor="#A9DAED" >จำนวนครั้ง</th>
    </tr>
    </thead>
  </table>
<div style="margin-top:-18px;overflow:scroll;overflow-x:hidden;overflow-y:auto; height:450px;">
  <table width="600" border="0" cellpadding="3" cellspacing="0" class="table table-sm" style="border:solid 1px #CCCCCC">
<tbody>
    <?php $i=0; do {  $i++; 
  if($bgcolor=="#FFFFFF") { $bgcolor="#E4F4FA"; $font="#FFFFFF"; } else { $bgcolor="#FFFFFF"; $font="#999999";  }
  ?>
    <tr class="grid cursor font14" onclick="window.location='item_history.php?hn=<?php echo $_GET['hn']; ?>&icode=<?php echo $row_drug_list['icode']; ?>&back=Y';">
      <td align="center" bgcolor="<?php echo $bgcolor; ?>" ><?php echo $i; ?></td>
      <td align="left" bgcolor="<?php echo $bgcolor; ?>" ><?php echo $row_drug_list['drugname']; ?></td>
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo $row_drug_list['countdrug']; ?></td>
    </tr>
    <?php } while ($row_drug_list = mysql_fetch_assoc($drug_list)); ?>
    </tbody>
  </table>
 </div>
  <?php } // Show if recordset not empty ?>
</body>
</html>
<?php
mysql_free_result($drug_list);

?>
