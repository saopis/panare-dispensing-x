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

    // ปรับปรุงสถานะการเปิดปิดการตรวจสอบ diag
 if(isset($_GET['action'])&&($_GET['action']=="onofficd10")){
    mysql_select_db($database_hos, $hos);
    $query_rs_icd10off = "select * from ".$database_kohrx.".kohrx_dispensing_setting where id='41'";
    $rs_icd10off = mysql_query($query_rs_icd10off, $hos) or die(mysql_error());
    $row_rs_icd10off = mysql_fetch_assoc($rs_icd10off);
    $totalRows_rs_icd10off = mysql_num_rows($rs_icd10off);
     
        if($totalRows_rs_icd10off==0){
            mysql_select_db($database_hos, $hos);
            $query_insert = "insert into ".$database_kohrx.".kohrx_dispensing_setting (id,name,value) value ('41','icd10_drug_off','".$_GET['istatus']."')";
            $insert = mysql_query($query_insert, $hos) or die(mysql_error());          
        } 
        else{
            mysql_select_db($database_hos, $hos);
            $query_update = "update ".$database_kohrx.".kohrx_dispensing_setting set `value`='".$_GET['istatus']."' where id='41'";
            $update = mysql_query($query_update, $hos) or die(mysql_error());                      
        }
     
    mysql_free_result($rs_icd10off);
     exit();
 }

if(isset($_POST['save2'])&&($_POST['save2']=="บันทึก")){
	mysql_select_db($database_hos, $hos);
	$query_insert = "insert into ".$database_kohrx.".kohrx_icd10_chronic_check (diag1,diag2,name) value ('".$_POST['diag1']."','".$_POST['diag2']."','".$_POST['diagname']."')";
	$insert = mysql_query($query_insert, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_icd10_chronic_check (diag1,diag2) value (\'".$_POST['diag1']."\',\'".$_POST['diag2']."\',\'".$_POST['diagname']."\')')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	 
	 }

 if(isset($_POST['save'])&&($_POST['save']=="บันทึก")){
	mysql_select_db($database_hos, $hos);
	$query_insert = "insert into ".$database_kohrx.".kohrx_drug_chronic_check (icode) value ('".$_POST['drugname']."')";
	$insert = mysql_query($query_insert, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_drug_chronic_check (icode) value (\'".$_POST['drugname']."\')')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	
}
if($_GET['do']=="delete"){
	mysql_select_db($database_hos, $hos);
	$query_delete = "delete from ".$database_kohrx.".kohrx_drug_chronic_check where icode ='".$_GET['id']."'";
	$delete = mysql_query($query_delete, $hos) or die(mysql_error());	
	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','delete from ".$database_kohrx.".kohrx_drug_chronic_check where icode =\'".$_GET['id']."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	
}
if($_GET['do']=="delete2"){
	mysql_select_db($database_hos, $hos);
	$query_delete = "delete from ".$database_kohrx.".kohrx_icd10_chronic_check where id ='".$_GET['id']."'";
	$delete = mysql_query($query_delete, $hos) or die(mysql_error());	
	
	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','delete from ".$database_kohrx.".kohrx_diag_chronic_check where id =\'".$_GET['id']."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	
}

mysql_select_db($database_hos, $hos);
$query_rs_drugqty = "select concat(d.name,' ',d.strength) as drugname,u.icode from ".$database_kohrx.".kohrx_drug_chronic_check u left outer join drugitems d on d.icode=u.icode order by drugname ASC";
$rs_drugqty = mysql_query($query_rs_drugqty, $hos) or die(mysql_error());
$row_rs_drugqty = mysql_fetch_assoc($rs_drugqty);
$totalRows_rs_drugqty = mysql_num_rows($rs_drugqty);

mysql_select_db($database_hos, $hos);
$query_rs_diag = "select * from ".$database_kohrx.".kohrx_icd10_chronic_check order by diag1 ASC";
$rs_diag = mysql_query($query_rs_diag, $hos) or die(mysql_error());
$row_rs_diag = mysql_fetch_assoc($rs_diag);
$totalRows_rs_diag = mysql_num_rows($rs_diag);

mysql_select_db($database_hos, $hos);
$query_rs_drug = "SELECT icode,name,strength FROM s_drugitems WHERE istatus='Y' and name not like '%คิด%' and name not like '%ต่อ%' and icode like '1%' and icode not in (select icode from ".$database_kohrx.".kohrx_drug_chronic_check) ORDER BY name ASC";
$rs_drug = mysql_query($query_rs_drug, $hos) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="include/kohrx/css/kohrx.css" rel="stylesheet" type="text/css" />
<?php include('java_css_online.php'); ?>
<style>
html{overflow-x: hidden;}

* {
  box-sizing: border-box;
}

/* Create two equal columns that floats next to each other */
.column {
  float: left;
  width: 50%;
  padding: 10px;
  height: 300px; /* Should be removed. Only for demonstration */
}

/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}
.switch {
  position: relative;
  display: inline-block;
  width: 40px;
  height: 24px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 16px;
  width: 16px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(16px);
  -ms-transform: translateX(16px);
  transform: translateX(16px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>
<script>
	$(document).ready(function(){
        <?php if($row_setting[41]=='Y'){ ?>
            $('#istatus').prop('checked', true);
            $('.icd10').show();

        <?php } else { ?>
            $('#istatus').prop('checked', false);
            $('.icd10').hide();
        <?php } ?>
        
		$('#istatus').change(function(){
            if($('#istatus').prop('checked')==true){
                $('.icd10').show();
            }
            else{
                $('.icd10').hide();
                
            }

			$('#check_load').load('drug_setting_chronic_check.php?action=onofficd10&istatus='+($('#istatus').prop('checked') ? 'Y' : 'N'));
		});
	});
</script>
</head>

<body>
<div style="padding:10px;">
<div class="row">
  <div class="column" style=" padding:10px;">
	<div class="card">
    	<div class="card-header"><span class="badge badge-dark" style="font-size: 20px;">1</span>&ensp;ยาที่ต้องตรวจสอบกรณีแพทย์หยุดสั่งใช้</div>
        <div class="card-body">
        <form id="form1" name="form1" method="post" action="drug_setting_chronic_check.php">
        	<div class="form-group row">
                <div class="col-sm-8">
                <select name="drugname" id="drugname"  class="form-control">
          		<option value="">เลือกรายการยา</option>
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
              <div class="col-sm-2">
      <input type="submit" name="save" id="save" value="บันทึก" class=" btn btn-primary"/>
              </div>
            </div>
        </form>
        </div>
    </div>
    <!-- .card -->
<div style="padding-top:10px;">
<?php if ($totalRows_rs_drugqty > 0) { // Show if recordset not empty ?>
<table id="table" class="table table-striped table-bordered row-border hover " >
<thead>
    <tr>
      <td  align="center">id</td>
      <td >drugname</td>
      <td  align="center">&nbsp;</td>
    </tr>
 </thead>
 <tbody>
    <?php $i=0; do { $i++; ?>
    <tr>
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo $i; ?></td>
      <td bgcolor="<?php echo $bgcolor; ?>"><?php echo $row_rs_drugqty['drugname']; ?></td>
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo "$row_rs_drugusage[code]"; ?> <a href="JavaScript:if(confirm('ต้องการลบข้อมูลนี้จริงหรือไม่?')==true){ window.location='drug_setting_chronic_check.php?id=<?php echo $row_rs_drugqty['icode']; ?>&amp;do=delete'; }"><img src="images/bin.png" width="16" height="16" border="0" align="absmiddle" /></a></td>
    </tr>
    <?php } while ($row_rs_drugqty = mysql_fetch_assoc($rs_drugqty)); ?>
   </tbody>
  </table>
  <?php } // Show if recordset not empty ?>    
  </div>
  </div>
  <!-- .column -->
  <div class="column" style=" padding:10px;">
	<div class="card">
    	<div class="card-header"><span class="badge badge-dark" style="font-size: 20px;">2</span>&emsp;ICD10 ที่ต้องตรวจสอบกรณีแพทย์หยุดสั่งใช้</div>
        <div class="card-body">
        <form id="form2" name="form2" method="post" action="">
			<div class="form-group row">
				<label class="col-form-label col-form-label-sm col-sm-3 text-right">เปิดใช้งาน</label>
				<div class="col-sm-auto"><label class="switch">
                  <input type="checkbox" id="istatus"/>
                  <span class="slider round"></span>
                </label></div>
			</div>
        	<div class="form-group row icd10">
            	<label for="diag1" class="col-sm-3 col-form-label" style="text-align:right">ICD</label>
                <div class="col-sm-2">
				<input name="diag1" type="text" class=" form-control" id="diag1" />
                </div>
            	<label for="diag2" class="col-sm-1 col-form-label">-</label>
                <div class="col-sm-2">
				<input name="diag2" type="text" class=" form-control" id="diag2" />
                </div>

            </div>
		<div class="form-group row icd10">
            	<label for="diagname" class="col-sm-3 col-form-label" style="text-align:right">ชื่อกลุ่มโรค</label>                
                <div class="col-sm-5" align="left">
				<input name="diagname" type="text" class=" form-control" id="diagname" />
                </div>
                <div class="col-sm-2">
			    <input type="submit" name="save2" id="save2" class="btn btn-primary" value="บันทึก"/>
                </div>
        
        </div>
            <!-- .row -->
        </form>
			<div id="check_load"></div>
        </div>
        <!-- .card-body -->
    </div>
    <!-- .card -->
    <div style="padding-top:10px;" class="icd10">
	<table id="table2" class="table table-striped table-bordered row-border hover " >
    <thead>
      <tr >
        <td width="5%" align="center">id</td>
        <td width="40%" align="center">icd10</td>
        <td width="43%" align="center">รายละเอียด</td>
        <td width="12%" align="center">&nbsp;</td>
        </tr>
     </thead>
     <tbody>   
    <?php $i=0; do { $i++; ?>
      <tr >
          <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo $i; ?></td>
          <td align="center" bgcolor="<?php echo $bgcolor; ?>"><span class="table_head_small"><?php echo $row_rs_diag['diag1']; ?> - <?php echo $row_rs_diag['diag2']; ?></span>&nbsp; <a href="JavaScript:if(confirm('ต้องการลบข้อมูลนี้จริงหรือไม่?')==true){ window.location='drug_setting_chronic_check.php?id=<?php echo $row_rs_diag['id']; ?>&amp;do=delete2'; }"></a></td>
          <td align="center" bgcolor="<?php echo $bgcolor; ?>"><span class="table_head_small"><?php echo $row_rs_diag['name']; ?></span></td>
          <td align="center" bgcolor="<?php echo $bgcolor; ?>"><i class="fas fa-trash" style="color:#333; font-size:18px; cursor:pointer;" onclick="if(confirm('ต้องการลบข้อมูลนี้จริงหรือไม่?')==true){ window.location='drug_setting_chronic_check.php?id=<?php echo $row_rs_diag['id']; ?>&amp;do=delete2'; }"></i></td>
      </tr>
      <?php } while ($row_rs_diag = mysql_fetch_assoc($rs_diag)); ?>
	</tbody>
    </table>
	</div>
  </div>
  <!-- .colum -->
</div>
</div>

</body>
</html>
<?php
mysql_free_result($rs_drug);
mysql_free_result($rs_drugqty);
mysql_free_result($rs_diag);

?>
