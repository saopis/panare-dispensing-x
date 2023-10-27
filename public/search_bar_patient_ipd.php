<?php date_default_timezone_set("Asia/Bangkok"); ?>
<?php require_once('Connections/hos.php'); ?>
<?php 
mysql_select_db($database_hos, $hos);
$query_ward = "select * from ward";
$ward = mysql_query($query_ward, $hos) or die(mysql_error());
$row_ward = mysql_fetch_assoc($ward);
$totalRows_ward = mysql_num_rows($ward);
?>
<?php 
include('include/function.php');
$today=sprintf("%02d", date('d'))."/".sprintf("%02d", date('m'))."/".(date('Y')+543);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php include('java_css_file.php'); ?>
<script type="text/javascript" src="include/datepicker/js/moment.min.js"></script>
<script type="text/javascript" src="include/datepicker/js/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="include/datepicker/css/daterangepicker.css" />
<script>
$(document).ready(function(){
	   $('#indicator').show();
        getData();
        $('#daterang').hide();
    
        $('#ipd_search').click(function(){
            	   $('#indicator').show();

            if($('#pttype')==1){
                $("#displayDiv").load('search_bar_patient_ipd_result.php?hn='+encodeURIComponent($('#hn_search').val())+'&an='+$('#an_search').val()+'&pttype='+$('#pttype').val()+'&ward='+$('#ward').val(), function(responseTxt, statusTxt, xhr){
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();
                    
				    if(statusTxt == "error")
                      alert("Error: " + xhr.status + ": " + xhr.statusText);
                  });
                
            }
            else{
                $("#displayDiv").load('search_bar_patient_ipd_result.php?hn='+encodeURIComponent($('#hn_search').val())+'&an='+$('#an_search').val()+'&pttype='+$('#pttype').val()+'&datestart='+encodeURIComponent($('#datestart').val())+'&dateend='+encodeURIComponent($('#dateend').val())+'&ward='+$('#ward').val(), function(responseTxt, statusTxt, xhr){
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();
                    
				    if(statusTxt == "error")
                      alert("Error: " + xhr.status + ": " + xhr.statusText);
                  });
                
            }
        });
    
        $('#pttype').change(function(){
            var pttypeval=$('#pttype').val();
            if(pttypeval!="1"){
                $('#daterang').show();
            }
            else{
                $('#daterang').hide();
            }
        });

    $('#an_search').keyup(function(){
                $('#hn_search').val("");
                $("#displayDiv").load('search_bar_patient_ipd_result.php?an='+$('#an_search').val()+'&pttype='+$('#pttype').val()+'&datestart='+encodeURIComponent($('#datestart').val())+'&dateend='+encodeURIComponent($('#dateend').val())+'&ward='+$('#ward').val(), function(responseTxt, statusTxt, xhr){
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();
                    
				    if(statusTxt == "error")
                      alert("Error: " + xhr.status + ": " + xhr.statusText);
                  });        
    });
    $('#hn_search').keyup(function(){
                $('#an_search').val("");
                $("#displayDiv").load('search_bar_patient_ipd_result.php?hn='+encodeURIComponent($('#hn_search').val())+'&pttype='+$('#pttype').val()+'&datestart='+encodeURIComponent($('#datestart').val())+'&dateend='+encodeURIComponent($('#dateend').val())+'&ward='+$('#ward').val(), function(responseTxt, statusTxt, xhr){
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();
                    
				    if(statusTxt == "error")
                      alert("Error: " + xhr.status + ": " + xhr.statusText);
                  });  
	
    }); 
	
	$("#an_search").keypress(function(event) {
  			return /\d/.test(String.fromCharCode(event.keyCode));
		});	
	

});
	
function formSubmit(displayDiv,indicator) {
	var URL = "patient_search_list_ipd.php";		
	var data = getFormData("form1");
	ajaxLoad('post', URL, data, displayDiv,indicator);
	var e = document.getElementById(indicator);
	e.style.display = 'block';
	}
	
function hnselect(hn)
	{	
	var hnsel = self.opener.document.getElementById("ipd");
	hnsel.value=hn;
	window.close();
	}
function getData(){
	       $("#displayDiv").load('search_bar_patient_ipd_result.php?hn='+encodeURIComponent($('#hn_search').val())+'&an='+$('#an_search').val()+'&pttype='+$('#pttype').val()+'&date='+encodeURIComponent($('#date1').val()), function(responseTxt, statusTxt, xhr){
              if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();
                    
				if(statusTxt == "error")
                      alert("Error: " + xhr.status + ": " + xhr.statusText);
                  });

}
</script>
<style type="text/css">
body {
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
</head>

<body onload="javascript:document.getElementById('search').focus();">
<nav class="navbar navbar-expand-lg navbar-info bg-info">
  <a class="navbar-brand text-white" href="#"><i class="fas fa-fingerprint font20"></i>&ensp;ค้นหผู้ป่วยใน</a>
</nav>
<div style="padding:5px; background-color: #EFEFEF" >
<form class="form-inline">
	<div class="col-sm-auto">
	<input placeholder="ชื่อ/HN" type="text" id="hn_search" style="width: 80px;" class="form-control form-control-sm"  /> 
	</div>
	<div class="col-sm-auto">
	      <input placeholder="AN" type="text" id="an_search"  style="width: 90px;"  class="form-control form-control-sm"  /> 
	</div>
	<div class="col-sm-auto">
      <select name="pttype" id="pttype" class="form-control form-control-sm">
        <option value="1">ผู้ป่วยที่ Admit ปัจจุบัน</option>
        <option value="2">ผู้ป่วย D/C ในวันที่</option>
        <option value="3">ผู้ป่วย Admit ในวันที่</option>
      </select>
	</div>
           <div class="col-sm-auto" id="daterang">

                <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%; height: 32px;">
                  <i class="far fa-calendar-alt"></i>&nbsp;
                    <span></span> 
                  <i class="fas fa-sort-down"></i>
                </div>			
		
   	  </div>
            <input name="datestart" type="hidden" id="datestart" value="" /><input name="dateend" type="hidden" id="dateend" value="" />
	<div class="col-sm-auto">
		<select class="form-control form-control-sm" id="ward" style="width: 100px;">
		<option value="">ทั้งหมด</option>
		<?php do {  ?>
		<option value="<?php echo $row_ward['ward']; ?>"><?php echo $row_ward['name']; ?></option>
		<?php }while($row_ward = mysql_fetch_assoc($ward)); ?>
		</select>
	</div>

	<div class="col-sm-auto">
      <input type="button" name="ipd_search" id="ipd_search" class="btn btn-secondary" value="ค้นหา" />
	</div>
</form>
</div>
<div id="indicator"  align="center" style="position:absolute; display:none; z-index:1000;padding:0px;"> <img src="images/indicator.gif" hspace="10" align="absmiddle" />&nbsp;</div><div id="displayDiv" >&nbsp;</div>
<?php include('include/datepicker/datepickerrang.php'); ?>

</body>
</html>
<?php mysql_free_result($ward); ?>