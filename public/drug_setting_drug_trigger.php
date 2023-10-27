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
if(isset($_POST['save2'])&&($_POST['save2']=="แก้ไข")){
mysql_select_db($database_hos, $hos);
$query_update = "update ".$database_kohrx.".kohrx_drug_trigger set drug_prefix='".$_POST['prefix']."',trigger_color='".$_POST['trigger_color']."' where icode='".$_POST['id']."'";
$rs_udpate = mysql_query($query_update, $hos) or die(mysql_error());
	if($rs_udpate){  echo "<script>window.location='drug_setting_drug_trigger.php';</script>";
    exit();
  
	}
}
if(isset($_GET['do'])&&($_GET['do']=="delete")){
mysql_select_db($database_hos, $hos);
$query_delete = "delete from ".$database_kohrx.".kohrx_drug_trigger where icode='".$_GET['id']."'";
$rs_delete = mysql_query($query_delete, $hos) or die(mysql_error());
echo "<script>window.location='drug_setting_drug_trigger.php';</script>";
    exit();	
}

if(isset($_POST['save'])&&($_POST['save']=="บันทึก")){
mysql_select_db($database_hos, $hos);
$query_insert = "insert into ".$database_kohrx.".kohrx_drug_trigger (icode,drug_prefix,trigger_color) value ('".$_POST['drug']."','".$_POST['prefix']."','".$_POST['trigger_color']."')";
$rs_insert = mysql_query($query_insert, $hos) or die(mysql_error());
echo "<script>window.location='drug_setting_drug_trigger.php';</script>";	
exit();
}

if(!isset($_GET['e_id'])){
$condition="and icode not in (select icode from  ".$database_kohrx.".kohrx_drug_trigger)";
}
else{
$condition="and icode ='".$_GET['e_id']."'";	
}
mysql_select_db($database_hos, $hos);
$query_rs_icon = "select concat(name,' ',strength) as drugname,icode from drugitems where istatus='Y' ".$condition." order by name ASC";
$rs_icon = mysql_query($query_rs_icon, $hos) or die(mysql_error());
$row_rs_icon = mysql_fetch_assoc($rs_icon);
$totalRows_rs_icon = mysql_num_rows($rs_icon);

mysql_select_db($database_hos, $hos);
$query_rs_icon2 = "select * from ".$database_kohrx.".kohrx_drug_trigger ";
//echo $query_rs_icon2;
$rs_icon2 = mysql_query($query_rs_icon2, $hos) or die(mysql_error());
$row_rs_icon2 = mysql_fetch_assoc($rs_icon2);
$totalRows_rs_icon2 = mysql_num_rows($rs_icon2);

if(isset($_GET['e_id'])&&$_GET['e_id']!=""){
mysql_select_db($database_hos, $hos);
$query_rs_icon3 = "select * from ".$database_kohrx.".kohrx_drug_trigger where icode='".$_GET['e_id']."' ";
//echo $query_rs_icon3;
$rs_icon3 = mysql_query($query_rs_icon3, $hos) or die(mysql_error());
$row_rs_icon3 = mysql_fetch_assoc($rs_icon3);
$totalRows_rs_icon3 = mysql_num_rows($rs_icon3);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
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
    $(function() {
         
 
});
</Script>
<script>
$(document).ready(function() {
});
</script>
<style>
@media print {
    table,table thead, table tr, table td {
        border-top: #000 solid 1px;
        border-bottom: #000 solid 1px;
        border-left: #000 solid 1px;
        border-right: #000 solid 1px;
        font-size: 16px;
    }
    table {
    border:solid #000 !important;
    border-width:1px 0 0 1px !important;
}
th, td {
    border:solid #000 !important;
    border-width:0 1px 1px 0 !important;
}
} 
</style>


<style>
.ui-autocomplete {
	padding-right:5px;
        max-height:200px !important;
        overflow: auto !important;
	}
/*   css ส่วนของรายการที่แสดง  */   
	/*  css  ส่วนปุ่มคลิกเลือกแสดงรายการทั้งหมด*/ 
select {
  font-family: 'FontAwesome', 'Second Font name'
}
.fonawesome{
  font-family: 'FontAwesome', 'Second Font name'		
}
</style>
</head>

<body>
<div class="p-3">
<div class="card" style="margin-top:10px">
  <div class="card-header">
  กำหนด Drug Trigger
  </div>
  <div class="card-body">
    <form id="form1" name="form1" method="post" action="">
      <div class="form-group row">
        <label for="drug" class="col-sm-auto col-form-label">เลือกยา</label>
        <div class="col-sm-auto">
		<select id="drug" name="drug" class="selectpicker" data-live-search="true" style="height: 45px;">
			<?php do{ ?>
			<option value="<?php echo $row_rs_icon['icode']; ?>" <?php if ($totalRows_rs_icon3<>0){ if($row_rs_icon['icode']==$row_rs_icon3['icode']) {echo "selected=\"selected\"";}} ?>><?php echo $row_rs_icon['drugname']; ?></option>
			<?php }while($row_rs_icon = mysql_fetch_assoc($rs_icon)); ?>
		</select> 
        </div>
        <label for="icon_name" class="col-sm-auto col-form-label">ชื่อย่อ</label>
        <div class="col-sm-2">
        <input name="prefix" type="text" class="form-control" id="prefix" maxlength="5" value="<?php echo $row_rs_icon3['drug_prefix']; ?>" /> 
        </div>
        <label for="color" class="col-sm-auto col-form-label">สี icon</label>
        <div class="col-sm-auto">
			<select name="trigger_color" class="form-control" id="trigger_color" >
				<option value="light" <?php if ($row_rs_icon3['trigger_color']=="light") {echo "selected=\"selected\"";} ?> class="bg-ligh"><span class="bg-light">light</span></option>
				<option value="info" class="bg-info" <?php if ($row_rs_icon3['trigger_color']=="info") {echo "selected=\"selected\"";} ?>>info</option>
				<option value="primary" class="bg-primary" <?php if ($row_rs_icon3['trigger_color']=="primary") {echo "selected=\"selected\"";} ?>><span class="bg-primary">primary</span></option>
				<option value="success" class="bg-success" <?php if ($row_rs_icon3['trigger_color']=="success") {echo "selected=\"selected\"";} ?>><span class="bg-success">success</span></option>
				<option value="warning" class="bg-warning" <?php if ($row_rs_icon3['trigger_color']=="warning") {echo "selected=\"selected\"";} ?>><span class="bg-warning">warning</span></option>
				<option value="danger" class="bg-danger" <?php if ($row_rs_icon3['trigger_color']=="danger") {echo "selected=\"selected\"";} ?> ><span class="bg-danger">danger</span></option>
				<option value="secondary" class="bg-secondary" <?php if ($row_rs_icon3['trigger_color']=="secondary") {echo "selected=\"selected\"";} ?>><span class="bg-secondary">secondary</span></option>
				<option value="dark" class="bg-dark" <?php if ($row_rs_icon3['trigger_color']=="dark") {echo "selected=\"selected\"";} ?>><span class="bg-dark">dark</span></option>
			</select> 
        </div>		  
        <?php if(!isset($_GET['e_id'])){ ?>
        <input type="submit" name="save" id="save" value="บันทึก" class="btn btn-info col-sm-1" />
        <?php } else {?>
        <input type="submit" name="save2" id="save2" value="แก้ไข" class="btn btn-warning col-sm-1" />
      <?php } ?>
      <input name="id" type="hidden" id="id" value="<?php echo $_GET['e_id']; ?>" />
      </div>
      <!-- .form-group row -->
    </form>

  </div>
  <!-- .card-body -->
</div>
<!-- .card -->
 <div style="padding-top:20px; max-width: 500px">
<?php if($totalRows_rs_icon2<>0){ ?>	 
 <table id="tables" class="table table-striped table-bordered table-hover ">
 <thead>
  <tr >
    <td align="center">no.</td>
    <td align="left">icon</td>
    <td align="center"></td>
  </tr>
</thead>
<tbody>
	<?php $i=0; do{ $i++; ?>
      <tr >
      <td align="center" ><?php echo $i; ?></td>
      <td align="left" style="padding-left:10px"><span class="badge badge-<?php echo $row_rs_icon2
	['trigger_color']; ?> font14 p-2"><?php echo $row_rs_icon2['drug_prefix']; ?></span></td>
      <td width="41" align="center" ><nobr><i class="fas fa-edit" style="color:#333; font-size:18px; cursor:pointer;" onclick="window.location='drug_setting_drug_trigger.php?e_id=<?php echo $row_rs_icon2['icode']; ?>';"></i>&nbsp;<i class="fas fa-trash" style="color:#333; font-size:18px; cursor:pointer;" onclick="if(confirm('ต้องการลบข้อมูลนี้จริงหรือไม่?')==true){window.location='drug_setting_drug_trigger.php?do=delete&amp;id=<?php echo $row_rs_icon2["icode"]; ?>';}"></i></nobr>
      </td>
      </tr>
	<?php }while($row_rs_icon2 = mysql_fetch_assoc($rs_icon2)); ?>
	
</tbody>
</table>
<?php } ?>	 
</div>

</div>
<!-- .container -->
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.18/datatables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">

	
<script>
$(document).ready(function() {
    $('#table').DataTable();
    $('#table2').DataTable();
} );
</script>


</body>
</html>
<?php


mysql_free_result($rs_icon);
?>
