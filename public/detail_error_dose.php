<?php require_once('Connections/hos.php'); ?>
<?php 
if(!isset($_GET['answer'])){

include('include/function.php');

mysql_select_db($database_hos, $hos);
$query_rs_drug = "select o.hn,o.vn,o.icode,concat(d.name,' ',d.strength) as drugname,d2.dosage_min,d2.dosage_max,d2.dose_perunit,u.name1,u.name2,u.name3,u.shortlist,syr.is_error from opitemrece o left outer join drugitems d on d.icode=o.icode left outer join ".$database_kohrx.".kohrx_drugitems_calculate d2 on d2.icode=d.icode left outer join drugusage u on u.drugusage=o.drugusage left outer join ".$database_kohrx.".kohrx_syr_dosing_record syr on syr.vn=o.vn and o.icode=syr.icode where o.hos_guid='".$_GET['hos_guid']."'";
$rs_drug = mysql_query($query_rs_drug, $hos) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<script>
	$(document).ready(function(){
		$('#yes').click(function(){
			$('#modal-body-normal').load('detail_error_dose.php?answer=Y&vn=<?php echo $row_rs_drug['vn']; ?>&icode=<?php echo $row_rs_drug['icode']; ?>',function(responseTxt, statusTxt, xhr){
                            if(statusTxt == "success")
								//$('#myModal-normal').modal('hide');
                            if(statusTxt == "error")
                            	alert("Error: " + xhr.status + ": " + xhr.statusText);    
                        });
		;
		});
	});
function modal_normal_close(){
		$('#myModal-normal').modal('hide');
}
</script>
</head>

<body>
<?php 
if(isset($_GET['answer'])&&($_GET['answer']=='Y')){
    mysql_select_db($database_hos, $hos);
    $query_update = 
    "update ".$database_kohrx.".kohrx_syr_dosing_record 
     SET    is_error = CASE
         WHEN is_error ='Y' THEN 'N'
         WHEN is_error = 'N' THEN 'Y'
         ELSE 'N'
     END       
     where vn='".$_GET['vn']."' and icode='".$_GET['icode']."'";
    $rs_update = mysql_query($query_update, $hos) or die(mysql_error());
    
    echo "<script>drug_list_load_vn('".$_GET['vn']."');modal_normal_close();</script>";
	exit();
}
	?>
<div class="font14">
<div class="row">
	<div class="col-sm-2 text-secondary">น้ำหนัก</div>
	<div class="col-sm-auto"><?php echo "<span class='font16 text-danger'><strong>".$_GET['bw']."</strong></span>"; ?>&emsp;กิโลกรัม</div>
</div>
<div class="row">
	<div class="col-sm-2 text-secondary">สั่ง</div>
	<div class="col-sm-auto"><?php echo "<span class='font16 text-danger'><strong>".$row_rs_drug['drugname']."</strong></span>"; ?></div>

</div>	
<div class="row">
	<div class="col-sm-2 text-secondary">วิธีใช้</div>
	<div class="col-sm-auto"><?php echo "<span class='font16 text-danger'><strong>".$row_rs_drug['shortlist']."</strong></span>"; ?></div>

</div>	
</div>
<?php if($_GET['case']=="hd"){ ?>
<div class="text-center mt-5"><span class="badge badge-danger font20"><nobr><i class="fas fa-arrow-circle-up text-white"></i>&nbsp;hight dose</nobr></span></div><?php } ?>
<?php if($_GET['case']=="ld"){ ?>
<div class="text-center mt-5"><span class="badge badge-warning font20 text-danger"><nobr><i class="fas fa-arrow-circle-down text-danger"></i>&nbsp;low dose</nobr></span></div><?php } ?>
<div class="card mt-3">
	<div class="card-body bg-light">
		<div class="text-center ">การสั่งครั้งนี้<?php if($row_rs_drug['is_error']=="Y"){ echo "<span style='color:red;font-size:18px'>ไม่</span>"; } ?>เกิดความคลาดเคลื่อนจริงหรือไม่?&nbsp;<button class="btn btn-danger btn-sm" id="yes">ใช่</button>&nbsp;<button class="btn btn-dark btn-sm" id="no" onClick="modal_normal_close();">ไม่ใช่</button></div>

	</div>
</div>
</body>
</html>
<?php mysql_free_result($rs_drug); ?>