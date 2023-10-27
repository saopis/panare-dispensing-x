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

$date=date('d/m/').(date('Y')+543);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>แบบฟอร์มรายงานรับจ่ายยา favipiravir</title>
    
<link href="include/kohrx/css/kohrx.css" rel="stylesheet" type="text/css" />

<?php include('java_css_file.php'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/select/1.3.1/js/dataTables.select.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js"></script>
<script src="https://kit.fontawesome.com/1ed6ef1358.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.colVis.min.js"></script>
<!-- input Mask -->  
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js" ></script>

<script type="text/javascript">
$(document).ready(function() {

	$('#print').hide();
	$('#down').hide();
    var Digital=new Date()
    var hours=Digital.getHours()
    var minutes=Digital.getMinutes()

			const timenow = Date().slice(16,21);
            $('#time1').val('00:00');
            $('#time2').val(timenow);
            $('#date1').val('<?php echo $date; ?>');
            $('#date2').val('<?php echo $date; ?>');
        
            $('#save').prop('disabled',true);
			$("#time1").inputmask({"mask": "99:99"});
			$("#time2").inputmask({"mask": "99:99"});
            $("#time1").keypress(function(event) {
                return /\d/.test(String.fromCharCode(event.keyCode));
            });
            $("#time2").keypress(function(event) {
                return /\d/.test(String.fromCharCode(event.keyCode));
            });
            $('#time1').keyup(function(){
                   // regular expression to match required date format
                    re = /^(([0-1]?[0-9])|([2][0-3])):([0-5]?[0-9])(:([0-5]?[0-9]))?$/;
                    if(form1.receive_time.value == '' || !form1.receive_time.value.match(re)||form1.dispen_time.value == '' || !form1.dispen_time.value.match(re)){
                        $('#save').prop('disabled',true);                       
                    }
                    else{ $('#save').prop('disabled',false);}
            });
    
    
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
		<?php 
		$url="dispen_allergy_check_report_list.php";
		$para[1]="datestart";
		$para[2]="dateend";
		$para[3]="hn";
		$para[4]="respondent";
		$para[5]="answer";
		$para[6]="";
		$para[7]="";
		$para[8]="";
		$para[9]="";
		$para[10]="";
		
		$condition="";
		for($p=1;$p<=10;$p++){
			if($para[$p]!=""){
			$condition.="&".$para[$p]."='+encodeURIComponent($('#".$para[$p]."').val())+'";
			}
		}
		?>
	
    $('#search').click(function(){

                    $('#indicator').show();
        
                 var date1=$('#date1').val(),
                     date2=$('#date2').val(),
                     time1=$('#time1').val(),
                     time2=$('#time2').val(),
                     remain=$('#remain').val(),
                     depart=$('#depart').val()
                     
              $.ajax({
              type:"POST",
              url: "report_favipiravir_result.php",
              data: {date1:date1,
                     date2:date2,
                     time1:time1,
                     time2:time2,
                     remain:remain,
					 depart:depart
                     ,function:'submit'},	  
              success: function(data){
                  $('#indicator').hide();
                  $('#result').html(data);
              }
            });
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
	input[type=search]::-webkit-search-cancel-button {
    -webkit-appearance: searchfield-cancel-button;
	cursor: pointer;

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
        <a class="navbar-brand mx-auto" href="#"><i class="fas fa-chalkboard-teacher font20"></i>&ensp;ระบบรายงาน Favipiravir</a>
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
            <div class="col-sm-auto">
                <div class="form-row">
                    <div class="form-group col-auto">
                        <label>จากวันที่</label>
                        <input type="text" class="form-control form-control-sm" name="date1" id="date1" data-date-language="th-th" />
                    </div>    
                    <div class="form-group col-auto">
                        <label>เวลา</label>
                          <input type="text" id="time1" name="time1" class="form-control form-control-sm" style="padding: 3px;" />  
                        
                    </div>  
                    <div class="form-group col-auto">
                        <label>ถึงวันที่</label>
                        <input type="text" class="form-control form-control-sm" name="date2" id="date2" data-date-language="th-th" />
                    </div>    
                    <div class="form-group col-auto">
                        <label>เวลา</label>
                          <input type="text" id="time2" name="time2" class="form-control form-control-sm" style="padding: 3px;" />  
                        
                    </div>  
                    <div class="form-group col-auto">
                        <label>คงเหลือ</label>
                          <input type="text" id="remain" name="remain" class="form-control form-control-sm" value="0"  />  
                        
                    </div>  
                    <div class="form-group col-auto">
                        <label>ประเภทผู้ป่วย</label>
                          <select id="depart" name="depart" class="form-control form-control-sm">
							  <option value="OPD">OPD</option>
							  <option value="IPD" selected>IPD</option>
							  </select>	  
                        
                    </div>  					
                    <div class="form-group col-auto">
                        <button class="btn btn-primary btn-sm" id="search" name="search" style="margin-top: 32px;">ค้นหา</button>                        
                    </div>                       
                </div>    
                
            </div>
		</div>


                   
</div>
</div>

<div id="indicator"  align="center" style="position:absolute; display:none; z-index:1000;padding:0px;"><img src="images/indicator.gif" hspace="10" align="absmiddle" /></div><div id="result" class="p-2">&nbsp;</div>
</div>
	<!-- container-fluid -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="include/datepicker/js/bootstrap-datepicker-thai.js"></script>
<script src="include/datepicker/js/locales/bootstrap-datepicker.th.js"></script>    
<link rel="stylesheet" type="text/css" href="include/datepicker/css/datepicker.css" />

<script type="text/javascript">
$(document).ready(function(){
    $("#date1").datepicker( {
    format: "dd/mm/yyyy",
    startView: "days", 
    minViewMode: "days"
    });
    $("#date2").datepicker( {
    format: "dd/mm/yyyy",
    startView: "days", 
    minViewMode: "days"
    });    

});
</script>

</body>
</html>
