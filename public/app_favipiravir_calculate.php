<?php
if($_POST['bw']!=0){ 
function favi_cal($ideci){
	$deci=(int)$ideci;
  if($deci>0&&$deci<=25){
	  if(($deci)>=12.5){
      	$z='1/4';
	  }
	  else {
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
  else if($deci>75&&$deci<=99.99) {
	  if(($deci)>=87.5){
      $z="1";
	  }
	  else{
      $z='3/4';		  
	  }
  }
	return $z;
}

function favi_cal2($ideci,$last){	
  $deci=(int)$ideci;
	if($deci>0&&$deci<=25){
	  if($deci>=12.5){
      $z='<pie class="twentyfive"></pie>';
	  }
	  else{
	  $z="";
	  }
  }
  else if($deci>25&&$deci<=50){
	  if(($deci)>=37.5){
      $z='<pie class="fifty"></pie>';
	  }
	  else{
      $z='<pie class="twentyfive"></pie>';
	  }
  }
  else if($deci>50&&$deci<=75){
	  if(($deci)>=62.5){
      $z='<pie class="seventyfive"></pie>';
	  }
	  else{
      $z='<pie class="fifty"></pie>';
	  }
  }
  else if($deci>75&&$deci<=99.99) {
	  if(($deci)>=87.5){
      $z='<pie class="onehundred"><span style=\"position:absolute;margin-top:10px;margin-left:13px;\" class="text-white">'.($last).'</span></pie>';
	  }
	  else{
      $z='<pie class="seventyfive"></pie>';
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
  else if($deci>75&&$deci<=99.99) {
	  if(($deci)>=87.5){
      $z=0;
	  }
	  else{
      $z=0.75;		  
	  }
  }
	
	return $z;
}

function number_format2($number){
	if($number!=0){
	$number2=str_replace('.00','',number_format($number,2));
	return $number2;
	}
	if($number==0||$number==""){
	return 0;
	}
}

function date_th2db($date){

	$date1=explode('/',$date);
	$edate=($date1[2]-543)."-".$date1[1]."-".$date1[0];
	return $edate;

}
function date_db2th($date){
	if($date!=""){
	$date1=explode('-',$date);
	$edate=$date1[2]."/".$date1[1]."/".($date1[0]+543);
	return $edate;
	}
}	

function dateThai($date){
	if($date!=""){
	$_month_name = array("01"=>"มกราคม","02"=>"กุมภาพันธ์","03"=>"มีนาคม","04"=>"เมษายน","05"=>"พฤษภาคม","06"=>"มิถุนายน","07"=>"กรกฎาคม","08"=>"สิงหาคม","09"=>"กันยายน","10"=>"ตุลาคม","11"=>"พฤศจิกายน","12"=>"ธันวาคม");
	$yy=substr($date,0,4);$mm=substr($date,5,2);$dd=substr($date,8,2);$time=substr($date,11,8);
	$yy+=543;
	$dateT=intval($dd)." ".$_month_name[$mm]." ".$yy." ".$time;
	return $dateT;
		}
	 }
$stat=number_format(($_POST['bw']*35)/200,2);
$cont=number_format(($_POST['bw']*15)/200,2);

$a=explode('.',$stat);
$b=explode('.',$cont);

if($_POST['bw']<=50){	
	$statdose="";
	if(favi_cal($a[1])=="1"){ $statdose= $a[0]+1; } else { if($a[0]!=0){ $statdose= $a[0]; if(favi_cal($a[1])!="1"&&favi_cal($a[1])!=""){ $statdose .=" + "; } } if(favi_cal($a[1])!="1"){ $statdose.= favi_cal($a[1]); } }
	$statmg=number_format2($_POST['bw']*35)." mg";
	
	$contdose=""; if(favi_cal($b[1])=="1"){ $contdose=$b[0]+1; } else { if($b[0]!=0){ $contdose= $b[0]; if(favi_cal($b[1])!="1"&&favi_cal($b[1])!=""){ $contdose.= " + "; } } if(favi_cal($b[1])!="1"){ $contdose.= favi_cal($b[1]); } }
	
	$contmg=number_format2($_POST['bw']*15)." mg";
}

else if($_POST['bw']>50&&$_POST['bw']<90){	
	$statdose=9;
	$contdose=4;
	$statmg="1800 mg";
	$contmg="800 mg";
}
else {
	$statdose=12;
	$contdose=5;
	$statmg="2400 mg";
	$contmg="1000 mg";
}
	
} 

?>

<!doctype html>
<html><head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=0.5">	
<title>คำนวณยา Favipiravir : by KOHRX </title>
<!--	
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
-->
<?php if(!isset($_POST['action'])&&($_POST['action']!="cal")){ ?>
<script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>	
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js" integrity="sha384-VHvPCCyXqtD5DqJeNxl2dtTyhF78xXNXdkwX1CZeRusQfRKp+tA7hAShOK/B/fQ2" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>	
<script src="https://kit.fontawesome.com/1ed6ef1358.js" crossorigin="anonymous"></script>
	
<?php include('include/datepicker/datepicker.php'); ?>	
	
<script>
$(document).ready(function(){
	
	set_cal( $("#startdate") );
	$('.printbtn').hide();
	$('#favi_table').hide();

	$('#bw').keyup(function(){
		$("#result").html("");
		$("#favi_table").hide();
		var val = $(this).val();
		if(isNaN(val)){
			 val = val.replace(/[^0-9\.]/g,'');
			 if(val.split('.').length>2) 
				 val =val.replace(/\.+$/,"");
		}
		$(this).val(val); 	
	});
	
	$('#btn-cal').click(function(){
            $.ajax({
				   type: "POST",
				   url: "app_favipiravir_calculate.php",
				   cache: false,
				   data: {bw:$('#bw').val(),action:'cal'},
				   success: function(html)
					{                        
                        $("#result").html(html);
						$("#result2").html("");
						$('#favi_table').show();
					}
				 });    
		
	});
		$('#report').click(function() {
			if ($('#stat').is(':checked')) {
				var stats='Y';
			}
			var formData = {action:"cal2",bw:$('#bw').val(),startdate:$('#startdate').val(),starttime:$('#starttime').val(),stat:stats,interval:$('#interval').val()}; //Array 
			$.ajax({
				url : "app_favipiravir_calculate.php",
				type: "POST",
				data : formData,
				success: function(data, textStatus, jqXHR)
				{
					//data - response from server
					$('#result2').fadeIn(2000).html(data);
					window.print();
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
pie {
  width: 40px;
  height: 40px;
  display: block;
  border-radius: 50%;
  background-color: #4D8A5C;
  border: 2px solid #4D8A5C;
  float: left;
  margin: 5px;
}

.ten {
  background-image: linear-gradient(126deg, transparent 50%, white 50%), linear-gradient(90deg, white 50%, transparent 50%);
}

.twentyfive {
  background-image: linear-gradient(180deg, transparent 50%, white 50%), linear-gradient(90deg, white 50%, transparent 50%);
}

.fifty {
  background-image: linear-gradient(90deg, white 50%, transparent 50%);
}


/* Slices greater than 50% require first gradient
   to be transparent -> green */

.seventyfive {
  background-image: linear-gradient(180deg, transparent 50%, #4D8A5C 50%), linear-gradient(90deg, white 50%, transparent 50%);
}

.onehundred {
  background-image: none;
}
	.td_white td{
		background-color: white;
	}
	
input.largerCheckbox {
            width: 25px;
            height: 25px;
        }	
</style>
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
<?php } ?>	
</head>

<body>
<?php if(!isset($_POST['action'])&&($_POST['action']!="cal")){ ?>
<div class="container-fluid pt-2 noprint">
	<div><h3><i class="fas fa-calculator"></i> โปรแกรมคำนวณ Favipiravir ตามน้ำหนัก </h3><span style="font-size: 12px" class="text-danger">สงวนลิขสิทธิ์ 2565, version 1, ผู้จัดทำ: ภก.อรรถกร บุญแจ้ง</span></div>
	<div class="card">
		<div class="card-header">คำนวณขนาดยาและจำนวนเม็ด</div>
		<div class="card-body">
			<div class="row">
				<label class="col-form-label col-form-label-lg col-sm-auto">น้ำหนัก </label>
				<div class="col-auto"><input class="form-control form-control-lg" id="bw" name="bw" placeholder="กิโลกรัม" style="width: 100px" /></div>
				<div class="col-auto"><button class="btn btn-success btn-lg" id="btn-cal" name="btn-cal">คำนวณ</button></div>
			</div>
			<!--ผล-->
			<div id="result"></div>
			<!--ผล-->			
		</div>
	</div>
</div>
<?php } else { if($_POST['action']=="cal"){ ?>
<div class="row mt-2 noprint">
	<!-- stat -->
	<div class="col" style="z-index: 1">
		<div class="card" style="border: solid 1px #C5DBF7">
			<div class="card-header" style="background-color: #C5DBF7"><strong style="color: #2778E1">Stat Dose</strong> <span style="color: #4D4D4D">70 mg/kg/day</span></div>
			<div class="card-body" style="background-color: #E5EFFB">
				<div class="row">
					<div class="col-sm-4"><nobr>ปริมาณ/โดส</nobr></div>
					<div class="col-sm-auto"><?php echo $statmg; ?></div>
				</div>
				<div class="row">
					<div class="col-sm-4">คำนวณเม็ด</div>
					<div class="col-sm-auto"><?php  
						
						//แสดงจำนวนเม็ด
						echo $statdose;
						
				   
						?> เม็ด</div>
					
				</div>
				
				<div class="card mt-1" style="background-color: #DAE8F9; border: 0px">
						<div class="card-body p-2" style="border: dashed 2px #C5DBF7; border-radius: 5px" >
						<?php if($_POST['bw']<=50){ $piemax=$a[0]; } else if($_POST['bw']>50&&$_POST['bw']<90){ $piemax=9; } else { $piemax=12;} ?>
						<?php for($i=1;$i<=$piemax;$i++){
							echo "<pie class=\"onehundred\"><span style=\"position:absolute;margin-top:5px;margin-left:13px\" class='text-white'>".$i."</span></pie>&nbsp;";
						} ?> 
						
						<?php    if($_POST['bw']<=50){ echo favi_cal2($a[1],$i); } ?>
						
						</div>
			  </div>
			</div>
		</div>
	</div>
	<!-- stat -->
	<i class="fas fa-angle-double-right position-absolute" aria-hidden="true" style="font-size: 50px; left:50%; transform: translate(-50%,-50%); margin-top: 80px; color: #FC410D; z-index: 2"></i>

	<!-- con. -->
	<div class="col" style="z-index: 1">
		<div class="card" style="border: solid 1px #FFCFB0">
			<div class="card-header" style="background-color: #FFCFB0"><strong style="color: #B76C0B">Continue Dose</strong> <span style="color: #4D4D4D">30 mg/kg/day</span></div>
			<div class="card-body" style="background-color: #FFF7E7">
				<div class="row">
					<div class="col-sm-4"><nobr>ปริมาณ/โดส</nobr></div>
					<div class="col-sm-auto"><?php echo $contmg; ?></div>
				</div>
				<div class="row">
					<div class="col-sm-4">คำนวณเม็ด</div>
					<div class="col-sm-auto"><?php  
					
						//แสดงจำนวนเม็ด
						if($contdose==""){
							echo "1/4";
						}
						else { echo $contdose; }
						?> เม็ด</div>
				</div>
				<div class="card mt-1" style="background-color: #FFECC7; border: 0px">
						<div class="card-body p-2" style="border: dashed 2px #FFCFB0; border-radius: 5px"">
						<?php if($_POST['bw']<=50){ $piemax=$b[0]; } else if($_POST['bw']>50&&$_POST['bw']<90){ $piemax=4; } else { $piemax=5;} ?>
					
						<?php $k=0; for($i=1;$i<=$piemax;$i++){
							echo "<pie class=\"onehundred\"><span style=\"position:absolute;margin-top:5px;margin-left:13px\" class='text-white'>".$i."</span></pie>&nbsp;";
							
						} ?> 
						
						<?php if($_POST['bw']<=50){ if((int)$b[0]==0&&(int)$b[1]<12.5){ echo '<pie class="twentyfive"></pie>';
 } else{ echo favi_cal2($b[1],$i); } } ?>
						</div>	
			  </div>				
			</div>
		</div>		
	</div>
	<!-- con. -->
	<!--conclude-->
</div>	
	<div class="card mt-2" style="background-color: #D291BC">
		<div class="card-body p-2"><center><h3 class="text-white">ต้องใช้ทั้งหมด <span class="badge " style="background-color: #BE609E; font-size: 30px"><?php if($_POST['bw']<=50){ echo round((($a[0]+favi_cal3($a[1]))*2)+(($b[0]+favi_cal3($b[1]))*8)); } else if($_POST['bw']>50&&$_POST['bw']<90){ echo 50; } else { echo 64;} ?></span>
 เม็ด</h3></center></div>
	</div>		
<?php }}?>	
<?php if(!isset($_POST['action'])&&($_POST['action']!="cal")){ ?>
	<div class="container-fluid pt-2" id="favi_table">
		<div class="card noprint">
			<div class="card-header">คำนวณตารางการให้ยา</div>
			<div class="card-body">
				<div class="row">
					<label for="startdate" class="col-form-label col-form-label-sm col-sm-auto">วันเริ่มต้น</label>
					<div class="col-sm-auto">
						<input type="text" name="startdate" id="startdate" value="<?php echo date('d/m/').(date('Y')+543); ?>" class="form-control form-control-sm" style=" padding:2px; padding-left: 2px; padding-right:2px; width:95px; height:30px;" />				
					</div>
					<label  class="col-form-lebel col-form-label-sm col-sm-auto">เวลา</label>
					<div class="col-sm-auto">
						<select id="interval" name="interval" class="form-control form-control-sm">
							<option value="1" selected>08:00-20:00</option>
							<option value="2">07:00-19:00</option>
						</select>
					</div>
					
					<label  class="col-form-lebel col-form-label-sm col-sm-auto">โดสแรก</label>
					<div class="col-sm-auto">
						<select id="starttime" name="starttime" class="form-control form-control-sm">
							<option value="8" selected>ช่วงเช้า</option>
							<option value="20">ช่วงเย็น</option>
						</select>
					</div>
					<label class="col-form-lebel col-form-label-sm col-sm-auto">ทันที</label>
					<div class="col-sm-auto">
						<input type="checkbox" id="stat" name="stat" value="Y" class="form-check-input largerCheckbox"/>
					</div>	
					<div class="col-auto"><button class="btn btn-primary btn-sm" id="report" name="report">ออกรายงาน</button></div>
					<div class="col-sm-auto"><button class="btn btn-danger printbtn btn-sm" onClick="window.print();"><i class="fa fa-print"></i>&nbsp;ปริ้น</button></div>
				</div>
			</div>
		</div>
				<!-- result2 -->
				<div id="result2"></div>
				<!-- result2 -->

	</div>

<?php } else{
	if($_POST['action']=="cal2"){ ?>
	<div class="p-2 text-center font_border"><h3>ตารางการรับประทานยา Favipiravir 200 MG.</h3></div>
<table class="table table-bordered table-sm mt-2 table-striped table-hover" style="width: 100%; font-size: 20px">
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
			if($_POST['starttime']==8){
				$daycount=5;
			}
			else if($_POST['starttime']==20){
				$daycount=6;
			}
			
		?>
		<?php for($i=0;$i<$daycount;$i++){ 
		?>
		<?php 
			$date=date_create(date_th2db($_POST['startdate']));
			date_add($date,date_interval_create_from_date_string($i." days"));	
		?>
		<tr>
			<td rowspan="2" align="center" style="text-align: center;
  vertical-align: middle;background-color:  #FFFFFF"><strong><?php echo dateThai(date_format($date,"Y-m-d")); ?></strong></td>
			<td align="left" style="padding-left: 20px"><?php if($i==0&&$_POST['starttime']==8&&$_POST['stat']=="Y"){ echo "ทันที"; } else if($i==0&&$_POST['starttime']==20){echo "-"; }else { if($_POST['interval']==1){ echo "08:00 (2 โมงเช้า)"; } else if($_POST['interval']==2){ echo "07:00 (1 โมงเช้า)"; } } ?></td>
			<td align="center" class="font20 font_bord">
				<?php if($i==0&&$_POST['starttime']==8&&$statcount<=2){ echo $statdose;  $statcount++;}  
				else if($i!=0&&$statcount<2){ echo $statdose; $statcount++; } 	
				else if($i!=0&&$statcount==2&&$concount<8){ 
						if($contdose==""){
							echo "1/4";
						}
						else { echo $contdose; }
					$concount=$concount+1;} ?>					
			</td>
			<td align="center"><?php if($i==0&&$_POST['starttime']==20){echo ""; } else { ?><i class="far fa-square" style="font-size: 30px"></i><?php } ?></td>
		</tr>
		<tr>
			<td align="left " style="padding-left: 20px"><?php if($i==0&&$_POST['starttime']==20&&$_POST['stat']=="Y"){ echo "ทันที"; } else if($i==($daycount-1)&&$_POST['starttime']==20){echo "-"; } else { if($_POST['interval']==1){ echo "20:00 (2 ทุ่ม)"; } else if($_POST['interval']==2){ echo "19:00 (1 ทุ่ม)"; } } ?></td>
			<td align="center" class="font20 font_bord"><?php if($i==0&&$_POST['starttime']==20&&$statcount<=2){ echo $statdose; $statcount++;} 
			else if($i==0&&$statcount<=2){ echo $statdose; $statcount++; }
				else if($i!=0&&$statcount>=2&&$concount<8){ 
						if($contdose==""){
							echo "1/4";
						}
						else { echo $contdose; }				
					$concount=$concount+1;} ?>					
			
			</td>
			<td align="center"><?php if($i==($daycount-1)&&$_POST['starttime']==20){echo ""; } else { ?><i class="far fa-square" style="font-size: 30px"></i><?php } ?></i></td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<?php }}?>
<?php if(!isset($_POST['action'])){ ?>
<div class="text-center m-3">จัดทำโดย : <strong>ภก.อรรถกร บุญแจ้ง </strong>เภสัชกร ชำนาญการ โรงพยาบาลมหาชนะชัย</div>
<?php } ?>
</body>
</html>