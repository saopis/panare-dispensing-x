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
$date=date('d/m/').(date('Y')+543);

mysql_select_db($database_hos, $hos);
$query_rs_drug = "select concat(name,' ',strength) as drugname,icode from drugitems where istatus='Y'  order by name ASC";
$rs_drug = mysql_query($query_rs_drug, $hos) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);

mysql_select_db($database_hos, $hos);
$query_rs_drugusage = "select drugusage,shortlist from drugusage where status='Y'";
$rs_drugusage = mysql_query($query_rs_drugusage, $hos) or die(mysql_error());
$row_rs_drugusage = mysql_fetch_assoc($rs_drugusage);
$totalRows_rs_drugusage = mysql_num_rows($rs_drugusage);

mysql_select_db($database_hos, $hos);
$query_rs_room = "select * from ".$database_kohrx.".kohrx_queue_caller_room where room_name !=''";
$rs_room = mysql_query($query_rs_room, $hos) or die(mysql_error());
$row_rs_room = mysql_fetch_assoc($rs_room);
$totalRows_rs_room = mysql_num_rows($rs_room);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>รายงานการใช้ยารายตัว</title>
<link rel="shortcut icon" href="images/favicon2.ico" />
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
	
	<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="include/select/css/bootstrap-select.min.css">
<!-- Latest compiled and minified JavaScript -->
<script src="include/select/js/bootstrap-select.min.js"></script>
<!-- (Optional) Latest compiled and minified JavaScript translation files -->
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
		$url="dispen_drug_count_report_list.php";
		$para[1]="date1";
		$para[2]="date2";
		$para[3]="hn";
		$para[4]="pttype";
		$para[5]="drug";
		$para[6]="room";
		$para[7]="drugusage";
		$para[8]="time1";
		$para[9]="time2";
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
                        $("#displayDiv").load('<?php echo $url; ?>?do=search<?php echo $condition; ?>', function(responseTxt, statusTxt, xhr){
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();
						$('#print').show();
                    
					if(statusTxt == "error")
                      alert("Error: " + xhr.status + ": " + xhr.statusText);
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

<link href="include/kohrx/css/kohrx.css" rel="stylesheet" type="text/css" />
</head>

<body>
<nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <div class="navbar-collapse collapse w-100 order-1 order-md-0 dual-collapse2">
        
    </div>
    <div class="mx-auto order-0">
        <a class="navbar-brand mx-auto" href="#"><i class="fas fa-chalkboard-teacher font20"></i>&ensp;รายงานการใช้ยาราย item</a>
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
                </div> 
                
            </div>
        

        
    </div> 
            <div class="row mt-2">
            <label class="col-form-label col-form-label-sm col-sm-2">เลือกตัวยา</label>
            <div class="col-sm-auto">
                <select name="drug" id="drug" required data-live-search="true" class="form-control selectpicker">
                            <?php
                    do {  
                    ?>
                            <option value="<?php echo $row_rs_drug['icode']?>"><?php echo $row_rs_drug['drugname']?></option>
                            <?php
                    } while ($row_rs_drug = mysql_fetch_assoc($rs_drug));
                      $rows = mysql_num_rows($rs_drug);
                      if($rows > 0) {
                          mysql_data_seek($rs_drug, 0);
                          $row_rs_drug = mysql_fetch_assoc($rs_drug);
                      }
                    ?>
                  </select>   
            </div>
        
    </div>  
            <div class="row mt-2">
            <label class="col-form-label col-form-label-sm col-sm-2">วิธีใช้</label>
            <div class="col-sm-auto">
                <select name="drugusage" id="drugusage" data-live-search="true" class="form-control selectpicker">
					<option value="">ทั้งหมด</option>
                            <?php
                    do {  
                    ?>
                            <option value="<?php echo $row_rs_drugusage['drugusage']?>"><?php echo $row_rs_drugusage['shortlist']?></option>
                            <?php
                    } while ($row_rs_drugusage = mysql_fetch_assoc($rs_drugusage));
                      $rows = mysql_num_rows($rs_drugusage);
                      if($rows > 0) {
                          mysql_data_seek($rs_drugusage, 0);
                          $row_rs_drugusage = mysql_fetch_assoc($rs_drugusage);
                      }
                    ?>
                  </select>   
            </div>
        
    </div>  		
            <div class="row mt-2">
            <label class="col-form-label col-form-label-sm col-sm-2">ประเภทผู้ป่วย</label>
            <div class="col-sm-auto">
                <select name="pttype" id="pttype" class="form-control">
                        <option value="">ทั้งหมด</option>
                        <option value="1">OPD</option>
                        <option value="2">IPD</option>
                </select>
            </div>
            <label class="col-form-label col-form-label-sm col-sm-auto">HN</label>
            <div class="col-sm-auto">
            <input name="hn" type="text" class="form-control" required id="hn" />
            </div>     
        
    </div> 
            <div class="row mt-2">
            <label class="col-form-label col-form-label-sm col-sm-2">เลือกห้อง</label>
            <div class="col-sm-auto">
                <select name="room" id="room" class="form-control">
                  <option value="">== เลือกห้อง ==</option>
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
            <div class="col-sm-auto">
                <button class="btn btn-primary" id="search">ค้นหา</button>
            </div>
        
            </div> 
        
    </div>
    </div>
    
<div id="indicator"  align="center" style="position:absolute; display:none; z-index:1000;padding:0px;"><img src="images/indicator.gif" hspace="10" align="absmiddle" /></div><div id="displayDiv" class="p-2">&nbsp;</div>
    
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
<?php
mysql_free_result($rs_drug);
mysql_free_result($rs_drugusage);

mysql_free_result($rs_room);

//mysql_free_result($rs_search);
?>
