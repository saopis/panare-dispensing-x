<?php ob_start();?>
<?php session_start();?>
<?php if(($_SESSION['r_opd']!='Y') and ($_SESSION['r_finance']!='Y')){
	echo "คุณไม่ได้รับสิทธิ์ให้ใช้งานในส่วนนี้";
	exit();
	} ?>
<?php require_once('Connections/hos.php'); ?>
<?php 
$_SESSION['pt']="OPD";
$get_ip=$_SERVER["REMOTE_ADDR"];

if($_GET['q']!=""){ $method="q"; }
else if($_GET['hn']!=""){ $method="hn"; }

include('include/function.php');
require('detail_query.php'); 

//ถ้ามีการเปิดใช้ image server ให้แนบ connection img.php มาด้วย
	if($row_setting[43]=="Y"){
		require_once('Connections/img.php');
	}

$_SESSION["ss_hn"]=$row_s_patient['hn'];
$_SESSION["ss_vn"]=$row_s_patient['vn'];
$_SESSION["ss_vstdate"]=$row_s_patient['vstdate'];

//echo $hn;
//echo $_GET['full_screen'];

mysql_select_db($database_hos, $hos);
$query_rs_full = "select full_screen from ".$database_kohrx.".kohrx_queue_caller_channel where ip='".$get_ip."'";
$rs_full = mysql_query($query_rs_full, $hos) or die(mysql_error());
$row_rs_full = mysql_fetch_assoc($rs_full);
$totalRows_rs_full = mysql_num_rows($rs_full);
$full_screen=$row_rs_full['full_screen'];
mysql_free_result($rs_full);

if(isset($_GET['full_screen'])&&($_GET['full_screen']!="")){
//ค้นหาข้อมูล full screen

if($full_screen!="Y" ||$full_screen==NULL )
{
mysql_select_db($database_hos, $hos);
$query_update = "update ".$database_kohrx.".kohrx_queue_caller_channel set full_screen = 'Y' where ip='".$get_ip."' ";
echo $query_update;
$udpate = mysql_query($query_update, $hos) or die(mysql_error());
$full_screen='Y';
}
else
{
mysql_select_db($database_hos, $hos);
$query_update = "update ".$database_kohrx.".kohrx_queue_caller_channel set full_screen = '' where ip='".$get_ip."' ";
$udpate = mysql_query($query_update, $hos) or die(mysql_error());  
$full_screen='N';
}

}

//ค้นหาข้อมูลการ login
mysql_select_db($database_hos, $hos);
$query_rs_login2 = "select * from ".$database_kohrx.".kohrx_login_check where login_name='".$_SESSION['username_log']."' and ipaddress='".$get_ip."' and substr(last_time,1,10)=CURDATE()";
$rs_login2 = mysql_query($query_rs_login2, $hos) or die(mysql_error());
$row_rs_login2 = mysql_fetch_assoc($rs_login2);
$totalRows_rs_login2 = mysql_num_rows($rs_login2);
//ถ้าพบ
	if($totalRows_rs_login2<>0){
	mysql_select_db($database_hos, $hos);
	$update = "update ".$database_kohrx.".kohrx_login_check set last_time=NOW() where login_name='".$_SESSION['username_log']."' and substr(last_time,1,10)=CURDATE()";
	$rs_update = mysql_query($update, $hos) or die(mysql_error());
	
		//บันทึกลง log
		mysql_select_db($database_hos, $hos);
		$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_login_check set last_time=NOW() where login_name=\'".$_SESSION['username_log']."\' and substr(last_time,1,10)=CURDATE()')";
		$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());

	}
//ถ้าไม่พบ
	else{
	mysql_select_db($database_hos, $hos);
	$insert = "insert into ".$database_kohrx.".kohrx_login_check (login_name,ipaddress,last_time) value ('".$_SESSION['username_log']."','".$get_ip."',NOW())";
	$rs_insert = mysql_query($insert, $hos) or die(mysql_error());
	
		//บันทึกลง log
		mysql_select_db($database_hos, $hos);
		$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_login_check (login_name,ipaddress,last_time) value (\'".$_SESSION['username_log']."\',\'".$get_ip."\',NOW())')";
		$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());
	}

mysql_free_result($rs_login2);

//ค้นหาการแพ้ยา
mysql_select_db($database_hos, $hos);
$query_allergy = "select hn,report_date,agent,symptom,reporter from opd_allergy where hn='".$row_s_patient['hn']."' order by report_date DESC";
$allergy = mysql_query($query_allergy, $hos) or die(mysql_error());
$row_allergy = mysql_fetch_assoc($allergy);
$totalRows_allergy = mysql_num_rows($allergy);

        if($totalRows_allergy<>0){ 
            
			echo "<script>alertload('allergy.php?hn=".$row_s_patient['hn']."','80%','80%');</script>";    
        
         } 

//ค้นหาการบันทึก ADR
mysql_select_db($database_hos, $hos);
$query_rs_edit_adr = "select * from ".$database_kohrx.".kohrx_adr_check where vn='".$vn1."'";
$rs_edit_adr = mysql_query($query_rs_edit_adr, $hos) or die(mysql_error());
$row_rs_edit_adr = mysql_fetch_assoc($rs_edit_adr);
$totalRows_rs_edit_adr = mysql_num_rows($rs_edit_adr);

//ค้นหาการบันทึกจัดจ่ายยา
mysql_select_db($database_hos, $hos);
$query_rs_rx_operator = "select  print_staff,check_staff,confirm_staff,pay_staff,note,receiver,receiver_other,lock_order,rx_time,note from rx_operator where vn='".$vn1."' and pay='Y' ";
$rs_rx_operator = mysql_query($query_rs_rx_operator, $hos) or die(mysql_error());
$row_rs_rx_operator = mysql_fetch_assoc($rs_rx_operator);
$totalRows_rs_rx_operator = mysql_num_rows($rs_rx_operator);
 if($totalRows_rs_rx_operator<>0) { 
 if($row_rs_rx_operator['print_staff']!=""){
 $print_staff2= $row_rs_rx_operator['print_staff'];
 }
 if($row_rs_rx_operator['check_staff']!=""){
 $check_staff2= $row_rs_rx_operator['check_staff'];
 }
 if($row_rs_rx_operator['confirm_staff']!=""){
 $confirm_staff2= $row_rs_rx_operator['confirm_staff'];
 }

 if($row_rs_rx_operator['pay_staff']!=""){
 $pay_staff2= $row_rs_rx_operator['pay_staff'];
 }
 
 }
$vstdate=$row_s_patient['vstdate'];
// เริ่ม load ส่วนต่างๆ
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php //include('java_css_file.php'); ?>
    
    <style>
        .vital-box{
            padding: 2px 2px 2px 10px;
        }
   
	.containerimg {
	  position: relative;
	  width: 100%;
		left: 5%
	}

	.image {
	  opacity: 1;
	  display: block;
	  width: 120px;
	  height: auto;
	  transition: .5s ease;
	  backface-visibility: hidden;
	}

	.middle {
	  transition: .5s ease;
	  opacity: 0;
	  position: absolute;
	  /*top: 50%;*/
	  left: 48%;
	  bottom: 0px;
	  width: 120px;
	  transform: translate(-50%);
	  -ms-transform: translate( -50%);
	  text-align: center;
	}

	.containerimg:hover .image {
	  opacity: 0.7;
	}

	.containerimg:hover .middle {
	  opacity: 1;
	}

	.text {
	  background-color:darkgrey;
	  color: white;
	  font-size: 14px;
	  padding: 8px;
	  cursor: pointer;
		border-bottom-left-radius: 8px;
		border-bottom-right-radius: 8px;
	}
	.text:hover {
	  background-color:crimson;
	  color: white;
	  font-size: 14px;
	  padding: 8px;
	  cursor: pointer;
		border-bottom-left-radius: 8px;
		border-bottom-right-radius: 8px;
	}
	</style>
<script>
    $(document).ready(function(){
		$('#pe2').hide();
		
	    $('#call_indicator').hide();
		
        $('#drug-last').hide();

	    $('#caller_indicator').hide();
		
		$('#rx-operator-panel').hide();
        
        

        //load short cut
        shortcut_load('<?php echo $hn2; ?>','<?php echo date_db2th($vstdate); ?>','<?php echo $row_s_patient['pdx']; ?>','<?php echo $row_s_patient['dx0']; ?>','<?php echo $row_s_patient['dx1']; ?>','<?php echo $row_s_patient['dx2']; ?>','<?php echo $row_s_patient['dx3']; ?>','<?php echo $row_s_patient['dx4']; ?>','<?php echo $row_s_patient['dx5']; ?>','<?php echo $row_s_patient['age_y']; ?>','<?php echo $row_s_patient['pttype']; ?>','<?php echo $row_s_patient['vn']; ?>');

		//load label icon
        label_load('<?php echo $hn2; ?>');
        
        //load drug list
        drug_list_load('<?php echo $hn2; ?>','<?php echo date_db2th($vstdate); ?>','<?php echo $vn; ?>','<?php echo $row_s_patient['pdx']; ?>','<?php echo $row_s_patient['dx0']; ?>','<?php echo $row_s_patient['dx1']; ?>','<?php echo $row_s_patient['dx2']; ?>','<?php echo $row_s_patient['dx3']; ?>','<?php echo $row_s_patient['dx4']; ?>','<?php echo $row_s_patient['dx5']; ?>','<?php echo $row_s_patient['age_y']; ?>','<?php echo $row_oapp['date_diff']; ?>');
        
        //load lab
        load_lab_list('<?php echo $hn2; ?>','<?php echo date_db2th($vstdate); ?>','<?php echo $vn1; ?>','<?php echo $row_s_patient['age_y']; ?>','<?php echo $row_s_patient['sex']; ?>');        
        
        
	//------------ คัดลอกประวัติการจ่ายยาล่าสุด -----------//
	$('#recent-doctor').click(function(){
		$('#recent-payment').load('detail_recent_payment.php?action=payment');
	});
	//------------ คัดลอกประวัติการถามแพ้ยาล่าสุด -----------//
	$('#recent-adr').click(function(){
		$('#recent-payment').load('detail_recent_payment.php?action=adr');
	});
    

	//------------ คัดลอกประวัติการจ่ายยาล่าสุด -----------//

		//ถ้าเลือกผู้ตอบคำถาม ADR ให้ไปกรอกในช่องผู้ัรับยาอัตโนมัติ

		$('#respondent').keyup(function(){
			$('#respondent2').val($('#respondent').val());
			$('#respondent_list2').val($('#respondent').val());
		
		});        

		$('#respondent_list').change(function(){
			$('#respondent_list2').val($('#respondent_list').val());
			$('#respondent2').val($('#respondent_list').val());

		});        

		////////////////////////////////////////
		
		$('.fullscreen').hide();
        $('#respondent').focus();
        $("#chart-bw").click(function(){
                $('#modal-body-lab').load('bw_chart.php?graph=bw&hn='+$('#hidden_hn').val(),function(){
                $('.modal-title').html('<i class="fas fa-weight"></i>&nbsp;น้ำหนักผู้ป่วย');
                $('#myModal2').modal({show:true});                
			     });
	
      })

	//ซ่อน operator ไปด้านบน

        $('.operator-long').hide();	
        $('.operator-short').click(function(){
		$('#rx-operator').slideUp(500);
		$('.operator-short').hide();	
		$('.operator-long').show();	
	   });
        
        $('.operator-long').click(function(){
		$('#rx-operator').slideDown(500);
		$('.operator-long').hide();	
		$('.operator-short').show();	
	   });
	//ซ่อน operator ไปด้านบน

	//ซ่อน operator ไปด้านขวา
		$('.operator-panel').hide();
        $('.operator-right').click(function(){
		$('#rx-operator-main').hide(500);
		$('.operator-panel').show();
	   });  

        $('.operator-panel').click(function(){
		$('#rx-operator-main').show(500);
		$('.operator-panel').hide();
	   });  
	//ซ่อน operator ไปด้านขวา
	         
        
    $('#druglast_indicator').hide();
    
    $('#drug-last-vstdate').change(function(){
                        $('#druglast_indicator').show();
                        $('.drug-last-body').load('detail_druglast.php?hn='+$('#hidden_hn').val()+'&vstdate='+$(this).val(),function(responseTxt, statusTxt, xhr){
                            if(statusTxt == "success")
                            $('#druglast_indicator').hide();
                            if(statusTxt == "error")
                            alert("Error: " + xhr.status + ": " + xhr.statusText);    
                        });

    });
	
    ///////////// สิ้นสุด document ////////////////    
        
    })

////========== กดแป้นคีย์บอร์ด F4 ============//
shortcut.add("F4",function() {
        
        $('#caller_indicator').show();
		  alertload('caller_key.php?hn='+$('#hidden_hn').val(),'0','0');
        $('#caller_indicator').hide();
    
        setTimeout(function(){$('#respondent').focus()}, 1000);
        //$('#caller-key').load('caller_key.php?hn='+$('#hidden_hn').val(),function(responseTxt, statusTxt, xhr){
        /*                    if(statusTxt == "success")
                            $('#caller_indicator').hide();
                            if(statusTxt == "error")
                            alert("Error: " + xhr.status + ": " + xhr.statusText);    
                        });
	    */

		
});
shortcut.add("F8",function() {
        
	 $('#modal-body-xl').load('dispen_template.php',function(responseTxt, statusTxt, xhr){
                            if(statusTxt == "success")
								$('#modal-title-xl').html('<i class="fas fa-street-view font20"></i>&ensp;เทมเพลตการบันทึกจ่ายยา');
								$('#myModal-xl').modal('show');
                            if(statusTxt == "error")
                            	alert("Error: " + xhr.status + ": " + xhr.statusText);    
                        });
		
});

    
function firstFocus2(objId){
	$('#'+objId).focus();
	}
function pe_short(){
	$('#pe2').hide();
	$('#pe1').show();
}
function pe_long(){
	$('#pe1').hide();
	$('#pe2').show();
}

function dispen_template(){
	//alert();
	 $('#modal-body-xl').load('dispen_template.php',function(responseTxt, statusTxt, xhr){
                            if(statusTxt == "success")
								$('#modal-title-xl').html('<i class="fas fa-street-view font20"></i>&ensp;เทมเพลตการบันทึกจ่ายยา');
								$('#myModal-xl').modal('show');
                            if(statusTxt == "error")
                            	alert("Error: " + xhr.status + ": " + xhr.statusText);    
                        });
}
function note_template(){
	//alert();
	 $('#modal-body-xl').load('note_template.php',function(responseTxt, statusTxt, xhr){
                            if(statusTxt == "success")
								$('#modal-title-xl').html('<i class="fas fa-street-view font20"></i>&ensp;เทมเพลตโน๊ตจ่ายยา');
								$('#myModal-xl').modal('show');
                            if(statusTxt == "error")
                            	alert("Error: " + xhr.status + ": " + xhr.statusText);    
                        });
}    

</script>
</head>

<body>
<!-- hidden decrare -->
<input type="hidden" name="vn" id="vn" value="<?php echo $_GET['vn']; ?>" />
<!--call indicator-->
<div id="call_indicator" align="center" class="spinner" style="position:absolute; margin-top:60px;">
                <button class="btn btn-secondary" type="button" style="width:200px;" disabled>
  <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
  <span >กำลังเรียก</span>
</button>
</div>
<!--call indicator-->

<div class=" container-fluid">
  <div class="row">
    <div class="col" >
    	<div class="row">
    <div class="col-md-auto" style="padding:5px;">
	<div align="center" class="containerimg mt-2 text-center">
        <?
	//ถ้าใช้ image server
	if($row_setting[43]=="Y"){
		mysql_select_db($database_img, $img);
	}
	else{ //กรณีไม่ใช้ image server
		mysql_select_db($database_hos, $hos);
	}
 	$query_selpic = "select count(*) as cc from patient_image where hn='".$row_s_patient['hn']."' ";
	//ถ้าใช้ image server
	if($row_setting[43]=="Y"){
	$selpic = mysql_query($query_selpic, $img) or die(mysql_error());
	}
	else{ //กรณีไม่ใช้ image server
	$selpic = mysql_query($query_selpic, $hos) or die(mysql_error());
	}		
	$row_selpic = mysql_fetch_assoc($selpic);
	$totalRows_selpic = mysql_num_rows($selpic);
				if($row_selpic['cc']>0){
				//ถ้าใช้ image server
				if($row_setting[43]=="Y"){
					mysql_select_db($database_img, $img);
				}
				else{ //กรณีไม่ใช้ image server
					mysql_select_db($database_hos, $hos);
				}				
				$query = "SELECT image as blob_img FROM patient_image where hn='".$row_s_patient['hn']."' "; 
				//ถ้าใช้ image server
				if($row_setting[43]=="Y"){
				$result = mysql_query($query, $img) or die(mysql_error()); 
				}
				else{ //กรณีไม่ใช้ image server
				$result = mysql_query($query, $hos) or die(mysql_error()); 
				}					
				$result = mysql_query($query, $hos) or die(mysql_error()); 
				$row = mysql_fetch_array($result); 
				$jpg = $row["blob_img"]; 
	mysql_free_result($selpic);
							?>
							<img src="data:image/jpeg;base64,<?php echo base64_encode($jpg); ?> "  width="100" height="120" vlign="middle" border="0" style="border-radius: 8px; border:solid 1px #E3E1E1" class="image"> <?php 
						}
						else {
							echo "<img src=\"images/noimage.png\" width=\"100\" height=\"120\" class=\"image\" />";
							}
							?>
		  <div class="middle ">
			<a href="javascript:valid(0);" onclick="alertload('caller_key.php?hn=<?php echo $hn; ?>','0','0');        setTimeout(function(){$('#respondent').focus()}, 1000);
" target="queue_caller" style="padding:0px; text-decoration: none" ><div class="text">เรียกผู้ป่วย&ensp;<i class="fas fa-headphones-alt font16"></i></div></a>
		  </div>

        </div>
        <div align="center">
<strong>HN:<?php echo $row_s_patient['hn']; ?></strong><input type="hidden" id="hidden_hn" value="<?php echo $hn2; ?>"/><br />
	
	<strong style="font-size:12px">VN:<?php echo $row_s_patient['vn']; ?></strong>

    <div style="margin-bottom: 5px;">
    <buton class="btn btn-primary" style="width:100px; font-size:14px;"><nobr>
  visit Q. <span class="badge badge-light" style="font-size: 16px; padding:2px;"><?php echo $row_s_patient['oqueue']; ?></span></nobr>
</button>
    </div>
            <div><button onclick="openNav()" class="btn btn-success" style="width:100px;"><i class="far fa-clock"></i>&nbsp;ประวัติ&nbsp;</button></div>        

            <div>
            <div id="call_div"></div>
                <iframe id="queue_caller" name="queue_caller" width="125" height="0" style="border:solid 0px"></iframe>
            </div>
        </div>
    </div>
    <div class="col-md-5" style="padding:5px;">
<!-- patient profile -->

  <div id='patient_profile'>
  <div class="card">
  <div class="card-header" style="font-size:18px; padding: 5px;">ข้อมูลทั่วไป<div class="float-right"><span class="btn btn-warning fullscreen" style="width: 38px; text-align: center; padding: 3px; " onClick="detail_load('<?php echo $hn; ?>','<?php echo date_db2th($vstdate); ?>','<?php echo $method; ?>','Y');"><i class="material-icons" style="font-size: 30px;">fullscreen<?php if($full_screen!="Y" ||$full_screen==NULL ){echo "_exit";} ?></i></span></div></div>
	<div class="card-body" style="padding:0px;">
<table width="100%" border="0" cellpadding="3" cellspacing="0" class="grid_font" >
        <tr >
          <td colspan="2" align="left" style="border-bottom: 1px solid #F0EBEB"><div class="ml-1" style="font-size:25px ; font-weight:bolder; text-wrap:none"><nobr><?php echo $row_s_patient['patient_name']; ?></nobr><div id="caller-key"></div>         
        <!--indicator-->
         <div id="caller_indicator" align="center" class="spinner" style=" position: absolute; margin-top: 50px; right: 0px;">
         <button class="btn btn-secondary" type="button" style="" disabled>
  <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
</button>
         </div>
         <!--indicator--></td>
          </tr>
        <tr class="grid4">
          <td width="17%" align="left" >วันที่มา</td>
          <td width="83%" align="left" ><?php echo date_db2th($row_s_patient['vstdate']); ?> &nbsp;เวลา <?php echo $row_s_patient['vsttime']; ?></td>
        </tr>
        <tr class="grid4">
          <td   >อายุ </td>
          <td bgcolor="#FFFFFF" ><?php echo $row_s_patient['age_y']; ?> ปี <?php echo $row_s_patient['age_m']; ?> เดือน</td>
        </tr>
        <tr class="grid4">
          <td    >CID</td>
          <td  ><?php echo $row_s_patient['cid']; ?></td>
        </tr>
        <tr class="grid4">
          <td   >ที่อยู่</td>
          <td  ><?php echo $row_s_patient['thaiaddress']; ?></td>
        </tr>
        <tr class="grid4">
          <td align="left"   >สิทธิ</td>
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
         <!--indicator-->
         <div id="shortcut_indicator" align="center" class="spinner" style="position:absolute; margin-top:230px;">
         <button class="btn btn-secondary" type="button" style="" disabled>
  <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
</button>
         </div>
         <!--indicator-->

        <!-- diag shortcut -->
		
        <div id="shortcut" class=" text-left mt-2">
         </div>
        <!-- label icon -->
		
        <div id="label_icon" class="text-left mt-2">
         </div>

</div>

<!-- patient vital sign -->
    <div class="col" style="padding:5px;">
  <div class="card" >
  <div class="card-header" style="font-size:18px; padding:5px;">ข้อมูลการซักประวัติและการตรวจรักษา</div>
  <div  style="position:absolute; right:2px; margin-top:4px;" class="operator-panel">
       <button type="button" class="btn btn-light btn-sm " style="padding:3px;">หน้าต่างบันทึก&ensp;<i class="fas fa-angle-double-right font20"></i> 
</button></div>
  <div class="card-body grid_font" style="padding:0px;">
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
      <div class="row vital-box" id="pe1" >
          <div class="col-md-1" style="font-weight: bold"><nobr>PE</nobr></div>
          <div class="col"><?php if(strlen($row_screen['pe'])>80){ echo iconv_substr($row_screen['pe'],0,80,"UTF-8")."..."; ?><a href="javascript:void(0);" style="font-weight:bold; font-size:12px;color:#0066CC; text-decoration:none; position:absolute; left:1200px;"  class="tooltip">อ่านเต็ม<span class="tooltiptext"><?php echo $row_screen['pe']; ?></span></a><?php } else{ echo $row_screen['pe'];  } ?>&ensp;<button class="btn btn-light btn-sm font16 cursor" onClick="pe_long();"><i class="fas fa-sort-down"></i></button></div>
      </div>
      <div class="row vital-box" id="pe2" >
          <div class="col-md-1" style="font-weight: bold"><nobr>PE</nobr></div>
          <div class="col"><?php if(strlen($row_screen['pe'])>80){ echo $row_screen['pe']; ?><a href="javascript:void(0);" style="font-weight:bold; font-size:12px;color:#0066CC; text-decoration:none; position:absolute; left:1200px;"  class="tooltip">อ่านเต็ม<span class="tooltiptext"><?php echo $row_screen['pe']; ?></span></a><?php } else{ echo $row_screen['pe'];  } ?>&ensp;<button class="btn btn-light btn-sm font16 cursor" onClick="pe_short();"><i class="fas fa-sort-up"></i></button></div>
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

      <div class="row vital-box" >
          <div class="col-md-2" style="font-weight: bold"><nobr>Rx time</nobr></div>
          <div class="col"><?php echo $row_rx_doctor['rx_time']; ?></div>
      </div>
      </div>
    </div>
    <!-- card -->
    <div class="card mt-2">
  <div class="card-header" style="font-size:14px; padding:5px;">การนัดหมาย</div>
  <div class="card-body grid_font" style="padding:0px;">
		<div class="row">
			<div class="col"></div>
			<div class="col"></div>
			<div class="col"></div>
	  	</div>
        <div class="row vital-box" >
          <div class="col-md-2" style="font-weight: bold"><nobr>มาครั้งที่แล้ว</nobr></div>
          <div class="col"><?php echo dateThai(date_th2db($row_oapp1['vstdate1'])); ?></div>
      </div>

      <div class="row vital-box" >
          <div class="col-md-2" style="font-weight: bold"><nobr>นัดครั้งที่แล้ว</nobr></div>
          <div class="col">
            <div>

                <?php if($row_oapp1['nextdate']!=""){ 
                    if($totalRows_oapp1<>0){ echo dateThai(date_th2db($row_oapp1['nextdate'])); ?>
                    <?php if($totalRows_chronic_diag<>0){ 
                                echo "=".$row_oapp1['date_diff2']." วัน"; 
                            } else 
                            { echo "-"; } ?> 
                <span class="big_red16">&nbsp;<?php  if($row_oapp1['date_diff']>0){ echo "<span class='badge badge-danger font14'>มาก่อนนัด ".$row_oapp1['date_diff']." วัน</span>";} if($row_oapp1['date_diff']==0){echo "<span class='badge badge-success font14'>มาตรงวันนัด</span>";} if($row_oapp1['date_diff']<0){echo "(มาผิดนัด ".str_replace('-','',$row_oapp1['date_diff'])." วัน)";;} 
                    } 
                    else { echo "-"; } ?>
&nbsp; <?php } ?></span>     
            </div>  
         </div>
      </div>

      <div class="row vital-box" >
          <div class="col-md-2" style="font-weight: bold"><nobr>นัดถัดไป</nobr></div>
          <div class="col-md-auto">
            <?php $i=0; do { $i++;?>
              <button class="btn btn-warning p-1 font12" style="">&nbsp;
              <?php if($totalRows_oapp<>0){ echo $row_oapp['nextdate']; ?>&nbsp;<span class="badge badge-danger font12"><?php echo $row_oapp['date_diff']; ?></span></span><?php }else { echo "-"; } ?></button>
             
          <?php }while($row_oapp=mysql_fetch_assoc($oapp)); ?>
          </div>
            
      </div>
      
    </div>
  <!-- card body vital sign -->
  </div>
  <!-- card vital sign -->

    </div>
<!-- patient vital sign -->       
        </div>
<!-- drug row -->
<div class="row" >
    <div class="col" style="padding: 5px;">
    <!-- drug -->
    <div class="row">
        <div class="col">
        <div class="card">
            <h6 class="card-header bg-info text-white"><i class="fas fa-prescription " style="font-size:22px;"></i>&ensp;รายการเวชภัณฑ์ที่ได้รับ</h6>
                <!--indicator-->
                <div id="drug_indicator" align="center" class="spinner" style="position:absolute; margin-top:60px;">
                <button class="btn btn-secondary" type="button" style="width:200px;" disabled>
  <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
  <span >กำลังโหลดรายการยา</span>
</button>
                </div>
            <div class="card-body" id="drug_list" style="padding: 0px;">


            </div>
        </div>
    </div> <!-- col -->
    <div class="col" style=" padding-left: 0px; -ms-flex: 0 0 300px;flex: 0 0 300px;" id="drug-last">        
    <!-- druglast-indicator -->
        <div id="druglast_indicator" align="center" class="spinner text-center" style="position:absolute; margin-top:60px; margin-left: 50% ">
                <button class="btn btn-secondary" type="button" style="" disabled>
  <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
</button>
        </div>    
       <!-- druglast-indicator -->
  
        <div class="card">
            <div class="card-header p-2 bg-success text-white"><div class="row"><div class="col-md-auto"><i class="fas fa-clock font20"></i>&ensp;<span class="card-title font14">รายการยาเดิม</span></div><div class="col"><select class="form-control form-control-sm" id="drug-last-vstdate" style="border: solid 0px #19561C; outline:0px; background-color: #6ED55D; padding-right:0px; width:100px; padding-left:5px;"><option selected="selected">เลือกวันที่ย้อนหลัง</option>        <?php do {  ?>
        <option value="<?php echo $row_rs_visit['vstdate']?>" <?php if($row_rs_visit['an']!=""){ echo "style=\"color:red\""; } ?> <?php if (!(strcmp($row_rs_visit['vstdate'], $_GET['vstdate']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_visit['vstdate1']?></option>
        <?php
} while ($row_rs_visit = mysql_fetch_assoc($rs_visit));
  $rows = mysql_num_rows($rs_visit);
  if($rows > 0) {
      mysql_data_seek($rs_visit, 0);
	  $row_rs_visit = mysql_fetch_assoc($rs_visit);
  }
?></select></div><div class="col-sm-1"><i class="fas fa-times mt-2" style="margin-left: -15px; cursor: pointer" id="drug-last-close"></i></div></div>
            </div>
            <div class="card-body drug-last-body p-0">

            </div>
        </div>
    </div>
</div> <!-- row -->
<!-- DRP -->
<div style="margin-top:10px;" id="drp_list">

</div>
<!-- DRP_old -->
<div style="margin-top:10px;" id="drp_list2">

</div>
<!--/////////////////////////// -->
<!-- ยาค้างจ่าย -->
<div style="margin-top:10px;" id="accrued_list">

</div>
<!--/////////////////////////// -->
<!-- ยาค้างจ่าย -->
<div style="margin-top:10px;" id="counseling_list">

</div>
<!--/////////////////////////// -->
<!-- ปฏิเสธรับยา -->
    <!-- druglast-indicator -->
        <div id="refuse_indicator" align="center" class="spinner text-center" style="position:absolute; margin-top:60px; margin-left: 50% ">
                <button class="btn btn-secondary" type="button" style="" disabled>
  <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
</button>
        </div>    
       <!-- druglast-indicator -->

<div style="margin-top:10px;" id="refuse_list">

</div>
<!--/////////////////////////// -->
    </div>
</div>
<!-- drug row -->

<!-- LAB row -->
<div class="row" >
    <div class="col" style="padding: 5px;">
    	<div class="card">
        	<div class="card-header"><i class="fas fa-flask"></i>&ensp;ค่าทางห้องปฏิบัติการล่าสุด</div>
            <div class="card-body" id="lab-list" style="padding: 0px;">
                <!--indicator-->
                <div id="lab_indicator" align="center" class="spinner" style="position:absolute; margin-top:60px;">
                <button class="btn btn-secondary" type="button" style="width:200px;" disabled>
  <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
  <span >กำลังโหลดรายการ LAB</span>
</button>
                </div>
            
            </div>
        </div>
    </div>
</div>
    </div>
    <div class="col-md-3" style="padding:5px;" id="rx-operator-main">
    <!-- queue -->
<?php if($queue!=""){ ?><div class=" rounded text-center" style="background-color:#FE1D22">
    <div class="row" style="padding-left:20px;">
		<div class="col-sm-auto text-white rounded-left" style="margin-left:-5px; font-size:30px; background-color:#C61A2B">rx-Q<div  style="margin-top:-10px;" class="font20">คิวรับยา</div><?php if($q_express=="E"){ ?><div class=" badge badge-info font20">เร่งด่วน</div><?php } ?></div>    
		<div class="col text-white text-center"  style="margin-top:-30px; margin-bottom:-20px;font-size:100px; right:10px;" ><?php echo $queue; ?></div>     
    </div>
</div><?php } ?>
    
    <!-- save -->
        <div class="card <?php if($queue!=""){ ?>mt-1<?php } ?>" >
            <div class="card-header bg-primary text-white" style="font-size:18px; padding:5px;"><i class="fas fa-user-check"></i>&nbsp;บันทึกจ่ายยา<?php if($totalRows_rs_rx_operator<>0){ echo "<span class=\"font14\">&emsp;<i class=\"fas fa-check-circle\"></i>&nbsp;".substr($row_rs_rx_operator['rx_time'],0,5)."</span>"; }?>
                <div  style="position:absolute; right:10px; margin-top:-30px;">
                    <button type="button" class="btn btn-primary btn-sm operator-right" style="padding:3px;">
 <i class="fas fa-chevron-circle-right"></i> ย่อขวา 
</button>           
                    <button type="button" class="btn btn-primary btn-sm operator-short" style="padding:3px;">
 <i class="fas fa-minus-circle"></i> ย่อขึ้น 
</button><button type="button" class="btn btn-primary btn-sm operator-long"  style="padding:3px;">
 <i class="fas fa-plus-circle"></i> ขยาย </button></div>
                    </div>
          <div class="card-body" id="rx-operator"  style="padding: 0px;">
            <!-- แบบฟอร์ม -->
            <div class="adr-check" style="padding: 5px; background-color:#E9E9E9">
              <div class="form-row">
                <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm font12">ผู้ถูกถาม</label>
                    <div class="col-sm-3">
                      <input name="respondent"  type="text" class="form-control form-control-sm font12" id="respondent" onkeydown="setNextFocus('answer');" onkeyup="respondent_link(this.value,'respondent_list');" onkeypress="return isNumberKey(event);" value="<?php if($totalRows_rs_edit_adr<>0){ echo $row_rs_edit_adr['respondent']; } ?>" />
                    </div>
                <div class="col-sm-6">
                      <select name="respondent_list" class="form-control form-control-sm font12" id="respondent_list"  onchange="doctorcode(this.value,'respondent')" onkeydown="setNextFocus('answer');">
                    <?php
do {  
?>
                    <option value="<?php echo $row_rs_respondent['id']?>"<?php if (!(strcmp($row_rs_respondent['id'], $row_rs_edit_adr['respondent']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_respondent['respondent']?></option>
                    <?php
} while ($row_rs_respondent = mysql_fetch_assoc($rs_respondent));
  $rows = mysql_num_rows($rs_respondent);
  if($rows > 0) {
      mysql_data_seek($rs_respondent, 0);
	  $row_rs_respondent = mysql_fetch_assoc($rs_respondent);
  }
?>
                  </select>
                </div>
              </div>
                
              <div class="form-row" style="margin-top: 5px;">
                <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm font12">คำตอบ</label>
                    <div class="col-sm-3">
                      <input name="answer" type="text" class="form-control form-control-sm font12" id="answer" value="<?php if($totalRows_rs_edit_adr<>0){ echo $row_rs_edit_adr['answer']; } ?>" onkeyup="answer_link(this.value,'answer_list');" onkeydown="setNextFocus('rx_print');" onkeypress="return isNumberKey(event);" />
                    </div>
                <div class="col-sm-6">
                        <select name="answer_list" class="form-control form-control-sm font12" id="answer_list" onchange="doctorcode(this.value,'answer')" onkeydown="setNextFocus('rx_print');" >
                                            <?php
                        do {  
                        ?>
                                            <option value="<?php echo $row_rs_answer['id']?>"<?php if (!(strcmp($row_rs_answer['id'], $row_rs_edit_adr['answer']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_answer['answer']?></option>
                                            <?php
                        } while ($row_rs_answer = mysql_fetch_assoc($rs_answer));
                          $rows = mysql_num_rows($rs_answer);
                          if($rows > 0) {
                              mysql_data_seek($rs_answer, 0);
                              $row_rs_answer = mysql_fetch_assoc($rs_answer);
                          }
                        ?>
                  </select>                    
                </div>
              </div>

                <div class="form-row" style="margin-top: 5px; ">
                 <label for="remark" class="col col-form-label col-form-label-sm font12"></label>
                  <div class="col-9">
                      <input name="remark" type="text" class="form-control form-control-sm font12" id="remark" onkeydown="setNextFocus('rx_print');" value="<?php echo $row_rs_edit_adr['remark']; ?>" placeholder="หมายเหตุ" /></td>
                  </div>
                </div>                
            </div>

            <!-- แบบฟอร์ม -->
            <div class="doctor-check" style="padding: 5px; background-color:; margin-bottom:-5px; ">
              <div class="form-row">
                <label for="rx_print" class="col col-form-label col-form-label-sm font12">ผู้พิมพ์</label>
                <div class="col-3">
                    <input name="rx_print" type="text" class="form-control form-control-sm font12" id="rx_print"  onkeydown="setNextFocus('prepare');" onkeyup="resutName(this.value,'doctorprint');" <?php if($row_setting['35']=="Y"){ ?>onkeypress="return isNumberKey(event);"<?php } ?> value="<?php if(isset($print_staff2)){ echo $row_rs_rx_operator['print_staff'];} else { if($_SESSION['doctor_type']==1){ echo $_SESSION['doctorcode'];} }  ?>" <?php if($_SESSION['doctor_type']==1){if(isset($print_staff2)){ echo "style=\"background-color:#FC0\""; }} ?>  />                      
                </div>  
                  <div class="col-6">
                  <select name="doctorprint" class="form-control form-control-sm font12" id="doctorprint" onchange="doctorcode(this.value,'rx_print')" style="padding-left:2px; padding-right:2px;" onkeydown="setNextFocus('prepare');" >
                    <?php
do {  
?>
                    <option value="<?php echo $row_rs_doctor['doctorcode']?>"<?php if(isset($print_staff2)){ if (!(strcmp($row_rs_doctor['doctorcode'], $row_rs_rx_operator['print_staff']))) {echo "selected=\"selected\"";} } else {  if (!(strcmp($row_rs_doctor['doctorcode'], $_SESSION['doctorcode']))) {echo "selected=\"selected\"";} } ?>><?php echo $row_rs_doctor['name']?></option>
                    <?php
} while ($row_rs_doctor = mysql_fetch_assoc($rs_doctor));
  $rows = mysql_num_rows($rs_doctor);
  if($rows > 0) {
      mysql_data_seek($rs_doctor, 0);
	  $row_rs_doctor = mysql_fetch_assoc($rs_doctor);
  }
?>
                  </select>                  
                  </div>
              </div>

              <div class="form-row" style="margin-top: 5px;">
                <label for="prepare" class="col col-form-label col-form-label-sm font12">ผู้จัด</label>
                <div class="col-3">
                    <input name="prepare" type="text" class="form-control form-control-sm font12"  id="prepare"  onkeydown="setNextFocus('check');" onkeyup="resutName(this.value,'preparedoctor');" value="<?php if(isset($check_staff2)){ echo $row_rs_rx_operator['check_staff']; } else { if($_SESSION['doctor_type']==2){ echo $_SESSION['doctorcode'] ;} } ?>" <?php if($row_setting['35']=="Y"){ ?>onkeypress="return isNumberKey(event);"<?php } ?> <?php if($_SESSION['doctor_type']==2){if(isset($check_staff2)){ echo "style=\"background-color:#FC0\""; }} ?> />
                    
                </div>  
                  <div class="col-6">
                    <select name="preparedoctor" class="form-control form-control-sm font12" id="preparedoctor" onchange="doctorcode(this.value,'prepare')" style="padding-left:2px; padding-right:2px;" onkeydown="setNextFocus('check');" >
                    <?php
do {  
?>
                    <option value="<?php echo $row_rs_doctor['doctorcode']?>"<?php if(isset($check_staff2)){ if (!(strcmp($row_rs_doctor['doctorcode'],$row_rs_rx_operator['check_staff']))) {echo "selected=\"selected\"";}} else {if($_SESSION['doctor_type']==2){if (!(strcmp($row_rs_doctor['doctorcode'], $_SESSION['doctorcode']))) {echo "selected=\"selected\"";}}  } ?>><?php echo $row_rs_doctor['name']?></option>
                    <?php
} while ($row_rs_doctor = mysql_fetch_assoc($rs_doctor));
  $rows = mysql_num_rows($rs_doctor);
  if($rows > 0) {
      mysql_data_seek($rs_doctor, 0);
	  $row_rs_doctor = mysql_fetch_assoc($rs_doctor);
  }
?>
                  </select>
                      
                  </div>
              </div>

              <div class="form-row" style="margin-top: 5px;">
                <label for="check" class="col col-form-label col-form-label-sm font12">ผู้ตรวจสอบ</label>
                <div class="col-3">
                  <input name="check" type="text" class="form-control form-control-sm font12" id="check" onkeydown="setNextFocus('dispen');" onkeyup="resutName(this.value,'checkdoctor')" value="<?php if(isset($confirm_staff2)){ echo $row_rs_rx_operator['confirm_staff']; } else { if($_SESSION['doctor_type']==3){ echo $_SESSION['doctorcode'] ;} } ?>" <?php if($row_setting['35']=="Y"){ ?>onkeypress="return isNumberKey(event);"<?php } ?> <?php if($_SESSION['doctor_type']==3){if(isset($confirm_staff2)){ echo "style=\"background-color:#FC0\""; }} ?> />
                    
                </div>  
                  <div class="col-6">
                    <select name="checkdoctor" class="form-control form-control-sm font12" id="checkdoctor" onchange="doctorcode(this.value,'check')" style="padding-left:2px; padding-right:2px;" onkeydown="setNextFocus('dispen');"  >
                    <?php
do {  
?>
                    <option value="<?php echo $row_rs_doctor['doctorcode']?>"<?php if(isset($confirm_staff2)){ if (!(strcmp($row_rs_doctor['doctorcode'],$row_rs_rx_operator['confirm_staff']))) {echo "selected=\"selected\"";}} else {if($_SESSION['doctor_type']==3){if (!(strcmp($row_rs_doctor['doctorcode'], $_SESSION['doctorcode']))) {echo "selected=\"selected\"";}}  } ?>><?php echo $row_rs_doctor['name']?></option>
                    <?php
} while ($row_rs_doctor = mysql_fetch_assoc($rs_doctor));
  $rows = mysql_num_rows($rs_doctor);
  if($rows > 0) {
      mysql_data_seek($rs_doctor, 0);
	  $row_rs_doctor = mysql_fetch_assoc($rs_doctor);
  }
?>
                  </select>
                      
                  </div>
              </div>

              <div class="form-row" style="margin-top: 5px;">
                <label for="dispen" class="col col-form-label col-form-label-sm font12">ผู้จ่าย</label>
                <div class="col-3">
                  <input name="dispen" type="text" class="form-control form-control-sm font12" id="dispen" onkeydown="setNextFocus('respondent2');" onkeyup="resutName(this.value,'dispendoctor')" value="<?php if(isset($pay_staff2)){ echo $row_rs_rx_operator['pay_staff']; } else { if($_SESSION['doctor_type']==4){ echo $_SESSION['doctorcode'] ;} } ?>" <?php if($row_setting['35']=="Y"){ ?>onkeypress="return isNumberKey(event);"<?php } ?> <?php if($_SESSION['doctor_type']==4){if(isset($pay_staff2)){ echo "style=\"background-color:#FC0\""; }} ?>  />
                    
                </div>  
                  <div class="col-6">
                    <select name="dispendoctor" class="form-control form-control-sm font12" id="dispendoctor" onchange="doctorcode(this.value,'dispen')" style="padding-left:2px; padding-right:2px;" onkeydown="setNextFocus('detail_save');" >
                    <?php
do {  
?>
                    <option value="<?php echo $row_rs_doctor['doctorcode']?>"<?php if(isset($pay_staff2)){ if (!(strcmp($row_rs_doctor['doctorcode'],$row_rs_rx_operator['pay_staff']))) {echo "selected=\"selected\"";}} else {if($_SESSION['doctor_type']==4){if (!(strcmp($row_rs_doctor['doctorcode'], $_SESSION['doctorcode']))) {echo "selected=\"selected\"";}}  } ?>><?php echo $row_rs_doctor['name']?></option>
                    <?php
} while ($row_rs_doctor = mysql_fetch_assoc($rs_doctor));
  $rows = mysql_num_rows($rs_doctor);
  if($rows > 0) {
      mysql_data_seek($rs_doctor, 0);
	  $row_rs_doctor = mysql_fetch_assoc($rs_doctor);
  }
?>
                  </select>
                      
                  </div>
              </div>
            <!-- ค้นหาการจ่ายล่าสุด -->
            <div class="mt-1 bg-gray1 p-1 rounded text-center"><a href="javascript:valid();" id="recent-adr" class="badge badge-success p-1 font14" style="padding-top:5px; padding-bottom:5px;">ถามแพ้ยาล่าสุด</a>&nbsp;<a href="javascript:valid();" id="recent-doctor" class="badge badge-secondary p-1 font14" style="padding-top:5px; padding-bottom:5px;">จ่ายล่าสุด</a>&nbsp;<a href="javascript:dispen_template();" class="badge badge-info p-1 font14" style="padding-top:5px; padding-bottom:5px;"><i class="fas fa-check-square"></i>&nbsp;Template</a></div>
            <div id="recent-payment"></div>
            <!-- ค้นหาการจ่ายล่าสุด -->
            </div>
            <!-- แบบฟอร์ม -->
             <hr class="col-xs-12" style="margin-top: 5px; margin-bottom: 5px;">
        
        <!-- แบบฟอร์ม -->            
        <div class="doctor-check" style="padding: 5px; background-color: ">
            <div class="form-row" >
                <label for="respondent2" class="col col-form-label col-form-label-sm font12">ผู้รับยา</label>
                <div class="col-3">
                  <input name="respondent2"   type="text" class="form-control form-control-sm font12" id="respondent2" onkeydown="setNextFocus('detail_save');" onkeyup="respondent_link(this.value,'respondent_list2');" onkeypress="return isNumberKey(event);" value="<?php echo $row_rs_rx_operator['receiver'];  ?>" />
                    
                </div>  
                  <div class="col-6">
                  <select name="respondent_list2" class="form-control form-control-sm font12" id="respondent_list2"  onchange="doctorcode(this.value,'respondent2')" onkeydown="setNextFocus('answer');" >
                   <option value="">=== ไม่เลือก ===</option>
 
					<?php
do {  
?>
                    <option value="<?php echo $row_rs_respondent['id']?>"<?php if (!(strcmp($row_rs_respondent['id'], $row_rs_rx_operator['receiver']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_respondent['respondent']?></option>
                    <?php
} while ($row_rs_respondent = mysql_fetch_assoc($rs_respondent));
  $rows = mysql_num_rows($rs_respondent);
  if($rows > 0) {
      mysql_data_seek($rs_respondent, 0);
	  $row_rs_respondent = mysql_fetch_assoc($rs_respondent);
  }
?>
                  </select>
                      
                  </div>
            </div>
            <div class="form-row" style="margin-top: 5px;">
              <label for="respondent2_other" class="col col-form-label col-form-label-sm font12"></label>
              <div class="col-9">
                <input name="respondent2_other" type="text" class="form-control form-control-sm font12" id="respondent2_other" placeholder="ระบุ" value="<?php echo $row_rs_rx_operator['receiver_other']; ?>"/>
              </div>            
            </div>
            <div class="form-row" style="margin-top: 5px;">
              <label for="note" class="col col-form-label col-form-label-sm font12">โน้ตจ่ายยา</label><br />
              <div class="col-9"><textarea name="note" class="form-control form-control-sm font12" id="note"><?php echo $row_rs_rx_operator['note']; ?></textarea></div>
            </div>    
			<!--
            <div class="form-row" style="margin-top: 5px;">
              <label for="notime" class="col col-form-label col-form-label-sm font12"></label>
              <div class="col-9 font12"><input name="notime" type="checkbox" id="notime" value="Y" /> กรณีจ่ายยาโดยไม่คิดเวลารอคอย</div>
            </div>
			-->
			<div class="form-row mt-1">
              <label for="notime" class="col col-form-label col-form-label-sm font12"></label>
			  <div class="col-9"><a href="javascript:note_template();" class="badge badge-info p-1 font14" style="padding-top:5px; padding-bottom:5px;"><i class="fas fa-check-square"></i>&nbsp;Note Template</a></div>					
			</div>
            <div class="form-row" style="margin-top: 5px;">
              <label for="cur_dep" class="col col-form-label col-form-label-sm font12">ส่งต่อไปที่</label>
              <div class="col-9"><select name="cur_dep" class="form-control form-control-sm font12" id="cur_dep" >
                  <?php
do {  
?>
                  <option value="<?php echo $row_rs_kskdepart['depcode']?>" <?php if (!(strcmp($row_rs_kskdepart['depcode'], $row_rs_channel['outdepcode']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_kskdepart['department']?></option>
                  <?php
} while ($row_rs_kskdepart = mysql_fetch_assoc($rs_kskdepart));
  $rows = mysql_num_rows($rs_kskdepart);
  if($rows > 0) {
      mysql_data_seek($rs_kskdepart, 0);
	  $row_rs_kskdepart = mysql_fetch_assoc($rs_kskdepart);
  }
?>
                </select>
              </div>
            </div>  

            <div class="form-row" style="margin-top: 5px;">
              <label for="lock" class="col col-form-label col-form-label-sm font12"></label>
              <div class="col-9 font12"><input name="lock" type="checkbox" id="lock" value="Y" <?php if (!(strcmp($row_rs_rx_operator['lock_order'],"Y"))) {echo "checked=\"checked\"";} ?> />
                  lock ใบสั่งยา
              </div>
            </div>              
                
                  <input name="drug_vn" type="hidden" id="drug_vn" value="<?php echo $vn1; ?>" />
                  <input name="depcode1" type="hidden" id="depcode1" value="<? echo $row_rs_channel['kskdepart']; ?>" />
                  <input type="hidden" name="drug_hn" id="drug_hn"  value="<?php echo $row_s_patient['hn']; ?>"/>
                  <input name="user1" type="hidden" id="user1" value="<? echo $user; ?>" />
                  <input name="record_date" type="hidden" id="record_date" value="<?php echo $edate1; ?>" />

            <input type="button" name="detail_save" id="detail_save" value="บันทึก [F9]" onkeydown="setNextFocus('Submit')" onclick="dispen_save('<?php echo $vn1; ?>','<?php echo $row_rs_channel['cursor_position']; ?>');"  class=" btn btn-danger btn-block"/>
            
            <input type="button" name="not_response" id="not_response" value="ผู้ป่วยไม่มารับ" onclick="page_load2('dispen-body','not_response_queue.php?hn=<?php echo $row_s_patient['hn']; ?>&room_id=<?php echo $row_rs_channel['room_id']; ?>')" class="btn btn-secondary btn-block"/>

            </div>
            <!-- แบบฟอร์ม -->
            
            </div>
        </div>    

    </div>

  </div>
<!-- row -->


    
</div>
</body>
</html>
<?php 
mysql_free_result($s_patient);

mysql_free_result($screen);

mysql_free_result($rx_doctor);

mysql_free_result($s_doctor);

mysql_free_result($oapp);

mysql_free_result($oapp1);

mysql_free_result($rs_respondent);

mysql_free_result($rs_answer);

mysql_free_result($rs_edit_adr);

mysql_free_result($rs_doctor);

mysql_free_result($rs_channel);

mysql_free_result($rs_rx_operator);

mysql_free_result($pttyp);

mysql_free_result($rs_visit);

mysql_free_result($s_pdx);

mysql_free_result($rs_kskdepart);
?>