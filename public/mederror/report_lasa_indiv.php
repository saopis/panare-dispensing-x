<?php require_once('../Connections/hos.php'); ?>

<?php
	include('../include/function.php');
	include('include/function.php');

if($_GET['pttype']!="all"){ $condition=" and pttype='".$_GET['pttype']."'";}
else if($_GET['pttype']=="all"){ $condition =""; }

mysql_select_db($database_hos, $hos);
$query_person = "select doctor_code from ".$database_kohrx.".kohrx_med_error_indiv2 where date_error BETWEEN '".$_GET['date1']."' and '".$_GET['date2']."' ".$condition." group by doctor_code";
$person = mysql_query($query_person, $hos) or die(mysql_error());
$row_person = mysql_fetch_assoc($person);
$totalRows_person = mysql_num_rows($person);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>


</head>

<body>
	<div style="overflow:scroll;overflow-x:hidden;overflow-y:auto; height:75vh; padding: 10px; ">
	<div class="p-2"><h4>สรุปความคลาดเคลื่อนทางยา LASA รายบุคคล</h4></div>
<?php do { 
mysql_select_db($database_hos, $hos);
$query_lasa = "SELECT count(m.id) as lasa,m.drug1,m.drug2 FROM ".$database_kohrx.".kohrx_med_error_indiv2 m  WHERE m.date_error between '".$_GET['date1']."' and '".$_GET['date2']."'  and m.doctor_code='".$row_person['doctor_code']."' and (m.drug1 != '0' or m.drug2 != '0') ".$condition." GROUP BY m.drug1,m.drug2";
$lasa = mysql_query($query_lasa, $hos) or die(mysql_error());
$row_lasa = mysql_fetch_assoc($lasa);
$totalRows_lasa = mysql_num_rows($lasa);


if($totalRows_lasa>0){
?>
  <table width="100%" class="table table-striped table-bordered">
    <tr>
      <td><strong>ผู้จัด :</strong> <span class="style1"><?php echo doctorname($row_person['doctor_code']); ?></span></td>
    </tr>
    <tr>
      <td>
	             <?php

?>
 
	  <?php if ($totalRows_lasa > 0) { // Show if recordset not empty ?>
          <table width="100%" class="table table-striped table-bordered table-hover font14">
            <tr>
              <td width="41" align="center" style="border:solid 1px #000000">no.</td>
            <td width="341" align="center" style="border:solid 1px #000000">drug1</td>
            <td width="334" align="center" style="border:solid 1px #000000">drug2</td>
            <td width="84" align="center" style="border:solid 1px #000000">จำนวนครั้ง</td>
          </tr>
            <? $i=0; do { $i++;
			mysql_select_db($database_hos, $hos);
$query_drug1 = "SELECT concat(name,' ',strength) as drug from drugitems where icode='".$row_lasa['drug1']."'";
$drug1 = mysql_query($query_drug1, $hos) or die(mysql_error());
$row_drug1 = mysql_fetch_assoc($drug1);
$totalRows_drug1 = mysql_num_rows($drug1);


mysql_select_db($database_hos, $hos);
$query_drug2 = "SELECT concat(name,' ',strength) as drug from drugitems where icode='".$row_lasa['drug2']."'";
$drug2 = mysql_query($query_drug2, $hos) or die(mysql_error());
$row_drug2 = mysql_fetch_assoc($drug2);
$totalRows_drug2 = mysql_num_rows($drug2);

			 ?><tr>
              <td align="center" style="border:solid 1px #000000"><? echo $i; ?></td>
            <td align="center" style="border:solid 1px #000000"><?php echo $row_drug1['drug']; ?></td>
            <td align="center" style="border:solid 1px #000000"><?php echo $row_drug2['drug']; ?></td>
            <td align="center" style="border:solid 1px #000000"><?php echo $row_lasa['lasa']; ?></td>
          </tr><?
mysql_free_result($drug1);
  mysql_free_result($drug2);
 } while	($row_lasa = mysql_fetch_assoc($lasa)); ?>
                </table>
          <?php } // Show if recordset not empty ?>
          </td>
    </tr>
  </table><? } ?>
  <?php 

  } while ($row_person = mysql_fetch_assoc($person)); ?>
	</div>
</body>
</html>
<?php
mysql_free_result($person);

mysql_free_result($lasa);
?>
