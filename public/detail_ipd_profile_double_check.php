<?php ob_start();?>
<?php session_start();?>
<?php require_once('Connections/hos.php'); ?>
<?php
include('include/function_sql.php');
include('include/function.php');

if($_GET['med_order_number']<>""){ 
mysql_select_db($database_hos, $hos);
$query_rs_qty = "select check_qty from medpay_ipd i left outer join ".$database_kohrx.".kohrx_ipd_profile_check k on k.med_plan_number=i.med_plan_number where i.med_order_number='".$_GET['med_order_number']."' order by k.check_date DESC limit 1 ";
//echo $query_rs_qty;
$rs_qty = mysql_query($query_rs_qty, $hos) or die(mysql_error());
$row_rs_qty = mysql_fetch_assoc($rs_qty);
$totalRows_rs_qty = mysql_num_rows($rs_qty);

    $qty= $row_rs_qty['check_qty'];

mysql_free_result($rs_qty);
}


if(isset($_POST['save_qr'])&&($_POST['save_qr']=="บันทึก")){
if($_POST['qrcode_drug']!=""&&$_POST['qty']!=""){
//หายา med plan
mysql_select_db($database_hos, $hos);
$query_rs_search = "select an,icode,drugusage,sp_use from medpay_ipd where med_order_number ='".$_POST['qrcode_drug']."'";
$rs_search = mysql_query($query_rs_search, $hos) or die(mysql_error());
$row_rs_search = mysql_fetch_assoc($rs_search);
$totalRows_rs_search = mysql_num_rows($rs_search);

if($row_rs_search['sp_use']!=""){
	$sp_use_con=" ";
}
else{
	$sp_use_con=" and drugusage='".$row_rs_search['drugusage']."'";
}
mysql_select_db($database_hos, $hos);
$query_rs_search2 = "select med_plan_number from medplan_ipd where an='".$_POST['an']."' and icode ='".$row_rs_search['icode']."' ".$sp_use_con." and sp_use='".$row_rs_search['sp_use']."' order by orderdate DESC limit 1";
//echo $query_rs_search2;
$rs_search2 = mysql_query($query_rs_search2, $hos) or die(mysql_error());
$row_rs_search2 = mysql_fetch_assoc($rs_search2);
$totalRows_rs_search2 = mysql_num_rows($rs_search2);

	if($totalRows_rs_search2<>0){
    mysql_select_db($database_hos, $hos);
    $query_insert = "insert into ".$database_kohrx.".kohrx_ipd_profile_check (med_plan_number,order_date,check_date,check_qty,check_staff,check_type) value ( '".$row_rs_search2['med_plan_number']."',CURDATE(),NOW(),'".$_POST['qty']."','".$_SESSION['doctorcode']."','2')";
    //echo $query_rs_medplan;
    $insert = mysql_query($query_insert, $hos) or die(mysql_error());
		
		echo "<script>window.location.href='detail_ipd_profile_double_check.php?an=".$_POST['an']."'</script>";
		exit();
	}
	else{
		echo "<script>alert('ไม่พบรายการยานี้ใน plan');</script>";
	}
	mysql_free_result($rs_search);
	mysql_free_result($rs_search2);
	}
}
if(isset($_GET['do'])&&($_GET['do']=="delete")){
    mysql_select_db($database_hos, $hos);
    $query_delete = "delete from ".$database_kohrx.".kohrx_ipd_profile_check where med_plan_number='".$_GET['med_plan_number']."' and check_date='".$_GET['check_date']."' and check_type=2 ";
    $delete = mysql_query($query_delete, $hos) or die(mysql_error());
	
	if($delete){
		echo "<script>window.location.href='detail_ipd_profile_double_check.php?an=".$_GET['an']."'</script>";
		exit();
		
	}
}

if(isset($_POST['an'])&&($_POST['an']!="")){
    $an=$_POST['an'];
}
if(isset($_GET['an'])&&($_GET['an']!="")){
    $an=$_GET['an'];
}
//หายา home med
mysql_select_db($database_hos, $hos);
$query_rs_hm = "select rxdate,order_no from ipt_order_no where an ='".$an."' and order_type='Hme'";
$rs_hm = mysql_query($query_rs_hm, $hos) or die(mysql_error());
$row_rs_hm = mysql_fetch_assoc($rs_hm);
$totalRows_rs_hm = mysql_num_rows($rs_hm);

if($totalRows_rs_hm<>0){
	$order_no=$row_rs_hm['order_no'];
	$rxdate=$row_rs_hm['rxdate'];
}
mysql_free_result($rs_hm);

if($rxdate<>""){
	$datecheck=$rxdate;
	$condition="and ((m1.orderstatus = 'C'))";
}
else{
	$datecheck=date('Y-m-d');
	$condition="and ((m1.orderstatus = 'C' and m1.offdate is null) or (m1.orderstatus='S' and m1.orderdate=CURDATE())  )";
}
//หาข้อมูลยาใน profile
mysql_select_db($database_hos, $hos);
$query_rs_medplan = " select m1.icode,concat(s.name,' ',s.strength,' ',s.units) as name ,substr(m1.orderdate,1,10) as orderdate,d.shortlist,m1.orderstatus,concat('>',sp.name1,sp.name2,sp.name3) as sp_use_name,m1.sp_use,m1.med_plan_number,m1.note,a.age_y,a.age_m,a.hn,iptadm.bedno
from medplan_ipd m1
left outer join an_stat a on a.an=m1.an
left outer join iptadm on iptadm.an=a.an
left outer join s_drugitems s on s.icode=m1.icode   
left outer join drugusage d on d.drugusage=m1.drugusage left outer join sp_use sp on sp.sp_use=m1.sp_use   
where m1.an='".$an."'  and m1.icode like '1%' and m1.orderdate >= DATE_SUB('".$datecheck."', INTERVAL 1 MONTH)  ".$condition."  order by m1.orderstatus,m1.orderdate ";
//echo $query_rs_medplan;
$rs_medplan = mysql_query($query_rs_medplan, $hos) or die(mysql_error());
$row_rs_medplan = mysql_fetch_assoc($rs_medplan);
$totalRows_rs_medplan = mysql_num_rows($rs_medplan);


//หาวันที่ให้ยาทั้งหมด
if($_GET['date']!=""){
	$select_date=" and order_date='".$_GET['date']."'";
}
mysql_select_db($database_hos, $hos);
$query_rs_meddate = "select order_date from medpay_ipd where an ='".$an."' ".$select_date." group by order_date order by order_date DESC limit 1";
$rs_meddate = mysql_query($query_rs_meddate, $hos) or die(mysql_error());
$row_rs_meddate = mysql_fetch_assoc($rs_meddate);
$totalRows_rs_meddate = mysql_num_rows($rs_meddate);

//หาวันที่ให้ยาทั้งหมด
mysql_select_db($database_hos, $hos);
$query_rs_meddate2 = "select order_date from medpay_ipd where an ='".$an."' group by order_date order by order_date DESC";
$rs_meddate2 = mysql_query($query_rs_meddate2, $hos) or die(mysql_error());
$row_rs_meddate2 = mysql_fetch_assoc($rs_meddate2);
$totalRows_rs_meddate2 = mysql_num_rows($rs_meddate2);


if($order_no!=""){
mysql_select_db($database_hos, $hos);
$query_s_drug = "select m1.sp_use,m1.order_no,concat(s.name,' ',s.strength,' ',s.units) as name ,d.shortlist  , mp.med_order_number,m1.icode,concat(s.name,' ',s.strength,' ',s.units) as drugname,concat(sp.name1,sp.name2,sp.name3) as sp_name, m1.qty,mp.med_plan_number,mp.med_real_pay_qty,mp.day_number,substring(m1.icode,1,1) as scode,m1.hn,m1.vn,m1.an,vstdate,a.pdx,a.dx0,a.dx1,a.dx2,a.dx3,a.dx4,a.dx5,p.sex,concat('.',d.code) as code,du.real_use,m1.drugusage,m1.hos_guid,m1.rxdate   
from (select an,order_no,icode,qty,drugusage,hos_guid,sp_use,item_no,hn,vn,vstdate,rxdate from opitemrece where order_no='".$order_no."' and an='".$an."' union select an,order_no,icode,qty,drugusage,hos_guid,sp_use,item_no,hn,vn,vstdate,rxdate from opitemrece_arc where order_no='".$order_no."' and an='".$an."') m1    left outer join s_drugitems s on s.icode=m1.icode   
left outer join drugusage d on d.drugusage=m1.drugusage  
left outer join ".$database_kohrx.".kohrx_drugusage_realuse du on du.drugusage=m1.drugusage
left outer join medpay_ipd mp on mp.hos_guid = m1.hos_guid 
left outer join sp_use sp on sp.sp_use=m1.sp_use 
left outer join an_stat a on a.an=m1.an 
left outer join patient p on p.hn=m1.hn order by item_no";
$s_drug = mysql_query($query_s_drug, $hos) or die(mysql_error());
$row_s_drug = mysql_fetch_assoc($s_drug);
$totalRows_s_drug = mysql_num_rows($s_drug);
$hn=$row_s_drug['hn'];
$vstdate=$row_s_drug['vstdate'];

mysql_select_db($database_hos, $hos);
$query_rs_vn = "select v.vn from (select vn from vn_stat where hn='".$hn."' and vstdate <'".$vstdate."' union select vn from an_stat where hn='".$hn."' and regdate < '".$vstdate."') as v order by v.vn DESC limit 1";
$rs_vn = mysql_query($query_rs_vn, $hos) or die(mysql_error());
$row_rs_vn = mysql_fetch_assoc($rs_vn);
$totalRows_rs_vn = mysql_num_rows($rs_vn);

$vn=$row_rs_vn['vn'];

mysql_free_result($rs_vn);

}
//ผู้บันทึกจ่ายยา
mysql_select_db($database_hos, $hos);
$query_rs_entry = "select i.* from ipt_order_no i where i.order_no='".$order_no."'";
$rs_entry = mysql_query($query_rs_entry, $hos) or die(mysql_error());
$row_rs_entry = mysql_fetch_assoc($rs_entry);
$totalRows_rs_entry = mysql_num_rows($rs_entry);

function username2doctorcode($doctor){
    global $hos;
    mysql_select_db($database_hos, $hos);
    $query_rs_doctor = "SELECT doctorcode from opduser where loginname='".$doctor."'";
    $rs_doctor = mysql_query($query_rs_doctor, $hos) or die(mysql_error());
    $row_rs_doctor = mysql_fetch_assoc($rs_doctor);    
    
    $doctorname= $row_rs_doctor['doctorcode'];
    
    mysql_free_result($rs_doctor);
    return $doctorname;
}

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<?php include('java_css_file.php'); ?>
<?php include('java_function2.php'); ?>	
<script>
function reloadCheck () {
	//alert();
     $('#check_login').load('check_login_expire2.php?page=profile');
}
	
$(document).ready(function(){
<?php if($order_no<>""&&$_GET['date']==""){ ?>
	$('#nav_date').hide();	
<?php } else { ?>	
	$('#nav_date').show();		
<?php } ?>

$('#nav-admit-tab').click(function(){
	$('#nav_date').show();		
});	
$('#nav-dc-tab').click(function(){	
	$('#nav_date').hide();	
});

	
$('[data-toggle="tooltip"]').tooltip()

$('#qrcode_drug').keypress(function(event) {
   if (event.keyCode == '13') {
       event.preventDefault();
   }	
});
//SELECT TEXT RANGE
$.fn.selectRange = function(start, end) {
    return this.each(function() {
        if (this.setSelectionRange) {
            this.focus();
            this.setSelectionRange(start, end);
        } else if (this.createTextRange) {
            var range = this.createTextRange();
            range.collapse(true);
            range.moveEnd('character', end);
            range.moveStart('character', start);
            range.select();
        }
    });
};  	
$.fn.setCursorPosition = function(pos) {
  this.each(function(index, elem) {
    if (elem.setSelectionRange) {
      elem.setSelectionRange(pos, pos);
    } else if (elem.createTextRange) {
      var range = elem.createTextRange();
      range.collapse(true);
      range.moveEnd('character', pos);
      range.moveStart('character', pos);
      range.select();
    }
  });
  return this;
}; 
	
		<?php if($qty!=""){ ?>
		  $('#qty').focus();
          $('#qty').selectRange(0,3);
        <?php } else { ?>
		  $('#qrcode_drug').focus();
        <?php  } ?>
	
	//$('#an').selectRange(5,9);
		reloadCheck();
	    setInterval(reloadCheck, 10000); 

$('#bologna-list a').on('click', function (e) {
  e.preventDefault()
  $(this).tab('show')
})	
	
	$('.overlay').hide();

		
        $('#save').click(function(){
				alert();
                    $("#ipd_save").load('detail_ipd_save.php?action=save&order_no=<?php echo $order_no; ?>&an=<?php echo $_GET['an']; ?>&prepare='+$('#prepare').val()+'&dispen='+$('#dispen').val(), function(responseTxt, statusTxt, xhr){
                    
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();
                    if(statusTxt == "error")
                       alert("Error: " + xhr.status + ": " + xhr.statusText);    
               });

			
        });	
});
    
    function spinnerShow(){
		$('.overlay').show();		
	}
    function setNextFocus(objId){
        if (event.keyCode == 13){
        if(objId!="Submit"){
	        var obj=document.getElementById(objId);
            if (obj){
                obj.focus();
            }
                   
                   var dataString='med_order_number='+$('#qrcode_drug').val();
                   $.ajax({
				   type: "POST",
				   url: "detail_ipd_profile_check_qty.php",
				   cache: false,
				   data: dataString,
				   success: function(html)
					{
                        $('#qty').val(html);
                        $('#qty').selectRange(0,3);
                        
					}
				    });
            
        }
		if(objId=="an"){
			window.location='detail_ipd_profile_double_check.php?an='+$('#an').val();
		}
		if(objId=="qrcode"){
            var qrcode = $('#qrcode_drug').val();
            var res = qrcode.split(",");
			window.location='detail_ipd_profile_double_check.php?an='+res[0]+'&med_order_number='+res[1];
		}            			
		if(objId=="Submit"){
			
			//event
			//alert();
            this.form.submit();
			}
            
		}
	}


</script>
<script>
function alertload(url,w,h){
	 $.colorbox({fixed:true,width:w,height:h, iframe:true, href:url, onOpen : function () {$('html').css('overflowY','hidden');},onCleanup :function(){
$('html').css('overflowY','auto');}
,onClosed:function(){ }});

}    
</script>     
<style>
.overlay {
    position: fixed;
    width: 100%;
    height: 100%;
    z-index: 1000;
    top: 40%;
    left: 0px;
    opacity: 0.5;
    filter: alpha(opacity=50);
 }
	
html,body { height:100%; overflow: hidden;}

::-webkit-scrollbar { width: 15px; }

::-webkit-scrollbar-track {
    box-shadow: inset 0 0 10px 10px #E6E6E6;
    border: solid 3px transparent;
}

::-webkit-scrollbar-thumb {
    box-shadow: inset 0 0 10px 10px #CCCCCC;
    border: solid 3px transparent;
}
</style>
</head>

<body>
    <div class="overlay">
      <div class="d-flex justify-content-center">  
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem; z-index: 20;">
          <span class="sr-only text-secondary">Loading...</span>
        </div>
		  &ensp;กำลังโหลดข้อมูล
      </div>
    </div>
<form method="post" action="detail_ipd_profile_double_check.php">	
	
<nav class="navbar navbar-dark" style="background-color: #002C5F">
  <a class="navbar-brand " href="#"><i class="fas fa-check " style="font-size: 20px;"></i>&nbsp;Drug Profile Double Check</a>
	<div class="row mr-5 text-white">
		
		<label class="col-form-label font-weight-bold text-white">AN&emsp;</label>
		<input type="text" id="an" name="an" class="form-control form-control-sm" style="width: auto; max-width:100px;" onKeyPress="setNextFocus('an');" value="<?php echo $an; ?>" />		
	</div>
	
      <div class="row mr-5"><label class="col-form-label font-weight-bold text-white">Drug Code :&nbsp;</label>
        <input type="text" id="qrcode_drug" name="qrcode_drug" class="form-control form-control-sm" style="width: auto; max-width:100px;" onKeyUp="setNextFocus('qrcode')" <?php if($_GET['med_order_number']<>""){ ?> value="<?php echo $_GET['med_order_number']; ?>" <?php } ?> />
    <label class="col-form-label font-weight-bold text-white">&emsp;จำนวน :&nbsp;</label>
    <input type="text" id="qty" name="qty" class="form-control form-control-sm" style="max-width: 50px; min-width:30px;" onKeyUp="setNextFocus('Submit');" value="<?php if($qty!=""){ echo number_format2($qty); } ?>"  />
    &ensp;<input type="submit" name="save_qr" id="save_qr" class="btn btn-dark btn-sm" style="font-size: 14px; height: 32px;" value="บันทึก" /></div>	     
</nav>
<div class="p-2 text-center" style="background-color:#D7D3D3"><?php echo "<span class='font-weight-bold font18'>". ptname($row_rs_medplan['hn'])."</span>&ensp;HN&nbsp;:&nbsp;".$row_rs_medplan['hn']."&ensp;อายุ&nbsp;:&nbsp;".$row_rs_medplan['age_y']."&nbsp;ปี&emsp;".$row_rs_medplan['age_m']."&nbsp;ปี&emsp;<span class='badge badge-primary font20 p-2'>".$row_rs_medplan['bedno']."</span>"; ?></div>	
<div class="p-2">
<div class="card">
<div class="card-header">	
  <ul class="nav nav-tabs card-header-tabs" id="bologna-list" role="tablist">
	<li class="nav-item">  
    	<a class="nav-link 	<?php if($order_no==""||$_GET['date']!=""){ echo "active"; } ?> font-weight-bold" id="nav-admit-tab" data-toggle="tab" href="#nav-admit" role="tab" aria-controls="nav-admit" aria-selected="true"><i class="fas fa-procedures font20"></i>&nbsp;admit</a>
	</li>
	<?php if($order_no<>""){ ?>  
	<li class="nav-item">  
    	<a class="nav-link <?php if($_GET['date']==""){ echo "active"; } ?> font-weight-bold" id="nav-dc-tab" data-toggle="tab" href="#nav-dc" role="tab" aria-controls="nav-dc" aria-selected="false"><i class="fas fa-house-user font20"></i>ยากลับบ้าน</a>
	</li>
	<?php } ?>  
  </ul>
<div class="position-absolute" id="nav_date" style="right: 40px; margin-top: -35px;">
	<select id="select_date" class="form-control" onchange="if (this.value) window.location.href=this.value">
		<?php do {  ?>
			<option value="detail_ipd_profile_double_check.php?an=<?php echo $_GET['an']; ?>&date=<?php echo $row_rs_meddate2['order_date']; ?>" <?php if($_GET['date']==$row_rs_meddate2['order_date']){ echo "selected";} ?> ><?php echo date_db2th($row_rs_meddate2['order_date']); ?></option>
		<?php }while($row_rs_meddate2 = mysql_fetch_assoc($rs_meddate2)); ?>
	</select>
</div>	
</div>
<div class="card-body">	
<div class="tab-content" id="nav-tabContent">
  <?php //~~~~~~~~~~~~~~~~~~~~~~~~ tab admit ~~~~~~~~~~~~~~~~~~~~~~~~~~// ?>
  <div class="tab-pane fade <?php if($order_no==""||$_GET['date']!=""){ echo "show active"; } ?>" id="nav-admit" role="tabpanel" aria-labelledby="nav-admit-tab">
		<div class="p-3" style="overflow:scroll;overflow-x:hidden;overflow-y:auto; height:75vh; margin-top: 0px">
		<table  class="table_head_small talbe table-striped table-sm table-bordered" cellspacing="0px" style="width: 100%; margin-top: -10px;"  >
			  <tr>
				<td  align="center" bgcolor="#FFFFFF" style="border: 1px solid #FFF; border-bottom:1px solid #E4E4E4">&nbsp;</td>
				<td  align="center" bgcolor="#FFFFFF" class="" style="border: 1px solid #FFF; border-bottom:1px solid #E4E4E4">&nbsp;</td>
				<td  align="center" bgcolor="#FFFFFF" class="" style="border: 1px solid #FFF; border-bottom:1px solid #E4E4E4"><span class="badge badge-info w-100 p-1 font-weight-bold" style="font-size: 18px;">รายการยา</span></td>
				<td  align="center" bgcolor="#FFFFFF"  style="border: 1px solid #FFF; border-bottom:1px solid #E4E4E4"><span class="badge badge-warning w-100 p-1 font-weight-bold" style="font-size: 18px;">วิธีใช้</span></td>
				
				<td  align="center" bgcolor="#FFFFFF" class="table_head_small_bord" style="border: 1px solid #FFF; border-bottom:1px solid #E4E4E4"><span class="badge badge-dark p-1 font-weight-bold" style="font-size: 18px;width: 100%">Rx <?php echo substr($row_rs_meddate['order_date'],8,2)."/".substr($row_rs_meddate['order_date'],5,2); ?></span></td>
				<td  align="center" bgcolor="#FFFFFF" class="table_head_small_bord" style="border: 1px solid #FFF; border-bottom:1px solid #E4E4E4"><span class="badge badge-dark p-1 font-weight-bold" style="font-size: 18px;width: 100%">IPD <?php echo substr($row_rs_meddate['order_date'],8,2)."/".substr($row_rs_meddate['order_date'],5,2); ?></span></td>				
			  </tr>
			  <tbody>
			  <?php $n=0;do { $n++;
			$rs_meddate2 = mysql_query($query_rs_meddate, $hos) or die(mysql_error());
			$row_rs_meddate2 = mysql_fetch_assoc($rs_meddate2);


			?>
			  <tr onmouseover="this.style.backgroundColor='#aaaaaa';" onmouseout="this.style.backgroundColor='';">
					<td align="center" class="bg-white"><?php echo $n; ?></td>
					<td bgcolor="#FFFFFF" class="font18 font-weight-bolder <?php if($row_rs_medplan['orderstatus']!="C"){ echo "text-danger"; } else { echo "text-primary"; } ?>"><?php print $row_rs_medplan['orderstatus']; ?></td>
				<td align="left" style="" class=""><input name="textfield2" type="text" class="table_head_small form-control-plaintext p-0" id="textfield2"  style="width:100%; outline: none" value="<?php echo $row_rs_medplan['name']; ?>" readonly/><?php //echo $row_rs_medplan['med_plan_number']; ?></td>
				<td align="left"  style="" class=""><input name="textfield" type="text" class="table_head_small form-control-plaintext p-0 " id="textfield"  style="width:100%; border:1px #FFFFFF;outline: none; " readonly value="<?php if($row_rs_medplan['sp_use']=="") {echo $row_rs_medplan['shortlist']; } else { echo $row_rs_medplan['sp_use_name']; } ?>"/><?php if($row_rs_medplan['note']!=""){ echo "<div class='text-white rounded bg-danger ' style='font-size:12px; padding:2px;'>** ".$row_rs_medplan['note']."</div>"; } ?></td>
				<? do{ 

	if(DateDiff($row_rs_medplan['orderdate'],$row_rs_meddate2['order_date'])>=0){		
		mysql_select_db($database_hos, $hos);
		$query_rs_order_qty = "select * from ".$database_kohrx.".kohrx_ipd_profile_check where med_plan_number='".$row_rs_medplan['med_plan_number']."' and order_date='".$row_rs_meddate2['order_date']."' and check_type=1 order by check_date DESC";
		//echo $query_rs_order_qty;
		$rs_order_qty = mysql_query($query_rs_order_qty, $hos) or die(mysql_error());
		$row_rs_order_qty = mysql_fetch_assoc($rs_order_qty);
		$totalRows_rs_order_qty = mysql_num_rows($rs_order_qty);
		
		mysql_select_db($database_hos, $hos);
		$query_rs_order_qty2 = "select * from ".$database_kohrx.".kohrx_ipd_profile_check where med_plan_number='".$row_rs_medplan['med_plan_number']."' and order_date='".$row_rs_meddate2['order_date']."' and check_type=2 order by check_date DESC";
		//echo $query_rs_order_qty;
		$rs_order_qty2 = mysql_query($query_rs_order_qty2, $hos) or die(mysql_error());
		$row_rs_order_qty2 = mysql_fetch_assoc($rs_order_qty2);
		$totalRows_rs_order_qty2 = mysql_num_rows($rs_order_qty2);
	}
			?>
				<td  align="center"  style="color:#000000">
					<?php if(DateDiff($row_rs_medplan['orderdate'],$row_rs_meddate2['order_date'])<0){ echo "-"; } else { ?>
						<?php if($totalRows_rs_order_qty<>0 ){ $i=0; do { $i++; ?><div class="btn btn-secondary btn-sm text-left <?php if($i>1){ echo "mt-1"; } ?>" style="font-size: 10px; min-width: 70px; height: 25px; position: relative; margin-top:0px;" data-toggle="tooltip" data-placement="right" title="<?php echo doctorname($row_rs_order_qty['check_staff']); ?>"><span class="badge badge-light font12 position-relative" style="left: -5px; top:-2px;"><?php echo number_format2($row_rs_order_qty['check_qty']); ?></span><div class="position-relative" style="left: 22px; top:-23px;"><?php echo dateThai3(substr($row_rs_order_qty['check_date'],0,10)); ?></div><div class="position-relative" style="left: 20px; top:-28px;"><?php echo substr($row_rs_order_qty['check_date'],10,6); ?></div>
					</div><?php if($totalRows_rs_order_qty>1){ echo "<br>"; } ?><?php } while($row_rs_order_qty = mysql_fetch_assoc($rs_order_qty)); ?><?php } ?>
					<?php } ?>
				  </td>
				  <td align="center">
					  	<?php if(DateDiff($row_rs_medplan['orderdate'],$row_rs_meddate2['order_date'])<0){ echo "-"; } else { ?>
						<?php if($totalRows_rs_order_qty2<>0 ){ $i=0; do { $i++; ?><div class="btn <?php if($row_rs_order_qty2['order_date']!=date('Y-m-d')){ echo "btn-secondary"; } else { if(DateTimeDiff($row_rs_order_qty2['check_date'],date('Y-m-d H:i:s'))<=1){ echo "btn-success";}else{ echo "btn-info";} } ?> btn-sm text-left <?php if($i>1){ echo "mt-1"; } ?>" style="font-size: 10px; min-width: 70px; height: 25px; position: relative; margin-top:0px;" data-toggle="tooltip" data-placement="right" title="<?php echo doctorname($row_rs_order_qty2['check_staff']); ?>"><span class="badge badge-light font12 position-relative" style="left: -5px; top:-2px;"><?php echo number_format2($row_rs_order_qty2['check_qty']); ?></span><div class="position-relative" style="left: 22px; top:-23px;"><?php echo dateThai3(substr($row_rs_order_qty2['check_date'],0,10)); ?></div><div class="position-relative" style="left: 20px; top:-28px;"><?php echo substr($row_rs_order_qty2['check_date'],10,6); ?></div><?php if(DateTimeDiff($row_rs_order_qty2['check_date'],date('Y-m-d H:i:s'))<=1){ ?><div class="font16 text-white text-center rounded-circle bg-dark" style="position: relative; top: -55px; left: 100%; cursor: pointer; width: 21px;height: 21px; padding: 0px;" onClick="if(confirm('ต้องการลบรายการนี้จริงหรือไม่')==true){ window.location.href='detail_ipd_profile_double_check.php?an=<?php echo $an; ?>&med_plan_number=<?php echo $row_rs_order_qty2['med_plan_number']; ?>&check_date=<?php echo $row_rs_order_qty2['check_date']; ?>&an=<?php echo $_GET['an']; ?>&do=delete'; }"><span aria-hidden="true" >&times;</span></div><?php } ?></div><?php if($totalRows_rs_order_qty2>1){ echo "<br>"; } ?><?php } while($row_rs_order_qty2 = mysql_fetch_assoc($rs_order_qty2)); ?><?php } ?>
					<?php } ?>
				  </td>
				<?php } while ($row_rs_meddate2 = mysql_fetch_assoc($rs_meddate2)); ?>
			  </tr>
			  <?php if($hos_guid!=""){  if(DateDiff($row_rs_medplan['orderdate'],$row_rs_meddate2['order_date'])>=0){		
mysql_free_result($rs_order_qty); } }} while ($row_rs_medplan = mysql_fetch_assoc($rs_medplan)); ?>
			  </tbody>
			</table>    
		</div>
  
  </div>
  <?php //~~~~~~~~~~~~~~~~~~~~~~~~ tab admit ~~~~~~~~~~~~~~~~~~~~~~~~~~// ?>
  <?php //~~~~~~~~~~~~~~~~~~~~~~~~ tab d/c ~~~~~~~~~~~~~~~~~~~~~~~~~~// ?>
	<?php if($order_no<>""){ ?>  
  <div class="tab-pane fade <?php if($_GET['date']==""){ echo "show active"; } ?>" id="nav-dc" role="tabpanel" aria-labelledby="nav-dc-tab">
<table border="0" style="width:100%;" class="table table-sm table-striped table-bordered table-hover"  >
      <tr class="text-white font14" style="background-color: #85A8BA">
        <td height="28" align="center" >ลำดับ</td>
        <td align="center" class=""  >ชื่อยา</td>
        <td align="center" class=""  >วิธีใช้</td>
        <td align="center"   >จำนวน </td>
        <td align="center"   >Rx Check</td>
        <td align="center"   >IPD Check</td>
	</tr>
      <?php $i=0; do { $i++;
					  
	mysql_select_db($database_hos, $hos);
	$query_drug_check = "select hos_guid from ".$database_kohrx.".kohrx_ipd_profile_check where hos_guid ='".$row_s_drug['hos_guid']."' and check_type=1";
	$rs_drug_check = mysql_query($query_drug_check, $hos) or die(mysql_error());
	$row_rs_drug_check = mysql_fetch_assoc($rs_drug_check);
	$totalRows_rs_drug_check = mysql_num_rows($rs_drug_check);
	
		if($row_rs_drug_check['hos_guid']!=""){
			$hos_guid=$row_rs_drug_check['hos_guid'];
		}
		else{
			$hos_guid="";
		}

	mysql_select_db($database_hos, $hos);
	$query_drug_check2 = "select hos_guid from ".$database_kohrx.".kohrx_ipd_profile_check where hos_guid ='".$row_s_drug['hos_guid']."' and check_type=2";
	$rs_drug_check2 = mysql_query($query_drug_check2, $hos) or die(mysql_error());
	$row_rs_drug_check2 = mysql_fetch_assoc($rs_drug_check2);
	$totalRows_rs_drug_check2 = mysql_num_rows($rs_drug_check2);
	
		if($row_rs_drug_check2['hos_guid']!=""){
			$hos_guid2=$row_rs_drug_check2['hos_guid'];
		}
		else{
			$hos_guid2="";
		}
					  
					  
	mysql_free_result($rs_drug_check);
	mysql_free_result($rs_drug_check2);
					  



							  
?>
      <tr >
        <td height="27" align="center"  style="border-right: solid 1px #EEE"><?=$i; ?></td>
        <td  class=" font14"  style="border-right: solid 1px #EEE; padding-right:5px"><?php echo $row_s_drug['drugname']; ?></td>
        <td align="left"  style="border-right: solid 1px #EEE;" class="font14" >
         <?php if($row_s_drug['scode']==1){ ?>
			<span style="cursor: pointer" onClick="alertload('item_history.php?hn=<?php echo $row_s_drug['hn']; ?>&amp;icode=<?php echo $row_s_drug['icode']; ?>','800','500')" >
			<?php if($row_s_drug['sp_use']=="") {echo "$row_s_drug[shortlist]"; } else { echo "$row_s_drug[sp_name]"; } ?>
			</span>
<?php } //scode=1 ?>
        </td>
        <td align="center" class="font12"  ><?php echo "$row_s_drug[qty]"; ?></td>
        <td align="center" class="font12"  ><?php if($hos_guid!=""){ ?><i class="fas fa-check-circle text-info" style="font-size: 30px;"></i><?php } ?></td>
		  <td align="center" class="font12"  ><i class="fas fa-check-circle <?php if($hos_guid2!=""){ echo "text-success"; } else { echo "text-secondary"; } ?> cursor" style="font-size: 30px;" onClick="alertload('detail_ipd_profile_check_add.php?order_date=<?php echo $row_s_drug['rxdate']; ?>&med_type=dc&hos_guid=<?php echo $row_s_drug['hos_guid']; ?>&check_type=2','500','300');"></i></td>  
      </tr>
      <?php 
 } while ($row_s_drug = mysql_fetch_assoc($s_drug)); ?>
    </table>
	<div class="rounded p-2 mt-2 text-dark text-center" style="background-color: #BFDAFE" >
              <div class="form-row text-center">
                <label for="prepare" class="col-sm-auto col-form-label col-form-label-sm font12"><strong style="color:#000000">ผู้บันทึก :</strong> <?=doctorname(username2doctorcode($row_rs_entry['entry_staff'])); ?>&nbsp;(<?=substr($row_rs_entry['rxtime'],0,5); ?>)</label>                
  
				 <?php if($row_rs_entry['prepare_staff']!=""){ ?>
<label class="col-sm-auto col-form-label col-form-label-sm font12"><strong style="color:#000000">ผู้จัด :</strong> <?=doctorname($row_rs_entry['prepare_doctor_code']); ?></label>   	
				<?php	} ?>
				  
	

              </div>

	</div>	
	  
	</div>
	<?php } ?>
  <?php //~~~~~~~~~~~~~~~~~~~~~~~~ tab d/c ~~~~~~~~~~~~~~~~~~~~~~~~~~// ?>
	</div>
</div>	
	
</div>
	</div>	
	
	</form>	
</body>
</html>
<?php mysql_free_result($rs_medplan); if($order_no!=""){ mysql_free_result($s_drug); } mysql_free_result($rs_meddate2); ?>