<?php ob_start();?>
<?php session_start();?>
<?php require_once('Connections/hos.php'); ?>
<?php 
include('include/function_sql.php');
	if(isset($_POST['save'])&&($_POST['save']=="บันทึก")){

			mysql_select_db($database_hos, $hos);
			$query_emer_drug = "select * from  ".$database_kohrx.".kohrx_emergency_drug where vn='".$vn."' and icode='".$emer_drug1."' and rxtime='".$rxtime."'";
			$emer_drug = mysql_query($query_emer_drug, $hos) or die(mysql_error());
			$row_emer_drug = mysql_fetch_assoc($emer_drug);
			$totalRows_emer_drug = mysql_num_rows($emer_drug);
			
			if($totalRows_emer_drug==0){
			mysql_select_db($database_hos, $hos);
			$query_insert = "insert into  ".$database_kohrx.".kohrx_emergency_drug (vn,icode,rxtime,reciever,doctor,dispen_date,dispen_time,receive_time) values('".$_POST['vn']."','".$_POST['icode']."','".$_POST['rxtime']."','".$_POST['respondent']."','".$_POST['rx_check']."',NOW(),'".$_POST['dispen_time']."','".$_POST['receive_time']."')";
			$insert = mysql_query($query_insert, $hos) or die(mysql_error());
				
			
			
			}
			
			$success=1;
            mysql_free_result($emer_drug);
			}
		if($success==1){
                echo "<script>parent.drug_list_load_vn('".$vn."');parent.$.fn.colorbox.close();</script>";
                    exit();


			}
mysql_select_db($database_hos, $hos);
$query_drug_emd = "select concat(d.name,' ',d.strength) as drugname,o.qty,u.shortlist,o.rxtime from opitemrece o left outer join drugitems d on o.icode=d.icode left outer join drugusage u on u.drugusage=o.drugusage where o.icode='".$_GET['icode']."' and o.hos_guid='".$_GET['hos_guid']."' and o.vn='".$_GET['vn']."'";
$drug_emd = mysql_query($query_drug_emd, $hos) or die(mysql_error());
$row_drug_emd = mysql_fetch_assoc($drug_emd);
$totalRows_drug_emd = mysql_num_rows($drug_emd);

mysql_select_db($database_hos, $hos);
$query_rs_setting = "select * from ".$database_kohrx.".kohrx_dispensing_setting order by id ASC";
$rs_setting = mysql_query($query_rs_setting, $hos) or die(mysql_error());
$row_rs_setting = mysql_fetch_assoc($rs_setting);
$totalRows_rs_setting = mysql_num_rows($rs_setting);

$i=0; do{ $i++;
$row_setting[$i]=$row_rs_setting['value'];} 
while($row_rs_setting = mysql_fetch_assoc($rs_setting));

mysql_select_db($database_hos, $hos);
$query_rs_respondent = "select * from  ".$database_kohrx.".kohrx_adr_check_respondent";
$rs_respondent = mysql_query($query_rs_respondent, $hos) or die(mysql_error());
$row_rs_respondent = mysql_fetch_assoc($rs_respondent);
$totalRows_rs_respondent = mysql_num_rows($rs_respondent);

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<link href="include/kohrx/css/kohrx.css" rel="stylesheet" type="text/css" />

<?php include('java_css_file.php'); ?>
<script>
    $(document).ready(function(){		
            
			const timenow = Date().slice(16,21);
            $('#dispen_time').val(timenow);
        
            $('#save').prop('disabled',true);
			$("#receive_time").inputmask({"mask": "99:99"});
			$("#dispen_time").inputmask({"mask": "99:99"});
			$('#receive_time').focus();
            $("#receive_time").keypress(function(event) {
                return /\d/.test(String.fromCharCode(event.keyCode));
            });
            $("#dispen_time").keypress(function(event) {
                return /\d/.test(String.fromCharCode(event.keyCode));
            });
            $('#receive_time').keyup(function(){
                   // regular expression to match required date format
                    re = /^(([0-1]?[0-9])|([2][0-3])):([0-5]?[0-9])(:([0-5]?[0-9]))?$/;
                    if(form1.receive_time.value == '' || !form1.receive_time.value.match(re)||form1.dispen_time.value == '' || !form1.dispen_time.value.match(re)){
                        $('#save').prop('disabled',true);                       
                    }
                    else{ $('#save').prop('disabled',false);}
            });
            $('#dispen_time').keyup(function(){
                   // regular expression to match required date format
                    re = /^(([0-1]?[0-9])|([2][0-3])):([0-5]?[0-9])(:([0-5]?[0-9]))?$/;
                    if(form1.receive_time.value == '' || !form1.receive_time.value.match(re)||form1.dispen_time.value == '' || !form1.dispen_time.value.match(re)){
                        $('#save').prop('disabled',true);                       
                    }
                    else{ $('#save').prop('disabled',false);}
            });        
///////////////////////////////////
   
    });
	
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
<nav class="navbar navbar-dark bg-danger text-white">
  <!-- Navbar content -->
    <span class="font18"><i class="fas fa-check-double"></i>&emsp;บันทึกยาด่วน</span>
</nav>	
<div class="p-3">
<h5><?php echo $row_drug_emd['drugname']; ?></h5>	
<span class="font-weight-bold">วิธีใช้ :&nbsp; </span><span class=""><?php echo $row_drug_emd['shortlist']; ?></span>	
</div>
<form name="form1" method="post" action="detail_drug_list_emd.php">	
    <div class="card mt-2 position-fixed w-100" style="bottom: 0px;" >
        <div class="card-body" style="background-color:gainsboro; padding-bottom: 0px;">
            <div class="form-group row">
                <label class="col-form-label col-form-label-sm col-sm-auto">จ่ายให้กับ</label>
                <div class="col-sm-auto">
                  <input name="respondent" autofocus  type="text" class="form-control form-control-sm" id="respondent" style="width:30px; padding-left: 3px" onkeyup="key_doctor(this.value,'respondent_list');" onkeypress="return isNumberKey(event);" value="<?php if($totalRows_rs_edit<>0){ echo $row_rs_edit['respondent']; }else { echo "1"; } ?>" />
                </div>
                <div class="col-sm-auto">
            <select name="respondent_list" class="form-control form-control-sm" style="padding: 3px;" id="respondent_list"  onchange="list_doctor(this.value,'respondent')" onkeydown="setNextFocus('answer');">
              <?php
do {  
?>
              <option value="<?php echo $row_rs_respondent['id']?>"<?php if (!(strcmp($row_rs_respondent['id'], $row_rs_edit['respondent']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_respondent['respondent']?></option>
              <?php
} while ($row_rs_respondent = mysql_fetch_assoc($rs_respondent));
  $rows = mysql_num_rows($rs_respondent);
  if($rows > 0) {
      mysql_data_seek($rs_respondent, 0);
	  $row_rs_respondent = mysql_fetch_assoc($rs_respondent);
  }
?>
          </select>
                </div>
                <label class="col-form-label-sm">เวลารับ</label>
                <div class="col-sm-auto">
                  <input type="text" id="receive_time" name="receive_time" class="form-control form-control-sm" style="padding: 3px;width: 60px" />    
                </div>
                <label class="col-form-label-sm">เวลาจ่าย</label>
                <div class="col-sm-auto">
                  <input type="text" id="dispen_time" name="dispen_time" class="form-control form-control-sm" style="padding: 3px;width: 60px" />    
                </div>                
                <div class="col-sm-auto">
                    <?php echo $_SESSION['nameuser']; ?>
                </div>                				
                <div class="col-sm-auto">
                  <input type="submit" name="save" id="save" value="บันทึก" class="btn btn-danger">  
                </div>                
            </div>
        </div>
    </div>	
				<input type="hidden" id="rx_check" name="rx_check" value="<?php echo $_SESSION['doctorcode']; ?>"/>
				<input type="hidden" id="icode" name="icode" value="<?php echo $_GET['icode']; ?>"/>
				<input type="hidden" id="vn" name="vn" value="<?php echo $_GET['vn']; ?>"/>
				<input type="hidden" id="rxtime" name="rxtime" value="<?php echo $row_drug_emd['rxtime']; ?>"/>
	
	</form>	
</body>
</html>
<?php
mysql_free_result($drug_emd);
mysql_free_result($rs_setting);
//mysql_free_result($rs_doctor);
mysql_free_result($rs_respondent);

?>
