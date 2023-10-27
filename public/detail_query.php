<?php 
if(isset($_GET['vstdate'])){ $vstdate=date_th2db($_GET['vstdate']); }
if(isset($_POST['vstdate'])){ $vstdate=date_th2db($_POST['vstdate']); }

if(isset($_GET['hn'])){ $hn_search=$_GET['hn']; }
if(isset($_POST['hn'])){ $hn_search=$_POST['hn']; }

if(isset($_GET['vn'])){ $vn=$_GET['vn']; }
if(isset($_POST['vn'])){ $vn=$_POST['vn']; }

if(isset($_GET['q'])){ 
    mysql_select_db($database_hos, $hos);
    $query_rs_hn = "select hn from ovst where vstdate='".$vstdate."' and oqueue='".$_GET['q']."'";
    $rs_hn = mysql_query($query_rs_hn, $hos) or die(mysql_error());
    $row_rs_hn = mysql_fetch_assoc($rs_hn);
    $totalRows_rs_hn = mysql_num_rows($rs_hn);

    $hn_search=$row_rs_hn['hn']; 

    mysql_free_result($rs_hn);
    
    }

if(isset($_POST['q'])){ 
    mysql_select_db($database_hos, $hos);
    $query_rs_hn = "select hn from ovst where vstdate='".$vstdate."' and oqueue='".$_POST['q']."'";
    $rs_hn = mysql_query($query_rs_hn, $hos) or die(mysql_error());
    $row_rs_hn = mysql_fetch_assoc($rs_hn);
    $totalRows_rs_hn = mysql_num_rows($rs_hn);

    $hn_search=$row_rs_hn['hn']; 

    mysql_free_result($rs_hn);

    }


mysql_select_db($database_hos, $hos);
$query_rs_setting = "select * from ".$database_kohrx.".kohrx_dispensing_setting order by id ASC";
$rs_setting = mysql_query($query_rs_setting, $hos) or die(mysql_error());
$row_rs_setting = mysql_fetch_assoc($rs_setting);
$totalRows_rs_setting = mysql_num_rows($rs_setting);

$i=0; do{ $i++;
$row_setting[$i]=$row_rs_setting['value'];} 
while($row_rs_setting = mysql_fetch_assoc($rs_setting));

mysql_free_result($rs_setting);

$hn=sprintf("%".$row_setting[24]."d", $hn_search);

//echo $hn." ".$vstdate;

if($_GET['q']!=''){
/*	mysql_select_db($database_hos, $hos);
$query_s_q = "select hn from ovst where oqueue='$queue' and vstdate='$edate1'";
$s_q= mysql_query($query_s_q, $hos) or die(mysql_error());
$row_s_q = mysql_fetch_assoc($s_q);
$totalRows_s_q = mysql_num_rows($s_q);
*/
//	$condition="ov.hn='$row_s_q[hn]' and ov.vstdate='$edate1' ";
//	$condition2=" order by v.vstdate DESC ";
	$condition="ov.oqueue='".$_GET['q']."' and ov.vstdate='".$vstdate."'";
	$condition2=" order by v.vstdate DESC ";
}
if($_GET['hn']!="" ){
	$condition="ov.hn='".$hn."' and ov.vstdate='".$vstdate."' ";
	$condition2=" order by v.vstdate DESC ";}

if($_GET['vn']!="" ){
	$condition="ov.vn='".$vn."' ";
    $condition2="";
}
/////////////// s_patient ค้นหาข้อมูลพื้นฐานผู้ป่วย ///////////////
mysql_select_db($database_hos, $hos);
$query_s_patient = "SELECT v.vn,v.hn, concat(p.pname,p.fname,'    ',p.lname) as patient_name,v.age_y,v.age_m,concat(p.addrpart,'  ม. ',p.moopart,'  ',t.full_name) as thaiaddress,v.vstdate,ov.pttype,ov.doctor,v.pdx,v.dx0,v.dx1,v.dx2,v.dx3,v.dx4,v.dx5,p.cid,v.pttypeno,p.sex,ov.oqueue,date_format(v.vstdate,'%d/%m/%Y') as visitdate,ov.vsttime FROM patient p  left outer join pname s on s.name=p.pname left outer join thaiaddress t on t.chwpart=p.chwpart and t.tmbpart=p.tmbpart and t.amppart=p.amppart left outer join vn_stat v on v.hn=p.hn left outer join ovst ov on ov.vn=v.vn  where ".$condition.$condition2;
//echo $query_s_patient;
$s_patient = mysql_query($query_s_patient, $hos) or die(mysql_error());
$row_s_patient = mysql_fetch_assoc($s_patient);
$totalRows_s_patient = mysql_num_rows($s_patient);

$vn=$row_s_patient['vn'];

$vn1=$row_s_patient['vn'];
if($totalRows_s_patient>1){
echo "<script>page_load2('dispen-body','visit_list2.php?vstdate=".$vstdate."&hn=".$hn."');</script>";	//**
exit();
	}
if($vn1==""){
require('visit_list.php');
exit(); //**
}

$hn2=$row_s_patient['hn'];


//ค้นหาแผนก
mysql_select_db($database_hos, $hos);
$query_channel = "SELECT q.*,n.channel_name,n.id as channel_id,r.room_name,n.q_show from ".$database_kohrx.".kohrx_queue_caller_channel q left outer join ".$database_kohrx.".kohrx_queue_caller_channel_name n on n.id=q.channel left outer join ".$database_kohrx.".kohrx_queue_caller_room r on r.id=q.room_id WHERE ip='".$get_ip."'";
$rs_channel = mysql_query($query_channel, $hos) or die(mysql_error());
$row_rs_channel = mysql_fetch_assoc($rs_channel);
$totalRows_rs_channel = mysql_num_rows($rs_channel);

//+++++++++++++++ vital sign ++++++++++++++++++//
//======= screen ========//
mysql_select_db($database_hos, $hos);
$query_screen = "select bpd,bps,bw,cc,pe,hr,pulse,temperature,pregnancy,breast_feeding,height from opdscreen where vn='".$row_s_patient['vn']."'";
$screen = mysql_query($query_screen, $hos) or die(mysql_error());
$row_screen = mysql_fetch_assoc($screen);
$totalRows_screen = mysql_num_rows($screen);

//========== เวลาสั่งยา ==========//
mysql_select_db($database_hos, $hos);
$query_rx_doctor = "select rx_time from rx_doctor where vn='".$row_s_patient['vn']."'";
$rx_doctor = mysql_query($query_rx_doctor, $hos) or die(mysql_error());
$row_rx_doctor = mysql_fetch_assoc($rx_doctor);
$totalRows_rx_doctor = mysql_num_rows($rx_doctor);

//========== PDX ==========//
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

switch($row_s_doctor['position_name']){
	case  "แพทย์" :
	$depart=$row_setting[15];
	break;
	case "ทันตแพทย์":
	$depart=$row_setting[16];
	break;
	case "เภสัชกร";
	$depart=$row_setting[17];
	break;	
	}

//========= วันนัด ==========//
mysql_select_db($database_hos, $hos);
$query_oapp = "select concat(date_format(nextdate,'%d/%m/'),(date_format(nextdate,'%Y')+543)) as nextdate,DATEDIFF(nextdate,'".$row_s_patient['vstdate']."') as date_diff,nextdate as nextdate1 from oapp where vn='".$row_s_patient['vn']."'";
$oapp = mysql_query($query_oapp, $hos) or die(mysql_error());
$row_oapp = mysql_fetch_assoc($oapp);
$totalRows_oapp = mysql_num_rows($oapp);

mysql_select_db($database_hos, $hos);
$query_oapp1 = "select concat(date_format(nextdate,'%d/%m/'),(date_format(nextdate,'%Y')+543)) as nextdate,concat(date_format(v.vstdate,'%d/%m/'),(date_format(v.vstdate,'%Y')+543)) as vstdate1,DATEDIFF(nextdate,'".$row_s_patient['vstdate']."') as date_diff,DATEDIFF(nextdate,v.vstdate) as date_diff2 from vn_stat v left outer join oapp o on o.vn=v.vn where v.hn='".$row_s_patient['hn']."' and v.vn!='".$row_s_patient['vn']."' order by v.vstdate DESC limit 1";
$oapp1 = mysql_query($query_oapp1, $hos) or die(mysql_error());
$row_oapp1 = mysql_fetch_assoc($oapp1);
$totalRows_oapp1 = mysql_num_rows($oapp1);

//ค้นหา ADR
mysql_select_db($database_hos, $hos);
$query_adr_check = "select a.*,date_format(a.check_date,'%d/%m/%Y') as checkdate,d.name as doctorname from ".$database_kohrx.".kohrx_adr_check a left outer join doctor d on d.code=a.doctorcode where hn='".$row_s_patient['hn']."' order by check_date DESC limit 1";
$adr_check = mysql_query($query_adr_check, $hos) or die(mysql_error());
$row_adr_check = mysql_fetch_assoc($adr_check);
$totalRows_adr_check = mysql_num_rows($adr_check);

$adr_check_date=$row_adr_check['checkdate'];
$adr_check_doctor=$row_adr_check['doctorname'];

mysql_free_result($adr_check);

//คำนวณ IBW
//ส่วนสูงที่เกิน 5 ฟุต
if($row_screen['height']>152.4){ $over5=number_format((($row_screen['height']-152.4)/2.54)*2.3,2); } else{ $over5=0;}

//เด็ก <13
if($row_s_patient['age_y']<=6){
	$ibw=($row_s_patient['age_y']*2)+8;
	}
else if($row_s_patient['age_y']>6&&$row_s_patient['age_y']<=12){ $ibw=(($row_s_patient['age_y']*7)-5)/2;}
//ผู้ใหญ่
else if($row_s_patient['age_y']>15){
	if($row_s_patient['sex']==1){ $ibw=50+$over5; }
	else if($row_s_patient['sex']==2){$ibw=45.5+$over5;}
	}

mysql_select_db($database_hos, $hos);
$query_rs_respondent = "select * from ".$database_kohrx.".kohrx_adr_check_respondent";
$rs_respondent = mysql_query($query_rs_respondent, $hos) or die(mysql_error());
$row_rs_respondent = mysql_fetch_assoc($rs_respondent);
$totalRows_rs_respondent = mysql_num_rows($rs_respondent);

mysql_select_db($database_hos, $hos);
$query_rs_answer = "select * from ".$database_kohrx.".kohrx_adr_check_answer";
$rs_answer = mysql_query($query_rs_answer, $hos) or die(mysql_error());
$row_rs_answer = mysql_fetch_assoc($rs_answer);
$totalRows_rs_answer = mysql_num_rows($rs_answer);

mysql_select_db($database_hos, $hos);
$query_rs_edit_adr = "select * from ".$database_kohrx.".kohrx_adr_check where vn='".$vn."'";
$rs_edit_adr = mysql_query($query_rs_edit_adr, $hos) or die(mysql_error());
$row_rs_edit_adr = mysql_fetch_assoc($rs_edit_adr);
$totalRows_rs_edit_adr = mysql_num_rows($rs_edit_adr);

mysql_select_db($database_hos, $hos);
$query_rs_doctor = "SELECT d.name,o.doctorcode FROM ".$database_kohrx.".kohrx_rx_person o left outer join doctor d on d.code=o.doctorcode ";
$rs_doctor = mysql_query($query_rs_doctor, $hos) or die(mysql_error());
$row_rs_doctor = mysql_fetch_assoc($rs_doctor);
$totalRows_rs_doctor = mysql_num_rows($rs_doctor);

mysql_select_db($database_hos, $hos);
$query_pttyp = "SELECT name,paidst,pttype from pttype WHERE pttype ='".$row_s_patient['pttype']."'";
$pttyp = mysql_query($query_pttyp, $hos) or die(mysql_error());
$row_pttyp = mysql_fetch_assoc($pttyp);
$totalRows_pttyp = mysql_num_rows($pttyp);

mysql_select_db($database_hos, $hos);
$query_rs_visit = "select vstdate,an,concat(date_format(vstdate,'%d/%m/'),(date_format(vstdate,'%Y')+543)) as vstdate1,vn from opitemrece o where ifnull(item_type,'')!='P' and icode like '1%'  and hn='".$hn."' and (o.vstdate <'".$vstdate."' or o.vn<'".$vn."') group by vstdate  order by vstdate DESC";
//echo $query_rs_visit;
$rs_visit = mysql_query($query_rs_visit, $hos) or die(mysql_error());
$row_rs_visit = mysql_fetch_assoc($rs_visit);
$totalRows_rs_visit = mysql_num_rows($rs_visit);

//========= queue ============//
mysql_select_db($database_hos, $hos);
$query_queued = "SELECT queue,q_express from ".$database_kohrx.".kohrx_queued where substr(queue_datetime,1,10)='".$vstdate."' and room_id='".$row_rs_channel['room_id']."' and hn='".$hn."' order by queue DESC limit 1";
$rs_queued = mysql_query($query_queued, $hos) or die(mysql_error());
$row_rs_queued = mysql_fetch_assoc($rs_queued);
$totalRows_rs_queued = mysql_num_rows($rs_queued);

	$queue=$row_rs_queued['queue'];
	$q_express=$row_rs_queued['q_express'];

mysql_free_result($rs_queued);

//========= drug interaction ============//
mysql_select_db($database_hos, $hos);
$query_di = "SELECT vn from drug_interaction_incident where vn='".$vn."'";
$rs_di = mysql_query($query_di, $hos) or die(mysql_error());
$row_rs_di = mysql_fetch_assoc($rs_di);
$totalRows_rs_di = mysql_num_rows($rs_di);
$di=$totalRows_rs_di;

    if($di<>0){
        echo "<script>drug_interaction_show('".$vn."');</script>";
    }

mysql_free_result($rs_di);

mysql_select_db($database_hos, $hos);
$query_rs_kskdepart = "select * from kskdepartment order by depcode desc";
$rs_kskdepart = mysql_query($query_rs_kskdepart, $hos) or die(mysql_error());
$row_rs_kskdepart = mysql_fetch_assoc($rs_kskdepart);
$totalRows_rs_kskdepart = mysql_num_rows($rs_kskdepart);
?>