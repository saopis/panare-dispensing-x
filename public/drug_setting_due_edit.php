<?php require_once('Connections/hos.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<script>
$(document).ready(function(){
		$('#edit').hide();
		$('#cancel').hide();

	$('#save2').click(function(){
                $('#indicator').show();
                $("#displayDiv2").load('drug_setting_due_edit_list.php?action=save&cause='+encodeURIComponent($('#cause').val())+'&icode='+$('#icode').val(), function(responseTxt, statusTxt, xhr){
                    
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();
						$('#cause').val("");
                    if(statusTxt == "error")
                       alert("Error: " + xhr.status + ": " + xhr.statusText);    
               });		
	});

	$('#edit').click(function(){
                $('#indicator').show();

                $("#displayDiv2").load('drug_setting_due_edit_list.php?action=edit&cause='+encodeURIComponent($('#cause').val())+'&id='+$('#id').val()+'&icode='+$('#icode').val(), function(responseTxt, statusTxt, xhr){
                    
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();
						$('#save2').show();
						$('#edit').hide();
						$('#cancel').hide();
						$('#cause').val("");
						$('#id').val("");
                    if(statusTxt == "error")
                       alert("Error: " + xhr.status + ": " + xhr.statusText);    
               });		
	});
	
                $('#indicator').show();
                $("#displayDiv2").load('drug_setting_due_edit_list.php?icode='+$('#icode').val(), function(responseTxt, statusTxt, xhr){
                    
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();
                    if(statusTxt == "error")
                       alert("Error: " + xhr.status + ": " + xhr.statusText);    
               });		
	
});
function due_edit(id,cause){
	$('#cause').val(cause);
	$('#id').val(id);
	$('#save2').hide();
	$('#edit').show();
	$('#cancel').show();
	
}
function due_delete(id,icode){
                $("#displayDiv2").load('drug_setting_due_edit_list.php?action=delete&id='+id+'&icode='+icode, function(responseTxt, statusTxt, xhr){
                    
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();
						$('#save2').show();
						$('#edit').hide();
						$('#cancel').hide();
						$('#cause').val("");
						$('#id').val("");
                    if(statusTxt == "error")
                       alert("Error: " + xhr.status + ": " + xhr.statusText);    
               });	
}
function cancel(){
						$('#save2').show();
						$('#edit').hide();
						$('#cancel').hide();
						$('#cause').val("");
						$('#id').val("");	
}
</script>
</head>

<body>
<div class="p-3">
  <div class="form-group row">
	<label class="col-form-label">เพิ่มสาเหตุการเลือกใช้</label>
    <div class="col-sm-auto">
  		<input name="cause" type="text" class="form-control" id="cause" value="<?php echo $row_rs_edit['use_cause']; ?>" />		
	</div>
	<div class="col-sm-auto">
		<button name="save2" id="save2" class="btn btn-primary" >บันทึก</button>
		<button name="edit" id="edit" class="btn btn-primary" >แก้ไข</button>
		<button name="cancel" id="cancel" class="btn btn-danger" onClick="cancel();" >ยกเลิก</button>
	  </div>

    <input name="icode" type="hidden" id="icode" value="<?php echo $_GET['icode']; ?>" />
    <input name="id" type="hidden" id="id" />    

</div>
<div id="displayDiv2" class="p-3"></div>
</body>
</html>
