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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Dispen IPD Report</title>
<?php include('java_css_online.php'); ?> 

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
                        $("#displayDiv").load('dispen_ipd_report_list.php?do=search&hn='+$('#hn').val()+'&datestart='+encodeURIComponent($('#datestart').val())+'&dateend='+encodeURIComponent($('#dateend').val())+'&patient_type='+encodeURIComponent($('#patient_type').val())+'&person0='+encodeURIComponent($('#person0').val())+'&person1='+encodeURIComponent($('#person1').val())+'&person3='+encodeURIComponent($('#person3').val())+'&an='+encodeURIComponent($('#an').val()), function(responseTxt, statusTxt, xhr){
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();
						$('#print').show();
                    
					if(statusTxt == "error")
                      alert("Error: " + xhr.status + ": " + xhr.statusText);
                  });
    });

    $('#print').click(function(){
                        
                        window.open('dispen_ipd_report_list.php?export=Y&do=search&hn='+$('#hn').val()+'&datestart='+encodeURIComponent($('#datestart').val())+'&dateend='+encodeURIComponent($('#dateend').val())+'&patient_type='+encodeURIComponent($('#patient_type').val())+'&person0='+encodeURIComponent($('#person0').val())+'&person1='+encodeURIComponent($('#person1').val())+'&person3='+encodeURIComponent($('#person3').val())+'&an='+encodeURIComponent($('#an').val()),'_new');
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
        <a class="navbar-brand mx-auto" href="#"><i class="fas fa-procedures font20"></i>&ensp;ระบบรายงานบันทึกจ่ายยาผู้ป่วยใน</a>
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
            
    </div> 
      <div class="row mt-2">
            <label class="col-form-label col-form-label-sm col-sm-auto">ประเภทผู้ป่วย</label>
            <div class="col-sm-auto">
			  <select name="patient_type" class="form-control" id="patient_type">
				<option value="1">ผู้ป่วย admit</option>
				<option value="2">ผู้ป่วยกลับบ้าน</option>
	          </select>
            </div>
		  <label class="col-form-label col-form-label-sm col-sm-auto">HN</label>
            <div class="col-sm-auto">
            <input name="hn" type="text" style="width: 100px; padding-left: 3px;" class="form-control" id="hn" />
            </div>
            <label class="col-form-label col-form-label-sm col-sm-auto">AN</label>
            <div class="col-sm-auto">
            <input name="an" type="text" style="width: 100px; padding-left: 3px;" class="form-control" id="an" />
            </div>

      </div>

        <div class="row mt-2">
             <label class="col-form-label col-form-label-sm col-sm-1">ผู้บันทึก</label>   
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
              <div class="col-sm-auto" ><button class="btn btn-primary" id="search">ค้นหา</button>&ensp;<button class="btn btn-success" id="print"><i class="fas fa-file-excel"></i>&nbsp;export</button></div>
        </div>

                    
</div>
</div>

<div id="indicator"  align="center" style="position:absolute; display:none; z-index:1000;padding:0px;"><img src="images/indicator.gif" hspace="10" align="absmiddle" /></div><div id="displayDiv" class="p-2">&nbsp;</div>
</div>
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="include/datepicker/js/moment.min.js"></script>
<script type="text/javascript" src="include/datepicker/js/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="include/datepicker/css/daterangepicker.css" />
<script type="text/javascript">
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
           'เดือนที่แล้ว': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);
	
    cb(start, end);
	


});
</script>
</body>
</html>
<?php
mysql_free_result($person);

mysql_free_result($rs_setting);

?>
