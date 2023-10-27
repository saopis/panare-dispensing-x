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
$query_drp_cat = "select * from ".$database_kohrx.".kohrx_drp_category";
$drp_cat = mysql_query($query_drp_cat, $hos) or die(mysql_error());
$row_drp_cat = mysql_fetch_assoc($drp_cat);
$totalRows_drp_cat = mysql_num_rows($drp_cat);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Drug Related Problem Report</title>
<?php include('java_css_online.php'); ?> 
<!-- auto complete -->    
<script src="include/jquery/js/jquery-ui.js"></script>
<link rel="stylesheet" href="include/jquery/css/jquery-ui.css" type="text/css" />
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
    
//search
		<?php 
		$url="dispen_kohrx_drp_report_list.php";
		$para[1]="datestart";
		$para[2]="dateend";
		$para[3]="hn";
		$para[4]="drug";
		$para[5]="cat";
		$para[6]="pttype";
		$para[7]="problem";
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


//auto complete รายการยา
	$('#drug').keyup(function(){
		if($('#drug').val()==""){
			$('#icode').val("");
		}
	});
        $( "#drug" ).autocomplete({ // ใช้งาน autocomplete กับ input text id=tags
			
			minLength: 0, // กำหนดค่าสำหรับค้นหาอย่างน้อยเป็น 0 สำหรับใช้กับปุ่ใแสดงทั้งหมด
            source: "drug_search.php", // กำหนดให้ใช้ค่าจากการค้นหาในฐานข้อมูล
            open:function(){
				 // เมื่อมีการแสดงรายการ autocomplete
                var valInput=$(this).val(); // ดึงค่าจาก text box id=tags มาเก็บที่ตัวแปร
                if(valInput!=""){ // ถ้าไม่ใช่ค่าว่าง
                    $(".ui-menu-item a").each(function(){ // วนลูปเรียกดูค่าทั้งหมดใน รายการ autocomplete
                        var matcher = new RegExp("("+valInput+")", "ig" ); // ตรวจสอบค่าที่ตรงกันในแต่ละรายการ กับคำค้นหา
                        var s=$(this).text();
                        var newText=s.replace(matcher, "<b>$1</b>");    //      แทนค่าที่ตรงกันเป็นตัวหนา
                        $(this).html(newText); // แสดงรายการ autocomplete หลังจากปรับรูปแบบแล้ว
                    }); 
                }
            },
            select: function( event, ui ) {
                $("#icode").val(ui.item.id);
            }
        });
//auto complete รายการยา
///////////////////////////

$("#drp_code").keyup(function()
{
var id=$(this).val();
var dataString = 'id='+ id;
$.ajax
({
type: "POST",
url: "drp_search.php",
data: dataString,
cache: false,
success: function(html)
{
$("#problem").val(html);
} 
});

});
/////////////////////////	
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
        <a class="navbar-brand mx-auto" href="#"><i class="fas fa-chalkboard-teacher font20"></i>&ensp;ระบบรายงานบันทึกปัญหาจากการใช้ยา (KOHRX DRP Report)</a>
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
        <div class="form-row">
		    <div class="form-group col-md-4">
            <label class="col-form-label-sm">เลือกช่วงวันที่</label>
                <div id="reportrange" class="form-control" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                  <i class="far fa-calendar-alt"></i>&nbsp;
                    <span></span> 
                  <i class="fas fa-sort-down"></i>
                </div>	

            <input name="datestart" type="hidden" id="datestart" value="" /><input name="dateend" type="hidden" id="dateend" value="" />
                
            </div>
		    <div class="form-group col-md-2">			
   			<label class="col-form-label-sm">HN</label>
            <input name="hn" type="text" class="form-control" id="hn" />
            </div>  
			 
			<div class="form-group col-md-6">
			 <label for="problem" class="col-form-label-sm">ประเด็นปัญหา</label>
			 <input name="problem" type="text" class=" form-control" id="problem" value="<?php echo $row_edit['title']; ?>" size="50" />     
			</div>
			
    </div> 

<div class="form-row">
	 <div class="form-group col-md-4">
		 <label class="col-form-label-sm">ยาที่เกี่ยวข้อง</label>
            <input id="drug" type="text" style="padding-left: 5px;" class="form-control" />
			<input id="icode" type="hidden"/>	
      </div>
	 <div class="form-group col-md-6">
     	<label for= "cat" class="col-form-label-sm">ประเภท DRP</label>
        <select name="cat" class=" form-control" id="cat">
        <option value="">--- ทั้งหมด ---</option>
        <?php
do {  
?>
            <option value="<?php echo $row_drp_cat['drp_cat']?>" <?php if (!(strcmp($row_drp_cat['drp_cat'], $row_edit['drp_cat']))) {echo "selected=\"selected\"";} ?>><?php echo $row_drp_cat['drp_cat']."=".$row_drp_cat['name_thai']."(".$row_drp_cat['name_eng'].")"; ?></option>
            <?php
} while ($row_drp_cat = mysql_fetch_assoc($drp_cat));
  $rows = mysql_num_rows($drp_cat);
  if($rows > 0) {
      mysql_data_seek($drp_cat, 0);
	  $row_drp_cat = mysql_fetch_assoc($drp_cat);
  }
?>
          </select>
          <input name="id" type="hidden" id="id" value="<?php echo $_GET['id']; ?>" />
          <input name="hn" type="hidden" id="hn" value="<?php echo $hn; ?>" />
        <input name="do" type="hidden" id="do" value="<?php echo $hn; ?>" />        
     </div>
	 <div class="form-group col-md-2">
	 <label for="pttype" class="col-form-label-sm">ประเภทผู้ป่วย</label>
    <select name="pttype" id="pttype" class="form-control">
    <option value="">--- ทั้งหมด ---</option>
    <option value="opd" <?php if (!(strcmp("opd", $pttype))) {echo "selected=\"selected\"";} ?>>OPD</option>
     <option value="ipd" <?php if (!(strcmp("ipd", $pttype))) {echo "selected=\"selected\"";} ?>>IPD</option>
	</select>
    </div>     
</div>		



                    
<div class="form-row">
              <div class="col-sm-auto" ><button class="btn btn-primary" id="search">ค้นหา</button></div>
</div>
</div>
</div>

<div id="indicator"  align="center" style="position:absolute; display:none; z-index:1000;padding:0px;"><img src="images/indicator.gif" hspace="10" align="absmiddle" /></div><div id="displayDiv" class="p-2">&nbsp;</div>
</div>
<?php include('include/datepicker/datepickerrang.php'); ?>

</script>
	</div>
</body>
</html>
<?php
mysql_free_result($drp_cat);

mysql_free_result($rs_setting);

?>
