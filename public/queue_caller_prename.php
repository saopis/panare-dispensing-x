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
if(isset($_POST['save'])){
	if($_POST['save']=="บันทึก"){
	if($_POST['year_old']==""){ $year_old="NULL"; } else {$year_old="'".$_POST['year_old']."'"; $year_old2="\'".$_POST['year_old']."\'"; }

mysql_select_db($database_hos, $hos);
$query_insert = "insert into ".$database_kohrx.".kohrx_queue_caller_pname (pname,pname_call,monk,year_old,parent_call) value ('".$_POST['pname']."','".$_POST['pname_call']."','".$_POST['monk']."',".$year_old.",'".$_POST['parent_call']."')";
$rs_insert = mysql_query($query_insert, $hos) or die(mysql_error());	

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_queue_caller_pname (pname,pname_call,monk,year_old,parent_call) value (\'".$_POST['pname']."\',\'".$_POST['pname_call']."\',\'".$_POST['monk']."\',".$year_old2.",\'".$_POST['parent_call']."\')')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

}
if($_POST['save']=="แก้ไข"){
	if($_POST['year_old']==""){ $year_old="NULL"; } else {$year_old="'".$_POST['year_old']."'"; $year_old2="\'".$_POST['year_old']."\'"; }
	
mysql_select_db($database_hos, $hos);
$query_update = "update ".$database_kohrx.".kohrx_queue_caller_pname set pname_call='".$_POST['pname_call']."',monk='".$_POST['monk']."',year_old=".$year_old.",parent_call='".$_POST['parent_call']."' where pname='".$_POST['pname']."'";
$rs_update = mysql_query($query_update, $hos) or die(mysql_error());	

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_queue_caller_pname set pname_call=\'".$_POST['pname_call']."\',monk=\'".$_POST['monk']."\',year_old=".$year_old2.",parent_call=\'".$_POST['parent_call']."\' where pname=\'".$_POST['pname']."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

	echo "<meta http-equiv=\"refresh\" content=\"0;URL=queue_caller_prename.php\" />";
	}
}

if(isset($_GET['do'])){

if($_GET['do']=="delete"){
mysql_select_db($database_hos, $hos);
$query_delete = "delete from ".$database_kohrx.".kohrx_queue_caller_pname where id='$id'";
$rs_delete = mysql_query($query_delete, $hos) or die(mysql_error());	

	echo "<meta http-equiv=\"refresh\" content=\"0;URL=queue_caller_prename.php\" />";
}
if($_GET['do']=="edit"){
mysql_select_db($database_hos, $hos);
$query_rs_prename = "select pname as name,pname_call,monk,year_old,parent_call from ".$database_kohrx.".kohrx_queue_caller_pname where id='$id'";
$rs_prename = mysql_query($query_rs_prename, $hos) or die(mysql_error());
$row_rs_prename = mysql_fetch_assoc($rs_prename);
$totalRows_rs_prename = mysql_num_rows($rs_prename);	
}
}
else {
	mysql_select_db($database_hos, $hos);
$query_rs_prename = "select * from pname where name not in (select pname from ".$database_kohrx.".kohrx_queue_caller_pname)";
$rs_prename = mysql_query($query_rs_prename, $hos) or die(mysql_error());
$row_rs_prename = mysql_fetch_assoc($rs_prename);
$totalRows_rs_prename = mysql_num_rows($rs_prename);

	}

mysql_select_db($database_hos, $hos);
$query_rs_pname_caller = "select * from ".$database_kohrx.".kohrx_queue_caller_pname order by id DESC";
$rs_pname_caller = mysql_query($query_rs_pname_caller, $hos) or die(mysql_error());
$row_rs_pname_caller = mysql_fetch_assoc($rs_pname_caller);
$totalRows_rs_pname_caller = mysql_num_rows($rs_pname_caller);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php include('java_css_file.php'); ?>
<style>
html,body{overflow:hidden; }
::-webkit-scrollbar {
    width: 15px;
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
<nav class="navbar navbar-dark bg-info text-white fixed-top">
  <!-- Navbar content -->
    <span class="font18"><i class="fab fa-whmcs font20"></i>&ensp;ตั้งค่าคำนำหน้าชื่อ</span>
</nav>
<div style="margin-top:45px; padding:10px; padding-top:0px; padding-right:0px;">
<div class="p-2">
<form id="form1" name="form1" method="post" class="mt-2">
<div class="rounded border p-3">
<div class="form-row">
  <div class="col-sm-2"><label for="pname" class="col-form-label-sm">คำนำหน้า</label></div>
  <div class="col-sm-auto"><select name="pname" class="form-control" id="pname">
        <?php
do {  
?>
        <option value="<?php echo $row_rs_prename['name']?>"><?php echo $row_rs_prename['name']?></option>
        <?php
} while ($row_rs_prename = mysql_fetch_assoc($rs_prename));
  $rows = mysql_num_rows($rs_prename);
  if($rows > 0) {
      mysql_data_seek($rs_prename, 0);
	  $row_rs_prename = mysql_fetch_assoc($rs_prename);
  }
?>
      </select></div>
      <div class="col-sm-auto"><label for="pname_call" class="col-form-label-sm">คำเรียก</label></div>
     <div class="col-sm-auto"><input name="pname_call" type="text" class="form-control" id="pname_call" value="<?php echo $row_rs_prename['pname_call']; ?>" /></div>
     <div class="col-sm-auto">
     <div class="custom-control custom-switch">
    <input <?php if (!(strcmp($row_rs_prename['monk'],"Y"))) {echo "checked=\"checked\"";} ?> type="checkbox" class="custom-control-input" id="monk" name="monk" value="Y">
    <label class="custom-control-label" for="monk">พระภิกษุ</label>
  </div>
     </div>
</div>
<div class="form-row mt-2">
	<div class="col-sm-auto"><label for="year_old" class="col-form-label"><i class="fas fa-child"></i>&ensp;กรณีเด็ก</label></div>
    <div class="col-sm-auto"><label class="col-form-label">ถ้าอายุต่ำกว่า</label></div>
    <div class="col-sm-auto"><input name="year_old" type="text" id="year_old" style="width:50px; padding-left:2px;" class="form-control" value="<?php echo $row_rs_prename['year_old']; ?>" />
    </div>
    <div class="col-sm-auto">    
        <label class=" col-form-label">ปี</label> 
        </div>
        <div class="col-sm-auto">
           <div class="custom-control custom-switch mt-2">
            <input <?php if (!(strcmp($row_rs_prename['parent_call'],"Y"))) {echo "checked=\"checked\"";} ?> type="checkbox" class="custom-control-input" name="parent_call" id="parent_call" value="Y">
                <label class="custom-control-label" for="parent_call">ให้เรียกผู้ปกครอง</label>
          </div>
</div>
<div class="form-row">
<div class="col">
<input type="submit" name="save" id="save" value="<?php if($_GET['do']=="edit"){ echo "แก้ไข"; } if(!isset($_GET['do'])) { echo "บันทึก";} ?>" class="btn btn-primary"/>
</div>
</div>
</div>
</div>
</form>
</div>
<div class="p-2">
<table style="width:100%" border="0" cellspacing="0" class="table-striped table-sm table table-hover">
<thead>
  <tr class="table_head_small_bord text-center">
    <th width="5%" height="30px;" align="center"><span >ลำดับ</span></th>
    <th width="20%" align="center"><span >คำนำหน้า</span></th>
    <th width="20%" align="center"><span >เสียงเรียก</span></th>
    <th width="10%" align="center"><span >พระภิกษุ</span></th>
    <th width="20%" align="center"><span >เรียกผู้ปกครอง</span></th>
    <th width="15%" align="center"><span >อายุต่ำกว่า(ปี)</span></th>
    <th width="10%" align="center">&nbsp;</th>
  </tr>
</thead>
</table>
<div style="overflow:scroll;overflow-x:hidden;overflow-y:auto; height:360px; margin-top:-18px;">
<table style="width:100%" border="0" cellspacing="0" class="table-striped table-sm table table-hover mt-2">
<tbody>
    <?php $i=0; do { $i++; ?>
  <tr class="grid2">
      <td width="5%" align="center"><?php print $i; ?></td>
      <td width="20%" align="center"><?php echo $row_rs_pname_caller['pname']; ?></td>
      <td width="20%" align="center"><?php echo $row_rs_pname_caller['pname_call']; ?></td>
      <td width="10%" align="center"><?php if($row_rs_pname_caller['monk']=="Y"){ echo "<i class=\"fas fa-check font20\" style=\"color:green\"></i>"; } ?></td>
      <td width="20%" align="center"><?php if($row_rs_pname_caller['parent_call']=="Y"){ echo "<i class=\"fas fa-user-check font20\"></i>"; } ?></td>
      <td width="15%" align="center"><?php print $row_rs_pname_caller['year_old']; ?></td>
      <td width="10%" align="center"><a href="queue_caller_prename.php?do=edit&amp;id=<?php echo $row_rs_pname_caller['id']; ?>"><i class="fas fa-edit text-primary"></i></a>&ensp;<a href="javascript:if(confirm('ต้องการลบข้อมูลจริงหรือไม่?')==true){window.location='queue_caller_prename.php?do=delete&id=<?php echo $row_rs_pname_caller['id']; ?>'}"><i class="fas fa-minus-circle text-danger"></i></a></td>
  </tr>      <?php } while ($row_rs_pname_caller = mysql_fetch_assoc($rs_pname_caller)); ?>
</tbody>
</table>
</div>
</div>
</body>
</html>
<?php
mysql_free_result($rs_prename);

mysql_free_result($rs_pname_caller);
?>
