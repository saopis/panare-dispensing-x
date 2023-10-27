<?php require_once('../Connections/hos.php'); ?>
<?php

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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>รายงานความคลาดเคลื่อนจากการจัดยา</title>
<?php include('java_css_file.php'); ?> 

<script>
$(document).ready(function(){
	$('#indicator').hide();
    $('.timecheck').hide();
	
    $('#timecheck').click(function(){
        if ($('#timecheck').prop('checked')) {
            $('.timecheck').show();
        }
        else{
            $('.timecheck').hide();            
        }
    });
    
	$('#search').click(function(){
		var timecheck;
		if ($('input#timecheck').prop('checked')) {
		timecheck="Y";
		}
		else{
		timecheck="";
		}
        
		var prepare;
		if ($('input#prepare').prop('checked')) {
		prepare="Y";
		}
		else{
		prepare="";
		}        
        
		$('#indicator').show();
		$('#result').load('dispen_report_result.php?month='+$('#month').val()+'&year='+$('#year').val()+'&timecheck='+timecheck+'&time1='+$('#time1').val()+'&time2='+$('#time2').val()+'&pttype='+$('#pttype').val()+'&room_id='+$('#room').val()+'&prepare='+prepare,function(responseTxt, statusTxt, xhr){
                         if(statusTxt == "success")
							$('#indicator').hide();
                         if(statusTxt == "error")
                            alert("Error: " + xhr.status + ": " + xhr.statusText);    
         });		
	});

});
		
</script>  
<script type="text/javascript">
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}

function alertload(url,w,h){
	 $.colorbox({fixed:true,width:w,height:h, iframe:true, href:url, onOpen : function () {$('html').css('overflowY','hidden');},onCleanup :function(){
$('html').css('overflowY','auto');}
,onClosed:function(){ }});

}
</script>
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
</style>

</head>

<body>

<div class="row" style="height: 50px;">
	<div class="col bg-dark text-white text-center" style="-ms-flex: 0 0 200px;flex: 0 0 150px;"><div class=" font_bord" style="font-size: 30px; margin-top: -5px;">ME</div>
<div class="font12" style="margin-top: -10px;">medication error</div></div>
	<div class="col bg-info text-white" >
		<div class="row">
		<div class="col-auto" style="padding-top: 10px;">รายงานความคลาดเคลื่อนจากการจัดยา(Predispensing Error) รายเดือน</div>
		<div class="col text-right" style="padding-top: 8px; right:10px;">
		<a href="#" onClick="alertload('report_lasa.php?date_error=<? echo $ym; ?>&pttype=<?=$pttype; ?>','90%','90%');" class="btn btn-primary btn-sm">รายงาน LASA</a>
		</div>
		</div>
</div>	

</div>
	
<div class=" bg-light p-1">
	<div class="row">
	<label for="month" class="col-form-label col-form-label-sm col-sm-auto">เลือกเดือน/ปี</label>
	<div class="col-sm-auto" >
	<select name="month" id="month" class="form-control form-control-sm">
        <option value="01" <?php if (!(strcmp("01", date('m')))) {echo "selected=\"selected\"";} ?>>มกราคม</option>
        <option value="02" <?php if (!(strcmp("02", date('m')))) {echo "selected=\"selected\"";} ?>>กุมภาพันธ์</option>
        <option value="03" <?php if (!(strcmp("03", date('m')))) {echo "selected=\"selected\"";} ?>>มีนาคม</option>
        <option value="04" <?php if (!(strcmp("04", date('m')))) {echo "selected=\"selected\"";} ?>>เมษายน</option>
        <option value="05" <?php if (!(strcmp("05", date('m')))) {echo "selected=\"selected\"";} ?>>พฤษภาคม</option>
        <option value="06" <?php if (!(strcmp("06", date('m')))) {echo "selected=\"selected\"";} ?>>มิถุนายน</option>
        <option value="07" <?php if (!(strcmp("07", date('m')))) {echo "selected=\"selected\"";} ?>>กรกฎาคม</option>
        <option value="08" <?php if (!(strcmp("08", date('m')))) {echo "selected=\"selected\"";} ?>>สิงหาคม</option>
        <option value="09" <?php if (!(strcmp("09", date('m')))) {echo "selected=\"selected\"";} ?>>กันยายน</option>
        <option value="10" <?php if (!(strcmp("10", date('m')))) {echo "selected=\"selected\"";} ?>>ตุลาคม</option>
        <option value="11" <?php if (!(strcmp("11", date('m')))) {echo "selected=\"selected\"";} ?>>พฤศจิกายน</option>
        <option value="12" <?php if (!(strcmp("12", date('m')))) {echo "selected=\"selected\"";} ?>>ธันวาคม</option>
      </select>	
	</div>
	<div class="col-sm-auto">
          <select name="year" id="year" class="form-control form-control-sm">
            <? for($i=(date('Y')-5);$i<=(date('Y')+543);$i++){?>
            <option value="<? echo $i+543; ?>" <?php if (!(strcmp(date('Y'), $i))) {echo "selected=\"selected\"";} ?>><? echo $i+543; ?></option>
            <? } ?>
          </select>
	
	</div>
	<label class="checkbox-inline "><input type="checkbox" value="Y" id="timecheck">&nbsp;ช่วงเวลา</label>		
	<div class="col-sm-auto timecheck">
		<input name="time1" type="text" id="time1" class="form-control form-control-sm" value="08:00" style="width: 80px;" />	
	</div>
	<label for="time2" class="col-form-label col-form-label-sm col-sm-auto timecheck">ถึง</label>	
	<div class="col-sm-auto timecheck">
        <input name="time2" type="text" id="time2" class="form-control form-control-sm" value="16:00" style="width: 80px;" />
	</div>

	<div class="col-sm-auto">
		<select name="pttype" id="pttype" class="form-control form-control-sm">
			<option value="all">ประเภทผู้ป่วยทั้งหมด</option>
			<option value="OPD">OPD</option>
			<option value="IPD">IPD</option>
		</select> 		
	</div>
	<div class="col-sm-auto">
		<select name="room" id="room" class="form-control form-control-sm">
			<option value="">ห้องทั้งหมด</option>
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
	<label class="checkbox-inline "><input type="checkbox" value="Y" id="prepare" name="prepare">&nbsp;คำนวณการจัด</label>		        
	<div class="col-sm-auto">
		<input type="button" name="search" id="search" value="แสดงผล" class="btn btn-primary btn-sm" />		
	</div>

</div>
</div>

<div class="position-relative">
<div class="position-absolute text-center mt-5" id="indicator" style="width: 100%;z-index: 2;">
<div  align="center" class="spinner">
  <button class="btn btn-primary" style="opacity: 0.5;">
    <span class="spinner-border " style="width: 5rem; height: 5rem;" role="status"></span>
    <br />
<span>กำลังโหลด</span>
  </button>
</div>
</div>
	
<div class="p-3" id="result" style="overflow:scroll;overflow-x:hidden;overflow-y:auto; height:85vh; padding-top: 0px;" ></div>
</div>

	

</body>
</html>
<?php
mysql_free_result($channel);
mysql_free_result($rs_room);
?>