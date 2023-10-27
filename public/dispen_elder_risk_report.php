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
$query_rs_drug = "SELECT icode,name,strength FROM s_drugitems WHERE istatus='Y' and name not like '%คิด%' and name not like '%ต่อ%' and icode like '1%' ORDER BY name ASC";
$rs_drug = mysql_query($query_rs_drug, $hos) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Drug Elder Risk Report</title>
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
		<?php 
		$para[1]="datestart";
		$para[2]="dateend";
		$para[3]="icode";
		$para[4]="severity";
		$para[5]="consult";
		$para[6]="hn";
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
                        $("#displayDiv").load('dispen_elder_risk_report_list.php?do=search<?php echo $condition; ?>', function(responseTxt, statusTxt, xhr){
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
        <a class="navbar-brand mx-auto" href="#"><i class="fas fa-chalkboard-teacher font20"></i>&ensp;ระบบรายงานอุบัติการณ์ยาที่ต้องระวังในผู้ป่วยสูงอายุ</a>
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
            <input name="hn" type="text" class="form-control" id="hn" />
            </div> 
		</div>
		<div class="row mt-2">
            <label class="col-form-label col-form-label-sm col-sm-2">เลือกยา</label>
            <div class="col-sm-auto">
		<select name="icode" id="icode" class="form-control">
              <option value="">-</option>
              <?php
do {  
?>
              <option value="<?php echo $row_rs_drug['icode']?>" <?php if (!(strcmp($row_rs_drug['icode'], $icode))) {echo "selected=\"selected\"";} ?>><?php echo  $row_rs_drug['name']." ".$row_rs_drug['strength'];
?></option>
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
            <label class="col-form-label col-form-label-sm col-sm-2">ระดับความรุนแรง</label>
            <div class="col-sm-auto">
				<select name="severity" id="severity" class="form-control">
				<option value="">== ทั้งหมด ==</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				</select>				
			</div>
		</div>
		<div class="row mt-2">
            <label class="col-form-label col-form-label-sm col-sm-2">ผลการ consult</label>
            <div class="col-sm-auto">
				<select name="consult" id="consult" class="form-control" >
							<option value="" >=== ทั้งหมด ===</option>
							<option value="1">แพทย์และเภสัชกรสั่งและจ่ายยาระยะสั้น</option>
							<option value="2">เลี่ยงไปใช้ทางเลือกอื่น</option>
							<option value="3">แพทย์และเภสัชกรสั่งและจ่ายยาโดยไม่มีการเปลี่ยนแปลงคำสั่งการใช้ยาแต่มีนัดติดตามดูอาการ</option>
							<option value="4">แพทย์และเภสัชกรสั่งและจ่ายยาโดยไม่มีการเปลี่ยนแปลงคำสั่งการใช้ยาและไม่นัดติดตามดูอาการ</option>
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

mysql_free_result($rs_setting);

mysql_free_result($rs_drug);

?>
