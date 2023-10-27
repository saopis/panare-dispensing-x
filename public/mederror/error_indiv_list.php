<?php require_once('../Connections/hos.php'); ?>
<?php
include('../include/function.php');

$datestart=$_GET['datestart'];
$dateend=$_GET['dateend'];

if(isset($_GET['action'])&&($_GET['action']=="delete")){ 
mysql_select_db($database_hos, $hos);
$delete="delete from ".$database_kohrx.".kohrx_med_error_indiv2  where id='".$_GET['id']."'";
$qdelete=mysql_query($delete, $hos) or die(mysql_error());
echo "<script>$('#time1').focus();</script>";

}

if(isset($_GET['action'])&&($_GET['action']=="add")){
if($_GET['time']==""){
	$msg="กรุณากรอกเวลาด้วยครับ";
	echo "<script>$('#time1').focus();</script>";
}
else if($_GET['pttype']==""){  $msg= "กรุณาเลือกประเภทผู้ป่วย";	echo "<script>$('#pttype').focus();</script>";
}
else if($_GET['error_type']==""){  $msg= "กรุณาเลือกประเภทความผิดพลาด";	echo "<script>$('#type').focus();</script>";
}
else if($_GET['error_type']=="58"){
 if($_GET['lasa1']==""||$_GET['lasa2']==""){ $msg= "กรุณาเลือกยาให้ครบทั้ง 2 ตัว"; }
	 
	if($_GET['lasa1']!=""){
		 $lasa1="'".$_GET['lasa1']."'"; 
	 }
	 if($_GET['lasa2']!=""){
		 $lasa2="'".$_GET['lasa2']."'"; 
	 }
}
else if($_GET['person_code']==""){  $msg= "กรุณาเลือกบุคลากรที่เกิดความผิดพลาด"; 	echo "<script>$('#person_code').focus();</script>";
}
else{ $msg=""; $lasa1="NULL"; $lasa2="NULL"; }

	if($msg!=""){
	echo "<div class='alert alert-danger position-absolute' style='z-index: 1;width: 98%' role='alert'><i class='fas fa-ban'>&ensp;".$msg."</i></div>"; exit(); 
	}
mysql_select_db($database_hos, $hos);
$insert1="insert into ".$database_kohrx.".kohrx_med_error_indiv2 (date_error,doctor_code,error_type,pttype,drug1,drug2,time1,room_id) values ('".$_GET['date']."','".$_GET['person_code']."','".$_GET['error_type']."','".$_GET['pttype']."',".$lasa1.",".$lasa2.",'".$_GET['time']."','".$_GET['room_id']."')";
$qinsert1=mysql_query($insert1, $hos) or die(mysql_error());

mysql_select_db($database_hos, $hos);
$query_lasa = "select i.* from ".$database_kohrx.".kohrx_med_error_indiv2 i where date_error='".$_GET['date']."' and lasagroup is null and (drug1!=0 or drug2!=0)";
$lasa = mysql_query($query_lasa, $hos) or die(mysql_error());
$row_lasa = mysql_fetch_assoc($lasa);
$totalRows_lasa = mysql_num_rows($lasa);

if($totalRows_lasa<>0){
do { 
$a = array($row_lasa['name1'].":".$row_lasa['drug1'].":",$row_lasa['name2'].":".$row_lasa['drug2'].":");
sort($a);

$aa= explode(":",$a[0]);
$bb=explode(":",$a[1]);
$lasagroup=$aa[1].$bb[1];
	
mysql_select_db($database_hos, $hos);
$query_lasa1 = "update ".$database_kohrx.".kohrx_med_error_indiv2 set lasagroup ='".$lasagroup."' where id=".$row_lasa['id']."";
$qlasa1 = mysql_query($query_lasa1, $hos) or die(mysql_error());

 } while ($row_lasa = mysql_fetch_assoc($lasa)); 
}

mysql_free_result($lasa);	

$datestart=$_GET['date'];
$dateend=$_GET['date'];
echo "<script>document.getElementById('med_form').reset();</script>";
}

mysql_select_db($database_hos, $hos);
$query_error = "SELECT m.id,m.date_error,p.person,t.name,m.person as personid,m.doctor_code,m.time1,m.drug1,m.drug2 FROM ".$database_kohrx.".kohrx_med_error_indiv2 m left outer join ".$database_kohrx.".kohrx_med_error_person p on p.id=m.person left outer join ".$database_kohrx.".kohrx_med_error_error_cause t on t.id=m.error_type  WHERE m.date_error between '".$datestart."' and '".$dateend."' ORDER BY m.date_error ASC";
$error = mysql_query($query_error, $hos) or die(mysql_error());
$row_error = mysql_fetch_assoc($error);
$totalRows_error = mysql_num_rows($error);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<?php include('java_css_file.php'); ?> 
</head>

<body>
  <?php if ($totalRows_error > 0) { // Show if recordset not empty ?>
<div style=" margin-top: -17px; margin-right: <?php if($totalRows_error>5){echo "5px"; } else { echo "-10px"; } ?>">
  <table class="table table-bordered thfont font14" style="width: 98%"  >
	<thead class="thead-light">
	  <tr class=" text-center">
      <th scope="col" width="3%" rowspan="2" align="center" >#</th>
      <th scope="col" width="17%" rowspan="2" align="center" >วัน เวลา</th>
      <th scope="col" width="15%" rowspan="2" align="center">ชื่อ</th>
      <th scope="col" width="22%" rowspan="2" align="center">ความผิดพลาด</th>
      <th scope="col" colspan="3" align="center">คู่ยา Lasa </th>
    </tr>
    <tr class=" text-center">
      <th scope="col" width="20%" align="center">drug1</th>
      <th scope="col" width="20%" align="center">drug2</th>
      <th scope="col" width="3%" align="center">&nbsp;</th>
    </tr>
	</thead>
	</table>
	</div>
<div style="overflow:scroll;overflow-x:hidden;overflow-y:auto; height:38vh; margin-top: -17px; margin-right: -10px; ">
<table class="table table-bordered thfont font14" style="width: 98%; margin-right: -10px;">
	<? $i=0; do{ $i++; 
	mysql_select_db($database_hos, $hos);
	$query_rs_doctor = "select name from doctor where code='".$row_error['doctor_code']."'";
	$rs_doctor = mysql_query($query_rs_doctor, $hos) or die(mysql_error());
	$row_rs_doctor = mysql_fetch_assoc($rs_doctor);
	$totalRows_rs_doctor = mysql_num_rows($rs_doctor);
	
		mysql_select_db($database_hos, $hos);
$query_rs_drug1 = "SELECT concat(name,' ',strength) as drugname FROM drugitems d  WHERE icode='".$row_error['drug1']."'";
$rs_drug1 = mysql_query($query_rs_drug1, $hos) or die(mysql_error());
$row_rs_drug1 = mysql_fetch_assoc($rs_drug1);
$totalRows_rs_drug1 = mysql_num_rows($rs_drug1);

	mysql_select_db($database_hos, $hos);
$query_rs_drug2 = "SELECT concat(name,' ',strength) as drugname FROM drugitems d  WHERE icode='".$row_error['drug2']."'";
$rs_drug2 = mysql_query($query_rs_drug2, $hos) or die(mysql_error());
$row_rs_drug2 = mysql_fetch_assoc($rs_drug2);
$totalRows_rs_drug2 = mysql_num_rows($rs_drug2);

	?>
    <tr>
      <td align="center" width="3%"><? echo $i; ?></td>
      <td align="center" width="17%"><?php echo dateThai($row_error['date_error'])." ".$row_error['time1']; ?></td>
      <td align="center" width="15%"><?php echo $row_rs_doctor['name']; ?></td>
      <td align="center" width="22%" class="font12"><?php echo $row_error['name']; ?></td>
      <td align="center" width="20%" class="font12"><?php echo $row_rs_drug1['drugname']; ?></td>
      <td align="center" width="20%" class="font12"><?php echo $row_rs_drug2['drugname']; ?></td>
      <td align="center" width="3%"><i class="fas fa-trash-alt cursor font20" style="cursor: pointer" onClick="if(confirm('ต้องการลบจริงหรือไม่')==true){deleteResult('<?php echo $row_error['id']; ?>');}"></i></td>
    </tr>
    <?php
mysql_free_result($rs_doctor);	
mysql_free_result($rs_drug1);	
mysql_free_result($rs_drug2);	

	 } while ($row_error = mysql_fetch_assoc($error)); ?>
  </table>
</div>
  <?php }   else { ?>
<div class="alert alert-primary position-absolute" style="z-index: 1;width: 98%" role="alert">
  <i class="fas fa-ban">&ensp;ไม่มีข้อมูล</i>
</div>
	<?php } ?>

</body>
</html>
<?php mysql_free_result($error); ?>