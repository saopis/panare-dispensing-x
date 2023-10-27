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
include('include/function.php');

if(isset($_GET['vn'])&&($_GET['vn']!="")){
	$vn=$_GET['vn'];
	}
else if(isset($_POST['vn'])&&($_POST['vn']!="")){
	$vn=$_POST['vn'];
	}
	
if(isset($_POST['intervention1'])&&($_POST['intervention1']!="")){
	$intervention1="'".$_POST['intervention1']."'";
	$rintervention1="\'".$_POST['intervention1']."\'";
	}
else{
	$intervention1="NULL";
	}
if(isset($_POST['intervention2'])&&($_POST['intervention2']!="")){
	$intervention2="'".$_POST['intervention2']."'";
	$rintervention2="\'".$_POST['intervention2']."\'";
	}
else{
	$intervention2="NULL";
	}
if(isset($_POST['intervention3'])&&($_POST['intervention3']!="")){
	$intervention3="'".$_POST['intervention3']."'";
	$rintervention3="\'".$_POST['intervention3']."\'";
	}
else{
	$intervention3="NULL";
	}
if(isset($_POST['outcome'])&&($_POST['outcome']!="")){
	$outcoe="'".$_POST['outcome']."'";
	$routcome="\'".$_POST['outcome']."\'";
	
	}
else{
	$outcome="NULL";
	}

if(isset($_POST['follow_up'])&&($_POST['follow_up']!="")){
	$follow_up=$_POST['follow_up'];
	}

if(isset($_GET['icode'])&&($_GET['icode']!="")){
	//ค้นหาชื่อยา
	mysql_select_db($database_hos, $hos);
	$query_drug_search = "select concat(name,' ',strength) as drug_name from drugitems where icode='".$_GET['icode']."'";
	$drug_search = mysql_query($query_drug_search, $hos) or die(mysql_error());
	$row_drug_search = mysql_fetch_assoc($drug_search);
	$totalRows_drug_search = mysql_num_rows($drug_search);
	
	$drug_name=$row_drug_search['drug_name'];
		
	mysql_free_result($drug_search);
}

if(isset($_GET['action'])&&($_GET['action']=="delete")){
	mysql_select_db($database_hos, $hos);
	$query_delete = "delete from drp_problem_list where drp_problem_list_id='".$_GET['id']."' ";
	$delete = mysql_query($query_delete, $hos) or die(mysql_error());

	//delete replicate_log
	mysql_select_db($database_hos, $hos);
	$query_ptdepart = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','delete from drp_problem_list where drp_problem_list_id=\'".$_GET['id']."\'')";
	$ptdepart = mysql_query($query_ptdepart, $hos) or die(mysql_error());	

	echo "<script>window.location='drp2.php?hn=".$_GET['hn']."&action=showall';</script>";
	exit();
}
	
if(isset($_POST['save'])&&($_POST['save']=="แก้ไข")){

	//ค้นหา drp cause
	mysql_select_db($database_hos, $hos);
	$query_drp_cause_search = "select drp_cause_id from drp_cause where std_code='".$_POST['drp_code']."'";
	$drp_cause_search = mysql_query($query_drp_cause_search, $hos) or die(mysql_error());
	$row_drp_cause_search = mysql_fetch_assoc($drp_cause_search);
	$totalRows_drp_cause_search = mysql_num_rows($drp_cause_search);
	
	$drp_cause_id=$row_drp_cause_search['drp_cause_id'];
		
	mysql_free_result($drp_cause_search);

/////////////////////////////
	if($totalRows_drp_cause_search==0){
		$msg="รหัสปัญหานี้ไม่ถูกต้องหรือไม่มีในระบบ";	
		}
	else{

	if($_POST['follow_up']=='Y'){
		$follow_up='Y';
		}
	else{
		$follow_up='N';
		}

	//update drp_problem_list
	mysql_select_db($database_hos, $hos);
	$query_update = "update drp_problem_list set drp_cause_id='".$drp_cause_id."',drp_intervention_type_id_1=".$intervention1.",drp_intervention_type_id_2=".$intervention2.",drp_intervention_type_id_3=".$intervention3.",intervention_note='".$_POST['remark']."',drp_datetime='".date_th2db($_POST['date1'])." ".$_POST['drp_time']."',need_follow_up='".$follow_up."',drp_outcome_type_id=".$outcome.",staff='".$_SESSION["username_log"]."',icode='".$_POST['icode']."' where drp_problem_list_id='".$_POST['id']."' ";
	$update = mysql_query($query_update, $hos) or die(mysql_error());
	
	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$query_ptdepart = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update drp_problem_list set drp_cause_id=\'".$drp_cause_id."\',drp_intervention_type_id_1=".$rintervention1.",drp_intervention_type_id_2=".$rintervention2.",drp_intervention_type_id_3=".$rintervention3.",intervention_note=\'".$_POST['remark']."\',drp_datetime=\'".date_th2db($_POST['date1'])." ".$_POST['drp_time']."\',need_follow_up=\'".$follow_up."\',drp_outcome_type_id=".$outcome.",staff=\'".$_SESSION["username_log"]."\',icode=\'".$_POST['icode']."\' where drp_problem_list_id=\'".$_POST['id']."\'')";
	$ptdepart = mysql_query($query_ptdepart, $hos) or die(mysql_error());	

if($_POST['action']<>"editshowall"){
echo "<script>parent.$.fn.colorbox.close();parent.drp_load2('".$_POST['hn']."');</script>";
exit();
}
else{
	echo "<script>window.location='drp2.php?hn=".$_POST['hn']."&action=showall';</script>";
	exit();
}
	}
}

if(isset($_POST['save'])&&($_POST['save']=="บันทึก")){
	mysql_select_db($database_hos, $hos);
$query_rs_regis_search = "select drp_regist_id,hn,vn from drp_regist where vn='".$vn."'";
$rs_regis_search = mysql_query($query_rs_regis_search, $hos) or die(mysql_error());
$row_rs_regis_search = mysql_fetch_assoc($rs_regis_search);
$totalRows_rs_regis_search = mysql_num_rows($rs_regis_search);

	//ค้นหา drp cause
	mysql_select_db($database_hos, $hos);
	$query_drp_cause_search = "select drp_cause_id from drp_cause where std_code='".$_POST['drp_code']."'";
	$drp_cause_search = mysql_query($query_drp_cause_search, $hos) or die(mysql_error());
	$row_drp_cause_search = mysql_fetch_assoc($drp_cause_search);
	$totalRows_drp_cause_search = mysql_num_rows($drp_cause_search);
	
	$drp_cause_id=$row_drp_cause_search['drp_cause_id'];
		
	mysql_free_result($drp_cause_search);

/////////////////////////////
	if($totalRows_drp_cause_search==0){
		$msg="รหัสปัญหานี้ไม่ถูกต้องหรือไม่มีในระบบ";	
		}
	else{
					
	if($totalRows_rs_regis_search==0){
	mysql_select_db($database_hos, $hos);
	$query_lock = "LOCK TABLES serial WRITE";
	$lock = mysql_query($query_lock, $hos) or die(mysql_error());
	
	mysql_select_db($database_hos, $hos);
	$query_update = "update serial set serial_no=serial_no+1 where name='drp_regist_id'
";
	$update = mysql_query($query_update, $hos) or die(mysql_error());


	mysql_select_db($database_hos, $hos);
	$query_rs_serial2 = "select serial_no from serial where name='drp_regist_id'";
	$rs_serial2 = mysql_query($query_rs_serial2, $hos) or die(mysql_error());
	$row_rs_serial2 = mysql_fetch_assoc($rs_serial2);
	$totalRows_rs_serial2 = mysql_num_rows($rs_serial2);	
	
	$serial_no=$row_rs_serial2['serial_no'];

	mysql_free_result($rs_serial2);

	mysql_select_db($database_hos, $hos);
	$query_lock = "UNLOCK TABLES";
	$lock = mysql_query($query_lock, $hos) or die(mysql_error());
	
	//insert regist id
	mysql_select_db($database_hos, $hos);
	$query_insert = "insert into drp_regist (drp_regist_id,hn,vn) value ('".$serial_no."','".$_POST['hn']."','".$vn."')";
	$insert = mysql_query($query_insert, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$query_ptdepart = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update serial set serial_no=serial_no+1 where name=\'drp_problem_list_id\'')";
	$ptdepart = mysql_query($query_ptdepart, $hos) or die(mysql_error());	

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$query_ptdepart = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into drp_regist_id (drp_regist_id,hn,vn) value (\'".$serial_no."\',\'".$_POST['hn']."\',\'".$vn."\')')";
	$ptdepart = mysql_query($query_ptdepart, $hos) or die(mysql_error());	
		
	}
	else{
	$serial_no=$row_rs_regis_search['drp_regist_id'];
	}
		
	mysql_select_db($database_hos, $hos);
	$query_rs_serial1 = "select count(*) as cc from serial where name='drp_problem_list_id'";
	$rs_serial1 = mysql_query($query_rs_serial1, $hos) or die(mysql_error());
	$row_rs_serial1 = mysql_fetch_assoc($rs_serial1);
	$totalRows_rs_serial1 = mysql_num_rows($rs_serial1);	

	if($row_rs_serial1['cc']==1){
	mysql_select_db($database_hos, $hos);
	$query_lock = "LOCK TABLES serial WRITE";
	$lock = mysql_query($query_lock, $hos) or die(mysql_error());
	
	mysql_select_db($database_hos, $hos);
	$query_update = "update serial set serial_no=serial_no+1 where name='drp_problem_list_id'
";
	$update = mysql_query($query_update, $hos) or die(mysql_error());


	mysql_select_db($database_hos, $hos);
	$query_rs_serial2 = "select serial_no from serial where name='drp_problem_list_id'";
	$rs_serial2 = mysql_query($query_rs_serial2, $hos) or die(mysql_error());
	$row_rs_serial2 = mysql_fetch_assoc($rs_serial2);
	$totalRows_rs_serial2 = mysql_num_rows($rs_serial2);	
	
	$serial_no2=$row_rs_serial2['serial_no'];

	mysql_free_result($rs_serial2);

	mysql_select_db($database_hos, $hos);
	$query_lock = "UNLOCK TABLES";
	$lock = mysql_query($query_lock, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$query_ptdepart = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update serial set serial_no=serial_no+1 where name=\'drp_problem_list_id\'')";
	$ptdepart = mysql_query($query_ptdepart, $hos) or die(mysql_error());	

	}
	
	mysql_free_result($rs_serial1);
	
	mysql_free_result($rs_regis_search);

	if($_POST['follow_up']=='Y'){
		$follow_up='Y';
		}
	else{
		$follow_up='N';
		}
	//insert drp_problem_list
	mysql_select_db($database_hos, $hos);
	$query_insert = "insert into drp_problem_list (drp_problem_list_id,drp_regist_id,drp_problem_id,drp_cause_id,drp_intervention_type_id_1,drp_intervention_type_id_2,drp_intervention_type_id_3,intervention_note,drp_datetime,need_follow_up,drp_outcome_type_id,staff,icode) value ('".$serial_no2."','".$serial_no."','0','".$drp_cause_id."',".$intervention1.",".$intervention2.",".$intervention3.",'".$_POST['remark']."','".date_th2db($_POST['date1'])." ".$_POST['drp_time']."','".$follow_up."',".$outcome.",'".$_SESSION["username_log"]."','".$_POST['icode']."') ";
	$insert = mysql_query($query_insert, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$query_ptdepart = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into drp_problem_list (drp_problem_list_id,drp_regist_id,drp_problem_id,drp_cause_id,drp_intervention_type_id_1,drp_intervention_type_id_2,drp_intervention_type_id_3,intervention_note,drp_datetime,need_follow_up,drp_outcome_type_id,staff,icode) value (\'".$serial_no2."\',\'".$serial_no."\',\'0\',\'".$drp_cause_id."\',".$rintervention1.",".$rintervention2.",".$rintervention3.",\'".$_POST['remark']."\',\'".date_th2db($_POST['date1'])." ".$_POST['drp_time']."\',\'".$follow_up."\',".$routcome.",\'".$_SESSION["username_log"]."\',\'".$_POST['icode']."\')')";
	$ptdepart = mysql_query($query_ptdepart, $hos) or die(mysql_error());	

echo "<script>parent.$.fn.colorbox.close();parent.drp_load2('".$_POST['hn']."');</script>";
exit();

	}

	
}

 if(!isset($_GET['action'])||($_GET['action']!="showall")){ 
mysql_select_db($database_hos, $hos);
$query_rs_visit = "select vstdate from vn_stat where vn ='".$_GET['vn']."'";
$rs_visit = mysql_query($query_rs_visit, $hos) or die(mysql_error());
$row_rs_visit = mysql_fetch_assoc($rs_visit);
$totalRows_rs_visit = mysql_num_rows($rs_visit);


mysql_select_db($database_hos, $hos);
$query_rs_drp_intervention = "select * from drp_intervention_type";
$rs_drp_intervention = mysql_query($query_rs_drp_intervention, $hos) or die(mysql_error());
$row_rs_drp_intervention = mysql_fetch_assoc($rs_drp_intervention);
$totalRows_rs_drp_intervention = mysql_num_rows($rs_drp_intervention);

mysql_select_db($database_hos, $hos);
$query_rs_outcome = "select * from drp_outcome_type order by drp_outcome_type_id DESC";
$rs_outcome = mysql_query($query_rs_outcome, $hos) or die(mysql_error());
$row_rs_outcome = mysql_fetch_assoc($rs_outcome);
$totalRows_rs_outcome = mysql_num_rows($rs_outcome);
}

if(isset($_GET['id'])&&($_GET['id']!="")){
mysql_select_db($database_hos, $hos);
$query_rs_edit = "select l.drp_outcome_type_id,l.icode,l.drp_intervention_type_id_1,l.drp_intervention_type_id_2,l.drp_intervention_type_id_3,l.drp_problem_list_id,l.drp_datetime,c.std_code,c.drp_cause_name,o.drp_outcome_type_name,i.drp_intervention_type_name as intervention1,i2.drp_intervention_type_name as intervention2,i3.drp_intervention_type_name as intervention3,l.intervention_note,l.need_follow_up,l.staff,concat(s.name,s.strength) as drugname,r.hn,r.vn from drp_problem_list l  left outer join drp_regist r on r.drp_regist_id=l.drp_regist_id left outer join drp_cause c on c.drp_cause_id=l.drp_cause_id left outer join drp_outcome_type o on o.drp_outcome_type_id=l.drp_outcome_type_id left outer join drp_intervention_type i on i.drp_intervention_type_id=l.drp_intervention_type_id_1 left outer join drp_intervention_type i2 on i2.drp_intervention_type_id=l.drp_intervention_type_id_2 left outer join drp_intervention_type i3 on i3.drp_intervention_type_id=l.drp_intervention_type_id_3 left outer join s_drugitems s on s.icode=l.icode where drp_problem_list_id='".$_GET['id']."'";
$rs_edit = mysql_query($query_rs_edit, $hos) or die(mysql_error());
$row_rs_edit = mysql_fetch_assoc($rs_edit);
$totalRows_rs_edit = mysql_num_rows($rs_edit);

$intervention1=$row_rs_edit['drp_intervention_type_id_1'];
$intervention2=$row_rs_edit['drp_intervention_type_id_2'];
$intervention3=$row_rs_edit['drp_intervention_type_id_3'];
$outcome=$row_rs_edit['drp_outcome_type_id'];
$follow_up=$row_rs_edit['need_follow_up'];
}

if(isset($_GET['action'])&&($_GET['action']=="showall")){
//ค้นหา DRP
mysql_select_db($database_hos, $hos);
$query_rs_problem_list = "select l.drp_problem_list_id,l.drp_datetime,c.std_code,c.drp_cause_name,o.drp_outcome_type_name,i.drp_intervention_type_name as intervention1,i2.drp_intervention_type_name as intervention2,i3.drp_intervention_type_name as intervention3,l.intervention_note,l.need_follow_up,l.staff,concat(s.name,s.strength) as drugname,r.hn,r.vn from drp_problem_list l  left outer join drp_regist r on r.drp_regist_id=l.drp_regist_id left outer join drp_cause c on c.drp_cause_id=l.drp_cause_id left outer join drp_outcome_type o on o.drp_outcome_type_id=l.drp_outcome_type_id left outer join drp_intervention_type i on i.drp_intervention_type_id=l.drp_intervention_type_id_1 left outer join drp_intervention_type i2 on i2.drp_intervention_type_id=l.drp_intervention_type_id_2 left outer join drp_intervention_type i3 on i3.drp_intervention_type_id=l.drp_intervention_type_id_3 left outer join s_drugitems s on s.icode=l.icode where hn='".$_GET['hn']."' order by l.drp_datetime DESC";
$rs_problem_list = mysql_query($query_rs_problem_list, $hos) or die(mysql_error());
$row_rs_problem_list = mysql_fetch_assoc($rs_problem_list);
$totalRows_rs_problem_list = mysql_num_rows($rs_problem_list);

}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php include('java_css_file.php'); ?>
<script>
$(document).ready(function() {

$("#drp_time").inputmask({
mask: '99:99',
placeholder: ' ',
showMaskOnHover: false,
showMaskOnFocus: false,
onBeforePaste: function (pastedValue, opts) {
var processedValue = pastedValue;

//do something with it

return processedValue;
}
});		
<?php if($totalRows_rs_edit==0){ ?>	
//เวลาปัจจุบัน
const timenow = Date().slice(16,21);
$('#drp_time').val(timenow);
<?php } ?>

	var vn='';

	$( "input#vstdate" ).change(function() {
		if ($('input#vstdate').prop('checked')){
		//blah blah
		var vn='<?php echo $_GET['vn']; ?>';
		alert(vn);
		}
		else {
		 var vn='';	
		alert(vn);
		}
	});


//auto complete รายการยา
        $( "#drp_drug" ).autocomplete({ // ใช้งาน autocomplete กับ input text id=tags
		
			minLength: 0, // กำหนดค่าสำหรับค้นหาอย่างน้อยเป็น 0 สำหรับใช้กับปุ่ใแสดงทั้งหมด
            source: "drp_search.php?type=drug&vn=<?php echo $_GET['vn']; ?>", // กำหนดให้ใช้ค่าจากการค้นหาในฐานข้อมูล
            open:function(){
				 // เมื่อมีการแสดงรายการ autocomplete
                var valInput=$(this).val(); // ดึงค่าจาก text box id=tags มาเก็บที่ตัวแปร
                if(valInput!=""){ // ถ้าไม่ใช่ค่าว่าง
                    $(".ui-menu-item a").each(function(){ // วนลูปเรียกดูค่าทั้งหมดใน รายการ autocomplete
                        var matcher = new RegExp("("+valInput+")", "ig" ); // ตรวจสอบค่าที่ตรงกันในแต่ละรายการ กับคำค้นหา
                        var s=$(this).text();
                        var newText=s.replace(matcher, "<b>$1</b>");    //      แทนค่าที่ตรงกันเป็นตัวหนา
                        $(this).html(newText); // แสดงรายการ autocomplete หลังจากปรับรูปแบบแล้ว
                    }); 
                }
            },
            select: function( event, ui ) {
                $("#icode").val(ui.item.id);
            }
        });

//problem
//auto complete รายการยา
        $( "#problem" ).autocomplete({ // ใช้งาน autocomplete กับ input text id=tags
		
			minLength: 0, // กำหนดค่าสำหรับค้นหาอย่างน้อยเป็น 0 สำหรับใช้กับปุ่ใแสดงทั้งหมด
            source: "drp_search.php?type=problem", // กำหนดให้ใช้ค่าจากการค้นหาในฐานข้อมูล
            open:function(){
				 // เมื่อมีการแสดงรายการ autocomplete
                var valInput=$(this).val(); // ดึงค่าจาก text box id=tags มาเก็บที่ตัวแปร
                if(valInput!=""){ // ถ้าไม่ใช่ค่าว่าง
                    $(".ui-menu-item a").each(function(){ // วนลูปเรียกดูค่าทั้งหมดใน รายการ autocomplete
                        var matcher = new RegExp("("+valInput+")", "ig" ); // ตรวจสอบค่าที่ตรงกันในแต่ละรายการ กับคำค้นหา
                        var s=$(this).text();
                        var newText=s.replace(matcher, "<b>$1</b>");    //      แทนค่าที่ตรงกันเป็นตัวหนา
                        $(this).html(newText); // แสดงรายการ autocomplete หลังจากปรับรูปแบบแล้ว
                    }); 
                }
            },
            select: function( event, ui ) {
                $("#drp_code").val(ui.item.id);
            }
        });

//code
//problem
//auto complete รายการยา
        $( "#drp_code" ).autocomplete({ // ใช้งาน autocomplete กับ input text id=tags
		
			minLength: 0, // กำหนดค่าสำหรับค้นหาอย่างน้อยเป็น 0 สำหรับใช้กับปุ่ใแสดงทั้งหมด
            source: "drp_search.php?type=code", // กำหนดให้ใช้ค่าจากการค้นหาในฐานข้อมูล
            open:function(){
				 // เมื่อมีการแสดงรายการ autocomplete
                var valInput=$(this).val(); // ดึงค่าจาก text box id=tags มาเก็บที่ตัวแปร
                if(valInput!=""){ // ถ้าไม่ใช่ค่าว่าง
                    $(".ui-menu-item a").each(function(){ // วนลูปเรียกดูค่าทั้งหมดใน รายการ autocomplete
                        var matcher = new RegExp("("+valInput+")", "ig" ); // ตรวจสอบค่าที่ตรงกันในแต่ละรายการ กับคำค้นหา
                        var s=$(this).text();
                        var newText=s.replace(matcher, "<b>$1</b>");    //      แทนค่าที่ตรงกันเป็นตัวหนา
                        $(this).html(newText); // แสดงรายการ autocomplete หลังจากปรับรูปแบบแล้ว
                    }); 
                }
            },
            select: function( event, ui ) {
                $("#problem").val(ui.item.id);
            }
        });
///////////////////////////

$("#drp_code").keyup(function()
{
var id=$(this).val();
var dataString = 'id='+ id;
$.ajax
({
type: "POST",
url: "drp_search.php",
data: dataString,
cache: false,
success: function(html)
{
$("#problem").val(html);
} 
});

});
/////////////////////////

});



</script>
<script type="text/javascript">   
$(function(){
     
    $.datetimepicker.setLocale('th'); // ต้องกำหนดเสมอถ้าใช้ภาษาไทย และ เป็นปี พ.ศ.

    $("#calendar").datetimepicker({
        timepicker:false,
        format:'d/m/Y',  // กำหนดรูปแบบวันที่ ที่ใช้ เป็น 00-00-0000            
        lang:'th',  // ต้องกำหนดเสมอถ้าใช้ภาษาไทย และ เป็นปี พ.ศ.
        onSelectDate:function(dp,$input){
            var yearT=new Date(dp).getFullYear();  
            var yearTH=yearT+543;
            var fulldate=$input.val();
            var fulldateTH=fulldate.replace(yearT,yearTH);
            $('#date1').val(fulldateTH);
        },
    });       

     
});
</script>
<style>
.ui-autocomplete {
	position:absolute;
		margin-top:50px;
		margin-left:10px;
		padding-right:5px;
        max-height:300px !important;
        overflow: auto !important;
    	font-family: th_saraban;
		src:url(font/thsarabunnew-webfont.woff);

}
input.upper { text-transform: uppercase; }

html,body{overflow:hidden; }
::-webkit-scrollbar {
    width: 15px;
}

::-webkit-scrollbar-track {
    box-shadow: inset 0 0 10px 10px #E6E6E6;
    border: solid 3px transparent;
}

::-webkit-scrollbar-thumb {
    box-shadow: inset 0 0 10px 10px #CCCCCC;
    border: solid 3px transparent;
}
input[type=search]::-webkit-search-cancel-button {
    -webkit-appearance: searchfield-cancel-button;

}

</style>

</head>

<body>
<?php if($msg!=""){?>
<div class="alert alert-danger" role="alert" style="margin:10px;margin-top:50px;">
  <?php echo $msg; ?>
</div>
<?php } ?>

<nav class="navbar navbar-dark bg-info text-white fixed-top">
  <!-- Navbar content -->
    <span class="font18"><i class="fas fa-user-times font20"></i>&ensp;บันทึกปัญหาจากการใช้ยา (DRP)</span>
</nav>
<?php if(!isset($_GET['action'])||($_GET['action']!="showall")){ ?>
<div class="p-3" style=" <?php if($msg==""){?>margin-top: 40px;<?php } ?>">
<form class="needs-validation" novalidate method="post" action="drp2.php">
<input name="vn" type="hidden" id="vn" value="<?php if(isset($_GET['vn'])&&($_GET['vn']!="")){echo $_GET['vn']; } else if(isset($_POST['vn'])&&($_POST['vn']!="")){echo $_POST['vn']; }?>" />
<input name="hn" type="hidden" id="hn" value="<?php if(isset($_GET['hn'])&&($_GET['hn']!="")){echo $_GET['hn']; } else if(isset($_POST['hn'])&&($_POST['hn']!="")){echo $_POST['hn']; }?>" />
<input name="id" type="hidden" id="id" value="<?php if(isset($_GET['id'])&&($_GET['id']!="")){echo $_GET['id']; } else if(isset($_POST['id'])&&($_POST['id']!="")){echo $_POST['id']; }?>" />
<input name="action" type="hidden" id="action" value="<?php if(isset($_GET['action'])&&($_GET['action']!="")){echo $_GET['action']; } else if(isset($_POST['action'])&&($_POST['action']!="")){echo $_POST['action']; }?>" />
  <div class="form-row">
    <div class="col-md-2 mb-3">
      <label for="date1">วันที่&ensp;<i class="btn btn-secondary fas fa-calendar-alt" style="cursor: pointer; padding: 5px;" id="calendar" data-toggle="tooltip" data-placement="bottom" title="คลิ๊กเลือกวันที่"></i></label>
      <input type="text" class="form-control" id="date1" name="date1" placeholder="วันที่" readonly="readonly"  value="<?php if($totalRows_rs_edit<>0){ echo date_db2th(substr($row_rs_edit['drp_datetime'],0,10)); } else{if(isset($_POST['date1'])&&($_POST['date1']!="")){ echo $_POST['date1'];} else {echo date('d/m/').(date('Y')+543); }} ?>" required>
      <div class="valid-feedback">
        ถูกต้อง!
      </div>
    </div>
    <div class="col-md-2 mb-3">
      <label for="drp_time">เวลา</label>
      <input type="text" class="form-control" id="drp_time" name="drp_time" data-inputmask="'alias': 'drp_time'" value="<?php if($totalRows_rs_edit<>0){ echo substr($row_rs_edit['drp_datetime'],11,5); } else{if(!isset($_POST['drp_time'])){ echo date('H:m'); } else {$_POST['drp_time']; }} ?>" required>
      <div class="valid-feedback">
        ถูกต้อง!
      </div>
    </div>
    <div class="col-md-8 mb-3">
      <label for="drp_drug">รายการยา</label>
      <input type="hidden" name="icode" id="icode" value="<?php if($totalRows_rs_edit<>0){ echo $row_rs_edit['icode']; } else {if(isset($_POST['icode'])&&($_POST['icode']!="")){ echo $_POST['icode']; }  else if($_GET['icode']!=""){ echo $_GET['icode']; } } ?>" />
      <input type="search" class="form-control" id="drp_drug" name="drp_drug" placeholder="รายการยา" value="<?php if($totalRows_rs_edit<>0){ echo $row_rs_edit['drugname']; } else {if(isset($_POST['drp_drug'])&&($_POST['drp_drug']!="")){ echo $_POST['drp_drug']; } else if($drug_name!=""){ echo $drug_name; }} ?>" required>
      <div class="valid-feedback">
        ถูกต้อง!
      </div>
    </div>
</div>
  <div class="form-row">
    <div class="col-md-2 mb-3">
      <label for="drp_code">รหัสปัญหา</label>
      <input type="search" class="form-control upper" id="drp_code" name="drp_code" placeholder="code" value="<?php if($totalRows_rs_edit<>0){ echo $row_rs_edit['std_code']; } else {if(isset($_POST['drp_code'])&&($_POST['drp_code']!="")){ echo $_POST['drp_code']; }} ?>" required />
      <div class="invalid-feedback">
        กรุณากรอกรหัสปัญหา
      </div>
    </div>
    <div class="col-md-10 mb-3">
      <label for="problem">ปัญหา</label>
      <input type="search" class="form-control" id="problem" placeholder="ปัญหา" value="<?php if($totalRows_rs_edit<>0){ echo $row_rs_edit['drp_cause_name']; } else {if(isset($_POST['problem'])&&($_POST['problem']!="")){ echo $_POST['problem']; }} ?>" required>
      <div class="invalid-feedback">
        กรุณาเลือกปัญหา
      </div>
    </div>
  </div>
<div class="form-row">
    <div class="col-md-4 mb-3">
      <label for="intervention1">การแทรกแซง1</label>
      <select name="intervention1" id="intervention1" class="form-control">
        <option value="" <?php if (!(strcmp("", $intervention1))) {echo "selected=\"selected\"";} ?>>-</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rs_drp_intervention['drp_intervention_type_id']?>"<?php if (!(strcmp($row_rs_drp_intervention['drp_intervention_type_id'], $intervention1))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_drp_intervention['drp_intervention_type_name']?></option>
        <?php
} while ($row_rs_drp_intervention = mysql_fetch_assoc($rs_drp_intervention));
  $rows = mysql_num_rows($rs_drp_intervention);
  if($rows > 0) {
      mysql_data_seek($rs_drp_intervention, 0);
	  $row_rs_drp_intervention = mysql_fetch_assoc($rs_drp_intervention);
  }
?>
      </select>
    </div>
    <div class="col-md-4 mb-3">
      <label for="intervention2">การแทรกแซง2</label>
      <select name="intervention2" id="intervention2" class="form-control">
        <option value="" <?php if (!(strcmp("", $intervention2))) {echo "selected=\"selected\"";} ?>>-</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rs_drp_intervention['drp_intervention_type_id']?>"<?php if (!(strcmp($row_rs_drp_intervention['drp_intervention_type_id'], $intervention2))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_drp_intervention['drp_intervention_type_name']?></option>
        <?php
} while ($row_rs_drp_intervention = mysql_fetch_assoc($rs_drp_intervention));
  $rows = mysql_num_rows($rs_drp_intervention);
  if($rows > 0) {
      mysql_data_seek($rs_drp_intervention, 0);
	  $row_rs_drp_intervention = mysql_fetch_assoc($rs_drp_intervention);
  }
?>
      </select>
    </div>
    <div class="col-md-4 mb-3">
      <label for="validationCustom01">การแทรกแซง3</label>
      <select name="intervention3" id="intervention3" class="form-control">
        <option value="" <?php if (!(strcmp("", $intervention3))) {echo "selected=\"selected\"";} ?>>-</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rs_drp_intervention['drp_intervention_type_id']?>"<?php if (!(strcmp($row_rs_drp_intervention['drp_intervention_type_id'], $intervention3))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_drp_intervention['drp_intervention_type_name']?></option>
        <?php
} while ($row_rs_drp_intervention = mysql_fetch_assoc($rs_drp_intervention));
  $rows = mysql_num_rows($rs_drp_intervention);
  if($rows > 0) {
      mysql_data_seek($rs_drp_intervention, 0);
	  $row_rs_drp_intervention = mysql_fetch_assoc($rs_drp_intervention);
  }
?>
      </select>
    </div>

  </div>
<div class="form-row">
    <div class="col-md-4 mb-3">
      <label for="outcome">ผลที่ได้</label>
<select name="outcome" id="outcome" class="form-control">
  <?php
do {  
?>
  <option value="<?php echo $row_rs_outcome['drp_outcome_type_id']?>"<?php if (!(strcmp($row_rs_outcome['drp_outcome_type_id'], $outcome))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_outcome['drp_outcome_type_name']?></option>
  <?php
} while ($row_rs_outcome = mysql_fetch_assoc($rs_outcome));
  $rows = mysql_num_rows($rs_outcome);
  if($rows > 0) {
      mysql_data_seek($rs_outcome, 0);
	  $row_rs_outcome = mysql_fetch_assoc($rs_outcome);
  }
?>
</select>
    </div>
    <div class="col-md-6 mb-3">
      <label for="validationCustom02">หมายเหตุ</label>
      <input type="text" class="form-control" name="remark" id="remark" placeholder="หมายเหตุ" value="<?php if($totalRows_rs_edit<>0){ echo $row_rs_edit['intervention_note']; } else {if(isset($_POST['remark'])&&($_POST['remark']!="")){ echo $_POST['remark']; }} ?>">
  </div>
  <div class="form-group">
    <div class="form-check">
      <input name="follow_up" type="checkbox" class="form-check-input" id="follow_up" value="Y" <?php if($follow_up=='Y'){ echo "checked=\"checked\""; } ?> >
      <label class="form-check-label" for="follow_up">
        ติดตามผล&emsp;
      </label>
    </div>
  <input class="btn btn-primary" type="submit" id="save" name="save" value="<?php if(isset($_GET['id'])&&($_GET['id']!="")){ echo "แก้ไข"; } else { echo "บันทึก"; } ?>" style="margin-top:7px;"/>
  </div>
</form>

<script>
// Example starter JavaScript for disabling form submissions if there are invalid fields
(function() {
  'use strict';
  window.addEventListener('load', function() {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();
</script>
  </div>

<?php } 
else{
?>
<div style="margin-top:40px; padding:10px;">
  <table class="font12 table table-hover" style="width:100%">
    <thead>
      <tr>
        <th width="5%" valign="top">ลำดับ</th>
        <th width="13%" valign="top">วันที่ เวลา</th>
        <th width="5%" valign="top">รหัสปัญหา</th>
        <th width="15%" valign="top">รายละเอียดปัญหา</th>
        <th width="15%" valign="top">เวชภัณฑ์ที่เกี่ยวข้อง</th>
        <th width="7%" valign="top">แทรกแซง1</th>
        <th width="7%" valign="top">แทรกแทรง2</th>
        <th width="7%" valign="top">แทรกแทรง3</th>
        <th width="7%" valign="top">ผลลัพธ์</th>
        <th width="5%" valign="top">ติดตาม</th>
        <th width="10%" valign="top">หมายเหตุ</th>
        <th width="2%" valign="top">&nbsp;</th>
        <th width="2%" valign="top">&nbsp;</th>
      </tr>
    </thead>
  </table>
</div>
<div style=" margin-top:-28px; padding:10px; overflow:scroll;overflow-x:hidden;overflow-y:auto; height:490px;" align="center">
  <?php if($totalRows_rs_problem_list<>0){ ?>
  <table class="font12 table table-hover" style="width:100%; margin-top:-10px;">
    <tbody>
      <?php $i=0; do{ $i++; ?>
      <tr>
        <td width="5%" align="center"><?php echo $i; ?></td>
        <td width="13%" align="center"><?php echo date_db2th(substr($row_rs_problem_list['drp_datetime'],0,10))." ".substr($row_rs_problem_list['drp_datetime'],11,5); ?></td>
        <td width="5%" align="center"><?php print $row_rs_problem_list['std_code']; ?></td>
        <td width="15%" ><?php print $row_rs_problem_list['drp_cause_name'];  ?></td>
        <td width="15%" style="font-size:11px;"><?php print substr($row_rs_problem_list['drugname'],0,30); ?></td>
        <td width="7%" ><?php print $row_rs_problem_list['intervention1']; ?></td>
        <td width="7%"><?php print $row_rs_problem_list['intervention2']; ?></td>
        <td width="7%"><?php print $row_rs_problem_list['intervention3']; ?></td>
        <td width="7%" align="center"><?php print $row_rs_problem_list['drp_outcome_type_name']; ?></td>
        <td width="5%" align="center"><?php print $row_rs_problem_list['need_follow_up']; ?></td>
        <td width="10%"><?php echo $row_rs_problem_list['intervention_note']; ?></td>
        <td width="2%"><i class="fas fa-pen-square" style="color:#0066CC; font-size:20px; cursor:pointer" onclick="window.location='drp2.php?hn=<?php echo $row_rs_problem_list['hn']; ?>&vn=<?php echo $row_rs_problem_list['vn']; ?>&id=<?php echo $row_rs_problem_list['drp_problem_list_id']; ?>&action=editshowall';"></i></td>
        <td width="2%"><i class="fas fa-minus-square" onclick="if(confirm('ต้องการลบรายการนี้จริงหรือไม่?')==true){window.location='drp2.php?id=<?php echo $row_rs_problem_list['drp_problem_list_id']; ?>&hn=<?php echo $row_rs_problem_list['hn']; ?>&action=delete'; }" style="color: #F10101; font-size:20px; cursor:pointer"></i></td>
      </tr>
      <?php } while($row_rs_problem_list = mysql_fetch_assoc($rs_problem_list)); ?>
    </tbody>
  </table>
    <?php } ?>
</div>
<?php
	}
?>
</body>
</html>
<?php
if(!isset($_GET['action'])||($_GET['action']!="showall")){

mysql_free_result($rs_visit);

mysql_free_result($rs_drp_intervention);

mysql_free_result($rs_outcome);
}

if(isset($_GET['id'])&&($_GET['id']!='')){
	mysql_free_result($rs_edit);
}
?>
