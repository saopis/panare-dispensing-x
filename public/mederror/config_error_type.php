<?php require_once('../Connections/hos.php'); ?>
<? 
if((isset($_GET['action'])&&($_GET['action']=="edit"))){
	if ($_GET['mr']=='N'){
		$mr="NULL";
	}
	else{
		$mr="'".$_GET['mr']."'";	
	}
	
	if($_GET['type']=="error_type"){
	mysql_select_db($database_hos, $hos);
	$update ="update ".$database_kohrx.".kohrx_med_error_error_type set type_thai='".$_GET['text']."',mr=".$mr." where id ='".$_GET['id']."'";
	//echo $update;
	$query_update = mysql_query($update,$hos) or die (mysql_error());	
	}
	if($_GET['type']=="error_cause"){
	mysql_select_db($database_hos, $hos);
	$update ="update ".$database_kohrx.".kohrx_med_error_error_cause set name='".$_GET['text']."',mr=".$mr." where id ='".$_GET['id']."'";
	//echo $update;
	$query_update = mysql_query($update,$hos) or die (mysql_error());	
	}
	if($_GET['type']=="error_subtype"){
	mysql_select_db($database_hos, $hos);
	$update ="update ".$database_kohrx.".kohrx_med_error_error_sub_cause set sub_name='".$_GET['text']."',mr=".$mr." where id ='".$_GET['id']."'";
	$query_update = mysql_query($update,$hos) or die (mysql_error());	
	}
}

if((isset($_GET['action'])&&($_GET['action']=="add"))){
	//error_type
	if ($_GET['mr']=='N'){
		$mr="NULL";
	}
	else{
		$mr="'".$_GET['mr']."'";	
	}
	
	if($_GET['type']=="error_type"){

	mysql_select_db($database_hos, $hos);
	$search = "select order_type from ".$database_kohrx.".kohrx_med_error_error_type order by order_type DESC limit 1";
	$query_search=mysql_query($search,$hos) or die (mysql_error());
	$row_search = mysql_fetch_assoc($query_search);
	$totalRows_search = mysql_num_rows($query_search);

	$max_order=$row_search['order_type']+1;
	mysql_free_result($query_search);

	mysql_select_db($database_hos, $hos);
	$update ="insert ".$database_kohrx.".kohrx_med_error_error_type (type_thai,order_type,mr) value ('".$_GET['text']."','".$max_order."',".$mr.")";
	$query_update = mysql_query($update,$hos) or die (mysql_error());
	}
	//error_cause
	if($_GET['type']=="error_cause"){
	mysql_select_db($database_hos, $hos);
	$search = "select order_cause from ".$database_kohrx.".kohrx_med_error_error_cause order by order_cause DESC limit 1";
	$query_search=mysql_query($search,$hos) or die (mysql_error());
	$row_search = mysql_fetch_assoc($query_search);
	$totalRows_search = mysql_num_rows($query_search);

	$max_order=$row_search['order_cause']+1;
	mysql_free_result($query_search);

	mysql_select_db($database_hos, $hos);
	$update ="insert ".$database_kohrx.".kohrx_med_error_error_cause (type_id,name,order_cause,mr) value ('".$_GET['error_type_id']."','".$_GET['text']."','".$max_order."',".$mr.")";
	$query_update = mysql_query($update,$hos) or die (mysql_error());
	}
	//error_subtype
	if($_GET['type']=="error_subtype"){

	mysql_select_db($database_hos, $hos);
	$update ="insert ".$database_kohrx.".kohrx_med_error_error_sub_cause (cause_id,sub_name,mr) value ('".$_GET['error_cause_id']."','".$_GET['text']."',".$mr.")";
	$query_update = mysql_query($update,$hos) or die (mysql_error());
	}
	
}

if((isset($_GET['action'])&&($_GET['action']=="delete"))){
	if($_GET['type']=="error_type"){
	mysql_select_db($database_hos, $hos);
	$search = "select id from  ".$database_kohrx.".kohrx_med_error_error_cause where type_id='".$_GET['id']."'";
	$query_search=mysql_query($search,$hos) or die (mysql_error());
	$row_search = mysql_fetch_assoc($query_search);
	$totalRows_search = mysql_num_rows($query_search);

	if($totalRows_search<>0){
		mysql_select_db($database_hos, $hos);
		$delete ="delete from ".$database_kohrx.".kohrx_med_error_error_sub_cause  where cause_id ='".$row_search['id']."'";
		$q_delete = mysql_query($delete,$hos) or die (mysql_error());
	}	
	mysql_free_result($query_search);
		
	mysql_select_db($database_hos, $hos);
	$delete ="delete from ".$database_kohrx.".kohrx_med_error_error_cause  where type_id ='".$_GET['id']."'";
	$q_delete = mysql_query($delete,$hos) or die (mysql_error());

	mysql_select_db($database_hos, $hos);
	$delete ="delete from ".$database_kohrx.".kohrx_med_error_error_type  where id ='".$_GET['id']."'";
	$q_delete = mysql_query($delete,$hos) or die (mysql_error());	
	}
	
	if($_GET['type']=="error_cause"){
	mysql_select_db($database_hos, $hos);
	$delete ="delete from ".$database_kohrx.".kohrx_med_error_error_cause  where id ='".$_GET['id']."'";
	$q_delete = mysql_query($delete,$hos) or die (mysql_error());	

	mysql_select_db($database_hos, $hos);
	$delete ="delete from ".$database_kohrx.".kohrx_med_error_error_sub_cause  where cause_id ='".$_GET['id']."'";
	$q_delete = mysql_query($delete,$hos) or die (mysql_error());	

	}
	if($_GET['type']=="error_subtype"){
	mysql_select_db($database_hos, $hos);
	$delete ="delete from ".$database_kohrx.".kohrx_med_error_error_sub_cause  where id ='".$_GET['id']."'";
	$q_delete = mysql_query($delete,$hos) or die (mysql_error());	
	}
}


if(isset($type)&&($type=="down")){
$order_down=$order-1;
$order_up=$order+1;
mysql_select_db($database_hos, $hos);
$update ="update ".$database_kohrx.".kohrx_med_error_error_type set order_type=order_type-1 where order_type ='$order_up'";
$query_update = mysql_query($update,$hos) or die (mysql_error());

$update ="update ".$database_kohrx.".kohrx_med_error_error_type set order_type=order_type+1 where id='$id'";
$query_update = mysql_query($update,$hos) or die (mysql_error());


}
if(isset($type)&&($type=="up")){
$order_down=$order-1;
$order_up=$order+1;
mysql_select_db($database_hos, $hos);
$update ="update ".$database_kohrx.".kohrx_med_error_error_type set order_type=order_type+1 where order_type ='$order_down'";
$query_update = mysql_query($update,$hos) or die (mysql_error());

$update ="update ".$database_kohrx.".kohrx_med_error_error_type set order_type=order_type-1 where id='$id'";
$query_update = mysql_query($update,$hos) or die (mysql_error());
}
if(isset($status)&&($status=="show")){
mysql_select_db($database_hos, $hos);
$update ="update ".$database_kohrx.".kohrx_med_error_error_type set  status=1 where id ='$id'";
$query_update = mysql_query($update,$hos) or die (mysql_error());
}

if(isset($status)&&($status=="not")){
mysql_select_db($database_hos, $hos);
$update ="update ".$database_kohrx.".kohrx_med_error_error_type set  status=2 where id ='$id'";
$query_update = mysql_query($update,$hos) or die (mysql_error());
}

if(isset($del)&&($del=="del")){
$search = "select count(c.id) as count_id from ".$database_kohrx.".kohrx_med_error_cause c left outer join ".$database_kohrx.".kohrx_med_error_error_cause ec on ec.id=c.cause_id  where ec.type_id='$id'";
$query_search=mysql_query($search,$hos) or die (mysql_error());
$row_search = mysql_fetch_assoc($query_search);
$totalRows_search = mysql_num_rows($query_search);
if($row_search['count_id']!=0){
echo '<br /><br /><div align="center">ลบไม่ไำ้ด้เนื่องจาก  มีบางรายงานที่ยังใช้ประเภทความคลาดคลื่อนนี้ <br />
    <span class="red2"><a href="javascript:history.back()">&lt;&lt; ย้อนกลับ</a> </span> </div>';
exit();
}
mysql_select_db($database_hos, $hos);
$update ="delete from ".$database_kohrx.".kohrx_med_error_error_type where id ='$id'";
$query_update = mysql_query($update,$hos) or die (mysql_error());

$update ="delete from ".$database_kohrx.".kohrx_med_error_error_cause where type_id ='$id'";
$query_update = mysql_query($update,$hos) or die (mysql_error());
}
?>
<?php
mysql_select_db($database_hos, $hos);
$query_type_error = "SELECT * FROM ".$database_kohrx.".kohrx_med_error_error_type ORDER BY `order_type`ASC";
$type_error = mysql_query($query_type_error, $hos) or die(mysql_error());
$row_type_error = mysql_fetch_assoc($type_error);
$totalRows_type_error = mysql_num_rows($type_error);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php include('java_css_file.php'); ?> 
<style type="text/css">
<!--
body {
	background-color: #FFFFFF;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
-->
html,body { height:100%; overflow: hidden; }

::-webkit-scrollbar { width: 15px; }

::-webkit-scrollbar-track {
    box-shadow: inset 0 0 10px 10px #E6E6E6;
    border: solid 3px transparent;
}

::-webkit-scrollbar-thumb {
    box-shadow: inset 0 0 10px 10px #CCCCCC;
    border: solid 3px transparent;
}

.ui-autocomplete {
	    position:absolute;
		margin-top:50px;
		margin-left:10px;
		padding-right:5px;
        max-height:300px !important;
        overflow: auto !important;
    	font-family: th_saraban;
		src:url(admin/font/thsarabunnew-webfont.woff);
        font-size: 14px;

}

</style>
<script>
$(document).ready(function(){
	//ซ่อน
	$('#error_type_edit').hide();
	$('#error_type_edit_btn').hide();
	$('#error_type_edit_btn2').hide();
	$('#error_type_delete_btn').hide();
	$('#error_cause_open').hide();
	$('#error_cause_edit').hide();
	$('#error_cause_edit_btn').hide();
	$('#error_cause_edit_btn2').hide();
	$('#error_cause_delete_btn').hide();
	$('#error_subtype_open').hide();
	$('#error_subtype_edit').hide();
	$('#error_subtype_edit_btn').hide();
	$('#error_subtype_edit_btn2').hide();
	$('#error_subtype_delete_btn').hide();
	
	
	//เลือกความคลาดเคลื่อนหลัก
$("#error_type").change(function()
    {
		$('#error_type_edit').hide();
		$('#error_type_open').show();
		$('#error_subtype').html('<select><option value="">= เลือกประเภทย่อย 2 =</option></select>');
			$('#error_cause_edit').hide();
			$('#error_subtype_edit').hide();
			$('#error_cause_edit_btn').hide();
			$('#error_subtype_edit_btn').hide();
			$('#error_subtype_open').hide();
	
		if($(this).val()!=""){
			$('#error_type_edit_btn').show();
			$('#error_type_delete_btn').show();
			$('#error_cause_open').show();			
		}
		else {
			$('#error_type_edit_btn').hide();		
			$('#error_type_delete_btn').hide();
			$('#error_cause_open').hide();
		}

    var id=$(this).val();
    var dataString = 'id='+ id+'&type=main&show_mr=Y';
    $("#error_subtype").val("");
    $.ajax
    ({
    type: "POST",
    url: "get_error_type.php",
    data: dataString,
    cache: false,
    success: function(html)
    {
    $("#error_cause").html(html);
    } 
    });
});
//===============//
//เลือกความคลาดเคลื่อนย่อย1
$("#error_cause").change(function()
    {
		$('#error_cause_edit').hide();
		$('#error_cause_open').show();

		$('#error_subtype_edit_btn').hide();
		$('#error_subtype_edit').hide();
		$('#error_subtype2').val('');
		$('#error_subtype_open').show();

		if($(this).val()!=""){
			$('#error_cause_edit_btn').show();
			$('#error_cause_delete_btn').show();
		}
		else {
			$('#error_cause_edit_btn').hide();		
			$('#error_cause_delete_btn').hide();
			$('#error_subtype_open').hide();
		}

    var id=$(this).val();
    var dataString = 'id='+ id+'&type=sub&show_mr=Y';
    $.ajax
    ({
    type: "POST",
    url: "get_error_type.php",
    data: dataString,
    cache: false,
    success: function(html)
    {
    $("#error_subtype").html(html);
    } 
    });
});
//===============//
//เลือกความคลาดเคลื่อนย่อย2
$("#error_subtype").change(function(){
		$('#error_subtype_edit').hide();
		$('#error_subtype_open').show();
	
		if($(this).val()!=""){
			$('#error_subtype_edit_btn').show();
			$('#error_subtype_delete_btn').show();
		}
		else {
			$('#error_subtype_edit_btn').hide();		
			$('#error_subtype_delete_btn').hide();
		}

});
	
//กดปุ่ม +
$('#error_type_open').click(function(){ 	
	//error_type
   $('#error_type_edit').show();$('#error_type2').val('');$('#error_type_open').hide();$('#error_type_edit_btn2').hide();$('#error_type_edit_btn').hide();$('#error_type_add_btn').show();	$('#error_type').val('');
	//error_cause
	$('#error_cause_open').hide();
	$('#error_type_delete_btn').hide();
	$('#error_cause_edit').hide();
	$('#error_cause').html('<select><option value="">= เลือกประเภทย่อย 1 =</option></select>');
	if($('#error_cause').val()!=""){
		$('#error_cause_edit_btn').show();
	}
	else{
		$('#error_cause_edit_btn').hide();		
	}
	//error_subtype
	$('#error_subtype_open').hide();
	$('#error_subtype_edit').hide();
	$('#error_subtype').html('<select><option value="">= เลือกประเภทย่อย 2 =</option></select>');
	if($('#error_subtype').val()!=""){
		$('#error_subtype_edit_btn').show();
	}
	else{
		$('#error_subtype_edit_btn').hide();		
	}
});

$('#error_cause_open').click(function(){ 
	$('#error_cause_edit').show();
	$('#error_cause2').val('');
	$('#error_cause_open').hide();
	$('#error_cause_delete_btn').hide();
	$('#error_cause_edit_btn2').hide();
	$('#error_cause_edit_btn').hide();
	$('#error_cause_add_btn').show();	});

$('#error_subtype_open').click(function(){ 
	$('#error_subtype_edit').show();
	$('#error_subtype2').val('');
	$('#error_subtype_open').hide();
	$('#error_subtype_delete_btn').hide();	
	$('#error_subtype_edit_btn2').hide();
	$('#error_subtype_edit_btn').hide();
	$('#error_subtype_add_btn').show();	});
	
//กดปุ่มแก้ไข
$('#error_type_edit_btn').click(function(){
	//error_type
	$('#error_type_edit').show();
	$('#error_type_open').hide();
	$('#error_type_add_btn').hide();
	$('#error_type_edit_btn2').show();
	$('#error_type_edit_btn').hide();
	
	var type_text=$('#error_type option:selected').text().split('-[');
	
	$('#error_type2').val(type_text[0]);
	if(type_text[1]=="MR]"){
		$('#mr_type').prop('checked',true);
	}
	else{
		$('#mr_type').prop('checked',false);		
	}
	//error_cause
	$('#error_cause_edit').hide();
	$('#error_cause_open').show();
	$('#error_cause_edit_btn').hide();
	//error_subtype
	$('#error_subtype_edit').hide();
	$('#error_subtype_open').hide();
	$('#error_subtype_edit_btn').hide();
});	
$('#error_cause_edit_btn').click(function(){
	//error_type
	$('#error_type_edit').hide();
	$('#error_type_open').show();
	$('#error_type_edit_btn').show();
	//error_subtype
	$('#error_subtype_edit').hide();
	$('#error_subtype_open').show();
	$('#error_subtype_edit_btn').hide();
	
	//error_cause
	$('#error_cause_edit').show();
	$('#error_cause_open').hide();
	$('#error_cause_add_btn').hide();
	$('#error_cause_edit_btn2').show();
	$('#error_cause_edit_btn').hide();
	var type_text=$('#error_cause option:selected').text().split('-[');
	
	$('#error_cause2').val(type_text[0]);
	if(type_text[1]=="MR]"){
		$('#mr_cause').prop('checked',true);
	}
	else{
		$('#mr_cause').prop('checked',false);		
	}
	
});	
$('#error_subtype_edit_btn').click(function(){
	//error_type
	$('#error_type_edit').hide();
	$('#error_type_open').show();
	$('#error_type_edit_btn').show();
	
	//error_cause
	$('#error_cause_edit').hide();
	$('#error_cause_open').show();
	$('#error_cause_edit_btn').show();
	//error_subtype
	$('#error_subtype_edit').show();
	$('#error_subtype_open').hide();
	$('#error_subtype_add_btn').hide();
	$('#error_subtype_edit_btn2').show();
	$('#error_subtype_edit_btn').hide();
	var type_text=$('#error_subtype option:selected').text().split('-[');
	
	$('#error_subtype2').val(type_text[0]);
	if(type_text[1]=="MR]"){
		$('#mr_subtype').prop('checked',true);
	}
	else{
		$('#mr_subtype').prop('checked',false);		
	}
	
});	

//กดปุ่มยกเลิก
$('#error_type_cancel').click(function(){
	$('#error_type_edit').hide();
	$('#error_type_open').show();
	$('#error_type_edit_btn').show();
	//error_cause
	$('#error_cause_open').hide();
	$('#error_cause_edit').hide();
	$('#error_cause').val("");
	$('#error_cause_edit_btn').hide();
	
	//error_subtype
	$('#error_subtype_open').hide();
	$('#error_subtype_edit').hide();
	$('#error_subtype').html('<select><option value="">= เลือกประเภทย่อย 2 =</option></select>');
	$('#error_subtype_edit_btn').hide();
});
	
$('#error_cause_cancel').click(function(){
	$('#error_cause_edit').hide();
	$('#error_cause_open').show();
	$('#error_cause_edit_btn').show();
	$('#error_subtype').val("");
	
});

$('#error_subtype_cancel').click(function(){
	$('#error_subtype_edit').hide();
	$('#error_subtype_open').show();
	$('#error_subtype_edit_btn').show();	
});

//แก้ไขข้อมูล error_type
$('#error_type_edit_btn2').click(function(){
	window.location='config_error_type.php?type=error_type&id='+$('#error_type').val()+'&text='+encodeURIComponent($('#error_type2').val())+'&mr='+($('#mr_type').prop('checked') ? 'Y' : '')+'&action=edit';
	});
//แก้ไขข้อมูล error_cause
$('#error_cause_edit_btn2').click(function(){
	window.location='config_error_type.php?type=error_cause&id='+$('#error_cause').val()+'&text='+encodeURIComponent($('#error_cause2').val())+'&mr='+($('#mr_cause').prop('checked') ? 'Y' : '')+'&action=edit';
	});
//แก้ไขข้อมูล error_cause
$('#error_subtype_edit_btn2').click(function(){
	window.location='config_error_type.php?type=error_subtype&id='+$('#error_subtype').val()+'&text='+encodeURIComponent($('#error_subtype2').val())+'&mr='+($('#mr_subtype').prop('checked') ? 'Y' : '')+'&action=edit';
	});	
//เพิ่มข้อมูล error_type
$('#error_type_add_btn').click(function(){
	window.location='config_error_type.php?type=error_type&text='+encodeURIComponent($('#error_type2').val())+'&mr='+($('#mr_type').prop('checked') ? 'Y' : '')+'&action=add';
	});
//เพิ่มข้อมูล error_cause
$('#error_cause_add_btn').click(function(){
	window.location='config_error_type.php?type=error_cause&text='+encodeURIComponent($('#error_cause2').val())+'&mr='+($('#mr_cause').prop('checked') ? 'Y' : '')+'&action=add&error_type_id='+$('#error_type').val();
	});
//เพิ่มข้อมูล error_subtype
$('#error_subtype_add_btn').click(function(){
	window.location='config_error_type.php?type=error_subtype&text='+encodeURIComponent($('#error_subtype2').val())+'&mr='+($('#mr_subtype').prop('checked') ? 'Y' : '')+'&action=add&error_cause_id='+$('#error_cause').val();
	});
//ลบข้อมูล error_type
$('#error_type_delete_btn').click(function(){
	if(confirm('ต้องการลบข้อมูลนี้จริงหรือไม่')==true){window.location='config_error_type.php?type=error_type&id='+$('#error_type').val()+'&action=delete';}
	});	
//ลบข้อมูล error_cause
$('#error_cause_delete_btn').click(function(){
	if(confirm('ต้องการลบข้อมูลนี้จริงหรือไม่')==true){window.location='config_error_type.php?type=error_cause&id='+$('#error_cause').val()+'&action=delete';}
	});	
//ลบข้อมูล error_subtype
$('#error_subtype_delete_btn').click(function(){
	if(confirm('ต้องการลบข้อมูลนี้จริงหรือไม่')==true){window.location='config_error_type.php?type=error_subtype&id='+$('#error_subtype').val()+'&action=delete';}
	});		
	
});
	
</script>
</head>

<body>
<nav class="navbar navbar-dark bg-info text-white ">
  <!-- Navbar content -->
    <span class="font18"><i class="fas fa-laptop-medical font20"></i>&ensp;กำหนดประเภทความคลาดเคลื่อนทางด้านยา</span></nav>
<div style=" padding-right: 0px; overflow:scroll;overflow-x:hidden;overflow-y:auto; height:95vh; padding: 10px;">
	<div class="form-group row">
		<label class="col-form-label-sm col-sm-3" for="error_type">ประเภทความคลาดเคลื่อน</label>
		<div class="col-sm-6">
		<select class="form-control form-control-sm" id="error_type">
		<option value="">-- ไม่เลือก --</option>	
		<?php do { ?>
			<option value="<?php echo $row_type_error['id']; ?>"><?php echo $row_type_error['type_thai']; if($row_type_error['mr']=="Y"){ echo "-[MR]"; } ?></option>
		<?php
		} while ($row_type_error = mysql_fetch_assoc($type_error));
		  $rows = mysql_num_rows($type_error);
		  if($rows > 0) {
			  mysql_data_seek($type_error, 0);
			 $row_type_error = mysql_fetch_assoc($type_error);
		  }
		?>
	
		</select>
		</div>
		<div class="col-sm-auto"><button class="btn btn-secondary btn-sm" id="error_type_open"><i class="fas fa-plus font20"></i></button>&nbsp;<button  class="btn btn-success btn-sm" id="error_type_edit_btn">แก้ไข</button></div>

	</div>
	<div class="form-group row" id="error_type_edit">
		<label class="col-form-label-sm col-sm-3 text-danger" for="error_type2">แก้ไขประเภทความคลาดเคลื่อน</label>
		<div class="col-sm-5"><input type="text" id="error_type2" class="form-control form-control-sm" style="background-color: #FDF79E"/></div>
		<div class="custom-control custom-switch col-sm-1">
			<input type="checkbox" class="custom-control-input" id="mr_type" name="mr_type">
			<label class="custom-control-label" for="mr_type">MR</label>
		  </div>
		
		<div class="col-sm-auto"><button class="btn btn-primary btn-sm" id="error_type_add_btn" >เพิ่ม</button>&nbsp;<button class="btn btn-success btn-sm" id="error_type_edit_btn2">แก้ไข</button>&nbsp;<button class="btn btn-danger btn-sm" id="error_type_delete_btn" >ลบ</button>&nbsp;<button class="btn btn-danger btn-sm" id="error_type_cancel">ยกเลิก</button></div>

	</div>

	<div class="form-group row">
		<label class="col-form-label-sm col-sm-3" for="error_cause">ประเภทความคลาดเคลื่อนย่อย 1</label>
		<div class="col-sm-6">
		<select id="error_cause" name="error_cause" class="form-control form-control-sm">
                <option value="">= เลือกประเภทย่อย 1 =</option>
            </select>
		</div>
		
		<div class="col-sm-auto"><button class="btn btn-secondary btn-sm" id="error_cause_open"><i class="fas fa-plus font20"></i></button>&nbsp;<button  class="btn btn-success btn-sm" id="error_cause_edit_btn">แก้ไข</button></div>
	</div>
	<div class="form-group row" id="error_cause_edit">
		<label class="col-form-label-sm col-sm-3 text-danger" for="error_cause2">แก้ไขความคลาเดลื่อนย่อย 1</label>
		<div class="col-sm-5"><input type="text" id="error_cause2" class="form-control form-control-sm" style="background-color: #FDF79E"/></div>
		<div class="custom-control custom-switch col-sm-1">
			<input type="checkbox" class="custom-control-input" id="mr_cause" name="mr_cause">
			<label class="custom-control-label" for="mr_cause">MR</label>
		  </div>		
		<div class="col-sm-auto"><button class="btn btn-primary btn-sm" id="error_cause_add_btn" >เพิ่ม</button>&nbsp;<button class="btn btn-success btn-sm" id="error_cause_edit_btn2" >แก้ไข</button>&nbsp;<button class="btn btn-danger btn-sm" id="error_cause_delete_btn" >ลบ</button>&nbsp;<button class="btn btn-danger btn-sm" id="error_cause_cancel">ยกเลิก</button></div>

	</div>

	<div class="form-group row">
		<label class="col-form-label-sm col-sm-3" for="error_subtype">ประเภทความคลาดเคลื่อนย่อย 2</label>
		<div class="col-sm-6">
			<select id="error_subtype" name="error_subtype" class="form-control form-control-sm">
                <option value="">= เลือกประเภทย่อย 2 =</option>
            </select>
		</div>
		<div class="col-sm-auto"><button class="btn btn-secondary btn-sm" id="error_subtype_open"><i class="fas fa-plus font20"></i></button>&nbsp;<button  class="btn btn-success btn-sm" id="error_subtype_edit_btn">แก้ไข</button></div>	</div>
	<div class="form-group row" id="error_subtype_edit">
		<label class="col-form-label-sm col-sm-3 text-danger" for="error_subtype2">แก้ไขความคลาเดลื่อนย่อย 2</label>
		<div class="col-sm-5"><input type="text" id="error_subtype2" class="form-control form-control-sm" style="background-color: #FDF79E"/></div>
		<div class="custom-control custom-switch col-sm-1">
			<input type="checkbox" class="custom-control-input" id="mr_subtype" name="mr_subtype">
			<label class="custom-control-label" for="mr_subtype">MR</label>
		  </div>		
		<div class="col-sm-auto"><button class="btn btn-primary btn-sm" id="error_subtype_add_btn" >เพิ่ม</button>&nbsp;<button class="btn btn-success btn-sm" id="error_subtype_edit_btn2" >แก้ไข</button>&nbsp;<button class="btn btn-danger btn-sm" id="error_subtype_delete_btn" >ลบ</button>&nbsp;<button class="btn btn-danger btn-sm" id="error_subtype_cancel">ยกเลิก</button></div>

	</div>
	<div class="form-group row" id="error_subtype_edit">
		<div class="col-sm-3"></div>
		<div class="col-sm-9 text-secondary"><i>MR = Medication Reconcile</i></div>
	</div>

	</div>
</body>
</html>
<?php
mysql_free_result($type_error);
?>
