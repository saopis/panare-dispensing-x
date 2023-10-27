<?php ob_start();?>
<?php session_start();?>
<?php /*if(($_SESSION['r_opd']!='Y') and ($_SESSION['r_finance']!='Y')){
	echo "คุณไม่ได้รับสิทธิ์ให้ใช้งานในส่วนนี้";
	exit();
	} 
	*/
?>
<?php require_once('Connections/hos.php'); ?>
<?php include('include/function.php'); ?>
<?php include('include/function_sql.php'); ?>
<?php if($_POST['hn']!=""){
mysql_select_db($database_hos, $hos);
$query_rs_setting = "select * from ".$database_kohrx.".kohrx_dispensing_setting order by id ASC";
$rs_setting = mysql_query($query_rs_setting, $hos) or die(mysql_error());
$row_rs_setting = mysql_fetch_assoc($rs_setting);
$totalRows_rs_setting = mysql_num_rows($rs_setting);

$i=0; do{ $i++;
$row_setting[$i]=$row_rs_setting['value'];} 
while($row_rs_setting = mysql_fetch_assoc($rs_setting));

mysql_free_result($rs_setting);
	
$hn=$hn=sprintf("%".$row_setting[24]."d", $_POST['hn']);
mysql_select_db($database_hos, $hos);
$query_s_patient = "SELECT v.vn,v.hn, concat(p.pname,p.fname,'    ',p.lname) as patient_name,v.age_y,v.age_m,v.vstdate,v.cid FROM patient p  left outer join pname s on s.name=p.pname left outer join vn_stat v on v.hn=p.hn where p.hn='".$hn."'";
//echo $query_s_patient;
$s_patient = mysql_query($query_s_patient, $hos) or die(mysql_error());
$row_s_patient = mysql_fetch_assoc($s_patient);
$totalRows_s_patient = mysql_num_rows($s_patient);	

	
	if($_POST['action']=="save"){
		$qty=$_POST['qty'];
		$use_date=$_POST['use_date'];
		$use_time=$_POST['use_time'];
		$in_out=$_POST['in_out'];
		$doctor=$_POST['doctor'];
		$remark=$_POST['remark'];

		mysql_select_db($database_hos, $hos);
		$query_insert = "insert into ".$database_kohrx.".kohrx_favipiravir_use (hn,use_date,use_time,qty,in_out_method,doctor,remark) value ('".$hn."','".date_th2db($use_date)."','".$use_time.":00',".$qty.",'".$in_out."','".$doctor."','".$remark."') ";
		$insert = mysql_query($query_insert, $hos) or die(mysql_error());
		echo "<script>page_load2('left-app','app_favipiravir_register_record.php');page_load2('main-app','app_favipiravir_register_list.php');</script>";
	}
}

?>
<?php 
if($_POST['id']!=""){
mysql_select_db($database_hos, $hos);
	$query_rs_edit = "select * from ".$database_kohrx.".kohrx_favipiravir_use where id='".$_POST['id']."'";
	$rs_edit = mysql_query($query_rs_edit, $hos) or die(mysql_error());
	$row_rs_edit = mysql_fetch_assoc($rs_edit);
	$totalRows_rs_edit = mysql_num_rows($rs_edit);		
} 
//========== แพทย์ผู้สั่งใช้ยา ==========//
mysql_select_db($database_hos, $hos);
$query_rs_doctor = "SELECT d.name,o.doctorcode FROM ".$database_kohrx.".kohrx_rx_person o left outer join doctor d on d.code=o.doctorcode ";
$rs_doctor = mysql_query($query_rs_doctor, $hos) or die(mysql_error());
$row_rs_doctor = mysql_fetch_assoc($rs_doctor);
$totalRows_rs_doctor = mysql_num_rows($rs_doctor);

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>

<script>
$(document).ready(function () {
	set_cal( $("#use_date"));	
	set_cal( $("#use_date2"));	
	set_cal( $("#use_date3"));	
	const timenow = Date().slice(16,21);
    $('#use_time').val(timenow);
    $('#use_time3').val(timenow);
	//$('#use_time').mask('99:99′);
  //$('#client-number').mask('0000', {placeholder: '0000'});
  
  /*Esse foi o melhor script que consegui fazer, porem ele não permite backspace, delete nem arrows, o que torna a experiência ruim*/
				$('#hn').bind('keyup', function(e) {
				if(e.which == '13'){ //enter
				$.ajax({
				url : "app_favipiravir_register_record.php",
				type: "POST",
				data : {hn:$(this).val()},
				success: function(data, textStatus, jqXHR)
				{
					//data - response from server
					$('#left-app').html(data);
					$("#qty").focus();
				},
				error: function (jqXHR, textStatus, errorThrown)
				{
					//$('.throw_error').fadeIn(1000).show();
				}
			});
					 }

				});	 
	
				$('#qty').bind('keyup', function(e) {
				if(e.which == '13'){ //enter
				if($(this).val().match(/^\d+$/)&&$(this).val()>0){
					$.ajax({
					url : "app_favipiravir_register_record.php",
					type: "POST",
					data : {hn:'<?php echo $hn; ?>',qty:$(this).val(),use_date:$('#use_date').val(),use_time:$('#use_time').val(),in_out:$('#in_out').val(),doctor:$('#doctor').val(),remark:$('#remark').val(),action:'save'},
					success: function(data, textStatus, jqXHR)
					{
						//data - response from server
						$('#left-app').html(data);

					},
					error: function (jqXHR, textStatus, errorThrown)
					{
						//$('.throw_error').fadeIn(1000).show();
					}
					});
					
					}
					else{
						alert('กรุณากรอกตัวเลขและต้องมีค่ามากกว่า 0')
						return false;
					}
				}

				});	 
				/////////////////////////
				$('#edit').click(function(){

							swal({
								title:"ระบบกำลังประมวลผล", 
								text:"กรุณารอสักครู่...",
								icon: "https://uploads.toptal.io/blog/image/122376/toptal-blog-image-1489080120310-07bfc2c0ba7cd0aee3b6ba77f101f493.gif",
								buttons: false,      
								closeOnClickOutside: false,
								//timer: 3000,
								//icon: "success"
							});
					
							$.ajax({
							url : "app_favipiravir_register_list.php",
							type: "POST",
							data : {id:$('#id').val(),use_date:$('#use_date2').val(),use_time:$('#use_time2').val(),in_out:$('#in_out2').val(),qty:$('#qty2').val(),doctor:$('#doctor').val(),remark:$('#remark').val()},
							success: function(data, textStatus, jqXHR)
							{
								//data - response from server
								$('#Modal').modal('hide');
								$('#main-app').html(data);
								swal.close();
							},
							error: function (jqXHR, textStatus, errorThrown)
							{
								//$('.throw_error').fadeIn(1000).show();
							}
							});					
				});
				//////////////
				$('#btn-receive').click(function(){
					$.ajax({
					url : "app_favipiravir_register_record.php",
					type: "POST",
					data : {action:'receive'},
					success: function(data, textStatus, jqXHR)
					{
						//data - response from server
						$('#left-app').html(data);

					},
					error: function (jqXHR, textStatus, errorThrown)
					{
						//$('.throw_error').fadeIn(1000).show();
					}
					});
					
				});
	///////////////////////////////
				$('#receive-save').click(function(){
						swal({
							title:"ระบบกำลังประมวลผล", 
							text:"กรุณารอสักครู่...",
							icon: "https://uploads.toptal.io/blog/image/122376/toptal-blog-image-1489080120310-07bfc2c0ba7cd0aee3b6ba77f101f493.gif",
							buttons: false,      
							closeOnClickOutside: false,
								//timer: 3000,
								//icon: "success"
							});
					$.ajax({
					url : "app_favipiravir_register_list.php",
					type: "POST",
					data : {action:'receive',use_date:$('#use_date3').val(),use_time:$('#use_time3').val(),qty:$('#qty3').val()},
					success: function(data, textStatus, jqXHR)
					{
						//data - response from server
						$('#main-app').html(data);
						page_load2('left-app','app_favipiravir_register_record.php');
						swal.close();

					},
					error: function (jqXHR, textStatus, errorThrown)
					{
						//$('.throw_error').fadeIn(1000).show();
					}
					});
					
				});
	
});

function input_focus(id){
	$('#'+id).focus();
}	

function page_load2(divid,page){
	//$('#indicator').show();
	$("#"+divid).load(page,function(responseTxt, statusTxt, xhr){
        if(statusTxt == "success")
		$('#hn').focus();
		//$('#indicator').hide();            
        if(statusTxt == "error")
            alert("โหลดข้อมูลไม่สำเร็จ กรุณาลองใหม่อีกครั้ง");
		//$('#indicator').hide();            
    	});
	}
	
</script>	
</head>

<body>
<?php if($_POST['id']==""){ ?>
	
<nav class="navbar navbar-light text-white" style="background-color: #D199B3">
    บันทึกใช้ยาผู้ป่วย <button class="btn btn-danger btn-sm" id="btn-receive">รับยา</button>
</nav>
<?php if($_POST['action']=="receive"){ ?>
	<div class="p-2">
		<div class="card">
			<div class="card-header">บันทึกรับยา</div>
			<div class="card-body">	
			<div class="row mt-2">
				<label for="use_date3" class="col-form-label col-form-label-sm col-sm-3">วัน</label>
			<div class="col-sm-auto">
				<input type="text" name="use_date3" id="use_date3" value="<?php echo date('d/m/').(date('Y')+543); ?>" class="form-control form-control-sm" style=" padding:2px; padding-left: 2px; padding-right:2px; width:95px; height:30px;" />				
			</div>			
				</div>	
				<div class="row mt-2">
					<label class="col-form-label col-form-label-sm col-sm-3">เวลา</label>
					<div class="col-sm-auto">
					  <input type="text" id="use_time3" name="use_time3" class="form-control form-control-sm" style="padding: 3px;width: 95px" />    
					</div>					
				</div>
				<div class="row mt-2">
					<label class="col-form-label col-form-label-sm col-sm-3">จำนวน</label>
					<div class="col-sm-auto">
					  <input type="text" id="qty3" name="qty3" autofocus class="form-control form-control-sm" style="padding: 3px;width: 95px" />    
					</div>					
					
				</div>
				<div class="row mt-2">
					<div class="col-sm-3"></div>
					<div class="col-sm-auto">
						<button class="btn btn-sm btn-success" id="receive-save">บันทึก</button>
						<button class="btn btn-sm btn-warning" id="receive-cancel" onClick="page_load2('left-app','app_favipiravir_register_record.php');">ยกเลิก</button>

					</div>
				</div>	

				</div>
			</div>
		</div>
<?php }  else { ?>	
<div class="p-2">

	<div class="card  ">
	<div class="card-header">บันทึก จ่าย-คืน</div>
	<div class="card-body p-1">	
	<?php if(!isset($_POST['hn'])&&($_POST['hn']=="")){ ?>
	<div class="row p-2 thfont font14">
		<label class="col-form-label col-auto">ค้นหา HN ผู้ป่วย</label>
		<div class="col-auto">
			<input id="hn" class="form-control form-control-sm" type="text" autofocus  style="" />
		</div>
	</div>
	<?php } else { ?>
		<?php if($totalRows_s_patient<>0){ ?>
			<div class="p-2 thfont font14">
					<div>
						<strong>ชื่อ :&nbsp;</strong><?php echo $row_s_patient['patient_name']; ?>
					</div>
					<div>
						<strong>CID :&nbsp;</strong><?php echo $row_s_patient['cid']; ?>
					</div>
					
					<div class="row mt-2">
						<label for="startdate" class="col-form-label col-form-label-sm col-sm-3">วัน</label>
			<div class="col-sm-auto">
				<input type="text" name="use_date" id="use_date" value="<?php echo date('d/m/').(date('Y')+543); ?>" class="form-control form-control-sm" style=" padding:2px; padding-left: 2px; padding-right:2px; width:95px; height:30px;" />				
			</div>			
				</div>	
				<div class="row mt-2">
					<label class="col-form-label col-form-label-sm col-sm-3">เวลา</label>
					<div class="col-sm-auto">
					  <input type="text" id="use_time" name="use_time" class="form-control form-control-sm" style="padding: 3px;width: 95px" />    
					</div>					
				</div>
				<div class="row mt-2">
					<label class="col-form-label col-form-label-sm col-sm-3">วิธีการ</label>
					<div class="col-sm-auto">
						<select id="in_out" name="in_out" class="form-control form-control-sm" style="width: 95px">
							<option value="21">จ่าย</option>
							<option value="12">คืน</option>
						</select>
					</div>	
				</div>	
              <div class="row mt-2" >
                <label for="dispen" class="col-sm-3 col-form-label col-form-label-sm font12">ผู้บันทึก</label>

                  <div class="col-sm-auto">
                    <select name="doctor" class="form-control form-control-sm font12" id="doctor" style="padding-left:2px; padding-right:2px;"  >
                    <?php
					do {  
					?>
                    <option value="<?php echo $row_rs_doctor['doctorcode']?>"<?php if (!(strcmp($row_rs_doctor['doctorcode'],$_SESSION['doctorcode']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_doctor['name']?></option>
                    <?php
					} while ($row_rs_doctor = mysql_fetch_assoc($rs_doctor));
					  $rows = mysql_num_rows($rs_doctor);
					  if($rows > 0) {
						  mysql_data_seek($rs_doctor, 0);
						  $row_rs_doctor = mysql_fetch_assoc($rs_doctor);
					  }
?>
                  </select>
                      
                  </div>
              </div>
				<div class="row mt-2">
					<label class="col-form-label col-form-label-sm col-sm-3" style="font-size: 10px">หมายเหตุ</label>
					<div class="col-sm-auto">
					  <input type="text" id="remark" name="remark" autofocus class="form-control form-control-sm" style="padding: 3px;" />    
					</div>					
					
				</div>
				


				<div class="row mt-2">
					<label class="col-form-label col-form-label-sm col-sm-3">จำนวน</label>
					<div class="col-sm-auto">
					  <input type="text" id="qty" name="qty" autofocus class="form-control form-control-sm" style="padding: 3px;width: 95px" />    
					</div>					
					
				</div>
				<div class="thfont font11 mt-2 text-primary">ใส่จำนวนแล้วกด enter เพื่อบันทึก</div>
				
			</div>
		<?php  } ?>
	<?php } ?>
</div>
</div>
</div>		
<?php } ?>	
<?php }  else { ?>
			<div class="p-2 thfont font14">
					<div>
						<strong>ชื่อ :&nbsp;</strong><?php echo $row_s_patient['patient_name']; ?>
					</div>
					<div>
						<strong>CID :&nbsp;</strong><?php echo $row_s_patient['cid']; ?>
					</div>
					
					<div class="row mt-2">
						<label for="startdate" class="col-form-label col-form-label-sm col-sm-3">วัน</label>
			<div class="col-sm-auto">
				<input type="text" name="use_date2" id="use_date2" value="<?php echo date_db2th($row_rs_edit['use_date']); ?>" class="form-control form-control-sm" style=" padding:2px; padding-left: 2px; padding-right:2px; width:95px; height:30px;" />				
			</div>			
				</div>	
				<div class="row mt-2">
					<label class="col-form-label col-form-label-sm col-sm-3">เวลา</label>
					<div class="col-sm-auto">
					  <input type="text" id="use_time2" name="use_time2" class="form-control form-control-sm" style="padding: 3px;width: 95px" value="<?php echo substr($row_rs_edit['use_time'],0,5); ?>" />    
					</div>					
				</div>
				<div class="row mt-2">
					<label class="col-form-label col-form-label-sm col-sm-3">วิธีการ</label>
					<div class="col-sm-auto">
						<select id="in_out2" name="in_out2" class="form-control form-control-sm" style="width: 95px">
							<option value="21" <?php if($row_rs_edit['in_out_method']=="21"){ echo "selected"; } ?>>จ่าย</option>
							<option value="12" <?php if($row_rs_edit['in_out_method']=="12"){ echo "selected"; } ?>>คืน</option>
						</select>
					</div>	
				</div>	
              <div class="row mt-2" >
                <label for="dispen" class="col-sm-3 col-form-label col-form-label-sm font12">ผู้บันทึก</label>

                  <div class="col-sm-auto">
                    <select name="doctor" class="form-control form-control-sm font12" id="doctor" style="padding-left:2px; padding-right:2px;"  >
                    <?php
					do {  
					?>
                    <option value="<?php echo $row_rs_doctor['doctorcode']?>"<?php if (!(strcmp($row_rs_doctor['doctorcode'],$row_rs_edit['doctor']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_doctor['name']?></option>
                    <?php
					} while ($row_rs_doctor = mysql_fetch_assoc($rs_doctor));
					  $rows = mysql_num_rows($rs_doctor);
					  if($rows > 0) {
						  mysql_data_seek($rs_doctor, 0);
						  $row_rs_doctor = mysql_fetch_assoc($rs_doctor);
					  }
?>
                  </select>
                      
                  </div>
              </div>
			<div class="row mt-2">
					<label class="col-form-label col-form-label-sm col-sm-3" style="font-size: 10px">หมายเหตุ</label>
					<div class="col-sm-auto">
					  <input type="text" id="remark" name="remark" autofocus class="form-control form-control-sm" style="padding: 3px;" value="<?php echo $row_rs_edit['remark']; ?>" />    
					</div>					
					
				</div>			
				<div class="row mt-2">
					<label class="col-form-label col-form-label-sm col-sm-3">จำนวน</label>
					<div class="col-sm-auto">
					  <input type="text" id="qty2" name="qty2" value="<?php echo $row_rs_edit['qty']; ?>" autofocus class="form-control form-control-sm" style="padding: 3px;width: 95px" />    
					</div>					
					
				</div>	
				<div class="row mt-2">
					<div class="col-sm-3">
					</div>
					<div class="col-sm-auto">					
					<button class="btn btn-success btn-sm" style="width: 95px" id="edit">แก้ไข</button>
					<input type="hidden" id="id" value="<?php echo $_POST['id']; ?>"/>	
					</div>
				</div>				
			</div>
	
<?php	} ?>	
</body>
<script>
$(document).ready(function () {


     
        
    });
</script>	
</html>

<?php 
 if($_POST['hn']!=""){
mysql_free_result($s_patient);
}
if($_POST['id']!=""){
mysql_free_result($rs_edit);
}
mysql_free_result($rs_doctor);
?>
