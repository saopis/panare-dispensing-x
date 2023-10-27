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
?>
<? 
include('include/function.php');

//หา an จาก search
mysql_select_db($database_hos, $hos);
$query_rs_search = "select * from an_stat where an='".$_GET['an']."'";
$rs_search = mysql_query($query_rs_search, $hos) or die(mysql_error());
$row_rs_search = mysql_fetch_assoc($rs_search);
$totalRows_rs_search = mysql_num_rows($rs_search);			


//หาข้อมูลทั่วไปของผู้ป่วย
mysql_select_db($database_hos, $hos);
$query_rs_patient = "SELECT i.bedno,a.hn,a.an,concat(p.pname,p.fname,'  ',p.lname) as patientname,a.ward,a.age_y,a.age_m,a.age_d,os.bw,w.name,ptt.name as pttypename,a.regdate,a.dchdate,icd.name as dxname ,dd.name as doctorname FROM an_stat a left outer join pttype ptt on ptt.pttype=a.pttype left outer join opdscreen os on os.vn=a.vn left outer join patient p on p.hn=a.hn left outer join iptadm i on i.an=a.an left outer join ward w on w.ward=a.ward left outer join icd101 icd on icd.code=a.pdx left join doctor dd on dd.code=a.dx_doctor WHERE a.an ='".$row_rs_search['an']."'";
$rs_patient = mysql_query($query_rs_patient, $hos) or die(mysql_error());
$row_rs_patient = mysql_fetch_assoc($rs_patient);
$totalRows_rs_patient = mysql_num_rows($rs_patient);

// หาผลต่างของวันนับจากวันเข้ารักษา
$datediff = DateDiff($row_rs_patient['regdate'],date('Y-m-d'))+1;

$day1=substr($row_rs_patient['regdate'],8,2);
if(strpos($day1,'0')==0 && strpos($day1,'0') != false ){ 
 $newday = intval(substr($day1,1));
}
else {
$newday=$day1;	
}

//หาวันย้อนหลัง 30 day
mysql_select_db($database_hos, $hos);
$query_rs_interval = "SELECT ADDDATE(CURDATE(), -30) as diffdate;";
$rs_interval = mysql_query($query_rs_interval, $hos) or die(mysql_error());
$row_rs_interval = mysql_fetch_assoc($rs_interval);
$totalRows_rs_interval = mysql_num_rows($rs_interval);


//หาข้อมูลยาใน profile
mysql_select_db($database_hos, $hos);
$query_rs_medplan = " select m1.icode,concat(s.name,' ',s.strength,' ',s.units) as name ,d.shortlist,m1.orderstatus,concat('>',sp.name1,sp.name2,sp.name3) as sp_use_name,m1.sp_use,m1.med_plan_number
from medplan_ipd m1   
left outer join s_drugitems s on s.icode=m1.icode   
left outer join drugusage d on d.drugusage=m1.drugusage left outer join sp_use sp on sp.sp_use=m1.sp_use   
where m1.an='".$row_rs_patient['an']."'  and m1.icode like '1%'   order by m1.orderstatus,m1.orderdate ";
$rs_medplan = mysql_query($query_rs_medplan, $hos) or die(mysql_error());
$row_rs_medplan = mysql_fetch_assoc($rs_medplan);
$totalRows_rs_medplan = mysql_num_rows($rs_medplan);


//หาวันที่ให้ยาทั้งหมด
mysql_select_db($database_hos, $hos);
$query_rs_meddate = "select order_date from medpay_ipd where an ='".$row_rs_patient['an']."' group by order_date order by order_date ASC";
$rs_meddate = mysql_query($query_rs_meddate, $hos) or die(mysql_error());
$row_rs_meddate = mysql_fetch_assoc($rs_meddate);
$totalRows_rs_meddate = mysql_num_rows($rs_meddate);


mysql_select_db($database_hos, $hos);
$query_allergy = "select hn,report_date,agent,symptom,reporter from opd_allergy where hn='".$row_rs_patient['hn']."' order by report_date DESC";
$allergy = mysql_query($query_allergy, $hos) or die(mysql_error());
$row_allergy = mysql_fetch_assoc($allergy);
$totalRows_allergy = mysql_num_rows($allergy);



mysql_select_db($database_hos, $hos);
$query_s_medpay = "SELECT i.an,i.rxdate,DATE_FORMAT(i.rxdate,'%e/%c/%Y') as rxdate2,i.order_no,i.order_locked,i.order_type,i.entry_staff,i.rxtime  ,w.name as ward_name  ,i.item_count, i.confirm_prepare,i.confirm_pay, i.amount,t.name as medication_type_name,i.day_queue,i.rxdate as maxdate FROM ipt_order_no i  left outer join ward w on w.ward = i.ward  left outer join medpay_ipd_head m on m.med_rx_number = i.order_no  left outer join ipt_medication_type t on t.code = m.ipt_medication_type WHERE i.an='".$_GET['an']."'  and i.order_type in ('IRx','Hme') ORDER BY i.rxdate desc,i.rxtime desc";
$s_medpay = mysql_query($query_s_medpay, $hos) or die(mysql_error());
$row_s_medpay = mysql_fetch_assoc($s_medpay);
$totalRows_s_medpay = mysql_num_rows($s_medpay);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php include('java_css_file.php'); ?>

<script>
$(document).ready(function(e) {
              $('#chart-drug').load('emr_detail_ipd_med.php?an=<?php echo $_GET['an']; ?>', function(responseTxt, statusTxt, xhr){
               if(statusTxt == "success")
                //alert("External content loaded successfully!");
                $('#indicator_medpay').fadeOut(1000);
                if(statusTxt == "error")
                alert("Error: " + xhr.status + ": " + xhr.statusText);    
               });
    
    });

function medpay(order_no,an){
	          $('#indicator_medpay').show();
              $('#chart-drug').load('emr_detail_ipd_med.php?order_no='+order_no+'&an='+an, function(responseTxt, statusTxt, xhr){
               if(statusTxt == "success")
                //alert("External content loaded successfully!");
                $('#indicator_medpay').fadeOut(1000);
                if(statusTxt == "error")
                alert("Error: " + xhr.status + ": " + xhr.statusText);    
               });

	}
</script>
</head>

<body>
<div class="card mt-1">
	<div class="card-header">
    	<span class="card-title font-weight-bold"><i class="fas fa-clinic-medical font20"></i>&ensp;General Information</span>
    </div>
    <div class="card-body p-2">
	<!-- row main -->
    <div class="row">
        <div class="col">
    <!-- row 1 -->
    <div class="row">
    	<div class="col"><nobr><strong>ชื่อ:</strong> <?php echo "$row_rs_patient[patientname]"; ?></nobr></div>
    	<div class="col"><strong>HN:</strong> <?php echo "$row_rs_patient[hn]"; ?></div>
    	<div class="col"><strong>AN:</strong> <?php echo "$row_rs_patient[an]"; ?></div>
    	<div class="col"><strong>Bed No.</strong>: <?php echo "$row_rs_patient[bedno]"; ?></div>
    	<div class="col"><strong>Ward :</strong> <?php echo "$row_rs_patient[name]"; ?></div>
    </div>
    <!-- row 1 -->
    <!-- row 2 -->
    <div class="row">
    	<div class="col"><nobr><strong>Reg Date :</strong> <?php echo  dateThai($row_rs_patient['regdate']); ?></nobr></div>
    	<div class="col"><nobr><strong>D/C:</strong> <?php echo dateThai($row_rs_patient['dchdate']); ?></nobr></div>
    	<div class="col"><nobr><strong>สิทธิ์&nbsp;:</strong> <?php echo "$row_rs_patient[pttypename]"; ?></nobr></div>
    	<div class="col"></div>        
    </div>
    <!-- row 2 -->
    <!-- row 3 -->
    <div class="row">
    	<div class="col"><strong>อายุ:</strong> <?php echo "$row_rs_patient[age_y] y."."$row_rs_patient[age_m] m."; ?></div>
    	<div class="col"><strong>น้ำหนัก:</strong>
          <?=number_format($row_rs_patient['bw'])."กก."; ?></div>
    	<div class="col"></div>
    	<div class="col"></div>
    	<div class="col"></div>
    	<div class="col"></div>
    </div>
    <!-- row 3 -->
    <!-- row 4 -->
    <div class="row">
    	<div class="col"><nobr><strong>Diag:</strong> <?php echo "$row_rs_patient[dxname]"; ?></nobr></div>
    <div class="col"><nobr><strong>แพทย์ </strong>: <?php echo "$row_rs_patient[doctorname]"; ?></nobr></div>
    </div>
    <!-- row 4 -->        
        </div>
    </div>
    <!-- row main -->
    </div>
</div>
    
<?php if($totalRows_allergy<>0){ ?>
<div class="card mt-2">                
        <div class="card-header"><span class="card-title font-weight-bold"><i class="fas fa-allergies font20"></i>&ensp;Drug Allergy</span></div>
            <div class="card-body font16 font-weight-bold rounded-bottom p-2">
            <?php $a=0; do{ $a++; echo $row_allergy['agent']; if($a!=$totalRows_allergy){ echo " , ";} } while($row_allergy = mysql_fetch_assoc($allergy)); ?>
            </div>
        </div>
<?php } ?>
    
<!-- drug profile -->
<div class="card mt-2">
	<div class="card-header"><span class="card-title font-weight-bold"><i class="fas fa-pills font20"></i>&ensp;Drug Profile</span></div>
    <div class="card-body p-1">
<table  class="table_head_small talbe table-striped table-sm table-bordered" cellspacing="0px"  >
      <tr>
        <td width="25" align="center" bgcolor="#FFFFFF" style="border: 1px solid #FFF; border-bottom:1px solid #E4E4E4">&nbsp;</td>
        <td width="25" align="center" bgcolor="#FFFFFF" class="big_white16" style="border: 1px solid #FFF; border-bottom:1px solid #E4E4E4">&nbsp;</td>
        <td width="250" align="center" bgcolor="#FFFFFF" class="big_white16" style="border: 1px solid #FFF; border-bottom:1px solid #E4E4E4"><span class="badge badge-secondary w-100 p-1">รายการยา</span></td>
        <td width="200" align="center" bgcolor="#FFFFFF" class="big_white16" style="border: 1px solid #FFF; border-bottom:1px solid #E4E4E4"><span class="badge badge-secondary w-100 p-1">วิธีใช้</span></td>
        <?php  do {  ?>
        <td  align="center" bgcolor="#FFFFFF"  width="20" class="table_head_small_bord" style="border: 1px solid #FFF; border-bottom:1px solid #E4E4E4"><span class="badge badge-dark p-1"><?php echo substr($row_rs_meddate['order_date'],8,2)."/".substr($row_rs_meddate['order_date'],5,2); ?></span></td>
        <?php } while ($row_rs_meddate = mysql_fetch_assoc($rs_meddate)); ?>
      </tr>
      <tbody>
      <?php $n=0;do { $n++;
	mysql_select_db($database_hos, $hos);
$query_rs_meddate2 = "select m.order_date,m.icode,m.med_plan_number from medpay_ipd m";
$query_rs_meddate2.="  where m.an ='".$row_rs_patient['an']."' group by m.order_date order by m.order_date ASC";
$rs_meddate2 = mysql_query($query_rs_meddate2, $hos) or die(mysql_error());
$row_rs_meddate2 = mysql_fetch_assoc($rs_meddate2);
	?>
      <tr onmouseover="this.style.backgroundColor='#aaaaaa';" onmouseout="this.style.backgroundColor='';">
            <td align="center" class="bg-white"><?php echo $n; ?></td>
            <td bgcolor="#FFFFFF" class="font18 font-weight-bolder <?php if($row_rs_medplan['orderstatus']!="C"){ echo "text-danger"; } ?>"><?php print $row_rs_medplan['orderstatus']; ?></td>
        <td align="left" style="" class=""><input name="textfield2" type="text" class="table_head_small form-control-plaintext p-0" id="textfield2"  style="width:100%;" value="<?php echo $row_rs_medplan['name']; ?>"/></td>
        <td align="left"  style="" class=""><input name="textfield" type="text" class="table_head_small form-control-plaintext p-0 " id="textfield"  style="width:100%; border:1px #FFFFFF; " value="<?php if($row_rs_medplan['sp_use']=="") {echo $row_rs_medplan['shortlist']; } else { echo $row_rs_medplan['sp_use_name']; } ?>"/></td>
        <? do{ 
	mysql_select_db($database_hos, $hos);
$query_rs_order_qty = "select med_order_qty from medpay_ipd where icode='$row_rs_medplan[icode]' and med_plan_number='$row_rs_medplan[med_plan_number]' and  order_date='".$row_rs_meddate2['order_date']."' and an='".$row_rs_patient['an']."'  ";
$rs_order_qty = mysql_query($query_rs_order_qty, $hos) or die(mysql_error());
$row_rs_order_qty = mysql_fetch_assoc($rs_order_qty);
$totalRows_rs_order_qty = mysql_num_rows($rs_order_qty);
	?>
        <td  align="center" class="table_head_small_white" style="color:#000000"><?php echo "$row_rs_order_qty[med_order_qty]"; ?></td>
        <?php } while ($row_rs_meddate2 = mysql_fetch_assoc($rs_meddate2)); ?>
      </tr>
      <?php mysql_free_result($rs_order_qty); mysql_free_result($rs_meddate2);} while ($row_rs_medplan = mysql_fetch_assoc($rs_medplan)); ?>
      </tbody>
    </table>
    </div>
</div>
<!-- drug profile -->
<!-- drug order -->
<div class="row mt-2">
	<div class="col" style="-ms-flex: 0 0 260px;flex: 0 0 260px;">
   	<!-- chart -->
    <div class="card">
    	<div class="card-header p-1 text-center font-weight-bold"><span class="card-title thfont font12">วันที่สั่ง (order date)</span></div>
    	<div class="card-body p-0 ">
        <table width="100%" border="0" align="center" cellpadding="3" cellspacing="0" class="table table_head_small table-sm table-striped ">
          <tr>
            <td  align="center" >no.</td>
            <td  align="center" >ชนิด</td>
            <td  align="center" >วันที่่</td>
            <td  align="center" >เวลา</td>
            <td  align="center" >order_no</td>
          </tr>
          <?php $i=0; do { $i++; 
	  ?>
          <tr onclick="medpay('<?php echo $row_s_medpay['order_no']; ?>','<?php echo $row_s_medpay['an']; ?>');" style="cursor:pointer" >
            <td  align="center" ><font color="<?php echo $font; ?>"><?php echo $i; ?></font></td>
            <td align="center" ><font color="<?php echo $font; ?>">
              <?=$row_s_medpay['order_type']; ?>
            </font></td>
            <td align="center"  ><font color="<?php echo $font; ?>"><?=$row_s_medpay['rxdate2']; ?></font></td>
            <td align="center" ><font color="<?php echo $font; ?>">
              <?=$row_s_medpay['rxtime']; ?>
            </font></td>
            <td align="center" ><font color="<?php echo $font; ?>">
              <?=$row_s_medpay['order_no']; ?>
            </font></td>
          </tr>
          <?php } while ($row_s_medpay = mysql_fetch_assoc($s_medpay)); ?>
        </table>
        </div>
    </div>
   	<!-- chart -->
    </div>
	<!-- chart-drug -->
	<div class="col"  id="chart-drug"> 
    </div>
	<!-- chart-drug-->
</div>
<!-- drug order -->
</body>
</html>
<?php 
mysql_free_result($rs_search);
mysql_free_result($rs_patient);
mysql_free_result($rs_interval);
mysql_free_result($rs_medplan);
mysql_free_result($rs_meddate);
mysql_free_result($allergy);
mysql_free_result($s_medpay);

?>