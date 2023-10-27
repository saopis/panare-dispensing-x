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
mysql_select_db($database_hos, $hos);
$query_rs_setting = "select * from ".$database_kohrx.".kohrx_dispensing_setting order by id ASC";
$rs_setting = mysql_query($query_rs_setting, $hos) or die(mysql_error());
$row_rs_setting = mysql_fetch_assoc($rs_setting);
$totalRows_rs_setting = mysql_num_rows($rs_setting);

$i=0; do{ $i++;
$row_setting[$i]=$row_rs_setting['value'];} 
while($row_rs_setting = mysql_fetch_assoc($rs_setting));

mysql_select_db($database_hos, $hos);
$query_person = "SELECT o.name,o.doctorcode,o.hospital_department_id FROM opduser o left outer join doctor d on d.code=o.doctorcode WHERE o.hospital_department_id ='$row_setting[1]' and d.active='Y' order by name";
$person = mysql_query($query_person, $hos) or die(mysql_error());
$row_person = mysql_fetch_assoc($person);
$totalRows_person = mysql_num_rows($person);

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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Drug Related Problem Report</title>
<?php include('java_css_online.php'); ?> 
<!-- auto complete -->    
<script src="include/jquery/js/jquery-ui.js"></script>
<link rel="stylesheet" href="include/jquery/css/jquery-ui.css" type="text/css" />
<script type="text/javascript">
$(document).ready(function() {
	$('#print').hide();
	$('#down').hide();
    var Digital=new Date()
    var hours=Digital.getHours()
    var minutes=Digital.getMinutes()
	$('#time2').val(hours+':'+minutes);    
    
	$('#up').click(function(){
		$('#search-tool').hide();
		$('#up').hide();
		$('#down').show();
	});
	$('#down').click(function(){
		$('#search-tool').show();
		$('#up').show();
		$('#down').hide();
	});
//search
    
    $('#search').click(function(){

                        $('#indicator').show();
                        $("#displayDiv").load('dispen_drp_report_list.php?do=search&hn='+$('#hn').val()+'&datestart='+encodeURIComponent($('#datestart').val())+'&dateend='+encodeURIComponent($('#dateend').val())+'&icode='+$('#icode').val()+'&drp_code='+encodeURIComponent($('#drp_code').val())+'&intervention1='+encodeURIComponent($('#intervention1').val())+'&intervention2='+encodeURIComponent($('#intervention2').val())+'&intervention3='+encodeURIComponent($('#intervention3').val())+'&outcome='+encodeURIComponent($('#outcome').val())+'&remark='+encodeURIComponent($('#remark').val())+'&follow_up='+$('#follow_up').val(), function(responseTxt, statusTxt, xhr){
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();
						$('#print').show();
                    
					if(statusTxt == "error")
                      alert("Error: " + xhr.status + ": " + xhr.statusText);
                  });
    });


//auto complete รายการยา
	$('#drug').keyup(function(){
		if($('#drug').val()==""){
			$('#icode').val("");
		}
	});
        $( "#drug" ).autocomplete({ // ใช้งาน autocomplete กับ input text id=tags
			
			minLength: 0, // กำหนดค่าสำหรับค้นหาอย่างน้อยเป็น 0 สำหรับใช้กับปุ่ใแสดงทั้งหมด
            source: "drug_search.php", // กำหนดให้ใช้ค่าจากการค้นหาในฐานข้อมูล
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
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
</style>
<style>
@font-face {
    font-family: th_saraban;
    src: url(font/thsarabunnew-webfont.woff);
}
.thfont{
   font-family: th_saraban;
	}
</style>
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
.ui-autocomplete {
	position:absolute;
		margin-top:50px;
		margin-left:10px;
		padding-right:5px;
        max-height:200px !important;
        overflow: auto !important;
    	font-family: th_saraban;
		src:url(font/thsarabunnew-webfont.woff);
		font-size:14px;

}

</style>
</head>

<body>
<nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <div class="navbar-collapse collapse w-100 order-1 order-md-0 dual-collapse2">
        
    </div>
    <div class="mx-auto order-0">
        <a class="navbar-brand mx-auto" href="#"><i class="fas fa-chalkboard-teacher font20"></i>&ensp;ระบบรายงานบันทึกปัญหาจากการใช้ยา (DRP Report)</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".dual-collapse2">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
    <div class="navbar-collapse collapse w-100 order-3 dual-collapse2">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="fas fa-caret-square-up font20" id="up"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="fas fa-caret-square-down font20" id="down"></i></a>
            </li>
        </ul>
    </div>
</nav>
<div style="overflow:scroll;overflow-x:hidden;overflow-y:auto; height:90vh; padding: 10px;">
<div class="card m-2" id="search-tool">
    <div class="card-body">
        <div class="row">
            <label class="col-form-label col-form-label-sm col-sm-2">เลือกช่วงวันที่</label>
            <div class="col-sm-auto">
                <div id="reportrange" class="form-control" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                  <i class="far fa-calendar-alt"></i>&nbsp;
                    <span></span> 
                  <i class="fas fa-sort-down"></i>
                </div>	

            <input name="datestart" type="hidden" id="datestart" value="" /><input name="dateend" type="hidden" id="dateend" value="" />
                
            </div>
   			<label class="col-form-label col-form-label-sm col-sm-auto">HN</label>
            <div class="col-sm-auto">
            <input name="hn" type="text" class="form-control" id="hn" />
            </div>         
    </div> 
      <div class="row mt-2">
		  <label class="col-form-label col-form-label-sm col-sm-2">ยาที่เกี่ยวข้อง</label>
            <div class="col-sm-10">
            <input id="drug" type="text" style="padding-left: 5px;" class="form-control" />
			<input id="icode" type="hidden"/>	
            </div>            
      </div>
  <div class="form-row">
    <div class="col-md-2 mb-3">
      <label for="drp_code">รหัสปัญหา</label>
      <input type="search" class="form-control upper" id="drp_code" name="drp_code" placeholder="code"  required />
      <div class="invalid-feedback">
        กรุณากรอกรหัสปัญหา
      </div>
    </div>
    <div class="col-md-10 mb-3">
      <label for="problem">ปัญหา</label>
      <input type="search" class="form-control" id="problem" placeholder="ปัญหา"  required>
      <div class="invalid-feedback">
        กรุณาเลือกปัญหา
      </div>
    </div>
  </div>
<div class="form-row">
    <div class="col-md-4 mb-3">
      <label for="intervention1">การแทรกแซง1</label>
      <select name="intervention1" id="intervention1" class="form-control">
        <option value="" >-</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rs_drp_intervention['drp_intervention_type_id']?>"><?php echo $row_rs_drp_intervention['drp_intervention_type_name']?></option>
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
        <option value="" >-</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rs_drp_intervention['drp_intervention_type_id']?>"><?php echo $row_rs_drp_intervention['drp_intervention_type_name']?></option>
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
        <option value="" >-</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rs_drp_intervention['drp_intervention_type_id']?>"><?php echo $row_rs_drp_intervention['drp_intervention_type_name']?></option>
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
  <option value="<?php echo $row_rs_outcome['drp_outcome_type_id']?>"><?php echo $row_rs_outcome['drp_outcome_type_name']?></option>
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
    <div class="col-md-2 mb-3">
      <label for="follow_up">
        ติดตามผล&emsp;
      </label>
	<select id="follow_up" class="form-control">
		<option value="" >ทั้งหมด</option>
		<option value="Y"  >ติดตาม</option>
		<option value="N" >ไม่ติดตาม</option>
		</select>
	</div>

  </div>

                    
<div class="form-row">
              <div class="col-sm-auto" ><button class="btn btn-primary" id="search">ค้นหา</button></div>
</div>
</div>
</div>

<div id="indicator"  align="center" style="position:absolute; display:none; z-index:1000;padding:0px;"><img src="images/indicator.gif" hspace="10" align="absmiddle" /></div><div id="displayDiv" class="p-2">&nbsp;</div>
</div>
<?php include('include/datepicker/datepickerrang.php'); ?>

</script>
	</div>
</body>
</html>
<?php
mysql_free_result($person);

mysql_free_result($rs_setting);

mysql_free_result($rs_drp_intervention);

mysql_free_result($rs_outcome);

?>
