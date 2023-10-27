<?php require_once('../Connections/hos.php'); ?>
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


if($_POST['id'])
{
$id=$_POST['id'];
mysql_select_db($database_hos, $hos);
if($_POST['type']=="main"){
$no=1;
if($_POST['mr']=="Y"){ $condition=" and mr='Y'"; }
$query_rs_error = "select id,name,mr from ".$database_kohrx.".kohrx_med_error_error_cause where type_id='$id'".$condition;
}
if($_POST['type']=="sub"){
$no=2;
if($_POST['mr']=="Y"){ $condition=" and mr='Y'"; }
$query_rs_error = "select id,sub_name as name,mr  from ".$database_kohrx.".kohrx_med_error_error_sub_cause where cause_id='$id'".$condition;
}
$rs_error = mysql_query($query_rs_error, $hos) or die(mysql_error());
//$row_rs_error = mysql_fetch_assoc($rs_error);
$totalRows_rs_error = mysql_num_rows($rs_error);
echo '<option value="">= เลือกประเภทย่อย '.$no.' =</option>';

if($totalRows_rs_error<>0){
while($row_rs_error = mysql_fetch_assoc($rs_error))
{
if($_POST['show_mr']=='Y'){
if($row_rs_error['mr']=='Y'){
	$mrs="-[MR]";
}
else{ $mrs=""; }
}
$id=$row_rs_error['id'];
$data=$row_rs_error['name'].$mrs;
	
	if($_POST['error_id']!=""){ ?>
	<option value="<?php echo $id; ?>" <?php if (!(strcmp($id, $_POST['error_id']))) { echo "selected=\"selected\"";} ?> ><?php echo $data; ?></option>
	<?php
	}
	else{ ?>
	<option value="<?php echo $id; ?>"><?php echo $data; ?></option>
	<?php
	}

}
}
}
else{
if($_POST['type']=="main"){
$no=1;
}
if($_POST['type']=="sub"){
$no=2;
}

    echo '<option value="">= เลือกประเภทย่อย '.$no.' =</option>';
}
mysql_free_result($rs_error);
?>