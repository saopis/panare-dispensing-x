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

$today=date('Y-m-d');

mysql_select_db($database_hos, $hos);
$query_rs_drug = "select icode,name,strength from drugitems where istatus='Y' and name not like '%คิด%' and name not like '%ต่อ%'";
$rs_drug = mysql_query($query_rs_drug, $hos) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);mysql_select_db($database_hos, $hos);
$query_rs_drug = "SELECT icode,name,strength FROM drugitems WHERE istatus='Y' and name not like '%คิด%' and name not like '%ต่อ%' order by name ASC";
$rs_drug = mysql_query($query_rs_drug, $hos) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);
?>
<? include('include/function.php');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ระบบบันทึกยาคืน</title>
<?php include('java_css_online.php'); include('include/datepicker/datepickerrang.php'); include('include/datepicker/datepicker.php'); ?>
<script>

$(document).ready(function(){
					$('#indicator').show();
				
	                $("#displayDiv").load('service_drug_return_save.php?action=show&datestart='+encodeURIComponent($('#datestart').val())+'&dateend='+encodeURIComponent($('#dateend').val()), function(responseTxt, statusTxt, xhr){
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();
                    
					if(statusTxt == "error")
                      alert("Error: " + xhr.status + ": " + xhr.statusText);
                  });
	
		set_cal( $("#recdate") );

	$('#search').click(function(){
					$('#indicator').show();
				
	                $("#displayDiv").load('service_drug_return_save.php?action=show&datestart='+encodeURIComponent($('#datestart').val())+'&dateend='+encodeURIComponent($('#dateend').val()), function(responseTxt, statusTxt, xhr){
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();
                    
					if(statusTxt == "error")
                      alert("Error: " + xhr.status + ": " + xhr.statusText);
                  });
	
	});
});
    
function action_save(){
					$('#indicator').show();
				
	                $("#displayDiv").load('service_drug_return_save.php?action=save&icode='+$('#drugname').val()+'&among='+$('#among').val()+'&datestart='+encodeURIComponent($('#datestart').val())+'&dateend='+encodeURIComponent($('#dateend').val())+'&recdate='+encodeURIComponent($('#recdate').val()), function(responseTxt, statusTxt, xhr){
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();
                    	$('#drugname').val("");
						$('#among').val("");
					if(statusTxt == "error")
                      alert("Error: " + xhr.status + ": " + xhr.statusText);
                  });

}	

function action_delete(icode,recdate){
					$('#indicator').show();
				
	                $("#displayDiv").load('service_drug_return_save.php?action=delete&icode='+icode+'&recdate='+recdate+'&datestart='+encodeURIComponent($('#datestart').val())+'&dateend='+encodeURIComponent($('#dateend').val()), function(responseTxt, statusTxt, xhr){
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();
                    	$('#drugname').val("");
						$('#among').val("");
					if(statusTxt == "error")
                      alert("Error: " + xhr.status + ": " + xhr.statusText);
                  });

}	
	

// กดenter แล้ว เลื่อน cursor
document.onkeydown = chkEvent 
	function chkEvent(e) {
		var keycode;
		if (window.event) keycode = window.event.keyCode; //*** for IE ***//
		else if (e) keycode = e.which; //*** for Firefox ***//
		if(keycode==13)
		{
			return false;
		}
	}

    function setNextFocus(objId){
        if (event.keyCode == 13){
            var obj=document.getElementById(objId);
            if (obj){
                obj.focus();
            }
        }
    }





function drugtoicode(icode){
	$('#icode').val(icode);
	}


    </script>
<style type="text/css">
body {
	margin-top: 0px;
	margin-left: 0px;
	margin-right: 0px;
}
</style>
<style type="text/css">
table.sample {
	border-width: 1px;
	border-spacing: 0px;
	border-style: outset;
	border-color: gray;
	border-collapse: collapse;
}
table.sample th {
	border-width: 1px;
	padding: 1px;
	border-style: inset;
	border-color: gray;
	-moz-border-radius: ;
}
table.sample td {
	border-width: 1px;
	padding: 1px;
	border-style: inset;
	border-color: gray;
	-moz-border-radius: ;
}
</style>
</head>

<body >
<nav class="navbar navbar-dark bg-info text-white ">
  <!-- Navbar content -->
    <h4><i class="fas fa-clinic-medical" style="font-size: 20px;"></i>&ensp;ระบบบันทึกยาคืน</h4>
	    <div class="row" >
			   <div class="col-sm-auto">
                <div id="reportrange" class="form-control" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                  <i class="far fa-calendar-alt"></i>&nbsp;
                    <span></span> 
                  <i class="fas fa-sort-down"></i>
                </div>	
            <input name="datestart" type="hidden" id="datestart" value="" /><input name="dateend" type="hidden" id="dateend" value="" />
                
            </div>
			<div class="col-sm-auto">
				<button name="search" id="search"   class="btn btn-dark"  >ค้นหา</button> 
			</div>
			<div class="col-sm-1">
			</div>
</div>

</nav>


<div class="card m-3">
	<div class="card-body">
		<div class="form-group row">
			<div class="col-sm-auto">
				<input type="text" name="recdate" id="recdate" value="<?php echo date('d/m/').(date('Y')+543); ?>" class="form-control " style=" padding:2px; padding-left: 2px; padding-right:2px; width:95px; height:30px;" />				
			</div>
			<label class="col-form-label col-sm-auto">เลือกยา</label>
			<div class="col-sm-auto">
        <select name="drugname" id="drugname" class="form-control" onKeyDown="setNextFocus('among');">
          <option value="">-</option>
		  <?php
do {  
?>
          <option value="<?php echo $row_rs_drug['icode']?>"><?php echo  $row_rs_drug['name']." ".$row_rs_drug['strength'];
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
			<label class="col-form-label col-sm-auto">จำนวน</label>
			<div class="col-sm-auto">
      			<input name="among" class="form-control" type="text" id="among" size="5"  onKeyDown="setNextFocus('save');" />			
			</div>
			<div class="col-sm-auto">
				<button class="btn btn-success" id="save" onClick="action_save();">บันทึก</button>
			</div>
			
		</div>
	</div>
</div>
<!--indicator-->
<div id="indicator" align="center" class="spinner">
<div class="spinner-border" style="width: 5rem; height: 5rem;" role="status"></div>

  <span class="sr-only">Loading...</span>
</div>
<div class="p-3" id="displayDiv"></div>
</body>
</html><?php
mysql_free_result($rs_drug);
?>
