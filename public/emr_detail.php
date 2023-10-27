<?php ob_start();?>
<?php session_start();?>
<?php if(($_SESSION['r_opd']!='Y') and ($_SESSION['r_finance']!='Y')){
	echo "คุณไม่ได้รับสิทธิ์ให้ใช้งานในส่วนนี้";
	exit();
	} ?>
<?php require_once('Connections/hos.php'); ?>
<?php 
$get_ip=$_SERVER["REMOTE_ADDR"];

include('include/function.php');

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

mysql_select_db($database_hos, $hos);
$query_s_patient = "SELECT concat(p.pname,p.fname,'  ',p.lname) as patient_name,p.hn,v.age_y,v.age_m,concat(p.addrpart,'  ม. ',p.moopart,'  ',t.full_name) as thaiaddress,v.vstdate,v.vn,ov.pttype,ov.doctor,v.pdx,p.cid,v.pttypeno,v.vstdate,ov.vsttime,v.pttypeno FROM patient p  left outer join pname s on s.name=p.pname left outer join thaiaddress t on t.chwpart=p.chwpart and t.tmbpart=p.tmbpart and t.amppart=p.amppart left outer join vn_stat v on v.hn=p.hn left outer join ovst ov on ov.vn=v.vn WHERE v.vn='".$_GET['vn']."' ORDER BY v.vstdate DESC LIMIT 1";
$s_patient = mysql_query($query_s_patient, $hos) or die(mysql_error());
$row_s_patient = mysql_fetch_assoc($s_patient);
$totalRows_s_patient = mysql_num_rows($s_patient);

if($row_s_patient['vstdate']<'2010-01-01'){
$table="opitemrece_arc";	
	}
if($row_s_patient['vstdate']>='2010-01-01'){
$table="opitemrece";	
	}

mysql_select_db($database_hos, $hos);
$query_screen = "select bpd,bps,bw,cc,pe,pulse,temperature from opdscreen where vn='".$row_s_patient['vn']."'";
$screen = mysql_query($query_screen, $hos) or die(mysql_error());
$row_screen = mysql_fetch_assoc($screen);
$totalRows_screen = mysql_num_rows($screen);

mysql_select_db($database_hos, $hos);
$query_s_drug = "select o.hos_guid,o.icode,substring(o.icode,1,1) as scode,o.income,concat(s.name,' ',s.strength,' ',s.units) as drugname,  o.qty, o.unitprice ,sum(sum_price) as totprice ,  d.drugusage,d.code,d.name1,d.name2,d.name3,d.shortlist,dt.name as doctor_name  , k.department as dep_name ,u.name1,u.name2,u.name3,i.name as income_name,o.vn,dc.dosage_min,dc.dosage_max,dc.dose_perunit,d.ccperdose,d.iperday,concat(sp.name1,sp.name2,sp.name3) as sp_name,sp.sp_use
from ".$table." o 
left outer join sp_use sp on sp.sp_use=o.sp_use
left outer join s_drugitems s on s.icode=o.icode
left outer join ".$database_kohrx.".kohrx_drugitems_calculate dc on dc.icode=s.icode  
left outer join drugusage d on d.drugusage=o.drugusage  
left outer join doctor dt on dt.code=o.doctor  
left outer join kskdepartment k on k.depcode=o.dep_code 
left outer join sp_use u on u.sp_use = o.sp_use  left outer join income i on i.income = o.income
 left outer join ovst ov on ov.vn=o.vn  
where ov.vn='".$_GET['vn']."'  
group by o.hos_guid,o.icode,s.name,s.strength,s.units,o.qty,o.unitprice,d.drugusage,d.code,d.name1,d.name2,d.name3,d.shortlist,dt.name,k.department,u.name1,u.name2,u.name3   order by scode,s.name ";
$s_drug = mysql_query($query_s_drug, $hos) or die(mysql_error());
$row_s_drug = mysql_fetch_assoc($s_drug);
$totalRows_s_drug = mysql_num_rows($s_drug);

mysql_select_db($database_hos, $hos);
$query_pttyp = "SELECT name,paidst,pttype from pttype WHERE pttype ='".$row_s_patient['pttype']."'";
$pttyp = mysql_query($query_pttyp, $hos) or die(mysql_error());
$row_pttyp = mysql_fetch_assoc($pttyp);
$totalRows_pttyp = mysql_num_rows($pttyp);

mysql_select_db($database_hos, $hos);
$query_s_doctor = "select name from doctor where code='".$row_s_patient['doctor']."'";
$s_doctor = mysql_query($query_s_doctor, $hos) or die(mysql_error());
$row_s_doctor = mysql_fetch_assoc($s_doctor);
$totalRows_s_doctor = mysql_num_rows($s_doctor);

mysql_select_db($database_hos, $hos);
$query_rx_doctor = "select rx_time from rx_doctor where vn='".$row_s_patient['vn']."'";
$rx_doctor = mysql_query($query_rx_doctor, $hos) or die(mysql_error());
$row_rx_doctor = mysql_fetch_assoc($rx_doctor);
$totalRows_rx_doctor = mysql_num_rows($rx_doctor);

mysql_select_db($database_hos, $hos);
$query_oapp = "select nextdate,DATEDIFF(nextdate,'".$row_s_patient['vstdate']."') as date_diff from oapp where vn='".$row_s_patient['vn']."'";
$oapp = mysql_query($query_oapp, $hos) or die(mysql_error());
$row_oapp = mysql_fetch_assoc($oapp);
$totalRows_oapp = mysql_num_rows($oapp);

mysql_select_db($database_hos, $hos);
$query_s_pdx = "select code,name from icd101 where code='".$row_s_patient['pdx']."'";
$s_pdx = mysql_query($query_s_pdx, $hos) or die(mysql_error());
$row_s_pdx = mysql_fetch_assoc($s_pdx);
$totalRows_s_pdx = mysql_num_rows($s_pdx);

mysql_select_db($database_hos, $hos);
$query_allergy = "select hn,report_date,agent,symptom,reporter from opd_allergy where hn='".$row_s_patient['hn']."' order by report_date DESC";
$allergy = mysql_query($query_allergy, $hos) or die(mysql_error());
$row_allergy = mysql_fetch_assoc($allergy);
$totalRows_allergy = mysql_num_rows($allergy);

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<?php include('java_css_file.php'); ?>
</head>
<!-- fontawesome -->
<link rel="stylesheet" href="include/fontawesome/css/all.css"/>
<!-- kohrx -->
<link rel="stylesheet" href="include/kohrx/css/kohrx.css"/>
<script>
    $(document).ready(function(){
    });
</script>

</head>

<body>
<div class=" container<?php if($full_screen!="Y" ||$full_screen==NULL ){echo "-fluid";} ?>">
  <div class="row">
    <div class="col" >
   	  <div class="row">
    
    <div class="col-md-5" style="padding:5px;">
<!-- patient profile -->

  <div id='patient_profile'>
  <div class="card">
  <div class="card-header" style="font-size:18px; padding: 5px;">ข้อมูลทั่วไป</div>
	<div class="card-body" style="padding:10px; height: 200px;">
<table width="100%" border="0" cellpadding="5" cellspacing="0" class="grid_font" >
        <tr >
          <td colspan="2" align="left"   ><div style="font-size:25px ; font-weight:bolder; text-wrap:none"><nobr><?php echo $row_s_patient['patient_name']; ?>&nbsp;<a href="javascript:	alertload('caller_key.php?queue='+$('#recent_q').val()+'&room_id=<?php echo $rid; ?>','300','200');
" target="queue_caller" style="padding:0px;" ></a></td>
          </tr>
        <tr class="grid4">
          <td width="17%" align="left"   >วันที่มา</td>
          <td width="83%" align="left"  class="border-right-gray"><?php echo dateThai($row_s_patient['vstdate']); ?> &nbsp;เวลา <?php echo $row_s_patient['vsttime']; ?></td>
        </tr>
        <tr class="grid4">
          <td   >อายุ </td>
          <td bgcolor="#FFFFFF" class="border-right-gray"><?php echo $row_s_patient['age_y']; ?> ปี <?php echo $row_s_patient['age_m']; ?> เดือน</td>
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

</div>

<!-- patient vital sign -->
    <div class="col" style="padding:5px;">
  <div class="card" >
  <div class="card-header" style="font-size:18px; padding:5px;">ข้อมูลการซักประวัติและการตรวจรักษา</div>
  <div class="card-body grid_font" style="padding:10px; height: 200px;">
      <div>
        <nobr>BT. 
            <span class="badge  badge-secondary font12" style="width:50px;"><?php echo str_replace('.00','',number_format($row_screen['temperature'],2)); ?></span>
         </nobr>
            องศา&nbsp;          
            <nobr>         
			BW. 
            <a href="javascript:alertload('bw_chart.php?graph=bw&amp;hn=<?php echo $hn; ?>','90%','90%');" ><span class="badge  badge-secondary font12"><?php echo str_replace('.00','',number_format($row_screen['bw'],2)); ?></span></a>&nbsp;ก.ก. &nbsp; </nobr>
        	<nobr>         
IBW&nbsp;<span class="badge  badge-secondary font12"><?php echo $ibw; ?></span>&nbsp;ก.ก.&nbsp;</nobr>
	</div>
    <div style="padding-top:5px">
		<nobr>
        HR.
            <span class="badge  badge-secondary font12"><?php print number_format($row_screen['hr']); ?></span>&nbsp;Pulse 
            <a href="javascript:alertload('pulse_chart.php?graph=pulse&amp;hn=<?php echo $hn; ?>','90%','90%');" ><span class="badge  badge-secondary font12"><?php print number_format($row_screen['pulse']); ?></span></a>&nbsp;
            </nobr>
         <nobr>                     
             BP.
          <a href="javascript:alertload('bp_chart.php?graph=bp&hn=<?php echo $hn; ?>','90%','90%');"><span class="badge  badge-secondary font12"><?=number_format($row_screen['bps'])."/".number_format($row_screen['bpd']); ?></span></a>
          </nobr>
            
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

      <div class="row vital-box" >
          <div class="col-md-2" style="font-weight: bold"><nobr>Rx time</nobr></div>
          <div class="col"><?php echo $row_rx_doctor['rx_time']; ?></div>
      </div>
      <div class="row vital-box" >
          <div class="col-md-2" style="font-weight: bold"><nobr>นัดถัดไป</nobr></div>
          <div class="col"><?php if($totalRows_oapp<>0){ echo dateThai($row_oapp['nextdate']); ?> = </span><?php echo $row_oapp['date_diff']." วัน"; }else { echo "-"; } ?></div>
      </div>
      
    </div>
  <!-- card body vital sign -->
  </div>
  <!-- card vital sign -->

    </div>
<!-- patient vital sign -->       
        </div>



  </div>
<!-- row -->
</div>
<!-- row -->
<?php if($totalRows_allergy!=0){ ?>
<!-- row -->
<div class="row">
<!-- row -->
    <div class="col p-1">
        <div class="card border-0">                
        <div class="card-header bg-gray2"><span class="card-title"><i class="fas fa-allergies font20"></i>&ensp;รายการยาที่แพ้</span></div>
            <div class="card-body bg-danger font16 font-weight-bold text-white border-0 rounded-bottom p-2">
            <?php $a=0; do{ $a++; echo $row_allergy['agent']; if($a!=$totalRows_allergy){ echo " , ";} } while($row_allergy = mysql_fetch_assoc($allergy)); ?>
            </div>
        </div>
    </div>
<!-- row -->
</div>
<!-- row -->
<?php } ?>
<!-- row -->
<div class="row">
<!-- row -->
    <div class="col" style="padding: 5px;">
    <div class="card">
        <div class="card-header " ><span class="card-title"><i class="fas fa-laptop-medical font20"></i>&ensp;รายการเวชภัณฑ์</span></div>
        <div class="card-body" style="padding: 0px;">
        <?php if($totalRows_s_drug<>0){ ?>
        <table  id="drug-table" class="table table-striped table-sm" >
            <thead >
            <tr>
                <th  align="center" style="border-top:0px; border-bottom:0px; " >ลำดับ</th>
                <th align="center" style="border-top:0px;border-bottom:0px;" >ชื่อยา</td>
                <td align="center" style="border-top:0px;" >วิธีใช้</th>
                <td align="center" style="border-top:0px;" >จำนวน </th>
                <td align="center" style="border-top:0px;" ></tr>
            </thead>
            <tbody class="thfont font12">
              <?php $i=0; do { $i++;

?>
              <tr class="grid4">
                <td  align="center" ><?=$i; ?></td>
                <td  ><?php echo $row_s_drug['drugname']; ?></td>
                <td align="left" ><?php if($row_s_drug['sp_use']=="") {echo $row_s_drug['shortlist']; } else { echo $row_s_drug['sp_name']; } ?></td>
                <td align="center" ><?php echo $row_s_drug['qty']; ?></td>
                <td align="center" ></td>
                </tr>
              <?php } while ($row_s_drug = mysql_fetch_assoc($s_drug)); ?>
              </table>
         <?php } ?>
            </tbody>
            <!-- card body -->
            </div>
            <!--card body -->
        <!--card -->
        </div>
        <!--card -->
        
    </div>
<!-- row -->
</div>
<!-- row -->

</body>
</html>
<?php 
mysql_free_result($s_patient);

mysql_free_result($screen);

mysql_free_result($pttyp);

mysql_free_result($s_doctor);


mysql_free_result($oapp);

mysql_free_result($s_pdx);

mysql_free_result($allergy);



?>