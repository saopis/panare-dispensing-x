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
$query_rs_drug_creatinine = "select p.id,p.icode,concat(d.name,' ',d.strength) as drugname,p.min_value,p.max_value,p.remark,p.detail,p.cr_min_value,p.cr_max_value from ".$database_kohrx.".kohrx_drug_creatinine p left outer join s_drugitems d on d.icode=p.icode ORDER BY name ASC";
$rs_drug_creatinine = mysql_query($query_rs_drug_creatinine, $hos) or die(mysql_error());
$row_rs_drug_creatinine = mysql_fetch_assoc($rs_drug_creatinine);
$totalRows_rs_drug_creatinine = mysql_num_rows($rs_drug_creatinine);
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
<nav class="navbar navbar-dark thfont bg-success text-white" >
  <!-- Navbar content -->
  <span class="card-title" style="padding-top:5px;">
ยาที่จำเป็นต้องปรับขนาดตามค่าไต
</span>
</nav>
<div style="overflow:scroll;overflow-x:hidden;overflow-y:auto; margin-right: 10px; ">   
<table width="100%" class="table table-bordered">
<thead class="text-center">
    <tr>
    <th width="5%" height="23" align="center">ลำดับ</th>
    <th width="20%" align="center">ชื่อยา</th>
    <th width="10%" align="center">Serum Cr.</th>
    <th width="10%" align="center">GFR</th>
    <th width="25%" align="center">คำแนะนำ</th>
    <th width="30%" align="center">รายละเอียด</th>
  </tr>
</thead>
    </table>
    </div>
<div style="overflow:scroll;overflow-x:hidden;overflow-y:auto; height:85vh; margin-top:-17px; ">
<table width="100%" class="table table-bordered table-striped" >
<tbody>
  <?php $j=0; do { $j++; 

	  ?>
  <tr>
    <td height="28" width="5%" align="center" valign="top"><?php echo $j; ?></td>
    <td align="center" width="20%" valign="top"><?php echo "$row_rs_drug_creatinine[drugname]"; ?></td>
    <td align="center" width="10%" valign="top"><?php echo "$row_rs_drug_creatinine[cr_min_value]-$row_rs_drug_creatinine[cr_max_value]"; ?></td>
    <td align="center" width="10%" valign="top"><?php echo "$row_rs_drug_creatinine[min_value] - $row_rs_drug_creatinine[max_value]"; ?><a href="JavaScript:if(confirm('ต้องการลบข้อมูลนี้จริงหรือไม่?')==true){window.location='drug_setting_pulse.php?do=drug_pulse_delete&amp;icode=<?php echo $row_rs_drug_pulse["icode"]; ?>';}"></a></td>
    <td align="left" width="25%" valign="top"><?php echo "$row_rs_drug_creatinine[remark]"; ?></td>
    <td align="left" width="30%" valign="top"><?php echo "$row_rs_drug_creatinine[detail]"; ?><a href="JavaScript:if(confirm('ต้องการลบข้อมูลนี้จริงหรือไม่?')==true){window.location='drug_setting_pulse.php?do=drug_pulse_delete&amp;icode=<?php echo $row_rs_drug_pulse["icode"]; ?>';}"></a></td>
  </tr>
  <?php } while ($row_rs_drug_creatinine = mysql_fetch_assoc($rs_drug_creatinine)); ?>
</tbody>
</table>
</div>
</body>
</html>
<?php
mysql_free_result($rs_drug_creatinine);
?>
