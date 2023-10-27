<?
ob_start();
session_start();
?>
<?php require_once('Connections/hos.php'); ?>
<?php 
include('include/function.php');
if($_GET['action']=="add"&&$_GET['an']!=""){
	mysql_select_db($database_hos, $hos);
	$query_s_an = "SELECT hn from ipt where an='".$_GET['an']."' and hn='".$_GET['hn']."'";
	$s_an = mysql_query($query_s_an, $hos) or die(mysql_error());
	$row_s_an = mysql_fetch_assoc($s_an);
	$totalRows_s_an = mysql_num_rows($s_an);
	
	if($row_s_an['hn']!=""){
		mysql_select_db($database_hos, $hos);
		$query_update = "update ".$database_kohrx.".kohrx_med_reconcile set an='".$_GET['an']."' where hn='".$_GET['hn']."' and vstdate2='".date_th2db($_GET['vstdate'])."'";
		$update = mysql_query($query_update, $hos) or die(mysql_error());
		
		if($update){
		echo "<script>window.location='med_reconcile.php?do=link&hn=".$_GET['hn']."&vstdate=".$_GET['vstdate']."';</script>";			
		}
	}
	else{
		echo "<script>alert('AN ไม่ถูกต้อง กรุณาตรวจสอบใหม่');</script>";
		
	}
	mysql_free_result($s_an);
	exit();
}
else if($_GET['action']=="add"&&$_GET['an']==""){
		mysql_select_db($database_hos, $hos);
		$query_update = "update ".$database_kohrx.".kohrx_med_reconcile set an=NULL where hn='".$_GET['hn']."' and vstdate2='".date_th2db($_GET['vstdate'])."'";
		$update = mysql_query($query_update, $hos) or die(mysql_error());
		
		if($update){
		echo "<script>window.location.reload();</script>";			
		}
	exit();
}
/////////////// s_patient ค้นหาข้อมูลพื้นฐานผู้ป่วย ///////////////
mysql_select_db($database_hos, $hos);
$query_s_patient = "SELECT v.vn,v.hn, concat(p.pname,p.fname,'    ',p.lname) as patient_name,v.age_y,v.age_m,concat(p.addrpart,'  ม. ',p.moopart,'  ',t.full_name) as thaiaddress,v.vstdate,ov.pttype,ov.doctor,v.pdx,v.dx0,v.dx1,v.dx2,v.dx3,v.dx4,v.dx5,p.cid,v.pttypeno,p.sex,ov.oqueue,date_format(v.vstdate,'%d/%m/%Y') as visitdate,ov.vsttime FROM patient p  left outer join pname s on s.name=p.pname left outer join thaiaddress t on t.chwpart=p.chwpart and t.tmbpart=p.tmbpart and t.amppart=p.amppart left outer join vn_stat v on v.hn=p.hn left outer join ovst ov on ov.vn=v.vn  where v.hn='".$_GET['hn']."' and v.vstdate='".date_th2db($_GET['vstdate'])."'";
//echo $query_s_patient;
$s_patient = mysql_query($query_s_patient, $hos) or die(mysql_error());
$row_s_patient = mysql_fetch_assoc($s_patient);
$totalRows_s_patient = mysql_num_rows($s_patient);

//////////////////ค้นหา AN ///////////////////
mysql_select_db($database_hos, $hos);
$query_s_an = "SELECT an from ".$database_kohrx.".kohrx_med_reconcile where hn='".$_GET['hn']."' and vstdate2='".date_th2db($_GET['vstdate'])."'";
//echo $query_s_patient;
$s_an = mysql_query($query_s_an, $hos) or die(mysql_error());
$row_s_an = mysql_fetch_assoc($s_an);
$totalRows_s_an = mysql_num_rows($s_an);

if($row_s_an['an']==""){
mysql_select_db($database_hos, $hos);
$query_s_an2 = "SELECT an from ipt where hn='".$_GET['hn']."' and regdate='".date_th2db($_GET['vstdate'])."'";
//echo $query_s_patient;
$s_an2 = mysql_query($query_s_an2, $hos) or die(mysql_error());
$row_s_an2 = mysql_fetch_assoc($s_an2);
$totalRows_s_an2 = mysql_num_rows($s_an2);
$an=$row_s_an2['an'];
mysql_free_result($s_an2);
}
else{
$an=$row_s_an['an'];
$med_an=1;
	
}
mysql_free_result($s_an);

//+++++++++++++++ vital sign ++++++++++++++++++//
//======= screen ========//
mysql_select_db($database_hos, $hos);
$query_screen = "select bpd,bps,bw,cc,pe,hr,pulse,temperature,pregnancy,breast_feeding,height from opdscreen where vn='".$row_s_patient['vn']."'";
$screen = mysql_query($query_screen, $hos) or die(mysql_error());
$row_screen = mysql_fetch_assoc($screen);
$totalRows_screen = mysql_num_rows($screen);

///// ค้นหายาที่แพทย์ off //////
//========== pdx =================//
mysql_select_db($database_hos, $hos);
$query_s_pdx = "select code,name from icd101 where code='".$row_s_patient['pdx']."'";
$s_pdx = mysql_query($query_s_pdx, $hos) or die(mysql_error());
$row_s_pdx = mysql_fetch_assoc($s_pdx);
$totalRows_s_pdx = mysql_num_rows($s_pdx);

//========== แพทย์ผู้สั่งใช้ยา ==========//
mysql_select_db($database_hos, $hos);
$query_s_doctor = "select d.name,p.name as position_name,p.id,d.code from doctor d left outer join doctor_position p on d.position_id = p.id where d.code='".$row_s_patient['doctor']."'";
$s_doctor = mysql_query($query_s_doctor, $hos) or die(mysql_error());
$row_s_doctor = mysql_fetch_assoc($s_doctor);
$totalRows_s_doctor = mysql_num_rows($s_doctor);

mysql_select_db($database_hos, $hos);
$query_rs_disease = "select * from ".$database_kohrx.".kohrx_med_reconcile_disease_type";
$rs_disease = mysql_query($query_rs_disease, $hos) or die(mysql_error());
$row_rs_disease = mysql_fetch_assoc($rs_disease);
$totalRows_rs_disease = mysql_num_rows($rs_disease);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<script>
$(document).ready(function() {
    $('#indicator_an').hide();
	
	med_reconcile_load();
	
	$('#disease_save').click(function(){
                $("#disease_result").load('med_reconcile_disease_result.php?action=add&hn=<?php echo $_GET['hn']; ?>&vstdate=<?php echo $_GET['vstdate']; ?>&disease_type='+$('#disease').val(), function(responseTxt, statusTxt, xhr){
                    
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();
                    if(statusTxt == "error")
                        alert("Error: " + xhr.status + ": " + xhr.statusText);    
                                                
                 ///////////////////////////// 
                });			
	});
	$('#an_save').click(function(){
                $('#indicator_an').show();		
                $("#an_save_result").load('med_reconcile_detail.php?action=add&hn=<?php echo $_GET['hn']; ?>&vstdate=<?php echo $_GET['vstdate']; ?>&an='+$('#an').val(), function(responseTxt, statusTxt, xhr){
                    
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator_an').hide();
						med_reconcile_load();
					
                    if(statusTxt == "error")
                        alert("Error: " + xhr.status + ": " + xhr.statusText);    
                                                
                 ///////////////////////////// 
                });			
	});

});
function disease_delete(hn,vstdate,disease) {
  var result = confirm("ต้องการลบ?");
  if (result==true) {

   $("#disease_result").load('med_reconcile_disease_result.php?action=delete&hn='+hn+'&vstdate='+vstdate+'&disease_type='+disease, function(responseTxt, statusTxt, xhr){
                    
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();
                    if(statusTxt == "error")
                        alert("Error: " + xhr.status + ": " + xhr.statusText);    
                                                
                 ///////////////////////////// 
                });	
  } else {
   return false;
  }
}
</script>
</head>

<body>
<?php if($totalRows_s_patient<>0){?>

    <div class="row">
        <div class="col" style="-ms-flex: 0 0 130px;flex: 0 0 130px;">
        <div align="center">
        <?
	mysql_select_db($database_hos, $hos);
 	$query_selpic = "select count(*) as cc from patient_image where hn='".$_GET['hn']."' ";
	$selpic = mysql_query($query_selpic, $hos) or die(mysql_error());
	$row_selpic = mysql_fetch_assoc($selpic);
	$totalRows_selpic = mysql_num_rows($selpic);
				if($row_selpic['cc']>0){
				mysql_select_db($database_hos, $hos);
				$query = "SELECT image as blob_img FROM patient_image where hn='".$_GET['hn']."' "; 
				$result = mysql_query($query, $hos) or die(mysql_error()); 
				$row = mysql_fetch_array($result); 
				$jpg = $row["blob_img"]; 
	mysql_free_result($selpic);
							?>
							<img src="data:image/jpeg;base64,<?php echo base64_encode($jpg); ?> "  width="100" height="120" vlign="middle" border="0" style="border-radius: 8px; border:solid 1px #E3E1E1"> <?php 
						}
						else {
							echo "<img src=\"images/noimage.png\" width=\"80\" height=\"94\" />";
							}
							?>
        </div>
<div align="center">
<strong>HN:<?php echo $row_s_patient['hn']; ?></strong><input type="hidden" id="hidden_hn" value="<?php echo $hn; ?>"/><br />
	
	<strong style="font-size:12px">VN:<?php echo $row_s_patient['vn']; ?></strong>

    <div style="margin-bottom: 5px;">
    <buton class="btn btn-light" style="width:100px; font-size:14px;"><nobr>
  visit Q. <span class="badge badge-light" style="font-size: 16px; padding:2px;"><?php echo $row_s_patient['oqueue']; ?></span></nobr>
</button>
    </div>
	<div><input type="text" id="an" class="form-control text-center <?php if($med_an!=1){ echo "text-danger"; } else { echo "text-dark";} ?>" value="<?php echo $an; ?>"/></div>
	<div class="mt-2">
	  <button class="btn <?php if($med_an!=1){ echo "btn-success";} else { echo "btn-danger";} ?>" id="an_save"><?php if($med_an!=1){ echo "AN ถูกต้อง?"; } else {echo "แก้ไข AN";} ?></button></div>
        <!--indicator-->
         <div id="indicator_an" align="center" class="spinner mt-2" >
         <button class="btn btn-secondary" type="button" style="" disabled>
		  <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
		</button>
         </div>
         <!--indicator-->	
		<div id="an_save_result"></div>
	



        </div>
        </div>

     <div class="col-md-4" style="padding:5px;">
<!-- patient profile -->

  <div id='patient_profile'>
  <div class="card" style="margin-right: 5px;">
  <div class="card-header" style="font-size:18px; padding: 5px;">ข้อมูลทั่วไป</div>
	<div class="card-body" style="padding:0px; min-height: 250px;">
<table width="100%" border="0" cellpadding="3" cellspacing="0" class="grid_font" >
        <tr >
          <td align="left" class="pl-2"   >       
         ชื่อผู้ป่วย
         <!--indicator--></td>
          <td align="left"   ><div class="text-left" style="font-size:25px ; font-weight:bolder; text-wrap:none">  <nobr><?php echo $row_s_patient['patient_name']; ?>&nbsp;<a href="javascript:valid(0);" onClick="alertload('caller_key.php?hn=<?php echo $hn; ?>','0','0');        setTimeout(function(){$('#respondent').focus()}, 1000);
              " target="queue_caller" style="padding:0px;" ></a></nobr></div></td>
          </tr>
        <tr class="grid4">
          <td width="17%" align="left" class="pl-2" >วันที่มา</td>
          <td width="83%" align="left" ><?php echo date_db2th($row_s_patient['vstdate']); ?> &nbsp;เวลา <?php echo $row_s_patient['vsttime']; ?></td>
        </tr>
        <tr class="grid4">
          <td   class="pl-2">อายุ </td>
          <td bgcolor="#FFFFFF"  ><?php echo $row_s_patient['age_y']; ?> ปี <?php echo $row_s_patient['age_m']; ?> เดือน</td>
        </tr>
        <tr class="grid4">
          <td   class="pl-2" >CID</td>
          <td  ><?php echo $row_s_patient['cid']; ?></td>
        </tr>
        <tr class="grid4">
          <td   class="pl-2">ที่อยู่</td>
          <td  ><?php echo $row_s_patient['thaiaddress']; ?></td>
        </tr>
        <tr class="grid4">
          <td align="left"  class="pl-2" >สิทธิ</td>
          <td align="left"  >
            <?php echo $row_pttyp['name']; ?><span class="table_head_small ">(<?php echo $row_s_patient['pttypeno']; ?>)</span></td>
        </tr>
      </table>  


	</div>
    <!-- card body -->
</div>
    <!-- card -->

    </div>
<!-- patient profile -->
<!-- drug allergy -->
<?php if ($totalRows_allergy > 0||$totalRows_opd_allergy>0) { // Show if recordset not empty ?>
<div class="card mt-2">
    <div class="card-body p-1 bg-light">
    
    <span class="badge badge-danger font16 card-title font_bord p-2 cursor" onClick="alertload('allergy.php?hn=<?php echo $row_s_patient['hn']; ?>','80%','80%');">รายการยาที่แพ้</span>
    &ensp;
    <span class="text-danger font12 font_bord">
        <?php $a=0; do{ $a++; echo $row_allergy['agent']; if($a!=$totalRows_allergy){ echo " , ";} } while($row_allergy = mysql_fetch_assoc($allergy)); ?>
    </span>
    </div>
</div>
<!-- drug allergy -->
<?php } ?>

</div>

<!-- patient vital sign -->
    <div class="col" style="padding:5px; margin-right: 10px;">
  <div class="card" >
  <div class="card-header" style="font-size:18px; padding:5px;">ข้อมูลการซักประวัติและการตรวจรักษา</div>
  
  <div class="card-body grid_font" style="padding:5px; min-height: 150px;">
      <div style="padding: 5px;">
        
          BT. 
            <span class="badge badge-pill badge-secondary font12"><?php echo str_replace('.00','',number_format($row_screen['temperature'],2)); ?></span>
            องศา&nbsp; BW. 
            <a href="javascript:alertload('bw_chart.php?graph=bw&amp;hn=<?php echo $hn; ?>','90%','90%');" ><span class="badge badge-pill badge-secondary font12"><?php echo str_replace('.00','',number_format($row_screen['bw'],2)); ?></span></a>&nbsp;ก.ก. &nbsp;IBW&nbsp;<span class="badge badge-pill badge-secondary font12"><?php echo $ibw; ?></span>&nbsp;ก.ก.&nbsp;HR.
            <span class="badge badge-pill badge-secondary font12"><?php print number_format($row_screen['hr']); ?></span>&nbsp;Pulse 
            <a href="javascript:alertload('pulse_chart.php?graph=pulse&amp;hn=<?php echo $hn; ?>','90%','90%');" ><span class="badge badge-pill badge-secondary font12"><?php print number_format($row_screen['pulse']); ?></span></a>&nbsp; BP.
          <a href="javascript:alertload('bp_chart.php?graph=bp&hn=<?php echo $hn; ?>','90%','90%');"><span class="badge badge-pill badge-secondary font12"><?=number_format($row_screen['bps'])."/".number_format($row_screen['bpd']); ?></span></a>
            
      </div>
      <div class="row vital-box" >
          <div class="col-md-1" style="font-weight: bold"><nobr>CC</nobr></div>
          <div class="col"><?php echo $row_screen['cc']; ?></div>
      </div>
      <div class="row vital-box" >
          <div class="col-md-1" style="font-weight: bold"><nobr>PE</nobr></div>
          <div class="col"><?php if(strlen($row_screen['pe'])>80){ echo iconv_substr($row_screen['pe'],0,80,"UTF-8")."..."; ?><a href="javascript:void(0);" style="font-weight:bold; font-size:12px;color:#0066CC; text-decoration:none; position:absolute; left:1200px;"  class="tooltip">อ่านเต็ม<span class="tooltiptext"><?php echo $row_screen['pe']; ?></span></a><?php } else{ echo $row_screen['pe'];  } ?></div>
      </div>
      <div class="row vital-box" >
          <div class="col-md-1" style="font-weight: bold"><nobr>Dx.</nobr></div>
          <div class="col"><?php echo $row_s_pdx['code']; ?> : <span class="table_head_small_underline"><?php echo $row_s_pdx['name']; ?> </span><br />
[dx0: <?php echo $row_s_patient['dx0']; ?>] [dx1: <?php echo $row_s_patient['dx1']; ?>] [dx2: <?php echo $row_s_patient['dx2']; ?>] [dx3: <?php echo $row_s_patient['dx3']; ?>] [dx4: <?php echo $row_s_patient['dx4']; ?>] [dx5: <?php echo $row_s_patient['dx5']; ?>]</div>
      </div>
      <div class="row vital-box" >
          <div class="col-md-1" style="font-weight: bold"><nobr>Doctor</nobr></div>
          <div class="col"><?php echo $row_s_doctor['name']; ?></div>
      </div>
    
  </div>
  <!-- card body vital sign -->
  </div>
  <!-- card vital sign -->
<div class="card mt-2">
    <div class="card-header">โรคประจำตัว</div>
    <div class="card-body p-1">
      <div class="row vital-box mt-2" >
          <div class="col-sm-2">
              <select id="disease" class="form-control form-control-sm">
                        <?php do { ?>
                            <option value="<?php echo $row_rs_disease['med_reconcile_disease_type']; ?>"><?php echo $row_rs_disease['med_reconcile_disease_name']; ?></option>
                        <?php
                        } while ($row_rs_disease = mysql_fetch_assoc($rs_disease));
                          $rows = mysql_num_rows($type_error);
                          if($rows > 0) {
                              mysql_data_seek($rs_disease, 0);
                             $row_rs_disease = mysql_fetch_assoc($rs_disease);
                          }
                        ?>
              </select>
          </div>
          <div class="col-sm-auto">
              <button id="disease_save" class="btn btn-dark btn-sm">เพิ่ม</button>
          </div>
          <div class="col-sm-auto" id="disease_result">
          </div>		  
      </div>          
    </div>
</div>
    </div>
<!-- patient vital sign -->         
        
    </div>
<!-- row -->

<!-- row -->
<div class="row mt-2">
    <div class="col-sm-auto" style="-ms-flex: 0 0 130px;flex: 0 0 130px;"></div>
    <div class="col">
        <div class="card">
            <div class="card-header bg-gray1 " style="padding: 5px; padding-left: 10px;"><span class="card-title font_bord font18">รายการยา med. reconcile</span>&emsp;<button class="btn btn-info btn-sm" style="width: 100px;" onClick="alertload('med_reconcile_visit.php?hn=<?php echo $_GET['hn']; ?>&vstdate2=<?php echo date_th2db($_GET['vstdate']); ?>','90%','90%')">เลือกจาก visit</button></div>
            <div class="card-body" id="med_reconcile_drug" style="padding:0px;"></div>
        </div>
    </div>
</div>
<!-- row -->
<?php } else { ?>
<div style="padding: 20px;">
    <div class="card">
        <div class="card-body font18 thfont">
            <i class="fas fa-user-slash font20"></i>&ensp;
    ผู้ป่วยไม่ได้มารับบริการในวันที่คุณเลือก
    </div>
    </div>
</div>
<?php 
}?>    
</body>
</html>
<?php
mysql_free_result($s_patient);

mysql_free_result($screen);

mysql_free_result($s_pdx);

mysql_free_result($s_doctor);


?>