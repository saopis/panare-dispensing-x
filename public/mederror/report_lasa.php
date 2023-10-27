<?php require_once('../Connections/hos.php'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<? 
//include("FusionCharts.php");
//include('../Connections/DBConn.php'); 
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php include('java_css_file.php'); ?> 
<script type="text/javascript" src="../include/datepicker/js/moment.min.js"></script>
<script type="text/javascript" src="../include/datepicker/js/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="../include/datepicker/css/daterangepicker.css" />

<style>
body {
	background-color: #FFFFFF;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
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
<script>
$(document).ready(function($){ 
	$('#indicator').hide();
	
	$('#search').click(function(){
		if($('#lasa').val()=='1'){
			var url='report_lasa_result.php';
		}
		else{
			var url='report_lasa_indiv.php';
		}
		
		$('#indicator').show();
		$('#result').load(url+'?date1='+encodeURIComponent($('#datestart').val())+'&date2='+encodeURIComponent($('#dateend').val())+'&pttype='+$('#pttype').val(),function(responseTxt, statusTxt, xhr){
                         if(statusTxt == "success") 
							$('#indicator').hide();
                         if(statusTxt == "error")
                            alert("Error: " + xhr.status + ": " + xhr.statusText);    
                        });		
	});

//เลือกวันที่
    var start = moment().startOf('month');
    var end = moment().endOf('month');

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

</head>

<body>
<nav class="navbar navbar-light bg-info">
  <a class="navbar-brand text-white">รายงานสรุป LASA</a>
</nav>
<div class="p-2 pl-4 pb-0 bg-gray1">
<div class="row ">
	  <label class="col-form-label col-form-label-sm">เลือกวันที่</label>

				<div class="col-sm-auto">                
				<div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" class="form-control form-control-sm">
                  <i class="far fa-calendar-alt"></i>&nbsp;
                    <span></span> 
                  <i class="fas fa-sort-down"></i>
                </div>	
            <input name="datestart" type="hidden" id="datestart" value="" /><input name="dateend" type="hidden" id="dateend" value="" />
			</div>
		<label for="pttype" class="col-form-label col-form-label-sm col-sm-auto">ประเภทผู้ป่วย</label>	
	<div class="col-sm-auto">
		<select name="pttype" id="pttype" class="form-control form-control-sm">
			<option value="all">ทั้งหมด</option>
			<option value="OPD">OPD</option>
			<option value="IPD">IPD</option>
		</select> 		
	</div>
	<div class="col-sm-auto">
		<select name="lasa" id="lasa" class="form-control form-control-sm">
			<option value="1">LASA รวม</option>
			<option value="2">LASA รายบุคคล</option>
		</select> 		
	</div>	
</div>
<div class="row mt-2">
	<label class="checkbox-inline "><input type="checkbox" value="Y" id="timecheck">เลือกช่วงเวลา</label>		
	<div class="col-sm-auto">
		<input name="time1" type="text" id="time1" class="form-control form-control-sm" value="08:00" />	
	</div>
	<label for="time2" class="col-form-label col-form-label-sm col-sm-auto">ถึง</label>	
	<div class="col-sm-auto">
        <input name="time2" type="text" id="time2" class="form-control form-control-sm" value="16:00" />
	</div>
			<div class="col-sm-auto">
				<a class="btn btn-success text-white" id="search">แสดงข้อมูล</a>
			</div>
</div>
</div>
<div class="position-relative">
<div class="position-absolute text-center mt-4" id="indicator" style="width: 100%;z-index: 2; ">
<div  align="center" class="spinner">
  <button class="btn btn-primary" style="opacity: 0.5;">
    <span class="spinner-border " style="width: 5rem; height: 5rem;" role="status"></span>
    <br />
<span>กำลังโหลด</span>
  </button>
</div>
</div>
	
<div id="result" ></div>
</div>
	
	
<form id="form1" name="form1" method="post" action="">

</form>
<p>&nbsp;</p>
</body>
</html>
<?php
//mysql_free_result($lasa);
?>
