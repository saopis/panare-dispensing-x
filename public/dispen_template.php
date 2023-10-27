<?php ob_start();?>
<?php session_start();?>
<?php if(($_SESSION['r_opd']!='Y') and ($_SESSION['r_finance']!='Y')){
	echo "คุณไม่ได้รับสิทธิ์ให้ใช้งานในส่วนนี้";
	exit();
	} ?>
<?php require_once('Connections/hos.php'); ?>
<?php
include('include/function.php');
include('include/function_sql.php');


if(isset($_GET['action'])&&($_GET['action']=="save")){
    if($_GET['print_staff']!=""){
        $print_staff="'".$_GET['print_staff']."'";
    }
    else{
        $print_staff="NULL";
    }
    if($_GET['prepare_staff']!=""){
        $prepare_staff="'".$_GET['prepare_staff']."'";
    }
    else{
        $prepare_staff="NULL";
    }
    if($_GET['check_staff']!=""){
        $check_staff="'".$_GET['check_staff']."'";
    }
    else{
        $check_staff="NULL";
    }
    if($_GET['pay_staff']!=""){
        $pay_staff="'".$_GET['pay_staff']."'";
    }
    else{
        $pay_staff="NULL";
    }
    mysql_select_db($database_hos, $hos);
    $query_rs_doctor = "SELECT * FROM ".$database_kohrx.".kohrx_doctor_operation where doctorcode='".$_SESSION['doctorcode']."' and print_staff=".$print_staff." and prepare_staff=".$prepare_staff." and check_staff=".$check_staff." and pay_staff=".$pay_staff." ";
    //echo $query_rs_doctor;
    $rs_doctor = mysql_query($query_rs_doctor, $hos) or die(mysql_error());
    $row_rs_doctor = mysql_fetch_assoc($rs_doctor);
    $totalRows_rs_doctor = mysql_num_rows($rs_doctor);
    
    $total_search=$totalRows_rs_doctor;
    
    mysql_free_result($rs_doctor);
    
        if($total_search==0){
            mysql_select_db($database_hos, $hos);
            $query_insert = "insert into ".$database_kohrx.".kohrx_doctor_operation (doctorcode,print_staff,prepare_staff,check_staff,pay_staff) value ('".$_SESSION['doctorcode']."',".$print_staff.",".$prepare_staff.",".$check_staff.",".$pay_staff.") ";
            $rs_insert = mysql_query($query_insert, $hos) or die(mysql_error());
        }

}

if(isset($_GET['action'])&&($_GET['action']=="delete")){

    mysql_select_db($database_hos, $hos);
    $query_delete = "delete from ".$database_kohrx.".kohrx_doctor_operation where id='".$_GET['id']."' ";
    $rs_delete = mysql_query($query_delete, $hos) or die(mysql_error());

}
mysql_select_db($database_hos, $hos);
$query_rs_doctor = "SELECT d.name,o.doctorcode FROM ".$database_kohrx.".kohrx_rx_person o left outer join doctor d on d.code=o.doctorcode ";
$rs_doctor = mysql_query($query_rs_doctor, $hos) or die(mysql_error());
$row_rs_doctor = mysql_fetch_assoc($rs_doctor);
$totalRows_rs_doctor = mysql_num_rows($rs_doctor);

mysql_select_db($database_hos, $hos);
$query_rs_doctor2 = "SELECT * FROM ".$database_kohrx.".kohrx_doctor_operation where doctorcode ='".$_SESSION['doctorcode']."'";
//echo $query_rs_doctor2;
$rs_doctor2 = mysql_query($query_rs_doctor2, $hos) or die(mysql_error());
//$row_rs_doctor2 = mysql_fetch_assoc($rs_doctor2);
$totalRows_rs_doctor2 = mysql_num_rows($rs_doctor2);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>

<link rel="stylesheet" href="https://www.w3schools.com/lib/w3-colors-flat.css">
<link rel="stylesheet" href="https://www.w3schools.com/lib/w3-colors-metro.css">
	
<style>
.left {
    width: 300px;
    float: left;
    /*background: #aafed6;*/
}

.right {
    float: none; /* not needed, just for clarification */
    /* the next props are meant to keep this block independent from the other floated one */
    width: auto;
    overflow: hidden;
	padding-left: 10px;
}​​
</style>
<script>
    $(document).ready(function(){
        $('#pay_staff').val('<?php echo $_SESSION['doctorcode']; ?>');
        $('#save').click(function(){
	       $('#modal-body-xl').load('dispen_template.php?action=save&print_staff='+$('#print_staff').val()+'&prepare_staff='+$('#prepare_staff').val()+'&check_staff='+$('#check_staff').val()+'&pay_staff='+$('#pay_staff').val(),function(responseTxt, statusTxt, xhr){
                            if(statusTxt == "success")

                            if(statusTxt == "error")
                            	alert("Error: " + xhr.status + ": " + xhr.statusText);    
                        });
            
        });
    });

function operation_delete(id){
 	       $('#modal-body-xl').load('dispen_template.php?action=delete&id='+id,function(responseTxt, statusTxt, xhr){
                            if(statusTxt == "success")

                            if(statusTxt == "error")
                            	alert("Error: " + xhr.status + ": " + xhr.statusText);    
                        });   
}

function select_doctor(print_staff,prepare_staff,check_staff,pay_staff){
    $('#rx_print').val(print_staff);
    $('#doctorprint').val(print_staff);
    $('#prepare').val(prepare_staff);
    $('#preparedoctor').val(prepare_staff);
    $('#check').val(check_staff);
    $('#checkdoctor').val(check_staff);
    $('#dispen').val(pay_staff);
    $('#dispendoctor').val(pay_staff);
    
    $('#myModal-xl').modal('hide');
}
</script>
</head>

<body>
    <?php if($total_search<>0){        
            echo "<div class='alert alert-primary' role='alert'>ซ้ำกับรายการที่มีอยู่แล้ว !!</div>";
        }    ?>
	<div  style="height: auto; overflow: hidden" class="m-0">
	<div class="left">
		<div class="card">
			<div class="card-header">จัดการเทมเพลตการจ่ายยา</div>
			<div class="card-body">
				<div class="form-group row">
		<label class="col-form-label col-form-label-sm col-sm-3">ผู้พิมพ์</label>
			<div class="col-sm-9">
				<select name="print_staff" class="form-control form-control-sm font12" id="print_staff" style="padding-left:2px; padding-right:2px;" >
                    <option value="">== ไม่เลือก ==</option>
                    
                    <?php
					do {  
					?>
										<option value="<?php echo $row_rs_doctor['doctorcode']?>"><?php echo $row_rs_doctor['name']?></option>
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
	<div class="form-group row">
		<label class="col-form-label col-form-label-sm col-sm-3">ผู้จัด</label>
			<div class="col-sm-9">
				<select name="prepare_staff" class="form-control form-control-sm font12" id="prepare_staff" style="padding-left:2px; padding-right:2px;" >
                    <option value="">== ไม่เลือก ==</option>
                    <?php
					do {  
					?>
										<option value="<?php echo $row_rs_doctor['doctorcode']?>"><?php echo $row_rs_doctor['name']?></option>
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
	<div class="form-group row">
		<label class="col-form-label col-form-label-sm col-sm-3">ผู้เช็ค</label>
			<div class="col-sm-9">
				<select name="check_staff" class="form-control form-control-sm font12" id="check_staff" style="padding-left:2px; padding-right:2px;" >
                    <option value="">== ไม่เลือก ==</option>
                    <?php
					do {  
					?>
										<option value="<?php echo $row_rs_doctor['doctorcode']?>"><?php echo $row_rs_doctor['name']?></option>
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
	<div class="form-group row">
		<label class="col-form-label col-form-label-sm col-sm-3">ผู้จ่าย</label>
			<div class="col-sm-9">
                <select name="pay_staff" class="form-control form-control-sm font12" id="pay_staff" style="padding-left:2px; padding-right:2px;" >
                <option value="">== ไม่เลือก ==</option>
                    <?php
					do {  
					?>
										<option value="<?php echo $row_rs_doctor['doctorcode']?>"><?php echo $row_rs_doctor['name']?></option>
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
	<div class="form-group row">
			<label class="col-sm-3"></label>
			<div class="col-sm-9">
				<button class="btn btn-success" id="save">บันทึก</button>
			</div>
	</div>				
			</div>
		</div>
	</div>
	<div class="right">
			<div class="pl-3 pr-2">
			<div class="rounded border-secondary bg-light" style="border: 1px dashed">
			<div class="row pr-4 pl-4 pt-2 pb-1">
			<?php if ($totalRows_rs_doctor2 > 0) { 
					$count = 0;
				while ($row_rs_doctor2 = mysql_fetch_assoc($rs_doctor2)){
					 $count++; ?>
					<div class="col-sm-6" style="padding:5px;">
					<div class="card card-hover cursor border-0" style="cursor:pointer"><button type="button" class="close position-absolute" style="right:5px; top:5px;" aria-label="Close" onclick="if(confirm('ต้องการลบชื่อ <?php echo $row_rs_key['sensor_name']; ?> จริงหรือไม่')==true){ operation_delete('<?php echo $row_rs_doctor2['id']; ?>'); } "><span aria-hidden="true">&times;</span></button><h2><span class="badge badge-light position-absolute" style="left: 5px; top: 13px;"><?php echo $count; ?></span></h2>
			<div class="card-body w3-flat-belize-hole text-white border rounded" onClick="select_doctor('<?php echo $row_rs_doctor2['print_staff']; ?>','<?php echo $row_rs_doctor2['prepare_staff']; ?>','<?php echo $row_rs_doctor2['check_staff']; ?>','<?php echo $row_rs_doctor2['pay_staff']; ?>');" >
				<div style="padding-left: 30px;"><span class="font14"><strong class="text-warning">ผู้พิมพ์ :</strong>&ensp;<?php echo doctorname($row_rs_doctor2['print_staff']); ?> </span></div>
				<div style="padding-left: 30px;"><span class="font14"><strong class="text-warning">ผู้จัด :</strong>&ensp;<?php echo doctorname($row_rs_doctor2['prepare_staff']); ?> </span></div>
				<div style="padding-left: 30px;"><span class="font14"><strong class="text-warning">ผู้เช็ค :</strong>&ensp;<?php echo doctorname($row_rs_doctor2['check_staff']); ?> </span></div>
				<div style="padding-left: 30px;"><span class="font14"><strong class="text-warning">ผู้จ่าย :</strong>&ensp;<?php echo doctorname($row_rs_doctor2['pay_staff']); ?> </span></div>

			</div>
                    </div>
					</div>	
				<?php if ($count % 2 == 0){  ?></div><div class="row row pr-4 pl-4 pt-1 pb-2">
				<?php } } ?>
				
					</div>
				<?php 


			} else{ echo nodata();} ?>
			</div>
			</div>
	</div>
	</div>
	
</body>
</html>
<?php mysql_free_result($rs_doctor); ?>
<?php mysql_free_result($rs_doctor2); ?>