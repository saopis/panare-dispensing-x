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

mysql_select_db($database_hos, $hos);
$query_rs_drug = "select concat(name,strength) as drugname,icode from drugitems where istatus='Y' and icode not in (select icode from ".$database_kohrx.".kohrx_had) order by name";
$rs_drug = mysql_query($query_rs_drug, $hos) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>รายการยา High Alert Drug</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<?php include('java_css_file.php'); ?>
<script type="text/javascript">
$(document).ready(function(){

    
	$('#save').click(function(){
                $('#indicator').show();
                $("#displayDiv").load('drug_setting_had_list.php?action=save&drug='+$('#drug').val(), function(responseTxt, statusTxt, xhr){
                    
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();
                    if(statusTxt == "error")
                       alert("Error: " + xhr.status + ": " + xhr.statusText);    
               });		
	});
	
				$('#indicator').show();
                $("#displayDiv").load('drug_setting_had_list.php', function(responseTxt, statusTxt, xhr){
                    
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();
                    if(statusTxt == "error")
                       alert("Error: " + xhr.status + ": " + xhr.statusText);    
               });
});
	
function alertload1(url,w,h,str,queue){
	 $.colorbox({width:w,height:h, iframe:true, href:url,onOpen : function () {$('html').css('overflowY','hidden');},onCleanup :function(){
$('html').css('overflowY','auto');}
,onClosed:function(){formSubmits('load','displayDiv','indicator');}});

}

function modal_close(){
				$('#indicator').show();
                $("#displayDiv").load('drug_setting_had_list.php', function(responseTxt, statusTxt, xhr){
                    
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();
                    if(statusTxt == "error")
                       alert("Error: " + xhr.status + ": " + xhr.statusText);    
               });					  
			}
function had_drug_delete(icode){

                $('#indicator').show();
                $("#displayDiv").load('drug_setting_had_list.php?action=delete&icode='+icode, function(responseTxt, statusTxt, xhr){
                    
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();
                    if(statusTxt == "error")
                       alert("Error: " + xhr.status + ": " + xhr.statusText);    
               });		
}    
</script>

</head>

<body >
<div class="p-3">
	<div class="card">
		<div class="card-header">ตั้งค่า High Alert Drug</div>
		<div class="card-body">
			<div class="form-group row">
				<div class="col-sm-auto">
				  <select name="drug" id="drug" class="form-control">
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
				<div class="col-sm-auto">
					<button name="save" id="save" class="btn btn-primary" >บันทึก</button>
				</div>
				
			</div>
		</div>
	</div>
</div>

<div id="indicator"  align="center" style="position:absolute; display:none; z-index:1000;padding:0px;"><img src="images/indicator.gif" hspace="10" align="absmiddle" /></div><div id="displayDiv">&nbsp;</div></td>
<!-- The Modal list-->
  <div class="modal fade" id="myModal">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header bg-danger">
          <h5 class="modal-title text-white " id="modal-title">สาเหตุที่เลือกใช้</h5>
          <button type="button" class="close" data-dismiss="modal" onClick="modal_close();">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body" id="modal-body" style="margin-top:0px;overflow:scroll;overflow-x:hidden;overflow-y:auto; height:60vh; z-index: 2"></div>
        
        
      </div>
    </div>
  </div>
</body>
</html>
<?php
mysql_free_result($rs_drug);
?>
