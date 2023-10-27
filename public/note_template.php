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
            mysql_select_db($database_hos, $hos);
            $query_insert = "insert into ".$database_kohrx.".kohrx_dispen_note_template (doctorcode,note) value ('".$_SESSION['doctorcode']."','".$_GET['message']."') ";
            $rs_insert = mysql_query($query_insert, $hos) or die(mysql_error());

}

if(isset($_GET['action'])&&($_GET['action']=="delete")){
    mysql_select_db($database_hos, $hos);
    $query_delete = "delete from ".$database_kohrx.".kohrx_dispen_note_template where id='".$_GET['id']."' ";
    $rs_delete = mysql_query($query_delete, $hos) or die(mysql_error());

}
//ค้นหาข้อมูล note
mysql_select_db($database_hos, $hos);
$query_rs_note = "select * from ".$database_kohrx.".kohrx_dispen_note_template where doctorcode='".$_SESSION['doctorcode']."' ";
//echo $query_rs_note;
$rs_note = mysql_query($query_rs_note, $hos) or die(mysql_error());
//$row_rs_note = mysql_fetch_assoc($rs_note);
$totalRows_rs_note = mysql_num_rows($rs_note);

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<link rel="stylesheet" href="https://www.w3schools.com/lib/w3-colors-flat.css">
<link rel="stylesheet" href="https://www.w3schools.com/lib/w3-colors-metro.css">
<script>
    $(document).ready(function(){
        $('#pay_staff').val('<?php echo $_SESSION['doctorcode']; ?>');
        $('#save').click(function(){
	       $('#modal-body-xl').load('note_template.php?action=save&message='+encodeURI($('#notetext').val()),function(responseTxt, statusTxt, xhr){
                            if(statusTxt == "success")

                            if(statusTxt == "error")
                            	alert("Error: " + xhr.status + ": " + xhr.statusText);    
                        });
            
        });
    });
	
function operation_delete(id){
 	       $('#modal-body-xl').load('note_template.php?action=delete&id='+id,function(responseTxt, statusTxt, xhr){
                            if(statusTxt == "success")

                            if(statusTxt == "error")
                            	alert("Error: " + xhr.status + ": " + xhr.statusText);    
                        });   
}
function select_note(note){
	if($('#note').text()!=""){
		var note2=' '+note;
	}
	else{
		var note2=note;
	}
   	$('#note').append(note2);
    
    $('#myModal-xl').modal('hide');
}	
	</script>	
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

</head>

<body>
	<div  style="height: auto; overflow: hidden" class="m-0">
	<div class="left">
		<div class="card">
			<div class="card-header">จัดการเทมเพลตโน๊ตจ่ายยา</div>
			<div class="card-body">
				<div class="form-group row">
					<textarea id="notetext" class="form-control form-control-sm"></textarea>
				</div>	
				<div class="form-group row ">
					<div class="text-center"><button class="btn btn-success" id="save">บันทึก</button></div>
				</div>				
			</div>
		</div>
	</div>
	<div class="right">
		<div class="card">
			<div class="card-body p-1">
			<div class="row pr-4 pl-4 pt-2 pb-1">
			<?php while($row_rs_note = mysql_fetch_assoc($rs_note)){
				 $count++; ?>
					<div class="col-sm-4" style="padding:5px;">
					<div class="card card-hover cursor border-0" style="cursor:pointer"><button type="button" class="close position-absolute" style="right:5px; top:5px;" aria-label="Close" onclick="if(confirm('ต้องการลบชื่อ <?php echo $row_rs_note['note']; ?> จริงหรือไม่')==true){ operation_delete('<?php echo $row_rs_note['id']; ?>'); } "><span aria-hidden="true">&times;</span></button><h5><span class="badge badge-light position-absolute" style="left: 5px; top: 13px;"><?php echo $count; ?></span></h5>
			<div class="card-body bg-success text-white border rounded" onClick="select_note('<?php echo $row_rs_note['note']; ?>');" >
				<div style="padding-left: 30px;"><span class="font14"><?php echo $row_rs_note['note']; ?> </span></div>

			</div>
                    </div>
					</div>	
				
			<?php if ($count % 3 == 0){  ?></div><div class="row row pr-4 pl-4 pt-1 pb-2">
				<?php } }
			?>			
			</div>
	</div>	
	</div>	
</div>	
</body>
</html>
<?php mysql_free_result($rs_note); ?>