<?php ob_start();?>
<?php session_start();?>
<?php require_once('Connections/hos.php'); ?>
<?php require('include/get_channel.php'); ?>
<?php include('include/function.php'); ?>
<?php 
$vstdate=$_GET['vstdate'];
mysql_select_db($database_hos, $hos);
$query_rs_vn = "select vstdate,vn,hn from vn_stat where vn='".$_GET['vn']."'";
$rs_vn = mysql_query($query_rs_vn, $hos) or die(mysql_error());
$row_rs_vn = mysql_fetch_assoc($rs_vn);
$totalRows_rs_vn = mysql_num_rows($rs_vn);

//===== setting ==========//
mysql_select_db($database_hos, $hos);
$query_rs_setting = "select * from ".$database_kohrx.".kohrx_dispensing_setting order by id ASC";
$rs_setting = mysql_query($query_rs_setting, $hos) or die(mysql_error());
$row_rs_setting = mysql_fetch_assoc($rs_setting);
$totalRows_rs_setting = mysql_num_rows($rs_setting);

$i=0; do{ $i++;
$row_setting[$i]=$row_rs_setting['value'];} 
while($row_rs_setting = mysql_fetch_assoc($rs_setting));

mysql_free_result($rs_setting);

//===== setting ==========//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Easy Dispensing Med.Reconcile</title>
<?php include('java_css_file.php'); ?>
<?php include('include/datepicker/datepicker.php'); ?>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-light-blue.css">

<script>
$(document).ready(function() {
    $('#hn').bind('keyup', function(e) {
        if(e.which == '13'){ //enter
        //ใส่ 0 หน้า HN
        $('#hn').val(leftPad($('#hn').val(),<?php echo $row_setting[24]; ?>));        
        //load หน้า
                    $('#indicator').show();            
                
                
                $("#main-div").load('med_reconcile_detail.php?hn='+$('#hn').val()+'&vstdate='+encodeURIComponent($('#vstdate').val()), function(responseTxt, statusTxt, xhr){
                    
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();
                    if(statusTxt == "error")
                        alert("Error: " + xhr.status + ": " + xhr.statusText);    
                    
                                       
                        ///////////////////////////// 
                });
            
        }
    });   

	set_cal( $("#vstdate") );


    <?php if($_GET['vn']!=""){ ?>
                $("#main-div").load('med_reconcile_detail.php?hn=<?php echo $row_rs_vn['hn']; ?>&vstdate=<?php echo date_db2th($row_rs_vn['vstdate']); ?>', function(responseTxt, statusTxt, xhr){
                    
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
						disease_load_list('<?php echo $row_rs_vn['hn']; ?>','<?php echo date_db2th($row_rs_vn['vstdate']); ?>');
                        $('#indicator').hide();
                    if(statusTxt == "error")
                        alert("Error: " + xhr.status + ": " + xhr.statusText);    
                    
                                       
                        ///////////////////////////// 
                });        
    <?php } ?>
    <?php if($_GET['do']=="link"){ ?>
				$("#main-div").load('med_reconcile_detail.php?hn=<?php echo $_GET['hn']; ?>&vstdate=<?php echo ($vstdate); ?>', function(responseTxt, statusTxt, xhr){
                    
                    if(statusTxt == "success")
						$('#vstdate').val('<?php echo $vstdate; ?>');
						$('#hn').val('<?php echo $_GET['hn']; ?>');
                      //alert("External content loaded successfully!");
						disease_load_list('<?php echo $_GET['hn']; ?>','<?php echo $vstdate; ?>');
                        $('#indicator').hide();
                    if(statusTxt == "error")
                        alert("Error: " + xhr.status + ": " + xhr.statusText);    
                    
                                       
                        ///////////////////////////// 
                });        
    <?php } ?>
	
});

//ใส่ 0 ข้างหน้าตัวเลข
function leftPad(value, length) { 
    return ('0'.repeat(length) + value).slice(-length); 
}
// alert load
function alertload(url,w,h){
	 $.colorbox({fixed:true,width:w,height:h, iframe:true, href:url, onOpen : function () {$('html').css('overflowY','hidden');},onCleanup :function(){
$('html').css('overflowY','auto');}
,onClosed:function(){ }});

}
function alertload_error(url,w,h){
	 $.colorbox({fixed:true,width:w,height:h, iframe:true, href:url, onOpen : function () {$('html').css('overflowY','hidden');},onCleanup :function(){
$('html').css('overflowY','auto');}
,onClosed:function(){ 
	med_reconcile_load();
}});

}	

function med_reconcile_load(source){
                $("#med_reconcile_drug").load('med_reconcile_drug.php?hn='+$('#hn').val()+'&vstdate='+encodeURIComponent($('#vstdate').val()), function(responseTxt, statusTxt, xhr){
                    
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
						disease_load_list($('#hn').val(),encodeURIComponent($('#vstdate').val()));						
                        $('#indicator').hide();
						$('#source').val(source);

                    if(statusTxt == "error")
                        alert("Error: " + xhr.status + ": " + xhr.statusText);    
                    
                                       
                        ///////////////////////////// 
                });	
	}
function med_reconcile_load2(hn,vstdate){
                $("#med_reconcile_drug").load('med_reconcile_drug.php?hn='+hn+'&vstdate='+encodeURIComponent(vstdate), function(responseTxt, statusTxt, xhr){
                    
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
						disease_load_list(hn,vstdate);
                        $('#indicator').hide();
                    if(statusTxt == "error")
                        alert("Error: " + xhr.status + ": " + xhr.statusText);    
                    
                                       
                        ///////////////////////////// 
                });	
	}
function drug_del(id,action){
                $("#med_reconcile_drug").load('med_reconcile_drug.php?id='+id+'&action='+action+'&vstdate='+encodeURIComponent($('#vstdate').val())+'&hn='+$('#hn').val(), function(responseTxt, statusTxt, xhr){
                    
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();
                    if(statusTxt == "error")
                        alert("Error: " + xhr.status + ": " + xhr.statusText);    
                                                
                 ///////////////////////////// 
                });			
	}

function open_modal(){
                $("#modal-body").load('med_reconcile_list.php', function(responseTxt, statusTxt, xhr){
                    
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
						$('#myModal').modal('show');
                    if(statusTxt == "error")
                        alert("Error: " + xhr.status + ": " + xhr.statusText);    
                                                
                 ///////////////////////////// 
                });			

}	

function load_list(a,b){
				$('#myModal').modal('hide');
	            $('#indicator').show();            
                $('#hn').val(a);
				$('#vstdate').val(b);
	
                $("#main-div").load('med_reconcile_detail.php?hn='+a+'&vstdate='+b, function(responseTxt, statusTxt, xhr){
                    
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();
                    if(statusTxt == "error")
                        alert("Error: " + xhr.status + ": " + xhr.statusText);    
                    
                                       
                        ///////////////////////////// 
                });

}
function disease_load_list(a,b){
	            $('#indicator').show();            	
                $("#disease_result").load('med_reconcile_disease_result.php?hn='+a+'&vstdate='+b, function(responseTxt, statusTxt, xhr){
                    
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();
                    if(statusTxt == "error")
                        alert("Error: " + xhr.status + ": " + xhr.statusText);    
                    
                                       
                        ///////////////////////////// 
                });

}	
</script>
<style>
html,body{overflow:hidden; }
	::-webkit-scrollbar {
    width: 10px;
}

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

<body>
<nav class="navbar navbar-dark bg-info text-white " style="padding-bottom: 2px;" >
  <!-- Navbar content -->
  <div class="form-row">
      <div class="col-sm-auto card-title font20 font_bord"><i class="fas fa-laptop-medical" style="font-size: 25px;"></i>&ensp;HOSxp&ensp;Medication Reconciliation</div>
      &emsp;&emsp;
      <div class="col-sm-auto"><i class="far fa-calendar-alt" style="font-size: 28px;"></i></div>
      
      <div class="col-sm-auto"><input type="text" name="vstdate" id="vstdate" value="<?php if($_GET['vn']!=""){ echo date_db2th($row_rs_vn['vstdate']); } else { echo date('d/m/').(date('Y')+543); } ?>" class="form-control form-control-plaintext thfont font16 font-weight-bolder" style=" padding:2px; padding-left: 2px; padding-right:2px; width:95px; height:30px; background-color:#E6E6FA"/>
      </div>
      
      <div class="col-sm-auto"><input name="hn" placeholder="HN" type="search" id="hn" class="form-control" style=" padding-left:2px; padding-right:2px;width:120px; height:30px;" maxlength="9" value="<?php if($_GET['vn']!=""){ echo $row_rs_vn['hn']; } ?>" /></div>
  </div>

  <ul class="navbar-nav ml-auto">
    <li class="nav-item">
      <a class="nav-link" href="#" onClick="open_modal();"><i class="fas fa-list-alt font20"></i>&ensp;รายการบันทึก</a>
    </li>
  </ul>
	
</nav>
    
<div id="main-div" style="padding: 10px;overflow:scroll;overflow-x:hidden;overflow-y:auto; height:90vh;">
    
</div>
 
<!-- The Modal list-->
  <div class="modal fade" id="myModal">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header w3-theme-d4">
          <h5 class="modal-title text-white" id="modal-title">รายชื่อผู้ป่วยที่บันทึกใบ med. reconcile</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body" id="modal-body" style="margin-top:0px;overflow:scroll;overflow-x:hidden;overflow-y:auto; height:60vh; z-index: 2"></div>
        
        
      </div>
    </div>
  </div>

	
</body>
</html>
<?php mysql_free_result($rs_vn); ?>