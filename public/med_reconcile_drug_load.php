<?php require_once('Connections/hos.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

include('include/function.php');


if($_GET['an']==""){
mysql_select_db($database_hos, $hos);
$query_rs_drug = "select   o.hos_guid,concat(d.name,' ',d.strength) as drugname,u.shortlist,s.sp_use,o.qty,s.name1,s.name2,s.name3,o.hn
from opitemrece o 
left outer join drugitems d on d.icode=o.icode 
left outer join drugusage u on u.drugusage=o.drugusage
left outer join sp_use s on s.sp_use=o.sp_use
where o.vn='".$_GET['vn']."'  and o.icode like '1%' order by o.item_no";
$rs_drug = mysql_query($query_rs_drug, $hos) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);
}
else {
mysql_select_db($database_hos, $hos);
$query_rs_drug = "select o.hos_guid,concat(d.name,' ',d.strength) as drugname,u.shortlist,s.sp_use,o.qty,s.name1,s.name2,s.name3,o.hn,i.order_type from opitemrece o left outer join drugitems d on d.icode=o.icode left outer join drugusage u on u.drugusage=o.drugusage left outer join sp_use s on s.sp_use=o.sp_use left outer join ipt_order_no i on o.order_no=i.order_no where o.an='".$_GET['an']."' and o.icode like '1%'";/* and i.order_type='Hme'*/ 
$query_rs_drug.="group by o.hos_guid order by i.order_type,o.item_no";
$rs_drug = mysql_query($query_rs_drug, $hos) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);
}

mysql_select_db($database_hos, $hos);
$query_s_patient = "SELECT concat(p.pname,p.fname,'  ',p.lname) as patient_name,p.hn,v.age_y,v.age_m,concat(p.addrpart,'  ม. ',p.moopart,'  ',t.full_name) as thaiaddress,v.vstdate,v.vn,ov.pttype,ov.doctor,v.pdx,p.cid,v.pttypeno,v.vstdate,ov.vsttime,v.pttypeno FROM patient p  left outer join pname s on s.name=p.pname left outer join thaiaddress t on t.chwpart=p.chwpart and t.tmbpart=p.tmbpart and t.amppart=p.amppart left outer join vn_stat v on v.hn=p.hn left outer join ovst ov on ov.vn=v.vn WHERE v.vn='".$_GET['vn']."' and v.vstdate='".$_GET['vstdate']."' ORDER BY v.vstdate DESC LIMIT 1";
$s_patient = mysql_query($query_s_patient, $hos) or die(mysql_error());
$row_s_patient = mysql_fetch_assoc($s_patient);
$totalRows_s_patient = mysql_num_rows($s_patient);

mysql_select_db($database_hos, $hos);
$query_screen = "select bpd,bps,bw,cc,pe,pulse,temperature from opdscreen where vn='".$row_s_patient['vn']."'";
$screen = mysql_query($query_screen, $hos) or die(mysql_error());
$row_screen = mysql_fetch_assoc($screen);
$totalRows_screen = mysql_num_rows($screen);

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
$query_s_pdx = "select code,name from icd101 where code='$row_s_patient[pdx]'";
$s_pdx = mysql_query($query_s_pdx, $hos) or die(mysql_error());
$row_s_pdx = mysql_fetch_assoc($s_pdx);
$totalRows_s_pdx = mysql_num_rows($s_pdx);

mysql_select_db($database_hos, $hos);
$query_allergy = "select hn,report_date,agent,symptom,reporter from opd_allergy where hn='".$row_s_patient['hn']."' order by report_date DESC";
$allergy = mysql_query($query_allergy, $hos) or die(mysql_error());
$row_allergy = mysql_fetch_assoc($allergy);
$totalRows_allergy = mysql_num_rows($allergy);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script>
$(document).ready(function() {
	$("#check_all").click(function () {
     $('input:checkbox').not(this).prop('checked', this.checked);
	 if ($('.icode').is(':checked')) {
		$('#save').show();
	 }
	 else{
 	$('#save').hide();	 
	 }
 	});
    
	$('.icode').change(function()
      {	 
	 	if ($('.icode').is(':checked')) {
		$('#save').show();	  
		}
		else{
	 	$('#save').hide();
		$('#check_all').prop('checked', false);	 			
		}
	  });
});
</script>
</head>

<body>
<input type="hidden" id="vn" value="<?php echo $_GET['vn']; ?>" />
<div class="row">
<div class="col" style="padding:10px; padding-left:25px; padding-right: 0px;">
<!-- patient profile -->

  <div id='patient_profile'>
  <div class="card">
  <div class="card-header" style="font-size:18px; padding: 5px;">ข้อมูลทั่วไป</div>
	<div class="card-body" style="padding:10px; height: 200px;">
<table width="100%" border="0" cellpadding="5" cellspacing="0" class="grid_font" >
        <tr >
          <td colspan="2" align="left" ><div style="font-size:25px ; font-weight:bolder; text-wrap:none"><nobr><?php echo $row_s_patient['patient_name']; ?></div></td>
          </tr>
        <tr class="grid4">
          <td width="17%" align="left"   >วันที่มา</td>
          <td width="83%" align="left"  ><?php echo dateThai($row_s_patient['vstdate']); ?> &nbsp;เวลา <?php echo $row_s_patient['vsttime']; ?></td>
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

</div>
<div class="col" style="padding-top:10px; padding-right:25px;">
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
            <span class="badge  badge-secondary font12"><?php echo str_replace('.00','',number_format($row_screen['bw'],2)); ?></span>&nbsp;ก.ก. &nbsp; </nobr>
        	<nobr>         
IBW&nbsp;<span class="badge  badge-secondary font12"><?php echo $ibw; ?></span>&nbsp;ก.ก.&nbsp;</nobr>
	</div>
    <div style="padding-top:5px">
		<nobr>
        HR.
            <span class="badge  badge-secondary font12"><?php print number_format($row_screen['hr']); ?></span>&nbsp;Pulse 
            <span class="badge  badge-secondary font12"><?php print number_format($row_screen['pulse']); ?></span>&nbsp;
            </nobr>
         <nobr>                     
             BP.
         <span class="badge  badge-secondary font12"><?=number_format($row_screen['bps'])."/".number_format($row_screen['bpd']); ?></span>
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
</div>
<?php if($totalRows_rs_drug<>0){ ?>

<div style="padding: 10px;">
<form id="form1" name="form1" method="post" action="">
  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="table font14 table-striped table-hover">
    <tr>
      <td align="center">
        <input name="check_all" type="checkbox" id="check_all" checked="checked" />
		#</td>
      <td align="center">รายชื่อยา
      <input name="hn" type="hidden" id="hn" value="<?php echo $row_rs_drug['hn']; ?>" />
      <input name="vstdate" type="hidden" id="vstdate" value="<?php echo $_GET['vstdate']; ?>" />
      <input name="vstdate2" type="hidden" id="vstdate2" value="<?php echo $_GET['vstdate2']; ?>" /></td>
      <td align="center">วิธีใช้</td>
      <td align="center">จำนวน</td>
	  <?php if($_GET['an']!=""){ ?>
        <td align="center">ประเภทยา</td>
	  <?php } ?>
    </tr>
    <?php $i=0; do{  $i++; ?>
    <tr>
      <td align="center"><input name="icode[]" type="checkbox" class="icode" id="icode[]" value="<?php echo $row_rs_drug['hos_guid']; ?>" checked="checked" />
        <label for="icode[]"></label>
        <?php echo $i; ?></td>
      <td align="center"><?php echo $row_rs_drug['drugname']; ?></td>
      <td align="center"><?php if($row_rs_drug['sp_use']!=""){ echo $row_rs_drug['name1'].' '.$row_rs_drug['name2'].' '.$row_rs_drug['name3'];  } else {echo $row_rs_drug['shortlist'];} ?></td>
      <td align="center"><?php echo $row_rs_drug['qty']; ?></td>
	  <?php if($_GET['an']!=""){ ?>
      <td align="center"><?php if($row_rs_drug['order_type']=="Hme"){ echo "<span class='badge badge-danger font14'>HM</span>"; } else { echo "<span class='badge badge-secondary font14'>Adm</span>"; } ?></td>
	  <?php } ?>
	  </tr>
    <?php } while($row_rs_drug = mysql_fetch_assoc($rs_drug)); ?>
  </table>
  <div class="mt-3 text-right"><input type="submit" value="บันทึก" class="btn btn-primary" id="save" name="save" /></div>
</form>
</div>
<?php } else{ ?>
<div style="padding: 20px;" class="font20"><i class="far fa-times-circle font20"></i>&ensp;ไม่มีรายการยา</div>
<?php } ?>    
</body>
</html>
<?php
mysql_free_result($rs_drug);
mysql_free_result($s_patient);
mysql_free_result($screen);

mysql_free_result($pttyp);

mysql_free_result($s_doctor);


mysql_free_result($oapp);

mysql_free_result($s_pdx);

mysql_free_result($allergy);

?>
