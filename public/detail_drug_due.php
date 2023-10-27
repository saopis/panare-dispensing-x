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

if(isset($_POST['delete'])&&$_POST['delete']=="ลบ"){
	mysql_select_db($database_hos, $hos);
	$query_delete = "delete from ".$database_kohrx.".kohrx_due_record where id='$id'";
	$rs_delete = mysql_query($query_delete, $hos) or die(mysql_error());

//insert replicate_log		
mysql_select_db($database_hos, $hos);
$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','delete from ".$database_kohrx.".kohrx_due_record where id=\'".$id."\'')
";
$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());


echo "<script>parent.$.fn.colorbox.close();</script>";
exit();

}

if(isset($_POST['save'])&&$_POST['save']=="บันทึก"){
if($cause_id==""){
		echo "กรุณาเลือกเหตุผลของการสั่งใช้ยา   ";
		echo "<input type=\"button\" name=\"back\" id=\"back\" value=\"ย้อนกลับ\" class=\"button red\" onclick=\"window.history.back();\" />";
		exit();
	}
foreach ($cause_id as $question){
	
	if($id==""){
	if($_POST['an']!=""){
		$field="an";
		$str=$_POST['an'];
		}
	if($_POST['vn']!=""){
		$field="vn";		
		$str=$_POST['vn'];
		}
	
	mysql_select_db($database_hos, $hos);
	$query_insert = "insert into ".$database_kohrx.".kohrx_due_record (icode,".$field.",doctor,use_cause_id,remark) value ('$icode','".$str."','$doctor','$question','$remark')";
	$rs_insert = mysql_query($query_insert, $hos) or die(mysql_error());
	
	//insert replicate_log		
mysql_select_db($database_hos, $hos);
$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_due_record (icode,".$field.",doctor,use_cause_id,remark) value (\'".$icode."\',\'".$str."\',\'".$doctor."\',\'".$question."\',\'".$remark."\')')";
$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());

	}
	else{
	mysql_select_db($database_hos, $hos);
	$query_update = "update ".$database_kohrx.".kohrx_due_record  set use_cause_id='$question' ,remark='$remark' where id='$id'";
	$rs_update = mysql_query($query_update, $hos) or die(mysql_error());

	//insert replicate_log		
mysql_select_db($database_hos, $hos);
$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_due_record  set use_cause_id=\'".$question."\' ,remark=\'".$remark."\' where id=\'".$id."\'')";
$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());
	
	}
}
echo "<script>parent.$.fn.colorbox.close();</script>";
exit();

}

	if($_GET['an']!=""){
		$field1="an";
		$str1=$_GET['an'];
		}
	if($_GET['vn']!=""){
		$field1="vn";		
		$str1=$_GET['vn'];
		}

mysql_select_db($database_hos, $hos);
$query_rs_drug = "select c.use_cause,d.icode,concat(d.name,d.strength) as drugname,c.id from drugitems d left outer join ".$database_kohrx.".kohrx_due_cause c on c.icode=d.icode where d.icode='$icode'";
$rs_drug = mysql_query($query_rs_drug, $hos) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);

mysql_select_db($database_hos, $hos);
$query_rs_search = "select * from ".$database_kohrx.".kohrx_due_record where ".$field1."='".$str1."' and icode='$icode'";
$rs_search = mysql_query($query_rs_search, $hos) or die(mysql_error());
$row_rs_search = mysql_fetch_assoc($rs_search);
$totalRows_rs_search = mysql_num_rows($rs_search);


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
<nav class="navbar navbar-dark bg-info text-white " style="padding-bottom: 10px; height: 50px;" >
  <!-- Navbar content -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <i class="fas fa-list-alt font20"></i>&ensp;บันทึก Drug DUE
	  </li>
  </ul>
	
</nav>	
<div class="p-3" style="overflow:scroll;overflow-x:hidden;overflow-y:auto; height:90vh;">	
<p><span class="big_black16">สั่งใช้</span> <span class="big_red16"><?php echo $row_rs_drug['drugname']; ?></span> </p>
<form id="form1" name="form1" method="post" action="">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
       <?php do { ?>
 <tr>
        <td><input <?php if (!(strcmp($row_rs_search['use_cause_id'],$row_rs_drug['id']))) {echo "checked=\"checked\"";} ?> name="cause_id[]" type="radio" id="cause_id[]" value="<?php echo $row_rs_drug['id']; ?>" />
        &nbsp; <span class="font14"><?php echo $row_rs_drug['use_cause']; ?></span></td>
      </tr>    <?php } while ($row_rs_drug = mysql_fetch_assoc($rs_drug)); ?>

  </table>
    <p>หมายเหตุ
      <input name="vn" type="hidden" id="vn" value="<?php echo $_GET['vn']; ?>" />
      <input name="icode" type="hidden" id="icode" value="<?php echo $_GET['icode']; ?>" />
      <input name="doctor" type="hidden" id="doctor" value="<?php echo $_GET['doctor']; ?>" />
      <input name="id" type="hidden" id="id" value="<?php echo $row_rs_search['id']; ?>" />
      <input name="an" type="hidden" id="an" value="<?php echo $_GET['an']; ?>" />
      <br />
      <textarea name="remark" id="remark" cols="90%" rows="5"><?php echo $row_rs_search['remark']; ?></textarea>
    </p>
    <p>
      <input type="submit" name="save" id="save" value="บันทึก" class="btn btn-primary" />
      <?php if($totalRows_rs_search<>0){ ?><input type="submit" name="delete" id="delete" value="ลบ" class="btn btn-danger" onclick="return confirm('ต้องการลบรายการนี้จริงหรือไม่?');" /><?php } ?>
    </p>
</form>
</div>	
</body>
</html>
<?php
mysql_free_result($rs_drug);

mysql_free_result($rs_search);
?>
