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

	 function dateThai($date){
	$_month_name = array("01"=>"มกราคม","02"=>"กุมภาพันธ์","03"=>"มีนาคม","04"=>"เมษายน","05"=>"พฤษภาคม","06"=>"มิถุนายน","07"=>"กรกฎาคม","08"=>"สิงหาคม","09"=>"กันยายน","10"=>"ตุลาคม","11"=>"พฤศจิกายน","12"=>"ธันวาคม");
	$yy=substr($date,0,4);$mm=substr($date,5,2);$dd=substr($date,8,2);$time=substr($date,11,8);
	$yy+=543;
	$dateT=intval($dd)." ".$_month_name[$mm]." ".$yy." ".$time;
	return $dateT;
	}

mysql_select_db($database_hos, $hos);
$query_rs_an = "select concat(p.pname,p.fname,' ',p.lname) as ptname,p.hn,an,age_y,age_m,d.name as doctorname,i.name as icdname,a.regdate,a.dchdate from an_stat a left outer join patient p on p.hn=a.hn left outer join doctor d on d.code=a.dx_doctor left outer join icd101 i on i.code=a.pdx where an='".$_GET['an']."'";
$rs_an = mysql_query($query_rs_an, $hos) or die(mysql_error());
$row_rs_an = mysql_fetch_assoc($rs_an);
$totalRows_rs_an = mysql_num_rows($rs_an);

mysql_select_db($database_hos, $hos);
$query_rs_allergy = "select agent,symptom from opd_allergy where hn='".$row_rs_an['hn']."'";
$rs_allergy = mysql_query($query_rs_allergy, $hos) or die(mysql_error());
$row_rs_allergy = mysql_fetch_assoc($rs_allergy);
$totalRows_rs_allergy = mysql_num_rows($rs_allergy);

mysql_select_db($database_hos, $hos);
$query_s_drug = "select m1.order_no,concat(s.name,' ',s.strength,' ',s.units) as name ,d.shortlist  , mp.med_order_number,m1.icode,concat(s.name,' ',s.strength,' ',s.units) as drugname,concat(sp.name1,sp.name2,sp.name3) as sp_name, m1.qty,mp.med_plan_number,mp.med_real_pay_qty,mp.day_number,substring(m1.icode,1,1) as scode,m1.sp_use   from opitemrece m1    left outer join s_drugitems s on s.icode=m1.icode   left outer join drugusage d on d.drugusage=m1.drugusage  left outer join medpay_ipd mp on mp.hos_guid = m1.hos_guid left outer join sp_use sp on sp.sp_use=m1.sp_use   where m1.an='".$_GET['an']."' and m1.order_no='".$_GET['order_no']."' order by item_no ";
$s_drug = mysql_query($query_s_drug, $hos) or die(mysql_error());
$row_s_drug = mysql_fetch_assoc($s_drug);
$totalRows_s_drug = mysql_num_rows($s_drug);

mysql_select_db($database_hos, $hos);
$query_rs_opdconfig = "select o.hospitalname,o.hospitalcode,t.full_name from opdconfig o left outer join hospcode h on h.hospcode=o.hospitalcode left outer join thaiaddress t on t.addressid=concat(h.chwpart,h.amppart,h.tmbpart)";
$rs_opdconfig = mysql_query($query_rs_opdconfig, $hos) or die(mysql_error());
$row_rs_opdconfig = mysql_fetch_assoc($rs_opdconfig);
$totalRows_rs_opdconfig = mysql_num_rows($rs_opdconfig);
?>

<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<?php include('java_css_file.php'); ?>
  <style>
page {
  background: white;
  display: block;
  margin: 0 auto;
  margin-bottom: 1cm;
  box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
}
page[size="A4"] {  
  width: 21cm;
  height: 29.7cm; 
    margin-top: 120px;
}
page[size="A4"][layout="landscape"] {
  width: 29.7cm;
  height: 21cm;  
}
page[size="A3"] {
  width: 29.7cm;
  height: 42cm;
}
page[size="A3"][layout="landscape"] {
  width: 42cm;
  height: 29.7cm;  
}
page[size="A5"] {
  width: 14.8cm;
  height: 21cm;
}
page[size="A5"][layout="landscape"] {
  width: 21cm;
  height: 14.8cm;  
}
@media print {
header, footer, aside, nav, form, iframe, .menu, .hero, .adslot {
  display: none;
}
    body {transform: scale(1.4);}
    @page {
            size: A5;
        }
    
  html, body {
    width: 150mm;
    height: 210mm;
	margin-left: -40px;
	margin-right: -30px;
	margin-top: 80px;
  }


    .non-printable { display: none; }
    .printable { display: block; }
     
   	.btn { display:none;}
 
}
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

<body onLoad="window.print();" >
<page size="A5" class="p-3" >	
<div id="head" align="center" class=" thfont" style=" font-size:25px;">
รายการยาผู้ป่วยกลับบ้าน
</div>
<div align="center " class="thfont font16 text-center" style=" border-bottom: 1px double #000000">โรงพยาบาล
<?php echo $row_rs_opdconfig['hospitalname']."&nbsp;&nbsp;".$row_rs_opdconfig['full_name']; ?>
</div>
<div style="margin-top:20px; " class="thfont font16">
<?php echo $row_rs_an['ptname']."<span style=\"font-size:16px\" >&nbsp;&nbsp;&nbsp;HN ".$row_rs_an['hn']."&nbsp;&nbsp;&nbsp;AN ".$row_rs_an['an']."&nbsp;&nbsp;&nbsp;
อายุ ".$row_rs_an['age_y']." ปี ".$row_rs_an['age_m']." เดือน</span>"; ?>
</div>
<div style="margin-top:5px" class="thfont font14"><table width="100%" border="0" cellpadding="5" cellspacing="0">
  <tr>
    <td width="19%" style="padding-left:0px;font-weight:bold" >ประวัติการแพ้ยา</td>
    <td width="81%"><?php if($totalRows_rs_allergy<>0){ ?>
      <?php do { ?>
        <table width="100%" border="0" cellspacing="0" cellpadding="5">
          <tr>
            <td><?php echo $row_rs_allergy['agent']; ?> = <?php echo $row_rs_allergy['symptom']; ?></td>
            </tr>
        </table>
        <?php } while ($row_rs_allergy = mysql_fetch_assoc($rs_allergy)); ?>      <?php } else { echo "-"; } ?></td>
  </tr>
</table>
</div>
<div class="thfont" style="margin-top:0px;font-size:14px"><span style="font-weight: bold">แพทย์ผู้วินิจฉัย :&nbsp;&nbsp;</span><span style="border-bottom: dotted 1px #000000"><?php echo $row_rs_an['doctorname']; ?></span><br>
</div>
<div class="thfont font14" style="margin-top:5px"><span style="font-weight: bold">วันที่เข้ารับการรักษา : </span><span style="font-size:12px; border-bottom: dotted 1px #000000"><?php echo dateThai($row_rs_an['regdate']); ?></span><span style="font-weight: bold">&nbsp;&nbsp;&nbsp;วันที่ออก :</span> <span style="border-bottom: dotted 1px #000000"><?php if($row_rs_an['dchdate']!=""){ echo dateThai($row_rs_an['dchdate']); }else{ echo dateThai(date('Y-m-d')); } ?></span></div>
<div>
  <table  class="table table-bordered table-sm thfont font14 mt-2 mr-2" style="">
      <thead>
      <tr>
        <td width="7%" align="center"  class="thfont" >ลำดับ</td>
        <td width="43%" align="center">รายการยา</td>
        <td width="41%" align="center">วิธีใช้</td>
        <td width="9%" align="center" >จำนวน</td>
      </tr>
    </thead>
    <tbody>
  <?php $i=0; do { $i++; ?>
      <tr>
        <td align="center" valign="top"><?php echo $i; ?></td>
        <td align="left" valign="top"><?php echo $row_s_drug['drugname']; ?></td>
        <td align="left" valign="top"><?php if($row_s_drug['sp_use']==""){ echo $row_s_drug['shortlist']; } else { echo $row_s_drug['sp_name']; } ?></td>
        <td align="center" valign="top"><?php echo $row_s_drug['qty']; ?></td>
      </tr>
    <?php } while ($row_s_drug = mysql_fetch_assoc($s_drug)); ?>
    </tbody>
  </table>
</div>
<div id="appdate" class="thfont font16" style="margin-top:10px">
<i class="far fa-square font20"></i> นัด&nbsp; วันที่................................................ <br>
<i class="far fa-square font20"></i> ไม่นัด</div>
<div align="center" class=" font_bord" style="; margin-top:30px;font-size:25px; border:solid 1px #000000; padding:10px">โปรดนำรายการยาผู้ป่วยกลับบ้านนี้มาด้วย ทุกครั้งเมื่อมาโรงพยาบาล</div>
</div>
</body>
</html>

<?php
mysql_free_result($rs_an);

mysql_free_result($rs_allergy);

mysql_free_result($s_drug);

mysql_free_result($rs_opdconfig);
?>
