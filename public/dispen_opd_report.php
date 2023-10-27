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
include('include/function_sql.php');
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
$query_rs_room = "select * from ".$database_kohrx.".kohrx_queue_caller_room";
$rs_room = mysql_query($query_rs_room, $hos) or die(mysql_error());
$row_rs_room = mysql_fetch_assoc($rs_room);
$totalRows_rs_room = mysql_num_rows($rs_room);

mysql_select_db($database_hos, $hos);
$query_rs_respondent = "select * from ".$database_kohrx.".kohrx_adr_check_respondent";
$rs_respondent = mysql_query($query_rs_respondent, $hos) or die(mysql_error());
$row_rs_respondent = mysql_fetch_assoc($rs_respondent);
$totalRows_rs_respondent = mysql_num_rows($rs_respondent);

mysql_select_db($database_hos, $hos);
$query_rs_chw = "select * from ".$database_kohrx.".kohrx_province order by province_name ASC";
$rs_chw = mysql_query($query_rs_chw, $hos) or die(mysql_error());
$row_rs_chw = mysql_fetch_assoc($rs_chw);
$totalRows_rs_chw = mysql_num_rows($rs_chw);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Dispen_opd_report</title>
<?php include('java_css_online.php'); ?> 

<script type="text/javascript">
$(document).ready(function() {
	$('#print').hide();
	$('#print2').hide();
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
                        $("#displayDiv").load('dispen_opd_report_list.php?do=search&hn='+$('#hn').val()+'&datestart='+encodeURIComponent($('#datestart').val())+'&dateend='+encodeURIComponent($('#dateend').val())+'&time1='+encodeURIComponent($('#time1').val())+'&time2='+encodeURIComponent($('#time2').val())+'&print_type='+encodeURIComponent($('#print_type').val())+'&room='+encodeURIComponent($('#room').val())+'&person0='+encodeURIComponent($('#person0').val())+'&person1='+encodeURIComponent($('#person1').val())+'&person2='+encodeURIComponent($('#person2').val())+'&person3='+encodeURIComponent($('#person3').val())+'&note='+encodeURIComponent($('#note').val())+'&receiver='+encodeURIComponent($('#receiver').val())+'&chw='+encodeURIComponent($('#chw').val())+'&amp='+encodeURIComponent($('#amp').val())+'&tambon='+encodeURIComponent($('#tambon').val())+'&nohave='+$('#nohave').val(), function(responseTxt, statusTxt, xhr){
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();
						$('#print').show();
                        $('#print2').show();
                    
					if(statusTxt == "error")
                      alert("Error: " + xhr.status + ": " + xhr.statusText);
                  });
    });

    $('#print').click(function(){
                        
                        window.open('dispen_opd_report_list.php?do=search&export=Y&hn='+$('#hn').val()+'&datestart='+encodeURIComponent($('#datestart').val())+'&dateend='+encodeURIComponent($('#dateend').val())+'&time1='+encodeURIComponent($('#time1').val())+'&time2='+encodeURIComponent($('#time2').val())+'&print_type='+encodeURIComponent($('#print_type').val())+'&room='+encodeURIComponent($('#room').val())+'&person0='+encodeURIComponent($('#person0').val())+'&person1='+encodeURIComponent($('#person1').val())+'&person2='+encodeURIComponent($('#person2').val())+'&person3='+encodeURIComponent($('#person3').val())+'&note='+encodeURIComponent($('#note').val())+'&receiver='+encodeURIComponent($('#receiver').val())+'&chw='+encodeURIComponent($('#chw').val())+'&amp='+encodeURIComponent($('#amp').val())+'&tambon='+encodeURIComponent($('#tambon').val())+'&nohave='+$('#nohave').val(),'_new');
    });
    $('#print2').click(function(){
                        
                        window.open('dispen_opd_report_list2.php?do=search&export=Y&hn='+$('#hn').val()+'&datestart='+encodeURIComponent($('#datestart').val())+'&dateend='+encodeURIComponent($('#dateend').val())+'&time1='+encodeURIComponent($('#time1').val())+'&time2='+encodeURIComponent($('#time2').val())+'&print_type='+encodeURIComponent($('#print_type').val())+'&room='+encodeURIComponent($('#room').val())+'&person0='+encodeURIComponent($('#person0').val())+'&person1='+encodeURIComponent($('#person1').val())+'&person2='+encodeURIComponent($('#person2').val())+'&person3='+encodeURIComponent($('#person3').val())+'&note='+encodeURIComponent($('#note').val())+'&receiver='+encodeURIComponent($('#receiver').val())+'&chw='+encodeURIComponent($('#chw').val())+'&amp='+encodeURIComponent($('#amp').val())+'&tambon='+encodeURIComponent($('#tambon').val())+'&nohave='+$('#nohave').val(),'_new');
    });

//เลือกจังหวัด
$(".chw").change(function()
{
$('#tambon').val("");
$('#tambon').html("<option value=''>-- เลือกตำบล --</option>");
var id=$(this).val();
var dataString = 'id='+ id;
$.ajax
({
type: "POST",
url: "amphur.php",
data: dataString,
cache: false,
success: function(html)
{
$("#amp").html(html);
} 
});

});
//// เลือกอำเภอ
$("#amp").change(function()
{
var id=$(this).val();
var dataString = 'id='+ id;
if(id!=""){
$.ajax
({
type: "POST",
url: "tambon.php",
data: dataString,
cache: false,
success: function(html)
{
$("#tambon").html(html);
},
error: function(){
$('#tambon').html("<option value=''>-- เลือกตำบล --</option>");

}
});
}
else if(id==""){
$('#tambon').html("<option value=''>-- เลือกตำบล --</option>");

}
});

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
</style>
</head>

<body>
<nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <div class="navbar-collapse collapse w-100 order-1 order-md-0 dual-collapse2">
        
    </div>
    <div class="mx-auto order-0">
        <a class="navbar-brand mx-auto" href="#"><i class="fas fa-user-injured font20"></i>&ensp;ระบบรายงานบันทึกจ่ายยาผู้ป่วยนอก</a>
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
            <label class="col-form-label col-form-label-sm col-sm-auto">เลือกช่วงวันที่</label>
            <div class="col-sm-auto">
                <div id="reportrange" class="form-control" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                  <i class="far fa-calendar-alt"></i>&nbsp;
                    <span></span> 
                  <i class="fas fa-sort-down"></i>
                </div>	
            <input name="datestart" type="hidden" id="datestart" value="" /><input name="dateend" type="hidden" id="dateend" value="" />
                
            </div>
            <div class="col-sm-auto">เวลามา</div>
            <div class="col-sm-auto"><input name="time1" type="text" class="form-control" id="time1" value="00:01" size="5" /></div><div class="col-sm-auto">ถึง</div><div class="col-sm-auto"><input type="text" size="5" id="time2" class="form-control" name="time2"  /></div>
    </div> 
      <div class="row mt-2">
            <label class="col-form-label col-form-label-sm col-sm-auto">ประเภทการพิมพ์</label>
            <div class="col-sm-auto">
            <select name="print_type" class="form-control" id="print_type">
              <option value="1">พิมพ์จากห้องจ่ายยา</option>
              <option value="2">พิมพ์จากห้องตรวจ</option>
            </select>
            </div>            
<label class="col-form-label col-form-label-sm col-sm-auto">ห้องจ่ายยา</label>
            <div class="col-sm-auto">
              <select name="room" class="form-control" id="room">
                              <option value="">ทั้งหมดทุกห้อง</option>
                              <?php
                do {  
                ?>
                              <option value="<?php echo $row_rs_room['id']?>"><?php echo $row_rs_room['room_name']?></option>
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
      </div>
        <div class="row mt-2">
            <label class="col-form-label col-form-label-sm col-sm-1">HN</label>
            <div class="col-sm-auto">
            <input name="hn" type="text" class="form-control" id="hn" />
            </div>
             <label class="col-form-label col-form-label-sm col-sm-1">note</label>   
			   <div class="col-sm-auto">
				   <select id="nohave" name="nohave" class="form-control">
					   <option value="1">มีคำว่า</option>
					   <option value="2">ไม่มีคำว่า</option>
				   </select>
				</div>			
			   <div class="col-sm-auto"><input type="text" class="form-control" id="note"/></div>
        </div>


        <div class="row mt-2">
             <label class="col-form-label col-form-label-sm col-sm-1">ผู้พิมพ์</label>   
                <div class="col-sm-auto">
                  <select name="person0" class="form-control" id="person0">
                                <option value="">------เลือก-----</option>
                                <?php
                    do {  
                    ?>
                                <option value="<?php echo $row_person['doctorcode']?>"><?php echo $row_person['name']?></option>
                                <?php
                    } while ($row_person = mysql_fetch_assoc($person));
                      $rows = mysql_num_rows($person);
                      if($rows > 0) {
                          mysql_data_seek($person, 0);
                          $row_person = mysql_fetch_assoc($person);
                      }
                    ?>
                    </select>                    
                </div>
            
        </div>
        <div class="row mt-2">
             <label class="col-form-label col-form-label-sm col-sm-1">ผู้จัด</label>   
                <div class="col-sm-auto">
                  <select name="person1" class="form-control" id="person1">
                                <option value="">------เลือก-----</option>
                                <?php
                    do {  
                    ?>
                                <option value="<?php echo $row_person['doctorcode']?>"><?php echo $row_person['name']?></option>
                                <?php
                    } while ($row_person = mysql_fetch_assoc($person));
                      $rows = mysql_num_rows($person);
                      if($rows > 0) {
                          mysql_data_seek($person, 0);
                          $row_person = mysql_fetch_assoc($person);
                      }
                    ?>
                    </select>                    
            </div>
        </div>
          <div class="row mt-2">
             <label class="col-form-label col-form-label-sm col-sm-1">ผู้ตรวจสอบ</label>   
                <div class="col-sm-auto">
                    <select name="person2" class="form-control" id="person2">
                                <option value="">------เลือก-----</option>
                                <?php
                    do {  
                    ?>
                                <option value="<?php echo $row_person['doctorcode']?>"><?php echo $row_person['name']?></option>
                                <?php
                    } while ($row_person = mysql_fetch_assoc($person));
                      $rows = mysql_num_rows($person);
                      if($rows > 0) {
                          mysql_data_seek($person, 0);
                          $row_person = mysql_fetch_assoc($person);
                      }
                    ?>
                  </select>
              </div>
        </div>
          <div class="row mt-2">
             <label class="col-form-label col-form-label-sm col-sm-1">ผู้จ่าย</label>   
                <div class="col-sm-auto">
                  <select name="person3" class="form-control" id="person3">
                                <option value="">------เลือก-----</option>
                                <?php
                    do {  
                    ?>
                                <option value="<?php echo $row_person['doctorcode']?>"><?php echo $row_person['name']?></option>
                                <?php
                    } while ($row_person = mysql_fetch_assoc($person));
                      $rows = mysql_num_rows($person);
                      if($rows > 0) {
                          mysql_data_seek($person, 0);
                          $row_person = mysql_fetch_assoc($person);
                      }
                    ?>
                </select>                    
              </div>

        </div>
          <div class="row mt-2">
             <label class="col-form-label col-form-label-sm col-sm-1">ผู้รับ</label>   
                <div class="col-sm-auto">
                <select name="receiver" class="form-control" id="receiver">
                            <option value="">------เลือก-----</option>
                            <?php
                do {  
                ?>
                            <option value="<?php echo $row_rs_respondent['id']?>"><?php echo $row_rs_respondent['respondent']?></option>
                            <?php
                } while ($row_rs_respondent = mysql_fetch_assoc($rs_respondent));
                  $rows = mysql_num_rows($rs_respondent);
                  if($rows > 0) {
                      mysql_data_seek($rs_respondent, 0);
                      $row_rs_respondent = mysql_fetch_assoc($rs_respondent);
                  }
                ?>
                          </select>              
              </div>
        </div>
      <div class="row mt-2">
             <label class="col-form-label col-form-label-sm col-sm-1">ที่อยู่</label>   
                <div class="col-sm-auto">
                  <select name="chw" id="chw" class="form-control chw" style="width:200px">
                    <option selected="selected" value="">--เลือกจังหวัด--</option>
                    <?php
do {  
?>
                    <option value="<?php echo $row_rs_chw['province_code']?>"><?php echo $row_rs_chw['province_name']?></option>
                    <?php
} while ($row_rs_chw = mysql_fetch_assoc($rs_chw));
  $rows = mysql_num_rows($rs_chw);
  if($rows > 0) {
      mysql_data_seek($rs_chw, 0);
	  $row_rs_chw = mysql_fetch_assoc($rs_chw);
  }
?>
                  </select>
          </div>
          <div class="col-sm-auto">
                  <select name="amp" id="amp" class="form-control amp" >
                    <option selected="selected" value="">-- เลือกอำเภอ --</option>
                  </select>
          </div>
                <div class="col-sm-auto">
                  <select name="tambon" id="tambon" class="form-control tambon" >
                    <option selected="selected" value="">-- เลือกตำบล --</option>
                  </select>
            </div>
              <div class="col-sm-auto" ><button class="btn btn-primary" id="search">ค้นหา</button>&ensp;<button class="btn btn-success" id="print"><i class="fas fa-file-excel"></i>&nbsp;export</button>&ensp;<button class="btn btn-success" id="print2"><i class="fas fa-file-excel"></i>&nbsp;export2</button></div>
        </div>                    
</div>
</div>

<div id="indicator"  align="center" style="position:absolute; display:none; z-index:1000;padding:0px;"><img src="images/indicator.gif" hspace="10" align="absmiddle" /></div>
	<div id="displayDiv" class="p-2"></div>
</div>
<?php include('include/datepicker/datepickerrang.php'); ?>
</body>
</html>
<?php
mysql_free_result($person);

mysql_free_result($rs_setting);

mysql_free_result($rs_room);

mysql_free_result($rs_respondent);

mysql_free_result($rs_chw);

?>
