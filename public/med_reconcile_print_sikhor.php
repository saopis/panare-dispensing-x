<?php require_once('Connections/hos.php'); ?>
<?php
		mysql_select_db($database_hos, $hos);
		$query_rs_med = "select m.*,a.dx_doctor,v.age_y,v.age_m,o.bw,v.pdx,a.an,regtime,v.pttype,u.name1,u.name2 from ".$database_kohrx.".kohrx_med_reconcile m left outer join drugusage u on u.shortlist=m.drugusage left outer join vn_stat v on v.hn=m.hn and v.vstdate=m.vstdate2 left outer join opdscreen o on o.vn=v.vn left outer join an_stat a on a.vn=v.vn left outer join ipt i on i.an=a.an  where m.hn='".$_GET['hn']."' and m.vstdate2='".$_GET['vstdate']."' order by drug_name ";
		//echo $query_rs_med;
		$rs_med = mysql_query($query_rs_med, $hos) or die(mysql_error());
		$rs_med2 = mysql_query($query_rs_med, $hos) or die(mysql_error());

		$row_rs_med = mysql_fetch_assoc($rs_med);
		$totalRows_rs_med = mysql_num_rows($rs_med);
		$row_rs_med2 = mysql_fetch_assoc($rs_med2);
		$totalRows_rs_med2 = mysql_num_rows($rs_med2);

        $age_y=$row_rs_med['age_y'];
        $age_m=$row_rs_med['age_m'];
        $bw=$row_rs_med['bw'];
        $pdx=$row_rs_med['pdx'];
        $dx_doctor=$row_rs_med['dx_doctor'];
        $an=$row_rs_med['an'];
        $regtime=$row_rs_med['regtime'];
        $pttype=$row_rs_med['pttype'];
        
		mysql_select_db($database_hos, $hos);
		$query_rs_allergy = "select agent from opd_allergy where hn='".$_GET['hn']."' order by agent ";
		$rs_allergy = mysql_query($query_rs_allergy, $hos) or die(mysql_error());
		$row_rs_allergy = mysql_fetch_assoc($rs_allergy);
		$totalRows_rs_allergy = mysql_num_rows($rs_allergy);

?>
<?php

  	mysql_select_db($database_hos, $hos);
	$query_logo = "select * from ".$database_kohrx.".kohrx_dispensing_setting where name = 'logo' ";
	$rs_logo = mysql_query($query_logo, $hos) or die(mysql_error());
	$row_logo = mysql_fetch_assoc($rs_logo);
	$totalRows_logo = mysql_num_rows($rs_logo);
?>
<?php include('include/function.php'); ?>
<?php include('include/function_sql.php'); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Medication Reconciliation</title>
<?php include('java_css_file.php'); ?>

    <script src="https://unpkg.com/gijgo@1.9.11/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.11/css/gijgo.min.css" rel="stylesheet" type="text/css" />

<style>
body {
        margin: 0;
        padding: 0;
        background-color: #FAFAFA;
        font-size: 16px;
    }
    * {
        box-sizing: border-box;
        -moz-box-sizing: border-box;
    }
    .page {
        width: 25cm;
        min-height: 37cm;
        padding: 2cm;
        margin: 1.5cm auto;
        border: 1px #D3D3D3 solid;
        border-radius: 5px;
        background: white;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);*/
    }
    .subpage {
        padding: 0px;
        /*border: 5px red solid;*/
        height: 29.7cm;
        width: 21cm;
        outline: 0 #FFFFFF solid;
    }
    
    @page {
        size: A4;
        margin: 0;
    }
    @media print {
        .page {
            margin: 1cm;
            padding: 1.5cm;
            border: initial;
            border-radius: initial;
            width: initial;
            min-height: initial;
            box-shadow: initial;
            background: initial;
            page-break-after: always;
            font-size: 16px;
        }
    }
p.stb { text-indent: 0; margin-top: 0.8em;}
p.mtb { text-indent: 0; margin-top: 2.17em }
p.ltb { text-indent: 0; margin-top: 3.08em }
.dotshed {  border-bottom: dotted 1px #646464;}
.footer {
  position: fixed;
  left: 0;
  bottom: 30;
  width: 100%;
  color: white;
}
    .table-bordered td, .table-bordered th{
    border-color: black !important;
}
</style>
</head>

<body>
<div class="book">
    <div class="page thfont">
		<!-- หน้า 1 --><ol></ol>
      <div class="subpage">
		<div class="text-center font18 font-weight-bolder">โรงพยาบาล<?php echo getHospname(); ?></div>
		<div>
          <table class="table-bordered thfont" style="width: 100%; font-size: 12px;">
              <thead>
              <tr style="font-size: 11px;" class="font-weight-bold">
                  <td width="5%" class="text-center">Date/Time</td>
                  <td width="30%" class="text-center">Orders for one day</td>
                  <td width="5%" class="text-center">Date/time</td>
                  <td width="40%" class="text-center">Orders for contisuation</td>
                  <td width="20%" class="text-center">Progress note</td>
              </tr>
            </thead>
            <tbody>
              <tr style="height: 1050px">
                  <td width="5%" class="text-center" style="font-size: 11px; vertical-align: top;"><?php echo dateThai3(date('Y-m-d')); ?></td>
                  <td width="30%" class="text-center"></td>
                  <td width="5%" class="text-center" style="font-size: 11px; vertical-align: top;"><?php echo dateThai3(date('Y-m-d')); ?></td>                  <td width="40%" class="" style="vertical-align: top; padding-left: 10px;">
                      <div><span style="border-bottom: solid 1px #000000;" class="font-weight-bolder">MEDICATION</span></div>
                      <?php $i=0; do{ $i++; ?>
                      <div class="font-weight-bold">
                          <?php echo $i.".) <span style='font-size:18px;'>&#9634;</span> ".$row_rs_med['drug_name']; ?>
                      </div>
                      <div style="padding-left: 30px; margin-top: -10px;">
                          - <?php if($row_rs_med['name1']!=""){ echo $row_rs_med['name1'].' '.$row_rs_med['name2']; } else { echo $row_rs_med['drugusage']; } ?>                          
                      </div>
                      <?php }while($row_rs_med = mysql_fetch_assoc($rs_med)); ?>
                  </td>
                  <td width="20%" class="text-center"></td>
              </tr>
        </tbody>
        </table>
<table width="100%" class="table-bordered thfont font14">
  <tbody>
    <tr>
      <td width="49%" class="text-center"><span class="font-weight-bold">ชื่อ-สกุล&ensp;</span><?php echo ptname($_GET['hn']); ?>&ensp;<span class="font-weight-bold">อายุ&ensp;</span><?php echo $age_y; ?>&nbsp;<span class="font-weight-bold">ปี&ensp;</span><?php echo $age_m; ?>&nbsp;<span class="font-weight-bold">เดือน&ensp;</span></td>
      <td width="19%">&nbsp;<span class="font-weight-bold">น้ำหนัก&ensp;</span><?php echo number_format2($bw); ?><span class="font-weight-bold">&nbsp;กิโลกรัม</span></td>
      <td width="17%">&nbsp;<span class="font-weight-bold">H.N.&ensp;</span><?php echo $_GET['hn']; ?></td>
      <td width="15%">&nbsp;<span class="font-weight-bold">A.N.&ensp;<?php echo $an; ?></span></td>
    </tr>
    <tr>
      <td class="text-center font-weight-bolder font14">Department of Service</td>
      <td>&nbsp;<span class="font-weight-bold">เตียง&ensp;</span></td>
      <td colspan="2">&nbsp;<span class="font-weight-bold">แพทย์&ensp;<?php echo doctorname($dx_doctor); ?></span></td>
      </tr>
    <tr>
  </tbody>
    </table>
    <table class="table-borderless" width="100%">
    <tr>
    <td colspan="4" style="border: 0px;" class="text-center font-weight-bolder font14">
          <div class="text-center font20 font-weight-bold">
            DOCTOR's/ORDER SHEET
        </div></td>
      </tr>

</table>

        </div>
        </div> 

    </div>
    <div class="page">
		<!-- หน้า 2 -->
        <div class="subpage">
		<div class="position-absolute" style="margin-top:-30px;"><img src="data:image/jpeg;base64,<?php echo base64_encode($row_logo['picture']); ?> "  width="100" height="auto" vlign="middle" class="image"></div>
		<div class="text-center font20 font-weight-bolder">Medication Reconciliation</div>
		<div class="text-center font14 ">(Medication Prior To Adminission)</div>
		<div class="text-center font14 thfont ">โรงพยาบาล<?php echo getHospname(); ?></div>
<div>
               
<table width="100%" class="table-bordered table-striped">
  <thead>
    <tr>
      <td colspan="14" style="padding: 5px;" class="thfont font13"><span class="font-weight-bold">ชื่อ-สกุล&ensp;</span><?php echo ptname($_GET['hn']); ?>&ensp;<span class="font-weight-bold">อายุ&ensp;</span><?php echo $age_y; ?>&nbsp;<span class="font-weight-bold">ปี&ensp;</span><?php echo $age_m; ?>&nbsp;<span class="font-weight-bold">เดือน&ensp;H.N.&ensp;</span><?php echo $_GET['hn']; ?><span class="font-weight-bold">&ensp;A.N.&ensp;<?php echo $an; ?>&ensp;น้ำหนัก</span>&ensp;<?php echo number_format2($bw); ?>&ensp;<span class="font-weight-bold">กิโลกรัม</span><br>
      <span class="font-weight-bold">วันที่เข้ารับการรักษา&ensp;</span><?php echo dateThai($_GET['vstdate']); ?>&ensp;<span class="font-weight-bold">แพทย์ผู้สั่ง Admit&ensp;<?php echo doctorname($dx_doctor); ?></span>&emsp;เวลา Admit : <?php echo $regtime; ?><br>
<span class="font-weight-bold">สิทธิ์ : </span>&ensp;<span class="font12"><?php echo pttypename($pttype); ?></span><br><span class="font-weight-bold">มื้อสุดท้ายที่ใช้ยา............................................( ) เช้า ( ) เที่ยง ( ) เย็น ( ) ก่อนนอน เวลา......................</span><br>
      <span class="font-weight-bold">แพ้ยา&ensp;</span><?php $i=0; do{ $i++; echo $row_rs_allergy['agent']; if($totalRows_rs_allergy<>0&&$i<>$totalRows_rs_allergy){ echo ","; } else if($totalRows_rs_allergy==0){ echo " - ปฏิเสธ - "; } } while($row_rs_allergy = mysql_fetch_assoc($rs_allergy)); ?>
</td>
      </tr>
    <tr class="thfon font12">
      <td rowspan="2" align="center" style="width: 36%">รายการยาที่ได้รับ</td>
      <td rowspan="2" align="center" style="width: 7%">วันที่ได้/ last dose</td>
      <td rowspan="2" align="center" style="width: 6%">วันที่นัด</td>
      <td rowspan="2" align="center" style="width: 10%">แหล่งที่ได้รับ</td>
      <td colspan="3" align="center" >Admission</td>
      <td colspan="4" align="center" >Discharge</td>
      </tr>
    <tr class="thfon font12">
      <td align="center" style="width: 4%">สั่ง</td>
      <td align="center" style="width: 4%">ไม่สั่ง</td>
      <td align="center" style="width: 10%">ปรับเปลี่ยน/เหตุผล</td>
      <td align="center" style="width: 4%">สั่ง</td>
      <td align="center" style="width: 4%">ไม่สั่ง</td>
      <td align="center" style="width: 10%">ปรับเปลี่ยน/เหตุผล</td>
      <td align="center" style="width: 5%">จำนวน</td>
    </tr>
  </thead>    
    <tbody>
    <?php $i=0; do{ $i++; ?>
    <tr style="height: 40px;">
      <td class="thfont" style="padding-left: 5px; font-size: 13px; vertical-align: top;">
        <div class="font-weight-bold"><?php echo $i.". ".mb_substr($row_rs_med2['drug_name'],0,100,'UTF-8')." #".$row_rs_med2['qty']; ?></div>
        <div class="pl-4" style="margin-top: -5px; font-size: 10px"><?php if($row_rs_med2['name1']!=""){ echo "- ".$row_rs_med2['name1'].' '.$row_rs_med2['name2']; } else { echo "- ".$row_rs_med2['drugusage']; } ?></div>
		  <?php if($row_rs_med2['remark']!=""){ ?><div style="border-top: 1px solid #000000; font-size: 11px"><strong>หมายเหตุ</strong>&nbsp;<?php echo $row_rs_med2['remark']; ?> </div><?php } ?>
          </td>

      <td class="thfont" style="padding-left: 5px; font-size: 11px; vertical-align: top;" align="center"><div><?php echo dateThai3($row_rs_med2['vstdate']); ?></div><div><?php echo $row_rs_med2['last_dose']; ?></div></td>
      <td class="thfont" style="padding-left: 5px; font-size: 11px; vertical-align: top;" align="center"><?php echo dateThai3($row_rs_med2['appdate']); ?></td>
      <td class="thfont" style="padding-left: 5px; font-size: 10px; vertical-align: top;" align="center"><?php echo $row_rs_med2['src_hospcode']; ?></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <?php } while($row_rs_med2 = mysql_fetch_assoc($rs_med2)); ?>
    <?php for($j=$i;$j<18;$j++){ ?>
    <tr style="height: 40px;">
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <?php } ?>
    </tbody>
    </table>
	<table class="table-borderless thfont font12" width="100%" >
		<tr>
			<td style="border: solid 1px #000000; border-top: 0px;">
				<div class="row">
					<div class="col-4 text-center"><br>

					<nobr>ลงชื่อ.......................................แพทย์ผู้สั่ง Admit</nobr><br>
                  (...............................................)
					</div>
					<div class="col-4 text-center"><br>

					<nobr>ลงชื่อ.............................................เภสัชกร</nobr><br>
                  (...............................................)
					</div>
					<div class="col-4 text-center"><br>

					<nobr>ลงชื่อ.............................................พยาบาล</nobr><br>
                  (...............................................)
					</div>
				</div>
			</td>
		</tr>
		<tr>
			<td style="border: solid 1px #000000; border-top: 0px;">
				<div class="row">
					<div class="col-4 text-center"><br>

					<nobr>ลงชื่อ.......................................แพทย์ผู้สั่ง D/C</nobr><br>
                  (...............................................)
					</div>
					<div class="col-4 text-center"><br>

					<nobr>ลงชื่อ.............................................เภสัชกร</nobr><br>
                  (...............................................)
					</div>
					<div class="col-4 text-center"><br>

					<nobr>ลงชื่อ.............................................พยาบาล</nobr><br>
                  (...............................................)
					</div>
				</div>			
			</td>			
		</tr>
	</table>
	
	<div class="row mt-2">
		<div class="col-sm-1 font-weight-bold">Note</div>
		<div class="col-sm-11">
			<div style="border-bottom: dotted 1px #000000; height: 30px;"></div>
			<div style="border-bottom: dotted 1px #000000; height: 30px;"></div>
			<div style="border-bottom: dotted 1px #000000; height: 30px;"></div>
		</div>

	</div>



          </div> 
      </div>    
    </div>
</div>

</body>
</html>
<?php mysql_free_result($rs_med); ?>
<?php mysql_free_result($rs_med2); ?>
<?php mysql_free_result($rs_allergy); ?>