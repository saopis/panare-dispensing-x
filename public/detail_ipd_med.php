<?php require_once('Connections/hos.php'); ?>
<?php 
if($_GET['order_no']==""){
mysql_select_db($database_hos, $hos);
$query_rs_order_no = "select o.order_no from (select order_no from opitemrece where  an='".$_GET['an']."' union select order_no from opitemrece_arc where  an='".$_GET['an']."') as o left outer join ipt_order_no i on i.order_no=o.order_no where i.order_type in ('IRx','Hme')  group by o.order_no order by o.order_no DESC limit 1";
$rs_order_no = mysql_query($query_rs_order_no, $hos) or die(mysql_error());
$row_rs_order_no = mysql_fetch_assoc($rs_order_no);
$totalRows_rs_order_no = mysql_num_rows($rs_order_no);
    
$order=$row_rs_order_no['order_no'];

}
else{
$order=$_GET['order_no'];    
}

mysql_select_db($database_hos, $hos);
$query_s_drug = "select m1.sp_use,m1.order_no,concat(s.name,' ',s.strength,' ',s.units) as name ,d.shortlist  , mp.med_order_number,m1.icode,concat(s.name,' ',s.strength,' ',s.units) as drugname,concat(sp.name1,sp.name2,sp.name3) as sp_name, m1.qty,mp.med_plan_number,mp.med_real_pay_qty,mp.day_number,substring(m1.icode,1,1) as scode,m1.hn,m1.vn,m1.an,vstdate,a.pdx,a.dx0,a.dx1,a.dx2,a.dx3,a.dx4,a.dx5,p.sex,concat('.',d.code) as code,du.real_use,m1.drugusage  
from (select an,order_no,icode,qty,drugusage,hos_guid,sp_use,item_no,hn,vn,vstdate from opitemrece where order_no='".$order."' and an='".$_GET['an']."' union select an,order_no,icode,qty,drugusage,hos_guid,sp_use,item_no,hn,vn,vstdate from opitemrece_arc where order_no='".$order."' and an='".$_GET['an']."') m1    left outer join s_drugitems s on s.icode=m1.icode   
left outer join drugusage d on d.drugusage=m1.drugusage  
left outer join ".$database_kohrx.".kohrx_drugusage_realuse du on du.drugusage=m1.drugusage
left outer join medpay_ipd mp on mp.hos_guid = m1.hos_guid 
left outer join sp_use sp on sp.sp_use=m1.sp_use 
left outer join an_stat a on a.an=m1.an 
left outer join patient p on p.hn=m1.hn order by item_no ";
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

if($totalRows_s_drug<>0){
//ความปลอดภัยด้านยา
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

if($row_s_drug['sex']==1){
	$f=1;
	$normal='97-137';
	}
if($row_s_drug['sex']==2){
	$f=0.85;
	$normal='88-128';
	}
mysql_select_db($database_hos, $hos);
$query_rs_cr = "select  format((((140-v.age_y)*os.bw)/(72*lab_order_result))*".$f.",2) as lab_order_result1,lab_order_result,lab_items_unit,lab_items_normal_value,concat(date_format(order_date,'%d/%m/'),(date_format(order_date,'%Y')+543)) as order_date1,i.range_check_min,i.range_check_max,order_date,v.age_y from lab_order l left join lab_head h on h.lab_order_number=l.lab_order_number left join lab_items i on i.lab_items_code=l.lab_items_code  left join opdscreen os on os.hn=h.hn and os.vstdate=h.order_date left join an_stat v on v.hn=h.hn where h.hn='".$hn."' and l.lab_items_code='".$row_setting[7]."' order by order_date DESC limit 1";
$rs_cr = mysql_query($query_rs_cr, $hos) or die(mysql_error());
$row_rs_cr = mysql_fetch_assoc($rs_cr);
$totalRows_rs_cr = mysql_num_rows($rs_cr);

	if($row_rs_cr['lab_order_result']!=""&&(is_numeric($row_rs_cr['lab_order_result'])==true)){
	//ถ้าเป็นผู้ชาย
	$xx=$row_rs_cr['lab_order_result'];
	$yy=$row_rs_cr['age_y'];
	
	if($row_s_drug['sex']==1){ 
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
	if($row_s_drug['sex']==2){ 
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
	
	$result_cr=number_format($gfr,2);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php //include('java_css_file.php'); ?>
<script>
    $(function(){
		// onload
                    $("#ipd_save").load('detail_ipd_save.php?order_no=<?php echo $order; ?>&an=<?php echo $_GET['an']; ?>', function(responseTxt, statusTxt, xhr){
						if(statusTxt == "success")
						  //alert("External content loaded successfully!");
							$('#indicator').hide();
						if(statusTxt == "error")
						   alert("Error: " + xhr.status + ": " + xhr.statusText);    
					});
		
        $('#save').click(function(){
				alert();
                    $("#ipd_save").load('detail_ipd_save.php?action=save&order_no=<?php echo $order; ?>&an=<?php echo $_GET['an']; ?>&prepare='+$('#prepare').val()+'&dispen='+$('#dispen').val(), function(responseTxt, statusTxt, xhr){
                    
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();
                    if(statusTxt == "error")
                       alert("Error: " + xhr.status + ": " + xhr.statusText);    
               });

			
        });
    });
</script>
</head>

<body>
<div class="card mt-2">
<div class="card-body p-2">
<div style="overflow:scroll;overflow-x:hidden;overflow-y:auto; height:63vh; background-color: white; ">
<?php if($totalRows_s_drug<>0){ ?>
<table border="0" style="width:100%;" class="table table-sm table-striped table-bordered table-hover"  >
      <tr class="text-white font14" style="background-color: #85A8BA">
        <td width="6%" height="28" align="center" >ลำดับ</td>
        <td width="39%" align="center" class=""  >ชื่อยา</td>
        <td width="45%" align="center" class=""  >วิธีใช้</td>
        <td width="10%" align="center"   >จำนวน </td>
      </tr>
      <?php $i=0; do { $i++;

$vn_drug_last="";

if($row_s_drug['scode']=='1'){
mysql_select_db($database_hos, $hos);
$query_s_new = "
select o.vn,o.icode,o.hn from opitemrece o where o.hn='".$hn."' and o.order_no<'".$order."' and o.icode='".$row_s_drug['icode']."' order by o.order_no DESC  limit 1"; 
$s_new = mysql_query($query_s_new, $hos) or die(mysql_error());
$row_s_new = mysql_fetch_assoc($s_new);
$totalRows_s_new = mysql_num_rows($s_new);
//จำนวนครั้งของยาที่เคยได้รับ OPD
$total_count1=$totalRows_s_new;	
//$total_count1=$row_s_new['count1'];
$vn_drug_last_opd=$row_s_new['vn'];

mysql_select_db($database_hos, $hos);
$query_s_new = "
select o.vn,o.icode,o.hn from opitemrece o where o.hn='".$hn."' and o.an is NULL and o.icode = '".$row_s_drug['icode']."' and o.vn <'".$vn."' order by o.vn DESC limit 1";

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
$query_s_use.="and p.order_no <'".$order."' order by p.vstdate DESC limit 1";
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
	$query_rs_drug_steroid2 = "select k.dose,o.vstdate,DATEDIFF(NOW(),o.vstdate) as date_diff from opitemrece o left outer join ".$database_kohrx.".kohrx_drug_steroid_haler k on k.icode=o.icode where k.icode='".$row_s_drug['icode']."' and hn='".$hn."' and vstdate != '".$vstdate."' order by vstdate DESC limit 1 ";
	$rs_drug_steroid2 = mysql_query($query_rs_drug_steroid2, $hos) or die(mysql_error());
	$row_rs_drug_steroid2 = mysql_fetch_assoc($rs_drug_steroid2);
	$totalRows_rs_drug_steroid2 = mysql_num_rows($rs_drug_steroid2);
	
	$steroid_dose=(2*$row_rs_drug_steroid2['date_diff'])-1;
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
$query_asu_icd10 = "select * from ".$database_kohrx.".kohrx_asu_icd10 where code in ('".$_GET['pdx']."','".$_GET['dx0']."','".$_GET['dx1']."','".$_GET['dx2']."','".$_GET['dx3']."','".$_GET['dx4']."','".$_GET['dx5']."')";
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
$query_asu_record = "select * from ".$database_kohrx.".kohrx_asu_record where vn='".$vn."' and icode='".$row_s_drug['icode']."' and icd10 in ('".$_GET['pdx']."','".$_GET['dx0']."','".$_GET['dx1']."','".$_GET['dx2']."','".$_GET['dx3']."','".$_GET['dx4']."','".$_GET['dx5']."')";
$rs_asu_record = mysql_query($query_asu_record, $hos) or die(mysql_error());
$row_rs_asu_record = mysql_fetch_assoc($rs_asu_record);
$totalRows_rs_asu_record = mysql_num_rows($rs_asu_record);

if($totalRows_rs_asu_record==0&&$totalRows_rs_asu_drug<>0){
//ค้นหาแต่ละ diag
//pdx
$asu_dx=array($_GET['pdx'],$_GET['dx0'],$_GET['dx1'],$_GET['dx2'],$_GET['dx3'],$_GET['dx4'],$_GET['dx5']);

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
$query_drug_icd10 = "select * from ".$database_kohrx.".kohrx_drug_icd10 where  ('".$_GET['pdx']."' between icd101 and icd102) or ('".$_GET['dx0']."' between icd101 and icd102) or ('".$_GET['dx1']."' between icd101 and icd102) or ('".$_GET['dx2']."' between icd101 and icd102) or ('".$_GET['dx3']."' between icd101 and icd102) or ('".$_GET['dx4']."' between icd101 and icd102) or ('".$_GET['dx5']."' between icd101 and icd102)";
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
$query_drug_icd10_record = "select * from ".$database_kohrx.".kohrx_drug_icd10_record where vn='".$vn."' and icode='".$row_s_drug['icode']."' and icd10 in ('".$_GET['pdx']."','".$_GET['dx0']."','".$_GET['dx1']."','".$_GET['dx2']."','".$_GET['dx3']."','".$_GET['dx4']."','".$_GET['dx5']."')";
$rs_drug_icd10_record = mysql_query($query_drug_icd10_record, $hos) or die(mysql_error());
$row_rs_drug_icd10_record = mysql_fetch_assoc($rs_drug_icd10_record);
$totalRows_rs_drug_icd10_record = mysql_num_rows($rs_drug_icd10_record);

if($totalRows_rs_drug_icd10_record==0&&$totalRows_rs_icd10_drug<>0){
$drug_icd10_dx=array($_GET['pdx'],$_GET['dx0'],$_GET['dx1'],$_GET['dx2'],$_GET['dx3'],$_GET['dx4'],$_GET['dx5']);

for($i=0;$i<count($drug_icd10_dx); $i++){
	mysql_select_db($database_hos, $hos);
	$query_drug_icd10_dx = "select * from ".$database_kohrx.".kohrx_drug_icd10 where '".$drug_icd10_dx['$i']."' between icd101 and icd102";
	$rs_drug_icd10_dx = mysql_query($query_drug_icd10_dx, $hos) or die(mysql_error());
	$row_rs_drug_icd10_dx = mysql_fetch_assoc($rs_drug_icd10_dx);
	$totalRows_rs_drug_icd10_dx = mysql_num_rows($rs_drug_icd10_dx);
	
if($totalRows_rs_drug_icd10_dx<>0){
	
	mysql_select_db($database_hos, $hos);
	$query_insert = "insert into ".$database_kohrx.".kohrx_drug_icd10_record (icode,icd10,vn,doctorcode) value ('".$row_s_drug['icode']."','".$drug_icd10_dx['$i']."','".$vn."','".$row_s_doctor['code']."')";
	$insert = mysql_query($query_insert, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_drug_icd10_record (icode,icd10,vn,doctorcode) value (\'".$row_s_drug['icode']."\',\'".$drug_icd10_dx['$i']."\',\'".$vn."\',\'".$row_s_doctor['code']."\')')";
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
	$query_rs_drugusage_check_record = "insert into ".$database_kohrx.".kohrx_drugusage_check_record (vn,hn,icode,drugusage,doctorcode) value ('".$vn."','".$hn."','".$row_s_drug['icode']."',substring('".$row_s_drug['code']."',2),'".$row_s_doctor['code']."')";
	$rs_drugusage_check_record = mysql_query($query_rs_drugusage_check_record, $hos) or die(mysql_error());
		
	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_drugusage_check_record (vn,hn,icode,drugusage,doctorcode) value (\'".$vn."\',\'".$hn."\',\'".$row_s_drug['icode']."\',substring(\'".$row_s_drug['code']."\',2),\'".$row_s_doctor['code']."\')')";
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
	$query_insert = "insert into ".$database_kohrx.".kohrx_drug_elder_risk_record (vn,icode,age,doctorcode,daterecord,severity) value ('".$vn."','".$row_s_drug['icode']."','".$_GET['age_y']."','".$row_s_doctor['code']."',NOW(),'".$row_rs_drug_elder['severity']."')";
	$insert = mysql_query($query_insert, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_drug_elder_risk_record (vn,icode,age,doctorcode,daterecord,severity) value (\'".$vn."\',\'".$row_s_drug['icode']."\',\'".$_GET['age_y']."\',\'".$row_s_doctor['code']."\',NOW(),\'".$row_rs_drug_elder['severity']."\')')";
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
	$query_insert = "insert into ".$database_kohrx.".kohrx_had_record (vn,icode,doctor) value ('".$vn."','".$row_s_drug['icode']."','".$row_s_doctor['code']."')";
	$insert = mysql_query($query_insert, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_had_record (vn,icode,doctor) value (\'".$vn."\',\'".$row_s_drug['icode']."\',\'".$row_s_doctor['code']."\')')";
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
      <tr >
        <td height="27" align="center"  style="border-right: solid 1px #EEE"><?=$i; ?></td>
        <td  class=" font14"  style="border-right: solid 1px #EEE; padding-right:5px"><?php echo $row_s_drug['drugname']; ?></td>
        <td align="left"  style="border-right: solid 1px #EEE;" class="font14" >
         <?php if($row_s_drug['scode']==1){ ?>
			<span style="cursor: pointer" onClick="alertload('item_history.php?hn=<?php echo $row_s_drug['hn']; ?>&amp;icode=<?php echo $row_s_drug['icode']; ?>','800','500')" >
			<?php if($row_s_drug['sp_use']=="") {echo "$row_s_drug[shortlist]"; } else { echo "$row_s_drug[sp_name]"; } ?>
			</span>
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
	$query_insert = "insert into ".$database_kohrx.".kohrx_drug_use_change (vn,hn,icode,drugusage,doctor,change_type) value ('".$vn."','".$hn."','".$row_s_drug['icode']."','".$row_s_drug['drugusage']."','".$row_s_doctor['code']."','new') ";
	$insert = mysql_query($query_insert, $hos) or die(mysql_error());
	
	//update replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_drug_use_change (vn,hn,icode,drugusage,doctor,change_type) value (\'".$vn."\',\'".$hn."\',\'".$row_s_drug['icode']."\',\'".$row_s_drug['drugusage']."\',\'".$row_s_doctor['code']."\',\'new\')')";
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
	$query_insert = "insert into ".$database_kohrx.".kohrx_drug_use_change (vn,hn,icode,drugusage,doctor,change_type) value ('".$vn."','".$hn."','".$row_s_drug['icode']."','".$row_s_drug['drugusage']."','".$row_s_doctor['code']."','$change_type') ";
	$insert = mysql_query($query_insert, $hos) or die(mysql_error());
	
	//update replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_drug_use_change (vn,hn,icode,drugusage,doctor,change_type) value (\'".$vn."\',\'".$hn."\',\'".$row_s_drug['icode']."\',\'".$row_s_drug['drugusage']."\',\'".$row_s_doctor['code']."\',\'".$change_type."\')')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	}
	mysql_free_result($rs_drug_check);
	}///// finish ///////
	 //ตรวจสอบ pregnancy
	 if ($row_screen['pregnancy']=="Y" && $row_s_drug['pregnancy_notify_text']!=""){ echo "<a href=\"javascript:alertload('pregnancy.php?icode=".$row_s_drug['icode']."','50%','80%','');\"><img src=\"images/preg2.gif\" width=\"22\" height=\"22\" border=\"0\" align=\"absmiddle\" /></a>"; } if ($row_screen['breast_feeding']=="Y" && $row_s_drug['breast_feeding_alert_text']!=""){ echo "<a href=\"javascript:valid();\" onclick=\"alertload('lactation.php?icode=".$row_s_drug['icode']."','50%','80%','');\"><img src=\"images/lac.gif\" width=\"22\" height=\"22\" border=\"0\" align=\"absmiddle\" /></a>";} if($totalRows_sp_drug<>0){echo "<a href=\"document/".$row_sp_drug['name']."\" class=\" align-middle ml-1\" target=\"_blank\"><i class=\"fas fa-file-alt text-primary\" style=\"font-size:25px;\"></i></a>";}
if($totalRows_rs_asu_icd10<>0&&$totalRows_rs_asu_drug<>0){echo "
<button onclick=\"alertload('asu_consult.php?vn=".$vn."&icode=".$row_s_drug['icode']."','80%','600');\" class='btn btn-success text-white font14 btn-sm font_border' style=\"padding:0px;padding-left:5px;padding-right:5px;\" >ASU</button>";}	
//caution drug_icd10
if($totalRows_rs_drug_icd10<>0&&$totalRows_rs_icd10_drug<>0){echo "<a href=\"javascript:valid();\" onclick=\"alertload('drug_icd10_detail.php?id=".$row_rs_icd10_drug['id']."','80%','600');\"  ><img src=\"images/caution.gif\" width=\"57\" height=\"25\" border=\"0\" align=\"absmiddle\" /></a>";}					
				
					 ?>
        <?php 
					
//////////  ตรวจสอบ Cr	 //////////				
if($totalRows_rs_drug_cr<>0&&$row_rs_cr['lab_order_result']!=""){ echo "<a href=\"javascript:valid();\" onclick=\"alertload('drug_creatinine.php?vn=".$vn."&id=".$row_rs_drug_cr['id']."&crcl=".$result_cr."&cr=".$row_rs_cr['lab_order_result']."&hn=".$hn."&lab_date=".$row_rs_cr['order_date']."','80%','600')\" > <img src=\"images/cr.gif\" width=\"40\" height=\"25\" align=\"absmiddle\" /></a>"; }?>
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
	echo "<button type=\"button\" class=\"btn btn-primary font14\" style=\"padding:0px;padding-left:5px;padding-right:5px;\" onclick=\"alertload('drug_elder_risk_view.php?id=$row_rs_drug_elder[id]&vn=".$vn."&icode=$row_s_drug[icode]','80%','600');\">
  สูงอายุ <span class=\"badge badge-light\">".$row_rs_drug_elder['severity']."</span>
</button>";
	}

?>
        <?php 
//mederror
if($row_s_drug['scode']==1){
if($usage_search==0){
	echo "<a href=\"javascript:valid();\" onclick=\"alertload('mederror/med_error_form.php?icode=".$row_s_drug['icode']."&doctor=".$row_s_doctor['name']."&g_hn=".$hn."&depart=".$depart."&type_id=".$row_setting[13]."&cause_id=".$row_setting[14]."&g_detail=สั่ง ".$row_s_drug['drugname']." ผิดวิธี โดยสั่งเป็น ".$row_s_drug['code']."','90%','90%');\" class=\"badge badge-info font15 align-middle\" >ERROR</a>";
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
if($totalRows_rs_drug_due<>0){
	echo "
    <button class='btn btn-danger btn-sm font14 font_border' style='padding:0px;padding-left:5px;padding-right:5px;' onclick=\"alertload('detail_drug_due.php?icode=".$row_s_drug['icode']."&vn=".$vn."&doctor=".$row_s_doctor['code']."','80%','600');\">DUE</button>
    ";
}
?>
        <?php 

if($totalRows_rs_drug_had<>0){
	echo "<button class='btn btn-danger btn-sm font14 font_border' style='padding:0px;padding-left:5px;padding-right:5px;' onclick=\" alertload('detail_drug_had.php?icode=".$row_s_drug['icode']."&vn=".$vn."&doctor=".$row_s_doctor['code']."','80%','600');\">HAD</button>";
}
?>
        <?
//emer drug
if($totalRows_emer_drug2!=0){echo "<img src=\"images/emd.png\" width=\"40\" height=\"21\" border=\"0\" align=\"absmiddle\" />";}
?>
        <?php 
// show renew icon
if($renew=="Y"){
	echo "<img src=\"images/renew.png\" width=\"49\" height=\"29\" border=\"0\" align=\"absmiddle\" />";
	}
?>
<?php } //scode=1 ?>
        </td>
        <td align="center" class="font12"  ><?php echo "$row_s_drug[qty]"; ?></td>
      </tr>
      <?php 
 } while ($row_s_drug = mysql_fetch_assoc($s_drug)); ?>
    </table>
	<?php } ?>
</div>
</div>
</div>
	<div id="ipd_save"></div>	
</body>
</html>
<?php mysql_free_result($s_drug); ?>