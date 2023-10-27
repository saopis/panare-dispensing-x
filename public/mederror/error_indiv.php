<?php require_once('../Connections/hos.php'); ?>
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
include('../include/function.php');

$get_ip=$_SERVER["REMOTE_ADDR"];
// check การ login
mysql_select_db($database_hos, $hos);
$query_delete = "delete from ".$database_kohrx.".kohrx_login_check where last_time < CURDATE()";
$delete = mysql_query($query_delete, $hos) or die(mysql_error());

mysql_select_db($database_hos, $hos);
$query_channel = "SELECT channel_name,cursor_position,r.room_name,r.id as room_id,q.queue_list,q.kskdepart from ".$database_kohrx.".kohrx_queue_caller_channel q left outer join ".$database_kohrx.".kohrx_queue_caller_channel_name n on n.id=q.channel left outer join ".$database_kohrx.".kohrx_queue_caller_room r on r.id=q.room_id WHERE ip='".$get_ip."'";
$channel = mysql_query($query_channel, $hos) or die(mysql_error());
$row_channel = mysql_fetch_assoc($channel);
$totalRows_channel = mysql_num_rows($channel);

mysql_select_db($database_hos, $hos);
$query_rs_room = "SELECT * from ".$database_kohrx.".kohrx_queue_caller_room";
$rs_room = mysql_query($query_rs_room, $hos) or die(mysql_error());
$row_rs_room = mysql_fetch_assoc($rs_room);
$totalRows_rs_room = mysql_num_rows($rs_room);

?>
<? $m=date('m');?>
<? if(isset($_GET['action'])&&($_GET['action']=="delete")){ 
mysql_select_db($database_hos, $hos);
$delete="delete from ".$database_kohrx.".kohrx_med_error_indiv2  where id='".$_GET['id']."'";
$qdelete=mysql_query($delete, $hos) or die(mysql_error());
}
 if(isset($_POST['Submit'])&&($_POST['Submit']=="บันทึก")){ 
		$date11=explode("/",$date1);
		$date_error=($date11[2]-543)."-".$date11[1]."-".$date11[0];
if($_POST['time1']==""){echo "<script>if(confirm('กรุณากรอกเวลาที่เกิด error ด้วยครับ')==true){ window.history.go(-1);} </script>"; exit(); }
if($doctor==""){  echo "กรุณาเลือกบุคลากรที่เกิดความผิดพลาด";}
else if($type==""){  echo "กรุณาเลือกประเภทความผิดพลาด";}
else if($doctor!=""&&$type!=""){
if($lasa1==""&&$lasa2!=""){ echo "กรุณาเลือกยาให้ครบทั้ง 2 ตัว"; exit();}
if($lasa1!=""&&$lasa2==""){ echo "กรุณาเลือกยาให้ครบทั้ง 2 ตัว"; exit();}

mysql_select_db($database_hos, $hos);
$insert1="insert into ".$database_kohrx.".kohrx_med_error_indiv2 (date_error,doctor_code,error_type,pttype,drug1,drug2,time1,room_id) values ('$date_error','$person_code','$type','$pttype','$lasa1','$lasa2','".$_POST['time1']."','".$_GET['room_id']."')";
echo $insert1;
$qinsert1=mysql_query($insert1, $hos) or die(mysql_error());

?>
<?php
mysql_select_db($database_hos, $hos);
$query_lasa = "select i.* from ".$database_kohrx.".kohrx_med_error_indiv2 i where date_error='$date_error' and lasagroup is null and (drug1!=0 or drug2!=0)";
$lasa = mysql_query($query_lasa, $hos) or die(mysql_error());
$row_lasa = mysql_fetch_assoc($lasa);
$totalRows_lasa = mysql_num_rows($lasa);
?>
<?

//บันทึก lasagroup
if($totalRows_lasa<>0){
do { 
$a = array("$row_lasa[name1]:$row_lasa[drug1]:","$row_lasa[name2]:$row_lasa[drug2]:");
sort($a);

$aa= explode(":",$a[0]);
$bb=explode(":",$a[1]);
$lasagroup=$aa[1].$bb[1];
	
mysql_select_db($database_hos, $hos);
$query_lasa1 = "update ".$database_kohrx.".kohrx_med_error_indiv2 set lasagroup ='".$lasagroup."' where id=".$row_lasa['id']."";
$qlasa1 = mysql_query($query_lasa1, $hos) or die(mysql_error());

 } while ($row_lasa = mysql_fetch_assoc($lasa)); 
}

}
if($qupdate1){
echo "บันทึกเรียบร้อยแล้ว";
}
}
?>
<?php

mysql_select_db($database_hos, $hos);
$query_person_error = "SELECT d.name,o.doctorcode FROM ".$database_kohrx.".kohrx_rx_person o left outer join doctor d on d.code=o.doctorcode ORDER BY name";
$person_error = mysql_query($query_person_error, $hos) or die(mysql_error());
$row_person_error = mysql_fetch_assoc($person_error);
$totalRows_person_error = mysql_num_rows($person_error);

mysql_select_db($database_hos, $hos);
$query_error_type = "SELECT * FROM ".$database_kohrx.".kohrx_med_error_error_cause where type_id='2'";
$error_type = mysql_query($query_error_type, $hos) or die(mysql_error());
$row_error_type = mysql_fetch_assoc($error_type);
$totalRows_error_type = mysql_num_rows($error_type);
?>
<?php
mysql_select_db($database_hos, $hos);
$query_drug1 = "SELECT concat(drugitems.icode,'/',drugitems.did) as drugcode,concat(drugitems.name, drugitems.strength) as drugname,icode FROM drugitems WHERE drugitems.name not like '%คิด%' and drugitems.istatus='Y' ORDER BY drugitems.name ASC";
$drug1 = mysql_query($query_drug1, $hos) or die(mysql_error());
$row_drug1 = mysql_fetch_assoc($drug1);
$totalRows_drug1 = mysql_num_rows($drug1);

if(!isset($_POST['date1'])&&!isset($_GET['error_date'])){
$edate1=date('Y-m-d');
	}
else if(isset($_POST['date1'])&&!isset($_GET['error_date'])){
$date11=explode("/",$_POST['date1']);
$edate1=(($date11[2]-543)."-".$date11[1]."-".$date11[0]);
}
else if(!isset($_POST['date1'])&&isset($_GET['error_date'])){
$edate1=$_GET['error_date'];
}
else if(isset($_POST['date1'])&&isset($_GET['error_date'])){
$date11=explode("/",$_POST['date1']);
$edate1=(($date11[2]-543)."-".$date11[1]."-".$date11[0]);
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>บันทึกความคลาดเคลื่อนจากการจัดยา(Predispensing Error) กลุ่มงานเภสัชกรรมชุมชน <?php echo $row_rs_config['hospitalname']; ?></title>
<?php include('java_css_file.php'); ?> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js" ></script>
<script type="text/javascript" src="../include/datepicker/js/moment.min.js"></script>
<script type="text/javascript" src="../include/datepicker/js/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="../include/datepicker/css/daterangepicker.css" />

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
		src:url(font/thsarabunnew-webfont.woff);
        font-size: 14px;

}

</style>

<script>
$(document).ready(function(){	
			$("#time1").inputmask({"mask": "99:99"});
			$('#time1').focus();

	$('#indicator').hide();
	
	$('#save').click(function(){
		$('#indicator').show();
		$('#result').load('error_indiv_list.php?date='+encodeURIComponent($('#dateend').val())+'&time='+encodeURIComponent($('#time1').val())+'&pttype='+$('#pttype').val()+'&person_code='+$('#person_code').val()+'&error_type='+$('#type').val()+'&lasa1='+$('#lasa1').val()+'&lasa2='+$('#lasa2').val()+'&room_id='+$('#room').val()+'&action=add',function(responseTxt, statusTxt, xhr){
                         if(statusTxt == "success")
							 
							$('#indicator').hide();
                         if(statusTxt == "error")
                            alert("Error: " + xhr.status + ": " + xhr.statusText);    
                        });
		
	});

	$('#showResult').click(function(){
		showResult($('#datestart').val(),$('#dateend').val());
	});
	showResult('<?php echo date('Y-m-d'); ?>','<?php echo date('Y-m-d'); ?>');
	
    // กรณีใช้แบบ input
    $("#date1").datetimepicker({
        timepicker:false,
        format:'d/m/Y',  // กำหนดรูปแบบวันที่ ที่ใช้ เป็น 00-00-0000            
        lang:'th',  // ต้องกำหนดเสมอถ้าใช้ภาษาไทย และ เป็นปี พ.ศ.
        onSelectDate:function(dp,$input){
            var yearT=new Date(dp).getFullYear();  
            var yearTH=yearT+543;
            var fulldate=$input.val();
            var fulldateTH=fulldate.replace(yearT,yearTH);
            $input.val(fulldateTH);
        },
    });       
    // กรณีใช้กับ input ต้องกำหนดส่วนนี้ด้วยเสมอ เพื่อปรับปีให้เป็น ค.ศ. ก่อนแสดงปฏิทิน
    $("#date1").on("mouseenter mouseleave",function(e){
        var dateValue=$(this).val();
        if(dateValue!=""){
                var arr_date=dateValue.split("/"); // ถ้าใช้ตัวแบ่งรูปแบบอื่น ให้เปลี่ยนเป็นตามรูปแบบนั้น
                // ในที่นี้อยู่ในรูปแบบ 00-00-0000 เป็น d-m-Y  แบ่งด่วย - ดังนั้น ตัวแปรที่เป็นปี จะอยู่ใน array
                //  ตัวที่สอง arr_date[2] โดยเริ่มนับจาก 0 
                if(e.type=="mouseenter"){
                    var yearT=arr_date[2]-543;
                }       
                if(e.type=="mouseleave"){
                    var yearT=parseInt(arr_date[2])+543;
                }   
                dateValue=dateValue.replace(arr_date[2],yearT);
                $(this).val(dateValue);                                                 
        }       
    });
	
 $(window).keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
  });
	
});

$(function() {

    var start = moment().subtract(0, 'days');
    var end = moment().subtract(0, 'days');

    function cb(start, end) {
        $('#reportrange span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
		$('#datestart').val(start.format('Y-MM-DD'));
		$('#dateend').val(end.format('Y-MM-DD'));

    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
		lang:'th',
        ranges: {
           'วันนี้': [moment(), moment()],
           'เมื่อวาน': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'ย้อนหลัง 7 วัน': [moment().subtract(7, 'days'), moment().subtract(1, 'days')],
           '30 วันที่แล้ว': [moment().subtract(29, 'days'), moment()],
           'เดือนนี้': [moment().startOf('month'), moment().endOf('month')],
           'เดือนที่แล้ว': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
		   'ปีงบประมาณนี้':[moment([new Date().getFullYear(), 9, 01]).subtract(1,'year'),moment([new Date().getFullYear(), 8, 30])],
		   'ปีงบประมาณก่อน':[moment([new Date().getFullYear(), 9, 01]).subtract(2,'year'),moment([new Date().getFullYear(), 8, 30]).subtract(1,'year')],
        }
    }, cb);
	
    cb(start, end);
});
</script>
<script>
function showResult(datestart,dateend){
		$('#indicator').show();

	$('#result').load('error_indiv_list.php?datestart='+datestart+'&dateend='+dateend,function(responseTxt, statusTxt, xhr){
                         if(statusTxt == "success")
							$('#indicator').hide();
                         if(statusTxt == "error")
                            alert("Error: " + xhr.status + ": " + xhr.statusText);    
                        });

}
function deleteResult(id){
		$('#indicator').show();

	$('#result').load('error_indiv_list.php?id='+id+'&datestart='+encodeURIComponent($('#datestart').val())+'&dateend='+encodeURIComponent($('#dateend').val())+'&action=delete',function(responseTxt, statusTxt, xhr){
                         if(statusTxt == "success")
							$('#indicator').hide();
                         if(statusTxt == "error")
                            alert("Error: " + xhr.status + ": " + xhr.statusText);    
                        });

}

	function setfocus(a){
	$('#'+a).focus();
	}

 function showDrug(a){
	if(a==58){
		$('#drugerror').show();
		$('#drugerror2').show();
		} 
	if(a!=58){
		$('#drugerror').hide();
		$('#drugerror2').hide();
		}
	 }

function alertload(url,w,h){
	 $.colorbox({fixed:true,width:w,height:h, iframe:true, href:url, onOpen : function () {$('html').css('overflowY','hidden');},onCleanup :function(){
$('html').css('overflowY','auto');}
,onClosed:function(){ }});

}
	
function resutName(icode,doctor)
	{
		if(icode!=""){
		switch(icode)
		{
			<?
			mysql_select_db($database_hos, $hos);

			$strSQL = "SELECT d.name,o.doctorcode FROM ".$database_kohrx.".kohrx_rx_person o left outer join doctor d on d.code=o.doctorcode";
			$objQuery = mysql_query($strSQL);
			while($objResult = mysql_fetch_array($objQuery))
			{
			?>
				case "<?=$objResult["doctorcode"];?>":
				document.getElementById(doctor).value = "<?=$objResult["doctorcode"];?>";
							
				break;
			<?
			}
			?>
			default:
			 document.getElementById(doctor).value = "0066";
		}
		}
	}
	
function doctorcode(icode,doctor){
	document.getElementById(doctor).value=icode;
	}
	
function setNextFocus(objId){
        if (event.keyCode == 13){
			$('#'+objId).focus();
		}
}

</script>
</head>
<body>
<div class="row" style="height: 50px;">
	<div class="col bg-dark text-white text-center" style="-ms-flex: 0 0 200px;flex: 0 0 150px;"><div class=" font_bord" style="font-size: 30px; margin-top: -5px;">ME</div>
<div class="font12" style="margin-top: -10px;">medication error</div></div>
	<div class="col bg-info text-white" style="padding-top: 10px;">บันทึกความคลาดเคลื่อนจากการจัดยา(Predispensing Error) (short form)
	<div style=" position: absolute; top: 10px; right: 20px;"><button class="btn btn-dark btn-sm " onClick="window.location='index.php';">บันทึกความคลาดเคลื่อน (full form)</button>&nbsp;<button class="btn btn-dark btn-sm " onClick="window.open('dispen_report.php','_new');">รายงานความคลาดเคลื่อนจัดยา</button>&nbsp;<button class="btn btn-secondary btn-sm" onClick="alertload('config_error_type.php','900','500');">ตั้งค่า</button></div>
	</div>	
	
</div>
<form id="med_form" >
<div class="card m-3">
	<div class="card-body p-3">
		<div class="form-group row">
			<label for="date1" class="col-form-label-sm col-sm-2">วันที่เกิดความคลาดเคลื่อน</label>
			<div class="col-sm-auto">                
				<div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" class="form-control form-control-sm">
                  <i class="far fa-calendar-alt"></i>&nbsp;
                    <span></span> 
                  <i class="fas fa-sort-down"></i>
                </div>	
            <input name="datestart" type="hidden" id="datestart" value="" /><input name="dateend" type="hidden" id="dateend" value="" />
			</div>
			<div class="col-sm-auto"><input type="button" class="btn btn-success btn-sm" id="showResult" value="ค้นหา"/></div>
			<label for="time1" class="col-form-label-sm col-sm-2">เวลาที่เกิดความคลาดเคลื่อน</label>
			<div class="col-sm-auto"><input type="text" name="time1" id="time1" class="form-control form-control-sm" onKeyUp ="setNextFocus('pttype');" /></div>

		</div>
		<div class="form-group row">
			<label for="pttype" class="col-form-label-sm col-sm-2">ประเภทผู้ป่วย</label>
			<div class="col-sm-auto">
				<select name="pttype" id="pttype" class="form-control form-control-sm" onKeyUp ="setNextFocus('type');" >
					<option value="">ไม่เลือก</option>
					<option value="OPD" <?php if (!(strcmp("OPD", $_POST['pttype']))) {echo "selected=\"selected\"";} ?>>OPD</option>
					<option value="IPD" <?php if (!(strcmp("IPD", $_POST['pttype']))) {echo "selected=\"selected\"";} ?>>IPD</option>
      			</select>
			</div>
			<label for="type" class="col-form-label-sm col-sm-auto">ความคลาดเคลื่อน</label>
			<div class="col-sm-auto">
			<select name="type" id="type" onChange="showDrug(this.value)" class="form-control form-control-sm" onKeyUp ="setNextFocus('person_code');">
				<option value="">ไม่เลือก</option>
				<?php
					do {  
					?>
						<option value="<?php echo $row_error_type['id']?>"><?php echo $row_error_type['name']?></option>
							  <?php
					} while ($row_error_type = mysql_fetch_assoc($error_type));
					  $rows = mysql_num_rows($error_type);
					  if($rows > 0) {
						  mysql_data_seek($error_type, 0);
						  $row_error_type = mysql_fetch_assoc($error_type);
					  }
				?>
				</select>
			</div>

		</div>
		<div class="form-group row" id="drugerror" style="display:none">
			<label for="lasa1" class="col-form-label-sm col-sm-2">ยา1[ยาที่ถูกต้อง]</label>
			<div class="col-sm-auto">
				<select name="lasa1" id="lasa1" class="form-control form-control-sm">
				<option value="">-</option>
				<?php
				do {  
				?>
				<option value="<?php echo $row_drug1['icode']?>"><?php echo $row_drug1['drugname']?></option>
				<?php
				} while ($row_drug1 = mysql_fetch_assoc($drug1));
				  $rows = mysql_num_rows($drug1);
				  if($rows > 0) {
					  mysql_data_seek($drug1, 0);
					  $row_drug1 = mysql_fetch_assoc($drug1);
				  }
				?>
				</select>
			</div>

		</div>
		<div class="form-group row" id="drugerror2" style="display:none">
			<label for="lasa2" class="col-form-label-sm col-sm-2">ยา2[ยาที่ผิด]</label>
			<div class="col-sm-auto">
				<select name="lasa2" id="lasa2" class="form-control form-control-sm">
					<option value="">-</option>
			        <?php
					do {  
					?>
					<option value="<?php echo $row_drug1['icode']?>"><?php echo $row_drug1['drugname']?></option>
					<?php
					} while ($row_drug1 = mysql_fetch_assoc($drug1));
					  $rows = mysql_num_rows($drug1);
					  if($rows > 0) {
						  mysql_data_seek($drug1, 0);
						  $row_drug1 = mysql_fetch_assoc($drug1);
					  }
					?>
			      </select>
			</div>

		</div>

		<div class="form-group row">
			<label for="person_code" class="col-form-label-sm col-sm-2">ผู้เกิดความคลาดเคลื่อน</label>
			<div class="col-sm-auto">
				<input name="person_code" type="text" class="form-control form-control-sm" id="person_code"  onkeyup="resutName(this.value,'doctor')"  size="2" onKeyPress="return isNumberKey(event);"   />			
			</div>			
			<div class="col-sm-auto">
				<select name="doctor" id="doctor" class="form-control form-control-sm" onChange="doctorcode(this.value,'person_code')" onKeyUp ="setNextFocus('save');">
						  <?php
							do {  
							?>
							 <option value="<?php echo $row_person_error['doctorcode']?>"><?php echo $row_person_error['name']?></option>
							 <?php
							} while ($row_person_error = mysql_fetch_assoc($person_error));
							  $rows = mysql_num_rows($person_error);
							  if($rows > 0) {
								  mysql_data_seek($person_error, 0);
								  $row_person_error = mysql_fetch_assoc($person_error);
							  }
							?>
				</select>		
			</div>
			<label class="col-form-label col-form-label-sm col-sm-auto" >ห้องจ่ายยาที่พบ</label>
			<div class="col-sm-auto">
			<select name="room" id="room" class="form-control form-control-sm">
            <?php do {  ?>
                    <option value="<?php echo $row_rs_room['id']?>"<?php if($row_rs_edit['room_id']!=""){ if (!(strcmp($row_rs_room['id'], $row_rs_edit['room_id']))) {echo "selected=\"selected\"";}} else {if (!(strcmp($row_rs_room['id'], $row_channel['room_id']))) {echo "selected=\"selected\"";}} ?>><?php echo $row_rs_room['room_name']?></option>
                    <?php
            } while ($row_rs_room = mysql_fetch_assoc($rs_room));
              $rows = mysql_num_rows($rs_room);
              if($rows > 0) {
                  mysql_data_seek($rs_room, 0);
                  $row_rs_room = mysql_fetch_assoc($rs_room);
              }
            ?>
            </select>				
			</div>
			<div class="col-sm-auto">
				<input type="button" class="btn btn-primary btn-sm" id="save" value="บันทึก"/>
			</div>


		</div>
	</div>
</div>
<div class="position-relative">
<div class="position-absolute text-center" id="indicator" style="width: 100%;z-index: 2;">
<div  align="center" class="spinner">
  <button class="btn btn-primary" style="opacity: 0.5;">
    <span class="spinner-border " style="width: 5rem; height: 5rem;" role="status"></span>
    <br />
<span>กำลังโหลด</span>
  </button>
</div>
</div>
	
<div class="mt-2 p-3" id="result" ></div>
</div>
	</form>
</body>
</html>
<?php
mysql_free_result($drug1);

mysql_free_result($person_error);

mysql_free_result($error_type);

mysql_free_result($channel);

mysql_free_result($rs_room);
?>
