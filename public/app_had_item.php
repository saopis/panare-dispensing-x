<?php ob_start();?>
<?php session_start();?>
<?php /*if(($_SESSION['r_opd']!='Y') and ($_SESSION['r_finance']!='Y')){
	echo "คุณไม่ได้รับสิทธิ์ให้ใช้งานในส่วนนี้";
	exit();
	} d
	*/
?>
<?php require_once('Connections/hos.php'); ?>
<?php include('include/function.php'); ?>
<?php include('include/function_sql.php'); ?>
<?php

if($_POST['action']=="operate"){
	if($_POST['did2']!=""){
		mysql_select_db($database_hos, $hos);
		$query_rs_update = "delete from ".$database_kohrx.".kohrx_had_use  where id='".$_POST['did2']."'";
		$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());
		if($rs_update){
		echo "<script>modal2Close();Drugload('".$_POST['icode']."','load');</script>";	
		exit();
		}
	}
	if($_POST['qty2']!=""){
		mysql_select_db($database_hos, $hos);
		$query_rs_update = "update ".$database_kohrx.".kohrx_had_use set use_time='".$_POST['use_time']."',use_date='".date_th2db($_POST['use_date'])."',qty='".$_POST['qty2']."',doctor='".$_POST['doctor']."',remark='".$_POST['remark']."',department='".$_POST['department1']."' where id='".$_POST['id']."'";
		//echo $query_rs_update;
		//exit();
		$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());
		
		if($rs_update){
		echo "<script>modal2Close();Drugload('".$_POST['icode']."','load');</script>";	
		exit();
		}
		
		}
	
if($_POST['hn']!=""){
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

//========== แพทย์ผู้สั่งใช้ยา ==========//
mysql_select_db($database_hos, $hos);
$query_rs_doctor = "SELECT d.name,o.doctorcode FROM ".$database_kohrx.".kohrx_rx_person o left outer join doctor d on d.code=o.doctorcode ";
$rs_doctor = mysql_query($query_rs_doctor, $hos) or die(mysql_error());
$row_rs_doctor = mysql_fetch_assoc($rs_doctor);
$totalRows_rs_doctor = mysql_num_rows($rs_doctor);
	
	if($_POST['qty']!=""){
		$qty=$_POST['qty'];
		$use_date=$_POST['use_date'];
		$use_time=$_POST['use_time'];
		$in_out=2;
		$doctor=$_POST['doctor'];
		$remark=$_POST['remark'];
		$icode=$_POST['icode'];
		$department=$_POST['department'];

		mysql_select_db($database_hos, $hos);
		$query_insert = "insert into ".$database_kohrx.".kohrx_had_use (hn,use_date,use_time,qty,in_out_method,doctor,remark,icode,department) value ('".$hn."','".date_th2db($use_date)."','".$use_time.":00',".$qty.",'".$in_out."','".$doctor."','".$remark."','".$icode."','".$department."') ";
		$insert = mysql_query($query_insert, $hos) or die(mysql_error());
		echo "<script>modal2Close();Drugload('".$icode."','load');</script>";
	}

	if($_POST['id']!=""){
		mysql_select_db($database_hos, $hos);
			$query_rs_edit = "select * from ".$database_kohrx.".kohrx_had_use where id='".$_POST['id']."'";
			$rs_edit = mysql_query($query_rs_edit, $hos) or die(mysql_error());
			$row_rs_edit = mysql_fetch_assoc($rs_edit);
			$totalRows_rs_edit = mysql_num_rows($rs_edit);
		
	} 
	
	}
	
}
else if($_POST['action']=="receive"){
		$qty=$_POST['qty'];
		$use_date=$_POST['use_date'];
		$use_time=$_POST['use_time'];
		$in_out=1;
		$icode=$_POST['icode'];

		$query_insert = "insert into ".$database_kohrx.".kohrx_had_use (use_date,use_time,qty,in_out_method,icode) value ('".date_th2db($use_date)."','".$use_time.":00',".$qty.",'".$in_out."','".$icode."') ";
		$insert = mysql_query($query_insert, $hos) or die(mysql_error());
		if($insert){
			echo "<script>Drugload('".$icode."','load');modal2Close();</script>";
			exit();
		}
	
}
else if($_POST['action']=="load2"){
mysql_select_db($database_hos, $hos);
$query_rs_result = "select concat(pt.pname,pt.fname,' ',pt.lname) as ptname,pt.cid,o.* from ".$database_kohrx.".kohrx_had_use o left outer join patient pt on pt.hn=o.hn where use_date between  '".($datestart)."' and '".($dateend)."' and icode='".$_POST['icode']."' order by use_date ASC,use_time ASC";
//echo $query_rs_result;
$rs_result = mysql_query($query_rs_result, $hos) or die(mysql_error());
$row_rs_result = mysql_fetch_assoc($rs_result);
$totalRows_rs_result = mysql_num_rows($rs_result);

//หาจำนวนยกมาตามวันที่แรก
mysql_select_db($database_hos, $hos);
$query_rs_result2 = "select * from ".$database_kohrx.".kohrx_had_use  where use_date <  '".($datestart)."' and icode='".$_POST['icode']."'  order by use_date ASC,use_time ASC";
//echo $query_rs_result;
$rs_result2 = mysql_query($query_rs_result2, $hos) or die(mysql_error());
$row_rs_result2 = mysql_fetch_assoc($rs_result2);
$totalRows_rs_result2 = mysql_num_rows($rs_result2);
	
$top=0; $i=0;  
	do{ $i++; 
		if($row_rs_result2['in_out_method']=="1"){
			$top+=$row_rs_result2['qty'];
		}
		else if($row_rs_result2['in_out_method']=="2") {
			$top-=$row_rs_result2['qty'];		
		}
	   
	} while($row_rs_result2 = mysql_fetch_assoc($rs_result2));
	
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<?php if($_POST['action']=="load"){ ?>	
<?php include('include/datepicker/datepicker.php'); ?>
	
<script>
	$(document).ready(function(){
		

		$('#search').click(function(){
			Drugload2('load2',$('#icode').val());
		});
		
	});
</script>	
<?php } 
else if($_POST['action']=="load2"){ ?>
	
<script>
$(document).ready(function() {
    $('#example').append('<caption style="caption-side: bottom"></caption>');

	$('#example').DataTable( {
		
		"lengthMenu": [[10, 25, 50,100, -1], [10, 25, 50,100, "All"]],
		"pageLength": 15,
        dom: 'Bfrtip',
		columnDefs: [
            {
                targets: 1,
                className: 'noVis'
            }
        ],
        buttons: [  

            {
				extend: 'copy',
				text: '<i class="fas fa-copy"></i><span class="thfont font12">&nbsp;Copy</span>',
				className: 'btn btn-default',
				titleAttr: 'COPY',
				exportOptions: {
					columns: ':not(.notexport)',
					columns: ':visible'
					}
				}
			,
				{
				extend: 'csv',
				text: '<i class="fas fa-file-csv"></i><span class="thfont font12">&nbsp;CSV</span>',
				className: 'btn btn-default',
				titleAttr: 'CSV',	
				exportOptions: {
					columns: ':not(.notexport)',
					columns: ':visible'
					}
				}			
			, 
				{
				extend: 'excel',
				text: '<i class="fas fa-file-excel"></i><span class="thfont font12">&nbsp;Excel</span>',
				className: 'btn btn-default',
				titleAttr: 'EXCEL',
				exportOptions: {
					columns: ':not(.notexport)',
					columns: ':visible'
					}
				}	
						
			,
                       {
                       extend: 'print',
					   text: '<i class="fas fa-print"></i><span class="thfont font12">&nbsp;Print</span>',
					   titleAttr: 'PRINT',
                       exportOptions: {
                          // stripHtml : false,
                           messageBottom: null,
						   columns: ':not(.notexport)',
						   columns: ':visible'

                           //columns: [ 0, 1, 2, 3, 4 ] //Your Colume value those you want
                           }
                         }
			
        ],
		language: {
        search: "_INPUT_",
        searchPlaceholder: "ค้นหา..."
    	}
    } );
});
</script>
<style>
.dataTables_length,.dataTables_filter {
    margin-left: 10px;
	margin-right: 15px;
    float: right;
}

</style>		
<?php }	else if($_POST['action']=="operate"){ ?>

<script>
$(document).ready(function(){
	<?php if($_POST['id']!=""){ ?>
		var id=<?php echo $_POST['id']; ?>;
	<?php } ?>
	$('#receivediv').hide();
	$('#use_type').change(function(){
		if($('#use_type').val()=="1"){
			$('#receivediv').show();
			$('#dispendiv').hide();
			
		}	
		else if($('#use_type').val()=="2"||id!=""){
			$('#receivediv').hide();
			$('#dispendiv').show();
		}	
	
	
	});

	
				$('#hn').bind('keyup', function(e) {
				if(e.which == '13'){ //enter
				$.ajax({
				url : "app_had_item.php",
				type: "POST",
				data : {action:'operate',dispendiv:'show',hn:$(this).val(),icode:$('#icode').val(),use_type:$('#use_type').val()},
				success: function(data, textStatus, jqXHR)
				{
					//data - response from server
					$('#ModalBody2').html(data);
						$('#dispendiv').show();
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
					url : "app_had_item.php",
					type: "POST",
					data : {hn:'<?php echo $hn; ?>',qty:$(this).val(),use_date:$('#use_date').val(),use_time:$('#use_time').val(),in_out:$('#in_out').val(),doctor:$('#doctor').val(),remark:$('#remark').val(),icode:$('#icode').val(),action:'operate',department:$('#department').val()},
					success: function(data, textStatus, jqXHR)
					{
						//data - response from server
						$('#ModalBody2').html(data);

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
	
				$('#edit').click(function(){

					$.ajax({
					url : "app_had_item.php",
					type: "POST",
					data : {id:$('#id').val(),use_date:$('#use_date2').val(),use_time:$('#use_time2').val(),qty2:$('#qty2').val(),doctor:$('#doctor').val(),remark:$('#remark').val(),icode:$('#icode').val(),department1:$('#department1').val(),action:'operate',action2:'edit'},
					success: function(data, textStatus, jqXHR)
					{
							
						//data - response from server
								//data - response from server
								
								$('#ModalBody2').html(data);
								//$('#Modal2').modal('hide');

					},
					error: function (jqXHR, textStatus, errorThrown)
					{
						//$('.throw_error').fadeIn(1000).show();
					}
					});
				});	

				$('#btn-delete').click(function(){
							$.ajax({
							url : "app_had_item.php",
							type: "POST",
							data : {did2:$('#did').val(),action:'operate',icode:$('#icode').val()},
							success: function(data, textStatus, jqXHR)
							{
								//data - response from server
								$('#ModalBody2').html(data);								//$('#Modal2').modal('hide');
								
								
							},
							error: function (jqXHR, textStatus, errorThrown)
							{
								//$('.throw_error').fadeIn(1000).show();
							}
							});					
				});
				///////////////
		$('#btn-cancel').click(function(){
			modal2Close();
		});
	
	
	$('#receive-save').hide();
	$('#qty3').keyup(function(){
		if($('#qty3').val()!=""){
			$('#receive-save').show();
		}
		else{
			$('#receive-save').hide();	
		}
	});
	
	$('#receive-save').click(function(){
	            $.ajax({
				   type: "POST",
				   url: 'app_had_item.php',
				   cache: false,
				   data: {action:'receive',icode:$('#icode').val(),use_date:$('#use_date3').val(),use_time:$('#use_time3').val(),qty:$('#qty3').val(),datestart:$('#datestart').val(),dateend:$('#dateend').val()},
				   success: function(html)
					{                        
                        $("#list2").html(html);
					
					}
				 });  		
	});	
	
	set_cal( $("#use_date"));	
	set_cal( $("#use_date2"));	
	set_cal( $("#use_date3"));	
	const timenow = Date().slice(16,21);
    $('#use_time').val(timenow);
    $('#use_time3').val(timenow);

	 $("#qty3").on("keypress keyup blur",function (event) {    
           $(this).val($(this).val().replace(/[^\d].+/, ""));
            if ((event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });
	 $("#qty").on("keypress keyup blur",function (event) {    
           $(this).val($(this).val().replace(/[^\d].+/, ""));
            if ((event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });	
	 $("#hn").on("keypress keyup blur",function (event) {    
           $(this).val($(this).val().replace(/[^\d].+/, ""));
            if ((event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });	

	/*
	 $("#qty").on("keypress keyup blur",function (event) {    
           $(this).val($(this).val().replace(/[^\d].+/, ""));
            if ((event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });	
	 $("#hn").on("keypress keyup blur",function (event) {    
           $(this).val($(this).val().replace(/[^\d].+/, ""));
            if ((event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });	
	*/
});		
</script>	
<?php } ?>	
	
</head>

<body>
<?php if($_POST['action']=="load"){ ?>		
<div class="p-3 row">
			<div class="col-sm-auto">
				<div id="reportrange" class="form-control form-control-sm" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 300px; ">
					  <i class="far fa-calendar-alt"></i>&nbsp;
						<span></span> 
					  <i class="fas fa-sort-down"></i>
			   </div>	

				<input name="datestart" type="hidden" id="datestart" value="" /><input name="dateend" type="hidden" id="dateend" value="" />
			</div>	
			<div class="col-sm-auto">			
			<button name="search" id="search" class="btn btn-secondary btn-sm" >ค้นหา</button>	
			</div>	
			<div class="col-sm-auto">			
				<button class="btn btn-success btn-sm" onClick="ModalLoad2('รับ-จ่าย ยาความเสี่ยงสูง','app_had_item.php','operate');">รับ-จ่าย</button>
			</div>	
			<div class="col-sm-auto">			
			<button name="search" id="search" class="btn btn-warning btn-sm" onClick="monitorPrint();" ><i class="fas fa-print"></i>&nbsp;Monitor</button>	
			</div>		
</div>	
<div id="list2"></div>	
<script type="text/javascript" src="include/datepicker/js/moment.min.js"></script>
<script type="text/javascript" src="include/datepicker/js/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="include/datepicker/css/daterangepicker.css" />
<script type="text/javascript">
$(function() {
	
    var start = moment().subtract(2, 'days');
    var end = moment().subtract(0, 'days');

    function cb(start, end) {
        $('#reportrange span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
		$('#datestart').val(start.format('Y-MM-DD'));
		$('#dateend').val(end.format('Y-MM-DD'));

    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
		lang:'th',
        ranges: {
           'วันนี้': [moment(), moment()],
           'เมื่อวาน': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'ย้อนหลัง 7 วัน': [moment().subtract(7, 'days'), moment().subtract(1, 'days')],
           '30 วันที่แล้ว': [moment().subtract(29, 'days'), moment()],
           'เดือนนี้': [moment().startOf('month'), moment().endOf('month')],
           'เดือนที่แล้ว': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
		   'ปีงบประมาณนี้':[moment([new Date().getFullYear(), 9, 01]).subtract(1,'year'),moment([new Date().getFullYear(), 8, 30])],
		   'ปีงบประมาณก่อน':[moment([new Date().getFullYear(), 9, 01]).subtract(2,'year'),moment([new Date().getFullYear(), 8, 30]).subtract(1,'year')]
        }
    }, cb);
	
    cb(start, end);
	


});
</script>
<script>
	$(document).ready(function(){
		Drugload2('load2',$('#icode').val());
		
	});
</script>	
	
<?php } else if($_POST['action']=="load2"){  ?>
	<div class="p-3">
<table  id="example" class="table table-striped table-bordered table-hover table-sm " style="width:100%; font-size:11px" >  
    <thead>
    <tr>
      <th align="center" >ลำดับ</th>
      <th align="center" >วัน/เดือน/ปี</th>
      <th align="center" >เวลาจ่าย</th>
      <th align="center" >ประเภทรับ/จ่าย</th>
      <th align="center" >จำนวน</th>
      <th align="center" >คงเหลือ</th>
      <th align="center" >ชื่อ-สกุลผู้ป่วย</th>
      <th align="center" >HN</th>
      <th align="center" >ผู้บันทึก</th>
      <th align="center" >หมายเหตุ</th>
   	  <th></th>	
    </tr>
    </thead>
    <tbody>
	<?php if($totalRows_rs_result2<>0){ ?>
    <tr>
      <td align="center" >1</td>			
      <td align="center" ><?php echo date_db2th2($datestart); ?></td>
      <td align="center" >-</td>      
	  <td align="center" ><i class="fas fa-circle text-success"></i>&nbsp;ยกยอด</td>
      <td align="center" class="text-danger" ><?php echo $top; ?></td>
      <td align="center" ><?php echo $top; ?></td>
      <td align="center" ></td>
      <td align="center" ></td>
      <td align="center" ></td>
      <td align="center" ></td>
	  <td></td>			
    </tr>      
		
	<?php } ?>	
		
	<?php if($totalRows_rs_result<>0){ ?>
    <?php $remain=$top; if($totalRows_rs_result2<>0){$k=1;}else{ $k=0;} $j=0; do{ $k++; $j++; ?>
    <?php 
	if($row_rs_result['in_out_method']=="1"){
		$remain+=$row_rs_result['qty'];
	}
	else if($row_rs_result['in_out_method']=="2"){
		$remain-=$row_rs_result['qty'];		
	}
        ?>
    <tr>
      <td align="center" ><?php echo $k; ?></td>			
      <td align="center" ><?php echo date_db2th2($row_rs_result['use_date']); ?></td>
      <td align="center" ><?php echo ($row_rs_result['use_time']); ?></td>      
	  <td align="center" ><?php if($row_rs_result['in_out_method']==1){?><i class="fas fa-circle text-success"></i>&nbsp;<?php } ?><?php if($row_rs_result['in_out_method']==1) {echo "รับ"; }else{ echo "จ่าย (".$row_rs_result['department'].")"; } ?></td>
      <td align="center" class="<?php if($row_rs_result['in_out_method']==1){ echo "text-danger"; }  ?>" ><?php echo $row_rs_result['qty']; ?></td>
      <td align="center" ><?php echo $remain; ?></td>
      <td align="center" ><?php echo $row_rs_result['ptname']; ?></td>
      <td align="center" ><?php echo $row_rs_result['hn']; ?></td>
      <td align="center" ><?php echo explode(' ',doctorname($row_rs_result['doctor']))[0]; ?></td>
      <td align="center" ><?php echo $row_rs_result['remark']; ?></td>
	  <td><?php if($row_rs_result['in_out_method']!=1){ ?><i class="fas fa-pen-square font20" style="cursor: pointer" onClick="ModalLoad2('รับ-จ่าย ยาความเสี่ยงสูง','app_had_item.php','operate','<?php echo $row_rs_result['id']; ?>','<?php echo $row_rs_result['hn']; ?>')"></i>&nbsp;<?php } ?><?php if($j==$totalRows_rs_result){ ?><i class="fas fa-eraser font20" style="cursor: pointer" onClick="delete_record('<?php echo $row_rs_result['id']; ?>');"></i><?php } if($row_rs_result['in_out_method']==2){?>&nbsp;<i class="fas fa-print font20 cursor" onClick="ptPrint('<?php echo $row_rs_result['hn']; ?>','<?php echo $row_rs_result['use_date']; ?>');"></i><?php } ?></td>			
    </tr>      
    <?php } while($row_rs_result = mysql_fetch_assoc($rs_result));?>    
		
	<?php } ?>	
    </tbody>
    </table>		
	</div>
<?php
	
 } 
else if($_POST['action']=="operate"){	?>
<?php if(!empty($_POST['did'])){
	echo "<center>ต้องการลบรายการนี้จริงหรือไม่<br><button class='btn btn-danger btn-sm' id='btn-delete'>ใช่! ลบข้อมูล</button>&nbsp;<button class='btn btn-primary btn-sm' id='btn-cancel'>ไม่ลบ</button></center><input type='hidden' id='did' value='".$_POST['did']."' />";
} else { ?>		
	<div class="p-2">
		<div class="row">
				<label for="use_type" class="col-form-label col-form-label-sm col-sm-3 thfont">ประเภทรับจ่าย</label>
				<div class="col-sm-auto">
					<select id="use_type" name="use_type" class="form-control form-control-sm thfont">
						<option value="1" <?php if($_POST['use_type']==1){echo "selected"; } ?>>รับ</option>
						<option value="2" <?php if($_POST['use_type']==2||isset($_POST['use_type'])==""){echo "selected"; } ?>>จ่าย</option>
					</select>
			</div>
		</div>
			<div id="receivediv">
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
					  <input type="text" id="qty3" name="qty3" autofocus class="form-control form-control-sm" style="padding: 3px;width: 95px"  />    
						
					</div>					
					
				</div>
				<div class="row mt-2">
					<div class="col-sm-3"></div>
					<div class="col-sm-auto">
						<button class="btn btn-sm btn-success" id="receive-save">บันทึก</button>
						<button class="btn btn-sm btn-warning" id="receive-cancel" onClick="modal2Close();">ยกเลิก</button>

					</div>
				</div>	
		</div>
<!--	จ่าย	-->
		
	<div id="dispendiv">
	<?php if($_POST['id']==""){ ?>	
	<div class="card-body p-1">	
	<?php if(!isset($_POST['hn'])&&($_POST['hn']=="")){ ?>
	<div class="row thfont font14 mt-2">
		<label class="col-form-label col-sm-3">ค้นหา HN </label>
		<div class="col-auto">
			<input id="hn" class="form-control form-control-sm" type="text" autofocus  style="" />
		</div>
	</div>
	<?php } else { ?>
		<?php if($totalRows_s_patient<>0){ ?>
			<div class=" thfont font14">
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
              <div class="row mt-2" >
                <label for="department" class="col-sm-3 col-form-label col-form-label-sm font12">แผนก</label>

                  <div class="col-sm-auto">
                    <select name="department" class="form-control form-control-sm font12" id="department" style="padding-left:2px; padding-right:2px;"  >
						<option value="OPD" >OPD</option>
						<option value="ER">ER</option>
						<option value="IPD">IPD</option>
                  </select>
                  </div>
              </div>				
				<div class="row mt-2">
					<label class="col-form-label col-form-label-sm col-sm-3" style="font-size: 12px">หมายเหตุ</label>
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
	<?php }   else { ?>
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
              <div class="row mt-2" >
                <label for="department1" class="col-sm-3 col-form-label col-form-label-sm font12">แผนก</label>

                  <div class="col-sm-auto">
                    <select name="department1" class="form-control form-control-sm font12" id="department1" style="padding-left:2px; padding-right:2px;"  >
						<option value="OPD" <?php if (!(strcmp("OPD",$row_rs_edit['department']))) {echo "selected=\"selected\"";} ?>>OPD</option>
						<option value="ER" <?php if (!(strcmp("ER",$row_rs_edit['department']))) {echo "selected=\"selected\"";} ?>>ER</option>
						<option value="IPD" <?php if (!(strcmp("IPD",$row_rs_edit['department']))) {echo "selected=\"selected\"";} ?>>IPD</option>
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
	</div>
<!--	จ่าย	-->
		</div>
	<?php } } ?>
</body>
</html>
<?php
if($_POST['action']=="operate"){
		if($_POST['hn']!=""){
			mysql_free_result($s_patient);
			mysql_free_result($rs_doctor);
			
		}
}
?>
<?php if($_POST['action']=="load2"){ ?>	
<?php mysql_free_result($rs_result); ?>
<?php mysql_free_result($rs_result2); ?>

<?php } ?>