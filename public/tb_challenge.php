<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php include('java_css_online.php'); ?>    

<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
html,body { height:100%; overflow: hidden }

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
<script type="text/javascript">
function MM_popupMsg(msg) { //v1.0
  alert(msg);
}
</script>
<script>
$(document).ready(function(){
	$('#rechallenge').click(function(){
		                $("#result").load('tb_challenge_list.php?hn='+$('#hn').val()+'&i_challenge='+$('#i_challenge').val()+'&r_challenge='+$('#r_challenge').val()+'&e_challenge='+$('#e_challenge').val()+'&p_challenge='+$('#p_challenge').val(), function(responseTxt, statusTxt, xhr){
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");                    
					if(statusTxt == "error")
                      alert("Error: " + xhr.status + ": " + xhr.statusText);
                  });	
	});
	$('#strep').click(function(){
		                $("#result2").load('strep_temp.php?hn='+$('#hn2').val()+'&im_type='+$('im_type').val(), function(responseTxt, statusTxt, xhr){
                        
                        if(statusTxt == "success")
                          //alert("External content loaded successfully!");                    
                        if(statusTxt == "error")
                          alert("Error: " + xhr.status + ": " + xhr.statusText);
                            
                  });	
	});
	
});
function caldosage(a){
	if(a!=0){
		var b
		if(a<=29){
			$('#tab').val(1);
			b=1;
			}
		if(a>=30 & a<=37){
			$('#tab').val(2);
			b=2;
			}
		if(a>=38 & a<=54){
			$('#tab').val(3);
			b=3;
			}
		if(a>=55 & a<=70){
			$('#tab').val(4);
			b=4;
			}
		if(a>=71) {
			$('#tab').val(5);
			b=5;
			}
		if(a<=49){
			$('#rifinah').html("150mg.(100/150)");
			$('#tab2').val(3);
			b=3; 
			}
		if(a>49){
			$('#rifinah').html("300mg.(150/300)");
			$('#tab2').val(2);
			b=2;
			}
		$('#i_range').val(75*$('#tab').val());
		$('#r_range').val(150*$('#tab').val());
		$('#i_range2').val(75*$('#tab2').val());
		$('#r_range2').val(150*$('#tab2').val());
		$('#e_range').val(275*$('#tab').val());
		$('#p_range').val(400*$('#tab').val());
		$('#inhmin').html(a*4);
		$('#inhmax').html(a*6);
		$('#rmin').html(a*8);
		$('#rmax').html(a*12);
		$('#emin').html(a*15);
		$('#emax').html(a*20);	
		$('#pmin').html(a*20);
		$('#pmax').html(a*30);
		$('#smin').html(a*15);
		$('#smax').html(a*20);			
		$('#omin').html(a*7.5);
		$('#omax').html(a*15);	
		$('#lmin').html(a*15);
		$('#lmax').html(a*15);	
		$('#inhn').html(a*5);					
		$('#rn').html(a*10);
		$('#en').html(a*15);
		$('#pn').html(a*15);
		$('#sn').html(a*15);
		$('#on').html(a*10);																						
		$('#ln').html(a*15);																						
	}
	}
function MM_popupMsg(msg) { //v1.0
  alert(msg);
}
function formSubmit(formid,url,displayDiv,indicator) {
	var data = getFormData(formid);
	ajaxLoad('post', url, data, displayDiv,indicator);
	var e = document.getElementById(indicator);
	e.style.display = 'block';
	}

	
function i_cal(b){
	if(b<100)
		{$('#i_challenge').val('100');}
	if(b>100&&b<200)
		{$('#i_challenge').val('200');}
	if(b>200&&b<300)
		{$('#i_challenge').val('300');}
	if(b>300)
		{$('#i_challenge').val('300');}
	}
function r_cal(b){
	if(b<300)
		{$('#r_challenge').val('300');}
	if(b>300&&b<450)
		{$('#r_challenge').val('450');}
	if(b>450&&b<600)
		{$('#r_challenge').val('600');}
	if(b>600)
		{$('#r_challenge').val('600');}
	}
function e_cal(b){
	if(b<400)
		{$('#e_challenge').val('400');}
	if(b>400&&b<500)
		{$('#e_challenge').val('500');}
	if(b>500&&b<800)
		{$('#e_challenge').val('800');}
	if(b>800&&b<900)
		{$('#e_challenge').val('900');}
	if(b>900&&b<1000)
		{$('#e_challenge').val('1000');}
	if(b>1000&&b<1200)
		{$('#e_challenge').val('1200');}
	if(b>1200&&b<1400)
		{$('#e_challenge').val('1400');}
	if(b>1400&&b<1500)
		{$('#e_challenge').val('1500');}
	}
function p_cal(b){
	if(b<250)
		{$('#p_challenge').val('250');}
	if(b>250&&b<500)
		{$('#p_challenge').val('500');}
	if(b>500&&b<750)
		{$('#p_challenge').val('750');}
	if(b>750&&b<1000)
		{$('#p_challenge').val('1000');}
	if(b>1000&&b<1250)
		{$('#p_challenge').val('1250');}
	if(b>1250&&b<1500)
		{$('#p_challenge').val('1500');}
	}

function validate(evt) {
  var theEvent = evt || window.event;
  var key = theEvent.keyCode || theEvent.which;
  key = String.fromCharCode( key );
  var regex = /[0-9]|\./;
  if( !regex.test(key) ) {
    theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
  }
}
</script>

</head>

<body>
<nav class="navbar navbar-dark bg-info text-white " >
  <!-- Navbar content -->
  <span class="card-title font_bord"  >&ensp;คำนวนยาวัณโรค</span>
</nav>

<div class=" p-3" style="overflow:scroll;overflow-x:hidden;overflow-y:auto; height:90vh;" >
<div class="card ">
	<div class="card-header"><span class="card-title">คำนวณตามขนาดเม็ดยา</span></div>
<div class="card-body">
<div class="p-2">
<form id="form1" name="form1" method="post" action="">
<div class="input-group" style="width:250px">
	<span class="input-group-addon" style="background-color:#FFFFFF; border:0px;">น้ำหนัก</span>&ensp;
  <input type="text" id="bw" name="bw" onkeyup="caldosage(this.value);" onkeypress="validate(event);" class="form-control form-control-sm" >
  <span class="input-group-addon">&ensp;กิโลกรัม</span>
</div>
<br />
</form>
</div>
<table width="750" border="0" cellpadding="2" cellspacing="0" style="border-collapse:collapse; " class="table">
  <tr>
    <td colspan="2" bgcolor="#EFEFEF">Rimstar/Rifafour</td>
    <td width="82" align="left" bgcolor="#EFEFEF"><input name="tab" type="text" class="form-control form-control-sm font-weight-bold text-danger" id="tab" size="5" disabled /></td>
    <td width="72" bgcolor="#EFEFEF">เม็ด</td>
    <td width="95" bgcolor="#EFEFEF">&nbsp;</td>
    <td width="84" bgcolor="#EFEFEF">&nbsp;</td>
    <td width="88" bgcolor="#EFEFEF">&nbsp;</td>
    <td width="88" bgcolor="#EFEFEF">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="8" bgcolor="#EFEFEF">
    <div class="input-group" style="width:90%;">
  <span class="input-group-addon">Isoniazid</span>&ensp;
  <input type="text" id="i_range" name="i_range"  class="form-control form-control-sm font-weight-bold text-danger" disabled />&ensp;
  <span class="input-group-addon">Rifampicin</span>&ensp;
  <input type="text" id="r_range" name="r_range" class="form-control  form-control-sm font-weight-bold text-danger" disabled />&ensp;
  <span class="input-group-addon">Ethambutol</span>&ensp;
  <input type="text" id="e_range" name="e_range" class="form-control  form-control-sm font-weight-bold text-danger" disabled />&ensp;
  <span class="input-group-addon">Pirazinamide</span>&ensp;
  <input type="text" id="p_range" name="p_range" class="form-control  form-control-sm font-weight-bold text-danger" disabled/>&ensp;

</div>
</td>
  </tr>
  <tr>
    <td colspan="8" bgcolor="#99CCCC">
		<div class="row"><div class="col-sm-auto">Rifinah</div>
		<div class="col-sm-auto">
      <input name="tab2" type="text" id="tab2" size="5" class="form-control form-control-sm font-weight-bold text-danger" disabled /></div><div class="col-sm-auto">เม็ด</div><div id="rifinah" class="col-sm-auto"></div></div></td>
    </tr>
  <tr>
    <td width="146" align="center" bgcolor="#999999" style="border: solid 1px #000000">รายการยา</td>
    <td width="105" align="center" bgcolor="#999999" style="border: solid 1px #000000">ขนาดยา/เม็ด</td>
    <td align="center" bgcolor="#999999" style="border: solid 1px #000000">min(mg.)</td>
    <td align="center" bgcolor="#999999" style="border: solid 1px #000000">max(mg.)</td>
    <td colspan="2" align="center" bgcolor="#999999" style="border: solid 1px #000000">normal</td>
    <td align="center" bgcolor="#999999" style="border: solid 1px #000000">maxdose</td>
    <td align="center" bgcolor="#999999" style="border: solid 1px #000000">Drug Infor</td>
  </tr>
  <tr>
    <td style="border: solid 1px #000000">Isoniazid (4-6)</td>
    <td align="center" style="border: solid 1px #000000">100 mg.</td>
    <td bgcolor="#99CCFF" style="border: solid 1px #000000"><div id="inhmin" align="center">&nbsp;</div></td>
    <td bgcolor="#FF9933" style="border: solid 1px #000000"><div id="inhmax" align="center">&nbsp;</div></td>
    <td align="center" style="border: solid 1px #000000">5 mg./kg.</td>
    <td align="center" bgcolor="#99CC33" style="border: solid 1px #000000"><div id="inhn" align="center">&nbsp;</div></td>
    <td align="center" style="border: solid 1px #000000">300 mg.</td>
    <td align="center" style="border: solid 1px #000000"><img src="images/admin_user.gif" width="14" height="14" onclick="MM_popupMsg('Isoniazid\r\rAdult = 5mg/kg./day\r\rGeriatric = 5 mg./kg./day\r\rPediatric = 10-20 mg./kg./day')" /></td>
  </tr>
  <tr>
    <td style="border: solid 1px #000000">Rifampicin (8-12)</td>
    <td align="center" style="border: solid 1px #000000">300, 400 mg.</td>
    <td bgcolor="#99CCFF" style="border: solid 1px #000000"><div id="rmin" align="center">&nbsp;</div></td>
    <td bgcolor="#FF9933" style="border: solid 1px #000000"><div id="rmax" align="center">&nbsp;</div></td>
    <td align="center" style="border: solid 1px #000000">10 mg./kg.</td>
    <td align="center" bgcolor="#99CC33" style="border: solid 1px #000000"><div id="rn" align="center">&nbsp;</div></td>
    <td align="center" style="border: solid 1px #000000">600 mg.</td>
    <td align="center" style="border: solid 1px #000000"><img src="images/admin_user.gif" width="14" height="14" onclick="MM_popupMsg('Rifampicin\rAdult = 10mg./kg./day\rGeriatric= 10mg./kg./day\rPediatric &lt; 12 y= 10-20 mg./kg./day ')" /></td>
  </tr>
  <tr>
    <td style="border: solid 1px #000000">Ethambutol (15-20)</td>
    <td align="center" style="border: solid 1px #000000">400 , 500 mg.</td>
    <td bgcolor="#99CCFF" style="border: solid 1px #000000"><div id="emin" align="center">&nbsp;</div></td>
    <td bgcolor="#FF9933" style="border: solid 1px #000000"><div id="emax" align="center">&nbsp;</div></td>
    <td align="center" style="border: solid 1px #000000">15 mg./kg.</td>
    <td align="center" bgcolor="#99CC33" style="border: solid 1px #000000"><div id="en" align="center">&nbsp;</div></td>
    <td align="center" style="border: solid 1px #000000">1200 mg.</td>
    <td align="center" style="border: solid 1px #000000"><img src="images/admin_user.gif" width="14" height="14" onclick="MM_popupMsg('retreatment : 25mg./kg.\r\rCDC,2003 :\r  40-55 kg. : 800 mg.\r  56-75 kg. : 1200 mg.\r  76-90 kg. : 1600 mg.')" /></td>
  </tr>
  <tr>
    <td style="border: solid 1px #000000">Pyrazinamide (20-30)</td>
    <td align="center" style="border: solid 1px #000000">400 mg.</td>
    <td bgcolor="#99CCFF" style="border: solid 1px #000000"><div id="pmin" align="center">&nbsp;</div></td>
    <td bgcolor="#FF9933" style="border: solid 1px #000000"><div id="pmax" align="center">&nbsp;</div></td>
    <td align="center" style="border: solid 1px #000000">25 mg./kg.</td>
    <td align="center" bgcolor="#99CC33" style="border: solid 1px #000000"><div id="pn" align="center">&nbsp;</div></td>
    <td align="center" style="border: solid 1px #000000">200 mg.</td>
    <td align="center" style="border: solid 1px #000000"><img src="images/admin_user.gif" width="14" height="14" onclick="MM_popupMsg('40-55 kg. : 1000mg.\r56-75 kg. : 1500mg.\r76-90 kg. : 2000mg.(max)\r')" /></td>
  </tr>
  <tr>
    <td style="border: solid 1px #000000">Streptomycin (15-20)</td>
    <td align="center" style="border: solid 1px #000000">1 G</td>
    <td bgcolor="#99CCFF" style="border: solid 1px #000000"><div id="smin" align="center">&nbsp;</div></td>
    <td bgcolor="#FF9933" style="border: solid 1px #000000"><div id="smax" align="center">&nbsp;</div></td>
    <td align="center" style="border: solid 1px #000000">15 mg./kg.</td>
    <td align="center" bgcolor="#99CC33" style="border: solid 1px #000000"><div id="sn" align="center">&nbsp;</div></td>
    <td align="center" style="border: solid 1px #000000">1G</td>
    <td align="center" style="border: solid 1px #000000"><img src="images/admin_user.gif" width="14" height="14" onclick="MM_popupMsg('Adult : 15 mg./kg./day\rPediatric : 20-40 mg./kg./day')" /></td>
  </tr>
  <tr>
    <td style="border: solid 1px #000000">Ofloxacin (7.5-15)</td>
    <td align="center" style="border: solid 1px #000000">200 mg.</td>
    <td bgcolor="#99CCFF" style="border: solid 1px #000000"><div id="omin" align="center">&nbsp;</div></td>
    <td bgcolor="#FF9933" style="border: solid 1px #000000"><div id="omax" align="center">&nbsp;</div></td>
    <td align="center" style="border: solid 1px #000000">10 mg./kg.</td>
    <td align="center" bgcolor="#99CC33" style="border: solid 1px #000000"><div id="on" align="center">&nbsp;</div></td>
    <td align="center" style="border: solid 1px #000000">&nbsp;</td>
    <td align="center" style="border: solid 1px #000000"><img src="images/admin_user.gif" width="14" height="14" /></td>
  </tr>
  <tr>
    <td style="border: solid 1px #000000">Levofloxacin (15)</td>
    <td align="center" style="border: solid 1px #000000">200 mg.</td>
    <td bgcolor="#99CCFF" style="border: solid 1px #000000"><div id="lmin" align="center">&nbsp;</div></td>
    <td bgcolor="#FF9933" style="border: solid 1px #000000"><div id="lmax" align="center">&nbsp;</div></td>
    <td align="center" style="border: solid 1px #000000">15 mg./kg.</td>
    <td align="center" bgcolor="#99CC33" style="border: solid 1px #000000"><div id="ln" align="center">&nbsp;</div></td>
    <td align="center" style="border: solid 1px #000000">&nbsp;</td>
    <td align="center" style="border: solid 1px #000000"><img src="images/admin_user.gif" width="14" height="14" /></td>
  </tr>	
</table>
<div class="panel-body">
	<div class="card">
		<div class="card-header">หมายเหตุ</div>
		<div class="card-body">
			<div>* ในกรณีน้ำหนัก < 35 หรือ > 70 กิโลกรัม  ให้คำนวณขนาดยาตามน้ำหนักตัว</div>
			<div>** INH สามารถปรับตามน้ำหนักตัว และชนิด Acetylator gene ของผู้ป่วย (NAT2 genotype))</div>
			<div><strong>คำแนะนำ</strong></div>
			<div>• การใช้ยาเม็ดรวม (fixed dose combination ; FDC) เช่น HR,HRZE จะช่วยเพิ่มความสะดวกในการจัดการรับประทานยา  และหลีกเลี่ยงการเลือกรับประทานยาบางขนานได้  แต่ต้องให้ขนาดยาตามน้ำหนักตัวตามคำแนะนำอย่างถูกต้อง</div>
		</div>
		
	</div>	
	<div class="card mt-2">
		<div class="card-header">การปรับยาวัณโรคในผู้ป่วยโรคไต</div>
		<div class="card-body">
			<div>ขนาดยาวัณโรคแนวที่หนึ่งและยาทางเลือกที่แนะนำในผู้ป่วยที่มีค่า Creatinine clearance < 30 มิลลิลิตรต่อนาที  หรือได้รับการล้างไต</div>
			<table style="width: 100%" class="table table-striped table-bordered">
				<thead>
					<tr>
						<th class="text-center">ยา</th>
						<th class="text-center">การปรับยา</th>
						<th class="text-center">ขนาดยาที่แนะนำ</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="text-center">H</td>
						<td class="text-center">ไม่ปรับ</td>
						<td class="text-center">เหมือนเดิม</td>
					</tr>
					<tr>
						<td class="text-center">R</td>
						<td class="text-center">ไม่ปรับ</td>
						<td class="text-center">เหมือนเดิม</td>
					</tr>
					<tr>
						<td class="text-center">E</td>
						<td class="text-center">ปรับ</td>
						<td class="text-center">15-20 มิลลิกรัมต่อวัน 3 วันต่อสัปดาห์</td>
					</tr>
					<tr>
						<td class="text-center">Z</td>
						<td class="text-center">ปรับ</td>
						<td class="text-center">20-30 มิลลิกรัมต่อวัน 3 วันต่อสัปดาห์</td>
					</tr>
				</tbody>
			</table>
		</div>
		
	</div>	
	<div class="card mt-2">
		<div class="card-header">วัณโรคในผู้ป่วยโรคตับ</div>
		<div class="card-body">
			<div>ผู้ที่มีอาการแสดงของโรคตับเรื้อรัง และระดับ ALT ในเลือด > 3 เท่าของค่าปกติ  ควรเลือกสูตรยาที่มีผลต่อการทำงานของตับน้อยลง  มีหลักการพิจารณาตามลำดับดังนี้ (ทั้งนี้ขึ้นกับระดับความรุนแรงของโรคตับของผู้ป่วย)</div>
			<div class="pl-5">
				<div>(1) สูตรยาที่มียาที่มีผลต่อการทำงานของตับ 2 ชนิด</div>
				<div class="pl-2">2HRE/7HR</div>
				<div class="pl-2">6-9 RZE</div>
			</div>	
			<div class="pl-5">
				<div>(2) สูตรยาที่มียาที่มีผลต่อการทำงานของตับ 1 ชนิด</div>
				<div class="pl-2">2 AmHE/16 HE</div>
				<div class="pl-2">12-18 HE + Lfx</div>
			</div>				
			<div>นัดติดตามอาการทางคลินิกทุก 1 สัปดาห์ในช่วง 2-3 สัปดาห์แรกของการรักษา  และทุก 2 สัปดาห์ในช่วง 2 เดือนแรกของการรักษาวัณโรค  ในระหว่างนั้นถ้ามีอาการทางคลินิกสงสัยตับอักเสบต้องได้รับการตรวจเลือดเพื่อติดตามการทำงานของตับทันที</div>
		</div>
		
	</div>	
	<div class="card mt-2">
		<div class="card-header">วัณโรคในหญิงตั้งครรภ์</div>
		<div class="card-body">
			<div><strong>คำแนะนำ</strong></div>
			<div class="pl-5">
				<div>• ผู้ป่วยวัณโรคที่ตั้งครรภ์สามารถให้ยาตามสูตรมาตรฐาน  ได้ตามปกติ  คำนวณขนาดยาตามน้ำหนักก่อนตั้งครรภ์</div>
				<div>• ในหญิงตั้งครรภ์ที่ได้ยา H พิจารณาให้รับประทานวิตามินบี 6 (pyridoxine) ในขนาด 50-100 มิลลิกรัม/วัน เพื่อป้องกันผลต่อระบบประสาท</div>
				<div>• หญิงที่ให้นมบุตรสามารถให้นมได้ตามปกติเนื่องจากมีปริมาณยาน้อยในน้ำนม  ดังนั้นไม่มีผลต่อเด็ก  แต่ต้องระวังการแพร่กระจายเชื้อวัณโรคจากมารดาสู่บุตร  ในกรณียังไอมากและเสมหะยังเป็นบวก  อาจเลี่ยงโดยการบีบน้ำนมแม่ใส่ขาด  แล้วให้เด็กดูดจากขวดแทน</div>
				<div>• หลีกเลี่ยงการให้ยา aminoglycosides ในหญิงตั้งครรภ์ และให้นมบุตรด้วย  เนื่องจากเกิดพิษต่อหู (ototoxic) ของทารกในครรภ์</div>
				<div>• หลีกเลี่ยงยากลุ่ม fluoroquinolone ในหญิงตั้งครรภ์  และให้นมบุตร ถ้ามีความจำเป็นต้องใช้ ควรปรึกษาแพทย์ผู้เชี่ยวชาญ เพื่อพิจารณาร่วมกับผู้ป่วยและญาติเป็นรายๆ ไป</div>
			</div>
		</div>
		
	</div>			
		
</div>
	</div>
	</div>
<div class="card mt-2">
	<div class="card-header">  <strong>TB rechallenge </strong></div>
<div class="card-body">
<form id="form2" name="form2" method="post" action="">
  <p>ขนาดยาที่ผู้ป่วยต้องได้รับ  </p>
<div class="row" ><div class="col-sm-auto"><span>HN ผู้ป่วย</span></div>
            <div class="col-sm-auto"><input type="text" id="hn" name="hn"  class="form-control form-control-sm" onkeypress="validate(event);" /></div>
</div>
  <table width="500" border="0" cellspacing="0" cellpadding="0" class="pad5">

        <tr>
      <td width="120" align="left">ISONIAZID
        <input type="hidden" name="id" id="id" />
        <input type="hidden" name="do" id="do" /></td>
      <td width="252"><div class="input-group" > 
        <input type="text" id="i_challenge" name="i_challenge"  class="form-control" onmouseout="i_cal(this.value);" onkeypress="validate(event);" />
        <span class="input-group-addon">&ensp;mg.</span> </div></td>
      </tr>
    <tr>
      <td align="left">RIFAMPICIN</td>
      <td><div class="input-group" > 
        <input type="text" id="r_challenge" name="r_challenge"  class="form-control" onmouseout="r_cal(this.value);" onkeypress="validate(event);" />
        <span class="input-group-addon">&ensp;mg.</span></div></td>
      </tr>
    <tr>
      <td align="left">ETHAMBUTOL</td>
      <td><div class="input-group"> 
        <input type="text" id="e_challenge" name="e_challenge"  class="form-control" onmouseout="e_cal(this.value);" onkeypress="validate(event);" />
        <span class="input-group-addon">&ensp;mg.</span></div></td>
      </tr>
    <tr>
      <td align="left">PYRAZINAMIDE</td>
      <td><div class="input-group" > 
        <input type="text" id="p_challenge" name="p_challenge"  class="form-control" onmouseout="p_cal(this.value);" onkeypress="validate(event);" />
        <span class="input-group-addon">&ensp;mg.</span></div></td>
      </tr>
</table>
<input type="button" class="btn btn-danger btn-sm" value="สร้างตาราง Rechallenge" id="rechallenge" name="rechallenge"/>
<div id="indicator"  align="center" style="position:absolute; display:none; z-index:1000;padding:0px;"> <img src="images/indicator.gif" hspace="10" align="absmiddle" />&nbsp;</div><div id="result" class="result mt-2" >&nbsp;</div>
</form>
</div>
</div>
<div class="card mt-2">
<div class="card-header"><strong>คำนวณ Streptomycin</strong></div>
<div class="card-body">
<form action="" id="form3" name="form3" method="get">
      <div class="row" ><div class="col-sm-auto">HN ผู้ป่วย</div>
      <div class="col-sm-auto"><input type="text" id="hn2" name="hn2"  class="form-control form-control-sm" onkeypress="validate(event);"  />
      </div>
	<div class="col-sm-auto">วิธีฉีด</div>
	<div class="col-sm-auto">
	<select name="im_type" id="im_type" class="form-control form-control-sm">
       <option value="1">จันทร์ถึงศุกร์ เว้นเสาร์อาทิตย์</option>
       <option value="2">วันเว้นวัน</option>
       <option value="3">สัปดาห์ละ 3 ครั้ง จันทร์ พุธ ศุกร์</option>
     </select> 
		  </div>
	<div class="col-sm-auto"><span class="btn btn-danger btn-sm " id="strep">คำนวณ</span></div>
	</div>

</form>
<div id="result2" class="result2 mt-2" >&nbsp;</div>
	</div>
	</div>
</div>
</div>
</body>
</html>
