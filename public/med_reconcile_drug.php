<?php 
ob_start();
session_start();
?>
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

include('include/function.php');

if($_GET['date_receive']!=""){
	$date_receive="'".date_th2db($_GET['date_receive'])."'";
}
else{
	$date_receive="NULL";
}
if($_GET['appdate']!=""){
	$appdate="'".date_th2db($_GET['appdate'])."'";
}
else{
	$appdate="NULL";
}
if($_GET['last_dose']!=""){
	$last_dose="'".$_GET['last_dose']."'";
}
else{
	$last_dose="NULL";
}
if($_GET['remain']!=""){
	$remain="'".$_GET['remain']."'";
}
else{
	$remain="NULL";
}
if($_GET['remark']!=""){
	$remark="'".$_GET['remark']."'";
}
else{
	$remark="NULL";
}
if($_GET['med_plan_type']!=""){
	$med_plan_type="'".$_GET['med_plan_type']."'";
}

if(isset($_GET['action'])&&($_GET['action']=="single")){
    mysql_select_db($database_hos, $hos);
    $query_rs_search = "select * from ".$database_kohrx.".kohrx_med_reconcile  where id='".$_GET['id']."'";
    $rs_search = mysql_query($query_rs_search, $hos) or die(mysql_error());
    $row_rs_search = mysql_fetch_assoc($rs_search);
    $totalRows_rs_search = mysql_num_rows($rs_search);

    mysql_select_db($database_hos, $hos);
	$query_delete = "delete from ".$database_kohrx.".kohrx_med_reconcile where id='".$_GET['id']."'";
	$rs_delete = mysql_query($query_delete, $hos) or die(mysql_error());
    

    mysql_select_db($database_hos, $hos);
    $query_rs_med = "select * from ".$database_kohrx.".kohrx_med_reconcile  where hn='".$row_rs_search['hn']."' and vstdate2='".$row_rs_search['vstdate2']."'";
    $rs_med = mysql_query($query_rs_med, $hos) or die(mysql_error());
    $row_rs_med = mysql_fetch_assoc($rs_med);
    $totalRows_rs_med = mysql_num_rows($rs_med);

    if($totalRows_rs_med==0){
        mysql_select_db($database_hos, $hos);
        $query_delete = "delete from ".$database_kohrx.".kohrx_med_reconcile_header where hn='".$row_rs_search['hn']."' and vstdate = '".$row_rs_search['vstdate2']."'";
        $rs_delete = mysql_query($query_delete, $hos) or die(mysql_error());
    }
    mysql_free_result($rs_med);
    mysql_free_result($rs_search);    

    
	echo "<script>med_reconcile_load();</script>";
	exit();	
}
if(isset($_GET['action'])&&($_GET['action']=="all")){
	mysql_select_db($database_hos, $hos);
	$query_delete = "delete from ".$database_kohrx.".kohrx_med_reconcile where hn='".$_GET['hn']."' and vstdate2='".date_th2db($_GET['vstdate'])."'";
	$rs_delete = mysql_query($query_delete, $hos) or die(mysql_error());
    
    mysql_select_db($database_hos, $hos);
	$query_delete = "delete from ".$database_kohrx.".kohrx_med_reconcile_header where hn='".$_GET['hn']."' and vstdate='".date_th2db($_GET['vstdate'])."'";
	$rs_delete = mysql_query($query_delete, $hos) or die(mysql_error());
	
    echo "<script>med_reconcile_load();</script>";
	exit();	
}
if(isset($_GET['action'])&&($_GET['action']=="save")){
	$hos_guid=md5($hn.$_GET['drugname']);
    mysql_select_db($database_hos, $hos);
    $query_rs_med = "select * from ".$database_kohrx.".kohrx_med_reconcile_header  where hn='".$_GET['hn']."' and vstdate='".date_th2db($_GET['vstdate2'])."'";
    $rs_med = mysql_query($query_rs_med, $hos) or die(mysql_error());
    $row_rs_med = mysql_fetch_assoc($rs_med);
    $totalRows_rs_med = mysql_num_rows($rs_med);

    if($totalRows_rs_med<>0){
        mysql_select_db($database_hos, $hos);
        $query_update = "update ".$database_kohrx.".kohrx_med_reconcile_header set create_time=NOW() where hn='".$_GET['hn']."' and vstdate='".date_th2db($_GET['vstdate2'])."'";
        $rs_update = mysql_query($query_update, $hos) or die(mysql_error());            
    }
    else{
        mysql_select_db($database_hos, $hos);
        $query_insert = "insert into ".$database_kohrx.".kohrx_med_reconcile_header (hn,vstdate,create_time,creator) value ('".$_GET['hn']."','".date_th2db($_GET['vstdate2'])."',NOW(),'".$_SESSION['doctorcode']."')";
        $rs_insert = mysql_query($query_insert, $hos) or die(mysql_error());        
    }
    mysql_free_result($rs_med);
    
	mysql_select_db($database_hos, $hos);
	$query_insert = "insert into ".$database_kohrx.".kohrx_med_reconcile (hn,vstdate,vstdate2,hos_guid,drug_name,drugusage,qty,src_hospcode,appdate,remain,last_dose,remark,med_plan_type) value ('".$_GET['hn']."',".$date_receive.",'".date_th2db($_GET['vstdate2'])."','".$hos_guid."','".$_GET['drugname']."','".$_GET['drugusage']."','".$_GET['qty']."','".$_GET['source']."',".$appdate.",".$remain.",".$last_dose.",".$remark.",".$med_plan_type.")";

	$rs_insert = mysql_query($query_insert, $hos) or die(mysql_error());
	echo "<script>med_reconcile_load('".$_GET['source']."');</script>";
	exit();	

}
if(isset($_GET['action'])&&($_GET['action']=="edit")){
	$hos_guid=md5($hn.$_GET['drugname']);
	mysql_select_db($database_hos, $hos);
	$query_insert = "update ".$database_kohrx.".kohrx_med_reconcile set vstdate=".$date_receive.",drug_name='".$_GET['drugname']."',drugusage='".$_GET['drugusage']."',qty='".$_GET['qty']."',src_hospcode='".$_GET['source']."',appdate=".$appdate.",remain=".$remain.",last_dose=".$last_dose.",remark=".$remark.",med_plan_type=".$med_plan_type.",hos_guid='".$hos_guid."' where id='".$_GET['id']."'";
	//echo $query_insert;
	$rs_insert = mysql_query($query_insert, $hos) or die(mysql_error());
	echo "<script>med_reconcile_load();</script>";
	exit();	

}

mysql_select_db($database_hos, $hos);
$query_rs_med = "select m.*,count(e.med_reconcile_id) as error_count from ".$database_kohrx.".kohrx_med_reconcile m left outer join ".$database_kohrx.".kohrx_med_reconcile_error e on e.med_reconcile_id=m.id  where m.hn='".$_GET['hn']."' and m.vstdate2='".date_th2db($_GET['vstdate'])."' group by m.id order by m.vstdate DESC,m.drug_name ASC";
$rs_med = mysql_query($query_rs_med, $hos) or die(mysql_error());
$row_rs_med = mysql_fetch_assoc($rs_med);
$totalRows_rs_med = mysql_num_rows($rs_med);
$an=$row_rs_med['an'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Untitled Document</title>
<style>
	::-webkit-scrollbar {
    width: 10px;
}

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
		/*margin-left:150px;*/
		padding-right:5px;
        max-height:300px !important;
        max-width:400px !important;
        overflow: auto !important;
    	font-family: th_saraban;
		src:url(font/thsarabunnew-webfont.woff);
		font-size:14px;

}
.ui-menu-item .ui-menu-item-wrapper.ui-state-active {
    background: #6693bc !important;
    color: #ffffff !important;
	border:0px;
} 
</style>
<script>
	
$(document).ready(function(){
	//เมื่อคลิ๊ก check_all
	$("#check_all").click(function () {
     $('input:checkbox').not(this).prop('checked', this.checked);
	 if ($('.icode').is(':checked')) {
		$('#save').show();
	 }
	 else{
 	$('#medplan').hide();	 
	 }
 	});
	//เมื่อคลิ๊ก icode
	$('.icode').change(function()
      {	 
	 	if ($('.icode').is(':checked')) {
		$('#medplan').show();	  
		}
		else{
	 	$('#medplan').hide();
		$('#check_all').prop('checked', false);	 			
		}
	  });
	
	$("#qty").keypress(function(event) {
  		return /\d/.test(String.fromCharCode(event.keyCode));
	});	
	$("#remain").keypress(function(event) {
  		return /\d/.test(String.fromCharCode(event.keyCode));
	});	

	set_cal( $("#date_appoint") );
	set_cal( $("#date_receive") );
	//auto complete รายการยา
        $( "#drugname" ).autocomplete({ // ใช้งาน autocomplete กับ input text id=tags
			minLength: 0, // กำหนดค่าสำหรับค้นหาอย่างน้อยเป็น 0 สำหรับใช้กับปุ่ใแสดงทั้งหมด
            source: "med_reconcile_drug_search.php?type=drugname", // กำหนดให้ใช้ค่าจากการค้นหาในฐานข้อมูล
        });	


	//auto complete วิธีใช้ยา
        $( "#drugusage" ).autocomplete({ // ใช้งาน autocomplete กับ input text id=tags
            source: "med_reconcile_drug_search.php?type=drugusage", // กำหนดให้ใช้ค่าจากการค้นหาในฐานข้อมูล
        });	

	//auto complete แหล่งที่มา
        $( "#source" ).autocomplete({ // ใช้งาน autocomplete กับ input text id=tags
            source: "med_reconcile_drug_search.php?type=source", // กำหนดให้ใช้ค่าจากการค้นหาในฐานข้อมูล
        });	

	
//กดปุ่ม save
$('#save').click(function(){
            if($('#id').val()==""){
                $("#med_reconcile_drug").load('med_reconcile_drug.php?action=save&hn='+$('#hn').val()+'&vstdate2='+encodeURIComponent($('#vstdate').val())+'&drugname='+encodeURIComponent($('#drugname').val())+'&drugusage='+encodeURIComponent($('#drugusage').val())+'&qty='+encodeURIComponent($('#qty').val())+'&source='+encodeURIComponent($('#source').val())+'&date_receive='+encodeURIComponent($('#date_receive').val())+'&appdate='+encodeURIComponent($('#date_appoint').val())+'&last_dose='+encodeURIComponent($('#last_dose').val())+'&remark='+encodeURIComponent($('#remark').val())+'&remain='+$('#remain').val()+'&med_plan_type='+$('#med_plan_type').val(), function(responseTxt, statusTxt, xhr){
                    
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();
                        $('#id').val('');
                    if(statusTxt == "error")
                        alert("Error: " + xhr.status + ": " + xhr.statusText);    
                    
                                       
                        ///////////////////////////// 
                });
            }
        else{
                $("#med_reconcile_drug").load('med_reconcile_drug.php?action=edit&hn='+$('#hn').val()+'&vstdate2='+encodeURIComponent($('#vstdate').val())+'&id='+$('#id').val()+'&drugname='+encodeURIComponent($('#drugname').val())+'&drugusage='+encodeURIComponent($('#drugusage').val())+'&qty='+encodeURIComponent($('#qty').val())+'&source='+encodeURIComponent($('#source').val())+'&date_receive='+encodeURIComponent($('#date_receive').val())+'&appdate='+encodeURIComponent($('#date_appoint').val())+'&last_dose='+encodeURIComponent($('#last_dose').val())+'&remark='+encodeURIComponent($('#remark').val())+'&remain='+$('#remain').val()+'&med_plan_type='+$('#med_plan_type').val(), function(responseTxt, statusTxt, xhr){
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();
                        $('#id').val('');
                    if(statusTxt == "error")
                        alert("Error: " + xhr.status + ": " + xhr.statusText);    
                    
                                       
                        ///////////////////////////// 
                });            
        }
	});
//จบ กดปุ่ม save
//กดปุ่ม medplan
$('#medplan').click(function(){
                $('#indicator').show();	

//get checkbox checked data as the array

				var hos_guid = new Array();
					  $('input[name="icode[]"]:checked').each(function(){
						 hos_guid.push($(this).val());
					  });



				var dataString = 'hn='+$('#hn').val()+'&vstdate='+$('#vstdate').val()+'&hos_guid='+ hos_guid+'&an='+$('#an').val();


				 $.ajax({
							type: "POST",
							url: "med_reconcile_medplan.php",
							data: dataString,
							cache: false,
							success: function(data){
								$('#medplan_save').html(data);
								$('#indicator').hide();									

				}
				 });	
			
						
					
	

	});
//จบ กดปุ่ม save

});

function edit_record(id,drugname,drugusage,qty,src,vstdate,appdate,remain,last_dose,remark,medplan_type,icode){

	if(icode!=""){
		$('#med_plan_type').prop('disabled',true);
		$('#drugname').prop('disabled',true);
	}
	else{
		$('#med_plan_type').prop('disabled',false);
		$('#drugname').prop('disabled',false);
		
	}
    $('#drugname').val(drugname);
    $('#drugusage').val(drugusage);
    $('#qty').val(qty);
    $('#source').val(src);
    $('#date_receive').val(vstdate);
    $('#id').val(id);
    $('#date_appoint').val(appdate);
    $('#remain').val(remain);
    $('#last_dose').val(last_dose);
    $('#remark').val(remark);
    $('#med_plan_type').val(medplan_type);
}

</script>

</head>

<body>
<div style="padding: 10px; width:100%">
	<div class="form-group row">
	<div class="col-sm-4"><input type="text" name="drugname" id="drugname" class="form-control form-control-sm " placeholder="ชื่อยา" /><input type="hidden" id="id" name="id" /></div>
	<div class="col-sm-3"><input type="text" name="drugusage" id="drugusage" class="form-control form-control-sm " placeholder="วิธีใช้ยา" /></div>
	<div class="col-sm-1"><input type="text" name="qty" id="qty" class="form-control form-control-sm " placeholder="ได้รับ" /></div>
	<div class="col-sm-1"><input type="text" name="remain" id="remain" class="form-control form-control-sm " placeholder="คงเหลือ" /></div>
	<div class="col-sm-3"><input type="text" name="source" id="source" class="form-control form-control-sm " placeholder="แหล่งที่มา" /></div>
  </div>
	<div class="form-group row">
	<div class="col-sm-2">
      <div class="input-group input-group-sm sm-auto">
        <div class="input-group-prepend" >
          <div class="input-group-text" style="background-color:#E3E1E1">วันได้รับ</div>
        </div>
        <input type="text" class="form-control form-control-sm" data-provide="datepicker" data-date-language="th-th" id="date_receive" name="date_receive" value="<?php echo date_db2th(date('Y-m-d')); ?>">
      </div>      
</div>
    <div class="col-sm-2">
      <div class="input-group input-group-sm sm-auto">
        <div class="input-group-prepend" >
          <div class="input-group-text" style="background-color:#E3E1E1">วันนัด</div>
        </div>
        <input type="text" class="form-control form-control-sm" data-provide="datepicker" data-date-language="th-th" id="date_appoint" name="date_appoint" value="<?php echo date_db2th(date('Y-m-d')); ?>">
      </div>      
</div>
    <div class="col-sm-3">
      <div class="input-group input-group-sm sm-auto">
        <div class="input-group-prepend" >
          <div class="input-group-text" style="background-color:#E3E1E1">Last Dose</div>
        </div>
        <input type="text" class="form-control form-control-sm" id="last_dose" name="last_dose" >
      </div>      
</div>

    <div class="col-sm-4">
      <div class="input-group input-group-sm sm-auto">
        <div class="input-group-prepend" >
          <div class="input-group-text" style="background-color:#E3E1E1">หมายเหตุ</div>
        </div>
        <input type="text" class="form-control form-control-sm" id="remark" name="remark" >
      </div>      
</div>

	</div>

	<div class="form-group row">
	<div class="col-sm-2">
		<select name="med_plan_type" id="med_plan_type" class="form-control form-control-sm "  >
			<option value="1">ยากิน</option>
			<option value="2">ยาฉีดและอื่นๆ</option>
		</select>	
		</div>
	<div class="col-sm-auto"><button class="btn btn-primary btn-sm" style="width:100%" id="save" name="save">บันทึก</button></div>
  </div>
	
</div>

<?php if($totalRows_rs_med<>0){ ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table font14 table-striped table-hover">
<thead>
  <tr>
    <th align="center" class="text-center"><input name="check_all" type="checkbox" id="check_all" checked="checked" />&nbsp;#</th>
    <th align="center" class="text-center">รายการยา/วิธีใช้</th>
    <th align="center" class="text-center">เหลือ</th>
    <th align="center" class="text-center">ที่มา</th>
    <th align="center" class="text-center">วันที่ได้</th>
    <th align="center" class="text-center">วันนัด</th>
    <th align="center" class="text-center">last dose</th>
    <th align="center" class="text-center">หมายเหตุ</th>
    <th align="center" class="text-center"><button class="btn btn-danger btn-sm font12" onclick=" if(confirm('ต้องการลบข้อมูลทั้งหมดจริงหรือไม่')==true){drug_del('','all');}">ลบทั้งหมด</button></th>
  </tr>
</thead>
<tbody>
  <?php $i=0; do { $i++; ?>
<?php 
    
?>
  <tr>
    <td align="center"><input name="icode[]" type="checkbox" class="icode" id="icode[]" value="<?php echo $row_rs_med['hos_guid']; ?>" checked="checked" />&nbsp;<?php echo $i; ?></td>
    <td align="left" id="<?php echo $row_rs_med['hos_guid']; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		<div>
		<?php echo "<strong>".$row_rs_med['drug_name']."</strong>"; if($row_rs_med['qty']!=""){ echo "&ensp;#&nbsp;<span class='text-danger'><strong>".$row_rs_med['qty']."</strong></span>"; } if($row_rs_med['med_plan_type']=="1"){ echo " <span class='badge badge-success p-2'>ยากิน</span>"; } else if($row_rs_med['med_plan_type']=="2"){ echo " <span class='badge badge-success p-2'>ยาฉีดและอื่นๆ</span>"; } ?>
		</div>
		<div class="pl-3 font12">
		<?php echo $row_rs_med['drugusage']; ?>
		</div>
        <div class="dropdown-menu" style="font-size:14px; margin-top: -5px;" aria-labelledby="<?php echo $row_rs_med['hos_guid']; ?>">
            <a class="dropdown-item" href="" onClick="alertload_error('med_reconcile_error.php?id=<?php echo $row_rs_med['id']; ?>','80%','80%');"><i class="fas fa-edit"></i>&nbsp;บันทึกความคลาดเคลื่อนทางยา</a>
          </div>
	</td>
    <td align="center"><?php echo $row_rs_med['remain']; ?></td>
    <td align="center"><?php echo $row_rs_med['src_hospcode']; ?></td>
    <td align="center"><?php echo dateThai3($row_rs_med['vstdate']); ?></td>
    <td align="center"><?php echo dateThai3($row_rs_med['appdate']); ?></td>
    <td align="center"><?php echo $row_rs_med['last_dose']; ?></td>
    <td align="center"><?php if($row_rs_med['error_count']<>0){?><div><button class="btn btn-success p-2" onClick="alertload_error('med_reconcile_error_detail.php?med_reconcile_id=<?php echo $row_rs_med['id']; ?>','90%','90%')" >error&nbsp;<span class="badge badge-light"><?php echo $row_rs_med['error_count']; ?></span></button><?php } ?></div>
        <?php echo $row_rs_med['remark']; ?><?php if($row_rs_med['icode']==""){ echo "<i class=\"far fa-keyboard font16\"></i>"; } ?></td>
    <td align="center"><i class="fas fa-user-edit font20 cursor" onClick="edit_record('<?php echo $row_rs_med['id']; ?>','<?php echo $row_rs_med['drug_name']; ?>','<?php echo $row_rs_med['drugusage']; ?>','<?php echo $row_rs_med['qty']; ?>','<?php echo $row_rs_med['src_hospcode']; ?>','<?php if($row_rs_med['vstdate']!=""){ echo date_db2th($row_rs_med['vstdate']); } else { echo ""; } ?>','<?php if($row_rs_med['appdate']!=""){ echo date_db2th($row_rs_med['appdate']); } else { echo ""; } ?>','<?php echo $row_rs_med['remain']; ?>','<?php echo $row_rs_med['last_dose']; ?>','<?php echo $row_rs_med['remark']; ?>','<?php echo $row_rs_med['med_plan_type']; ?>','<?php echo $row_rs_med['icode']; ?>');"></i>&ensp;<i class="fas fa-eraser font20 cursor" onclick=" if(confirm('ต้องการลบข้อมูลรายการนี้จริงหรือไม่')==true){drug_del('<?php echo $row_rs_med['id']; ?>','single');}"></i></td>  </tr>
  <?php } while ($row_rs_med = mysql_fetch_assoc($rs_med)); ?>
  </tbody>
</table>
<div style="padding:10px; border-top:1px #DBDBDB solid; margin-top:-17px;">
<button class="btn btn-success" onClick="window.open('med_reconcile_print.php?hn=<?php echo $_GET['hn']; ?>&vstdate=<?php echo date_th2db($_GET['vstdate']); ?>','_new');"><i class="fas fa-file-prescription font20" ></i>&ensp;พิมพ์</button><?php if($an!=""){ ?>&nbsp;<button class="btn btn-warning" id="medplan" ><i class="fas fa-paper-plane font20"></i>&ensp;Med. Plan</button> <div style="position: absolute; margin-top:-30px; margin-left: 220px;" id="medplan_save" ></div>
        <!--indicator-->
         <div id="indicator" align="center" class="spinner" style="position: absolute; margin-top:-30px; margin-left: 220px;">
         <button class="btn btn-secondary" type="button" style="" disabled>
  <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
</button>
         </div>
         <!--indicator-->	
	<?php } ?>
</div>
<?php }  
else { 
	echo "<div style=\"padding:10px\"><i class=\"far fa-times-circle font20\"></i>&ensp;ยังไม่มีข้อมูล</div>"; } 	
?>


</body>
</html>
<?php
mysql_free_result($rs_med);
?>
