<?
ob_start();
session_start();
?>
<?php require_once('Connections/hos.php'); ?>
<?php
	mysql_select_db($database_hos, $hos);
    $get_ip=$_SERVER["REMOTE_ADDR"];

?>
<?php include('include/function.php'); ?>
<?php include('include/function_sql.php'); ?>
<?php
    if(isset($_POST['hn'])&&($_POST['hn']!="")){
        $hn=$_POST['hn']; 
    }
    if(isset($_GET['hn'])&&($_GET['hn']!="")){
        $hn=$_GET['hn']; 
    }
    if(isset($_POST['vstdate'])&&($_POST['vstdate']!="")){
        $vstdate=$_POST['vstdate']; 
    }
    if(isset($_GET['vstdate'])&&($_GET['vstdate']!="")){
        $vstdate=$_GET['vstdate']; 
    }
    if(isset($_POST['vn'])&&($_POST['vn']!="")){
        $vn=$_POST['vn']; 
    }
    if(isset($_GET['vn'])&&($_GET['vn']!="")){
        $vn=$_GET['vn']; 
    }

if(isset($_GET['action'])&&($_GET['action']=="delete")){
				mysql_select_db($database_hos, $hos);
				$query_delete = "delete from ".$database_kohrx.".kohrx_drug_checked where hos_guid='".$_GET['hos_guid']."'";
				$delete = mysql_query($query_delete, $hos) or die(mysql_error());				

}
if(isset($_GET['action'])&&($_GET['action']=="checked")){
		mysql_select_db($database_hos, $hos);
		$query_rs_search = "select * from ".$database_kohrx.".kohrx_drug_checked where vn='".$_GET['vn']."' and hos_guid='".$_GET['hos_guid']."'";
		$rs_search = mysql_query($query_rs_search, $hos) or die(mysql_error());
		$row_rs_search = mysql_fetch_assoc($rs_search);
		$totalRows_rs_search = mysql_num_rows($rs_search);
			if($totalRows_rs_search==0){
				mysql_select_db($database_hos, $hos);
				$query_insert = "insert into ".$database_kohrx.".kohrx_drug_checked (vn,hos_guid,doctorcode) value ('".$_GET['vn']."','".$_GET['hos_guid']."','".$_SESSION['doctorcode']."')";
				$insert = mysql_query($query_insert, $hos) or die(mysql_error());			
				
				if($insert){
					mysql_select_db($database_hos, $hos);
					$query_rs_drug = "select d.therapeutic,o.drugusage,concat(u.name1,u.name2,u.name3) as iusage,concat(s.name1,s.name2,s.name3) as sp_usage from opitemrece o left outer join drugitems d on o.icode=d.icode left outer join drugusage u on u.drugusage=o.drugusage left outer join sp_use s on s.sp_use=o.sp_use  where o.hos_guid='".$_GET['hos_guid']."'";
					//echo $query_rs_drug;
					$rs_drug = mysql_query($query_rs_drug, $hos) or die(mysql_error());
					$row_rs_drug = mysql_fetch_assoc($rs_drug);
					$totalRows_rs_drug = mysql_num_rows($rs_drug);
					
						if($row_rs_drug['drugusage']<>""||$row_rs_drug['drugusage']<>NULL){
							$usage=$row_rs_drug['iusage'];
						}
						else{
							$usage=$row_rs_drug['sp_usage'];
						}
						$text="".$row_rs_drug['therapeutic']." ".$usage;
                    //echo $text;
					}
			}


		mysql_free_result($rs_search);

}
if(isset($_GET['action'])&&($_GET['action']=="all")){
				mysql_select_db($database_hos, $hos);
				$query_delete = "delete from ".$database_kohrx.".kohrx_drug_checked where vn='".$_GET['vn']."'";
				$delete = mysql_query($query_delete, $hos) or die(mysql_error());				

}

if(isset($_GET['barcode'])&&($_GET['barcode']!="")){
	mysql_select_db($database_hos, $hos);
	$query_rs_search1 = "select hos_guid,vn from opitemrece where vn='".$_GET['vn']."' and substr(md5(hos_guid),-10)='".$_GET['barcode']."'";
	$rs_search1 = mysql_query($query_rs_search1, $hos) or die(mysql_error());
	$row_rs_search1 = mysql_fetch_assoc($rs_search1);
	$totalRows_rs_search1 = mysql_num_rows($rs_search1);

	if($totalRows_rs_search1<>0&&$row_rs_search1['vn']!=NULL){
		mysql_select_db($database_hos, $hos);
		$query_rs_search = "select * from ".$database_kohrx.".kohrx_drug_checked where vn='".$_GET['vn']."' and substr(md5(hos_guid),-10)='".$_GET['barcode']."'";
		$rs_search = mysql_query($query_rs_search, $hos) or die(mysql_error());
		$row_rs_search = mysql_fetch_assoc($rs_search);
		$totalRows_rs_search = mysql_num_rows($rs_search);
			if($totalRows_rs_search==0){
				mysql_select_db($database_hos, $hos);
				$query_insert = "insert into ".$database_kohrx.".kohrx_drug_checked (vn,hos_guid,doctorcode) value ('".$_GET['vn']."','".$row_rs_search1['hos_guid']."','".$_SESSION['doctorcode']."')";
				$insert = mysql_query($query_insert, $hos) or die(mysql_error());
                
                $msg="";
			}
            else{
            $msg="รายการนี้ เช็ค! แล้ว";
            
            }


		mysql_free_result($rs_search);
	}
    else{
        $msg="ไม่ใช่ยาของผู้ป่วยรายนี้";
    }
	mysql_free_result($rs_search1);
}

mysql_select_db($database_hos, $hos);
$query_s_drug = "select o.rxtime,o.icode,substring(o.icode,1,1) as scode,o.income,concat(s.name,' ',s.strength,' ',s.units) as drugname,s.did,o.qty, d.drugusage,concat('.',d.code) as code,d.name1,d.name2,d.name3,d.shortlist,o.vn,concat(sp.name1,sp.name2,sp.name3) as sp_name,sp.sp_use,o.hos_guid,dc.hos_guid as checked  
from opitemrece o 
left outer join sp_use sp on sp.sp_use=o.sp_use 
left outer join drugitems s on s.icode=o.icode
left outer join drugusage d on d.drugusage=o.drugusage  
left outer join sp_use u on u.sp_use = o.sp_use
left outer join ".$database_kohrx.".kohrx_drug_checked dc on dc.vn=o.vn and dc.hos_guid=o.hos_guid
where o.vn='".$_GET['vn']."'  and o.icode like '1%'
group by o.hos_guid,o.icode,s.name,s.strength,s.units,o.qty,o.unitprice,d.drugusage,d.code,d.name1,d.name2,d.name3,d.shortlist,u.name1,u.name2,u.name3   order by o.item_no ";
//echo $query_s_drug;
$s_drug = mysql_query($query_s_drug, $hos) or die(mysql_error());
$row_s_drug = mysql_fetch_assoc($s_drug);
$totalRows_s_drug = mysql_num_rows($s_drug);

mysql_select_db($database_hos, $hos);
$query_rs_caller = "select detail_drug_sound from ".$database_kohrx.".kohrx_queue_caller_channel where ip='".$get_ip."'";
$rs_caller = mysql_query($query_rs_caller, $hos) or die(mysql_error());
$row_rs_caller = mysql_fetch_assoc($rs_caller);
$totalRows_rs_caller = mysql_num_rows($rs_caller);

mysql_select_db($database_hos, $hos);
$query_rs_search = "select * from ".$database_kohrx.".kohrx_drug_checked where vn='".$_GET['vn']."'";
$rs_search = mysql_query($query_rs_search, $hos) or die(mysql_error());
$row_rs_search = mysql_fetch_assoc($rs_search);
$totalRows_rs_search = mysql_num_rows($rs_search);

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>ตรวจสอบรายการยา</title>
<?php include('include/kohrx/kohrx.php'); ?>
<?php include('java_css_online.php'); ?>

<style>
.dot {
  height: 50px;
  width: 50px;
	
  background-color: #bbb;
  border-radius: 50%;
  display: inline-block;
}
.switch {
  position: relative;
  display: inline-block;
  width: 45px;
  height: 20px;
  top: -15px;
}

/* Hide default HTML checkbox */
.switch input {
  opacity: 0;
  width: 0;
  height: 0;
      top: -15px;

}

/* The slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 16px;
  width: 16px;
  left: 2px;
  bottom: 2px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}
/* Rounded sliders */
.slider.round {
  border-radius: 20px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>
<style>
html,body{overflow:hidden; }
	
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
$(document).ready(function(){
    
	$('#barcode').focus();
	  $('#barcode').bind('keyup', function(e) {
		  if(e.which == '13'){ //enter
                window.location='detail_drug_check.php?vn=<?php echo $_GET['vn']; ?>&barcode='+$('#barcode').val();
		  }
	  });

$('#sound').click(function(){
    if($('#sound').prop('checked')){
        $("#speak").load('detail_drug_check_enable.php?sound=Y');
    }
    else{
        $("#speak").load('detail_drug_check_enable.php?sound=N');                         
    }
    
  });   

    
});

$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
    
function check_delete(hos_guid){
    window.location='detail_drug_check.php?vn=<?php  echo $vn; ?>&hn=<?php  echo $hn; ?>&vstdate=<?php  echo $vstdate; ?>&hos_guid='+hos_guid+'&action=delete';
}
function check_delete_all(hos_guid){
    window.location='detail_drug_check.php?vn=<?php  echo $vn; ?>&hn=<?php  echo $hn; ?>&vstdate=<?php  echo $vstdate; ?>&action=all';
}
function checked(hos_guid){
    window.location='detail_drug_check.php?vn=<?php  echo $vn; ?>&hn=<?php  echo $hn; ?>&vstdate=<?php  echo $vstdate; ?>&hos_guid='+hos_guid+'&action=checked';
}
function drugspeak(text){
		$('#speak').load('queue_audio.php?text='+encodeURIComponent(text));
}
</script>
</head>

<body>
<div class="bg-warning" style="height: 50px; margin-top: -10px;">
<h6 class="text-dark text-center pt-3"><i class="fas fa-check-double"></i>&emsp;ตรวจสอบรายการยา</h6>
</div>
<div class="position-absolute" style="margin-top: -40px; right: 50px;">
    เสียงอธิบายยา
    <label class="switch">
      <input type="checkbox" id="sound" <?php if($row_rs_caller['detail_drug_sound']=='Y'){ echo "checked"; } ?> >
      <span class="slider round"></span>
    </label>
</div>
<div style="overflow:scroll;overflow-x:hidden;overflow-y:auto; height:90vh;">    
<div class="p-3 bg-light">
<div class="form-group row" style="margin-bottom: 0px;">
    <label class="col-form-label col-form-label-sm col-sm-auto"><?php echo "<strong>ชื่อผู้ป่วย : </strong>".ptnameVn($_GET['vn'])."<strong>&emsp;&emsp;&emsp;&emsp;วันที่มารับบริการ : </strong>".dateThai(vnVstdate($_GET['vn'])); ?></label>
    <label class="col-form-label col-form-label-sm col-sm-auto font-weight-bold text-right"><i class="fas fa-barcode " style="font-size: 20px;"></i>&nbsp;บาร์โค้ดยา&nbsp;<i class="fas fa-question-circle" data-toggle="tooltip" data-placement="top" title="substr"></i></label>
    <div class="col-sm-auto">
        <input type="text" id="barcode" class="form-control form-control-sm" style="width: 200px;" />
    </div>
	<div class="col-sm-auto"><?php if($totalRows_s_drug<>$totalRows_rs_search){ echo "ยังไม่เช็ค <span class='text-danger font16'><strong>".($totalRows_s_drug-$totalRows_rs_search)."</strong></span> รายการ"; } else { echo "<span class='text-danger font16'><strong><i class=\"fas fa-check font20 text-success\"></i>&ensp;เช็คครบ</strong></span>"; } ?></div>
</div>
</div>
<?php if($totalRows_s_drug<>0){ ?>
<div class="p-3">
<?php if($msg!=""){ ?>
<div class="alert alert-danger text-danger" style="font-size: 20px;" role="alert"><i class="fas fa-exclamation-circle"></i>&emsp;<?php echo $msg; ?></div>
<?php } ?>
<table class="table table-striped table-bordered table-hover">
	<thead>
		<tr>
			<th class="text-center">ลำดับ</th>
			<th>ชื่อยา</th>
			<th>วิธีใช้</th>
			<th>จำนวน</th>
			<th class="text-center">checked</th>
		</tr>
	</thead>
	<tbody>
		<?php $i=0; do{ $i++ ?>
		<tr <?php if($row_s_drug['checked']!=""){ ?>class="w3-theme-d3" style="color: white"<?php } ?>>
			<td class="text-center"><?php echo $i; ?></td>
			<td><?php echo $row_s_drug['drugname']; ?></td>
			<td><?php if($row_s_drug['sp_use']=="") {echo $row_s_drug['shortlist']; } else { echo $row_s_drug['sp_name']; } ?></td>
			<td class="text-center"><?php echo $row_s_drug['qty']; ?></td>
			<td class="text-center" style="cursor: pointer" ><?php if($row_s_drug['checked']!=""){ ?><i class="fas fa-check font20 text-white" onClick="if(confirm('ต้องการยกเลิกเช็ค!! จริงหรือไม่?')==true){check_delete('<?php echo $row_s_drug['hos_guid']; ?>');}" ></i><?php } else if($row_s_drug['checked']==NULL) { ?><i class="fas fa-check font20" style="color: #E3DADA" onClick="checked('<?php echo $row_s_drug['hos_guid']; ?>');" ></i><? } ?></td>
		</tr>
		<?php }while($row_s_drug = mysql_fetch_assoc($s_drug)); ?>
	</tbody>
	<tfooter>
		<tr>
			<td colspan="5" class="text-right"><button class="btn btn-danger" onClick="if(confirm('ต้องการล้างข้อมูลเช็คทั้งหมด!! จริงหรือไม่?')==true){check_delete_all('<?php echo $row_s_drug['vn']; ?>');}">ล้างข้อมูล</button></td>
		</tr>
	</tfooter>
</table>
<div id="speak"></div>	
</div>
<?php } ?>
<?php if($text!=""&&$row_rs_caller['detail_drug_sound']=="Y"){
        echo "<script>drugspeak('".$text."');</script>";
        }
?>
  <input name="vn" type="hidden" id="vn" value="<?php echo $_GET['vn']; ?>" />
  <input name="vstdate" type="hidden" id="vstdate" value="<?php echo $_GET['vstdate']; ?>" />
  <input name="hn" type="hidden" id="hn" value="<?php echo $_GET['hn']; ?>" />
</div>
</body>
</html>
<?php mysql_free_result($s_drug); 		
mysql_free_result($rs_search);
mysql_free_result($rs_caller);
?>
