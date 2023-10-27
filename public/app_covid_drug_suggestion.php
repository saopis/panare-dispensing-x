<?php ob_start();?>
<?php session_start();?>
<?php if(($_SESSION['r_opd']!='Y') and ($_SESSION['r_finance']!='Y')){
	echo "คุณไม่ได้รับสิทธิ์ให้ใช้งานในส่วนนี้";
	exit();
	} ?>
<?php require_once('Connections/hos.php'); ?>
<?php include('include/function.php'); ?>
<?php include('include/function_sql.php'); ?>
<?php
$vn=$_GET['vn'];
mysql_select_db($database_hos, $hos);
$query_s_patient = "SELECT v.vn,v.hn, concat(p.pname,p.fname,'    ',p.lname) as patient_name,v.age_y,v.age_m,v.vstdate,p.sex,ov.oqueue,date_format(v.vstdate,'%d/%m/%Y') as visitdate,ov.vsttime,os.bw FROM patient p  left outer join pname s on s.name=p.pname left outer join vn_stat v on v.hn=p.hn left outer join ovst ov on ov.vn=v.vn left outer join opdscreen os on os.vn=v.vn where v.vn='".$vn."'";
//echo $query_s_patient;
$s_patient = mysql_query($query_s_patient, $hos) or die(mysql_error());
$row_s_patient = mysql_fetch_assoc($s_patient);
$totalRows_s_patient = mysql_num_rows($s_patient);

function favi_cal($ideci){
  $deci=(int)$ideci;
  if($deci>0&&$deci<=25){
	  if(($deci)>=12.5){
      	$z='1/4';
	  }
	  else{
		 $z=""; 
	  }
  }
  else if($deci>25&&$deci<=50){
	  if(($deci)>=37.5){
      	$z='1/2';
	  }
	  else{
      	$z='1/4';		  
	  }
  }
  else if($deci>50&&$deci<=75){
	  if(($deci)>=62.5){
      $z='3/4';
	  }
	  else{
	  $z='1/2';	  
	  }
  }
  else if($deci>75||$deci=='00') {
	  if(($deci)>=87.5){
      $z="1";
	  }
	  else{
      $z='3/4';		  
	  }
  }
	return $z;
}

function favi_cal3($ideci){
	$deci=(int)$ideci;
  if($deci>0&&$deci<=25){
	  if(($deci)>=12.5){
      	$z=0.25;
	  }
	  else{
		 $z=0; 
	  }
  }
  else if($deci>25&&$deci<=50){
	  if(($deci)>=37.5){
      	$z=0.5;
	  }
	  else{
      	$z=0.25;		  
	  }
  }
  else if($deci>50&&$deci<=75){
	  if(($deci)>=62.5){
      $z=0.75;
	  }
	  else{
	  $z=0.5;	  
	  }
  }
  else if($deci>75||$deci=='00') {
	  if(($deci)>=87.5){
      $z=0;
	  }
	  else{
      $z=0.75;		  
	  }
  }
	
	return $z;
}

$stat=number_format(($row_s_patient['bw']*35)/200,2);
$cont=number_format(($row_s_patient['bw']*15)/200,2);

$a=explode('.',$stat);
$b=explode('.',$cont);

if($row_s_patient['bw']<=50){	
	$stat_dose="";
	if(favi_cal($a[1])=="1"){ $stat_dose= $a[0]+1; } else { if($a[0]!=0){ $stat_dose= $a[0]; if(favi_cal($a[1])!="1"&&favi_cal($a[1])!=""){ $stat_dose .=" + "; } } if(favi_cal($a[1])!="1"){ $stat_dose.= favi_cal($a[1]); } }
	$statmg=number_format2($_POST['bw']*35)." mg";
	
	$con_dose=""; if(favi_cal($b[1])=="1"){ $con_dose=$b[0]+1; } else { if($b[0]!=0){ $con_dose= $b[0]; if(favi_cal($b[1])!="1"&&favi_cal($b[1])!=""){ $con_dose.= " + "; } } if(favi_cal($b[1])!="1"){ $con_dose.= favi_cal($b[1]); } }
	
	$contmg=number_format2($_POST['bw']*15)." mg";
}

else if($row_s_patient['bw']>50&&$row_s_patient['bw']<90){	
	$stat_dose=9;
	$con_dose=4;
	$statmg="1800 mg";
	$contmg="800 mg";
}
else {
	$stat_dose=12;
	$con_dose=5;
	$statmg="2400 mg";
	$contmg="1000 mg";
}

 if($row_s_patient['bw']<=50){ $totaldrug=round((($a[0]+favi_cal3($a[1]))*2)+(($b[0]+favi_cal3($b[1]))*8)); } else if($row_s_patient['bw']>50&&$_POST['bw']<90){ $totaldrug= 50; } else { $totaldrug= 64;} 

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<?php include('java_css_file.php'); 
	include('include/datepicker/datepicker.php'); ?>
<script src="include/masked/js/jquery.mask.min.js" ></script>
	
<link rel="stylesheet" href="include/kohrx/css/kohrx.css"/>	
<script>
	$(document).ready(function(){
		$('.throw_error').hide();
		<?php if($_GET['action']!="cal"){ ?>
		$('.printbtn').hide();
		<?php } ?>
		set_cal( $("#startdate") );
		
		$('#save').click(function() {
			if ($('#stat').is(':checked')) {
				var stats='Y';
			}
			var formData = {action:"cal",vn:$('#vn').val(),stat_dose:$('#stat_dose').val(),con_dose:$('#con_dose').val(),startdate:$('#startdate').val(),starttime:$('#starttime').val(),drug:$('#selectdrug').val(),stat:stats}; //Array 
			$.ajax({
				url : "app_covid_drug_suggestion.php",
				type: "GET",
				data : formData,
				success: function(data, textStatus, jqXHR)
				{
					//data - response from server
					$('#success').fadeIn(2000).html(data);
					$('.printbtn').show();
				},
				error: function (jqXHR, textStatus, errorThrown)
				{
					$('.throw_error').fadeIn(1000).show();
				}
			});
		});		
	});
</script>	
<style>
@media print {
   .noprint,.printbtn { display:none; }
	    @page
    {
         size: 8.5in 11in; 
    }
	.table-bordered th,
	.table-bordered td {
	  border: 1px solid #000 !important;
	}
	table {border-collapse: collapse}
}	
</style>	
</head>

<body>
<?php if($_GET['action']!="cal"){ ?>	
<div class="card noprint" style="border: 0px">
	<div class="card-header">ข้อมูลทั่วไป</div>
	<div class="card-body" >
		<div><?php echo $row_s_patient['patient_name']; ?>&ensp;<strong>อายุ</strong>&nbsp;<?php echo $row_s_patient['age_y']; ?>&nbsp;ปี&nbsp;<?php echo $row_s_patient['age_m']; ?>&nbsp;เดือน&ensp;<strong>น้ำหนัก</strong>&nbsp;<?php echo $row_s_patient['bw']; ?>&nbsp;กก.</div>
		<div class="row mt-2">
			<label for="selectdrug" class="col-form-label col-form-label-sm col-sm-auto">ยา</label>
			<div class="col-sm-auto">
				<select id="selectdrug" name="selectdrug" class="form-control form-control-sm">
					<option value="1">Favipiravir 200</option>
					<option value="2">Molnupiravir 200</option>
				</select>
			</div>			
			<label for="startdate" class="col-form-label col-form-label-sm col-sm-auto">วันเริ่มต้น</label>
			<div class="col-sm-auto">
				<input type="text" name="startdate" id="startdate" value="<?php echo date('d/m/').(date('Y')+543); ?>" class="form-control form-control-sm" style=" padding:2px; padding-left: 2px; padding-right:2px; width:95px; height:30px;" />				
			</div>			
			<label  class="col-form-lebel col-form-label-sm col-sm-auto">ช่วงโดสแรก</label>
			<div class="col-sm-auto">
				<select id="starttime" name="starttime" class="form-control form-control-sm">
					<option value="8" selected>8:00</option>
					<option value="20">20:00</option>
				</select>
			</div>
			<label class="col-form-lebel col-form-label-sm col-sm-auto">ทันที</label>
			<div class="col-sm-auto">
				<input type="checkbox" id="stat" name="stat" value="Y" class="form-check-input"/>
			</div>	
			<label for="stat_dose" class="col-form-lebel col-form-label-sm col-sm-auto">stat Dose</label>
			<div class="col-sm-auto">
				<input class="form-control form-control-sm" type="text" id="stat_dose" name="stat_dose" size="3" value="<?php echo $stat_dose; ?>"/>
			</div>				
			<label for="con_dose" class="col-form-lebel col-form-label-sm col-sm-auto">continue Dose</label>
			<div class="col-sm-auto">
				<input class="form-control form-control-sm" type="text" id="con_dose" name="con_dose" size="3" value="<?php echo $con_dose; ?>"/>
			</div>
			<div class="col-sm-auto"><button class="btn btn-success btn-sm" id="save" name="save" >คำนวณ</button></div>
			<div class="col-sm-auto"><button class="btn btn-danger printbtn btn-sm" onClick="window.print();"><i class="fa fa-print"></i>&nbsp;ปริ้น</button></div>	

			<input type="hidden" id="vn" name="vn" value="<?php echo $vn; ?>"/>
			
		</div>
	</div>
</div>
	
<div class="alert alert-primary throw_error" role="alert" style="display: none">
  This is a primary alert—check it out!
</div>
<div id="success" class="p-4 printarea"></div>	

<?php } else {  ?>
<div class="thfont font20 p-2 text-center font_border">ตารางการรับประทานยา <?php if($_GET['drug']==1){ echo "Favipiravir 200mg."; } else { echo "Molnupiravir 200mg."; } ?></div>
<div class="thfont font20 text-center"><?php echo $row_s_patient['patient_name']; ?>&ensp;<strong>อายุ</strong>&nbsp;<?php echo $row_s_patient['age_y']; ?>&nbsp;ปี&nbsp;<?php echo $row_s_patient['age_m']; ?>&nbsp;เดือน&ensp;<strong>น้ำหนัก</strong>&nbsp;<?php echo number_format2($row_s_patient['bw']); ?>&nbsp;กก.&ensp;HN:&nbsp;<?php echo $row_s_patient['hn']; ?></div>	
<div class="noprint"><center class='h4'><span class="badge badge-danger">ใช้ทั้งหมด <?php echo $totaldrug." เม็ด"; ?></span></center></div>	
<table class="table table-bordered table-sm thfont font20 mt-2">
	<thead>
		<tr>
			<th class="text-center">วันที่</th>
			<th class="text-center">เวลา</th>
			<th class="text-center">จำนวนเม็ด</th>
			<th class="text-center">ช่องทำเครื่องหมาย <i class="fa fa-check"></i></th>
		</tr>
	</thead>
	<tbody>
		<?php 
			$statcount=0;
			$concount=0;
			if($_GET['starttime']==8){
				$daycount=5;
			}
			else if($_GET['starttime']==20){
				$daycount=6;
			}
			
		?>
		<?php for($i=0;$i<$daycount;$i++){ 
		?>
		<?php 
			$date=date_create(date_th2db($_GET['startdate']));
			date_add($date,date_interval_create_from_date_string($i." days"));
			
		?>
		<tr>
			<td rowspan="2" align="center" style="text-align: center;
  vertical-align: middle;"><?php echo dateThai(date_format($date,"Y-m-d")); ?></td>
			<td align="left pl-4"><?php if($i==0&&$_GET['starttime']==8&&$_GET['stat']=="Y"){ echo "08:00 (2 โมงเช้า)/(ทันที)"; } else if($i==0&&$_GET['starttime']==20){echo "-"; }else { echo "08:00 (2 โมงเช้า)"; } ?></td>
			<td align="center" class="font20 font_bord">
				<?php if($i==0&&$_GET['starttime']==8&&$statcount<=2){ echo $_GET['stat_dose'] ; $statcount++;}  
				else if($i!=0&&$statcount<2){ echo $_GET['stat_dose'] ; $statcount++; } 	
				else if($i!=0&&$statcount==2&&$concount<8){ echo $_GET['con_dose'] ; $concount=$concount+1;} ?>					
			</td>
			<td align="center"><?php if($i==0&&$_GET['starttime']==20){echo ""; } else { ?><i class="far fa-square" style="font-size: 30px"></i><?php } ?></td>
		</tr>
		<tr>
			<td align="left pl-4"><?php if($i==0&&$_GET['starttime']==20&&$_GET['stat']=="Y"){ echo "20:00 (2 ทุ่ม)/(ทันที)"; } else if($i==($daycount-1)&&$_GET['starttime']==20){echo "-"; } else { echo "20:00 (2 ทุ่ม)"; } ?></td>
			<td align="center" class="font20 font_bord"><?php if($i==0&&$_GET['starttime']==20&&$statcount<=2){ echo $_GET['stat_dose'] ; $statcount++;} 
			else if($i==0&&$statcount<=2){ echo $_GET['stat_dose'] ; $statcount++; }
				else if($i!=0&&$statcount>=2&&$concount<8){ echo $_GET['con_dose'] ; $concount=$concount+1;} ?>					
			
			</td>
			<td align="center"><?php if($i==($daycount-1)&&$_GET['starttime']==20){echo ""; } else { ?><i class="far fa-square" style="font-size: 30px"></i><?php } ?></i></td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<div class="font20 font_bord thfont mt-4">คำแนะนำการใช้ยา</div>	
<div class="font20 thfont mt-2" style="padding-left: 20px">
	- ฟาวิพิราเวียร์เป็นยาต้านไวรัส ใช้สำหรับรักษาผู้ป่วยโควิด-19<br>

	&emsp;วันที่ 1 รับประทานครั้งละ <?php echo $_GET['stat_dose']; ?> เม็ด ทุก 12 ชั่วโมง<br>

	&emsp;วันที่ 2-5 รับประทานครั้งละ <?php echo $_GET['con_dose']; ?> เม็ด ทุก 12 ชั่วโมง<br>

	- อาการข้างเคียง อาจทำให้มีอาการท้องเสีย คลื่นใส้ อาเจียนได้<br>

	- กรุณาทำเครื่องหมาย <i class="fa fa-check"></i> ในช่อง <i class="far fa-square font20"></i> เพื่อบันทึกการกินยา ป้องกันการลืมและความสับสน<br>
	</div>
<div class="text-center thfont font20" style="margin-left: 40%; margin-top:100px">
	ฝ่ายเภสัชกรรมและคุ้มครองผู้บริโภค โรงพยาบาลมหาชนะชัย<br>
	เบอร์โทรติดต่อ 0987704486<br><br><br>
	ลงชื่อเภสัชกร...............................................<br>
	
<?php echo doctorname($_SESSION['doctorcode']); ?>
</div>	
<?php } ?>
	
</body>
</html>
<?php mysql_free_result($s_patient); ?>