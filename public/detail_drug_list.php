<?php ob_start();?>
<?php session_start();?>
<?php require_once('Connections/hos.php'); ?>
<?php include('include/function.php'); ?>
<?php
//===== setting ==========//
mysql_select_db($database_hos, $hos);
$query_rs_setting = "select * from ".$database_kohrx.".kohrx_dispensing_setting order by id ASC";
$rs_setting = mysql_query($query_rs_setting, $hos) or die(mysql_error());
$row_rs_setting = mysql_fetch_assoc($rs_setting);
$totalRows_rs_setting = mysql_num_rows($rs_setting);

$i=0; do{ $i++;
$row_setting[$i]=$row_rs_setting['value'];} 
while($row_rs_setting = mysql_fetch_assoc($rs_setting));

mysql_free_result($rs_setting);

//===== setting ==========//

if(isset($_GET['vstdate'])){ $vstdate=date_th2db($_GET['vstdate']); }
if(isset($_POST['vstdate'])){ $vstdate=date_th2db($_POST['vstdate']); }
if(isset($_GET['hn'])){ $hn=$_GET['hn']; }
if(isset($_POST['hn'])){ $hn=$_POST['hn']; }

if(isset($_GET['vn'])){ $vn=$_GET['vn']; }
if(isset($_POST['vn'])){ $vn=$_POST['vn']; }

    if(isset($_POST['pdx'])&&($_POST['pdx']!="")){
        $pdx=$_POST['pdx']; 
    }
    if(isset($_GET['pdx'])&&($_GET['pdx']!="")){
        $pdx=$_GET['pdx']; 
    }
    if(isset($_POST['dx0'])&&($_POST['dx0']!="")){
        $dx0=$_POST['dx0']; 
    }
    if(isset($_GET['dx0'])&&($_GET['dx0']!="")){
        $dx0=$_GET['dx0']; 
    }
    if(isset($_POST['dx1'])&&($_POST['dx1']!="")){
        $dx1=$_POST['dx1']; 
    }
    if(isset($_GET['dx1'])&&($_GET['dx1']!="")){
        $dx1=$_GET['dx1']; 
    }
    if(isset($_POST['dx2'])&&($_POST['dx2']!="")){
        $dx2=$_POST['dx2']; 
    }
    if(isset($_GET['dx2'])&&($_GET['dx2']!="")){
        $dx2=$_GET['dx2']; 
    }
    if(isset($_POST['dx3'])&&($_POST['dx3']!="")){
        $dx3=$_POST['dx3']; 
    }
    if(isset($_GET['dx3'])&&($_GET['dx3']!="")){
        $dx3=$_GET['dx3']; 
    }
    if(isset($_POST['dx4'])&&($_POST['dx4']!="")){
        $dx4=$_POST['dx4']; 
    }
    if(isset($_GET['dx4'])&&($_GET['dx4']!="")){
        $dx4=$_GET['dx4']; 
    }
    if(isset($_POST['dx5'])&&($_POST['dx5']!="")){
        $dx5=$_POST['dx5']; 
    }
    if(isset($_GET['dx5'])&&($_GET['dx5']!="")){
        $dx5=$_GET['dx5']; 
    }
    if(isset($_POST['age_y'])&&($_POST['age_y']!="")){
        $age_y=$_POST['age_y']; 
    }
    if(isset($_GET['age_y'])&&($_GET['age_y']!="")){
        $age_y=$_GET['age_y']; 
    }
    if(isset($_POST['date_diff'])&&($_POST['date_diff']!="")){
        $date_diff=$_POST['date_diff']; 
    }
    if(isset($_GET['date_diff'])&&($_GET['date_diff']!="")){
        $date_diff=$_GET['date_diff']; 
    }

//============ แสดงรายการยา ==================//
if($row_setting[28]==2){
mysql_select_db($database_hos, $hos);
$query_s_drug = "select o.rxtime,o.icode,substring(o.icode,1,1) as scode,o.income,concat(s.name,' ',s.strength,' ',s.units) as drugname,s.did,o.qty, d.drugusage,concat('.',d.code) as code,d.name1,d.name2,d.name3,d.shortlist,o.vn,dc.dosage_min,dc.dosage_max,o.vstdate,dc.dose_perunit,d.ccperdose,d.iperday,concat(sp.name1,sp.name2,sp.name3) as sp_name,sp.sp_use,s.pregnancy,s.pregnancy_notify_text,breast_feeding_alert_text,du.real_use,o.hos_guid,kc.icode as qtycheck,dl.icode as monograph,kc.zero_check,dp.image1,dcd.hos_guid as checked,syr.is_error,o.doctor
from opitemrece o 
left outer join sp_use sp on sp.sp_use=o.sp_use 
left outer join drugitems s on s.icode=o.icode
left outer join ".$database_kohrx.".kohrx_drugitems_calculate dc on dc.icode=s.icode
left outer join ".$database_kohrx.".kohrx_drugusage_realuse du on du.drugusage=o.drugusage
left outer join drugusage d on d.drugusage=o.drugusage  
left outer join sp_use u on u.sp_use = o.sp_use
left outer join ".$database_kohrx.".kohrx_drugqty_check kc on kc.icode=o.icode 
left outer join ".$database_kohrx.".kohrx_drug_monograph dl on dl.icode=o.icode
left outer join drugitems_picture dp on dp.icode=o.icode
left outer join ".$database_kohrx.".kohrx_drug_checked dcd on dcd.hos_guid = o.hos_guid
left outer join ".$database_kohrx.".kohrx_syr_dosing_record syr on syr.vn='".$vn."' and s.icode=syr.icode
where o.vn='".$vn."'  and o.icode like '1%'
group by o.hos_guid,o.icode,s.name,s.strength,s.units,o.qty,o.unitprice,d.drugusage,d.code,d.name1,d.name2,d.name3,d.shortlist,u.name1,u.name2,u.name3   order by o.item_no ";
//echo $query_s_drug;
$s_drug = mysql_query($query_s_drug, $hos) or die(mysql_error());
$row_s_drug = mysql_fetch_assoc($s_drug);
$totalRows_s_drug = mysql_num_rows($s_drug);

//echo $query_s_drug;    
}
if($row_setting['28']==1){
mysql_select_db($database_hos, $hos);
$query_s_drug = "select o.hos_guid,o.icode,n.name as nname,s.did,substring(o.icode,1,1) as scode,o.income,concat(s.name,' ',s.strength,' ',s.units) as drugname,s.did,o.qty, o.unitprice ,sum(sum_price) as totprice,o.vstdate ,d.drugusage,concat('.',d.code) as code,d.name1,d.name2,d.name3,d.shortlist,d.iperdose,dt.name as doctor_name  , k.department as dep_name ,u.name1,u.name2,u.name3,i.name as income_name,o.vn,dc.dosage_min,dc.dosage_max,dc.dose_perunit,d.ccperdose,d.iperday ,concat(sp.name1,sp.name2,sp.name3) as sp_name,sp.sp_use,s.pregnancy,s.pregnancy_notify_text,breast_feeding_alert_text,du.real_use,o.rxtime,kc.icode as qtycheck,dl.icode as monograph,kc.zero_check,dp.image1,dcd.hos_guid as checked,syr.is_error,o.doctor
from opitemrece o
left outer join sp_use sp on sp.sp_use=o.sp_use
left outer join drugitems s on s.icode=o.icode
left outer join nondrugitems n on n.icode=o.icode
left outer join ".$database_kohrx.".kohrx_drugitems_calculate dc on dc.icode=s.icode  
left outer join ".$database_kohrx.".kohrx_drugusage_realuse du on du.drugusage=o.drugusage
left outer join drugusage d on d.drugusage=o.drugusage  
left outer join doctor dt on dt.code=o.doctor  
left outer join kskdepartment k on k.depcode=o.dep_code 
left outer join sp_use u on u.sp_use = o.sp_use  left outer join income i on i.income = o.income
left outer join ovst ov on ov.vn=o.vn  
left outer join ".$database_kohrx.".kohrx_drugqty_check kc on kc.icode=o.icode  
left outer join ".$database_kohrx.".kohrx_drug_monograph dl on dl.icode=o.icode  
left outer join drugitems_picture dp on dp.icode=o.icode
left outer join ".$database_kohrx.".kohrx_drug_checked dcd on dcd.hos_guid = o.hos_guid
left outer join ".$database_kohrx.".kohrx_syr_dosing_record syr on syr.vn='".$vn."' and s.icode=syr.icode
where o.vn='".$vn."'
group by o.hos_guid,o.icode,s.name,s.strength,s.units,o.qty,o.unitprice,d.drugusage,d.code,d.name1,d.name2,d.name3,d.shortlist,dt.name,k.department,u.name1,u.name2,u.name3   order by o.item_no";
$s_drug = mysql_query($query_s_drug, $hos) or die(mysql_error());
$row_s_drug = mysql_fetch_assoc($s_drug);
$totalRows_s_drug = mysql_num_rows($s_drug);

}

//============ วันนัด =====================//
mysql_select_db($database_hos, $hos);
$query_oapp = "select nextdate,DATEDIFF(nextdate,'".$row_s_drug['vstdate']."') as date_diff from oapp where vn='".$vn."'";
$oapp = mysql_query($query_oapp, $hos) or die(mysql_error());
$row_oapp = mysql_fetch_assoc($oapp);
$totalRows_oapp = mysql_num_rows($oapp);

$nextdate=$row_oapp['nextdate'];
$date_diff=$row_oapp['date_diff'];


//============ นับจำนวนยาทั้งหมด =============//
mysql_select_db($database_hos, $hos);
$query_rs_drug_all = "select count(*) as count_drug_all from opitemrece where vn='".$vn."' and icode like '1%'";
$rs_drug_all = mysql_query($query_rs_drug_all, $hos) or die(mysql_error());
$row_rs_drug_all = mysql_fetch_assoc($rs_drug_all);
$totalRows_rs_drug_all = mysql_num_rows($rs_drug_all);

$count_drug_all=$row_rs_drug_all['count_drug_all'];

mysql_free_result($rs_drug_all);

mysql_select_db($database_hos, $hos);
$query_rs_drug_check = "select count(*) as count_drug_check from ".$database_kohrx.".kohrx_drug_checked where vn='".$_GET['vn']."'";
$rs_drug_check = mysql_query($query_rs_drug_check, $hos) or die(mysql_error());
$row_rs_drug_check = mysql_fetch_assoc($rs_drug_check);
$totalRows_rs_drug_check = mysql_num_rows($rs_drug_check);

$count_drug_check=$row_rs_drug_check['count_drug_check'];

mysql_free_result($rs_drug_check);

if($hn!=""){
mysql_select_db($database_hos, $hos);
$query_s_patient = "select hn,sex from patient where hn='".$hn."'";
$s_patient = mysql_query($query_s_patient, $hos) or die(mysql_error());
$row_s_patient = mysql_fetch_assoc($s_patient);
$totalRows_s_patient = mysql_num_rows($s_patient);
}
else if($vn!=""){
mysql_select_db($database_hos, $hos);
$query_s_patient = "select p.hn,p.sex,o.doctor from vn_stat v left outer join ovst o on o.vn=v.vn left outer join patient p on p.hn=v.hn where v.vn='".$vn."'";
$s_patient = mysql_query($query_s_patient, $hos) or die(mysql_error());
$row_s_patient = mysql_fetch_assoc($s_patient);
$totalRows_s_patient = mysql_num_rows($s_patient);
}

$hn=$row_s_patient['hn'];

mysql_select_db($database_hos, $hos);
$query_rs_drug_steroid = "select p.icode,concat(d.name,' ',d.strength) as drugname,p.dose from ".$database_kohrx.".kohrx_drug_steroid_haler p left outer join s_drugitems d on d.icode=p.icode ORDER BY name ASC";
$rs_drug_steroid = mysql_query($query_rs_drug_steroid, $hos) or die(mysql_error());
$row_rs_drug_steroid = mysql_fetch_assoc($rs_drug_steroid);
$totalRows_rs_drug_steroid = mysql_num_rows($rs_drug_steroid);

$steroid_array=array();
	do{
	$steroid_array[]=$row_rs_drug_steroid['icode'];
	}while($row_rs_drug_steroid = mysql_fetch_assoc($rs_drug_steroid));

mysql_free_result($rs_drug_steroid);
//echo $totalRows_s_drug;

//ค้นหายาที่ต้องดู adherance
mysql_select_db($database_hos, $hos);
$query_rs_drug_adh = "select p.icode,concat(d.name,' ',d.strength) as drugname from ".$database_kohrx.".kohrx_drug_adherance p left outer join s_drugitems d on d.icode=p.icode ORDER BY name ASC";
$rs_drug_adh = mysql_query($query_rs_drug_adh, $hos) or die(mysql_error());
$row_rs_drug_adh = mysql_fetch_assoc($rs_drug_adh);
$totalRows_rs_drug_adh = mysql_num_rows($rs_drug_adh);

$adh_array=array();
	do{
	$adh_array[]=$row_rs_drug_adh['icode'];
	}while($row_rs_drug_adh = mysql_fetch_assoc($rs_drug_adh));
///////////////////////////////
mysql_free_result($rs_drug_adh);

mysql_select_db($database_hos, $hos);
$query_rs_drug_pulse = "select icode from ".$database_kohrx.".kohrx_drug_pulse ";
$rs_drug_pulse = mysql_query($query_rs_drug_pulse, $hos) or die(mysql_error());
$row_rs_drug_pulse = mysql_fetch_assoc($rs_drug_pulse);
$totalRows_rs_drug_pulse = mysql_num_rows($rs_drug_pulse);

$pulse_array=array();
	do{
	$pulse_array[]=$row_rs_drug_pulse['icode'];
	}while($row_rs_drug_pulse = mysql_fetch_assoc($rs_drug_pulse));

mysql_free_result($rs_drug_pulse);

mysql_select_db($database_hos, $hos);
$query_rs_drug_insulin = "select p.icode,concat(d.name,' ',d.strength) as drugname,p.units from ".$database_kohrx.".kohrx_drug_insulin p left outer join s_drugitems d on d.icode=p.icode ORDER BY name ASC";
$rs_drug_insulin = mysql_query($query_rs_drug_insulin, $hos) or die(mysql_error());
$row_rs_drug_insulin = mysql_fetch_assoc($rs_drug_insulin);
$totalRows_rs_drug_insulin = mysql_num_rows($rs_drug_insulin);

$insulin_array=array();
	do{
	$insulin_array[]=$row_rs_drug_insulin['icode'];
	}while($row_rs_drug_insulin = mysql_fetch_assoc($rs_drug_insulin));
mysql_free_result($rs_drug_insulin);



//+++++++++++++++ vital sign ++++++++++++++++++//
//======= screen ========//
mysql_select_db($database_hos, $hos);
$query_screen = "select bw,pulse,pregnancy,breast_feeding from opdscreen where vn='".$_GET['vn']."'";
$screen = mysql_query($query_screen, $hos) or die(mysql_error());
$row_screen = mysql_fetch_assoc($screen);
$totalRows_screen = mysql_num_rows($screen);

///// ค้นหายาที่แพทย์ off //////
//========== pdx =================//
mysql_select_db($database_hos, $hos);
$query_s_pdx = "select code,name from icd101 where code='".$_GET['pdx']."'";
$s_pdx = mysql_query($query_s_pdx, $hos) or die(mysql_error());
$row_s_pdx = mysql_fetch_assoc($s_pdx);
$totalRows_s_pdx = mysql_num_rows($s_pdx);


if($row_s_patient['sex']==1){
	$f=1;
	$normal='97-137';
	}
if($row_s_patient['sex']==2){
	$f=0.85;
	$normal='88-128';
	}

if($row_setting[45]!=""){
//GFR
mysql_select_db($database_hos, $hos);
$query_rs_gfr = "select  lab_order_result,lab_items_unit,lab_items_normal_value,concat(date_format(order_date,'%d/%m/'),(date_format(order_date,'%Y')+543)) as order_date1,i.range_check_min,i.range_check_max,order_date from lab_order l left join lab_head h on h.lab_order_number=l.lab_order_number left join lab_items i on i.lab_items_code=l.lab_items_code  left join opdscreen os on os.hn=h.hn and os.vstdate=h.order_date left join vn_stat v on v.hn=h.hn and v.vstdate=h.order_date where h.hn='".$hn."' and l.lab_items_code='".$row_setting[45]."' order by order_date DESC limit 1";
$rs_gfr = mysql_query($query_rs_gfr, $hos) or die(mysql_error());
$row_rs_gfr = mysql_fetch_assoc($rs_gfr);
$totalRows_rs_gfr = mysql_num_rows($rs_gfr);
}

//คำนวณ crcl จาก serum cr
mysql_select_db($database_hos, $hos);
$query_rs_cr = "select  format((((140-v.age_y)*os.bw)/(72*lab_order_result))*".$f.",2) as lab_order_result1,lab_order_result,lab_items_unit,lab_items_normal_value,concat(date_format(order_date,'%d/%m/'),(date_format(order_date,'%Y')+543)) as order_date1,i.range_check_min,i.range_check_max,order_date from lab_order l left join lab_head h on h.lab_order_number=l.lab_order_number left join lab_items i on i.lab_items_code=l.lab_items_code  left join opdscreen os on os.hn=h.hn and os.vstdate=h.order_date left join vn_stat v on v.hn=h.hn and v.vstdate=h.order_date where h.hn='".$hn."' and l.lab_items_code='".$row_setting[7]."' order by order_date DESC limit 1";
$rs_cr = mysql_query($query_rs_cr, $hos) or die(mysql_error());
$row_rs_cr = mysql_fetch_assoc($rs_cr);
$totalRows_rs_cr = mysql_num_rows($rs_cr);

	if($row_rs_cr['lab_order_result']!=""&&(is_numeric($row_rs_cr['lab_order_result'])==true)){
	//ถ้าเป็นผู้ชาย
	$xx=$row_rs_cr['lab_order_result'];
	$yy=$_GET['age_y'];
	
	if($row_s_patient['sex']==1){ 
	$zz=1;
	//คำนวณ GFR CKD
	//ถ้า Cr. <=0.9
	$cr_k=$xx/0.9;
	if($xx<=0.9){
	$gfr=141*(pow($cr_k,-0.411))*(pow(0.993,$yy));}
	//ถ้า Cr. >0.9
	if($xx>0.9){
	$gfr=141*(pow($cr_k,-1.209))*(pow(0.993,$yy));}    }
	//ถ้าเป็นผู้หญิง		
	if($row_s_patient['sex']==2){ 
	$zz=0.742;
	//คำนวณ GFR CKD
	//ถ้า Cr. <=0.9
	$cr_k=$xx/0.7;
	if($xx<=0.7){
	$gfr=141*(pow($cr_k,-0.329))*(pow(0.993,$yy));     }
	//ถ้า Cr. >0.9
	if($xx>0.7){
	$gfr=141*(pow($cr_k,-1.209))*(pow(0.993,$yy));} 	}			
	//คำนวณ GFR MDRD
	$mdrd=186*pow($xx,-1.154)*pow($yy,-0.203)*$zz;  	}
	
if($row_rs_gfr['lab_order_result']==""){
	$result_cr=number_format($gfr,2);	
}
else{
	$result_cr=$row_rs_gfr['lab_order_result'];
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php //include('java_css_file.php'); ?>
<script>
$(document).ready(function() {
    $('.drug-long').hide();	
    //load drug off
    drug_off_load('<?php echo $hn; ?>','<?php echo $_GET['pdx']; ?>','<?php echo $vstdate; ?>','<?php echo $vn; ?>');
    //load drug couselling
    counseling_load('<?php echo $hn; ?>');
    //load drug accrued
    accrued_load('<?php echo $hn; ?>');
    // load drug refuse
    refuse_load('<?php echo $hn; ?>','<?php echo $vstdate; ?>');
    // load drp
    drp_load('<?php echo $hn; ?>');
    drp_load2('<?php echo $hn; ?>');
	
    $('.drug-short').click(function(){
		$('#drug-list2').slideUp(500);
		$('.drug-short').hide();	
		$('.drug-long').show();	
	});
    $('.drug-long').click(function(){
		$('#drug-list2').slideDown(500);
		$('.drug-long').hide();	
		$('.drug-short').show();	
	});
    
    $('#druglast_btn').click(function(){
        $('#drug-last').animate({width:'toggle'},350);
        $('#rx-operator-main').animate({width:'toggle'},350);
        $('.operator-panel').animate({width:'toggle'},350);
		
    });

    $('#drug-last-close').click(function(){
        $('#drug-last').animate({width:'toggle'},350);
		$('#rx-operator-main').animate({width:'toggle'},350);
        $('.operator-panel').animate({width:'toggle'},350);

    });

});
function drug_list_reload(){
                	drug_list_load_vn('<?php echo $vn; ?>','<?php echo date_db2th($vstdate); ?>');  
				}

function drug_check_popup(url,w,h){
	 $.colorbox({fixed:true,width:w,height:h, iframe:true, href:url, onOpen : function () {$('html').css('overflowY','hidden');},onCleanup :function(){
$('html').css('overflowY','auto');},/*escKey: false,overlayClose: false,*/onClosed:function(){  drug_list_reload();}});

}

</script>
<style>
	.wrapper{
  float:left;
  width:100%;
}
.navigation{
    float: left;
    width: 100%;
    text-align: center;
}
.navigation ul{
    margin: 0;
    padding: 0;
    float: none;
    width: auto;
    list-style: none;
    display: inline-block;
	padding: 5px 5px 10px 5px;
}
.navigation ul li{
    float: left;
    width: auto;
    
    position: relative;
	padding: 5px 0px 0px 5px;
}
.navigation ul li:last-child{
    margin: 0;
}
.navigation ul li a{
    float: left;
    width: 100%;
    color: #333;
    padding: 5px;
    font-size: 12px;
    line-height: normal;
    text-decoration:none;
    box-sizing:border-box;
    /*text-transform: uppercase;
    font-family: 'Montserrat', sans-serif;
	*/
	-webkit-transition:color 0.3s ease;
    transition:color 0.3s ease;
}
.navigation .children {
    position: absolute;
    top: 100%;
    z-index: 1000;
    margin: 0;
    padding: 0;
    left: 0;
    min-width: 200px;
    background-color: #fff;
    border: solid 1px #dbdbdb;
    opacity: 0;
    -webkit-transform-origin: 0% 0%;
    transform-origin: 0% 0%;
    -webkit-transition: opacity 0.3s, -webkit-transform 0.3s;
    transition: opacity 0.3s, -webkit-transform 0.3s;
    transition: transform 0.3s, opacity 0.3s;
    transition: transform 0.3s, opacity 0.3s, -webkit-transform 0.3s;
}
.navigation ul li .children  {
    -webkit-transform-style: preserve-3d;
    transform-style: preserve-3d;
    -webkit-transform: rotateX(-75deg);
    transform: rotateX(-75deg);
    visibility: hidden;
}
.navigation ul li:hover > .children  {
    -webkit-transform: rotateX(0deg);
    transform: rotateX(0deg);
    opacity: 1;
    visibility: visible;
}
.navigation ul li .children .children{
	left: 100%;
	top: 0;
}
.navigation ul li.last .children{
	right: 0;
	left: auto;
}
.navigation ul li.last .children .children{
	right: 100%;
	left: auto;
}
.navigation ul li .children li{
	float: left;
	width: 100%;
  margin:0;
}
.navigation ul li .children  a {  
    display: block;
    font-size: 11px;
    color: #333;
    text-align: left;
    line-height: 1.5em;
    padding: 5px;
    letter-spacing: normal;
    border-bottom: 1px solid #dbdbdb;
    -webkit-transition: background-color 0.3s ease;
    transition: background-color 0.3s ease;
		border-radius: 10px;
	
}
.navigation ul li .children  a:hover{
	color: #fff;
    background-color:#BCBCBC;
	border-radius: 10px;
	border: 0px;
	
}
.navigation ul li a:hover{
  color:goldenrod;
	
}
</style>
</head>

<body>
<?php if($count_drug_check==$count_drug_all){ ?>
<div style="position: absolute; margin-top: -35px; margin-left: 220px;" class="text-white"><span class="btn-sm text-white"><i class="fas fa-check-double font20"></i>&nbsp;เช็คครบ</span>
</div>
<?php } ?>
<div  style="position:absolute; right:10px; margin-top:-40px;"><button type="button" class="btn btn-info btn-sm drug-short" style="padding:3px;">
 <i class="fas fa-minus-circle"></i> ย่อ 
</button><button type="button" class="btn btn-info btn-sm drug-long"  style="padding:3px;">
 <i class="fas fa-plus-circle"></i> ขยาย 
</button>
<button type="button" class="btn btn-info btn-sm cursor" onclick="drug_list_load_vn('<?php echo $vn; ?>','<?php echo date_db2th($vstdate); ?>');" style="padding:3px;">
 <i class="fas fa-sync-alt"></i> โหลดซ้ำ 
</button>
<button type="button" class="btn btn-info btn-sm " style="padding:3px;" id="druglast_btn"><i class="fas fa-briefcase-medical" style="font-size:16px; color:#FFFFFF"></i>&nbsp;รายการยาย้อนหลัง</button>
</div>
<div id="drug-list2">
<table border="0" align="center" cellpadding="2" cellspacing="0" class="drug-table" style="margin-top:0px; width:100%">
  <?php if ($totalRows_s_drug > 0) { // Show if recordset not empty   ?>
  <form id="form4" name="form4" method="post" action="">
    <thead>
      <tr class="grid_font">
      <th width="7%" height="28" align="center" bgcolor="#D1F5FC" style="border-right: solid 1px #EEE; border-bottom: solid 1px #EEE">ลำดับ</th>
      <th width="30%" align="center" bgcolor="#D1F5FC" style="border-right: solid 1px #EEE; border-bottom: solid 1px #EEE"><span class="context-menu-one"><a name="<?php echo $row_s_drug['icode']; ?>" id="<?php echo $row_s_drug['icode']; ?>"></a></span>ชื่อยา</th>
      <th width="39%" align="center" bgcolor="#D1F5FC" style="border-right: solid 1px #EEE; border-bottom: solid 1px #EEE">วิธีใช้</th>
      <th width="10%" align="center" class="text-center" bgcolor="#D1F5FC" style="border-right: solid 1px #EEE; border-bottom: solid 1px #EEE">จำนวน </th>
      <th width="6%" align="center" bgcolor="#D1F5FC" class="text-center" style="border-right: solid 1px #EEE; border-bottom: solid 1px #EEE">dosage</th>
      <th width="8%" align="center" bgcolor="#D1F5FC" class="text-center" style=" border-bottom: solid 1px #EEE; border-right:0px;">เวลาสั่งยา</th>
    </tr>
    </thead>
    <tbody>
    <?php $i=0; ; do { 
				
		  	mysql_select_db($database_hos, $hos);
			$query_emer_drug2 = "select e.*,d.name from ".$database_kohrx.".kohrx_emergency_drug e left outer join doctor d on d.code=e.doctor where e.vn='".$row_s_drug['vn']."' and e.icode='".$row_s_drug['icode']."' and e.rxtime='".$row_s_drug['rxtime']."'";
			$emer_drug2 = mysql_query($query_emer_drug2, $hos) or die(mysql_error());
			$row_emer_drug2 = mysql_fetch_assoc($emer_drug2);
			$totalRows_emer_drug2 = mysql_num_rows($emer_drug2);

				if($row_s_drug['qty']!=0){$i++;}
  if($bgcolor=="#FFFFFF") { $bgcolor="#F4FAFB"; $font="#FFFFFF"; } else { $bgcolor="#FFFFFF"; $font="#999999";  }

$vn_drug_last="";

if($row_s_drug['scode']=='1'){
mysql_select_db($database_hos, $hos);
$query_s_new = "
SELECT o.vn
from vn_stat v left outer join opitemrece o on v.vn=o.vn 
 WHERE v.hn = '".$hn."' and o.icode='".$row_s_drug['icode']."' and v.vn <'".$vn."' order by o.vn DESC limit 1
";

$s_new = mysql_query($query_s_new, $hos) or die(mysql_error());
$row_s_new = mysql_fetch_assoc($s_new);
$totalRows_s_new = mysql_num_rows($s_new);
//จำนวนครั้งของยาที่เคยได้รับ OPD
$total_count1=$totalRows_s_new;	
//$total_count1=$row_s_new['count1'];
$vn_drug_last_opd=$row_s_new['vn'];

mysql_select_db($database_hos, $hos);
$query_s_new = "
SELECT a.vn
from an_stat a left outer join opitemrece o on a.an=o.an 
 WHERE a.hn = '".$hn."' and o.icode='".$row_s_drug['icode']."' and a.vn < '".$vn."' order by o.vn DESC limit 1
";
$s_new = mysql_query($query_s_new, $hos) or die(mysql_error());
$row_s_new = mysql_fetch_assoc($s_new);
$totalRows_s_new = mysql_num_rows($s_new);
//จำนวนครั้งของยาที่เคยได้รับ IPD
$total_count2=$totalRows_s_new;	
//$total_count2=$row_s_new['count1'];
$vn_drug_last_ipd=$row_s_new['vn'];

if($vn_drug_last_opd>=$vn_drug_last_ipd){
	$vn_drug_last=$vn_drug_last_opd;
	}
else if($vn_drug_last_ipd>=$vn_drug_last_opd){
	$vn_drug_last=$vn_drug_last_ipd;	
	}


//s_use OPD  วิธีการใช้ยาของผู้ป่วยนอก
mysql_select_db($database_hos, $hos);
$query_s_use = "SELECT p.drugusage,u.real_use,v.vn from opitemrece p left outer join vn_stat v on v.vn=p.vn left outer join ".$database_kohrx.".kohrx_drugusage_realuse u on u.drugusage=p.drugusage where  p.icode='".$row_s_drug['icode']."' and v.hn='".$hn."' ";
//$query_s_use.="and p.an is null ";  // ถ้าไม่ตรวจสอบยาตอน admit ด้วย
$query_s_use.="and v.vstdate <'".$vstdate."' order by p.vstdate DESC limit 1";
$s_use = mysql_query($query_s_use, $hos) or die(mysql_error());
$row_s_use = mysql_fetch_assoc($s_use);
$totalRows_s_use = mysql_num_rows($s_use);

$total_use_opd=$totalRows_s_use;
$vn_use_opd=$row_s_use['vn'];
$real_use_opd=$row_s_use['real_use'];
$drugusage_opd=$row_s_use['drugusage'];

//s_use OPD วิธีการใช้ยาผู้ป่วยใน
mysql_select_db($database_hos, $hos);
$query_s_use = "SELECT p.drugusage,u.real_use,v.vn from opitemrece p left outer join an_stat v on v.an=p.an left outer join ".$database_kohrx.".kohrx_drugusage_realuse u on u.drugusage=p.drugusage where  p.icode='".$row_s_drug['icode']."' and v.hn='".$hn."' ";
//$query_s_use.="and p.an is null ";  // ถ้าไม่ตรวจสอบยาตอน admit ด้วย
$query_s_use.="and v.regdate <'".$vstdate."' order by p.vstdate DESC limit 1";
$s_use = mysql_query($query_s_use, $hos) or die(mysql_error());
$row_s_use = mysql_fetch_assoc($s_use);
$totalRows_s_use = mysql_num_rows($s_use);

$total_use_ipd=$totalRows_s_use;
$vn_use_ipd=$row_s_use['vn'];
$real_use_ipd=$row_s_use['real_use'];
$drugusage_ipd=$row_s_use['drugusage'];

if($vn_use_opd>$vn_use_ipd){
	$total_use=$total_use_opd;
	$vn_use=$vn_use_opd;
	$drugusage=$drugusage_opd;
	$real_use=$real_use_opd;
	}
else if($vn_use_opd<$vn_use_ipd){
	$total_use=$total_use_ipd;
	$vn_use=$vn_use_ipd;
	$drugusage=$drugusage_ipd;
	$real_use=$real_use_ipd;
}
//วิธีการใช้ยาสำหรับผู้ที่ใช้ * หรืออื่นๆ
mysql_select_db($database_hos, $hos);
$query_sp_drug = "select name from ".$database_kohrx.".kohrx_drug_spacial d left outer join ".$database_kohrx.".kohrx_spacial_technique s on s.id=d.sp_id where d.icode='".$row_s_drug['icode']."'";
$sp_drug = mysql_query($query_sp_drug, $hos) or die(mysql_error());
$row_sp_drug = mysql_fetch_assoc($sp_drug);
$totalRows_sp_drug = mysql_num_rows($sp_drug);

//ค้นหาการแพ้ยา
mysql_select_db($database_hos, $hos);
$query_s_drug_allergy = "SELECT * from opd_allergy where hn='".$hn."' and  substr(agent_code24,1,19) =substr('".$row_s_drug['did']."',1,19) and agent_code24 !='' ";
$s_drug_allergy = mysql_query($query_s_drug_allergy, $hos) or die(mysql_error());
$row_s_drug_allergy = mysql_fetch_assoc($s_drug_allergy);
$totalRows_s_drug_allergy = mysql_num_rows($s_drug_allergy);

mysql_select_db($database_hos, $hos);
$query_rs_drug_pulse2 = "select * from ".$database_kohrx.".kohrx_drug_pulse where icode='".$row_s_drug['icode']."' ";
$rs_drug_pulse2 = mysql_query($query_rs_drug_pulse2, $hos) or die(mysql_error());
$row_rs_drug_pulse2 = mysql_fetch_assoc($rs_drug_pulse2);

if(in_array($row_s_drug['icode'],$steroid_array,TRUE)){
	mysql_select_db($database_hos, $hos);
	$query_rs_drug_steroid2 = "select k.dose,o.vstdate,DATEDIFF('".$vstdate."',o.vstdate) as date_diff from opitemrece o left outer join ".$database_kohrx.".kohrx_drug_steroid_haler k on k.icode=o.icode where k.icode='".$row_s_drug['icode']."' and hn='".$hn."' and vstdate != '".$vstdate."' order by vstdate DESC limit 1 ";
	//echo $query_rs_drug_steroid2;
	$rs_drug_steroid2 = mysql_query($query_rs_drug_steroid2, $hos) or die(mysql_error());
	$row_rs_drug_steroid2 = mysql_fetch_assoc($rs_drug_steroid2);
	$totalRows_rs_drug_steroid2 = mysql_num_rows($rs_drug_steroid2);
	
	mysql_select_db($database_hos, $hos);
	$query_rs_drug_steroid_use = "select puff_per_day from ".$database_kohrx.".kohrx_steroid_inhale_use where drugusage='".$row_s_drug['drugusage']."'";
	$rs_drug_steroid_use = mysql_query($query_rs_drug_steroid_use, $hos) or die(mysql_error());
	$row_rs_drug_steroid_use = mysql_fetch_assoc($rs_drug_steroid_use);
	$totalRows_rs_drug_steroid_use = mysql_num_rows($rs_drug_steroid_use);
	
	$puff_per_day=$row_rs_drug_steroid_use['puff_per_day'];
	mysql_free_result($rs_drug_steroid2);
	
	$steroid_dose=($puff_per_day*$row_rs_drug_steroid2['date_diff'])-1;
	}

//// ค้นหายา adherance
if(in_array($row_s_drug['icode'],$adh_array,TRUE)){
	mysql_select_db($database_hos, $hos);
	$query_rs_drug_adh2 = "select o.vstdate,DATEDIFF(NOW(),o.vstdate) as date_diff,o.qty from opitemrece o left outer join ".$database_kohrx.".kohrx_drug_adherance k on k.icode=o.icode where k.icode='".$row_s_drug['icode']."' and hn='".$hn."' and vstdate != '".$vstdate."' order by vstdate DESC limit 1 ";
	$rs_drug_adh2 = mysql_query($query_rs_drug_adh2, $hos) or die(mysql_error());
	$row_rs_drug_adh2 = mysql_fetch_assoc($rs_drug_adh2);
	$totalRows_rs_drug_adh2 = mysql_num_rows($rs_drug_adh2);

	$qty_adherance=$row_rs_drug_adh2['qty']-number_format(($row_rs_drug_adh2['date_diff']*$row_s_drug['real_use']),2);
	mysql_free_result($rs_drug_adh2);	
	}

//ASU
mysql_select_db($database_hos, $hos);
$query_asu_icd10 = "select * from ".$database_kohrx.".kohrx_asu_icd10 where code in ('".$row_s_pdx['code']."','".$_GET['dx0']."','".$_GET['dx1']."','".$_GET['dx2']."','".$_GET['dx3']."','".$_GET['dx4']."','".$_GET['dx5']."')";
$rs_asu_icd10 = mysql_query($query_asu_icd10, $hos) or die(mysql_error());
$row_rs_asu_icd10 = mysql_fetch_assoc($rs_asu_icd10);
$totalRows_rs_asu_icd10 = mysql_num_rows($rs_asu_icd10);

if($totalRows_rs_asu_icd10<>0){
mysql_select_db($database_hos, $hos);
$query_asu_drug = "select * from ".$database_kohrx.".kohrx_asu_drug where stdcode=substring('".$row_s_drug['did']."',1,19)";
$rs_asu_drug = mysql_query($query_asu_drug, $hos) or die(mysql_error());
$row_rs_asu_drug = mysql_fetch_assoc($rs_asu_drug);
$totalRows_rs_asu_drug = mysql_num_rows($rs_asu_drug);


//ค้นหาประวัติการบันทึก
mysql_select_db($database_hos, $hos);
$query_asu_record = "select * from ".$database_kohrx.".kohrx_asu_record where vn='".$vn."' and icode='".$row_s_drug['icode']."' and icd10 in ('".$row_s_pdx['code']."','".$_GET['dx0']."','".$_GET['dx1']."','".$_GET['dx2']."','".$_GET['dx3']."','".$_GET['dx4']."','".$_GET['dx5']."')";
$rs_asu_record = mysql_query($query_asu_record, $hos) or die(mysql_error());
$row_rs_asu_record = mysql_fetch_assoc($rs_asu_record);
$totalRows_rs_asu_record = mysql_num_rows($rs_asu_record);

if($totalRows_rs_asu_record==0&&$totalRows_rs_asu_drug<>0){
//ค้นหาแต่ละ diag
//pdx
$asu_dx=array($row_s_pdx['code'],$_GET['dx0'],$_GET['dx1'],$_GET['dx2'],$_GET['dx3'],$_GET['dx4'],$_GET['dx5']);

for($i=0;$i<count($asu_dx); $i++){
	mysql_select_db($database_hos, $hos);
	$query_asu_dx = "select * from ".$database_kohrx.".kohrx_asu_icd10 where code='".$asu_dx[$i]."'";
	$rs_asu_dx = mysql_query($query_asu_dx, $hos) or die(mysql_error());
	$row_rs_asu_dx = mysql_fetch_assoc($rs_asu_dx);
	$totalRows_rs_asu_dx = mysql_num_rows($rs_asu_dx);
	
		if($totalRows_rs_asu_dx<>0){
			mysql_select_db($database_hos, $hos);
			$query_insert = "insert into ".$database_kohrx.".kohrx_asu_record (stdcode,icode,icd10,vn) value (substring('".$row_s_drug['did']."',1,19),'".$row_s_drug['icode']."','".$row_rs_asu_dx['code']."','".$vn."')";
			$insert = mysql_query($query_insert, $hos) or die(mysql_error());
			
	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_asu_record (stdcode,icode,icd10,vn) value (substring(\'".$row_s_drug['did']."\',1,19),\'".$row_s_drug['icode']."\',\'".$row_rs_asu_dx['code']."\',\'".$vn."\')')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

			}
		mysql_free_result($rs_asu_dx);	
		}
}
mysql_free_result($rs_asu_record);
}

//Caustion drug_icd10
mysql_select_db($database_hos, $hos);
$query_drug_icd10 = "select * from ".$database_kohrx.".kohrx_drug_icd10 where  ('".$row_s_pdx['code']."' between icd101 and icd102) or ('".$_GET['dx0']."' between icd101 and icd102) or ('".$_GET['dx1']."' between icd101 and icd102) or ('".$_GET['dx2']."' between icd101 and icd102) or ('".$_GET['dx3']."' between icd101 and icd102) or ('".$_GET['dx4']."' between icd101 and icd102) or ('".$_GET['dx5']."' between icd101 and icd102)";
$rs_drug_icd10 = mysql_query($query_drug_icd10, $hos) or die(mysql_error());
$row_rs_drug_icd10 = mysql_fetch_assoc($rs_drug_icd10);
$totalRows_rs_drug_icd10 = mysql_num_rows($rs_drug_icd10);

if($totalRows_rs_drug_icd10<>0){
mysql_select_db($database_hos, $hos);
$query_icd10_drug = "select * from ".$database_kohrx.".kohrx_drug_icd10 where icode='".$row_s_drug['icode']."'";
$rs_icd10_drug = mysql_query($query_icd10_drug, $hos) or die(mysql_error());
$row_rs_icd10_drug = mysql_fetch_assoc($rs_icd10_drug);
$totalRows_rs_icd10_drug = mysql_num_rows($rs_icd10_drug);
//////////////

//ค้นหาประวัติการบันทึก
mysql_select_db($database_hos, $hos);
$query_drug_icd10_record = "select * from ".$database_kohrx.".kohrx_drug_icd10_record where vn='".$vn."' and icode='".$row_s_drug['icode']."' and icd10 in ('".$row_s_pdx['code']."','".$_GET['dx0']."','".$_GET['dx1']."','".$_GET['dx2']."','".$_GET['dx3']."','".$_GET['dx4']."','".$_GET['dx5']."')";
$rs_drug_icd10_record = mysql_query($query_drug_icd10_record, $hos) or die(mysql_error());
$row_rs_drug_icd10_record = mysql_fetch_assoc($rs_drug_icd10_record);
$totalRows_rs_drug_icd10_record = mysql_num_rows($rs_drug_icd10_record);

if($totalRows_rs_drug_icd10_record==0&&$totalRows_rs_icd10_drug<>0){
$drug_icd10_dx=array($row_s_pdx['code'],$_GET['dx0'],$_GET['dx1'],$_GET['dx2'],$_GET['dx3'],$_GET['dx4'],$_GET['dx5']);

for($i=0;$i<count($drug_icd10_dx); $i++){
	mysql_select_db($database_hos, $hos);
	$query_drug_icd10_dx = "select * from ".$database_kohrx.".kohrx_drug_icd10 where '".$drug_icd10_dx['$i']."' between icd101 and icd102";
	$rs_drug_icd10_dx = mysql_query($query_drug_icd10_dx, $hos) or die(mysql_error());
	$row_rs_drug_icd10_dx = mysql_fetch_assoc($rs_drug_icd10_dx);
	$totalRows_rs_drug_icd10_dx = mysql_num_rows($rs_drug_icd10_dx);
if($totalRows_rs_drug_icd10_dx<>0){
	
	mysql_select_db($database_hos, $hos);
	$query_insert = "insert into ".$database_kohrx.".kohrx_drug_icd10_record (icode,icd10,vn,doctorcode) value ('".$row_s_drug['icode']."','".$drug_icd10_dx['$i']."','".$vn."','".$row_s_patient['doctor']."')";
	$insert = mysql_query($query_insert, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_drug_icd10_record (icode,icd10,vn,doctorcode) value (\'".$row_s_drug['icode']."\',\'".$drug_icd10_dx['$i']."\',\'".$vn."\',\'".$row_s_patient['doctor']."\')')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

}//for
mysql_free_result($rs_drug_icd10_dx);	

	}
	////if($totalRows_rs_drug_icd10_dx<>0){

}//if($totalRows_rs_drug_icd10_record==0){
mysql_free_result($rs_drug_icd10_record);
}
if($result_cr<>0){
//ยาที่มีผลต่อไต
mysql_select_db($database_hos, $hos);
$query_drug_cr = "select * from ".$database_kohrx.".kohrx_drug_creatinine where icode ='".$row_s_drug['icode']."' and '$result_cr' between min_value and max_value and '".$row_rs_cr['lab_order_result']."' between cr_min_value and cr_max_value";
$rs_drug_cr = mysql_query($query_drug_cr, $hos) or die(mysql_error());
$row_rs_drug_cr = mysql_fetch_assoc($rs_drug_cr);
$totalRows_rs_drug_cr = mysql_num_rows($rs_drug_cr);
}
if($totalRows_rs_drug_cr<>0){
	//ค้นหาว่า incedent มีการบันทึกรึยัง
mysql_select_db($database_hos, $hos);
$query_drug_cr1 = "select * from ".$database_kohrx.".kohrx_drug_creatinine_incedent where vn='".$vn."' and icode ='".$row_s_drug['icode']."'";
$rs_drug_cr1 = mysql_query($query_drug_cr1, $hos) or die(mysql_error());
$row_rs_drug_cr1 = mysql_fetch_assoc($rs_drug_cr1);
$totalRows_rs_drug_cr1 = mysql_num_rows($rs_drug_cr1);
		//ถ้าไม่มีให้บันทึก
		if($totalRows_rs_drug_cr1==0){
		mysql_select_db($database_hos, $hos);
		$query_insert = "insert into ".$database_kohrx.".kohrx_drug_creatinine_incedent (vn,hn,icode,crcl,cr,drugusage) value ('".$vn."','".$hn."','".$row_s_drug['icode']."','$result_cr','".$row_rs_cr['lab_order_result']."','".$row_s_drug['drugusage']."')";
		$insert = mysql_query($query_insert, $hos) or die(mysql_error());
		
	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_drug_creatinine_incedent (vn,hn,icode,crcl,cr,drugusage) value (\'".$vn."\',\'".$hn."\',\'".$row_s_drug['icode']."\',\'".$result_cr."\',\'".$row_rs_cr['lab_order_result']."\',\'".$row_s_drug['drugusage']."\')')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

		}
	mysql_free_result($rs_drug_cr1);
	}

//ยาที่ห้ามใช้ในผู้ป่วย warfarin
if($totalRows_warfarin<>0){
	mysql_select_db($database_hos, $hos);
	$query_rs_drug_warfarin = "select * from ".$database_kohrx.".kohrx_drug_warfarin where icode='".$row_s_drug['icode']."' ";
	$rs_drug_warfarin = mysql_query($query_rs_drug_warfarin, $hos) or die(mysql_error());
	$row_rs_drug_warfarin = mysql_fetch_assoc($rs_drug_warfarin);
	$totalRows_drug_warfarin = mysql_num_rows($rs_drug_warfarin);

	if($totalRows_drug_warfarin<>0){
	//ค้นหาการทำรายการ
	mysql_select_db($database_hos, $hos);
	$query_rs_drug_warfarin_search = "select * from ".$database_kohrx.".kohrx_drug_warfarin_record where icode='".$row_s_drug['icode']."' and vn='".$vn."'";
	$rs_drug_warfarin_search = mysql_query($query_rs_drug_warfarin_search, $hos) or die(mysql_error());
	$row_rs_drug_warfarin_search = mysql_fetch_assoc($rs_drug_warfarin_search);
	$totalRows_drug_warfarin_search = mysql_num_rows($rs_drug_warfarin_search);
	
	//ถ้าไม่พบให้บันทึก
	if($totalRows_drug_warfarin_search==0){
	mysql_select_db($database_hos, $hos);
	$query_insert = "insert into ".$database_kohrx.".kohrx_drug_warfarin_record (vn,icode,record_date) value ('".$vn."','".$row_s_drug['icode']."',CURDATE())";
	$insert = mysql_query($query_insert, $hos) or die(mysql_error());
	
	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_drug_warfarin_record (vn,icode,record_date) value (\'".$vn."\',\'".$row_s_drug['icode']."\',CURDATE())')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	}
	//จบการบันทึก
	mysql_free_result($rs_drug_warfarin);

	}
}

//ยาที่ห้ามใช้ในผู้ป่วย g6pd
if($totalRows_g6pd<>0){
	mysql_select_db($database_hos, $hos);
	$query_rs_drug_g6pd = "select * from ".$database_kohrx.".kohrx_drug_g6pd where icode='".$row_s_drug['icode']."' ";
	$rs_drug_g6pd = mysql_query($query_rs_drug_g6pd, $hos) or die(mysql_error());
	$row_rs_drug_g6pd = mysql_fetch_assoc($rs_drug_g6pd);
	$totalRows_drug_g6pd = mysql_num_rows($rs_drug_g6pd);

	if($totalRows_drug_g6pd<>0){
	//ค้นหาการทำรายการ
	mysql_select_db($database_hos, $hos);
	$query_rs_drug_g6pd_search = "select * from ".$database_kohrx.".kohrx_drug_g6pd_record where icode='".$row_s_drug['icode']."' and vn='".$vn."'";
	$rs_drug_g6pd_search = mysql_query($query_rs_drug_g6pd_search, $hos) or die(mysql_error());
	$row_rs_drug_g6pd_search = mysql_fetch_assoc($rs_drug_g6pd_search);
	$totalRows_drug_g6pd_search = mysql_num_rows($rs_drug_g6pd_search);
	
	//ถ้าไม่พบให้บันทึก
	if($totalRows_drug_g6pd_search==0){
	mysql_select_db($database_hos, $hos);
	$query_insert = "insert into ".$database_kohrx.".kohrx_drug_g6pd_record (vn,icode,record_date) value ('".$vn."','".$row_s_drug['icode']."',CURDATE())";
	$insert = mysql_query($query_insert, $hos) or die(mysql_error());
	
	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_drug_g6pd_record (vn,icode,record_date) value (\'".$vn."\',\'".$row_s_drug['icode']."\',CURDATE())')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	
	}
	//จบการบันทึก
	mysql_free_result($rs_drug_g6pd);
	}

}


if($row_s_drug['scode']==1){	
	mysql_select_db($database_hos, $hos);
	$query_rs_drugusage_check = "select * from ".$database_kohrx.".kohrx_drugusage_check where icode='".$row_s_drug['icode']."'";
	$rs_drugusage_check = mysql_query($query_rs_drugusage_check, $hos) or die(mysql_error());
	$row_rs_drugusage_check = mysql_fetch_assoc($rs_drugusage_check);
	$totalRows_drugusage_check = mysql_num_rows($rs_drugusage_check);
	
	if($totalRows_drugusage_check<>0){
	$usage_search=0;	
	$k=0;	

		do{
		$find_usage=strpos($row_s_drug['code'],$row_rs_drugusage_check['code']);
		if($find_usage){
		$usage_search++;		
		}			
		//ถ้าไม่พบ
		if(!$find_usage ){
			if($row_s_drug['sp_name']!=""){
			$usage_search++;
			}	
		}
		
		} while($row_rs_drugusage_check = mysql_fetch_assoc($rs_drugusage_check)); 
		}
	else if($totalRows_drugusage_check==0){
				$usage_search=1; }
	
	//ถ้าพบอุบัติการให้บันทึกอุบัติการณ์
	if($usage_search==0){
	mysql_select_db($database_hos, $hos);
	$query_rs_drugusage_check_search = "select * from ".$database_kohrx.".kohrx_drugusage_check_record where icode='".$row_s_drug['icode']."' and vn='".$vn."' and drugusage=substring('".$row_s_drug['code']."',2)";
	$rs_drugusage_check_search = mysql_query($query_rs_drugusage_check_search, $hos) or die(mysql_error());
	$row_rs_drugusage_check_search = mysql_fetch_assoc($rs_drugusage_check_search);
	$totalRows_drugusage_check_search = mysql_num_rows($rs_drugusage_check_search);
	if($totalRows_drugusage_check_search==0){	
	mysql_select_db($database_hos, $hos);
	$query_rs_drugusage_check_record = "insert into ".$database_kohrx.".kohrx_drugusage_check_record (vn,hn,icode,drugusage,doctorcode) value ('".$vn."','".$hn."','".$row_s_drug['icode']."',substring('".$row_s_drug['code']."',2),'".$row_s_patient['doctor']."')";
	$rs_drugusage_check_record = mysql_query($query_rs_drugusage_check_record, $hos) or die(mysql_error());
		
	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_drugusage_check_record (vn,hn,icode,drugusage,doctorcode) value (\'".$vn."\',\'".$hn."\',\'".$row_s_drug['icode']."\',substring(\'".$row_s_drug['code']."\',2),\'".$row_s_patient['doctor']."\')')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	
		}
	mysql_free_result($rs_drugusage_check_search);	
	}
	//จบการบันทึก
	}
	
}
	//ค้นหาการบันทึกสั่งยาผิดจำนวน
	mysql_select_db($database_hos, $hos);
	$query_rs_drugqty_check_search = "select * from ".$database_kohrx.".kohrx_drugqty_check_record where icode='".$row_s_drug['icode']."' and vn='".$vn."' and drugusage='".$row_s_drug['shortlist']."'";
	$rs_drugqty_check_search = mysql_query($query_rs_drugqty_check_search, $hos) or die(mysql_error());
	$row_rs_drugqty_check_search = mysql_fetch_assoc($rs_drugqty_check_search);
	$totalRows_drugqty_check_search = mysql_num_rows($rs_drugqty_check_search);

	//ค้นหาการใช้ยาที่ควรระวังในผู้สูงอายุ
mysql_select_db($database_hos, $hos);
$query_drug_elder = "select * from ".$database_kohrx.".kohrx_drug_elder_risk where icode ='".$row_s_drug['icode']."' and '".$_GET['age_y']."' between age_range1 and age_range2";
$rs_drug_elder = mysql_query($query_drug_elder, $hos) or die(mysql_error());
$row_rs_drug_elder = mysql_fetch_assoc($rs_drug_elder);
$totalRows_rs_drug_elder = mysql_num_rows($rs_drug_elder);

	if($totalRows_rs_drug_elder<>0){
	//ค้นหาการบันทึกรายงานอุบัติการการสั่งยาในผู้ป่วยสูงอายุ
	mysql_select_db($database_hos, $hos);
	$query_drug_elder1 = "select * from ".$database_kohrx.".kohrx_drug_elder_risk_record where icode ='".$row_s_drug['icode']."' and vn='".$vn."'";
	$rs_drug_elder1 = mysql_query($query_drug_elder1, $hos) or die(mysql_error());
	$row_rs_drug_elder1 = mysql_fetch_assoc($rs_drug_elder1);
	$totalRows_rs_drug_elder1 = mysql_num_rows($rs_drug_elder1);
	//ถ้าไม่พบให้บันทึก
	if($totalRows_rs_drug_elder1==0){
	mysql_select_db($database_hos, $hos);
	$query_insert = "insert into ".$database_kohrx.".kohrx_drug_elder_risk_record (vn,icode,age,doctorcode,daterecord,severity) value ('".$vn."','".$row_s_drug['icode']."','".$_GET['age_y']."','".$row_s_patient['doctor']."',NOW(),'".$row_rs_drug_elder['severity']."')";
	$insert = mysql_query($query_insert, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_drug_elder_risk_record (vn,icode,age,doctorcode,daterecord,severity) value (\'".$vn."\',\'".$row_s_drug['icode']."\',\'".$_GET['age_y']."\',\'".$row_s_patient['doctor']."\',NOW(),\'".$row_rs_drug_elder['severity']."\')')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

	}
mysql_free_result($rs_drug_elder1);
	}

//ค้นหา due drug
	mysql_select_db($database_hos, $hos);
	$query_drug_due = "select * from ".$database_kohrx.".kohrx_due_drug where icode ='".$row_s_drug['icode']."'";
	$rs_drug_due = mysql_query($query_drug_due, $hos) or die(mysql_error());
	$row_rs_drug_due = mysql_fetch_assoc($rs_drug_due);
	$totalRows_rs_drug_due = mysql_num_rows($rs_drug_due);

//ค้นหา HAD
	mysql_select_db($database_hos, $hos);
	$query_drug_had = "select * from ".$database_kohrx.".kohrx_had where icode ='".$row_s_drug['icode']."'";
	$rs_drug_had = mysql_query($query_drug_had, $hos) or die(mysql_error());
	$row_rs_drug_had = mysql_fetch_assoc($rs_drug_had);
	$totalRows_rs_drug_had = mysql_num_rows($rs_drug_had);

	if($totalRows_rs_drug_had<>0){
	//ค้นหาการบันทึก HAD
	mysql_select_db($database_hos, $hos);
	$query_drug_had1 = "select * from ".$database_kohrx.".kohrx_had_record where icode ='".$row_s_drug['icode']."' and vn='".$vn."'";
	$rs_drug_had1 = mysql_query($query_drug_had1, $hos) or die(mysql_error());
	$row_rs_drug_had1 = mysql_fetch_assoc($rs_drug_had1);
	$totalRows_rs_drug_had1 = mysql_num_rows($rs_drug_had1);
	//ถ้าไม่พบให้บันทึก
	if($totalRows_rs_drug_had1==0){
	mysql_select_db($database_hos, $hos);
	$query_insert = "insert into ".$database_kohrx.".kohrx_had_record (vn,icode,doctor) value ('".$vn."','".$row_s_drug['icode']."','".$row_s_patient['doctor']."')";
	$insert = mysql_query($query_insert, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_had_record (vn,icode,doctor) value (\'".$vn."\',\'".$row_s_drug['icode']."\',\'".$row_s_patient['doctor']."\')')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

	}
mysql_free_result($rs_drug_had1);
	}
	
	//ค้นหายา renew
	$renew="";
	mysql_select_db($database_hos, $hos);
	$query_drug_off = "select count(icode) as count1 from ".$database_kohrx.".kohrx_drug_use_change where icode ='".$row_s_drug['icode']."' and hn='".$hn."' and vn > '".$vn_drug_last."' and change_type='off'";
	$rs_drug_off = mysql_query($query_drug_off, $hos) or die(mysql_error());
	$row_rs_drug_off = mysql_fetch_assoc($rs_drug_off);
	$totalRows_rs_drug_off = mysql_num_rows($rs_drug_off);
	
	if($row_rs_drug_off['count1']>0){
	//ถ้าล่าสุดมีการ off ให้ show สัญลักษณ์ renew
		$renew="Y";
		}
	mysql_free_result($rs_drug_off);
?>
    <tr class="grid5" id="row<?php echo $i; ?>"  bgcolor="<?php echo $bgcolor; ?>">
      <td height="27" align="center" style="border-right: solid 1px #EEE;"><?php if($row_s_drug['qty']!=0){ echo $i; } ?>&nbsp;<?php echo ifnotempty($row_s_drug['checked'],"<i class=\"fas fa-check text-success\"></i>"); ?></td>
      <td class="table_head_small" style="border-right: solid 1px #EEE;color: black; text-decoration: none" id="<?php echo $row_s_drug['icode'].$row_s_drug['item_no']; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	            <div class="dropdown-menu" style="font-size:14px; margin-top: -5px;" aria-labelledby="<?php echo $row_s_drug['icode'].$row_s_drug['item_no']; ?>">
            <a class="dropdown-item" href="" onClick="alertload('include/autocomplete/drugedit.php?icode=<?php echo $row_s_drug['icode']; ?>&vn=<?php echo $vn; ?>&qty=<?php echo $row_s_drug['qty']; ?>&hos_guid=<?php echo $row_s_drug['hos_guid']; ?>&hn=<?php echo $hn; ?>&vn=<?php echo $vn; ?>&vstdate=<?php echo $_GET['vstdate']; ?>','60%','60%');"><i class="fas fa-edit"></i>&nbsp;แก้ไขข้อมูล</a>
            <a class="dropdown-item" href="#" onClick="alertload('form_couselling.php?icode=<?php echo $row_s_drug['icode']; ?>&hn=<?php echo $hn; ?>&vstdate=<?php echo $vstdate; ?>','90%','90%')"><i class="fas fa-comment-medical"></i>&nbsp;บันทึกให้คำปรึกษา</a>
            <a class="dropdown-item" href="#" onClick="alertload('payable.php?icode=<?php echo $row_s_drug['icode']; ?>&vn=<?php echo $vn; ?>','60%','500');"><i class="fas fa-file-download"></i>&nbsp;บันทึกค้างจ่าย</a>
            <a class="dropdown-item" href="#" onClick="alertload('drug_refuse.php?icode=<?php echo $row_s_drug['icode']; ?>&vn=<?php echo $vn; ?>&hos_guid=<?php echo $row_s_drug['hos_guid']; ?>&hn=<?php echo $hn; ?>&qty=<?php echo $row_s_drug['qty']; ?>&vstdate=<?php echo $vstdate; ?>','60%','300');"><i class="fas fa-recycle"></i>&nbsp;บันทึกปฏิเสธรับยา</a>

<?php if($row_s_drug['monograph']!=""){ ?>
            <a class="dropdown-item" href="#" onClick="alertload('detail_drug_monograph.php?icode=<?php echo $row_s_drug['icode']; ?>','90%','90%')"><i class="fas fa-bookmark"></i>&nbsp;DRUG monograph</a>
<?php } ?>
<?php if($row_s_drug['image1']!=""){ ?>
            <a class="dropdown-item" href="#" onClick="alertload('detail_drug_picture.php?icode=<?php echo $row_s_drug['icode']; ?>','800','90%')"><i class="fas fa-image"></i>&nbsp;Drug Picture</a>
<?php } ?>
          </div>

	  <?php if($row_s_drug['qty']!=0){ ?>
        <a href="#" style="text-decoration:none; color:#000000" class="<?php if(($row_s_drug['qty']!=0) &&in_array($row_s_drug['icode'],$pulse_array,TRUE)&&($row_screen['pulse']<=$row_rs_drug_pulse2['pulse_low']||$row_screen['pulse']>=$row_rs_drug_pulse2['pulse_hight'])&&($_GET['age_y']>=$row_rs_drug_pulse2['age'])){ echo "small_red_bord";
		//ค้นหาก่อนบันทึก		
		mysql_select_db($database_hos, $hos);
		$query_drug_pulse = "select * from ".$database_kohrx.".kohrx_drug_pulse_record where icode ='".$row_s_drug['icode']."' and vn='".$vn."' and pulse ='".$row_screen['pulse']."'";
		$rs_drug_pulse = mysql_query($query_drug_pulse, $hos) or die(mysql_error());
		$row_rs_drug_pulse = mysql_fetch_assoc($rs_drug_pulse);
		$totalRows_rs_drug_pulse = mysql_num_rows($rs_drug_pulse);
		
		if($totalRows_rs_drug_pulse==0){
		//บันทึกลงในฐานข้อมูล
		mysql_select_db($database_hos, $hos);
		$query_insert = "insert into ".$database_kohrx.".kohrx_drug_pulse_record (vn,hn,icode,pulse,age) value ('".$vn."','".$hn."','".$row_s_drug['icode']."','".$row_screen['pulse']."','".$_GET['age_y']."')";
		$insert = mysql_query($query_insert, $hos) or die(mysql_error());
		
	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_drug_pulse_record (vn,hn,icode,pulse,age) value (\'".$vn."\',\'".$hn."\',\'".$row_s_drug['icode']."\',\'".$row_screen['pulse']."\',\'".$_GET['age_y']."\')')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	
		}
		mysql_free_result($rs_drug_pulse);
				   }  else { echo "table_head_small"; } ?>">
        <? } ?>
        <span style="text-decoration:none; color: black; text-decoration-line: none" ><?php if($row_s_drug['drugname']!=""){ echo $row_s_drug['drugname']; } else { echo $row_s_drug['nname'];}  ?></span>
        <?php if($row_s_drug['qty']!=0){ echo "</a>"; } ?>
        <?php if(($row_s_drug['qty']!=0) &&in_array($row_s_drug['icode'],$pulse_array,TRUE)&&($row_screen['pulse']<=$row_rs_drug_pulse2['pulse_low']||$row_screen['pulse']>=$row_rs_drug_pulse2['pulse_hight'])&&($_GET['age_y']>=$row_rs_drug_pulse2['age'])){  echo "<img src=\"images/exclamation_mark.gif\" width=\"20\" height=\"20\" align=\"absmiddle\"/>"; } ?></td>
      <td align="left" style="border-right: solid 1px #EEE"><a href="#" style="text-decoration:none; color:#000000" class="<?php if($usage_search==0){ echo "small_red_bord"; } else if($usage_search!=0){ echo "table_head_small"; } else if($row_s_drug['qty']==0){ echo "_gray"; } ?>"  onClick="alertload('item_history.php?hn=<?php echo $hn; ?>&amp;icode=<?php echo $row_s_drug['icode']; ?>','800','500')">
        <?php if($row_s_drug['sp_use']=="") {echo $row_s_drug['shortlist']; } else { echo $row_s_drug['sp_name']; } ?>
        </a>
        <?php if((($total_count1+$total_count2)==0)&&($row_s_drug['scode']=='1')){ echo "<span class=\"badge badge-danger align-middle text-white font15\" style=\"\">NEW</span>"; 
	///ค้นหาการบันทึกใน kohrx_drug_use_change ///////
	mysql_select_db($database_hos, $hos);
	$query_rs_drug_check = "select * from ".$database_kohrx.".kohrx_drug_use_change where vn='".$vn."' and hn='".$hn."' and icode='".$row_s_drug['icode']."' and drugusage='".$row_s_drug['drugusage']."' and change_type='new'";
	$rs_drug_check = mysql_query($query_rs_drug_check, $hos) or die(mysql_error());
	$row_rs_drug_check = mysql_fetch_assoc($rs_drug_check);
	$totalRows_drug_check = mysql_num_rows($rs_drug_check);

	//////// บันทึกใน kohrx_drug_use_change /////////////

	if($totalRows_drug_check==0){
	mysql_select_db($database_hos, $hos);
	$query_insert = "insert into ".$database_kohrx.".kohrx_drug_use_change (vn,hn,icode,drugusage,doctor,change_type) value ('".$vn."','".$hn."','".$row_s_drug['icode']."','".$row_s_drug['drugusage']."','".$row_s_patient['doctor']."','new') ";
	$insert = mysql_query($query_insert, $hos) or die(mysql_error());
	
	//update replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_drug_use_change (vn,hn,icode,drugusage,doctor,change_type) value (\'".$vn."\',\'".$hn."\',\'".$row_s_drug['icode']."\',\'".$row_s_drug['drugusage']."\',\'".$row_s_patient['doctor']."\',\'new\')')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	}
	mysql_free_result($rs_drug_check);
	}///// finish ///////

	////ตรวจสอบวิธีการกินยาเหมือนเดิมหรือไม่
	if($real_use!=NULL){ 
	$change_use="";
	if((($total_count1+$total_count2)>0)and($total_use<>0)&&($row_s_drug['scode']=='1')){
		if($row_s_drug['real_use']>$real_use and ($row_s_drug['real_use']!= NULL || $real_use!=NULL)){ echo "<i class=\"fas fa-chevron-circle-up p-1 text-success align-middle\" style=\"font-size:25px\"></i>";	$change_use="Y"; $change_type="up";}
	if($row_s_drug['real_use']<$real_use){ echo "<i class=\"fas fa-chevron-circle-down align-middle p-1\" style=\"font-size:25px; color:orange\"></i>"; $change_use="Y"; $change_type="down";}
		}
	if(($row_s_drug['real_use']==$real_use)and($row_s_drug['drugusage']<>$drugusage) and (($total_count1+$total_count2)>0)){
echo "<i class=\"fas fa-random font20 p-1\" style=\"color:#FF8C00\"></i>"; 	$change_use="Y"; $change_type="change";
		}
	}
		
if($row_s_drug['real_use']==NULL || $row_s_use['real_use']==NULL){ 
 $change_use=""; 
	 if((($total_count1+$total_count2)>0)and($totalRows_s_use>0)and($row_s_drug['drugusage']<>$row_s_use['drugusage'])&&($row_s_drug['scode']=='1')&&($row_s_drug['real_use']==$row_s_use['real_use'])){ echo "<i class=\"fas fa-random font20 p-1\" style=\"color:#FF8C00\"></i>"; 	$change_use="Y"; $change_type="change"; }
 }

  if($change_use=="Y"){
	///ค้นหาการบันทึกใน kohrx_drug_use_change ///////
	mysql_select_db($database_hos, $hos);
	$query_rs_drug_check = "select * from ".$database_kohrx.".kohrx_drug_use_change where vn='".$vn."' and hn='".$hn."' and icode='".$row_s_drug['icode']."' and drugusage='".$row_s_drug['drugusage']."' ";
	$rs_drug_check = mysql_query($query_rs_drug_check, $hos) or die(mysql_error());
	$row_rs_drug_check = mysql_fetch_assoc($rs_drug_check);
	$totalRows_drug_check = mysql_num_rows($rs_drug_check);

	//////// บันทึกใน kohrx_drug_use_change /////////////

	if($totalRows_drug_check==0){
	mysql_select_db($database_hos, $hos);
	$query_insert = "insert into ".$database_kohrx.".kohrx_drug_use_change (vn,hn,icode,drugusage,doctor,change_type) value ('".$vn."','".$hn."','".$row_s_drug['icode']."','".$row_s_drug['drugusage']."','".$row_s_patient['doctor']."','$change_type') ";
	$insert = mysql_query($query_insert, $hos) or die(mysql_error());
	
	//update replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_drug_use_change (vn,hn,icode,drugusage,doctor,change_type) value (\'".$vn."\',\'".$hn."\',\'".$row_s_drug['icode']."\',\'".$row_s_drug['drugusage']."\',\'".$row_s_patient['doctor']."\',\'".$change_type."\')')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	}
	mysql_free_result($rs_drug_check);
	}///// finish ///////
	 //ตรวจสอบ pregnancy
	 if ($row_screen['pregnancy']=="Y" && $row_s_drug['pregnancy_notify_text']!=""){ echo "<a href=\"javascript:alertload('pregnancy.php?icode=".$row_s_drug['icode']."','50%','80%','');\"><img src=\"images/preg2.gif\" width=\"22\" height=\"22\" border=\"0\" align=\"absmiddle\" /></a>"; } if ($row_screen['breast_feeding']=="Y" && $row_s_drug['breast_feeding_alert_text']!=""){ echo "<a href=\"javascript:valid();\" onclick=\"alertload('lactation.php?icode=".$row_s_drug['icode']."','50%','80%','');\"><img src=\"images/lac.gif\" width=\"22\" height=\"22\" border=\"0\" align=\"absmiddle\" /></a>";} if($totalRows_sp_drug<>0){echo "<a href=\"document/".$row_sp_drug['name']."\" class=\" align-middle ml-1\" target=\"_blank\"><i class=\"fas fa-file-alt text-primary\" style=\"font-size:25px;\"></i></a>";}
if($totalRows_rs_asu_icd10<>0&&$totalRows_rs_asu_drug<>0){echo "&nbsp;<button onclick=\"alertload('asu_consult.php?vn=".$vn."&icode=".$row_s_drug['icode']."','80%','600');\" class='btn btn-success text-white font14 btn-sm font_border' style=\"padding:0px;padding-left:5px;padding-right:5px;\" >ASU</button>";}	
//caution drug_icd10
if($totalRows_rs_drug_icd10<>0&&$totalRows_rs_icd10_drug<>0){echo "<a href=\"javascript:valid();\" onclick=\"alertload('drug_icd10_detail.php?id=".$row_rs_icd10_drug['id']."','80%','600');\"  ><img src=\"images/caution.gif\" width=\"57\" height=\"25\" border=\"0\" align=\"absmiddle\" /></a>";}					
				
					 ?>
        <?php 
					
//////////  ตรวจสอบ Cr	 //////////				
if($totalRows_rs_drug_cr<>0&&$row_rs_cr['lab_order_result']!=""){ echo "<span class=\"badge badge-info align-middle text-white font15 cursor\" style=\"\" onclick=\"modal_custom_show('primary','drug_creatinine.php?vn=".$vn."&id=".$row_rs_drug_cr['id']."&crcl=".$result_cr."&cr=".$row_rs_cr['lab_order_result']."&hn=".$hn."&lab_date=".$row_rs_cr['order_date']."','ยาที่ต้องปรับในผู้ป่วยที่มีค่าการทำงานของไตผิดปกติ','<i class=\'fas fa-exclamation-triangle font20\'></i>');\">kidney</span>"; }?>
        <?php 
//ตรวจสอบยา warfarin
if($totalRows_drug_warfarin<>0){
	echo "<a href=\"javascript:valid();\" onclick=\"alertload('drug_warfarin.php?icode=".$row_s_drug['icode']."','80%','600');\" ><img src=\"images/warfarin2.gif\" width=\"86\" height=\"27\" align=\"absmiddle\" /></a>";
	}
//ตรวจสอบยา g6pd
if($totalRows_drug_g6pd<>0){
	echo "<a href=\"javascript:valid();\" onclick=\"alertload('drug_g6pd.php?icode=".$row_s_drug['icode']."','80%','600')\" ><img src=\"images/g6pd_alert.gif\" width=\"57\" height=\"25\" align=\"absmiddle\" /></a>";
	}

//ตรวจสอบยาผู้สูงอายุ
if($totalRows_rs_drug_elder<>0){
	echo "<button type=\"button\" class=\"btn btn-primary font14\" style=\"padding:0px;padding-left:5px;padding-right:5px;\" onclick=\"modal_custom_show('danger','drug_elder_risk_view.php?id=".$row_rs_drug_elder['id']."&vn=".$vn."&icode=".$row_s_drug['icode']."','ยาที่ควรระมัดระวังในผู้ป่วยสูงอายุ','<i class=\'fas fa-exclamation-triangle font20\'></i>');\">
  สูงอายุ <span class=\"badge badge-light\">".$row_rs_drug_elder['severity']."</span>
</button>";
	}

?>
        <?php 
//mederror
if($row_s_drug['scode']==1){
if($usage_search==0){
	echo "<a href=\"javascript:valid();\" class=\"badge badge-info font15 align-middle\" >ERROR</a>";
	}
}
?>
        <?php 
//mederror
if($totalRows_s_drug_allergy<>0){
	echo "<img src=\"images/drug_allergy.gif\" width=\"65\" height=\"26\" align=\"absmiddle\" />";
}
?>
        <?php 
//mederror
if($totalRows_rs_drug_due<>0){
	echo "<button class='btn btn-danger btn-sm font14 font_border' style='padding:0px;padding-left:5px;padding-right:5px;' onclick=\"alertload('detail_drug_due.php?icode=".$row_s_drug['icode']."&vn=".$vn."&doctor=".$row_s_drug['doctor']."','80%','600');\">DUE</button>";
}
?>
        <?php 
//mederror
if($totalRows_rs_drug_had<>0){
	echo "<button class='btn btn-danger btn-sm font14 font_border' style='padding:0px;padding-left:5px;padding-right:5px;' onclick=\" alertload('detail_drug_had.php?icode=".$row_s_drug['icode']."&vn=".$vn."&doctor=".$row_s_drug['doctor']."','80%','600');\">HAD</button>";
}
?>
        <?
//emer drug
if($totalRows_emer_drug2!=0){echo "<span class='badge badge-success' style='font-size: 16px;'>EMD</span>";}
?>
        <?php 
// show renew icon
if($renew=="Y"){
	echo "<img src=\"images/renew.png\" width=\"49\" height=\"29\" border=\"0\" align=\"absmiddle\" />";
	}
?>
<?php 
	//แสดงผลของสั่งผิดขนาดในเด็ก		
if($row_s_drug['drugusage']!=''){
if($row_s_drug['dose_perunit']!=""){
//คำนวณขนาดยาเด็ก
 $optimum_min=$row_screen['bw']*$row_s_drug['dosage_min']; $optimum_max=$row_screen['bw']*$row_s_drug['dosage_max']; 
$current_dose=$row_s_drug['ccperdose']*$row_s_drug['dose_perunit'];
//echo $optimum_max."  ".$optimum_min."  ".$current_dose;
	if($current_dose>$optimum_max&&$row_s_drug['drugusage']!=""){ ?><span class="badge badge-<?php if($row_s_drug['is_error']=="N"){ echo "secondary"; }else { echo "danger"; } ?> cursor" onclick="modal_custom_show('normal','detail_error_dose.php?hos_guid=<?php echo $row_s_drug['hos_guid']; ?>&bw=<?php echo $row_screen['bw']; ?>&case=hd','ความคลาดเคลื่อนทางยา','<i class=\'fas fa-pills\'></i>');"><nobr><i class="fas fa-arrow-circle-up font16 text-white"></i>&nbsp;hight dose</nobr></span><?php  } if($current_dose<$optimum_min){ ?> <span class="badge badge-<?php if($row_s_drug['is_error']=="N"){ echo "secondary"; }else { echo "warning text-danger"; } ?>  cursor" onclick="modal_custom_show('normal','detail_error_dose.php?hos_guid=<?php echo $row_s_drug['hos_guid']; ?>&bw=<?php echo $row_screen['bw']; ?>&case=ld','ความคลาดเคลื่อนทางยา','<i class=\'fas fa-pills\'></i>');"><nobr><i class="fas fa-arrow-circle-down font16"></i>&nbsp;low dose</nobr></span><?php } 

	if(($current_dose>$optimum_max||$current_dose<$optimum_min)&&$row_s_drug['drugusage']!=""){
	//ค้นหาการบันทึกความคลาดเคลื่อน
	mysql_select_db($database_hos, $hos);
	$query_rs_drugusage_check_search = "select * from ".$database_kohrx.".kohrx_syr_dosing_record where icode='".$row_s_drug['icode']."' and vn='".$vn."'";
	$rs_drugusage_check_search = mysql_query($query_rs_drugusage_check_search, $hos) or die(mysql_error());
	$row_rs_drugusage_check_search = mysql_fetch_assoc($rs_drugusage_check_search);
	$totalRows_drugusage_check_search = mysql_num_rows($rs_drugusage_check_search);
	//ถ้าไม่พบ
	if($totalRows_drugusage_check_search==0){
		mysql_select_db($database_hos, $hos);
		$query_insert = "insert into ".$database_kohrx.".kohrx_syr_dosing_record (icode,vn,hn,drugusage,bw,doctorcode,daterecord,is_error) value ('".$row_s_drug['icode']."','".$vn."','".$hn."',substring('".$row_s_drug['code']."',2),format('".$row_screen['bw']."',0),'".$row_s_drug['doctor']."',NOW(),'Y')";
		$insert = mysql_query($query_insert, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_syr_dosing_record  (icode,vn,hn,drugusage,bw,doctorcode,daterecord) value (\'".$row_s_drug['icode']."\',\'".$vn."\',\'".$hn."\',substring(\'".$row_s_drug['code']."\',2),format(\'".$row_screen['bw']."\',0),\'".$row_s_patient['doctor']."\',NOW())')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

	}
		mysql_free_result($rs_drugusage_check);
	}//จบการบันทึก
}
}
		  
?>
	</td>
      <td align="center" style="border-right: solid 1px #EEE" <?php if($date_diff!=""){ echo "align=\"left\"";} else { echo "align=\"center\"";} ?>><font color="<?php 
		/*
		//ถ้าใช่ insulin ให้หาร 300
		if($row_s_drug['icode']==$row_setting['2']){
		$insulin_cal=300;} 
		//ถ้าไม่ใช่ insulin ไม่ต้องหาร 300
		if($row_s_drug['icode']!=$row_setting['2']){
		$insulin_cal=1;} 
		*/
	mysql_select_db($database_hos, $hos);
	$query_rs_insulin1 = "select * from ".$database_kohrx.".kohrx_drug_insulin where icode='".$row_s_drug['icode']."'";
	$rs_insulin1 = mysql_query($query_rs_insulin1, $hos) or die(mysql_error());
	$row_rs_insulin1 = mysql_fetch_assoc($rs_insulin1);
	$totalRows_rs_insulin1 = mysql_num_rows($rs_insulin1);
		
		if($totalRows_rs_insulin1<>0){
			$insulin_cal=$row_rs_insulin1['units'];
		}
		else {
			$insulin_cal=1;	
		}
	
	if($row_s_drug['zero_check']!="Y"){
		/// ถ้ายาตัวนั้นมีการตรวจสอบจำนวนแจ้งเตือน
		$qty_check=$row_setting[25];
		}
	else{
		//ถ้ายาตัวนั้นไม่มีจำนวนแจ้งเตือน
		$qty_check=$row_s_drug['zero_check'];
		}
	
	if($date_diff!=""/*ถ้าลงวันนัด*/){if(/*ถ้าจำนวน=0 และมีicode ใน qtycheck*/($row_s_drug['qty']==0&&$row_s_drug['qtycheck']!="")||(/*ถ้าจำนวนใช้จริงไม่เท่ากับ 0 และจำนวนสั่งน้อยกว่าต้องใช้จริง*/$row_s_drug['real_use']*$date_diff!=0&&$row_s_drug['qty']<(ceil((($row_s_drug['real_use']*$date_diff)/$insulin_cal)))&&$row_s_drug['qtycheck']!=""&&$row_s_drug['qtycheck']!="")||($row_s_drug['qty']>ceil(((($row_s_drug['real_use']*$date_diff)/$insulin_cal)+$qty_check))&&$row_s_drug['qtycheck']!="")){ echo "#FF0000"; } }?>"><strong><?php echo "$row_s_drug[qty]"; ?></strong></font> <font class="table_head_small<?php if($row_s_drug['qty']==0){ echo "_gray"; } ?>">
            <?php if($date_diff!=""){ if($totalRows_rs_insulin1<>0){ echo "(".number_format(($row_s_drug['real_use']*$date_diff)/$insulin_cal,2).")";  } else { echo "(".$row_s_drug['real_use']*$date_diff.")"; }}  ?></font>
        <?php if($date_diff!=""){if(($row_s_drug['qty']==0&&$totalRows_drugqty_check_search==0&&$row_s_drug['qtycheck']!="")||(($row_s_drug['real_use']*$date_diff)/$insulin_cal!=0&&$row_s_drug['qty']<(($row_s_drug['real_use']*$date_diff)/$insulin_cal)&&$totalRows_drugqty_check_search==0&&$row_s_drug['qtycheck']!="")||($row_s_drug['qty']>((($row_s_drug['real_use']*$date_diff)/$insulin_cal)+$row_setting[25])&&$totalRows_drugqty_check_search==0&&$row_s_drug['qtycheck']!="")){ echo "<span class=\"cursor badge badge-danger\" onclick=\"alertload('drugqty_check_record.php?vn=".$vn."&icode=".$row_s_drug['icode']."&doctor=".$row_s_patient['doctor']."&qty=".$row_s_drug['qty']."&qtyideal=".$row_s_drug['real_use']*$date_diff."&drugusage=".$row_s_drug['shortlist']."&appdate=".$date_diff."&drugname=".$row_s_drug['drugname']."&hn=".$hn."&vstdate=".$_GET['vstdate']."&pdx=".$_GET['pdx']."&dx0=".$_GET['dx0']."&dx1=".$_GET['dx1']."&dx2=".$_GET['dx2']."&dx3=".$_GET['dx3']."&dx4=".$_GET['dx4']."&dx5=".$_GET['dx5']."&age_y=".$_GET['age_y']."&date_diff=".$date_diff."','600','300')\"><i class=\"fas fa-plus\" style=\"font-size:10;\" ></i>"; }} ?>
    </td>
      <td align="left" style="border-right:solid 1px #C7E4F1">
	<?php
	if($row_s_drug['dose_perunit']!=""){
?>
        <a href="javascript:valid();" onclick="alertload('show_dosage.php?icode=<?php echo $row_s_drug['icode']; ?>&amp;bw=<?php echo $row_screen['bw']; ?>','80%','250')"><img src="images/calculator2.png" width="25" height="25" border="0" align="absmiddle" /></a>
        <? } ?>
<?php 
if(in_array($row_s_drug['icode'],$insulin_array,TRUE)){ 
mysql_select_db($database_hos, $hos);
$query_rs_search = "select * from ".$database_kohrx.".kohrx_insulin_syring where hn='".$hn."'";
$rs_search = mysql_query($query_rs_search, $hos) or die(mysql_error());
$row_rs_search = mysql_fetch_assoc($rs_search);
$totalRows_rs_search = mysql_num_rows($rs_search); ?>
<nobr>
<?php
if($row_rs_search['syring_type']==1){ echo "<img src=\"images/syring2.png\" width=\"43\" height=\"30\" align=\"absmiddle\" />"; }
else if($row_rs_search['syring_type']>1){
if($row_rs_search['syring_type']==2){ echo "<span class='badge badge-danger'>Pen1</span>&nbsp;+"; } else if($row_rs_search['syring_type']==3){ echo "<span class='badge badge-info'>Pen2</span>&nbsp;+"; }
if($row_rs_search['needle_type']==0){ echo "<img src=\"images/5mm.png\" width=\"30\"  align=\"absmiddle\" />"; }     
if($row_rs_search['needle_type']==1){ echo "<img src=\"images/6mm.png\" width=\"30\" align=\"absmiddle\" />"; } if($row_rs_search['needle_type']==2){ echo "<img src=\"images/8mm.png\" width=\"30\" align=\"absmiddle\" />"; } 
    }
?>
</nobr>
<?php
}
    ?>
        <?php if(in_array($row_s_drug['icode'],$steroid_array,TRUE) && $steroid_dose<=0){ echo "<span class=\"button_red\">หมด</span>"; } else if(in_array($row_s_drug['icode'],$steroid_array,TRUE) && $steroid_dose>0) { echo "<span class=\"badge badge-dark text-white font14\" style=\"padding:5px;\" ><nobr>เหลือ <span class=\"badge badge-light\">".($row_rs_drug_steroid2['dose']-$steroid_dose)."</span> ครั้ง</nobr></span>";}?>
        <span >
          <?php
//ตรวจสอบ adherance
 if(in_array($row_s_drug['icode'],$adh_array,TRUE)){ if($qty_adherance<0){ echo "<span class=\"button_red\">ขาดยา ".str_replace('-','',$qty_adherance)." เม็ด</span>";} if($qty_adherance>0){ echo "<span class=\"badge badge-dark text-white font14\" style=\"padding:5px;\"><nobr>ยาเหลือ <span class=\"badge badge-light\">".$qty_adherance."</span>เม็ด</nobr></span>";} } 
 ?>
        </span></td>
      <td align="center" class="text-center font13"><?php echo substr($row_s_drug['rxtime'],0,5); ?></td>
    </tr>
  </form>
  <?php 
				if($row_s_drug['scode']==1){
				mysql_free_result($rs_drug_pulse2);
				mysql_free_result($rs_asu_icd10);
				mysql_free_result($rs_insulin1);
if($totalRows_rs_asu_icd10<>0){
				mysql_free_result($rs_asu_drug);}
				mysql_free_result($rs_drug_icd10);

if($totalRows_rs_drug_icd10<>0){
				mysql_free_result($rs_icd10_drug);}
				mysql_free_result($rs_drugqty_check_search);
				mysql_free_result($rs_drug_elder);
				mysql_free_result($s_drug_allergy);
				mysql_free_result($rs_drug_due);
				mysql_free_result($rs_drug_had);
				mysql_free_result($emer_drug2);
				
				}
					 } while ($row_s_drug = mysql_fetch_assoc($s_drug)); ?>
    </tbody>
  <?php } // Show if recordset empty ?>
  <tr >
  <td colspan="6" style="padding:0px;">
  <div id="drug-off"></div>
  </td>
  </tr>

</table>
<div class="wrapper">
<nav class="navigation bg-secondary" style=" border-radius: 0 0 5px 5px;">
  <ul>
    <li>
      <a href="javascript:valid(0);" onClick="alertload('mederror/index.php?doctor=<?php echo $row_s_doctor['name']; ?>&amp;g_hn=<?php echo $hn; ?>&amp;depart=<?php echo $depart; ?>&amp;type_id=<?php echo $row_setting[13]; ?>&amp;cause_id=&amp;g_detail=<?php echo $row_s_drug['drugname']."  ".$row_s_drug['shortlist']; ?>','90%','90%')" class="btn btn-light btn-sm text-dark" style="font-size:12px;"><i class="fas fa-pills"></i>&nbsp;Med.error</a>
    </li>
	  <li>
      <a href="javascript:valid(0);" class="btn btn-light btn-sm text-dark" style="font-size:12px;">ลงทะเบียน</a>
		 <ul class="children sub-menu">
			 <li>
				<a href="javascript:valid(0);" onclick="alertload('warfarin.php?hn=<?php echo $hn; ?>','500','150')" class=" text-dark" style="font-size:12px;" ><i class="fas fa-heart"></i>&nbsp;Warfarin</a>
			 </li>
			 <li>
		        <a href="javascript:valid(0);" onclick="alertload('g6pd.php?hn=<?php echo $hn; ?>','500','150');" class="text-dark" style="font-size:12px;" ><i class="fas fa-disease"></i>&nbsp;G-6-PD</a>
			 </li>
			 <li>
		        <a href="javascript:valid(0);" onclick="alertload('needle.php?hn=<?php echo $hn; ?>&vstdate=<?php echo $vstdate; ?>&vn=<?php echo $vn; ?>','500','400');" class=" text-dark" style="font-size:12px;" ><i class="fas fa-syringe"></i>&nbsp;เข็มInsulin</a>				 
				 
			 </li>
		  </ul>
	  </li>	
			<li >	
				<a href="javascript:valid();" onclick="alertload('sticker_print.php?vn=<?php echo $vn; ?>','90%','500');" class="btn btn-light btn-sm text-dark nav-link" style="font-size:12px;" ><i class="fas fa-print"></i>&nbsp;พิมพ์ sticker</a>
		  </li>	
			<li >	
				<a href="javascript:valid();" onclick="alertload('emer_drug.php?vn=<?php  echo $vn; ?>&hn=<?php  echo $hn; ?>&vstdate=<?php  echo $_GET['vstdate']; ?>','90%','90%');" class="btn btn-light btn-sm text-dark nav-link" style="font-size:12px" ><i class="fas fa-shipping-fast"></i>&nbsp;ยาด่วน</a>
		  </li>	
		  <li>		  
			  	<a onclick="drug_check_popup('detail_drug_check.php?vn=<?php  echo $vn; ?>&hn=<?php  echo $hn; ?>&vstdate=<?php  echo $_GET['vstdate']; ?>','90%','90%');" class="btn btn-light btn-sm text-dark nav-link" style="font-size:12px" ><i class="fas fa-check-double"></i>&nbsp;เช็คยา</a>
		  </li>
	  	  <li>
      			<a class="tbn btn-light btn-sm text-dark" style="font-size:12px;" href="#"><i class="fas fa-clipboard-check "></i>&nbsp;DRP</a>
			  	<ul class="children sub-menu">
					<li>
					<a href="javascript:valid(0);" onclick="alertload('detail_drp.php?hn=<?php  echo $hn; ?>&pt=<?php echo $_SESSION['pt']; ?>','90%','90%');" class="btn btn-light btn-sm text-dark" style="font-size:12px" >DRP&nbsp;<span class="badge badge-secondary font12">1</span></a>
					</li>
					<li>
					<a href="javascript:valid(0);" onclick="alertload('drp2.php?vn=<?php  echo $vn; ?>&hn=<?php  echo $hn; ?>','90%','90%');" class="btn btn-light btn-sm text-dark " style="font-size:12px" >DRP&nbsp;<span class="badge badge-secondary font12">2</span></a>		  
					</li>				
			    </ul>
	  		</li>
	  			<li>
					<a href="javascript:valid(0);" onclick="window.open('med_reconcile.php?do=link&hn=<?php  echo $hn; ?>&vstdate=<?php echo date_db2th($vstdate); ?>','_new');" class="btn btn-light btn-sm text-dark" style="font-size:12px" >Med.Reconcile</a>		  
	  			</li>
	  			<li>
					<a href="javascript:valid(0);" onclick="alertload('detail_label_icon.php?hn=<?php  echo $hn; ?>','800','500');" class="btn btn-light btn-sm text-dark" style="font-size:12px" ><i class="fas fa-tags"></i>&nbsp;ป้ายกำกับ</a>		  
	  			</li>
			  	<li>	
					<a href="javascript:valid(0);" onclick="alertload('drug_profile_log.php?hn=<?php echo $hn; ?>','90%','500');" class="btn btn-light btn-sm text-dark" style="font-size:12px;" ><i class="fas fa-clipboard-check"></i>&nbsp;Drug log</a>
	  			</li>
	  			<li>
					<a class="btn btn-light btn-sm text-dark" href="#" style="font-size:12px" ><i class="fas fa-folder-open font14"></i>&nbsp;Document</a>
					<ul class="children sub-menu">
						<li>
							<a class="font12 text-dark" href="#" onclick="window.open('document_insulin.php?hn=<?php echo $hn; ?>','_new')">ใบแนะนำการฉีดอินซูลิน(pen)</a>
						</li>
						<li>
	                        <a class="font12 text-dark" href="#" onclick="window.open('document_insulin2.php?hn=<?php echo $hn; ?>','_new')">ใบแนะนำการฉีดอินซูลิน(syring)</a>							
						</li>
						<li>
	                        <a class="font12 text-dark" href="#" onclick="alertload('app_covid_drug_suggestion.php?vn=<?php echo $vn; ?>','90%','90%')">ตารางการทานยา Favipiravir</a>							
						</li>
					</ul>
	  			</li>
	</ul>
	</nav>
	  
	</div>

</div>
</body>
</html>

<?php mysql_free_result($s_drug); ?>

<?php mysql_free_result($screen); ?>

<?php if($row_setting[45]!=""){
	mysql_free_result($rs_gfr);
}
?>
<?php if($row_setting[7]!=""){
	mysql_free_result($rs_cr);
}
?>
<?php if($totalRows_rs_last_drug<>0){ 
 mysql_free_result($rs_last_drug); 
mysql_free_result($s_patient);
 } ?>
