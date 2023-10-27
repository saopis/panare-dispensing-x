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

mysql_free_result($rs_setting);

mysql_select_db($database_hos, $hos);
$query_rs_drugusage = "select drugusage,shortlist from drugusage where status='Y'";
$rs_drugusage = mysql_query($query_rs_drugusage, $hos) or die(mysql_error());
$row_rs_drugusage = mysql_fetch_assoc($rs_drugusage);
$totalRows_rs_drugusage = mysql_num_rows($rs_drugusage);

mysql_select_db($database_hos, $hos);
$query_rs_drug = "select concat(name,' ',strength) as drugname,icode from drugitems where istatus='Y' and name not like '%คิดต่อ%'  order by name ASC";
$rs_drug = mysql_query($query_rs_drug, $hos) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>รายงาน Drug Log</title>
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
		<?php 
		$url="dispen_log_search_report_list.php";
		$para[1]="datestart";
		$para[2]="dateend";
		$para[3]="hn";
		$para[4]="";
		$para[5]="drug";
		$para[6]="drugusage";
		$para[7]="logs";
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
        <a class="navbar-brand mx-auto" href="#"><i class="fas fa-chalkboard-teacher font20"></i>&ensp;รายงาน Drug Log</a>
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
		</div>
			
		<div class="row mt-2">
   			<label class="col-form-label col-form-label-sm col-sm-2">HN</label>
            <div class="col-sm-auto">
            <input name="hn" type="search" class="form-control" id="hn" />
            </div> 
		</div>
            <div class="row mt-2">
            <label class="col-form-label col-form-label-sm col-sm-2">เลือกตัวยา</label>
            <div class="col-sm-auto">
                <select name="drug" id="drug" required data-live-search="true" class="form-control selectpicker">
					<option value="">ทั้งหมด</option>
					
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
            <label class="col-form-label col-form-label-sm col-sm-2">ประเภท log</label>
            <div class="col-sm-auto">
			<select name="logs" class="form-control" id="logs">
            <option value="">ทั้งหมด</option>    
            <option value="1">ยาใหม่</option>    
            <option value="2">หยุดยา</option>    
            <option value="3">เปลี่ยนวิธีใช้(แต่ไม่ทราบว่าปรับขึ้นหรือลง)</option>    
            <option value="4">ปรับวิธีใช้เพิ่มขึ้น</option>    
            <option value="5">ปรับวิธีใช้ลดลง</option>    
          </select>
			</div>
		</div>
		<div class="row mt-2">
<div class="col-sm-auto" ><button class="btn btn-primary" id="search">ค้นหา</button></div>   			    
    </div> 


                   
</div>
</div>

<div id="indicator"  align="center" style="position:absolute; display:none; z-index:1000;padding:0px;"><img src="images/indicator.gif" hspace="10" align="absmiddle" /></div><div id="displayDiv" class="p-2">&nbsp;</div>
</div>
<?php include('include/datepicker/datepickerrang.php'); ?>

	</div>
</body>
</html>
<?php

mysql_free_result($rs_drug);
mysql_free_result($rs_drugusage);

?>
