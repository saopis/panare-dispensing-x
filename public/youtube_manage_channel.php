<?php require_once('Connections/hos.php'); ?>
<?php

if(isset($_GET['action'])&&($_GET['action']=="save")){
mysql_select_db($database_hos, $hos);
$query_insert = "insert into ".$database_kohrx.".kohrx_youtube_channel (channel,channel_url,istatus) value ('".$_GET['channel_name']."','".$_GET['channel_url']."','Y')";
$rs_insert = mysql_query($query_insert, $hos) or die(mysql_error());
if($rs_insert){
	echo "<script>window.location.reload();</script>";
	exit();
}
}

if(isset($_GET['action'])&&($_GET['action']=="edit")){
mysql_select_db($database_hos, $hos);
$query_insert = "update ".$database_kohrx.".kohrx_youtube_channel set channel='".$_GET['channel_name']."',channel_url='".$_GET['channel_url']."' where id='".$_GET['id']."'";
$rs_insert = mysql_query($query_insert, $hos) or die(mysql_error());
if($rs_insert){
	echo "<script>window.location.reload();</script>";
	exit();
}
}

if(isset($_GET['action'])&&($_GET['action']=="delete")){
mysql_select_db($database_hos, $hos);
$query_insert = "delete from ".$database_kohrx.".kohrx_youtube_channel where id='".$_GET['id']."'";
$rs_insert = mysql_query($query_insert, $hos) or die(mysql_error());
if($rs_insert){
	echo "<script>window.location.reload();</script>";
	exit();
}
}

mysql_select_db($database_hos, $hos);
$query_rs_channel = "select * from ".$database_kohrx.".kohrx_youtube_channel";
$rs_channel = mysql_query($query_rs_channel, $hos) or die(mysql_error());
$row_rs_channel = mysql_fetch_assoc($rs_channel);
$totalRows_rs_channel = mysql_num_rows($rs_channel);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<?php include('java_css_online.php'); ?>

<script>
	$(document).ready(function(){
		$('#edit').hide();
		$('#save').click(function(){
                $("#result").load('youtube_manage_channel.php?action=save&channel_name='+encodeURIComponent($('#channel_name').val())+'&channel_url='+encodeURIComponent($('#channel_url').val()), function(responseTxt, statusTxt, xhr){
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                    	$('#channel_name').val("");
						$('#channel_url').val("");

					if(statusTxt == "error")
                      alert("Error: " + xhr.status + ": " + xhr.statusText);
                  });
			
		});
		$('#edit').click(function(){
                $("#result").load('youtube_manage_channel.php?action=edit&id='+$('#id').val()+'&channel_name='+encodeURIComponent($('#channel_name').val())+'&channel_url='+encodeURIComponent($('#channel_url').val()), function(responseTxt, statusTxt, xhr){
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                    	$('#channel_name').val("");
						$('#channel_url').val("");

					
					if(statusTxt == "error")
                      alert("Error: " + xhr.status + ": " + xhr.statusText);
                  });
			
		});
	});
	function youtube_edit(id,name,url){
		$('#save').hide();
		$('#edit').show();
		$('#channel_name').val(name);
		$('#channel_url').val(url);
		$('#id').val(id);
	}
	function youtube_delete(id){
                $("#result").load('youtube_manage_channel.php?action=delete&id='+id, function(responseTxt, statusTxt, xhr){
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");

					if(statusTxt == "error")
                      alert("Error: " + xhr.status + ": " + xhr.statusText);
                  });
		
	}
</script>
<style>
hr.dashed {
    border-top: 2px dashed #999;
}

hr.dotted {
    border-top: 1px dotted #999;
}

hr.solid {
    border-top: 1px solid #999;
}


hr.hr-text {
  position: relative;
    border: none;
    height: 1px;
    background: #999;
}

hr.hr-text::before {
    content: attr(data-content);
    display: inline-block;
    background: #fff;
    font-weight: bold;
    font-size: 0.85rem;
    color: #999;
    border-radius: 30rem;
    padding: 0.2rem 2rem;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

</style>
</head>

<body>
<div class="p-3">
<div class="card">
	<div class="card-header">จัดการช่อง youtube<button class="btn btn-info btn-sm position-absolute" style="right: 10px" onClick="window.location.href='queue_caller_vdo_setting.php'">ย้อนกลับ</button></div>
	<div class="card-body">
	<div class="form-group row">
		<label class="col-form-label col-form-label-sm col-sm-1">ชื่อช่อง</label>
		<div class="col-sm-2">
			<input type="text" class="form-control form-control-sm" id="channel_name" />
		</div>
		<label class="col-form-label col-form-label-sm col-sm-auto">playlist URL</label>
		<div class="col-sm-6">
			<input type="text" class="form-control form-control-sm" id="channel_url" />
			<input type="hidden" id="id"/>
		</div>
		<button class="btn btn-primary btn-sm" id="save">บันทึก</button>
		<button class="btn btn-info btn-sm" id="edit">แก้ไข</button>
		</div>
	</div>
<table class="table table-striped table-hover">
	<thead>
		<tr>
			<th>#</th>
			<th>ชื่อช่อง</th>
			<th>playlist</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php $i=0; do{ $i++; ?>
		<tr>
			<td><?php echo $i; ?></td>
			<td><?php echo $row_rs_channel['channel']; ?></td>
			<td><?php echo $row_rs_channel['channel_url']; ?></td>
			<td><nobr><i class="fas fa-pen-square text-primary" style="font-size: 30px; cursor: pointer" onClick="youtube_edit('<?php echo $row_rs_channel['id']; ?>','<?php echo $row_rs_channel['channel']; ?>','<?php echo $row_rs_channel['channel_url']; ?>')"></i>&nbsp;<i class="fas fa-minus-square text-danger" style="font-size: 30px; cursor: pointer" onClick="if(confirm('ต้องการลบรายการนี้จริงหรือไม่?')==true){ youtube_delete('<?php echo $row_rs_channel['id']; ?>');}"></i></nobr></td>
		</tr>
		<?php }while($row_rs_channel = mysql_fetch_assoc($rs_channel)); ?>
	</tbody>
</table>
</div>
</div>
<div id="result"></div>
</body>
</html>