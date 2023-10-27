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
$query_person = "SELECT o.name,o.doctorcode,o.hospital_department_id FROM opduser o left outer join doctor d on d.code=o.doctorcode WHERE d.active='Y' order by name";
$person = mysql_query($query_person, $hos) or die(mysql_error());
$row_person = mysql_fetch_assoc($person);
$totalRows_person = mysql_num_rows($person);

mysql_select_db($database_hos, $hos);
$query_rs_drug = "SELECT icode,name,strength FROM s_drugitems WHERE istatus='Y' and name not like '%คิด%' and name not like '%ต่อ%' and icode like '1%' ORDER BY name ASC";
$rs_drug = mysql_query($query_rs_drug, $hos) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);

mysql_select_db($database_hos, $hos);
$query_type_error = "SELECT * FROM ".$database_kohrx.".kohrx_med_error_error_type where mr='Y' ORDER BY `order_type`ASC";
$type_error = mysql_query($query_type_error, $hos) or die(mysql_error());
$row_type_error = mysql_fetch_assoc($type_error);
$totalRows_type_error = mysql_num_rows($type_error);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Med. Reconcile Error Report</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
<?php //include('java_css_online.php'); ?> 
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
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
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.colVis.min.js"></script>

<!-- CSS -->
<link href="include/kohrx/css/kohrx.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.bootstrap4.min.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.1/css/select.bootstrap4.min.css"/>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css"/>
<link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-light-green.css"/>	

<script>
$(document).ready(function() {
    $('#btn-clear').hide();
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
			var result = ''

	$('#icode').change(function(e){

    var List = new Array();
    $('#icode option:selected').each(function () {
       if ($(this).length) {
              var sel= $(this).val();
              
       }
       List.push("'"+sel+"'");
    });
    result = List.join(',');
        if(List!=""){
            $('#btn-clear').show();
        }
        else{
            $('#btn-clear').hide();
        }
    });
	
    $('#btn-clear').click(function(){
        $("#icode").val('default').selectpicker("refresh");
        $('#btn-clear').hide();
    });
								
    $('#search').click(function(){
		
				
             
			 $('#indicator').show();
             $("#displayDiv").load('dispen_med_reconcile_error_report_list.php?do=search&hn='+$('#hn').val()+'&datestart='+encodeURIComponent($('#datestart').val())+'&dateend='+encodeURIComponent($('#dateend').val())+'&error_type='+$('#error_type').val()+'&error_cause='+$('#error_cause').val()+'&error_subtype='+$('#error_subtype').val()+'&consult='+$('#consult').val()+'&icode='+encodeURIComponent(result)+'&drug_type='+$('#drug_type').val()+'&reporter='+$('#reporter').val(), function(responseTxt, statusTxt, xhr){
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();
						$('#print').show();
                    
					if(statusTxt == "error")
                      alert("Error: " + xhr.status + ": " + xhr.statusText);
                  });
    });



        //เลือกความคลาดเคลื่อนหลัก
        $("#error_type").change(function()
            {
                $('#error_subtype').html('<select><option value="">= เลือกประเภทย่อย 2 =</option></select>');

                if($(this).val()!=""&&$('#error_cause').val()!=""){
                    $('#save').prop('disabled',false);
                }
                else {
                    $('#save').prop('disabled',true);
                }

            var id=$(this).val();
            var dataString = 'id='+ id+'&type=main&mr=Y';
            $("#error_subtype").val("");
            $.ajax
            ({
            type: "POST",
            url: "mederror/get_error_type.php",
            data: dataString,
            cache: false,
            success: function(html)
            {
            $("#error_cause").html(html);
            } 
            });
        });
        //===============//
        //เลือกความคลาดเคลื่อนย่อย1
        $("#error_cause").change(function()
            {

                if($(this).val()!=""&&$('#error_type').val()!=""){
                    $('#save').prop('disabled',false);
                }
                else {
                    $('#save').prop('disabled',true);
                }


            var id=$(this).val();
            var dataString = 'id='+ id+'&type=sub&mr=Y';
            $.ajax
            ({
            type: "POST",
            url: "mederror/get_error_type.php",
            data: dataString,
            cache: false,
            success: function(html)
            {
            $("#error_subtype").html(html);
            } 
            });
        });
        //===============//
        //เลือกความคลาดเคลื่อนย่อย2
        $("#error_subtype").change(function(){
                $('#error_subtype_edit').hide();
                $('#error_subtype_open').show();

                if($(this).val()!=""){
                    $('#error_subtype_edit_btn').show();
                    $('#error_subtype_delete_btn').show();
                }
                else {
                    $('#error_subtype_edit_btn').hide();		
                    $('#error_subtype_delete_btn').hide();
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
        <a class="navbar-brand mx-auto" href="#"><i class="fas fa-chalkboard-teacher font20"></i>&ensp;ระบบรายงานบันทึกความคลาดเคลื่อนทางยา Medication Reconcile</a>
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
   			<label class="col-form-label col-form-label-sm col-sm-auto">HN</label>
            <div class="col-sm-auto">
            <input name="hn" type="text" class="form-control" id="hn" />
            </div>         
    </div> 
      <div class="row mt-2">
		  <label class="col-form-label col-form-label-sm col-sm-2">ยาที่ error&nbsp;</label>
            <div class="col-sm-10">
			<select class="selectpicker form-control font12" id="icode" multiple data-live-search="true" title="กรุณาเลือกยาที่ต้องการค้นหา">
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
			<input id="icode" type="hidden"/>	
            </div>            
		</div>

		<div class="row mt-2">
             <label class="col-form-label col-form-label-sm col-sm-2">ประเภท error</label>   
		<div class="col-sm-auto">
            <select class="form-control form-control-sm"  id="error_type" style="font-size: 14px;" data-live-search="true" title="กรุณาเลือกประเภทความคลาดเคลื่อน">
                		<option value="">-- กรุณาเลือก --</option>	
                        <?php do { ?>
                            <option value="<?php echo $row_type_error['id']; ?>"><?php echo $row_type_error['type_thai']; ?></option>
                        <?php
                        } while ($row_type_error = mysql_fetch_assoc($type_error));
                          $rows = mysql_num_rows($type_error);
                          if($rows > 0) {
                              mysql_data_seek($type_error, 0);
                             $row_type_error = mysql_fetch_assoc($type_error);
                          }
                        ?>

                </select>    
        </div>        
        <div class="col-sm-auto">
        <select id="error_cause" name="error_cause" class=" form-control form-control-sm" style="font-size: 14px; max-width: 300px;" title="กรุณาเลือกประเภทย่อย 1">
                <option value="">= เลือกประเภทย่อย 1 =</option>
            </select>
    </div>			

        <div class="col-sm-auto">
            <select id="error_subtype" name="error_subtype" class="form-control form-control-sm" style="font-size: 14px;">
                <option value="">= เลือกประเภทย่อย 2 =</option>
            </select>
    </div>			
		</div>

		<div class="row mt-2">
             <label class="col-form-label col-form-label-sm col-sm-2">consult</label>   
                <div class="col-sm-auto">
                  <select id="consult" class="form-control">
                 <option value="">------เลือกทั้งหมด-----</option>               
				<option value="0">ไม่ได้ consult</option>
				<option value="1">consult แล้วยืนยันไม่ปรับเปลี่ยน</option>
				<option value="2">consult แล้วยืนยันปรับเปลี่ยน</option>
			</select>
                    </select>                    
            </div>
             <label class="col-form-label col-form-label-sm col-sm-auto">ประเภทยา</label>   
            <div class="col-sm-auto">
			<select id="drug_type" class="form-control">
				<option value="">ทั้งหมด</option>
				<option value="1">Admit</option>
				<option value="2">D/C</option>
			</select>				
			</div>		
        </div>

	<div class="row mt-2">
             <label class="col-form-label col-form-label-sm col-sm-2">ผู้บันทึก</label>
                <div class="col-sm-auto">
                  <select name="reporter" class="selectpicker form-control" data-live-search="true" id="reporter">
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
              <div class="col-sm-auto" ><button class="btn btn-primary" id="search">ค้นหา</button></div>
        </div>

                    
</div>
</div>

<div id="indicator"  align="center" style="position:absolute; display:none; z-index:1000;padding:0px;"><img src="images/indicator.gif" hspace="10" align="absmiddle" /></div><div id="displayDiv" class="p-2">&nbsp;</div>
</div>
<?php include('include/datepicker/datepickerrang.php'); ?>
</body>
</html>
<?php
mysql_free_result($person);

mysql_free_result($rs_setting);

mysql_free_result($rs_drug);

mysql_free_result($type_error);

?>
