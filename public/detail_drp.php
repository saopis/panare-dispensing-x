<?php ob_start();?>
<?php session_start();?>
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
//echo $_GET['pt'];

if(isset($_POST['save'])&&($_POST['save']=="บันทึก")){
	$detail= str_replace("\n", "<br>\n", $_POST['detail']); 
	$solv= str_replace("\n", "<br>\n", $_POST['solv']); 
	$result= str_replace("\n", "<br>\n", $_POST['result']); 

	mysql_select_db($database_hos, $hos);
	$insert = "insert into ".$database_kohrx.".kohrx_drp_record (record_date,hn,drp_cat,title,detail,solv,result,last_update,recorder,attach,risk_category,pttype)  value (CURDATE(),'".$_POST['hn']."','".$_POST['cat']."','".$_POST['problem']."','".$detail."','".$solv."','".$result."',NOW(),'".$recorder."','".$_POST['attach']."','".$_POST['category']."','".$_POST['pttype']."')";
	$sinsert = mysql_query($insert, $hos) or die(mysql_error());
//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_drp_record (record_date,hn,drp_cat,title,detail,solv,result,last_update,recorder,attach,pttype)  value (CURDATE(),\'".$_POST['hn']."\',\'".$_POST['cat']."\',\'".$_POST['problem']."\',\'".$detail."\',\'".$solv."\',\'".$result."\',NOW(),\'".$recorder."\',\'".$_POST['attach']."\',\'".$_POST['category']."\',\'".$_POST['pttype']."\')')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

//ค้นหายาที่เกี่ยวข้อง
mysql_select_db($database_hos, $hos);
$query_drp_record = "select * from ".$database_kohrx.".kohrx_drp_record order by id desc limit 1";
$drp_record = mysql_query($query_drp_record, $hos) or die(mysql_error());
$row_drp_record = mysql_fetch_assoc($drp_record);
$totalRows_drp_record = mysql_num_rows($drp_record);

	mysql_select_db($database_hos, $hos);
	$update = "update ".$database_kohrx.".kohrx_drp_drug set drp_id='$row_drp_record[id]' where drp_id is NULL";
	$supdate = mysql_query($update, $hos) or die(mysql_error());
//insert replicate_log
	mysql_select_db($database_hos, $hos);


	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_drp_drug set drp_id=\'".$row_drp_record['id']."\' where drp_id is NULL')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
mysql_free_result($drp_record);
///////////////
if($_SESSION['pt']=="OPD"){
echo "<script>parent.$.fn.colorbox.close();parent.drug_list_reload();</script>";
}
else if($_SESSION['pt']=="IPD"){
echo "<script>parent.$.fn.colorbox.close();parent.drp_load_list();</script>";
}
exit();
}
if(isset($_POST['edit'])&&($_POST['edit']=="แก้ไข")){
	$detail= str_replace("\n", "<br>\n", $_POST['detail']); 
	$solv= str_replace("\n", "<br>\n", $_POST['solv']); 
	$result= str_replace("\n", "<br>\n", $_POST['result']); 

	mysql_select_db($database_hos, $hos);
	$update = "update ".$database_kohrx.".kohrx_drp_record set drp_cat='".$_POST['cat']."',title='".$_POST['problem']."',detail='".$detail."',solv='".$solv."',result='".$result."',last_update=NOW(),recorder='".$_POST['recorder']."',attach='".$_POST['attach']."',risk_category='".$_POST['category']."',pttype='".$_POST['pttype']."' where id='".$_POST['id']."'";
	$supdate = mysql_query($update, $hos) or die(mysql_error());
	
	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_drp_record set drp_cat=\'".$_POST['cat']."\',title=\'".$_POST['problem']."\',detail=\'".$detail."\',solv=\'".$solv."\',result=\'".$result."\',last_update=NOW(),recorder=\'".$_POST['recorder']."\',attach=\'".$_POST['attach']."\',risk_category=\'".$_POST['category']."\',pttype=\'".$_POST['pttype']."\' where id=\'".$_POST['id']."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

if($_SESSION['pt']=="OPD"){
echo "<script>parent.$.fn.colorbox.close();parent.drug_list_reload();</script>";
}
else if($_SESSION['pt']=="IPD"){
echo "<script>parent.$.fn.colorbox.close();parent.drp_load_list();</script>";
}
	
	exit();
}
if(isset($_POST['delete'])&&($_POST['delete']=="ลบข้อมูล")){
	mysql_select_db($database_hos, $hos);
	$update = "delete from ".$database_kohrx.".kohrx_drp_record where id='".$_POST['id']."'";
	$supdate = mysql_query($update, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','delete from ".$database_kohrx.".kohrx_drp_record where id=\'".$_POST['id']."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

	mysql_select_db($database_hos, $hos);
	$update = "delete from ".$database_kohrx.".kohrx_drp_drug where drp_id='".$_POST['id']."'";
	$supdate = mysql_query($update, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','delete from ".$database_kohrx.".kohrx_drp_drug where drp_id=\'".$_POST['id']."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

if($_SESSION['pt']=="OPD"){
echo "<script>parent.$.fn.colorbox.close();parent.drug_list_reload();</script>";
}
else if($_SESSION['pt']=="IPD"){
echo "<script>parent.$.fn.colorbox.close();parent.drp_load_list();</script>";
}
exit();

}

if($_GET['id']!=""){
mysql_select_db($database_hos, $hos);
$query_edit = "select * from ".$database_kohrx.".kohrx_drp_record where id='".$_GET['id']."'";
$edit = mysql_query($query_edit, $hos) or die(mysql_error());
$row_edit = mysql_fetch_assoc($edit);
$totalRows_edit = mysql_num_rows($edit);
$pttype=$row_edit['pttype'];
}
else{
$pttype=$_GET['pttype'];	
	}	
mysql_select_db($database_hos, $hos);
$query_drp_cat = "select * from ".$database_kohrx.".kohrx_drp_category";
$drp_cat = mysql_query($query_drp_cat, $hos) or die(mysql_error());
$row_drp_cat = mysql_fetch_assoc($drp_cat);
$totalRows_drp_cat = mysql_num_rows($drp_cat);

mysql_select_db($database_hos, $hos);
$query_rs_setting = "select * from ".$database_kohrx.".kohrx_dispensing_setting order by id ASC";
$rs_setting = mysql_query($query_rs_setting, $hos) or die(mysql_error());
$row_rs_setting = mysql_fetch_assoc($rs_setting);
$totalRows_rs_setting = mysql_num_rows($rs_setting);

$i=0; do{ $i++;
$row_setting[$i]=$row_rs_setting['value'];} 
while($row_rs_setting = mysql_fetch_assoc($rs_setting));

mysql_select_db($database_hos, $hos);
$query_rs_doctor = "SELECT o.name,o.doctorcode,o.hospital_department_id FROM opduser o left outer join doctor d on d.code=o.doctorcode WHERE o.hospital_department_id ='$row_setting[1]' and d.active='Y' order by name";
$rs_doctor = mysql_query($query_rs_doctor, $hos) or die(mysql_error());
$row_rs_doctor = mysql_fetch_assoc($rs_doctor);
$totalRows_rs_doctor = mysql_num_rows($rs_doctor);

//ลบรายการยาถ้าไม่ได้บันทึก DRP
mysql_select_db($database_hos, $hos);
	$update = "delete from ".$database_kohrx.".kohrx_drp_drug where drp_id is NULL";
	$supdate = mysql_query($update, $hos) or die(mysql_error());

mysql_select_db($database_hos, $hos);
$query_rs_drug = "SELECT drugitems.icode as drugcode,concat(drugitems.name, drugitems.strength) as drugname FROM drugitems WHERE drugitems.name not like '%คิด%' ORDER BY drugitems.name ASC";
$rs_drug = mysql_query($query_rs_drug, $hos) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php include('java_css_file.php'); ?>   
<link href="include/kohrx/css/kohrx.css" rel="stylesheet" type="text/css" />
<!--//////////////////////////////////////// -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote-bs4.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote-bs4.min.js"></script>
	<script type="text/javascript" src="include/jquery.mousewheel-3.0.6.pack.js"></script>


	<script type="text/javascript">
$(document).ready(function() {
	$('.fancybox-media').fancybox({
		//'title'	: 'Drug Interacion',
		'type' : 'iframe',
		'autoSize': true,
		'autoScale': false,
		maxWidth : 1200,
		minHeight   : 500,
		arrows : false,
		beforeClose :function(){formSubmit('Q','displayIndiv','indicator');}
	});
	$('#summernote').summernote();
});

function fancyboxClose(){
 $.fancybox.close();
 	}
	
function formDrug(sID,displayDiv,indicator,eID) {
	if(sID!=''){ $('#do').val(sID);}
	if(eID!=''){ $('#id2').val(eID);}
	 var URL = "detail_drp_drug.php"; 
	var data = getFormData("form1");
	ajaxLoad('post', URL, data, displayDiv,indicator);
	var e = document.getElementById(indicator);
	e.style.display = 'block';
		}

function page_load(divid,page,action,id){ 
			$('#indicator2').show();
			$("#"+divid).load(page+'?do='+action+'&id='+id+'&icode='+$('#drug').val(),function(responseTxt, statusTxt, xhr){
        	if(statusTxt == "success")
			$('#indicator2').hide();
	        if(statusTxt == "error")
            alert("โหลดข้อมูลไม่สำเร็จ กรุณาลองใหม่อีกครั้ง");
    	});
	}
function page_load2(divid,page,action,id,icode){ 
			$('#indicator2').show();
			$("#"+divid).load(page+'?do='+action+'&id='+id+'&icode='+icode,function(responseTxt, statusTxt, xhr){
        	if(statusTxt == "success")
			$('#indicator2').hide();
	        if(statusTxt == "error")
            alert("โหลดข้อมูลไม่สำเร็จ กรุณาลองใหม่อีกครั้ง");
    	});
	}

	</script>
<style>
html,body { height:100%; }

::-webkit-scrollbar { width: 15px; }

::-webkit-scrollbar-track {
    box-shadow: inset 0 0 10px 10px #E6E6E6;
    border: solid 3px transparent;
}

::-webkit-scrollbar-thumb {
    box-shadow: inset 0 0 10px 10px #CCCCCC;
    border: solid 3px transparent;
}
	</style>
</head>

<body <?php if($_GET['id']!=""){?>onload="page_load('Drugadd','detail_drp_drug.php','load','<?php echo $row_edit['id']; ?>');"<?php } ?>>
<div class="bg-secondary" style="height: 50px; margin-top: 0px;">
<h6 class="text-white text-center pt-3"><i class="fas fa-exclamation-circle font20"></i>&emsp;บันทึกปัญหาที่เกิดจากการใช้ยา</h6>
</div>

<div style="overflow:scroll;overflow-x:hidden;overflow-y:auto; height:90vh;">
<form id="form1" name="form1" method="post" action="detail_drp.php" class="p-3">
<div class="form-row">
	 <div class="form-group col-md-6">
     	<label for= "cat">ประเภท DRP</label>
        <select name="cat" class=" form-control" id="cat">
            <?php
do {  
?>
            <option value="<?php echo $row_drp_cat['drp_cat']?>" <?php if (!(strcmp($row_drp_cat['drp_cat'], $row_edit['drp_cat']))) {echo "selected=\"selected\"";} ?>><?php echo $row_drp_cat['drp_cat']."=".$row_drp_cat['name_thai']."(".$row_drp_cat['name_eng'].")"; ?></option>
            <?php
} while ($row_drp_cat = mysql_fetch_assoc($drp_cat));
  $rows = mysql_num_rows($drp_cat);
  if($rows > 0) {
      mysql_data_seek($drp_cat, 0);
	  $row_drp_cat = mysql_fetch_assoc($drp_cat);
  }
?>
          </select>
          <input name="id" type="hidden" id="id" value="<?php echo $_GET['id']; ?>" />
          <input name="hn" type="hidden" id="hn" value="<?php echo $hn; ?>" />
        <input name="do" type="hidden" id="do" value="<?php echo $hn; ?>" />        
     </div>
	 <div class="form-group col-md-3">
	 <label for="category">Risk Category</label>
     <select name="category" id="category" class="form-control">
       <option value="" <?php if (!(strcmp("", $row_edit['risk_category']))) {echo "selected=\"selected\"";} ?>>ไม่ระบุ</option>
       <option value="A" <?php if (!(strcmp("A", $row_edit['risk_category']))) {echo "selected=\"selected\"";} ?>>A</option>
       <option value="B" <?php if (!(strcmp("B", $row_edit['risk_category']))) {echo "selected=\"selected\"";} ?>>B</option>
<option value="C" <?php if (!(strcmp("C", $row_edit['risk_category']))) {echo "selected=\"selected\"";} ?>>C</option>
       <option value="D" <?php if (!(strcmp("D", $row_edit['risk_category']))) {echo "selected=\"selected\"";} ?>>D</option>
       <option value="E" <?php if (!(strcmp("E", $row_edit['risk_category']))) {echo "selected=\"selected\"";} ?>>E</option>
       <option value="F" <?php if (!(strcmp("F", $row_edit['risk_category']))) {echo "selected=\"selected\"";} ?>>F</option>
       <option value="G" <?php if (!(strcmp("G", $row_edit['risk_category']))) {echo "selected=\"selected\"";} ?>>G</option>
       <option value="I" <?php if (!(strcmp("I", $row_edit['risk_category']))) {echo "selected=\"selected\"";} ?>>I</option>
          </select>

     </div>
	 <div class="form-group col-md-3">
	 <label for="pttype">ประเภทผู้ป่วย</label>
     <select name="pttype" id="pttype" class="form-control">
     <option value="opd" <?php if (!(strcmp("opd", $pttype))) {echo "selected=\"selected\"";} ?>>OPD</option>
     <option value="ipd" <?php if (!(strcmp("ipd", $pttype))) {echo "selected=\"selected\"";} ?>>IPD</option>
	</select>
    </div>     
</div>
<div class="form-row">
	 <div class="form-group col-md-12">
	 <label for="problem">ประเด็นปัญหา</label>
     <input name="problem" type="text" class=" form-control" id="problem" value="<?php echo $row_edit['title']; ?>" size="50" />     
     </div>
</div>
<div class="form-row">
	 <div class="form-group col-md-6">
     <label for="drug">ยาที่เกี่ยวข้อง</label>
         <select name="drug" class=" form-control" id="drug">
          <?php
do {  
?>
          <option value="<?php echo $row_rs_drug['drugcode']?>"><?php echo $row_rs_drug['drugname']?></option>
          <?php
} while ($row_rs_drug = mysql_fetch_assoc($rs_drug));
  $rows = mysql_num_rows($rs_drug);
  if($rows > 0) {
      mysql_data_seek($rs_drug, 0);
	  $row_rs_drug = mysql_fetch_assoc($rs_drug);
  }
?>
        </select>  
  		</div>
	 <div class="form-group col-md-6">        
        <input type="button" name="button8" id="button8" value="เพิ่ม" class=" btn btn-success" style="margin-top:32px;" onclick="page_load('Drugadd','detail_drp_drug.php','insert','<?php echo $row_edit['id']; ?>');" />
        <span class="table_head_small">
        <input name="id2" type="hidden" id="id2" value="<?php echo $id; ?>" />
        </span>  
	 </div>
</div>
<!-- row -->
<div id="Drugadd" ></div>
<div class="form-row">
	<div class="form-group col-md-12">
    <label for="detail" >รายละเอียด</label>
<textarea name="detail" id="detail" ><?php echo str_replace("<br>\n","\n", $row_edit['detail']); ?></textarea>
    <script>
      $('#detail').summernote({
        placeholder: 'รายละเอียด',
        tabsize: 2,
        height: 100
      });
    </script>    
    </div>
</div>
<div class="form-row">
	<div class="form-group col-md-12">
    <label for="solv" >ข้อเสนอแนะ/แนวทางการแก้ไข</label>
<textarea name="solv" id="solv" ><?php echo str_replace("<br>\n","\n", $row_edit['solv']); ?></textarea>
    <script>
      $('#solv').summernote({
        placeholder: 'ข้อเสนอแนะ/แนวทางการแก้ไข',
        tabsize: 2,
        height: 100
      });
    </script>    
    </div>
</div>
<div class="form-row">
	<div class="form-group col-md-12">
    <label for="result" >ผลลัพธ์</label>
<textarea name="result" id="result" ><?php echo str_replace("<br>\n","\n", $row_edit['result']); ?></textarea>
    <script>
      $('#result').summernote({
        placeholder: 'ผลลัพธ์',
        tabsize: 2,
        height: 100
      });
    </script>    
    </div>
</div>
<div class="form-row">
	<div class="form-group col-md-12">
	<label for="recorder">ผู้ให้คำปรึกษา</label>	
        <select name="recorder" id="recorder" class="form-control" >
          <?php
do {  
?>
          <option value="<?php echo $row_rs_doctor['doctorcode']?>"<?php if($row_edit['recorder']!=""){ if (!(strcmp($row_rs_doctor['doctorcode'],$row_edit['recorder']))) {echo "selected=\"selected\"";}} else { if (!(strcmp($row_rs_doctor['doctorcode'],$_SESSION['doctorcode']))) {echo "selected=\"selected\"";}}  ?> <?php if(isset($pay_staff)){ echo "style=\"background-color:#FC0\""; } ?>><?php echo $row_rs_doctor['name']?></option>
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
    <?php if(!isset($id)){?>
    <input type="submit" name="save" id="save" class="btn btn-primary" value="บันทึก" />
    <?php } else { ?>
  <input type="submit" name="edit" id="edit" class="btn btn-warning" value="แก้ไข" />
  <input type="submit" name="delete" id="delete" class="btn btn-danger" value="ลบข้อมูล" onclick="return confirm('ยืนยันการลบข้อมูล?')" />
  <? } ?>
</form>
</div>	

</body>
</html>
<?php
mysql_free_result($drp_cat);
if($id!=""){
mysql_free_result($edit);
}
mysql_free_result($rs_drug);
?>
