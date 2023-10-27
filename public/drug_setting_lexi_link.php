<?php require_once('Connections/hos.php'); 

if(isset($_POST['button9'])&&$_POST['button9']=="เพิ่ม"){
mysql_select_db($database_hos, $hos);
$query_insert = "insert into ".$database_kohrx.".kohrx_drug_lexi_link (icode,link) value ('$lasa_drug','$url')";
$rs_insert = mysql_query($query_insert, $hos) or die(mysql_error());
}
if(isset($_GET['do'])&&$_GET['do']=="spacial_delete"){
mysql_select_db($database_hos, $hos);
$query_insert = "delete from ".$database_kohrx.".kohrx_drug_lexi_link where icode ='$icode'";
$rs_insert = mysql_query($query_insert, $hos) or die(mysql_error());
}

mysql_select_db($database_hos, $hos);
$query_rs_sp1 = "SELECT concat(d.name,' ',d.strength) as drugname,s.icode,s.link FROM ".$database_kohrx.".kohrx_drug_lexi_link s left outer join s_drugitems d on s.icode=d.icode order by d.name ASC";
$rs_sp1 = mysql_query($query_rs_sp1, $hos) or die(mysql_error());
$row_rs_sp1 = mysql_fetch_assoc($rs_sp1);
$totalRows_rs_sp1 = mysql_num_rows($rs_sp1);

mysql_select_db($database_hos, $hos);
$query_rs_drug2 = "SELECT icode,concat(name,strength) as drugname FROM s_drugitems WHERE icode like '1%' and istatus='Y' and icode not in (select icode from ".$database_kohrx.".kohrx_drug_lexi_link) ORDER BY name ASC";
$rs_drug2 = mysql_query($query_rs_drug2, $hos) or die(mysql_error());
$row_rs_drug2 = mysql_fetch_assoc($rs_drug2);
$totalRows_rs_drug2 = mysql_num_rows($rs_drug2);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
</head>

<body>
<p class="button_red">กำหนดลิงค์ LEXI Comp.</p>
<form id="form1" name="form1" method="post" action="">
  <p>
    <select name="lasa_drug" id="lasa_drug">
      <?php
do {  
?>
      <option value="<?php echo $row_rs_drug2['icode']?>"><?php echo $row_rs_drug2['drugname']?></option>
      <?php
} while ($row_rs_drug2 = mysql_fetch_assoc($rs_drug2));
  $rows = mysql_num_rows($rs_drug2);
  if($rows > 0) {
      mysql_data_seek($rs_drug2, 0);
	  $row_rs_drug2 = mysql_fetch_assoc($rs_drug2);
  }
?>
    </select>
    <input name="url" type="text" id="url" size="50" />
    <input name="button9" type="submit" class="button_red" id="button9" value="เพิ่ม" />
    </a>
  </p>
 <?php if ($totalRows_rs_sp1 > 0) { // Show if recordset not empty ?>
  <table width="500" border="0" cellpadding="3" cellspacing="1" class="head_small_gray">
    <tr>
      <td width="37" align="center" bgcolor="#CCCCCC">ลำดับ</td>
      <td width="343" align="center" bgcolor="#CCCCCC">ชื่อยา</td>
      <td width="55" align="center" bgcolor="#CCCCCC">link</td>
      <td width="36" align="center" bgcolor="#CCCCCC">&nbsp;</td>
    </tr>
    <?php $i=0; do { $i++; 
	       if($bgcolor=="#FFFFFF") { $bgcolor="#CFE1E7"; $font="#FFFFFF"; } else { $bgcolor="#FFFFFF"; $font="#999999";  }

	  ?><tr>
      
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo $i; ?></td>
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo $row_rs_sp1['drugname']; ?></td>
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><a href="<?php echo $row_rs_sp1['link']; ?>"><img src="images/socialmedia.png" width="38" height="38" border="0" /></a></td>
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><a href="JavaScript:if(confirm('ต้องการลบข้อมูลนี้จริงหรือไม่?')==true){window.location='drug_setting_lexi_link.php?do=spacial_delete&amp;icode=<?php echo $row_rs_sp1["icode"]; ?>';}"><img src="images/bin.png" width="16" height="16" border="0" align="absmiddle" /></a><a href="#" onclick="formSubmit('delete','sp_display','indicator','<?php echo $row_rs_sp1['icode']; ?>')"></a></td>
      </tr>      <?php } while ($row_rs_sp1 = mysql_fetch_assoc($rs_sp1)); ?>
    
  </table>
  <?php } // Show if recordset not empty ?>
</form>
<p>&nbsp;</p>
</body>
</html>
<?php 
mysql_free_result($rs_drug2);


?>