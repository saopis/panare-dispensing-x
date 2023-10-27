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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link type="text/css" href="css/ui-lightness/jquery-ui-1.8.10.custom.css" rel="stylesheet" />	
<link rel="stylesheet" href="include/bootstrap4/css/bootstrap.min.css" >
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
<link href="include/bootstrap4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<script src="include/jquery.js" ></script>
<script src="include/bootstrap4/js/popper.min.js"></script>
<script src="include/bootstrap4/js/bootstrap.min.js"></script>   
<script  src="include/ajax_framework.js"></script>
<script src="include/jquery.maskedinput.js" type="text/javascript"></script>
<script type="text/javascript" src="include/jquery-ui-1.8.10.offset.datepicker.min.js"></script>
<script type="text/javascript" src="include/ui.datepicker-th.js"></script>

<script>
jQuery(function($){ 
  $("#date1").mask("99/99/9999"); 
  $("#time1").mask("99:99");
  $("#time2").mask("99:99");
  $("#time3").mask("99:99");
  });
</script>
<script>
$(document).ready(function(){
    $('#table').DataTable();
    $('#table2').DataTable();
} );
</script>
<script type="text/javascript">
function formSubmit(sID,displayDiv,indicator,eID) {
	if(sID!=''){ $('#do').val(sID);}
	if(eID!=''){ $('#id').val(eID);}
	 var URL = "drugqty_check_list.php"; 
	var data = getFormData("form1");
	ajaxLoad('post', URL, data, displayDiv,indicator);
	var e = document.getElementById(indicator);
	e.style.display = 'block';
	$('#button').val("ค้นหา"); 	
	document.getElementById('button').onclick=function(){formSubmit('search','displayDiv','indicator');};			
	}
</script>
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
</style>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
</head>

<body>
<nav class="navbar" style="background-color:#4F9EC4; color:#FFF;">
ระบบรายงานบันทึกอุบัติการณ์การสั่งยาผิดจำนวน
</nav>
<table width="100%" border="0" cellpadding="10" cellspacing="0">
   <tr>
    <td><form id="form1" name="form1" method="post" action="">
      <table width="600" border="0" cellpadding="5" cellspacing="0">
        <tr>
          <td width="123">เลือกช่วงวัน</td>
          <td width="457"><div id="reportrange" class="form-control" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                  <i class="glyphicon glyphicon-calendar"></i>&nbsp;
                    <span></span> 
                  <i class="glyphicon glyphicon-chevron-down"></i>
                </div>			
            <input name="date1" type="hidden" id="date1" /><input name="date2" type="hidden" id="date2"  /></td>
        </tr>
        <tr>
          <td>ตัวเลือก</td>
          <td><select class="form-control" name="s_totalcost" id="s_totalcost">
            <option value="1">แสดงรายการทั้งหมด</option>
            <option value="2">แสดงเฉพาะที่สั่งเกิน</option>
            <option value="3">แสดงเฉพาะที่สั่งไม่พอ</option>
          </select></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><input type="button" name="search" id="search" class="btn btn-info" value="ค้นหา"  onclick="formSubmit('search','displayDiv','indicator')"/>
            <input type="hidden" name="id" id="id" />
            <input type="hidden" name="do" id="do" /></td>
        </tr>
      </table>
    </form><br />

    <div id="indicator"  align="center" style="position:absolute; display:none; z-index:1000;padding:0px;"><img src="images/indicator.gif" hspace="10" align="absmiddle" /></div><div id="displayDiv">&nbsp;</div></td>
  </tr>
</table>
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="include/bootstrap4/js/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script type="text/javascript">
$(function() {

    var start = moment().subtract(7, 'days');
    var end = moment().subtract(1, 'days');

    function cb(start, end) {
        $('#reportrange span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
		$('#date1').val(start.format('Y-MM-DD'));
		$('#date2').val(end.format('Y-MM-DD'));

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
