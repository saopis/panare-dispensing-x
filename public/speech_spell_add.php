<?php require_once('Connections/hos.php'); ?>
<?php 

//ค้นหานามสกุล
mysql_select_db($database_hos, $hos);
$query_rs_patient2 = "select * from ".$database_kohrx.".kohrx_queue_patient_name_spell where name='".$_GET['search']."'";
$rs_patient2 = mysql_query($query_rs_patient2, $hos) or die(mysql_error());
$row_rs_patient2 = mysql_fetch_assoc($rs_patient2);
$totalRows_rs_patient2 = mysql_num_rows($rs_patient2);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script>
$(document).ready(function() {
    	$('#save').click(function(){

		
		});

});

function spellSave(action){
	   $('#list_indicator').show();
       $('#spell_list').load('speech_spell_list.php?search='+encodeURIComponent($('#search_name').val())+'&type='+$('#search_type').val()+'&action='+action+'&spell='+encodeURIComponent($('#spell').val())+'&ptname='+encodeURIComponent($('#ptname').val())+'&name_type=<?php echo $_GET['type']; ?>',function(responseTxt, statusTxt, xhr){
           if(statusTxt == "success")
			  $('#spell_add').html("");
              $('#list_indicator').hide();
           if(statusTxt == "error")
              alert("Error: " + xhr.status + ": " + xhr.statusText);    
           });
	}
</script>
</head>

<body>
<div class=" rounded-top text-white p-2 text-center" style="background-color:#9F7B71"><i class="fas fa-spell-check font20"></i>&ensp;เพิ่ม/แก้ไขคำสะกด</div>
<div class=" rounded-bottom border" style="background-color: #F5F5F5">
<div style="padding:20px;" class="form-row">
<div class="col-auto"><label class="col-form-label">
<?php if($_GET['type']=="fname"){ echo "ชื่อผู้ป่วย"; } else{ echo "นามสกุลผู้ป่วย";} ?></label></div>
<div class="col-auto">
<input id="ptname" class="form-control form-control-plaintext text-danger " style="font-size:20px;" readonly="readonly" value="<?php echo $_GET['search']; ?>"/>
</div>
</div>
<div style="padding:20px; padding-top:0px;">
<div class="form-row">
<div class="col-auto"><label class="col-form-label">คำสะกสด</label></div>
<div class="col-auto"><input name="spell" type="text" id="spell" class="form-control" value="<?php echo $row_rs_patient2['spell']; ?>" />
</div>
<div class="col-auto">  <?php if($totalRows_rs_patient2==0){ ?><input type="submit" class=" btn btn-primary" name="save" id="save" value="บันทึก" onclick="spellSave('save');" /><?php } else {?>
  <input type="submit" class=" btn btn-primary" name="submit2" id="submit2" onclick="spellSave('edit');" value="แก้ไข" />
  <input type="submit" class=" btn btn-danger" name="submit3" id="submit3" onclick="if (!confirm('ต้องการลบการสะกดชื่อนี้จริงหรือไม่?')) { return false } else { spellSave('delete'); }" value="ลบ" />
  <?php } ?>
</div>
</div>
</div>
</body>
</html>
<?php mysql_free_result($rs_patient2); ?>