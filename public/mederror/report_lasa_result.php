<?php require_once('../Connections/hos.php'); ?>
<?php
		if($_GET['pttype']!="all"){ $condition=" and pttype='".$_GET['pttype']."'";}
		else if($_GET['pttype']=="all"){ $condition =""; }

mysql_select_db($database_hos, $hos);
$query_lasa = "SELECT count(m.lasagroup) as lasa,m.drug1,m.drug2 FROM ".$database_kohrx.".kohrx_med_error_indiv2 m  WHERE m.date_error between '".$_GET['date1']."' and '".$_GET['date2']."' and (m.drug1 != '' or m.drug2 != '') ".$condition." and m.drug1!=m.drug2 GROUP BY lasagroup order by lasa DESC";
$lasa = mysql_query($query_lasa, $hos) or die(mysql_error());
$row_lasa = mysql_fetch_assoc($lasa);
$totalRows_lasa = mysql_num_rows($lasa);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
	<?php if($totalRows_lasa<>0){?>
	<div style="overflow:scroll;overflow-x:hidden;overflow-y:auto; height:75vh; padding: 10px; ">
	<div class="p-2"><h4>รายงาน LASA รวม</h4></div>
	<table width="100%" class="table table-striped table-bordered table-hover font14">
        <tr>
          <td width="9%" align="center" style="border:solid 1px #000000">ลำดับ</td>
          <td width="42%" align="center" style="border:solid 1px #000000">ยา1</td>
          <td width="39%" align="center" style="border:solid 1px #000000">ยา2</td>
          <td width="10%" align="center" style="border:solid 1px #000000">จำนวนครั้ง</td>
        </tr>
        <?php $i=0;do { $i++; 
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
		
	?>
        <tr>
          <td align="center" style="border:solid 1px #000000"><?php echo $i; ?></td>
          <td align="center" style="border:solid 1px #000000"><?php echo $row_drug1['drug']; ?></td>
          <td align="center" style="border:solid 1px #000000"><?php echo $row_drug2['drug']; ?></td>
          <td align="center" style="border:solid 1px #000000"><?php echo $row_lasa['lasa']; ?></td>
        </tr>
        <?php 
		mysql_free_result($drug1);
		mysql_free_result($drug2);

		} while ($row_lasa = mysql_fetch_assoc($lasa)); ?>
      </table>
	  </div>

    </tr>
  </table>
<?php } ?>
</body>
</html>